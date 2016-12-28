<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Edge
 *
 * @property int                                                                 $id
 * @property int                                                                 $start_id
 * @property int                                                                 $end_id
 * @property array                                                               $types
 * @property-read \App\Models\Node                                               $start
 * @property-read \App\Models\Node                                               $end
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Pathway[] $pathways
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Edge whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Edge whereStartId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Edge whereEndId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Edge whereTypes($value)
 * @mixin \Eloquent
 */
class Edge extends Model
{

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'types' => 'array'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Start node relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function start()
    {
        return $this->belongsTo('App\Models\Node', 'start_id', 'id');
    }

    /**
     * Start node relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function end()
    {
        return $this->belongsTo('App\Models\Node', 'end_id', 'id');
    }

    /**
     * Pathways with this edge
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pathways()
    {
        return $this->belongsToMany('App\Models\Pathway', 'pathway_edges', 'edge_id', 'pathway_id');
    }


}
