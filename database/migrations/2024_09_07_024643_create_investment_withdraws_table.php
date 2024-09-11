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
        Schema::create('investment_withdraws', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('investment_id');
            $table->unsignedBigInteger('account_id');
            $table->decimal('amount', 15, 2);
            $table->decimal('profit', 15, 2)->nullable();
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_withdraws');
    }
};
