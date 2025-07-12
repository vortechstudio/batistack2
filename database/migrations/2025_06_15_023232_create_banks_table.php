<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banks', function (Blueprint $table): void {
            $table->id();
            $table->uuid('powens_uuid');
            $table->string('name');
            $table->string('status')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};
