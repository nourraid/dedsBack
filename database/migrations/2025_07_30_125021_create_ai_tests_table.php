<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAiTestsTable extends Migration
{
    public function up()
    {
        Schema::create('ai_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('test_number');
            $table->date('test_date');
            $table->string('ai_result');
            $table->text('ai_details')->nullable();
            $table->boolean('call')->default(false);
            $table->boolean('notification')->default(false);
            $table->text('notification_message')->nullable();
            $table->boolean('chat')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ai_tests');
    }
}
