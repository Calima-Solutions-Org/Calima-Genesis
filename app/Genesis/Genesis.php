<?php

namespace App\Genesis;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class Genesis {
    public static function config(?array $value = null): GenesisConfig
    {
        if (! File::exists(self::configFolder())) {
            File::makeDirectory(self::configFolder());
        }

        if ($value) {
            File::put(self::configFilePath(), json_encode($value));
        }

        if (! File::exists(self::configFilePath())) {
            return GenesisConfig::from([]);
        }

        return GenesisConfig::from(json_decode(File::get(self::configFilePath()), true) ?: []);
    }

    public static function configFolder(): string
    {
        return $_SERVER['HOME'] . '/.calima-genesis';
    }

    public static function configFilePath(): string
    {
        return self::configFolder() . '/genesis.json';
    }

    public function client(): PendingRequest
    {
        $config = self::config();
        return Http::acceptJson()
            ->baseUrl($this->endpoint())
            ->when($config->token, function (PendingRequest $client) use ($config) {
                $client->withHeaders([
                    'Authorization' => 'Bearer ' . $config->token,
                ]);
            });
    }

    private function endpoint(): string
    {
        return config('genesis.endpoint')[config('app.env')];
    }
}
