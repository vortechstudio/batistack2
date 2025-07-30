<?php

declare(strict_types=1);

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
        Schema::create('unite_mesures', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique(); // Code court (ex: M, M2, M3, KG, H, U)
            $table->string('nom'); // Nom complet (ex: Mètre, Mètre carré, Heure)
            $table->string('symbole', 10); // Symbole d'affichage (ex: m, m², h)
            $table->string('type'); // Type: longueur, surface, volume, poids, temps, unité
            $table->text('description')->nullable(); // Description
            $table->boolean('actif')->default(true); // Unité active/inactive
            $table->decimal('facteur_conversion', 15, 6)->default(1); // Facteur de conversion vers l'unité de base
            $table->foreignId('unite_base_id')->nullable()->constrained('unite_mesures')->nullOnDelete(); // Unité de base pour conversion
            $table->json('metadata')->nullable(); // Données supplémentaires
            $table->timestamps();

            // Index pour les performances
            $table->index('actif', 'unite_mesures_actif_index');
            $table->index('type', 'unite_mesures_type_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unite_mesures');
    }
};
