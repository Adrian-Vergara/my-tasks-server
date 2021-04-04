<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRole;
use App\Http\Requests\User\UpdateRole;
use App\Services\User\RoleInterface;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    private $role_service;

    public function __construct(RoleInterface $role_service)
    {
        $this->middleware('auth:api');
        $this->role_service = $role_service;
    }

    public function index()
    {
        return $this->jsonResponse($this->role_service->getAll());
    }

    public function store(StoreRole $request)
    {
        return $this->jsonResponse($this->role_service->create($request->all()));
    }

    public function update(UpdateRole $request, $id)
    {
        return $this->jsonResponse($this->role_service->update($id, $request->all()));
    }

    public function destroy($id)
    {
        return $this->jsonResponse($this->role_service->delete($id));
    }

}
