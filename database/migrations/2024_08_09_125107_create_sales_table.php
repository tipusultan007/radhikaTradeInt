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
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('invoice_no')->nullable();
            $table->unsignedBigInteger('referrer_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('dispatched_by')->nullable();
            $table->unsignedBigInteger('delivered_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->decimal('subtotal', 8, 2);
            $table->decimal('discount', 8, 2)->nullable();
            $table->decimal('customer_delivery_cost')->nullable();
            $table->decimal('owner_delivery_cost')->nullable();
            $table->decimal('total', 8, 2);
            $table->decimal('paid_amount', 8, 2)->default(0)->nullable();
            $table->date('date');
            $table->date('dispatched_date')->nullable();
            $table->date('delivered_date')->nullable();
            $table->string('note')->nullable();
            $table->text('payment_details')->nullable();
            $table->enum('status', ['pending','dispatched', 'delivered'])->default('pending');
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
