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
        Schema::create('note_frais_paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('note_frais_id')->constrained()->onDelete('cascade');
            $table->foreignId('mode_reglement_id')->constrained()->onDelete('cascade');
            $table->string('numero_paiement')->unique();
            $table->date('date_paiement');
            $table->decimal('montant', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('note_frais_paiements');
    }
};
