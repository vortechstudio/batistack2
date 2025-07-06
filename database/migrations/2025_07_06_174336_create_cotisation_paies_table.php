<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cotisation_paies', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('organisme');
            $table->decimal('taux_salarial');
            $table->decimal('taux_patronal');
            $table->string('code_urssaf')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cotisation_paies');
    }
};
