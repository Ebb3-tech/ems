<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->string('file_type')->nullable()->change();
            $table->string('file_path')->nullable()->change(); // 👈 also good to make path nullable
        });
    }

    public function down(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->string('file_type')->nullable(false)->change();
            $table->string('file_path')->nullable(false)->change();
        });
    }
};

