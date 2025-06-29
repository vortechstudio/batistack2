<?php

declare(strict_types=1);

use App\Models\Tiers\Tiers;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tiers_logs', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->boolean('event_day')->default(false);
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->string('status')->nullable();
            $table->longText('description')->nullable();
            $table->string('lieu')->nullable();
            $table->foreignIdFor(User::class)->constrained('users');
            $table->foreignIdFor(Tiers::class)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tiers_logs');
    }
};
