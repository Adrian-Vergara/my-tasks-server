<?php

namespace App\Services\User;

interface RoleInterface
{
    public function getAll();

    public function create($data);

    public function update($id, $data);

    public function delete($id);
}
