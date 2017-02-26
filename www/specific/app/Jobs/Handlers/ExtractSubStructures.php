<?php

namespace App\Jobs\Handlers;

use App\Exceptions\CommandException;
use App\Exceptions\JobException;
use App\Models\Disease;
use App\Models\Job as JobData;
use App\Utils\Commons;

class ExtractSubStructures extends AbstractHandler
{

    /**
     * Run MITHrIL 2 to extract SubStructures
     *
     * @param Disease $disease
     *
     * @return null|string
     */
    protected function exportSubStructures(Disease $disease)
    {
        $commandOutput = [];
        try {
            $outputFile = $this->jobData->getJobFile('substructures.txt');
            $nodesOfInterest = $this->jobData->getParameter('nodesOfInterest', null);
            $maxPVPathways = $this->jobData->getParameter('pathwaysMaxPValue', 0.01);
            $maxPVNois = $this->jobData->getParameter('noIsMaxPValue', 0.05);
            $maxPVNodes = $this->jobData->getParameter('nodesMaxPValue', 0.10);
            $minNumNodes = $this->jobData->getParameter('minNumberOfNodes', 5);
            $maxPVPaths = $this->jobData->getParameter('extractionMaxPValue', 1e-5);
            $backward = $this->jobData->getParameter('backward', false);
            Commons::exportSubStructures($disease, $outputFile, $nodesOfInterest, $maxPVPathways, $maxPVNois,
                $maxPVNodes, $maxPVPaths, $minNumNodes, $backward, $commandOutput);
            return $outputFile;
        } catch (CommandException $e) {
            $this->mapCommandException('makeHeatmap', $e, [
                101 => 'No valid nodes of interest specified.',
                102 => 'Unable to read pathway analysis results',
                103 => array_pop($commandOutput),
                104 => 'No significant pathways found in the analysis. Unable to extract NoIs.',
            ]);
            return null;
        }
    }

    /**
     * Read output from MITHrIL 2 and detect NoIs if needed
     *
     * @param string $inputFile
     *
     * @return void
     */
    protected function detectNoIs($inputFile)
    {
        $nodesOfInterest = $this->jobData->getParameter('nodesOfInterest', null);
        if (!is_array($nodesOfInterest) && empty($nodesOfInterest)) {
            $this->log('Detecting final set of NoIs...', false);
            $fp = fopen($inputFile, 'r');
            if (!$fp) {
                throw new JobException('Unable to read extracted sub-structures file.');
            }
            $detectedNoIs = [];
            while (!feof($fp)) {
                $fields = fgetcsv($fp, 0, "\t");
                if ($fields[0]{0} != '#') {
                    $line = fgets($fp);
                    if ($line{0} != '#') {
                        $fields = explode("\t", $line);
                        if (count($fields) == 6) {
                            $root = explode(';', $fields[0]);
                            foreach ($root as $r) $detectedNoIs[] = $r;
                        }
                    }
                }
            }
            @fclose($fp);
            $detectedNoIs = array_unique($detectedNoIs);
            $this->jobData->setData('nodesOfInterest', $detectedNoIs);
            $this->log('OK!');
        }
    }

    /**
     * Checks if this class can handle a specific job
     *
     * @param JobData $jobData
     *
     * @return boolean
     */
    public function canHandleJob(JobData $jobData)
    {
        $type = strtolower($jobData->job_type);
        return $type == 'extract_sub_structures';
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
        $this->log('Exporting SubStructures...', false);
        $subStructuresFile = $this->exportSubStructures($disease);
        $this->log('OK!');
        $this->jobData->setData([
            'subStructures' => $subStructuresFile,
        ]);
        $this->detectNoIs($subStructuresFile);
        $this->log('Completed!');
    }
}
