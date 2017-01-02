<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Disease
 *
 * @property int $id
 * @property string $short_name
 * @property string $description
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Node[] $perturbations
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Disease whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Disease whereShortName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Disease whereDescription($value)
 * @mixin \Eloquent
 */
class Disease extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['short_name', 'description'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Perturbations for this disease
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function perturbations()
    {
        return $this->belongsToMany('App\Models\Node', 'perturbations', 'disease_id', 'node_id')->withPivot([
            'perturbation', 'pvalue'
        ]);
    }

    /**
     * Get perturbations for a set of nodes
     *
     * @param string|Node|Node[]|array $nodes
     * @return array
     */
    public function getPerturbations($nodes)
    {
        if (!is_array($nodes)) {
            $nodes = [$nodes];
        }
        $result = [];
        foreach ($nodes as $node) {
            if ($node instanceof Node) {
                $node = $node->id;
            } elseif (is_string($node)) {
                $node = Node::whereAccession($node)->first()->id;
            }
            if (($pert = $this->perturbations()->find($node)) !== null) {
                $result[$node] = [
                    'perturbation' => $pert->pivot->perturbation,
                    'pvalue'       => $pert->pivot->pvalue
                ];
            } else {
                $result[$node] = ['perturbation' => 0.0, 'pvalue' => 1.0];
            }
        }
        return $result;
    }

}
