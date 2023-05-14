<?php
/**
* Created by Velgir
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTracksTable extends Migration
{
    public function up()
    {
        Schema::create('tracks', function (Blueprint $table) {
            $table->increments("id");
            $table->string('name');
            $table->string('description');
            $table->integer('temp');
            $table->integer('user_id',false,true);
            $table->integer('downloaded_count')->default(0);
            $table->integer('listened_count')->default(0);
            $table->integer('favorites_count')->default(0);
            $table->integer('last_download_at')->nullable();
            $table->integer('order');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('tracks');
    }
}
