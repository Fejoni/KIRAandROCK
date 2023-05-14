<?php
/**
 * Created by VelgirLab
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Genre extends Model
{
protected $table = "genres";

    protected $fillable = [
        "name", "published"
    ];

    public function image()
    {
        return $this->morphOne(Image::class,"object")->where("is_main",1);
    }

    public function tracks()
    {
        return $this->belongsToMany(Track::class, 'genres_tracks');
    }

}
