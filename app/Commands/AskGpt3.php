<?php

namespace App\Commands;

use App\Commands\Traits\Authenticatable;
use App\Genesis\Actions\AskQuestionToGpt3;
use LaravelZero\Framework\Commands\Command;

class AskGpt3 extends Command
{
    use Authenticatable;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'ask {question}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Asks a question to the Davinci AI engine and returns an answer.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->authenticateOrFail();
        $answer = AskQuestionToGpt3::run($this->argument('question'));
        $this->info($answer ?? 'We could not find an answer to this question.');
    }
}
