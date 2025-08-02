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
        Schema::create('produit_stocks', function (Blueprint $table) {
            $table->id();
            $table->integer('quantite')->default(0);

            $table->foreignId('produit_id')->constrained()->onDelete('cascade');
            $table->foreignId('entrepot_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produit_stocks');
    }
};
