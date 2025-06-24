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
        Schema::create('indicateurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('departement')->nullable();
            $table->string('index')->nullable();
            $table->string('libelle');
            $table->string('type')->nullable();
            $table->text('precision_indicateur')->nullable();
            $table->string('source')->nullable();
            $table->string('chemin_access')->nullable();
            $table->string('periodicite')->nullable();
            $table->string('type_seuil')->nullable(); 
            $table->double('seuil_alerte')->nullable();
            $table->double('valeur_actuelle')->nullable();
            $table->date('date_maj_valeur')->nullable();
            $table->text('commentaire')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indicateurs');
    }
};
