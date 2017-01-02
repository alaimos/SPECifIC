<?php

namespace App\Jobs\Handlers;

use App\Exceptions\JobException;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Job as JobData;

class ExtractAndEnrichSubStructures extends AbstractHandler
{

    /**
     * Checks if this class can handle a specific job
     *
     * @param JobData $jobData
     * @return boolean
     */
    public function canHandleJob(JobData $jobData)
    {
        $type = strtolower($jobData->job_type);
        return $type == 'extract_and_enrich_sub_structures';
    }

    /**
     * Execute the job.
     *
     * @throws \App\Exceptions\JobException
     * @return void
     */
    public function handle()
    {
        // TODO: Implement handle() method.
    }
}
