<?php

namespace App\Jobs\Handlers;

use App\Exceptions\CommandException;
use App\Exceptions\JobException;
use App\Models\Disease;
use App\Utils\Commons;
use App\Models\Job as JobData;
use App\Utils\Utils;

class EnrichSubStructure extends AbstractHandler
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
        return $type == 'enrich_sub_structure';
    }

    /**
     * Execute the job.
     *
     * @throws \App\Exceptions\JobException
     * @return void
     */
    public function handle()
    {
        $this->log('Looking for disease object...', false);
        $disease = Disease::whereShortName($this->jobData->getParameter('disease'))->first();
        if ($disease === null) {
            throw new JobException('Invalid disease specified.');
        }
        $this->log('OK!');
        $this->log('Checking Sub-Structure object...', false);
        $subStructure = Utils::uncompressArray($this->jobData->getParameter('structure'));
        if (!isset($subStructure['nodes']) || !is_array($subStructure['nodes']) || !count($subStructure['nodes'])) {
            throw new JobException('No nodes specified.');
        }
        $this->log('OK!');
        $this->log('Enriching SubStructure...', false);
        $maxPValue = (double)$this->jobData->getParameter('maxPValue', 0.05);
        list($key, $annotationFile) = Commons::makeAnnotation($subStructure['nodes'], $maxPValue);
        $this->log('OK!');
        $this->jobData->setData([
            'annotationKey'  => $key,
            'annotationFile' => $annotationFile,
        ]);
        $this->log('Completed!');
    }
}
