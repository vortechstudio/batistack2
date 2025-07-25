<?php

declare(strict_types=1);

use App\Models\Chantiers\Chantiers;
use App\Models\Commerce\Facture;
use App\Models\Tiers\Tiers;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avoirs', function (Blueprint $table) {
            $table->id();
            $table->string('num_avoir');
            $table->date('date_avoir');
            $table->decimal('amount_ht', 20, 2);
            $table->decimal('amount_ttc', 20, 2);
            $table->text('raison')->nullable();
            $table->foreignIdFor(Facture::class)->nullable()->cascadeOnDelete();
            $table->foreignIdFor(Tiers::class)->constrained('tiers')->cascadeOnDelete();
            $table->foreignIdFor(Chantiers::class)->nullable()->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'responsable_id')->constrained('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avoirs');
    }
};
