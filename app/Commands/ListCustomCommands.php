<?php

namespace App\Commands;

use App\Commands\Traits\Authenticatable;
use App\Genesis\Actions\GetCustomCommands;
use LaravelZero\Framework\Commands\Command;

class ListCustomCommands extends Command
{
    use Authenticatable;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'commands';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Lists all available custom commands built in Genesis.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->authenticateOrFail();
        $commands = GetCustomCommands::run();
        $headers = ['Signature', 'Description'];
        $rows = collect($commands)->map(fn ($command) => [
            $command->signature."\n",
            $command->description,
        ])->toArray();
        $this->table($headers, $rows);
        $this->info('You can run a custom command by running `genesis run <signature>`');
    }
}
