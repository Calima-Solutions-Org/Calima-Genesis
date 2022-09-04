<?php

namespace App\Genesis;

class Module
{
    public function __construct(
        public readonly string $identifier,
        public readonly string $name,
        public readonly ?string $description,
        public readonly array $versions,
        public readonly array $versionSummary,
    ) {
    }

    public static function from(array $module): self
    {
        return new self(
            $module['identifier'],
            $module['name'],
            $module['description'] ?? null,
            array_map(fn ($version) => ModuleVersion::from(
                $version,
                $module['readme'][$version['version']] ?? null,
                $module['files'][$version['version']] ?? [],
            ), $module['versions'] ?? []),
            $module['version_summary'] ?? [],
        );
    }
}
