<?php

declare(strict_types=1);

use App\Models\Commerce\Facture;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facture_lignes', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('libelle');
            $table->text('description')->nullable();
            $table->decimal('qte', 20, 2);
            $table->decimal('puht', 20, 2);
            $table->decimal('amount_ht', 20, 2);
            $table->decimal('tva_rate', 20, 2);
            $table->foreignIdFor(Facture::class)->constrained('factures')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facture_lignes');
    }
};
