<?php

namespace App\Services\Task;

use App\Entities\Message;
use App\Models\Task\Task;
use App\Services\Base\BaseService;
use App\Traits\ApiResponse;
use Illuminate\Container\Container as Application;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskService extends BaseService implements TaskInterface
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
        return Task::class;
    }

    public function countTasksByProjectAndGreaterThanDate($project_id, $date)
    {
        return Task::where('project_id', $project_id)
            ->where('execution_date', '>', $date)
            ->count();
    }

    public function countTasksByProjectAndStatus($project_id, $status)
    {
        return Task::where('project_id', $project_id)
            ->where('status', $status)
            ->count();
    }

    public function getAllByProject($project_id)
    {
        $tasks = Task::with('user', 'statuses.user')
            ->where('project_id', $project_id)
            ->get();
        if (count($tasks) == 0) {
            return $this->errorResponse('No hay tareas vinculadas al proyecto seleccionado', Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse('', $tasks);
    }

    public function getById($id)
    {
        $task = Task::with('project.user', 'user', 'statuses.user')->find($id);
        if (empty($task)) {
            return $this->errorResponse('La tarea que desea consultar no existe', Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse('', $task);
    }

    public function create($data)
    {
        try {
            DB::beginTransaction();

            $validate = (new TaskValidationService($this->app))->validationToCreate($data);
            if (!$validate->success) {
                return $validate;
            }

            $task = new Task($data);
            if (!$task->save()) {
                DB::rollBack();
                return $this->errorResponse(Message::$error_register, Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $statusObject = array("user_id" => $data["user_id"]);
            $task->statuses()->create($statusObject);
            DB::commit();
            return $this->successResponse(Message::$success_register, $this->getById($task->id)->content, Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(Message::$error_register, Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public function update($id, $data)
    {
        try {
            $task = Task::find($id);

            $validate = (new TaskValidationService($this->app))->validationToUpdate($task, $data);
            if (!$validate->success) {
                return $validate;
            }

            $task->fill($data);
            if (!$task->save()) {
                return $this->errorResponse(Message::$error_update, Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->successResponse(Message::$success_update, $this->getById($id)->content);
        } catch (\Exception $e) {
            return $this->errorResponse(Message::$error_update, Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public function finishTask($id)
    {
        try {
            DB::beginTransaction();
            $task = Task::find($id);

            $validate = (new TaskValidationService($this->app))->validateToFinishTask($task);
            if (!$validate->success) {
                return $validate;
            }

            $task->status = config('app.statuses.finalized');
            if (!$task->save()) {
                DB::rollBack();
                return $this->errorResponse(Message::$error_update, Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $objectStatus = array("user_id" => Auth::id(), "status" => $task->status);
            $task->statuses()->create($objectStatus);
            DB::commit();
            return $this->successResponse('La tarea se marcó como finalizado con exito', $this->getById($id)->content);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(Message::$error_update, Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $task = Task::find($id);

            if (count($task->statuses) > 0 && !$task->statuses()->delete()) {
                return $this->errorResponse(Message::$error_delete, Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            if (!$task->delete()) {
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
