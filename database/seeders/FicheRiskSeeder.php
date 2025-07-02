<?php

namespace Database\Seeders;

use App\Models\FicheRisque;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FicheRisqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Exemple : boucle pour insérer plusieurs fiches
        for ($i = 1; $i <= 10; $i++) {
            FicheRisque::create([
                'account_id' => 1,
                'entite' => 'Entité ' . $i,
                'departement' => 'Département ' . $i,
                'service_id' => 1,
                'created_by' => 1,
                'validated_by' => null,
                'version' => 'v1.0',
                'entretiens' => 'Audit ' . $i,
                'index' => 'IDX-' . $i,
                'ref_supp' => 'REF-' . Str::random(5),
                'libelle_risk' => 'Risque ' . $i,
                'category_id' => 1,
                'description' => 'Description du risque ' . $i,
                'macroprocessus_id' => 1,
                'processus_id' => 1,
                'identified_by' => 'Analyste',
                'risk_cause' => json_encode([
                    'level_1' => 'Cause 1',
                    'level_2' => 'Cause 2',
                    'level_3' => 'Cause 3',
                ]),
                'frequence' => 'FREQUENT',
                'net_impact' => 'MOYEN',
                'net_impact_value' => 3,
                'brut_impact' => 'FORT',
                'brut_impact_value' => 4,
                'net_cotation' => 'MOYEN',
                'brut_cotation' => 'MAJEUR',
                'echelle_risque' => 'CRITIQUE',
                'manque_a_gagner' => rand(0, 1),
                'is_validated' => rand(0, 1),
                'consequence_reglementaire' => rand(0, 1),
                'consequence_juridique' => rand(0, 1),
                'consequence_humaine' => rand(0, 1),
                'interruption_processus' => rand(0, 1),
                'risque_image' => rand(0, 1),
                'insatisfaction_client' => rand(0, 1),
                'impact_risque_credit' => rand(0, 1),
                'impact_risque_marche' => rand(0, 1),
                'description_DMR' => 'DMR Description ' . $i,
                'appreciation_DMR' => 'ACCEPTABLE',
                'risque_a_piloter' => rand(0, 1),
                'indicateur_exposition' => rand(1, 10),
                'indicateur_risque_survenu' => rand(1, 10),
                'indicateur_risque_avere' => rand(1, 10),
                'indicateur_risque_evite' => rand(1, 10),
                'action_maitrise_risque' => rand(0, 1),
                'other_informations' => 'Infos supplémentaires pour risque ' . $i,
            ]);
        }
    }
}

