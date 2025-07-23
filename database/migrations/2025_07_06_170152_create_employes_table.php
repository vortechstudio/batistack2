<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employes', function (Blueprint $table) {
            $table->id();
            $table->string('civility');
            $table->string('nom');
            $table->string('prenom');
            $table->string('adresse');
            $table->string('code_postal');
            $table->string('ville');
            $table->string('telephone');
            $table->string('portable')->nullable();
            $table->string('email')->unique();
            $table->string('poste')->nullable();
            $table->date('date_embauche')->nullable();
            $table->date('date_sortie')->nullable();
            $table->string('type_contrat')->default('employee')->nullable();
            $table->decimal('salaire_base')->nullable();
            $table->string('status')->default('actif');
            $table->string('bridge_user_id')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employes');
    }
};
