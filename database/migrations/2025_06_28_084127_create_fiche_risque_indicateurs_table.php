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
        Schema::create('fiche_risque_indicateurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fiche_risque_id')->constrained('fiche_risques')->nullOnDelete();
            $table->foreignId('indicateur_id')->constrained('indicateurs')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fiche_risque_indicateurs');
    }
};
