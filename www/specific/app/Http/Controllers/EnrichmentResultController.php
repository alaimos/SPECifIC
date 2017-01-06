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
        $i = 0;
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
                'source'         => $fields[10],
            ];
            $i++;
            if ($i == 20) {
                break;
            }
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

    public function viewEnrichmentResult($extractionJobKey, $enrichmentJobKey)
    {
        $extractionJob = $this->jobByKey($extractionJobKey);
        $enrichmentJob = $this->jobByKey($enrichmentJobKey);
        $backUrl = route('extraction-results', ['jobKey' => $extractionJob->job_key]);
        return view('analysis.enrichment.view', [
            'enrichmentJob' => $enrichmentJob,
            'backUrl'       => $backUrl,
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
                         ->editColumn('source', function ($data) {
                             return $this->getTermSource($data['source']);
                         })
                         ->setRowData([
                             'nodes' => function ($data) use ($allNodes) {
                                 return $this->getNodesByTerm($data['id'], $allNodes)->implode(',');
                             },
                         ])
                         ->setRowId('{{$id}}')->make(true);
    }

    /**
     * @param string $jobKey
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewSubStructure($jobKey)
    {
        $enrichmentJob = $this->jobByKey($jobKey);
        $subStructure = $this->getStructureDetails($enrichmentJob);
        $nodesMap = [];
        $nodes = array_filter(array_map(function ($accession) use (&$nodesMap) {
            $node = Node::whereAccession($accession)->first();
            if ($node === null) {
                return null;
            }
            $nodesMap[$accession] = $node->id;
            return $node->toArray();
        }, $subStructure['nodes']));
        $edges = array_filter(array_map(function ($edge) use ($nodesMap) {
            $edge = Edge::whereId(Edge::computeId($nodesMap[$edge[0]], $nodesMap[$edge[1]]))->first();
            if ($edge === null) {
                return null;
            }
            return $edge->toArray();
        }, $subStructure['edges']));
        return response()->json([
            'nodes' => $nodes,
            'edges' => $edges,
        ]);
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
}
