<?php


namespace App\Services\Project;


use App\Services\Task\TaskService;
use App\Traits\ApiResponse;
use Illuminate\Http\Response;
use Illuminate\Container\Container as Application;

class ProjectValidationService
{
    use ApiResponse;

    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }


    public function validateToUpdate($project, $data)
    {
        //Esta validación me pareció algo coherente, no está especificada en los requerimientos, es por ello que la comenté
        /*if ($project->status == config('app.statuses.finalized') && !empty($data["end_date"])) {
            return $this->errorResponse('La fecha de finalización del proyecto no puede ser actualizada porque el proyecto está finalizado',
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }*/

        if (!empty($data["end_date"])) {

            if ($data["end_date"] <= $project->start_date) {
                return $this->errorResponse('Verifique que la fecha de finalización diligenciada sea mayor a la fecha de inicio del proyecto.', Response::HTTP_BAD_REQUEST);
            }

            $countTasks = (new TaskService($this->app))->countTasksByProjectAndGreaterThanDate($project->id, $data["end_date"]);
            if ($countTasks > 0) {
                return $this->errorResponse("La fecha de finalización del proyecto no puede ser actualizada debido a que tiene {$countTasks} tareas con fecha de ejecución mayor a la fecha especificada.",
                    Response::HTTP_BAD_REQUEST);
            }
        }

        return $this->successResponse();
    }

    public function validateToFinishProject($project)
    {

        if (empty($project)) {
            return $this->errorResponse('El proyecto que desea marcar como finalizado no existe', Response::HTTP_BAD_REQUEST);
        }

        if ($project->status == config('app.statuses.finalized')) {
            return $this->errorResponse('El proyecto ya se encuentra finalizado.', Response::HTTP_BAD_REQUEST);
        }

        $countTasks = (new TaskService($this->app))->countTasksByProjectAndStatus($project->id, config('app.statuses.in_process'));
        if ($countTasks > 0) {
            return $this->errorResponse("El proyecto no se puede marcar como finalizado debido a que tiene {$countTasks} tareas en proceso", Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse();
    }

}
