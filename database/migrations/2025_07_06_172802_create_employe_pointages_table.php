<?php

use App\Models\Chantiers\Chantiers;
use App\Models\RH\Employe;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employe_pointages', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('heure_arrive')->nullable();
            $table->time('heure_depart')->nullable();
            $table->decimal('heure_travaillees');
            $table->foreignIdFor(Employe::class)->constrained('employes')->cascadeOnDelete();
            $table->foreignIdFor(Chantiers::class)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employe_pointages');
    }
};
