<?php


namespace App\Services\Task;


use App\Services\Project\ProjectService;
use App\Traits\ApiResponse;
use Illuminate\Container\Container as Application;
use Illuminate\Http\Response;

class TaskValidationService
{
    use ApiResponse;

    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function validationToCreate($data)
    {
        $projectExist = $this->projectExist($data["project_id"]);
        if (!$projectExist->success) {
            return $projectExist;
        }

        $project = $projectExist->content;
        if ($project->status == config('app.statuses.finalized')) {
            return $this->errorResponse('La tarea no puede ser registrada debido a que el proyecto ha sido finalizado.', Response::HTTP_BAD_REQUEST);
        }

        $validateExecutionDate = $this->validateExecutionDate($project, $data["execution_date"]);
        if (!$validateExecutionDate->success) {
            return $validateExecutionDate;
        }

        return $this->successResponse();
    }

    public function validationToUpdate($task, $data)
    {
        if (empty($task)) {
            return $this->errorResponse('La tarea que desea actualizar no existe', Response::HTTP_BAD_REQUEST);
        }

        //Si la fecha de ejecución se va a actualizar, entonces validamos que el rango de fechas esté dentro de las fechas del proyecto
        if (!empty($data["execution_date"])) {

            //Si la tarea está finalizada restringimos la actualización de este campo
            if ($task->status == config('app.statuses.finalized')) {
                return $this->errorResponse('La fecha de ejecución no puede ser actualizada debido a que la tarea está finalizada.', Response::HTTP_BAD_REQUEST);
            }

            $project = (new ProjectService($this->app))->getById($task->project_id)->content;
            $validateExecutionDate = $this->validateExecutionDate($project, $data["execution_date"]);
            if (!$validateExecutionDate->success) {
                return $validateExecutionDate;
            }
        }

        return $this->successResponse();
    }

    public function validateToFinishTask($task)
    {
        if (empty($task)) {
            return $this->errorResponse('La tarea que desea marcar como finalizada no existe', Response::HTTP_BAD_REQUEST);
        }

        if ($task->status == config('app.statuses.finalized')) {
            return $this->errorResponse('La tarea ya se encuentra finalizada.', Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse();
    }

    private function projectExist($project_id)
    {
        $getProject = (new ProjectService($this->app))->getById($project_id);
        if (!$getProject->success) {
            return $this->errorResponse('El proyecto al que desea vincular la tarea no existe.', Response::HTTP_BAD_REQUEST);
        }
        return $this->successResponse('', $getProject->content);
    }

    private function validateExecutionDate($project, $execution_date)
    {
        if ($execution_date < $project->start_date || $execution_date > $project->end_date) {
            return $this->errorResponse("La fecha de ejecución de la tarea está por fuera del rango de fechas del proyecto ({$project->start_date} - {$project->end_date}).", Response::HTTP_BAD_REQUEST);
        }
        return $this->successResponse();
    }

}
