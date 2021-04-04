<?php

namespace Database\Seeders;

use App\Models\User\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array("name" => "Administrador"),
            array("name" => "Operador")
        );

        foreach ($data as $item) {
            if (!$this->roleExist($item["name"])) {
                Role::create($item);
            }
        }

    }

    private function roleExist($name): bool
    {
        return !empty(Role::where('name', $name)->first());
    }
}
