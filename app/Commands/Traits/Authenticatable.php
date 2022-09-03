<?php

namespace App\Commands\Traits;

use App\Services\Genesis;

trait Authenticatable {
    protected function authenticate()
    {
        $config = Genesis::config();
        if ($config->token) {
            return;
        }

        $genesis = new Genesis();
        if (! $genesis->validateToken()) {
            $this->warning('You are not authenticated with Genesis. Please run `genesis authenticate` to log in.');
            abort(1);
        }
    }
}
