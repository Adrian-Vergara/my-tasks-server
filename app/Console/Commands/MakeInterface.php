<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class MakeInterface extends GeneratorCommand
{
    protected $signature = 'make:interfaceService {name}';

    protected $description = 'Create a new service interface';

    private function getInterfaceName()
    {
        $explode_name = explode("/", $this->argument('name'));
        return end($explode_name);
    }

    protected function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);
        $interface_name = $this->getInterfaceName();
        return str_replace('DummyInterface', $interface_name, $stub);
    }

    protected function getStub()
    {
        return app_path() . '/Console/Commands/Stubs/make-interface.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . "\Services";
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'El nombre del service es obligatorio.']
        ];
    }
}
