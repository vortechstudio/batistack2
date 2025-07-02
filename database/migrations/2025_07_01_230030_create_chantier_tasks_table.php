<?php

declare(strict_types=1);

use App\Models\Chantiers\Chantiers;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chantier_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->text('description')->nullable();
            $table->date('date_debut_prevu');
            $table->date('date_fin_prevue');
            $table->date('date_debut_reel')->nullable();
            $table->date('date_fin_reel')->nullable();
            $table->string('status')->default('todo');
            $table->string('priority')->default('low');
            $table->foreignIdFor(User::class, 'assigned_id')->nullable();
            $table->foreignIdFor(Chantiers::class)->constrained('chantiers');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chantier_tasks');
    }
};
