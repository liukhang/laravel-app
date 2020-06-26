<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupDevelopment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:development';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setting up development mode';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!config('app.key') && file_exists(base_path('.env'))) {
            $this->call('key:generate');
        }

        if (!file_exists(public_path('storage'))) {
            $this->call('storage:link');
        }

        $this->call('optimize:clear');
        $this->call('migrate:fresh', ['--force' => true]);
        $this->call('db:seed', ['--force' => true]);
    }
}
