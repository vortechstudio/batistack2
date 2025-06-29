<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('rcs')->nullable();
            $table->string('logo')->nullable();
            $table->string('logo_wide')->nullable();
            $table->string('bridge_client_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('rcs', 'logo', 'logo_wide', 'bridge_client_id');
        });
    }
};
