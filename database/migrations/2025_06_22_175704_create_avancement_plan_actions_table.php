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
        Schema::create('avancement_plan_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_action_id')->constrained('plan_actions')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->unsignedTinyInteger('mois');
            $table->unsignedTinyInteger('annee');
            $table->enum('statut', ['A_LANCER','PLANIFIER', 'EN_COURS', 'TERMINER', 'ANNULER','PAUSE'])->default('A_LANCER');
            $table->integer('reste_a_faire')->nullable();
            $table->text('commentaire')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avancement_plan_actions');
    }
};
