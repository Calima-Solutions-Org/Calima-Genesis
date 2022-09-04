<?php

namespace App\Genesis\Actions;

use Illuminate\Support\Facades\File;

class CreateDirectoryRecursively {
    use AsAction;

    public function __invoke(string $path)
    {
        $path = str_replace('/', DIRECTORY_SEPARATOR, $path);
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
        // create the path recursively
        $path = explode(DIRECTORY_SEPARATOR, $path);
        array_pop($path);
        $accPath = [];
        foreach ($path as $folder) {
            $fullPath = trim(implode(DIRECTORY_SEPARATOR, $accPath) . DIRECTORY_SEPARATOR . $folder, DIRECTORY_SEPARATOR);
            if (! File::exists($fullPath)) {
                File::makeDirectory($fullPath);
            }

            $accPath[] = $folder;
        }
    }
}
