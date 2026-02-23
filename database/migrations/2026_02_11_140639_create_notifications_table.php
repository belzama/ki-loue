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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            // Référence à l'utilisateur (optionnel)
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['Authentification', 'Dépôt', 'Retrait', 'Réservation']);
            $table->string('message');
            $table->boolean('read')->default(false);
            $table->boolean('send_email')->default(false);
            $table->boolean('send_whatsapp')->default(false);
            $table->string('send_email_address')->nullable();
            $table->string('send_whatsapp_number')->nullable();
            $table->datetime('send_email_date')->nullable();
            $table->datetime('send_whatsapp_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
