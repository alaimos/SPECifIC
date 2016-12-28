<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Pathway
 *
 * @property int $id
 * @property string $accession
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Pathway whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Pathway whereAccession($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Pathway whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Pathway whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Pathway whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Pathway extends Model
{
    //
}
