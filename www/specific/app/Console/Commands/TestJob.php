<?php

namespace App\Console\Commands;

use App\Jobs\DispatcherJob;
use App\Models\Disease;
use App\Models\Job;
use Illuminate\Console\Command;

class TestJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Job';

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
        $tmp = Disease::whereShortName('COAD S1')->first();
        $job = Job::create([
            'job_type'       => 'extract_sub_structures',
            'job_status'     => Job::QUEUED,
            'job_parameters' => [
                'disease'             => $tmp->short_name,
                'nodesOfInterest'     => ['7157', '2475', 'hsa-miR-21-5p', 'hsa-miR-21-3p'],
                'extractionMaxPValue' => 0.01,
                'minNumberOfNodes'    => 10,
                'combiner'            => 'fisher',
                'backward'            => false,
                'annotateTypes'       => null,
                'annotationMaxPValue' => 0.05
            ],
            'job_data'       => [],
            'job_log'        => ''
        ]);
        dispatch(new DispatcherJob($job->id));
        return 0;
    }
}
