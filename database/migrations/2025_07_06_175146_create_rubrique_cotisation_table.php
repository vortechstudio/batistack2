<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rubrique_cotisation', function (Blueprint $table) {
            $table->id();

            $table->foreignId('rubrique_paie_id')->constrained('rubrique_paies')->cascadeOnDelete();
            $table->foreignId('cotisation_paie_id')->constrained('cotisation_paies')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rubrique_cotisation');
    }
};
