<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeService extends Command
{
    protected $signature = 'make:service {name}';
    protected $description = 'Crea un nuevo servicio en app/Services';

    public function handle()
    {
        $name = $this->argument('name');
        $path = app_path("Services/{$name}.php");

        if (File::exists($path)) {
            $this->error("El servicio {$name} ya existe.");
            return;
        }

        File::ensureDirectoryExists(app_path('Services'));

        File::put($path, $this->getStub($name));

        $this->info("Servicio {$name} creado correctamente en app/Services/");
    }

    protected function getStub($name)
    {
        return "<?php

namespace App\Services;

class {$name}
{
    //
}";
    }
}
