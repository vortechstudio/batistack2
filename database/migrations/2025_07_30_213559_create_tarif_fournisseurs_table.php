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
        Schema::create('tarif_fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits')->cascadeOnDelete();
            $table->foreignId('tiers_id')->constrained('tiers')->cascadeOnDelete(); // Fournisseur
            $table->string('reference_fournisseur')->nullable(); // Référence chez le fournisseur
            $table->decimal('prix_achat', 10, 4); // Prix d'achat unitaire HT
            $table->string('devise', 3)->default('EUR'); // Devise
            $table->decimal('quantite_minimum', 10, 3)->default(1); // Quantité minimum de commande
            $table->decimal('quantite_conditionnement', 10, 3)->default(1); // Quantité par conditionnement
            $table->string('conditionnement')->nullable(); // Type de conditionnement (carton, palette, etc.)
            
            // Remises et conditions
            $table->decimal('remise_quantite_1', 5, 2)->nullable(); // Remise pour quantité 1
            $table->decimal('seuil_quantite_1', 10, 3)->nullable(); // Seuil pour remise 1
            $table->decimal('remise_quantite_2', 5, 2)->nullable(); // Remise pour quantité 2
            $table->decimal('seuil_quantite_2', 10, 3)->nullable(); // Seuil pour remise 2
            $table->decimal('remise_quantite_3', 5, 2)->nullable(); // Remise pour quantité 3
            $table->decimal('seuil_quantite_3', 10, 3)->nullable(); // Seuil pour remise 3
            
            // Conditions commerciales
            $table->integer('delai_livraison')->nullable(); // Délai de livraison en jours
            $table->string('conditions_paiement')->nullable(); // Conditions de paiement
            $table->decimal('frais_port', 8, 2)->nullable(); // Frais de port
            $table->decimal('seuil_franco_port', 8, 2)->nullable(); // Seuil franco de port
            $table->string('zone_livraison')->nullable(); // Zone de livraison
            
            // Validité et statut
            $table->date('date_debut'); // Date de début de validité
            $table->date('date_fin')->nullable(); // Date de fin de validité
            $table->boolean('actif')->default(true); // Tarif actif
            $table->boolean('prefere')->default(false); // Fournisseur préféré pour ce produit
            $table->integer('priorite')->default(0); // Priorité (0 = plus haute)
            
            // Champs spécifiques aux services
            $table->decimal('tarif_horaire', 8, 2)->nullable(); // Tarif horaire pour services
            $table->decimal('tarif_deplacement', 8, 2)->nullable(); // Tarif déplacement
            $table->decimal('majoration_weekend', 5, 2)->nullable(); // Majoration week-end en %
            $table->decimal('majoration_nuit', 5, 2)->nullable(); // Majoration nuit en %
            $table->decimal('majoration_ferie', 5, 2)->nullable(); // Majoration jour férié en %
            $table->json('grille_tarifaire')->nullable(); // Grille tarifaire complexe (JSON)
            
            // Informations complémentaires
            $table->text('notes')->nullable(); // Notes sur le tarif
            $table->string('contact_commercial')->nullable(); // Contact commercial chez le fournisseur
            $table->json('metadata')->nullable(); // Données supplémentaires
            $table->timestamps();

            // Index pour les performances
            $table->index(['produit_id', 'actif'], 'tarif_fournisseurs_produit_actif_index');
            $table->index(['tiers_id', 'actif'], 'tarif_fournisseurs_tiers_actif_index');
            $table->index(['date_debut', 'date_fin'], 'tarif_fournisseurs_validite_index');
            $table->index(['prefere', 'actif'], 'tarif_fournisseurs_prefere_index');
            $table->index('priorite', 'tarif_fournisseurs_priorite_index');
            $table->index('reference_fournisseur', 'tarif_fournisseurs_reference_index');
            
            // Contrainte d'unicité pour éviter les doublons
            $table->unique(['produit_id', 'tiers_id', 'date_debut'], 'tarif_fournisseurs_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarif_fournisseurs');
    }
};
