<?php

namespace App\Commands;

use App\Commands\Traits\Authenticatable;
use App\Genesis\Actions\GetModules;
use LaravelZero\Framework\Commands\Command;

class ListModules extends Command
{
    use Authenticatable;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'modules';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Lists all available modules built in Genesis.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->authenticateOrFail();
        $modules = GetModules::run();
        $headings = ['Module ID', 'Name', 'Description', 'Versions'];
        $rows = collect($modules)->map(fn ($module) => [
            $module->identifier,
            $module->name,
            $module->description ?? '',
            implode(', ', $module->versionSummary),
        ])->toArray();
        $this->table($headings, $rows);

        $this->confirm('☀️ Do you want to install one of Genesis modules in your app?');
    }
}
