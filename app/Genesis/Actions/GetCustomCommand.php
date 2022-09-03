<?php

namespace App\Genesis\Actions;

use App\Genesis\CustomCommand;
use App\Genesis\Genesis;

class GetCustomCommand {
    use AsAction;

    public function __invoke(string $signature): ?CustomCommand
    {
        $genesis = new Genesis();
        $response = $genesis->client()->get('/custom-commands/' . $signature)->json();
        $command = $response['command'] ?? null;
        return $command ? CustomCommand::from($command) : null;
    }
}
