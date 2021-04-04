<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\User\Role;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = $this->getRoleAdmin();
        $data = array(
            "name" => "Adrian",
            "last_name" => "Vergara",
            "phone" => "123456",
            "address" => "Calle 11",
            "email" => "adrianvergara22@gmail.com",
            "password" => "qaz123",
            "role_id" => $role->id
        );
        if (!$this->emailExist($data["email"])) {
            User::create($data);
        }

    }

    private function emailExist($email): bool
    {
        return !empty(User::where('email', $email)->first());
    }

    private function getRoleAdmin(): Role
    {
        return Role::where('name', "Administrador")->first();
    }
}
