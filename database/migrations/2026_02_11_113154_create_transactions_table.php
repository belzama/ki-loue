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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // Référence utilisateur
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Montant de la transaction
            $table->decimal('montant', 15, 2); // 999 999 999 999.99 max

            // Type de transaction : dépôt, retrait, paiement réservation, bonus, remboursement, ajustement
            $table->enum('type', [
                'depot',
                'retrait'
            ])->default('depot');

            $table->enum('categorie', [
                'recharge',
                'bonus',
                'retrait',
                'paiement',
                'remboursement',
                'ajustement'
            ])->default('recharge');

            // Source ou référence externe (paiement mobile, Stripe, Flooz, TMoney)
            $table->string('reference')->nullable();

            // Statut de la transaction
            $table->enum('statut', [
                'en_attente',
                'effectuee',
                'annulee',
                'echouee'
            ])->default('en_attente');

            // Solde après transaction (optionnel mais pratique pour historisation)
            $table->decimal('solde_apres', 15, 2)->nullable();

            // Commentaire ou description
            $table->string('description')->nullable();

            $table->datetime('date_annulation')->nullable();
            $table->text('motif_annulation')->nullable();
            
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();;

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
