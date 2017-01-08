<?php

namespace App\Http\Controllers;

use App\Models\AnnotationSource;
use App\Models\AnnotationTerm;
use App\Models\Disease;
use App\Models\Edge;
use App\Models\Job;
use App\Models\Node;
use App\Utils\Commons;
use App\Utils\Utils;
use Datatables;
use Illuminate\Http\Request;

class EnrichmentResultController extends Controller
{

    /**
     * Get an annotation term by its accession number
     *
     * @param string $accession
     * @return \App\Models\AnnotationTerm|null
     */
    protected function getTerm($accession)
    {
        static $terms = [];
        if (isset($terms[$accession])) {
            return $terms[$accession];
        }
        return ($terms[$accession] = AnnotationTerm::whereAccession($accession)->first());
    }

    /**
     * Get the name of a source by its identifier
     *
     * @param string $source
     * @return string
     */
    protected function getTermSource($source)
    {
        static $sources = [];
        if (isset($sources[$source])) {
            return $sources[$source];
        }
        return ($sources[$source] = AnnotationSource::whereId($source)->first()->name);
    }

    /**
     * Get the list of nodes for a term
     *
     * @param string $accession
     * @param array  $allNodes
     * @return \Illuminate\Support\Collection
     */
    protected function getNodesByTerm($accession, array $allNodes)
    {
        $term = $this->getTerm($accession);
        return $term->annotatedNodes()
                    ->getBaseQuery()
                    ->select('nodes.accession')
                    ->whereIn('nodes.accession', $allNodes)
                    ->pluck('accession');
    }

    /**
     * Get the identifier of a node by using its accession number
     *
     * @param string $accession
     * @param array  $nodesMap
     * @return int
     */
    protected function getNodeId($accession, array &$nodesMap)
    {
        if (isset($nodesMap[$accession])) {
            return $nodesMap[$accession];
        }
        return ($nodesMap[$accession] = Node::whereAccession($accession)->first()->id);
    }

    /**
     * Reads the result of the enrichment procedure
     *
     * @param string $resultFile
     * @return \Illuminate\Support\Collection
     */
    protected function readEnrichmentResult($resultFile)
    {
        $result = [];
        $fp = @fopen($resultFile, 'r');
        if (!$fp) {
            abort(500, 'Unable to read enrichment results');
        }
        while (!feof($fp)) {
            $fields = fgetcsv($fp, 0, "\t");
            if (count($fields) != 11 || $fields[0] == 'term.id') {
                continue;
            }
            $result[] = [
                'id'             => $fields[0],
                'name'           => $fields[1],
                'occurrences'    => intval($fields[2]),
                'expected'       => doubleval($fields[3]),
                'variance'       => doubleval($fields[4]),
                'pvalue'         => doubleval($fields[5]),
                'adjustedPValue' => doubleval($fields[6]),
                'source'         => $this->getTermSource($fields[10]),
            ];
        }
        @fclose($fp);
        return collect($result);
    }

    /**
     * Get a job by key
     *
     * @param string $jobKey
     * @return \App\Models\Job
     */
    protected function jobByKey($jobKey)
    {
        $jobData = Job::whereJobKey($jobKey)->first();
        if ($jobData === null || !$jobData->exists) {
            abort(404);
        }
        return $jobData;
    }

    /**
     * Return the details of a sub-structure
     *
     * @param Job $enrichmentJob
     * @return array
     */
    protected function getStructureDetails(Job $enrichmentJob)
    {
        $subStructure = Utils::uncompressArray($enrichmentJob->getParameter('structure'));
        return $subStructure;
    }

