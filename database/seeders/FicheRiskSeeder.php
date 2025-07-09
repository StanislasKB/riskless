<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    User,
    Account,
    Service,
    RiskCategory,
    Macroprocessus,
    Processus,
    RiskCause,
    FicheRisque,
    Indicateur,
    PlanAction,
    FicheRisqueIndicateur,
    FicheRisquePlanAction,
    EvolutionIndicateur,
    AvancementPlanAction
};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FicheRisqueSeeder extends Seeder
{
    // Tableaux de valeurs pour les champs enumérés
    private $frequences = [
        'EXTREMEMENT_RARE', 'RARE', 'PEU_FREQUENT',
        'FREQUENT', 'TRES_FREQUENT', 'PERMANENT'
    ];

    private $appreciationsDMR = [
        'INEXISTANT', 'ACCEPTABLE', 'INSUFFISANT', 'CONFORME', 'EFFICACE'
    ];

    private $typesPA = ['Correctif', 'Préventif', 'Amélioration'];
    private $prioritesPA = ['Basse', 'Moyenne', 'Haute', 'Critique'];
    private $statutsPA = ['En attente', 'En cours', 'Terminé', 'Abandonné'];

    public function run()
    {
        DB::transaction(function () {
            // Création des éléments pré-requis
            $account = Account::firstOrCreate(['name' => 'Compte de Test']);
            $user = User::firstOrCreate([
                'name' => 'Admin Test',
                'email' => 'admin@test.com',
                'password' => bcrypt('password'),
                'account_id' => $account->id
            ]);

            $service = Service::firstOrCreate([
                'name' => 'Service Test',
                'account_id' => $account->id,
                'uuid' => 'test-uuid'
            ]);

            // Création des entités liées
            $categories = RiskCategory::factory()->count(3)->create(['account_id' => $account->id]);
            $macroprocessus = Macroprocessus::factory()->count(2)->create(['account_id' => $account->id]);
            $processus = Processus::factory()->count(5)->create(['account_id' => $account->id]);
            $causes = RiskCause::factory()->count(10)->create(['account_id' => $account->id]);

            // Création de 20 fiches de risque
            for ($i = 1; $i <= 20; $i++) {
                $this->createFicheRisque(
                    $user,
                    $service,
                    $categories->random(),
                    $macroprocessus->random(),
                    $processus->random(),
                    $causes->random(),
                    $causes->random(),
                    $causes->random(),
                    $i
                );
            }
        });
    }

    private function createFicheRisque($user, $service, $category, $macroprocessus, $processus, $cause1, $cause2, $cause3, $index)
    {
        // Données de base
        $partIndex = 'ENT-' . date('Y');
        $fullIndex = $partIndex . '-' . str_pad($index, 3, '0', STR_PAD_LEFT);

        // Calculs des valeurs
        $brutImpactValue = rand(1, 20);
        $appreciationDMR = $this->appreciationsDMR[array_rand($this->appreciationsDMR)];
        $netImpactValue = $this->calculateNetImpact($appreciationDMR, $brutImpactValue);
        $brutImpact = $this->getCotationFromValue($brutImpactValue);
        $netImpact = $this->getCotationFromValue($netImpactValue);
        $brutCotation = $this->getCotationFinale($brutImpact, $this->frequences[array_rand($this->frequences)]);
        $netCotation = $this->getCotationFinale($netImpact, $this->frequences[array_rand($this->frequences)]);

        // Création de la fiche de risque
        $fiche = FicheRisque::create([
            'created_by' => $user->id,
            'account_id' => $user->account_id,
            'service_id' => $service->id,
            'entite' => 'Entité ' . $index,
            'departement' => 'Département ' . $index,
            'version' => 'v1.' . $index,
            'entretiens' => 'Entretien ' . $index,
            'index' => $fullIndex,
            'ref_supp' => 'REF-' . $index,
            'libelle_risk' => 'Risque ' . $index,
            'category_id' => $category->id,
            'description' => 'Description du risque ' . $index,
            'macroprocessus_id' => $macroprocessus->id,
            'processus_id' => $processus->id,
            'identified_by' => 'Identifié par ' . $index,
            'risk_cause' => json_encode([
                'level_1' => $cause1->id,
                'level_2' => $cause2->id,
                'level_3' => $cause3->id
            ]),
            'frequence' => $this->frequences[array_rand($this->frequences)],
            'brut_impact_value' => $brutImpactValue,
            'net_impact_value' => $netImpactValue,
            'net_impact' => $netImpact,
            'brut_impact' => $brutImpact,
            'brut_cotation' => $brutCotation,
            'net_cotation' => $netCotation,
            'echelle_risque' => $netCotation,
            'indicateur_exposition' => rand(1, 100),
            'indicateur_risque_survenu' => rand(1, 100),
            'indicateur_risque_avere' => rand(1, 100),
            'indicateur_risque_evite' => rand(1, 100),
            'manque_a_gagner' => (bool)rand(0, 1),
            'consequence_reglementaire' => (bool)rand(0, 1),
            'consequence_juridique' => (bool)rand(0, 1),
            'consequence_humaine' => (bool)rand(0, 1),
            'interruption_processus' => (bool)rand(0, 1),
            'risque_image' => (bool)rand(0, 1),
            'insatisfaction_client' => (bool)rand(0, 1),
            'impact_risque_credit' => (bool)rand(0, 1),
            'impact_risque_marche' => (bool)rand(0, 1),
            'description_DMR' => 'Description DMR ' . $index,
            'appreciation_DMR' => $appreciationDMR,
            'risque_a_piloter' => (bool)rand(0, 1),
            'action_maitrise_risque' => (bool)rand(0, 1),
            'other_informations' => 'Autres informations ' . $index,
        ]);

        // Gestion des indicateurs KRI
        if ($fiche->risque_a_piloter) {
            $choice = rand(0, 1) ? 'create' : 'select';

            if ($choice === 'create') {
                $indicateur = $this->createIndicateur($user, $service, $fiche);
                $this->linkIndicateurToRisk($fiche, $indicateur);
            } else {
                $existingIndicateur = Indicateur::inRandomOrder()->first() ?? $this->createIndicateur($user, $service, $fiche);
                $this->linkIndicateurToRisk($fiche, $existingIndicateur);
            }
        }

        // Gestion des plans d'action
        if ($fiche->action_maitrise_risque) {
            $choice = rand(0, 1) ? 'create_pa' : 'select_pa';

            if ($choice === 'create_pa') {
                $planAction = $this->createPlanAction($user, $service, $fiche);
                $this->linkPlanActionToRisk($fiche, $planAction);
            } else {
                $existingPlanAction = PlanAction::inRandomOrder()->first() ?? $this->createPlanAction($user, $service, $fiche);
                $this->linkPlanActionToRisk($fiche, $existingPlanAction);
            }
        }
    }

    private function createIndicateur($user, $service, $fiche)
    {
        return Indicateur::create([
            'created_by' => $user->id,
            'account_id' => $user->account_id,
            'service_id' => $service->id,
            'departement' => 'Département KRI',
            'index' => $fiche->index . '-KRI-001',
            'libelle' => 'Indicateur pour ' . $fiche->libelle_risk,
            'type' => 'Type ' . rand(1, 3),
            'precision_indicateur' => 'Précision ' . rand(1, 100),
            'source' => 'Source ' . rand(1, 5),
            'chemin_access' => '/chemin/indicateur',
            'periodicite' => 'Mensuel',
            'type_seuil' => 'Supérieur',
            'seuil_alerte' => rand(50, 100),
            'valeur_actuelle' => rand(30, 70),
            'commentaire' => 'Commentaire indicateur',
            'date_maj_valeur' => now(),
        ]);
    }

    private function createPlanAction($user, $service, $fiche)
    {
        $dateDebut = now()->addDays(rand(1, 30));
        $dateFin = $dateDebut->copy()->addDays(rand(30, 90));

        return PlanAction::create([
            'created_by' => $user->id,
            'account_id' => $user->account_id,
            'service_id' => $service->id,
            'index' => $fiche->index . '-PA-001',
            'type' => $this->typesPA[array_rand($this->typesPA)],
            'priorite' => $this->prioritesPA[array_rand($this->prioritesPA)],
            'responsable' => 'Responsable ' . rand(1, 5),
            'description' => 'Description PA pour ' . $fiche->libelle_risk,
            'date_debut_prevue' => $dateDebut,
            'date_fin_prevue' => $dateFin,
            'statut' => $this->statutsPA[array_rand($this->statutsPA)],
            'progression' => rand(0, 100),
        ]);
    }

    private function linkIndicateurToRisk($fiche, $indicateur)
    {
        FicheRisqueIndicateur::create([
            'fiche_risque_id' => $fiche->id,
            'indicateur_id' => $indicateur->id
        ]);

        EvolutionIndicateur::create([
            'created_by' => $fiche->created_by,
            'indicateur_id' => $indicateur->id,
            'valeur' => $indicateur->valeur_actuelle,
            'annee' => now()->year,
            'mois' => now()->month,
        ]);
    }

    private function linkPlanActionToRisk($fiche, $planAction)
    {
        FicheRisquePlanAction::create([
            'fiche_risque_id' => $fiche->id,
            'plan_action_id' => $planAction->id,
        ]);

        AvancementPlanAction::create([
            'created_by' => $fiche->created_by,
            'plan_action_id' => $planAction->id,
            'annee' => now()->year,
            'mois' => now()->month,
            'reste_a_faire' => 100 - $planAction->progression,
        ]);
    }

    // Méthodes de calcul reprises du contrôleur
    private function calculateNetImpact($appreciationDMR, $brutImpactValue)
    {
        switch ($appreciationDMR) {
            case 'INEXISTANT': return (int)($brutImpactValue * 0);
            case 'ACCEPTABLE': return (int)($brutImpactValue * 0.25);
            case 'INSUFFISANT': return (int)($brutImpactValue * 0.5);
            case 'CONFORME': return (int)($brutImpactValue * 0.75);
            case 'EFFICACE': return (int)($brutImpactValue * 1);
            default: return $brutImpactValue;
        }
    }

    private function getCotationFromValue($value)
    {
        if ($value >= 1 && $value <= 3) return 'A';
        if ($value >= 4 && $value <= 6) return 'B';
        if ($value >= 7 && $value <= 9) return 'C';
        if ($value >= 10 && $value <= 12) return 'D';
        return 'E';
    }

    private function getCotationFinale($cotationImpact, $frequence)
    {
        $matrice = [
            'A' => ['EXTREMEMENT_RARE' => 1, 'RARE' => 1, 'PEU_FREQUENT' => 1, 'FREQUENT' => 2, 'TRES_FREQUENT' => 2, 'PERMANENT' => 3],
            'B' => ['EXTREMEMENT_RARE' => 1, 'RARE' => 1, 'PEU_FREQUENT' => 2, 'FREQUENT' => 3, 'TRES_FREQUENT' => 3, 'PERMANENT' => 4],
            'C' => ['EXTREMEMENT_RARE' => 2, 'RARE' => 2, 'PEU_FREQUENT' => 3, 'FREQUENT' => 4, 'TRES_FREQUENT' => 4, 'PERMANENT' => 5],
            'D' => ['EXTREMEMENT_RARE' => 3, 'RARE' => 3, 'PEU_FREQUENT' => 4, 'FREQUENT' => 5, 'TRES_FREQUENT' => 5, 'PERMANENT' => 5],
            'E' => ['EXTREMEMENT_RARE' => 4, 'RARE' => 4, 'PEU_FREQUENT' => 5, 'FREQUENT' => 5, 'TRES_FREQUENT' => 5, 'PERMANENT' => 5],
        ];

        return $matrice[$cotationImpact][$frequence] ?? 1;
    }
}
