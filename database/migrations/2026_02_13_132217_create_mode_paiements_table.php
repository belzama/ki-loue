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
        Schema::create('mode_paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pays_id')->constrained()->cascadeOnDelete();
            $table->string('designation');
            $table->enum('type', ['Mobile Money', 'Visa Card', 'Wallet', 'Espèce', 'Chèque', 'Virement', 'Autres']);
            $table->string('api_url')->nullable();
            $table->string('numero_compte')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mode_paiements');
    }
};
