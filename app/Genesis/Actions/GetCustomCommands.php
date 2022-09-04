<?php

namespace App\Genesis\Actions;

use App\Genesis\CustomCommand;
use App\Genesis\Genesis;

class GetCustomCommands
{
    use AsAction;

    public function __invoke()
    {
        $genesis = new Genesis();
        $response = $genesis->client()->get('/custom-commands')->json();

        return array_map(fn ($command) => CustomCommand::from($command), $response['commands']);
    }
}
