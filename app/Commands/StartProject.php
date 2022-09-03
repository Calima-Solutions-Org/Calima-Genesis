<?php

namespace App\Commands;

use App\Commands\Traits\Authenticatable;
use App\Genesis\Actions\GetProjectType;
use App\Genesis\Step;
use App\Genesis\Variable;
use LaravelZero\Framework\Commands\Command;

class StartProject extends Command
{
    use Authenticatable;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'start {name}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Creates a new project';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->authenticateOrFail();
        $projectType = GetProjectType::run($this->argument('name'));
        if (! $projectType) {
            $this->error('Invalid name provided.');
            return;
        }

        $this->info($projectType->name);
        foreach ($projectType->variables as $variable) {
            $this->askVariable($variable);
        }

        foreach ($projectType->steps as $step) {
            $this->runStep($step, $projectType->variables);
        }
    }

    private function runStep(Step $step, array $variables)
    {
        if (! $step->meetsConditions()) {
            return;
        }

        $this->info($step->name);
        $output = shell_exec($step->command($variables));
        $this->info($output);
    }

    private function askVariable(Variable $variable)
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
