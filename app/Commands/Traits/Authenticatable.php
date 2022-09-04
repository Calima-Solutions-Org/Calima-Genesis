<?php

namespace App\Commands\Traits;

use App\Genesis\Actions\ValidateToken;
use App\Genesis\Genesis;

trait Authenticatable
{
    protected function authenticateOrFail()
    {
        $config = Genesis::config();
        if ($config->token) {
            if (! ValidateToken::run()) {
                $this->error('You are not authenticated with Genesis. Please run `genesis authenticate` to log in.');
                abort(1);
            }

            return;
        }

        $this->error('You are not authenticated with Genesis. Please run `genesis authenticate` to log in.');
    }
}
