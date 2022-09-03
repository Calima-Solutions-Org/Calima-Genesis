<?php

namespace App\Genesis;

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

    public function validateToken(): bool
    {
        $response = $this->client()->get('/me');
        return $response->status() === 200;
    }

    public function getProjectType(string $name): ?ProjectType
    {
        $response = $this->client()->get('/project-type/' . $name);
        $response = $response->json()['project_type'] ?? null;
        return $response ? ProjectType::from($response) : null;
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
