<?php
/**
* Created by Velgir
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrivacyPolicyTable extends Migration
{
    public function up()
    {
        Schema::create('privacy_policy', function (Blueprint $table) {
            $table->increments("id");
            $table->string('title');
            $table->text('text');
            $table->integer('order');
            $table->boolean('published')->default(1);
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('privacy_policy');
    }
}
