<?php

declare(strict_types=1);

use App\Models\Commerce\Avoir;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avoir_lignes', function (Blueprint $table) {
            $table->id();
            $table->string('type_avoir')->default('product');
            $table->string('libelle');
            $table->text('description')->nullable();
            $table->decimal('qte', 20, 2);
            $table->decimal('puht', 20, 2);
            $table->decimal('amount_ht', 20, 2);
            $table->decimal('tva_rate', 20, 2);
            $table->foreignIdFor(Avoir::class)->constrained('avoirs')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avoir_lignes');
    }
};
