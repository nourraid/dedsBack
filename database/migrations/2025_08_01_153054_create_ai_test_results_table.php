<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAiTestResultsTable extends Migration
{
    public function up()
    {
        Schema::create('ai_test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ai_test_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('standard');
            $table->string('value');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ai_test_results');
    }
}
