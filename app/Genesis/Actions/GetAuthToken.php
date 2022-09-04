<?php

namespace App\Genesis\Actions;

use App\Exceptions\DeactivatedUser;
use App\Exceptions\InvalidCredentials;
use App\Genesis\Genesis;
use Exception;

class GetAuthToken
{
    use AsAction;

    public function __invoke(string $username, string $password)
    {
        $genesis = new Genesis();
        $response = $genesis->client()->post('/login', [
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
}
