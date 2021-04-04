<?php

namespace App\Services\User;

interface UserInterface
{
    public function all();

    public function authenticate($data);

    public function create($data);

    public function updatePassword($data);

    public function enableAndDisable($id);
}
