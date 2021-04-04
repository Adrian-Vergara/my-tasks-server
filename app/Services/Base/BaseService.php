<?php


namespace App\Services\Base;


use App\Entities\Message;
use App\Traits\ApiResponse;
use App\Traits\GeneralLogic;
use Illuminate\Container\Container as Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;

abstract class BaseService
{

    use ApiResponse, GeneralLogic;

    protected $model;
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    abstract public function getFieldsSearchable();

    abstract public function model();

    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new \Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    public function allQuery($search = [], $page = null, $limit = null)
    {
        $query = $this->model->newQuery();

        if (count($search)) {
            foreach ($search as $key => $value) {
                if (in_array($key, $this->getFieldsSearchable())) {
                    $query->where($key, $value);
                }
            }
        }

        $query->when(!is_null($page), function ($q) use ($page, $limit) {
            $skip = $this->calculateSkip($page, $limit);
            $q->skip($skip);
        })
            ->when(!is_null($limit), function ($q) use ($limit) {
                $q->limit($limit);
            });

        return $query;
    }

    public function getAll($page = null, $limit = null, $columns = ['*'], $search = [])
    {
        $query = $this->allQuery($search, $page, $limit);

        $data = $query->get($columns);

        if (count($data) == 0) {
            return $this->errorResponse(
                Message::$error_query,
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->successResponse('', $data);
    }

    public function create($input)
    {
        $model = $this->model->newInstance($input);

        if (!$model->save()) {
            return $this->errorResponse(
                Message::$error_register,
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return $this->successResponse(Message::$success_register, $model, Response::HTTP_CREATED);
    }

    public function find($id, $columns = ['*'])
    {
        $query = $this->model->newQuery();

        $data = $query->find($id, $columns);

        if (empty($data)) {
            return $this->errorResponse(Message::$error_query, Response::HTTP_NOT_FOUND);
        }

        return $this->successResponse('', $data);

    }

    public function update($id, $input)
    {
        $query = $this->model->newQuery();

        $model = $query->findOrFail($id);


        $storageName = $model->getTable();
        $nameAttribute = $model->image ? 'image' : 'photo';
        $file = $model[$nameAttribute];

        $model->fill($input);

        if (!$model->save()) {
            return $this->errorResponse(
                Message::$error_update,
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        $model->wasChanged($nameAttribute) ? $this->deleteFile($storageName, $file) : null;
        return $this->successResponse(Message::$success_update, $model);
    }

    public function delete($id)
    {
        $query = $this->model->newQuery();

        $model = $query->findOrFail($id);

        if (!$model->delete()) {
            return $this->errorResponse(Message::$error_delete, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->successResponse(Message::$success_delete);
    }
}
