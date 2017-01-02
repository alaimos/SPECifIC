<?php

namespace App\Jobs\Handlers;

use App\Exceptions\CommandException;
use App\Exceptions\JobException;
use App\Models\Disease;
use App\Utils\Commons;
use App\Utils\Utils;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Job as JobData;

class ExtractAndEnrichSubStructures extends AbstractHandler
{

    /**
     * Annotate a set of nodes
     *
     * @param array  $list
     * @param string $pvAdjust
     * @return array|null
     */
    protected function makeAnnotation(array $list, $pvAdjust = "BH")
    {
        $key = md5(implode(',', $list));
        $commandOutput = [];
        try {
            $outputFile = $this->jobData->getJobFile('annotation.' . $key . '.txt');
            if (!file_exists($outputFile)) {
                $maxPValue = $this->jobData->getParameter('annotationMaxPValue', 0.05);
                Commons::makeAnnotation($list, $outputFile, $maxPValue, $pvAdjust);
            }
            return [$key, $outputFile];
        } catch (CommandException $e) {
            $this->mapCommandException('makeHeatmap', $e, [
                102 => array_pop($commandOutput)
            ]);
            return null;
        }
    }

    /**
     * Run MITHrIL 2 to extract SubStructures
     *
     * @param Disease $disease
     * @return null|string
     */
    protected function exportSubStructures(Disease $disease)
    {
        $commandOutput = [];
        try {
            $outputFile = $this->jobData->getJobFile('substructures.txt');
            $nodesOfInterest = $this->jobData->getParameter('nodesOfInterest', []);
            $maxPValue = $this->jobData->getParameter('extractionMaxPValue', 0.05);
            $minNodes = $this->jobData->getParameter('minNumberOfNodes', 0.05);
            $combiner = $this->jobData->getParameter('combiner', 'fisher');
            $backward = $this->jobData->getParameter('backward', false);
            Commons::exportSubStructures($disease, $outputFile, $nodesOfInterest, $maxPValue, $minNodes, $combiner,
                $backward, $commandOutput);
            return $outputFile;
        } catch (CommandException $e) {
            $this->mapCommandException('makeHeatmap', $e, [
                101 => 'No valid nodes of interest specified.',
                102 => 'Unable to read pathway analysis results',
                103 => array_pop($commandOutput)
            ]);
            return null;
        }
    }

    /**
     * Read output from MITHrIL 2 and annotate all substructures
     *
     * @param string $inputFile
     * @return array
     */
    protected function readSubStructures($inputFile)
    {
        $annotateTypes = $this->jobData->getParameter('annotateTypes', null);
        if (empty($annotateTypes)) {
            $annotateTypes = null;
        }
        $result = [];
        $totalLines = intval(exec('wc -l ' . $inputFile));
        $fp = fopen($inputFile, 'r');
        if (!$fp) {
            throw new JobException('Unable to read sub-structures output');
        }
        $i = 0;
        while (!feof($fp)) {
            $fields = fgetcsv($fp, 0, "\t");
            if ($fields[0]{0} != '#') {
                if ($annotateTypes === null || !in_array($fields[1], $annotateTypes)) {
                    $nodes = array_unique(array_filter(explode(',', $fields[4])));
                    if (!empty($nodes)) {
                        list($key, $annotationFile) = $this->makeAnnotation($nodes);
                        $result[$key] = $annotationFile;
                    }
                }
            }
            $i++;
            $percentage = round(($i / $totalLines) * 100);
            if (($percentage % 10) == 0) {
                $this->log('...' . $percentage . '%', false);
            }
        }
        @fclose($fp);
        return $result;
    }

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
        $this->log('Looking for disease object', false);
        $disease = Disease::whereShortName($this->jobData->getParameter('disease'))->first();
        if ($disease === null) {
            throw new JobException('Invalid disease specified.');
        }
        $this->log('...OK!');
        $this->log('Exporting SubStructures', false);
        $subStructuresFile = $this->exportSubStructures($disease);
        $this->log('...OK!');
        $this->log('Annotating SubStructures', false);
        $annotations = $this->readSubStructures($subStructuresFile);
        $this->log('...OK!');
        $this->jobData->setData([
            'subStructures' => $subStructuresFile,
            'annotations'   => $annotations,
        ]);
        $this->log('Completed!');
    }
}
