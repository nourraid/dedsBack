<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('name');                  // اسم المنطقة
            $table->text('description')->nullable(); // الوصف (اختياري)
            $table->string('map_image')->nullable(); // صورة الخريطة
            $table->string('flag_image')->nullable(); // صورة العلم
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('areas');
    }
};
