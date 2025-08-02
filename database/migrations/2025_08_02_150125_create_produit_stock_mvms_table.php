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
        Schema::create('produit_stock_mvms', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('libelle'); // Désignation courante du mouvement
            $table->integer('quantite')->default(0);
            $table->string('type')->default('entree'); // ENUM('entree', 'sortie')

            $table->foreignId('produit_stock_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produit_stock_mvms');
    }
};
