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
        Schema::create('fiche_risques', function (Blueprint $table) {
            $table->id();
             // Relations clés étrangères
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->string('entite');
            $table->string('departement');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('version')->nullable();
            $table->string('entretiens')->nullable();
            $table->string('index')->nullable();
            $table->string('ref_supp')->nullable();
            $table->string('libelle_risk');
            $table->foreignId('category_id')->constrained('risk_categories')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->foreignId('macroprocessus_id')->constrained('macroprocessuses')->onDelete('cascade');
            $table->foreignId('processus_id')->constrained()->onDelete('cascade');
            $table->string('identified_by')->nullable();

            // Causes (json)
            $table->json('risk_cause')->nullable(); // {level_1, level_2, level_3}

            // Énumérations
            $table->enum('frequence', ['EXTREMEMENT_RARE', 'RARE', 'PEU_FREQUENT','FREQUENT','TRES_FREQUENT','PERMANENT'])->nullable();
            $table->enum('net_impact', ['FAIBLE', 'MODERE', 'MOYEN','FORT','MAJEUR','CRITIQUE'])->nullable();
            $table->bigInteger('net_impact_value')->nullable();
            $table->enum('brut_impact', ['FAIBLE', 'MODERE', 'MOYEN','FORT','MAJEUR','CRITIQUE'])->nullable();
            $table->bigInteger('brut_impact_value')->nullable();
            $table->enum('net_cotation', ['FAIBLE', 'INACCEPTABLE', 'MOYEN','FORT','CRITIQUE'])->nullable();
            $table->enum('brut_cotation', ['FAIBLE', 'INACCEPTABLE', 'MOYEN','FORT','CRITIQUE'])->nullable();
            $table->enum('echelle_risque', ['FAIBLE', 'INACCEPTABLE', 'MOYEN','FORT','CRITIQUE'])->nullable();

            // Booleans : impacts / conséquences
            $table->boolean('manque_a_gagner')->default(false);
            $table->boolean('is_validated')->default(false);
            $table->boolean('consequence_reglementaire')->default(false);
            $table->boolean('consequence_juridique')->default(false);
            $table->boolean('consequence_humaine')->default(false);
            $table->boolean('interruption_processus')->default(false);
            $table->boolean('risque_image')->default(false);
            $table->boolean('insatisfaction_client')->default(false);
            $table->boolean('impact_risque_credit')->default(false);
            $table->boolean('impact_risque_marche')->default(false);

            // DMR
            $table->text('description_DMR')->nullable();
            $table->enum('appreciation_DMR', ['INEXISTANT', 'ACCEPTABLE', 'INSUFFISANT','CONFORME','EFFICACE'])->nullable();
            $table->boolean('risque_a_piloter')->default(false);

            // Indicateurs
            $table->integer('indicateur_exposition')->nullable();
            $table->integer('indicateur_risque_survenu')->nullable();
            $table->integer('indicateur_risque_avere')->nullable();
            $table->integer('indicateur_risque_evite')->nullable();
            $table->boolean('action_maitrise_risque')->default(false);

            // Autres
            $table->text('other_informations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fiche_risques');
    }
};
