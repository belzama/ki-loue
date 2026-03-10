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
        Schema::create('types_dispositifs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categorie_id')->constrained()->cascadeOnDelete();
            $table->string('nom');
            $table->double('tarif_min', 10, 2)->default(0);
            $table->double('tarif_max', 10, 2)->default(0);
            $table->integer('nb_max_photo')->default(4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('types_dispositifs');
    }
};
