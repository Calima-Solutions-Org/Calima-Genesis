<?php

namespace App\Genesis;

class ModuleVersion
{
    public function __construct(
        public readonly string $version,
        public readonly ?string $repository,
        public readonly array $files,
        public readonly array $commands,
        public readonly ?string $readme,
    ) {
    }

    public static function from(array $data, ?string $readme, array $files): self
    {
        return new self(
            $data['version'],
            $data['gh_repo'] ?? null,
            $files,
            $data['commands'] ?? [],
            $readme ?? null,
        );
    }

    public function files(?array $folder = null): array
    {
        $folder = $folder ?? collect(array_values($this->files))->flatten(1)->toArray();
        $files = [];
        foreach ($folder as $file) {
            if (isset($file['downloadUrl'])) {
                $files[] = $file;
            } elseif (isset($file['files'])) {
                $files = [
                    ...$files,
                    ...$file['files'],
                ];
            } elseif (isset($file['folders'])) {
                $files = [
                    ...$files,
                    ...$this->files($file['folders']),
                ];
            }
        }

        return $files;
    }
}
