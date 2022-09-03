<?php

namespace App\Services;

use App\Exceptions\DeactivatedUser;
use App\Exceptions\InvalidCredentials;
use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class Genesis {
    public static function config(?array $value = null): GenesisConfig
    {
        if ($value) {
            File::put(self::configFilePath(), json_encode($value));
        }

        if (! File::exists(self::configFilePath())) {
            return GenesisConfig::from([]);
        }

        return GenesisConfig::from(json_decode(File::get(self::configFilePath()), true) ?: []);
    }

    private static function configFilePath(): string
    {
        return getcwd() . '/calima-genesis.json';
    }

    public function getAuthToken(string $username, string $password)
    {
        $response = $this->client()->post('/login', [
            'email' => $username,
            'password' => $password,
        ])->json();

        if (isset($response['token'])) {
            return $response['token'];
        }

        switch ($response['error'] ?? '') {
            case 'invalid_credentials':
                throw new InvalidCredentials();
            case 'deactivated_user':
                throw new DeactivatedUser();
            default:
                throw new Exception('Unknown error');
        }
    }

    private function client(): PendingRequest
    {
        return Http::acceptJson()
            ->withHeaders([
                'Authorization' => 'Bearer ' . config('genesis.token'),
            ])
            ->baseUrl($this->endpoint());
    }

    private function endpoint(): string
    {
        return config('genesis.endpoint')[config('app.env')];
    }
}
