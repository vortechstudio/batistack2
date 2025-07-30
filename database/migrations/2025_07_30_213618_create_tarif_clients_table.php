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
        Schema::create('tarif_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits')->cascadeOnDelete();
            $table->foreignId('tiers_id')->nullable()->constrained('tiers')->cascadeOnDelete(); // Client spécifique (null = tarif général)
            $table->string('type_client')->nullable(); // Type de client (particulier, professionnel, collectivité)
            $table->decimal('prix_vente', 10, 4); // Prix de vente unitaire HT
            $table->string('devise', 3)->default('EUR'); // Devise
            $table->decimal('marge_pourcentage', 5, 2)->nullable(); // Marge en pourcentage
            $table->decimal('marge_montant', 10, 4)->nullable(); // Marge en montant
            
            // Tarifs dégressifs
            $table->decimal('prix_quantite_1', 10, 4)->nullable(); // Prix pour quantité 1
            $table->decimal('seuil_quantite_1', 10, 3)->nullable(); // Seuil pour prix 1
            $table->decimal('prix_quantite_2', 10, 4)->nullable(); // Prix pour quantité 2
            $table->decimal('seuil_quantite_2', 10, 3)->nullable(); // Seuil pour prix 2
            $table->decimal('prix_quantite_3', 10, 4)->nullable(); // Prix pour quantité 3
            $table->decimal('seuil_quantite_3', 10, 3)->nullable(); // Seuil pour prix 3
            
            // Remises commerciales
            $table->decimal('remise_commerciale', 5, 2)->nullable(); // Remise commerciale en %
            $table->decimal('remise_fidelite', 5, 2)->nullable(); // Remise fidélité en %
            $table->decimal('remise_volume', 5, 2)->nullable(); // Remise volume en %
            $table->decimal('seuil_remise_volume', 10, 3)->nullable(); // Seuil pour remise volume
            
            // Conditions commerciales
            $table->string('conditions_paiement')->nullable(); // Conditions de paiement
            $table->integer('delai_livraison')->nullable(); // Délai de livraison en jours
            $table->decimal('frais_livraison', 8, 2)->nullable(); // Frais de livraison
            $table->decimal('seuil_franco_livraison', 8, 2)->nullable(); // Seuil franco de livraison
            $table->string('zone_livraison')->nullable(); // Zone de livraison
            
            // Validité et statut
            $table->date('date_debut'); // Date de début de validité
            $table->date('date_fin')->nullable(); // Date de fin de validité
            $table->boolean('actif')->default(true); // Tarif actif
            $table->boolean('tarif_public')->default(false); // Tarif public (affiché sur site web)
            $table->integer('priorite')->default(0); // Priorité (0 = plus haute)
            
            // Champs spécifiques aux services
            $table->decimal('tarif_horaire', 8, 2)->nullable(); // Tarif horaire pour services
            $table->decimal('tarif_deplacement', 8, 2)->nullable(); // Tarif déplacement
            $table->decimal('majoration_urgence', 5, 2)->nullable(); // Majoration urgence en %
            $table->decimal('majoration_weekend', 5, 2)->nullable(); // Majoration week-end en %
            $table->decimal('majoration_nuit', 5, 2)->nullable(); // Majoration nuit en %
            $table->decimal('majoration_ferie', 5, 2)->nullable(); // Majoration jour férié en %
            $table->json('grille_tarifaire')->nullable(); // Grille tarifaire complexe (JSON)
            $table->json('zones_intervention')->nullable(); // Zones d'intervention avec tarifs spécifiques
            
            // Tarifs spéciaux
            $table->decimal('prix_forfait', 10, 4)->nullable(); // Prix forfaitaire
            $table->text('description_forfait')->nullable(); // Description du forfait
            $table->decimal('prix_minimum', 10, 4)->nullable(); // Prix minimum de facturation
            $table->decimal('prix_maximum', 10, 4)->nullable(); // Prix maximum
            
            // Informations complémentaires
            $table->text('notes')->nullable(); // Notes sur le tarif
            $table->string('contact_commercial')->nullable(); // Contact commercial responsable
            $table->boolean('negociable')->default(false); // Prix négociable
            $table->json('metadata')->nullable(); // Données supplémentaires
            $table->timestamps();

            // Index pour les performances
            $table->index(['produit_id', 'actif'], 'tarif_clients_produit_actif_index');
            $table->index(['tiers_id', 'actif'], 'tarif_clients_tiers_actif_index');
            $table->index(['type_client', 'actif'], 'tarif_clients_type_actif_index');
            $table->index(['date_debut', 'date_fin'], 'tarif_clients_validite_index');
            $table->index(['tarif_public', 'actif'], 'tarif_clients_public_index');
            $table->index('priorite', 'tarif_clients_priorite_index');
            $table->index('negociable', 'tarif_clients_negociable_index');
            
            // Contrainte d'unicité pour éviter les doublons
            $table->unique(['produit_id', 'tiers_id', 'type_client', 'date_debut'], 'tarif_clients_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarif_clients');
    }
};
