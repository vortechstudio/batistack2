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
            $table->bigInteger('bridge_id');
            $table->string('name');
            $table->string('group_name')->nullable();
            $table->string('logo')->nullable();
            $table->string('status_aggregate')->nullable();
            $table->string('status_payment')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};
