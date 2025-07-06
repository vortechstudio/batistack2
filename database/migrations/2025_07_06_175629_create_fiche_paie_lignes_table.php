<?php

use App\Models\RH\Paie\FichePaie;
use App\Models\RH\Paie\RubriquePaie;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fiche_paie_lignes', function (Blueprint $table) {
            $table->id();
            $table->decimal('montant');
            $table->integer('ordre');
            $table->foreignIdFor(FichePaie::class)->constrained('fiche_paies');
            $table->foreignIdFor(RubriquePaie::class)->constrained('rubrique_paies');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fiche_paie_lignes');
    }
};
