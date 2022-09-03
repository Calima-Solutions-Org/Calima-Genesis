<?php

namespace App\Commands;

use App\Services\Genesis;
use Exception;
use Illuminate\Console\Scheduling\Schedule;
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

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
