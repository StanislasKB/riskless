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
        Schema::create('processuses', function (Blueprint $table) {
            $table->id();
            // Relation avec macroprocessus
            $table->foreignId('macroprocessus_id')->constrained()->onDelete('cascade');

            // Champs du processus
            $table->string('name');
            $table->string('domaine')->nullable();
            $table->string('intervenant')->nullable();
            $table->string('procedure')->nullable();
            $table->string('description')->nullable();
            $table->string('pilote')->nullable();
            $table->string('controle_interne')->nullable();
            $table->string('periodicite')->nullable();
            $table->string('piste_audit')->nullable();
            $table->string('indicateur_performance')->nullable();
            $table->string('actif')->nullable(); // Peut être bool selon ton usage
            $table->text('commentaire')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('processuses');
    }
};
