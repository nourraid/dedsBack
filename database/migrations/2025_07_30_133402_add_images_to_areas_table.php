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
    Schema::table('areas', function (Blueprint $table) {
        if (!Schema::hasColumn('areas', 'map_image')) {
            $table->string('map_image')->nullable();
        }
        if (!Schema::hasColumn('areas', 'flag_image')) {
            $table->string('flag_image')->nullable();
        }
    });
}

public function down()
{
    Schema::table('areas', function (Blueprint $table) {
        $table->dropColumn(['map_image', 'flag_image']);
    });
}

};
