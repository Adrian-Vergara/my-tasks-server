<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\DestroyProject;
use App\Http\Requests\Project\StoreProject;
use App\Http\Requests\Project\UpdateProject;
use App\Models\Project\Project;
use App\Services\Project\ProjectInterface;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    private $project;

    public function __construct(ProjectInterface $project)
    {
        $this->middleware('auth:api');
        $this->project = $project;
    }

    public function index()
    {
        return $this->jsonResponse($this->project->all());
    }

    public function show($id)
    {
        return $this->jsonResponse($this->project->getById($id));
    }

    public function store(StoreProject $request)
    {
        return $this->jsonResponse($this->project->create($request->all()));
    }

    public function update(UpdateProject $request, $id)
    {
        return $this->jsonResponse($this->project->update($id, $request->only(['name', 'description', 'end_date'])));
    }

    public function finishProject($id)
    {
        return $this->jsonResponse($this->project->finishProject($id));
    }

    public function destroy(DestroyProject $request, $id)
    {
        return $this->jsonResponse($this->project->delete($id));
    }

}
