<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class MakeService extends GeneratorCommand
{

    protected $name = 'make:service {name}';

    protected $description = 'Create a new service';

    private $directory;

    private function getDirectoryName()
    {
        $array_name = explode("/", $this->argument('name'));
        unset($array_name[count($array_name) - 1]);

        $this->directory = "";
        if (count($array_name) > 0) {
            $count = 1;
            foreach ($array_name as $item) {
                if ($count == 1) {
                    $this->directory = $item;
                } else {
                    $this->directory = $this->directory . "\\" . $item;
                }
                $count++;
            }
        }
    }

    private function getServiceName()
    {
        $explode_name = explode("/", $this->argument('name'));
        return end($explode_name);
    }

    private function getInterfaceName($service_name)
    {
        return $this->getOnlyName($service_name) . "Interface";
    }

    private function getOnlyName($service_name)
    {
        return explode("Service", $service_name)[0];
    }

    private function replaceServiceProvider($interface_name, $service_name)
    {
        $data = file_get_contents(__DIR__ . './../../Providers/AppServiceProvider.php');

        $searchProvider = ["#DummyPathInterface", "#DummyPathService", "#DummyBind"];
        $this->getDirectoryName();
        $replaceProvider = [
            empty($this->directory)
                ? "use App\\Services\\$interface_name;"
                : "use App\\Services\\{$this->directory}\\$interface_name;",
            empty($this->directory)
                ? "use App\\Services\\$service_name;"
                : "use App\\Services\\$this->directory\\$service_name;

            #DummyPathInterface
            #DummyPathService",

            "$" . "this->app->bind(
            $interface_name::class,
            $service_name::class
        );

        #DummyBind"];
        $data = str_replace($searchProvider, $replaceProvider, $data);
        file_put_contents(__DIR__ . './../../Providers/AppServiceProvider.php', $data);
    }

    private function executeCommandInterface()
    {
        $explode_name = explode("Service", $this->argument('name'));
        $name = "{$explode_name[0]}Interface";
        exec("php artisan make:interfaceService $name");
    }

    protected function replaceClass($stub, $name)
    {
        $service_name = $this->getServiceName();
        $interface_name = $this->getInterfaceName($service_name);
        $only_name = $this->getOnlyName($service_name);
        $this->executeCommandInterface();
        $stub = parent::replaceClass($stub, $name);
        $this->replaceServiceProvider($interface_name, $service_name);

        $search = ["DummyService", "DummyPathModel", "DummyModel"];
        $replace = [
            "$service_name extends BaseService implements $interface_name",
            empty($this->directory)
                ? "use App\Models\\$only_name;"
                : "use App\Models\\{$this->directory}\\$only_name;",
            "return $only_name::class;"
        ];

        return str_replace($search, $replace, $stub);
    }

    protected function getStub()
    {
        return app_path() . '/Console/Commands/Stubs/make-service.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . "\Services";
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'El nombre del service es obligatorio.'],
        ];
    }
}
