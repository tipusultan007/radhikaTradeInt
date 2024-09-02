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
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id')->nullable();
            $table->morphs('journalable');
            $table->enum('type',[
                'sale',
                'purchase',
                'customer_payment',
                'payment',
                'expense',
                'income',
                'asset',
                'opening_balance',
                'balance_transfer',
                'salary',
                'advance_salary',
            ])->nullable();
            $table->date('date');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_entries');
    }
};
