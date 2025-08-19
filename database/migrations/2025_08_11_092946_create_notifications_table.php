<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
    $table->id();

    // Receiver
    $table->unsignedBigInteger('user_id');
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

    $table->string('title');
    $table->text('message');

    // Sender
    $table->unsignedBigInteger('created_by')->nullable();
    $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
