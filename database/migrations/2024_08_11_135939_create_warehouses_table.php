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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('packaging_type_id');
            $table->decimal('stock', 8, 2);
            $table->decimal('sale_price', 10, 2)->default(0);
            $table->decimal('dealer_price', 10, 2)->default(0);
            $table->decimal('commission_agent_price', 10, 2)->default(0);
            $table->decimal('retailer_price', 10, 2)->default(0);
            $table->decimal('retail_price', 10, 2)->default(0);
            $table->decimal('wholesale_price', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
