<?php

use App\Models\RH\Employe;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employe_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Employe::class)->constrained('employes');
            $table->string('nationnality')->nullable();
            $table->string('num_cni')->nullable();
            $table->string('num_secu')->nullable();
            $table->string('num_passport')->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('lieu_naissance')->nullable();
            $table->string('pays_naissance')->nullable();
            $table->string('num_permis_btp')->nullable();
            $table->string('exp_permis_btp')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employe_infos');
    }
};
