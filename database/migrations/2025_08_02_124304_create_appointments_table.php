<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
     Schema::create('appointments', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('ai_test_id')->nullable();
    $table->unsignedBigInteger('provider_id')->nullable();
    $table->dateTime('datetime');
    $table->text('note')->nullable();
    $table->timestamps();

    $table->foreign('ai_test_id')->references('id')->on('ai_tests')->onDelete('set null');
    $table->foreign('provider_id')->references('id')->on('providers')->onDelete('set null');
});

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};
