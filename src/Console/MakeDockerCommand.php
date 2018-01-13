<?php

namespace Csgt\Components\Console;

use Illuminate\Console\Command;

class MakeDockerCommand extends Command
{
    protected $signature   = 'make:csgtdocker';
    protected $description = 'Crear configuraciones Docker';
    protected $directories = [
        'dockerfiles'
    ];
    protected $files = [
        'docker/docker-compose.yml.example.stub' => 'docker-compose.yml.example',
        'docker/docker-compose.yml.stub'         => 'docker-compose.yml',
        'docker/dockerfiles/php.docker.stub'     => 'dockerfiles/php.docker',
        'docker/dockerfiles/nginx.docker.stub'   => 'dockerfiles/nginx.docker',
        'docker/dockerfiles/vhost.conf.stub'     => 'dockerfiles/vhost.conf',
    ];

    public function handle()
    {
        if (is_dir(base_path('dockerfiles'))) {
            $this->error('Configuraciones docker ya fueron generadas anteriormente.');
            return;
        }
        $this->createDirectories();
        $this->exportFiles();
        $this->info('Configuraciónes docker generadas correctamente.');
    }

    protected function createDirectories()
    {
        foreach ($this->directories as $directory) {
            if (! is_dir(base_path($directory))) {
                mkdir(base_path($directory), 0755, true);
            }
        }
    }

    protected function exportFiles()
    {
        foreach ($this->files as $origin => $destination) {
            copy(
                __DIR__.'/stubs/make/'.$origin,
                base_path($destination)
            );
        }
    }
}
