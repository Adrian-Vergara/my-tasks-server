<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\DestroyTask;
use App\Http\Requests\Task\StoreTask;
use App\Http\Requests\Task\UpdateTask;
use App\Services\Task\TaskInterface;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    private $task;

    public function __construct(TaskInterface $task)
    {
        $this->middleware('auth:api');
        $this->task = $task;
    }

    public function getAllByProject($project_id)
    {
        return $this->jsonResponse($this->task->getAllByProject($project_id));
    }

    public function show($id)
    {
        return $this->jsonResponse($this->task->getById($id));
    }

    public function store(StoreTask $request, $project_id)
    {
        return $this->jsonResponse($this->task->create($request->all()));
    }

    public function update(UpdateTask $request, $id)
    {
        return $this->jsonResponse($this->task->update($id, $request->only(['name', 'description', 'execution_date'])));
    }

    public function finishTask($id)
    {
        return $this->jsonResponse($this->task->finishTask($id));
    }

    public function destroy(DestroyTask $request, $id)
    {
        return $this->jsonResponse($this->task->delete($id));
    }
}
