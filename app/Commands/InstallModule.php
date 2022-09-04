<?php

namespace App\Commands;

use App\Commands\Traits\Authenticatable;
use App\Genesis\Actions\CreateDirectoryRecursively;
use App\Genesis\Actions\GetModule;
use App\Genesis\Actions\GetModules;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use LaravelZero\Framework\Commands\Command;

class InstallModule extends Command
{
    use Authenticatable;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'module {module?} {version?}';

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
        $this->authenticateOrFail();

        $modules = GetModules::run();

        $identifier = $this->argument('module');
        if (is_null($identifier)) {
            $identifier = $this->anticipate('Which module do you want to install?', collect($modules)->pluck('identifier')->toArray());
        }

        $module = collect($modules)->firstWhere('identifier', $identifier);
        if (is_null($module)) {
            $this->error('Module not found.');
            return;
        }

        $version = $this->argument('version');
        if (is_null($version)) {
            $version = $this->anticipate('Which version?', $module->versionSummary);
        }

        if (! in_array($version, $module->versionSummary)) {
            $this->error('Version not found.');
            return;
        }

        $module = GetModule::run($identifier, $version);
        $version = $module->versions[0];
        $files = $version->files();

        $this->info('The following files will be installed:');
        $this->line(collect($files)->pluck('path')->join("\n"));

        if (! empty($version->commands)) {
            $this->info('And the following commands will run:');
            $this->line(collect($version->commands)->join("\n"));
        }

        if (! $this->confirm('Are you sure you want to continue?')) {
            return;
        }

        $this->info("Installing {$module->identifier}@{$version->version}");

        foreach ($files as $file) {
            $this->line("Installing {$file['path']}");
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

        $this->info('🚀 Module installed successfully!');
    }
}
