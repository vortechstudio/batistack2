<?php

use App\Models\Chantiers\Chantiers;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('frais', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('libelle');
            $table->text('description')->nullable();
            $table->date('date_frais');
            $table->decimal('amount_ht', 20, 2);
            $table->decimal('tva_rate', 20, 2);
            $table->decimal('amount_ttc', 20, 2);
            $table->foreignIdFor(User::class)->constrained('users');
            $table->foreignIdFor(Chantiers::class)->nullable();
            $table->string('justificatif_uri')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('frais');
    }
};
