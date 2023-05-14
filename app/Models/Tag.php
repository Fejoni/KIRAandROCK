<?php
/**
 * Created by VelgirLab
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Tag extends Model
{
protected $table = "tags";

    protected $fillable = [
        "name", "published"
    ];


    public function tracks()
    {
        return $this->belongsToMany(Track::class, 'tags_tracks');
    }
}
