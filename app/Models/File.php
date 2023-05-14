<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = ['track_id', 'type', 'path'];

    public function track()
    {
        return $this->belongsTo(Track::class);
    }
}
