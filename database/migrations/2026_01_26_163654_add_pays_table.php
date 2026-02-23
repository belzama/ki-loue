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
        Schema::create('pays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('continent_id')->constrained()->cascadeOnDelete();
            $table->foreignId('devise_id')->constrained()->cascadeOnDelete();
            $table->string('code', 5);
            $table->string('indicatif', 10);
            $table->string('nom');
            $table->string('nationalite');
            $table->string('langue_officielle');
            $table->double('taux_commission', 10, 2)->default(0);
            $table->double('bonus_sponsor', 10, 2)->default(0);
            $table->double('taux_sponsor_new', 10, 2)->default(0);
            $table->string('drapeau')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pays');
    }
};
