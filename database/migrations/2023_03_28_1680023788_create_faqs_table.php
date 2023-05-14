<?php
/**
* Created by Velgir
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaqsTable extends Migration
{
    public function up()
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->increments("id");
            $table->string('question');
            $table->string('answer');
            $table->integer('order');
            $table->boolean('published')->default('1');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('faqs');
    }
}
