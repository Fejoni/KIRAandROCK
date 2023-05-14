<?php
/**
 * Created by VelgirLab
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
    use App\Traits\OrderSort;
class Useterm extends Model
{
    use OrderSort;

    protected $table = "use_terms";

    protected $fillable = [
        "title", "text", "order", "published"
    ];


}
