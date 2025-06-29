<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tiers_fournisseurs', function (Blueprint $table): void {
            $table->id();
            $table->boolean('tva')->default(true);
            $table->string('num_tva')->nullable();
            $table->string('rem_relative')->nullable();
            $table->string('rem_fixe')->nullable();
            $table->foreignId('code_comptable_general')->constrained('plan_comptables');
            $table->foreignId('code_comptable_fournisseur')->constrained('plan_comptables');
            $table->foreignId('tiers_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();

        });

        Schema::create('tiers_clients', function (Blueprint $table): void {
            $table->id();
            $table->boolean('tva');
            $table->string('num_tva')->nullable();
            $table->string('rem_relative')->nullable();
            $table->string('rem_fixe')->nullable();
            $table->foreignId('code_comptable_general')->constrained('plan_comptables');
            $table->foreignId('code_comptable_client')->constrained('plan_comptables');
            $table->foreignId('tiers_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tiers_fournisseurs');
        Schema::dropIfExists('tiers_clients');
    }
};
