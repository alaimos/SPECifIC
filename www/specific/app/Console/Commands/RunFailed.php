<?php

namespace App\Console\Commands;

use App\Jobs\DispatcherJob;
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
        $jobs = Job::whereJobStatus(Job::FAILED)->get();
        foreach ($jobs as $job) {
            /** @var Job $job */
            $job->job_status = Job::QUEUED;
            $job->job_log = '';
            $job->save();
            dispatch(new DispatcherJob($job->id));
        }
        return 0;
    }
}
