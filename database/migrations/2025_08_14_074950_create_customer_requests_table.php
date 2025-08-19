<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('customer_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('need'); // what the customer needs
            $table->enum('status', ['pending', 'processing', 'completed'])->default('pending');
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('customer_requests');
    }
};
