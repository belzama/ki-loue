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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            // Référence à la publication
            $table->foreignId('publication_id')->constrained()->cascadeOnDelete();
            
            // Référence à l'utilisateur (optionnel)
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Dates
            $table->date('date_reservation');      // date de la demande
            $table->date('date_demandee')->nullable();        // date souhaitée pour la réservation
            $table->integer('duree_demandee')->nullable();    // durée souhaitée (en jours)
            
            // Informations de l'utilisateur
            $table->string('nom_prenom')->nullable();
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('message')->nullable();

            // Validation
            $table->date('date_accordee')->nullable();   // date accordée
            $table->integer('duree_accordee')->nullable(); // durée accordée
            $table->string('motif_apporbation')->nullable();

            $table->enum('statut', ['Demandée', 'Accordée', 'Rejetée'])->default('Demandée');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
