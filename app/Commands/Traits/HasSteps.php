<?php

namespace App\Commands\Traits;

use App\Genesis\Step;
use Illuminate\Support\Str;

trait HasSteps {
    protected function runStep(Step $step, array $variables)
    {
        if (! $step->meetsConditions()) {
            return;
        }

        $this->info($step->name);
        $command = $step->command($variables);

        // if the command is a custom command, we execute it directly and not in another process
        if (Str::contains($command, 'genesis run')) {
            $basePath = Str::before(Str::after($command, 'cd "'), '" && ');
            $signature = Str::after($command, 'genesis run ');
            $this->call('run', [
                'signature' => $signature,
                '--basePath' => $basePath,
            ]);
        } else {
            $output = shell_exec($command);
            $this->info($output);
        }
    }
}
