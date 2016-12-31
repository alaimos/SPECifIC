<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AnnotationTerm
 *
 * @property int                                                              $id
 * @property string                                                           $accession
 * @property string                                                           $description
 * @property string                                                           $source_id
 * @property-read \App\Models\AnnotationSource                                $source
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Node[] $annotatedNodes
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AnnotationTerm whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AnnotationTerm whereAccession($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AnnotationTerm whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AnnotationTerm whereSourceId($value)
 * @mixin \Eloquent
 */
class AnnotationTerm extends Model
{

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['accession', 'description', 'source_id'];

    /**
     * References the source of this term
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function source()
    {
        return $this->belongsTo('App\Models\AnnotationSource', 'term_id', 'id');
    }

    /**
     * References the nodes annotated by this term
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function annotatedNodes()
    {
        return $this->belongsToMany('App\Models\Node', 'annotations', 'term_id', 'node_id');
    }

}
