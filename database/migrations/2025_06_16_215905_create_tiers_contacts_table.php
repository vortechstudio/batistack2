<?php

declare(strict_types=1);

use App\Models\Tiers\Tiers;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tiers_contacts', function (Blueprint $table): void {
            $table->id();
            $table->string('nom')->nullable();
            $table->string('prenom')->nullable();
            $table->string('civilite')->nullable();
            $table->string('poste')->nullable();
            $table->string('tel')->nullable();
            $table->string('portable')->nullable();
            $table->string('email')->nullable();
            $table->foreignIdFor(Tiers::class)->constrained('tiers');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tiers_contacts');
    }
};
