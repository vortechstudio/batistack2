<?php

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
            $table->string('ref_fournisseur')->unique();
            $table->decimal('qte_minimal')->default(1);
            $table->decimal('prix_unitaire')->default(0);
            $table->integer('delai_livraison')->default(1); //En jours
            $table->string('barrecode')->nullable()->unique();

            $table->foreignId('produit_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
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
