<?php
/**
 * Created by VelgirLab
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Licensetemplate extends Model
{
protected $table = "license_templates";

    protected $fillable = [
        "title", "slug", "text"
    ];

}
