<?php

use App\Models\Chantiers\Chantiers;
use App\Models\Tiers\Tiers;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chantier_depenses', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->decimal('montant', 12, 2);
            $table->date('date_depense');
            $table->string('type_depense');
            $table->string('invoice_ref')->nullable();
            $table->foreignIdFor(Tiers::class)->constrained('tiers');
            $table->foreignIdFor(Chantiers::class)->constrained('chantiers');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chantier_depenses');
    }
};
