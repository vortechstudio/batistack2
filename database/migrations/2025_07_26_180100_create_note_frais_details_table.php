<?php

declare(strict_types=1);

use App\Models\RH\NoteFrais;
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
        Schema::create('note_frais_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(NoteFrais::class)->constrained('note_frais')->cascadeOnDelete();
            $table->date('date_frais'); // Date du frais
            $table->enum('type_frais', [
                'transport',
                'hebergement',
                'restauration',
                'carburant',
                'peage',
                'parking',
                'materiel',
                'formation',
                'telecommunication',
                'autre',
            ]);
            $table->string('libelle'); // Description du frais
            $table->decimal('montant_ht', 8, 2); // Montant HT
            $table->decimal('taux_tva', 5, 2)->default(20.00); // Taux de TVA
            $table->decimal('montant_tva', 8, 2); // Montant TVA calculé
            $table->decimal('montant_ttc', 8, 2); // Montant TTC
            $table->decimal('montant_valide', 8, 2)->nullable(); // Montant validé (si différent)
            $table->string('fournisseur')->nullable(); // Nom du fournisseur/commerçant
            $table->string('numero_facture')->nullable(); // Numéro de facture/ticket
            $table->enum('mode_paiement', ['especes', 'carte', 'cheque', 'virement', 'autre'])
                ->default('carte');
            $table->boolean('remboursable')->default(true); // Si le frais est remboursable
            $table->text('commentaire')->nullable(); // Commentaire sur le frais
            $table->string('justificatif_path')->nullable(); // Chemin vers le justificatif scanné
            $table->decimal('kilometrage', 8, 2)->nullable(); // Kilométrage pour les frais de transport
            $table->string('lieu_depart')->nullable(); // Lieu de départ (pour transport)
            $table->string('lieu_arrivee')->nullable(); // Lieu d'arrivée (pour transport)
            $table->foreignId('chantier_id')->nullable()->constrained('chantiers')->nullOnDelete(); // Lien avec un chantier si applicable
            $table->json('metadata')->nullable(); // Données supplémentaires
            $table->timestamps();

            // Index pour les performances
            $table->index(['note_frais_id', 'date_frais'], 'note_frais_details_note_date_index');
            $table->index('type_frais', 'note_frais_details_type_index');
            $table->index('remboursable', 'note_frais_details_remboursable_index');
            $table->index('chantier_id', 'note_frais_details_chantier_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('note_frais_details');
    }
};
