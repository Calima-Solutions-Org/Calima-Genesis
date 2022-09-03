<?php

namespace App\Commands;

use App\Genesis\Genesis;
use Exception;
use LaravelZero\Framework\Commands\Command;

class Authenticate extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'authenticate';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Authenticate with Genesis';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $genesis = new Genesis();
        $email = $this->ask('Your email');
        $password = $this->secret('Your password');
        try {
            $token = $genesis->getAuthToken($email, $password);
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return;
        }


        Genesis::config([
            'token' => $token,
        ]);
        $this->info('Successfully authenticated with Genesis!');
    }
}
