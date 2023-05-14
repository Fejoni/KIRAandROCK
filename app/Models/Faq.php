<?php
/**
 * Created by VelgirLab
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
    use App\Traits\OrderSort;
class Faq extends Model
{
    use OrderSort;

    protected $table = "faqs";

    protected $fillable = [
        "question", "answer", "order", "published"
    ];


}
