<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AnnotationSource
 *
 * @property int                                                                        $id
 * @property string                                                                     $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AnnotationTerm[] $terms
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AnnotationSource whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AnnotationSource whereName($value)
 * @mixin \Eloquent
 */
class AnnotationSource extends Model
{

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

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
    protected $fillable = ['id', 'name'];


    /**
     * References all terms belonging to this source
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function terms()
    {
        return $this->hasMany('App\Models\AnnotationTerm', 'source_id', 'id');
    }


}
