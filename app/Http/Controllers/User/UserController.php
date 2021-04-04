<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUser;
use App\Http\Requests\User\UpdatePassword;
use App\Services\User\UserInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $user;

    public function __construct(UserInterface $user)
    {
        $this->middleware('auth:api');
        $this->user = $user;
    }

    public function index(){
        return $this->jsonResponse($this->user->all());
    }

    public function store(StoreUser $request)
    {
        return $this->jsonResponse($this->user->create($request->all()));
    }

    public function updatePassword(UpdatePassword $request)
    {
        return $this->jsonResponse($this->user->updatePassword($request->all()));
    }

    public function enableAndDisable($id)
    {
        return $this->jsonResponse($this->user->enableAndDisable($id));
    }
}
