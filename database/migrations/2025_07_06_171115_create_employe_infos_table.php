<?php

declare(strict_types=1);

use App\Models\RH\Employe;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employe_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Employe::class)->constrained('employes')->cascadeOnDelete();
            $table->string('nationnality')->nullable();
            $table->string('num_cni')->nullable();
            $table->string('num_secu')->nullable();
            $table->string('num_passport')->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('lieu_naissance')->nullable();
            $table->string('pays_naissance')->nullable();
            $table->string('num_permis_btp')->nullable();
            $table->string('exp_permis_btp')->nullable();
            $table->string('process')->default('creating');
            $table->boolean('cni_transmit')->default(false);
            $table->boolean('btp_card_transmit')->default(false);
            $table->boolean('vital_card_transmit')->default(false);
            $table->boolean('rib_transmit')->default(false);
            $table->dateTime('cni_verified_at')->nullable();
            $table->dateTime('vital_verified_at')->nullable();
            $table->dateTime('btp_card_verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employe_infos');
    }
};
