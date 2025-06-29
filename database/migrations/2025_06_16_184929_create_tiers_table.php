<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tiers', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('nature');
            $table->string('type');
            $table->string('code_tiers');
            $table->string('siren')->nullable();
            $table->boolean('tva');
            $table->string('num_tva')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tiers');
    }
};
