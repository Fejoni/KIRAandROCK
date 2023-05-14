<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnDescriptionInTracksTable extends Migration
{
    public function up()
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->string('description')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->string('description')->nullable(false)->change();
        });
    }
}
