<?php
/**
* Created by Velgir
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsageTypesTable extends Migration
{
    public function up()
    {
        Schema::create('usage_types', function (Blueprint $table) {
            $table->increments("id");
            $table->string('name');
            $table->boolean('published')->default('1');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('usage_types');
    }
}
