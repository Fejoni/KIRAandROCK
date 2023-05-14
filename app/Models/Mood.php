<?php
/**
 * Created by VelgirLab
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Mood extends Model
{
protected $table = "moods";

    protected $fillable = [
        "name", "published"
    ];

    public function tracks()
    {
        return $this->belongsToMany(Track::class, 'moods_tracks');
    }
}
