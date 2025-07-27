<?php

declare(strict_types=1);

use App\Models\RH\Employe;
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
        Schema::create('note_frais', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique(); // Numéro de la note de frais (ex: NF-2025-001)
            $table->foreignIdFor(Employe::class)->constrained('employes')->cascadeOnDelete();
            $table->date('date_debut'); // Date de début de la période
            $table->date('date_fin'); // Date de fin de la période
            $table->date('date_soumission')->nullable(); // Date de soumission de la note
            $table->string('statut')
                ->default('brouillon');
            $table->text('motif_refus')->nullable(); // Motif en cas de refus
            $table->decimal('montant_total', 10, 2)->default(0); // Montant total calculé
            $table->decimal('montant_valide', 10, 2)->nullable(); // Montant validé (peut être différent)
            $table->text('commentaire_employe')->nullable(); // Commentaire de l'employé
            $table->text('commentaire_validateur')->nullable(); // Commentaire du validateur
            $table->foreignId('validateur_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('date_validation')->nullable();
            $table->timestamp('date_paiement')->nullable();
            $table->string('reference_paiement')->nullable(); // Référence du virement/chèque
            $table->json('metadata')->nullable(); // Données supplémentaires (fichiers joints, etc.)
            $table->timestamps();

            // Index pour les performances
            $table->index(['employe_id', 'date_debut', 'date_fin'], 'note_frais_employe_periode_index');
            $table->index('statut', 'note_frais_statut_index');
            $table->index('date_soumission', 'note_frais_date_soumission_index');
            $table->index('validateur_id', 'note_frais_validateur_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('note_frais');
    }
};
