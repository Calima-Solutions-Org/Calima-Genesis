<?php

namespace App\Commands;

use App\Commands\Traits\Authenticatable;
use App\Genesis\Actions\CreateDirectoryRecursively;
use App\Genesis\Actions\DownloadFileTemplates;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;

class CopyFileTemplates extends Command
{
    use Authenticatable;

    protected $hidden = true;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'copy-templates
                            { templates* : The file templates to copy and the path where they should be stored. It should follow the following format: "template_name:path/to/file.ext" }';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Downloads file templates from Genesis and copies them to the specified path.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->authenticateOrFail();

        $templateMap = collect($this->argument('templates'))
            ->map(fn ($template) => explode(':', $template))
            ->mapWithKeys(fn ($template) => [
                $template[0] => $template[1],
            ])
            ->toArray();

        $this->info('Downloading templates...');
        $templates = DownloadFileTemplates::run(array_keys($templateMap));
        foreach ($templates as $template) {
            $path = trim($templateMap[$template->identifier], '/\\');
            $this->info('Copying '.$template->identifier.' to '.$path);
            CreateDirectoryRecursively::run($path);
            File::put($path, $template->content);
        }
    }
}
