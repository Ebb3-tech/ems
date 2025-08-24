<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('walk_in_customers', function (Blueprint $table) {
            $table->string('need')->nullable();
            $table->string('status')->nullable()->default('pending');
            $table->text('comment')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('walk_in_customers', function (Blueprint $table) {
            $table->dropColumn(['need', 'status', 'comment']);
        });
    }
};

