<?php

use App\Models\Commerce\Devis;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('devis_lignes', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('libelle');
            $table->text('description')->nullable();
            $table->decimal('qte', 12, 2);
            $table->decimal('puht', 12, 2);
            $table->decimal('amount_ht', 20, 2);
            $table->decimal('tva_rate', 12, 2);
            $table->foreignIdFor(Devis::class)->constrained('devis')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devis_lignes');
    }
};
