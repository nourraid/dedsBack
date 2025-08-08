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
    Schema::create('privacy_settings', function (Blueprint $table) {
        $table->id();
        $table->text('privacy_policy')->nullable(); // نص سياسة الخصوصية
        $table->boolean('can_download_reports')->default(true); // هل يمكن تحميل التقارير؟
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
        Schema::dropIfExists('privacy_settings');
    }
};
