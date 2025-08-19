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
    Schema::create('notification_replies', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('notification_id');
        $table->unsignedBigInteger('sender_id');   // user who replies
        $table->unsignedBigInteger('receiver_id'); // user who receives reply
        $table->text('message');
        $table->timestamps();

        $table->foreign('notification_id')->references('id')->on('notifications')->onDelete('cascade');
        $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_replies');
    }
};
