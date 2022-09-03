<?php

namespace App\Commands;

use App\Commands\Traits\Authenticatable;
use App\Commands\Traits\HasSteps;
use App\Commands\Traits\HasVariables;
use App\Genesis\Actions\GetCustomCommand;
use LaravelZero\Framework\Commands\Command;

class RunCustomCommand extends Command
{
    use Authenticatable;
    use HasSteps;
    use HasVariables;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'run {signature}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Runs a custom command.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->authenticateOrFail();
        $command = GetCustomCommand::run($this->argument('signature'));
        if (! $command) {
            $this->error('Invalid signture provided.');
            return;
        }

        foreach ($command->variables as $variable) {
            $this->askVariable($variable);
        }

        foreach ($command->steps as $step) {
            $this->runStep($step, $command->variables);
        }
    }
}
