<?php

namespace App\Services;

class GenesisConfig {
    public function __construct(
        public readonly ?string $token,
    ) { }

    public static function from(array $config)
    {
        return new GenesisConfig(
            token: $config['token'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'token' => $this->token,
        ];
    }
}
