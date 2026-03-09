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
        Schema::create('tarifs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pays_id')->constrained()->cascadeOnDelete();
            $table->string('designation');
            $table->integer('tranche_debut');
            $table->integer('tranche_fin');
            $table->decimal('tranche_valeur', 15, 4);
            $table->unique(['pays_id','tranche_debut']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarifs');
    }
};
