<?php

declare(strict_types=1);

use App\Models\Tiers\Tiers;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tiers_addresses', function (Blueprint $table): void {
            $table->id();
            $table->string('address')->nullable();
            $table->string('code_postal')->nullable();
            $table->string('ville')->nullable();
            $table->string('pays')->nullable();
            $table->foreignIdFor(Tiers::class)->constrained('tiers')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tiers_addresses');
    }
};
