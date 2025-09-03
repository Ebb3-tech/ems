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
    Schema::table('chats', function (Blueprint $table) {
        $table->enum('file_type', ['image','audio','pdf'])->nullable()->change();
    });
}

public function down()
{
    Schema::table('chats', function (Blueprint $table) {
        $table->enum('file_type', ['image','audio'])->nullable()->change();
    });
}

};
