<?php

namespace App\Services\User;

use App\Entities\Message;
use App\Mail\SendAuthCredential;
use App\Models\User;
use App\Services\Base\BaseService;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Container\Container as Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserService extends BaseService implements UserInterface
{
    use ApiResponse;

    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    protected $fieldSearchable = [];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return User::class;
    }

    public function all()
    {
        $users = User::with('role')->get();
        if (count($users) == 0) {
            return $this->errorResponse(Message::$error_query, Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse('', $users);
    }

    public function getById($id)
    {
        return User::with('role')->find($id);
    }

    public function getAllEmailsByRole($role_name)
    {
        return User::join('roles', 'users.role_id', '=', 'roles.id')
            ->where('roles.name', $role_name)
            ->select('users.email')
            ->get();
    }

    public function authenticate($data)
    {
        $user = User::with('role')->where('email', $data["email"])
            ->first();

        if (empty($user)) {
            return $this->errorResponse(Message::$invalid_credentials, Response::HTTP_UNAUTHORIZED);
        }

        if (!$user->active) {
            return $this->errorResponse('El usuario se encuentra inactivo.', Response::HTTP_UNAUTHORIZED);
        }

        if (!Hash::check($data["password"], $user->password)) {
            return $this->errorResponse(
                Message::$invalid_credentials,
                Response::HTTP_UNAUTHORIZED
            );
        }

        return $this->buildToken($user);
    }

    public function create($data)
    {
        try {
            $real_password = $data["password"];
            $user = new User($data);
            if (!$user->save()) {
                return $this->errorResponse(Message::$error_register, Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $user = $this->getById($user->id);
            $user->real_password = $real_password;
            Mail::send(new SendAuthCredential($user));
            unset($user->real_password);
            return $this->successResponse(Message::$success_register, $user);
        } catch (\Exception $e) {
            return $this->errorResponse(Message::$error_register, Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public function updatePassword($data)
    {
        $user = User::find(Auth::id());
        if (!Hash::check($data["old_password"], $user->password)) {
            return $this->errorResponse(
                "La contraseña es incorrecta.",
                Response::HTTP_BAD_REQUEST
            );
        }
        $user->password = $data["new_password"];
        if (!$user->save()) {
            return $this->errorResponse(Message::$error_update, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse(Message::$success_update);
    }

    public function enableAndDisable($id)
    {
        if (Auth::id() == $id) {
            return $this->errorResponse('No puedes activar o inactivar tu mismo usuario.', Response::HTTP_BAD_REQUEST);
        }

        $user = User::find($id);
        if (empty($user)) {
            return $this->errorResponse('El usuario al que desea activar/inactivar no existe.', Response::HTTP_BAD_REQUEST);
        }

        $user->active = !$user->active;
        if (!$user->save()) {
            return $this->errorResponse(Message::$error_update, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $action = $user->active ? 'activado' : 'inactivado';
        return $this->successResponse("El usuario ha sido {$action} exitosamente.");
    }

    public function logout($token)
    {
        $token->revoke();
        return $this->successResponse('Sesión cerrada.');
    }

    private function buildToken($user)
    {
        $token_result = $user->createToken('Personal Access Token', [$user->role->name]);
        return $this->successResponse(
            '',
            array(
                "user" => $user,
                "token" => array(
                    "access_token" => $token_result->accessToken,
                    "token_type" => "Bearer",
                    "expires_at" => Carbon::parse($token_result->token->expires_at)->toDateTimeString()
                )
            )
        );
    }
}
