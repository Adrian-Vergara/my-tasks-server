<?php

namespace App\Services\Project;

interface ProjectInterface
{
    public function all();

    public function getById($id);

    public function create($data);

    public function update($id, $data);

    public function finishProject($id);

    public function delete($id);
}
