<?php

namespace App\Commands\Traits;

use App\Genesis\Variable;

trait HasVariables {
    protected function askVariable(Variable $variable)
    {
        switch ($variable->type) {
            case 'string':
                $variable->value($this->ask($variable->description));
                break;
            case 'boolean':
                $variable->value($this->confirm($variable->description));
                break;
            case 'select':
                $variable->value($this->choice($variable->description, $variable->options));
                break;
        }
    }
}
