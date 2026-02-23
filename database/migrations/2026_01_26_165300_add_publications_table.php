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
        Schema::create('publications', function (Blueprint $table) {
        $table->id();
        $table->foreignId('dispositif_id')->constrained()->cascadeOnDelete();
        $table->foreignId('ville_id')->constrained()->cascadeOnDelete();
        $table->foreignId('devise_id')->constrained()->cascadeOnDelete();
        $table->decimal('tarif_location', 10, 2);
        $table->decimal('prix_publication', 10, 2)->default(0);
        $table->decimal('bonus_accorde', 10, 2)->default(0);
        $table->decimal('cout_publication', 10, 2)->default(0);
        $table->date('date_debut');
        $table->date('date_fin');
        $table->boolean('active')->default(true);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
