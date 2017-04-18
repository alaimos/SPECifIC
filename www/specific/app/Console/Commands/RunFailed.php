<?php

namespace App\Console\Commands;

use App\Models\Job;
use Illuminate\Console\Command;

class RunFailed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:failed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch all failed jobs';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $jobs = Job::whereJobStatus(Job::FAILED)->all();
        foreach ($jobs as $job) {
            dd($job);
        }
        return 0;
    }
}
