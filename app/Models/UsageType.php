<?php
/**
 * Created by VelgirLab
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class UsageType extends Model
{
protected $table = "usage_types";

    protected $fillable = [
        "name", "published"
    ];

    public function tracks()
    {
        return $this->belongsToMany(Track::class, 'tracks_usage_types');
    }

}
