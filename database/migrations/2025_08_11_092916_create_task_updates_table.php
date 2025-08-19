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
    Schema::create('task_updates', function (Blueprint $table) {
        $table->id();
        $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->enum('status', ['in_progress', 'completed', 'on_hold'])->default('in_progress');
        $table->text('update_notes')->nullable();
        $table->integer('time_spent')->nullable(); // in minutes
        $table->string('attachment')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_updates');
    }
};
