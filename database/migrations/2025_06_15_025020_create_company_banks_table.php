<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_banks', function (Blueprint $table): void {
            $table->id();
            $table->string('item_id');
            $table->foreignId('bank_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamp('last_refreshed_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_banks');
    }
};
