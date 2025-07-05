<?php

declare(strict_types=1);

use App\Models\Chantiers\Chantiers;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chantier_interventions', function (Blueprint $table) {
            $table->id();
            $table->date('date_intervention')->default(now());
            $table->text('description');
            $table->decimal('temps', 12, 2)->nullable();
            $table->foreignIdFor(Chantiers::class)->constrained('chantiers');
            $table->foreignIdFor(User::class, 'intervenant_id')->constrained('users');
            $table->boolean('facturable')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chantier_interventions');
    }
};
