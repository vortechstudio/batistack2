<?php

declare(strict_types=1);

use App\Models\RH\Employe;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employe_abscences', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('payed');
            $table->dateTime('date_debut');
            $table->dateTime('date_fin');
            $table->text('motif')->nullable();
            $table->string('status')->default('pending');
            $table->foreignIdFor(Employe::class)->constrained('employes')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employe_abscences');
    }
};
