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
        //
        Schema::create('dispositifs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('types_dispositif_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('numero_immatriculation')->nullable();
            $table->string('marque');
            $table->string('modele');
            $table->string('designation');
            $table->text('description')->nullable();
            $table->enum('etat', ['Neuf', 'Bon', 'Révisé'])->default('Bon');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispositifs');
    }
};
