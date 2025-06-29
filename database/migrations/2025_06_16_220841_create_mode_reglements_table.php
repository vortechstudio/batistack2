<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mode_reglements', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->json('type_paiement');
            $table->boolean('bridgeable')->default(false);
        });

        Schema::table('tiers_fournisseurs', function (Blueprint $table) {
            $table->foreignId('mode_reglement_id')->constrained();
        });

        Schema::table('tiers_clients', function (Blueprint $table) {
            $table->foreignId('mode_reglement_id')->constrained();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mode_reglements');
        Schema::dropColumns('tiers_fournisseurs', ['mode_reglement_id']);
        Schema::dropColumns('tiers_clients', ['mode_reglement_id']);
    }
};
