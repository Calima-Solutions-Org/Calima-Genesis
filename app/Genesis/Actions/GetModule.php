<?php

namespace App\Genesis\Actions;

use App\Genesis\Genesis;
use App\Genesis\Module;

class GetModule {
    use AsAction;

    public function __invoke(string $identifier, string $version): ?Module
    {
        $genesis = new Genesis();
        $response = $genesis->client()->get('/modules/' . $identifier . '/' . $version)->json();
        $module = $response['module'] ?? null;
        return $module ? Module::from($module) : null;
    }
}
