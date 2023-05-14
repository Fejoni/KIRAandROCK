<?php
/**
 * Created by VelgirLab
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Background extends Model
{
protected $table = "backgrounds";

    protected $fillable = [
        "title", "published"
    ];

    public function image()
    {
        return $this->morphOne(Image::class,"object")->where("is_main",1);
    }

    public function video()
    {
        return $this->hasOne(Video::class);
    }

}
