<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('profil_rubrique', function (Blueprint $table) {
            $table->id();

            $table->foreignId('profil_paie_id')->constrained('profil_paies')->cascadeOnDelete();
            $table->foreignId('rubrique_paie_id')->constrained('rubrique_paies')->cascadeOnDelete();
            $table->integer('ordre');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_rubrique');
    }
};
