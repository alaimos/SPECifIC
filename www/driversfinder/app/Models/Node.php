<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Node
 *
 * @property int                                                                 $id
 * @property string                                                              $accession
 * @property string                                                              $name
 * @property string                                                              $type
 * @property array                                                               $aliases
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Edge[]    $ingoingEdges
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Edge[]    $outgoingEdges
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Pathway[] $pathways
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Node whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Node whereAccession($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Node whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Node whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Node whereAliases($value)
 * @mixin \Eloquent
 */
class Node extends Model
{


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'aliases' => 'array'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Ingoing edges from this node
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ingoingEdges()
    {
        return $this->hasMany('App\Models\Edge', 'end_id', 'id');
    }

    /**
     * Outgoing edges from this node
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function outgoingEdges()
    {
        return $this->hasMany('App\Models\Edge', 'start_id', 'id');
    }

    /**
     * Pathways with this edge
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pathways()
    {
        return $this->belongsToMany('App\Models\Pathway', 'pathway_nodes', 'node_id', 'pathway_id');
    }

}
