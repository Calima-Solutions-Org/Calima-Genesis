<?php

namespace App\Commands;

use App\Commands\Traits\Authenticatable;
use App\Commands\Traits\HasSteps;
use App\Commands\Traits\HasVariables;
use App\Genesis\Actions\GetProjectType;
use LaravelZero\Framework\Commands\Command;

class StartProject extends Command
{
    use Authenticatable;
    use HasVariables;
    use HasSteps;

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
}
