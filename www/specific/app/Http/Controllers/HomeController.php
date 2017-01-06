<?php

namespace App\Http\Controllers;

use App\Jobs\DispatcherJob;
use App\Models\Disease;
use App\Models\Job;
use App\Models\Node;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;

class HomeController extends Controller
{


    /**
     * Shows the index page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('index', [
            'diseases' => Disease::all()->pluck('description', 'short_name'),
            'nodes'    => Node::all()->mapWithKeys(function ($e) {
                return [$e->accession => $e->accession . ' - ' . $e->name];
            })
        ]);
    }

    /**
     * Handles searching, pagination, and listing of disease-specific NoIs
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Http\JsonResponse
     */
    public function listNodesOfInterest(Request $request)
    {
        /** @var Disease $disease */
        $disease = Disease::whereShortName($request->get('disease'))->first();
        $diseaseTable = $disease->perturbations()->getTable();
        $diseaseForeign = $disease->perturbations()->getForeignKey();
        $q = $request->get('q');
        $perPage = (int)$request->get('perPage', 30);
        if ($disease === null || !$disease->exists) {
            return response()->json([
                'total'        => 0,
                'per_page'     => $perPage,
                'current_page' => 0,
                'last_page'    => 0,
                'data'         => []
            ]);
        }
        /** @var Builder $query */
        $query = Node::where(function (Builder $query) use ($q) {
            $query->where('nodes.accession', 'like', '%' . $q . '%')
                ->orWhere('nodes.name', 'like', '%' . $q . '%')
                ->orWhere('nodes.aliases', 'like', '%' . $q . '%');
        });
        $query->join($diseaseTable, 'nodes.id', '=', $diseaseTable . '.node_id');
        $query->where($diseaseForeign, '=', $disease->id);
        /*$query->whereIn('id', function (QueryBuilder $query) use ($disease, $diseaseTable, $diseaseForeign) {
            $query->select('node_id')
                ->from($diseaseTable)
                ->where($diseaseForeign, '=', $disease->id);
        });*/
        return $query->paginate($perPage, ['accession', 'name', 'perturbation', 'pvalue']);
    }

    /**
     * Checks if a p-value is in the correct range
     *
     * @param float|null $pValue
     * @param float      $default
     * @return float
     */
    protected function checkPValue($pValue, $default = 0.05)
    {
        if ($pValue === null) {
            $pValue = $default;
        }
        $pValue = doubleval($pValue);
        if ($pValue < 0 || $pValue > 1) {
            $pValue = $default;
        }
        return $pValue;
    }

    /**
     * Submit extraction job
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitExtractionJob(Request $request)
    {
        $this->validate($request, [
            'disease'          => 'required|exists:diseases,short_name',
            'nois'             => 'required|array|exists:nodes,accession',
            'max-pvalue'       => 'present|numeric',
            'max-pvalue-annot' => 'present|numeric',
            'min-num-nodes'    => 'present|numeric',
            'backward-visit'   => 'sometimes|boolean',
        ]);
        $disease = $request->get('disease');
        $NoIs = array_unique($request->get('nois'));
        sort($NoIs);
        $maxPV = $this->checkPValue($request->get('max-pvalue'));
        $maxPVAnnot = $this->checkPValue($request->get('max-pvalue-annot'));
        $isBackward = intval($request->get('backward-visit', 0)) == 1;
        $minNumNodes = (int)$request->get('min-num-nodes');
        $jobType = 'extract_sub_structures';
        $jobParameters = [
            'disease'             => $disease,
            'nodesOfInterest'     => $NoIs,
            'extractionMaxPValue' => $maxPV,
            'annotationMaxPValue' => $maxPVAnnot,
            'minNumberOfNodes'    => $minNumNodes,
            'combiner'            => 'fisher',
            'backward'            => $isBackward
        ];
        $jobKey = Job::computeKey($jobType, $jobParameters);
        $job = Job::whereJobKey($jobKey)->first();
        if ($job === null) {
            $job = Job::create([
                'job_type'       => $jobType,
                'job_status'     => Job::QUEUED,
                'job_parameters' => $jobParameters,
                'job_data'       => [],
                'job_log'        => ''
            ]);
            $this->dispatch(new DispatcherJob($job->id));
        }
        return redirect()->route('extraction-results', [
            'jobKey' => $job->job_key,
        ]);
    }

}
