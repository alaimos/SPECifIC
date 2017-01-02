<?php

namespace App\Jobs\Handlers;

use App\Exceptions\JobException;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Job as JobData;

abstract class AbstractHandler implements HandlerInterface
{
    use InteractsWithQueue;

    /**
     * @var \App\Models\Job
     */
    private $jobData;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\Job $jobData
     */
    public function __construct($jobData)
    {
        $this->setJobData($jobData);
    }


    /**
     * Get the current job object
     *
     * @return JobData
     */
    public function getJobData()
    {
        return $this->jobData;
    }

    /**
     * Set the current job object
     *
     * @param JobData $jobData
     * @return $this
     */
    public function setJobData($jobData)
    {
        if (!$this->canHandleJob($jobData)) {
            throw new JobException('This handler (' . get_class($this) . ') cannot handle a job of type ' . $jobData->job_type . '.');
        }
        $this->jobData = $jobData;
        return $this;
    }

    /**
     * Runs a shell command and checks for successful completion of execution
     *
     * @param string     $command
     * @param array|null $output
     * @param array      $errorCodeMap
     * @return boolean
     */
    protected function runCommand($command, array &$output = null, array $errorCodeMap = [])
    {
        $returnCode = -1;
        exec($command, $output, $returnCode);
        if ($returnCode != 0) {
            if (isset($errorCodeMap[$returnCode])) {
                throw new JobException($errorCodeMap[$returnCode]);
            } else {
                throw new JobException('Execution of command "' . $command . '" returned error code ' . $returnCode . '.');
            }
        }
        return true;
    }

}
