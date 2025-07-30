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
        Schema::create('category_produits', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique(); // Code court (ex: MAT, OUT, SER)
            $table->string('nom'); // Nom de la catégorie
            $table->text('description')->nullable(); // Description détaillée
            $table->string('couleur', 7)->default('#3B82F6'); // Couleur hexadécimale pour l'affichage
            $table->boolean('actif')->default(true); // Catégorie active/inactive
            $table->integer('ordre')->default(0); // Ordre d'affichage
            $table->foreignId('parent_id')->nullable()->constrained('category_produits')->nullOnDelete(); // Catégorie parent pour hiérarchie
            $table->json('metadata')->nullable(); // Données supplémentaires
            $table->timestamps();

            // Index pour les performances
            $table->index('actif', 'category_produits_actif_index');
            $table->index('parent_id', 'category_produits_parent_index');
            $table->index('ordre', 'category_produits_ordre_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_produits');
    }
};
