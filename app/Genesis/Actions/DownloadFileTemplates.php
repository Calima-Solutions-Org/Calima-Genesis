<?php

namespace App\Genesis\Actions;

use App\Genesis\FileTemplate;
use App\Genesis\Genesis;

class DownloadFileTemplates {
    use AsAction;

    public function __invoke(array $files)
    {
        $genesis = new Genesis();
        $response = $genesis->client()->get('/file-templates', [
            'files' => $files,
        ])->json();

        return array_map(fn ($template) => FileTemplate::from($template), $response['files'] ?? []);
    }
}
