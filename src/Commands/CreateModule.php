<?php

declare(strict_types=1);

namespace SebastiaanLuca\Module\Commands;

use Illuminate\Console\Command;

class CreateModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'modules:create
                            {name : The studly cased name of the module}
                            {--directory= : The directory in the root project to create the module in}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() : void
    {
        $name = studly_case($this->argument('name'));

        $moduleDir = $this->option('directory') ?? head(config('module-loader.paths'));

        if (! $moduleDir) {
            $this->error('No module directories configured nor any explicitly given!');

            return;
        }

        $path = base_path(sprintf(
            '%s/%s/src/Providers',
            $moduleDir,
            $name
        ));

        mkdir($path, 0777, true);

        $provider = file_get_contents(__DIR__ . '/../../resources/stubs/DummyServiceProvider.php');

        $provider = str_replace('Dummy', $name, $provider);

        file_put_contents("{$path}/{$name}ServiceProvider.php", $provider);

        $this->info(sprintf(
            'Module %s created!',
            $name
        ));
    }
}
