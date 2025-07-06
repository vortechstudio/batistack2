<?php

declare(strict_types=1);

use App\Models\Chantiers\Chantiers;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chantier_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->string('code_postal');
            $table->string('ville');
            $table->string('pays');
            $table->foreignIdFor(Chantiers::class)->constrained('chantiers');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chantier_addresses');
    }
};
