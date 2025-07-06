<?php

use App\Models\Tiers\Tiers;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('facture_fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->string('num_facture');
            $table->date('date_facture');
            $table->date('date_echeance');
            $table->string('status')->default('nom_payer');
            $table->decimal('amount_ht', 20, 2);
            $table->decimal('amount_ttc', 20, 2);
            $table->decimal('amount_du', 20, 2);
            $table->text('notes')->nullable();
            $table->foreignIdFor(Tiers::class)->constrained('tiers')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facture_fournisseurs');
    }
};
