<?php

namespace App\Genesis\Actions;

use App\Genesis\Genesis;
use App\Genesis\Module;

class GetModules {
    use AsAction;

    public function __invoke()
    {
        $genesis = new Genesis();
        $response = $genesis->client()->get('/modules')->json();
        return array_map(fn ($module) => Module::from($module), $response['modules']);
    }
}