    /**
     * Show the main page with the results of an enrichment analysis
     *
     * @param string $extractionJobKey
     * @param string $enrichmentJobKey
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewEnrichmentResult($extractionJobKey, $enrichmentJobKey)
    {
        $extractionJob = $this->jobByKey($extractionJobKey);
        $enrichmentJob = $this->jobByKey($enrichmentJobKey);
        $backUrl = route('extraction-results', ['jobKey' => $extractionJob->job_key]);
        return view('analysis.enrichment.view', [
            'enrichmentJob' => $enrichmentJob,
            'backUrl'       => $backUrl,
            'jobData'       => $enrichmentJob,
            'sources'       => AnnotationSource::pluck('name', 'id')
        ]);
    }

    /**
     * List terms for an enrichment job
     *
     * @param string $jobKey
     * @return \Illuminate\Http\JsonResponse
     */
    public function listTerms($jobKey)
    {
        $enrichmentJob = $this->jobByKey($jobKey);
        $subStructure = $this->getStructureDetails($enrichmentJob);
        $allNodes = $subStructure['nodes'];
        $results = $this->readEnrichmentResult($enrichmentJob->getData('annotationFile'));
        return Datatables::usingCollection($results)
                         ->editColumn('expected', function ($data) {
                             return number_format($data['expected'], 4);
                         })
                         ->editColumn('variance', function ($data) {
                             return number_format($data['variance'], 4);
                         })
                         ->editColumn('pvalue', function ($data) {
                             return number_format($data['pvalue'], 4);
                         })
                         ->editColumn('adjustedPValue', function ($data) {
                             return number_format($data['adjustedPValue'], 4);
                         })
                         ->setRowData([
                             'nodes' => function ($data) use ($allNodes) {
                                 return $this->getNodesByTerm($data['id'], $allNodes)->implode(',');
                             },
                         ])->make(true);
    }

    /**
     * Returns list of nodes and edges for rendering of the graph
     *
     * @param string $jobKey
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewSubStructure($jobKey)
    {
        $enrichmentJob = $this->jobByKey($jobKey);
        $subStructureViewFile = $enrichmentJob->getJobFile('view.gz-array');
        /*if (file_exists($subStructureViewFile)) {
            return response()->json(Utils::uncompressArray(file_get_contents($subStructureViewFile)));
        }*/
        $subStructure = $this->getStructureDetails($enrichmentJob);
        $nodesMap = [];
        $elements = array_filter(array_map(function ($accession) use (&$nodesMap) {
            $node = Node::whereAccession($accession)->first();
            if ($node === null) {
                return null;
            }
            $nodesMap[$accession] = $node->id;
            return [
                'group' => 'nodes',
                'data'  => [
                    'id'   => $node->accession,
                    'name' => $node->name,
                    'type' => $node->type,
                    'url'  => $node->getUrl(),
                ],
            ];
        }, $subStructure['nodes']));
        foreach ($subStructure['edges'] as $edge) {
            $edgeObject = Edge::whereId(Edge::computeId($this->getNodeId($edge[0], $nodesMap),
                $this->getNodeId($edge[1], $nodesMap)))->first();
            if ($edge !== null) {
                $elements[] = [
                    'group' => 'edges',
                    'data'  => [
                        'id'     => $edgeObject->id,
                        'source' => $edge[0],
                        'target' => $edge[1],
                        'type'   => implode(',', array_unique(array_map(function ($t) {
                            return $t[1];
                        }, $edgeObject->types))),
                    ]
                ];
            }
        }
        $data = [
            'elements' => $elements,
            'root'     => $subStructure['root'][0],
        ];
        file_put_contents($subStructureViewFile, Utils::compressArray($data));
        return response()->json($data);
    }

    /**
     * Generate heatmap for an enrichment job
     *
     * @param string $jobKey
     * @return \Illuminate\Http\JsonResponse
     */
    public function heatmap($jobKey)
    {
        $enrichmentJob = $this->jobByKey($jobKey);
        $subStructure = $this->getStructureDetails($enrichmentJob);
        $disease = Disease::whereShortName($enrichmentJob->getParameter('disease'))->first();
        $heatmapFile = Commons::makeHeatmap($disease, $subStructure['nodes']);
        return response()->file($heatmapFile);
    }

    /**
     * Downloads results of an enrichment job
     *
     * @param string $jobKey
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($jobKey)
    {
        $enrichmentJob = $this->jobByKey($jobKey);
        return response()->download($enrichmentJob->getData('annotationFile'), 'enrichment.txt');
    }
}
