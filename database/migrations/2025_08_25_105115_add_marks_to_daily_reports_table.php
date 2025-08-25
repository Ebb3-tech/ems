<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('daily_reports', function (Blueprint $table) {
        $table->integer('marks')->nullable(); 
    });
}


public function down()
{
    Schema::table('daily_reports', function (Blueprint $table) {
        $table->dropColumn('marks');
    });
}

};
