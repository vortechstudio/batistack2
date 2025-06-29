<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_comptables', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('account')->nullable();
            $table->string('type')->nullable();
            $table->boolean('lettrage');
            $table->string('principal')->nullable();
            $table->float('initial')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_comptables');
    }
};
