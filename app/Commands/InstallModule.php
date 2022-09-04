<?php

namespace App\Commands;

use App\Genesis\Actions\CreateDirectoryRecursively;
use App\Genesis\Actions\GetModule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use LaravelZero\Framework\Commands\Command;

class InstallModule extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'module {module} {version}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Installs a module into your codebase.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $module = GetModule::run($this->argument('module'), $this->argument('version'));
        if (empty($module?->versions)) {
            $this->error('Invalid versions or module.');

            return;
        }

        $version = $module->versions[0];
        $this->info("Installing {$module->identifier}@{$version->version}");

        $files = $version->files();
        foreach ($files as $file) {
            $this->info("Installing {$file['path']}", LOG_DEBUG);
            CreateDirectoryRecursively::run($file['path']);
            $contents = Http::get($file['downloadUrl'])->body();
            File::put($file['path'], $contents);
        }

        if (! empty($version->commands)) {
            $this->info('Executing commands...');
            foreach ($version->commands as $command) {
                $this->call('run', [
                    'signature' => $command,
                ]);
            }
        }
    }
}
