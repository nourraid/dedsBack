<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
                $table->string('id_number')->nullable();          // رقم الهوية
    $table->string('phone')->nullable();              // رقم الهاتف
    $table->date('dob')->nullable();           // تاريخ الميلاد
    $table->string('blood_type')->nullable();         // فصيلة الدم
    $table->string('country')->nullable();            // الدولة
    $table->string('city')->nullable();               // المدينة
    $table->string('street')->nullable();             // الشارع
    $table->string('building_number')->nullable();    // رقم المبنى
    $table->string('weight')->nullable();             // الوزن
    $table->string('profile_picture')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('gender');
            $table->integer('age');
            $table->enum('role', ['user', 'admin', 'distributor', 'provider'])->default('user');
            $table->unsignedBigInteger('area_id')->nullable();
            $table->rememberToken();
            $table->timestamps();

            // المفتاح الخارجي
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
