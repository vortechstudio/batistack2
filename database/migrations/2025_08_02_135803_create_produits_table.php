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
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('name');
            $table->boolean('achat')->default(true); //Disponible à l'achat
            $table->boolean('vente')->default(true); //Disponible à la vente
            $table->text('description')->nullable();
            $table->string('serial_number')->nullable();
            $table->decimal('limit_stock', 6, 2)->default(0); //Peut être utiliser pour définir une alerte de stock en dessous de cette valeur
            $table->decimal('optimal_stock', 6, 2)->default(0); //Valeur utilisée pour remplir le stock lors de la demande de réapprovisionnement
            $table->decimal('poids_value', 6, 2)->default(0); // Poids pour une unité
            $table->string('poids_unite')->default('kg'); //Unité de poids (mg, g, kg, t) ENUM('mg', 'g', 'kg', 't')
            $table->decimal('longueur')->default(0);
            $table->decimal('largeur')->default(0);
            $table->decimal('hauteur')->default(0);
            $table->string('llh_unite')->default('mm'); // Unité de mesure (mm,cm,dm,m,ml) ENUM('mm', 'cm', 'dm', 'm', 'ml')

            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('entrepot_id')->constrained()->cascadeOnDelete();
            $table->foreignId('code_comptable_vente')->nullable()->constrained('plan_comptables');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
