<?php

namespace App\Genesis\Actions;

use App\Genesis\Genesis;

class ValidateToken
{
    use AsAction;

    public function __invoke(): bool
    {
        $genesis = new Genesis();
        $response = $genesis->client()->get('/me');

        return $response->status() === 200;
    }
}
