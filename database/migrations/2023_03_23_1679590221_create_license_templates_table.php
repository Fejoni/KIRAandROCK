<?php
/**
* Created by Velgir
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLicenseTemplatesTable extends Migration
{
    public function up()
    {
        Schema::create('license_templates', function (Blueprint $table) {
            $table->increments("id");
            $table->string('title');
            $table->string('slug');
            $table->text('text');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('license_templates');
    }
}
