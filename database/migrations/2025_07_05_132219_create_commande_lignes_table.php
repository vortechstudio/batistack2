<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('commande_lignes', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('product');
            $table->string('libelle');
            $table->text('description')->nullable();
            $table->decimal('qte', 20, 2);
            $table->decimal('puth', 20, 2);
            $table->decimal('amount_ht', 20, 2);
            $table->decimal('tva_rate', 20, 2);
            $table->foreignId('commande_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commande_lignes');
    }
};
