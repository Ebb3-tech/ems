<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
    $table->id();
    $table->string('client_name');
    $table->string('client_phone');
    $table->foreignId('product_id')->constrained()->onDelete('cascade');
    $table->decimal('vendor_price', 10, 2);
    $table->decimal('expenses', 10, 2)->default(0);
    $table->decimal('sale_price', 10, 2);
    $table->timestamps();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
