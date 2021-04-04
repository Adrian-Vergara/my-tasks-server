<?php

namespace App\Services\Project;

use App\Entities\Message;
use App\Mail\FinishedProject;
use App\Models\Project\Project;
use App\Services\Base\BaseService;
use App\Services\User\UserService;
use App\Traits\ApiResponse;
use Illuminate\Container\Container as Application;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ProjectService extends BaseService implements ProjectInterface
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
        return Project::class;
    }

    public function all()
    {
        $projects = Project::with('user', 'statuses.user', 'tasks.user')->get();
        if (empty($projects)) {
            return $this->errorResponse(Message::$error_query, Response::HTTP_NOT_FOUND);
        }

        return $this->successResponse('', $projects);
    }

    public function getById($id)
    {
        $project = Project::with('user', 'statuses.user', 'tasks.user')->find($id);
        if (empty($project)) {
            return $this->errorResponse('El proyecto que desea consultar no existe.', Response::HTTP_NOT_FOUND);
        }

        return $this->successResponse('', $project);
    }

    public function create($data)
    {
        try {
            DB::beginTransaction();

            $project = new Project($data);
            if (!$project->save()) {
                DB::rollBack();
                return $this->errorResponse(Message::$error_register, Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $statusObject = array("user_id" => $data["user_id"]);
            $project->statuses()->create($statusObject);
            DB::commit();
            return $this->successResponse(Message::$success_register, $this->getById($project->id)->content, Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(Message::$error_register, Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public function update($id, $data)
    {
        try {
            $project = Project::find($id);

            $validate = (new ProjectValidationService($this->app))->validateToUpdate($project, $data);
            if (!$validate->success) {
                return $validate;
            }

            $project->fill($data);
            if (!$project->save()) {
                return $this->errorResponse(Message::$error_update, Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->successResponse(Message::$success_update, $this->getById($id)->content);
        } catch (\Exception $e) {
            return $this->errorResponse(Message::$error_update, Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public function finishProject($id)
    {
        try {
            DB::beginTransaction();
            $project = Project::find($id);

            $validate = (new ProjectValidationService($this->app))->validateToFinishProject($project);
            if (!$validate->success) {
                return $validate;
            }

            $project->status = config('app.statuses.finalized');
            if (!$project->save()) {
                DB::rollBack();
                return $this->errorResponse(Message::$error_update, Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $objectStatus = array("user_id" => Auth::id(), "status" => $project->status);
            $project->statuses()->create($objectStatus);
            DB::commit();
            $array_admin_emails = (new UserService($this->app))->getAllEmailsByRole(config('app.roles.admin'));
            $data = array(
                "project" => $project,
                "operator" => Auth::user()
            );
            Mail::send(new FinishedProject($data, $array_admin_emails));
            return $this->successResponse('El proyecto se marcó como finalizado con exito', $this->getById($id)->content);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(Message::$error_update, Response::HTTP_INTERNAL_SERVER_ERROR, $e->getTraceAsString());
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $project = Project::find($id);

            if (count($project->statuses) > 0 && !$project->statuses()->delete()) {
                return $this->errorResponse(Message::$error_delete, Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            if (count($project->tasks) > 0 && !$project->tasks()->delete()) {
                DB::rollBack();
                return $this->errorResponse(Message::$error_delete, Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            if (!$project->delete()) {
                DB::rollBack();
                return $this->errorResponse(Message::$error_delete, Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            DB::commit();
            return $this->successResponse(Message::$success_delete);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(Message::$error_delete, Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

}
