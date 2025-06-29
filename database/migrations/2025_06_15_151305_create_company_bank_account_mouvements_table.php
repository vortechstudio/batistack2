<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_bank_account_mouvements', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('transaction_id');
            $table->string('title');
            $table->string('description');
            $table->float('amount');
            $table->date('date')->nullable();
            $table->date('booking_date')->nullable();
            $table->date('transaction_date')->nullable();
            $table->date('value_date')->nullable();
            $table->integer('category_id')->nullable();
            $table->string('type')->nullable();
            $table->boolean('future')->default(false);
            $table->foreignId('company_bank_account_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_bank_account_mouvements');
    }
};
