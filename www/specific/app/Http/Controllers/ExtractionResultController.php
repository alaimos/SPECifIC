<?php

namespace App\Http\Controllers;

use App\Jobs\DispatcherJob;
use App\Models\Disease;
use App\Models\Job;
use App\Models\Node;
use App\Utils\Utils;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ExtractionResultController extends Controller
{

    /**
     * Parse a list of nodes
     *
     * @param array|string $node
     * @return string
     */
    protected function parseNode($node)
    {
        static $nodeMap = [];
        if (is_array($node)) {
            $tmp = [];
            foreach ($node as $n) {
                $tmp[] = $this->parseNode($n);
            }
            return implode(', ', array_filter($tmp));
        } else {
            if (isset($nodeMap[$node])) {
                return $nodeMap[$node];
            }
            if (($n = Node::whereAccession($node)->first()) != null) {
                return ($nodeMap[$node] = view('analysis.extraction.node_view', ['node' => $n])->render());
            }
            return '';
        }
    }

    /**
     * Read the list of structures
     *
     * @param string $structuresFile
     * @return Collection
     */
    protected function readStructuresList($structuresFile)
    {
        $result = [];
        $fp = @fopen($structuresFile, 'r');
        if (!$fp) {
            abort(500, 'Unable to read job results');
        }
        $id = 0;
        while (!feof($fp)) {
            $line = fgets($fp);
            if ($line{0} != '#') {
                $fields = explode("\t", $line);
                if (count($fields) == 6) {
                    $result[] = [
                        'id'          => $id,
                        'root'        => $fields[0],
                        'type'        => title_case($fields[1]),
                        'accumulator' => doubleval($fields[2]),
                        'pvalue'      => doubleval($fields[3]),
                        'nodes'       => count(explode(';', $fields[4])),
                    ];
                }
            }
            $id++;
        }
        @fclose($fp);
        return collect($result);
    }

    /**
     * View results of extraction
     *
     * @param Request $request
     * @param string  $jobKey
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewExtractionResult(Request $request, $jobKey)
    {
        $jobData = Job::whereJobKey($jobKey)->first();
        if ($jobData === null || !$jobData->exists) {
            abort(404);
        }
        /** @var Collection|Node[] $NoIs */
        $NoIs = Node::whereIn('accession', $jobData->getParameter('nodesOfInterest', []))->get();
        if ($jobData->job_status == Job::QUEUED) {
            return view('analysis.extraction.job_queued', [
                'jobData' => $jobData,
                'disease' => Disease::whereShortName($jobData->getParameter('disease'))->first()->description,
                'ahead'   => Job::whereJobStatus(Job::QUEUED)->where('created_at', '<', $jobData->created_at)->count(),
                'nois'    => $NoIs,
            ]);
        } elseif ($jobData->job_status == Job::PROCESSING) {
            return view('analysis.extraction.job_processing', [
                'jobData' => $jobData,
                'disease' => Disease::whereShortName($jobData->getParameter('disease'))->first()->description,
                'nois'    => $NoIs,
            ]);
        } elseif ($jobData->job_status == Job::FAILED) {
            return view('analysis.extraction.job_failed', [
                'jobData' => $jobData,
                'disease' => Disease::whereShortName($jobData->getParameter('disease'))->first()->description,
                'nois'    => $NoIs,
            ]);
        } else {
            $subStructuresFile = $jobData->getData('subStructures');
            $numOfStructures = intval(exec('wc -l ' . escapeshellarg($subStructuresFile)));
            return view('analysis.extraction.job_view', [
                'jobData'       => $jobData,
                'disease'       => Disease::whereShortName($jobData->getParameter('disease'))->first()->description,
                'numStructures' => number_format($numOfStructures, 0),
                'nois'          => $NoIs,
            ]);
        }
    }

    /**
     * List structures for a job
     *
     * @param Request $request
     * @param string  $jobKey
     * @return \Illuminate\Http\JsonResponse
     */
    public function listStructuresData(Request $request, $jobKey)
    {
        $jobData = Job::whereJobKey($jobKey)->first();
        if ($jobData === null || !$jobData->exists) {
            abort(404);
        }
        $subStructuresFile = $jobData->getData('subStructures');
        $subStructures = $this->readStructuresList($subStructuresFile);
        return Datatables::usingCollection($subStructures)
                         ->editColumn('root', function ($data) {
                             return $this->parseNode(explode(';', $data['root']));
                         })
                         ->editColumn('nodes', function ($data) {
                             return number_format($data['nodes'], 0);
                         })
                         ->editColumn('accumulator', function ($data) {
                             return number_format($data['accumulator'], 4);
                         })
                         ->editColumn('pvalue', function ($data) {
                             return number_format($data['pvalue'], 4);
                         })
                         ->addColumn('actions', function ($data) use ($jobData) {
                             return view('analysis.extraction.substructures_actions', [
                                 'struct'  => $data,
                                 'jobData' => $jobData
                             ])->render();
                         })
                         ->setRowId('{{$id}}')->make(true);
    }

    /**
     * Download structures
     *
     * @param string $jobKey
     * @return \Illuminate\Http\JsonResponse
     */
    public function download($jobKey)
    {
        $jobData = Job::whereJobKey($jobKey)->first();
        if ($jobData === null || !$jobData->exists) {
            abort(404);
        }
        $subStructuresFile = $jobData->getData('subStructures');
        return response()->download($subStructuresFile, 'substructures.txt');
    }

    /**
     * Find a sub-structure inside results of job
     *
     * @param Job $jobData
     * @param int $needle
     * @return array|null
     */
    public function findStructure(Job $jobData, $needle)
    {
        $structuresFile = $jobData->getData('subStructures');
        $result = null;
        $fp = @fopen($structuresFile, 'r');
        if (!$fp) {
            abort(500, 'Unable to read job results');
        }
        $id = 0;
        while (!feof($fp)) {
            $line = fgets($fp);
            if ($line{0} != '#' && $id == $needle) {
                $fields = explode("\t", trim($line, "\n\r"));
                if (count($fields) == 6) {
                    $result = [
                        'id'          => $id,
                        'root'        => explode(';', $fields[0]),
                        'type'        => title_case($fields[1]),
                        'accumulator' => doubleval($fields[2]),
                        'pvalue'      => doubleval($fields[3]),
                        'nodes'       => explode(';', $fields[4]),
                        'edges'       => array_map(function ($x) {
                            return explode(',', $x);
                        }, explode(';', $fields[5])),
                    ];
                    break;
                }
            }
            $id++;
        }
        @fclose($fp);
        return $result;
    }

    /**
     * Run enrichment for a single structure
     *
     * @param $jobKey
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function runEnrichment($jobKey, $id)
    {
        $extractionJob = Job::whereJobKey($jobKey)->first();
        if ($extractionJob === null || !$extractionJob->exists) {
            abort(404);
        }
        $structure = $this->findStructure($extractionJob, $id);
        if ($structure === null) {
            abort(404);
        }
        $jobType = 'enrich_sub_structure';
        $jobParameters = [
            'disease'   => $extractionJob->getParameter('disease'),
            'maxPValue' => $extractionJob->getParameter('annotationMaxPValue', 0.05),
            'structure' => Utils::compressArray($structure),
        ];
        $enrichmentJobKey = Job::computeKey($jobType, $jobParameters);
        $enrichmentJob = Job::whereJobKey($enrichmentJobKey)->first();
        if ($enrichmentJob === null) {
            $enrichmentJob = Job::create([
                'job_type'       => $jobType,
                'job_status'     => Job::QUEUED,
                'job_parameters' => $jobParameters,
                'job_data'       => [],
                'job_log'        => ''
            ]);
            $this->dispatchNow(new DispatcherJob($enrichmentJob->id));
        }
        return redirect()->route('extraction-enrichment', [
            'extractionJobKey' => $extractionJob->job_key,
            'enrichmentJobKey' => $enrichmentJob->job_key,
        ]);
    }
}
