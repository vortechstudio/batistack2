<?php

use App\Models\Tiers\Tiers;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chantiers', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->text('description')->nullable();
            $table->date('date_debut');
            $table->date('date_fin_prevu');
            $table->date('date_fin_reel')->nullable();
            $table->string('status')->default('planifie');
            $table->decimal('budget_estime', 12, 2);
            $table->decimal('budget_reel', 12,2);
            $table->foreignIdFor(Tiers::class)->constrained('tiers');
            $table->foreignIdFor(User::class, 'responsable_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chantiers');
    }
};
