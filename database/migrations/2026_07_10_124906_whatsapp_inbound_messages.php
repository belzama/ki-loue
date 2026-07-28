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
        Schema::create('whatsapp_inbound_messages', function (Blueprint $table) {
            $table->id();
            $table->string('message_id')->unique(); // déduplication via l'ID Infobip
            $table->string('from_number');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('publication_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('reservation_id')->nullable()->constrained()->nullOnDelete();
            $table->text('message_recu');
            $table->text('reponse_envoyee')->nullable();
            $table->boolean('suspect_bot')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_inbound_messages');
    }
};
