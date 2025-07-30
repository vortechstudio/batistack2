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
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 50)->unique(); // Référence produit unique
            $table->string('nom'); // Nom du produit/service
            $table->text('description')->nullable(); // Description détaillée
            $table->text('description_courte')->nullable(); // Description courte pour devis/factures
            $table->foreignId('category_produit_id')->constrained('category_produits')->cascadeOnDelete();
            $table->foreignId('unite_mesure_id')->constrained('unite_mesures')->cascadeOnDelete();
            $table->string('type'); // Utilise l'enum TypeProduit
            $table->boolean('actif')->default(true); // Produit actif/inactif
            $table->boolean('achat')->default(true); // Peut être acheté
            $table->boolean('vente')->default(true); // Peut être vendu
            
            // Champs spécifiques aux matériaux
            $table->decimal('poids', 8, 3)->nullable(); // Poids en kg
            $table->json('dimensions')->nullable(); // Dimensions (longueur, largeur, hauteur)
            $table->string('code_barre')->nullable(); // Code-barres
            $table->string('reference_fournisseur')->nullable(); // Référence chez le fournisseur principal
            $table->decimal('duree_vie', 8, 2)->nullable(); // Durée de vie en années (pour amortissement)
            $table->string('garantie')->nullable(); // Durée de garantie
            
            // Champs spécifiques aux services
            $table->decimal('duree_standard', 8, 2)->nullable(); // Durée standard en heures
            $table->string('competence_requise')->nullable(); // Compétence/qualification requise
            $table->boolean('deplacement_inclus')->default(false); // Déplacement inclus dans le tarif
            $table->decimal('cout_deplacement', 8, 2)->nullable(); // Coût du déplacement si non inclus
            $table->integer('delai_intervention')->nullable(); // Délai d'intervention en heures
            $table->boolean('urgence_possible')->default(false); // Intervention d'urgence possible
            $table->decimal('majoration_urgence', 5, 2)->nullable(); // Majoration urgence en %
            $table->json('horaires_disponibilite')->nullable(); // Horaires de disponibilité
            $table->json('zones_intervention')->nullable(); // Zones géographiques d'intervention
            
            // Champs communs
            $table->text('notes_techniques')->nullable(); // Notes techniques
            $table->json('certifications')->nullable(); // Certifications (CE, NF, etc.)
            $table->json('documents_joints')->nullable(); // Fiches techniques, photos, etc.
            $table->json('metadata')->nullable(); // Données supplémentaires
            $table->timestamps();

            // Index pour les performances
            $table->index('actif', 'produits_actif_index');
            $table->index('type', 'produits_type_index');
            $table->index(['achat', 'actif'], 'produits_achat_actif_index');
            $table->index(['vente', 'actif'], 'produits_vente_actif_index');
            $table->index('category_produit_id', 'produits_categorie_index');
            $table->index('reference_fournisseur', 'produits_ref_fournisseur_index');
            $table->index('competence_requise', 'produits_competence_index');
            $table->index('urgence_possible', 'produits_urgence_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
