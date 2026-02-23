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
        Schema::create('type_dispositif_params', function (Blueprint $table) {
            $table->id();
            $table->foreignId('types_dispositif_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('label');
            $table->enum('value_type', ['string', 'int', 'decimal', 'date', 'datetime']);
            $table->string('list_values')->nullable();
            $table->string('numeric_value_unit')->nullable();
            $table->boolean('required')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_dispositif_params');
    }
};
