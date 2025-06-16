<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('condition_reglements', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('name_document');
            $table->integer('nb_jours');
            $table->boolean('fdm');
        });

        Schema::table('tiers_fournisseurs', function (Blueprint $table) {
            $table->foreignId('condition_reglement_id')->constrained();
        });

        Schema::table('tiers_clients', function (Blueprint $table) {
            $table->foreignId('condition_reglement_id')->constrained();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('condition_reglements');
        Schema::dropColumns('tiers_fournisseurs', ['condition_reglement_id']);
        Schema::dropColumns('tiers_clients', ['condition_reglement_id']);
    }
};
