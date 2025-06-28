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
        Schema::create('plan_actions', function (Blueprint $table) {
            $table->id();
            $table->string('index')->nullable();
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('type')->nullable(); //Atténuation, Prévention, Atténuation et Prévention
            $table->string('priorite')->nullable(); // Forte, Moyenne, Faible 
            $table->string('responsable')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('date_debut_prevue')->nullable();
            $table->dateTime('date_fin_prevue')->nullable();
            $table->enum('statut', ['A_LANCER','PLANIFIER', 'EN_COURS', 'TERMINER', 'ANNULER','PAUSE'])->default('A_LANCER');
            $table->integer('progression')->default(0); // en %
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_actions');
    }
};
