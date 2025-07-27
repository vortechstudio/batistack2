<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employe_contrats', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('cdi');
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->decimal('salaire_horaire');
            $table->decimal('heure_travail');
            $table->string('status')->default('actif');
            $table->foreignId('employe_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employe_contrats');
    }
};
