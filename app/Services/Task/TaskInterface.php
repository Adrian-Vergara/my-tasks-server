<?php

namespace App\Services\Task;

interface TaskInterface
{
    public function getAllByProject($project_id);

    public function getById($id);

    public function create($data);

    public function update($id, $data);

    public function finishTask($id);

    public function delete($id);
}
