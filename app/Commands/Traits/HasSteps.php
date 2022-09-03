<?php

namespace App\Commands\Traits;

use App\Genesis\Step;

trait HasSteps {
    protected function runStep(Step $step, array $variables)
    {
        if (! $step->meetsConditions()) {
            return;
        }

        $this->info($step->name);
        $output = shell_exec($step->command($variables));
        $this->info($output);
    }
}
