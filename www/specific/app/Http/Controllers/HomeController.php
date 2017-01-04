<?php

namespace App\Http\Controllers;

use App\Models\Disease;
use App\Models\Node;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;

class HomeController extends Controller
{


    public function index()
    {
        return view('index', [
            'diseases' => Disease::all()->pluck('description', 'short_name'),
            'nodes'    => Node::all()->mapWithKeys(function ($e) {
                return [$e->accession => $e->accession . ' - ' . $e->name];
            })
        ]);
    }

    public function listNodesOfInterest(Request $request)
    {
        /** @var Disease $disease */
        $disease = Disease::whereShortName($request->get('disease'))->first();
        if ($disease === null || !$disease->exists) {
            abort(404, 'Disease not found');
        }
        $q = $request->get('q');
        $perPage = (int)$request->get('perPage', 30);
        /** @var Builder $query */
        $query = Node::where(function (Builder $query) use ($q) {
            $query->where('accession', 'like', '%' . $q . '%')
                ->orWhere('name', 'like', '%' . $q . '%')
                ->orWhere('aliases', 'like', '%' . $q . '%');
        });
        $query->whereIn('id', function (QueryBuilder $query) use ($disease) {
            $query->select('node_id')
                ->from($disease->perturbations()->getTable())
                ->where($disease->perturbations()->getForeignKey(), '=', $disease->id);
        });
        return $query->paginate($perPage, ['accession', 'name', 'aliases']);
    }
}
