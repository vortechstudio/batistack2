<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('profil_paies', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_paies');
    }
};
