<?php
/**
* Created by Velgir
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBackgroundsTable extends Migration
{
    public function up()
    {
        Schema::create('backgrounds', function (Blueprint $table) {
            $table->increments("id");
            $table->string('title');
            $table->boolean('published')->default(0);
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('backgrounds');
    }
}
