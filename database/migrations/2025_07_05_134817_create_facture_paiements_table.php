<?php

use App\Models\Commerce\Facture;
use App\Models\Core\ModeReglement;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('facture_paiements', function (Blueprint $table) {
            $table->id();
            $table->date('date_paiement');
            $table->decimal('amount', 20, 2);
            $table->string('reference')->nullable();
            $table->foreignIdFor(ModeReglement::class)->constrained('mode_reglements')->cascadeOnDelete();
            $table->foreignIdFor(Facture::class)->constrained('factures')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facture_paiements');
    }
};
