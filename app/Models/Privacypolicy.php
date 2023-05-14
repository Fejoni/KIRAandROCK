<?php
/**
 * Created by VelgirLab
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
    use App\Traits\OrderSort;
class Privacypolicy extends Model
{
    use OrderSort;

    protected $table = "privacy_policy";

    protected $fillable = [
        "title", "text", "order", "published"
    ];


}
