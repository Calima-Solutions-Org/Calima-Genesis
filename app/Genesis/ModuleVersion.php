<?php

namespace App\Genesis;

class ModuleVersion {
    public function __construct(
        public readonly string $version,
        public readonly ?string $repository,
        public readonly array $files,
        public readonly array $commands,
    ) {

    }

    public static function from(array $data): self {
        return new self(
            $data['version'],
            $data['gh_repo'] ?? null,
            $data['files'] ?? [],
            $data['commands'] ?? [],
        );
    }
}
