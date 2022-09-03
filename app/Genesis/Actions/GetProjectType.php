<?php

namespace App\Genesis\Actions;

use App\Genesis\Genesis;
use App\Genesis\ProjectType;

class GetProjectType {
    use AsAction;

    public function __invoke(string $name): ?ProjectType
    {
        $genesis = new Genesis();
        $response = $genesis->client()->get('/project-type/' . $name);
        $response = $response->json()['project_type'] ?? null;
        return $response ? ProjectType::from($response) : null;
    }
}
