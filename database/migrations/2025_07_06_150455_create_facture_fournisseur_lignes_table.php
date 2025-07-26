<?php

declare(strict_types=1);

use App\Models\Commerce\FactureFournisseur;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facture_fournisseur_lignes', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('product');
            $table->string('libelle');
            $table->text('description')->nullable();
            $table->decimal('qte', 20, 2);
            $table->decimal('puht', 20, 2);
            $table->decimal('amount_ht', 20, 2);
            $table->decimal('amount_ttc', 20, 2);
            $table->decimal('tva_rate', 20, 2);
            $table->foreignIdFor(FactureFournisseur::class)->constrained('facture_fournisseurs')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facture_fournisseur_lignes');
    }
};
