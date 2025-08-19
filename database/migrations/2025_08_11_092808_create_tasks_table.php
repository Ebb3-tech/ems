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
    Schema::create('tasks', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description');
        $table->foreignId('assigned_by')->constrained('users')->cascadeOnDelete();
        $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
        $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
        $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
        $table->enum('status', ['pending', 'in_progress', 'completed', 'on_hold'])->default('pending');
        $table->dateTime('deadline')->nullable();
        $table->string('attachment')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
