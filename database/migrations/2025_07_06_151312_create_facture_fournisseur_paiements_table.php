<?php

declare(strict_types=1);

use App\Models\Commerce\FactureFournisseur;
use App\Models\Core\ModeReglement;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facture_fournisseur_paiements', function (Blueprint $table) {
            $table->id();
            $table->date('date_paiement');
            $table->decimal('amount');
            $table->string('reference');
            $table->text('notes')->nullable();
            $table->foreignIdFor(FactureFournisseur::class)->constrained('facture_fournisseurs')->cascadeOnDelete();
            $table->foreignIdFor(ModeReglement::class)->constrained('mode_reglements');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facture_fournisseur_paiements');
    }
};
