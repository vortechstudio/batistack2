<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rubrique_paies', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('libelle');
            $table->string('type');
            $table->boolean('imposable');
            $table->boolean('soumis_cotisation');
            $table->string('categorie')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rubrique_paies');
    }
};
