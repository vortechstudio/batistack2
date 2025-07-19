<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employe_contrats', function (Blueprint $table) {
            $table->timestamp('signed_start_at')->nullable();
            $table->string('signed_code_otp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employe_contrats', function (Blueprint $table) {
            $table->dropColumn('signed_start_at');
            $table->dropColumn('signed_code_otp');
        });
    }
};
