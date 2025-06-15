<?php

use App\Models\Core\CompanyBank;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('company_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_id');
            $table->string('name');
            $table->float('balance');
            $table->float('instante');
            $table->string('type');
            $table->string('iban');
            $table->foreignId('company_bank_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_bank_accounts');
    }
};
