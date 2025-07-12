<?php

use App\Models\RH\Employe;
use App\Models\RH\Paie\ProfilPaie;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fiche_paies', function (Blueprint $table) {
            $table->id();
            $table->date('periode');
            $table->decimal('salaire_brut');
            $table->decimal('salaire_net');
            $table->decimal('total_cotisation');
            $table->decimal('net_a_paye');
            $table->string('status')->default('draft');
            $table->foreignIdFor(Employe::class)->constrained('employes')->cascadeOnDelete();
            $table->foreignIdFor(ProfilPaie::class)->constrained('profil_paies')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fiche_paies');
    }
};
