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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id')->nullable();     // le compte lié
            $table->unsignedBigInteger('causer_id')->nullable();      // utilisateur
            $table->string('causer_type')->nullable();                // modèle utilisateur
            $table->unsignedBigInteger('subject_id')->nullable();     // modèle affecté
            $table->string('subject_type')->nullable();               // classe du modèle
            $table->string('action');                                 // create, update, etc.
            $table->string('description')->nullable();                // message
            $table->json('properties')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
