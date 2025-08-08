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
      Schema::create('data_settings', function (Blueprint $table) {
    $table->id();
    $table->boolean('allow_data_sharing')->default(false); // مثال
    $table->integer('data_retention_days')->default(365); // مدة الاحتفاظ بالبيانات
    $table->boolean('allow_account_deletion')->default(true);
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_settings');
    }
};
