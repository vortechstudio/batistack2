<?php

use App\Models\Chantiers\Chantiers;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chantier_ressources', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Chantiers::class)->constrained('chantiers');
            $table->string('role')->default('employee');
            $table->date('date_affectation');
            $table->date('date_fin');
            $table->decimal('amount_fee', 20, 2)->nullable();
            $table->foreignId('employe_id')->constrained('employes')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chantier_ressources');
    }
};
