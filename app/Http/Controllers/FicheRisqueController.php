<?php

namespace App\Http\Controllers;

use App\Models\FicheRisque;
use App\Models\Indicateur;
use App\Models\Macroprocessus;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FicheRisqueController extends Controller
{
    public function form_view($uuid)
    {
        // dd($this->getCotationFinale('CRITIQUE','PERMANENT'));
        $service = Service::where('uuid', $uuid)->first();
        $account = Auth::user()->account;
        $processus = $account->processus()->get();
        $causes = $account->causes()->get();
        $macroprocessus = Macroprocessus::where('account_id', Auth::user()->account->id)->get();
        return view('service_manager.pages.referentiel.create', [
            'service' => $service,
            'macroprocessus' => $macroprocessus,
            'processus' => $processus,
            'causes' => $causes,
        ]);
    }


    public function store(Request $request, $uuid)
    {
        $service = Service::where('uuid', $uuid)->first();
        $validated = $request->validate([
            'part_index' => 'required|string|max:255',
            'entite' => 'required|string|max:255',
            'departement' => 'required|string|max:255',
            'version' => 'required|string|max:50',
            'entretiens' => 'required|string|max:255',
            'ref_supp' => 'nullable|string|max:255',
            'libelle_risk' => 'required|string|max:255',
            'category' => 'required|exists:risk_categories,id',
            'description' => 'required|string',
            'macroprocessus' => 'required|exists:macroprocessuses,id',
            'processus' => 'required|exists:processuses,id',
            'identified_by' => 'required|string|max:255',
            'cause_level_1' => 'required|exists:risk_causes,id',
            'cause_level_2' => 'required|exists:risk_causes,id',
            'cause_level_3' => 'required|exists:risk_causes,id',

            'frequence' => 'required|in:EXTREMEMENT_RARE,RARE,PEU_FREQUENT,FREQUENT,TRES_FREQUENT,PERMANENT',
            'brut_impact_value' => 'required|integer',

            'manque_a_gagner' => 'required|boolean',
            'consequence_reglementaire' => 'required|boolean',
            'consequence_juridique' => 'required|boolean',
            'consequence_humaine' => 'required|boolean',
            'interruption_processus' => 'required|boolean',
            'risque_image' => 'required|boolean',
            'insatisfaction_client' => 'required|boolean',
            'impact_risque_credit' => 'required|boolean',
            'impact_risque_marche' => 'required|boolean',

            'description_DMR' => 'required|string',
            'appreciation_DMR' => 'required|in:INEXISTANT,ACCEPTABLE,INSUFFISANT,CONFORME,EFFICACE',
            'risque_a_piloter' => 'required|boolean',

            'indicateur_exposition' => 'required|integer',
            'indicateur_risque_survenu' => 'required|integer',
            'indicateur_risque_avere' => 'required|integer',
            'indicateur_risque_evite' => 'required|integer',
            'action_maitrise_risque' => 'required|boolean',
            'other_informations' => 'nullable|string',

            'kri_departement' => 'nullable|string',
            'kri_libelle' => 'nullable|string',
            'kri_type' => 'nullable|string',
            'kri_precision_indicateur' => 'nullable|string',
            'kri_source' => 'nullable|string',
            'kri_chemin_access' => 'nullable|string',
            'kri_periodicite' => 'nullable|string',
            'kri_type_seuil' => 'nullable|string',
            'kri_seuil_alerte' => 'nullable|string',
            'kri_valeur_actuelle' => 'nullable|string',
            'kri_commentaire' => 'nullable|string',
            'kri_existing' => 'nullable|exists:indicateurs,id',


            'pa_type' => 'nullable|string',
            'pa_priorite' => 'nullable|string',
            'pa_responsable' => 'nullable|string',
            'pa_statut' => 'nullable|string',
            'pa_date_debut' => 'nullable|date',
            'pa_date_fin' => 'nullable|date',
            'pa_description' => 'nullable|strings',
            'pa_existing' => 'nullable|exists:plan_actions,id',


        ]);
        $net_impact_value = $this->getNetImpactValue($request->appreciation_DMR, $request->brut_impact_value);


        $fiche = FicheRisque::create([
            'created_by' => Auth::id(),
            'account_id' => Auth::user()->account->id,
            'service_id' => $service->id,
            'entite' => $request->entite,
            'departement' => $request->departement,
            'version' => $request->version,
            'entretiens' => $request->entretiens,
            'index' => $this->getNextIndex($request->part_index),
            'ref_supp' => $request->ref_supp ?? null,
            'libelle_risk' => $request->libelle_risk,
            'category_id' => $request->category,
            'description' => $request->description,
            'macroprocessus_id' => $request->macroprocessus,
            'processus_id' => $request->processus,
            'identified_by' => $request->identified_by,

            'risk_cause' => json_encode(['level_1' => $request->cause_level_1, 'level_2' => $request->cause_level_2, 'level_3' => $request->cause_level_3]),

            'frequence' => $request->frequence,
            'brut_impact_value' => $request->brut_impact_value,
            'net_impact_value' => $net_impact_value,
            'net_impact' => $this->getCotationFromValue($net_impact_value),
            'brut_impact' => $this->getCotationFromValue($request->brut_impact_value),
            'brut_cotation' => $this->getCotationFinale($this->getCotationFromValue($request->brut_impact_value), $request->frequence),
            'net_cotation' => $this->getCotationFinale($this->getCotationFromValue($net_impact_value), $request->frequence),
            'echelle_risque' => $this->getCotationFinale($this->getCotationFromValue($net_impact_value), $request->frequence),


            'manque_a_gagner' => $request->boolean('manque_a_gagner'),
            'consequence_reglementaire' => $request->boolean('consequence_reglementaire'),
            'consequence_juridique' => $request->boolean('consequence_juridique'),
            'consequence_humaine' => $request->boolean('consequence_humaine'),
            'interruption_processus' => $request->boolean('interruption_processus'),
            'risque_image' => $request->boolean('risque_image'),
            'insatisfaction_client' => $request->boolean('insatisfaction_client'),
            'impact_risque_credit' => $request->boolean('impact_risque_credit'),
            'impact_risque_marche' => $request->boolean('impact_risque_marche'),

            'description_DMR' => $request->description_DMR,
            'appreciation_DMR' => $request->appreciation_DMR,

            'risque_a_piloter' => $request->boolean('risque_a_piloter'),
            'action_maitrise_risque' => $request->boolean('action_maitrise_risque'),
            'a_indicateur' => $request->boolean('risque_a_piloter'),
            'other_informations' => $request->other_informations ?? null,
        ]);
        if ($request->boolean('risque_a_piloter')) {
            $indicateur = Indicateur::create([
                'created_by' => Auth::id(),
                'account_id' => Auth::user()->account->id,
                'service_id' => $service->id,
                'departement' => $request->kri_departement,
                'index' => $this->getNextKriIndex($fiche->index),
                'libelle' => $request->kri_libelle,
                'type' => $request->kri_type,
                'precision_indicateur' => $request->kri_precision_indicateur,
                'source' => $request->kri_source,
                'chemin_access' => $request->kri_chemin_access,
                'periodicite' => $request->kri_periodicite,
                'type_seuil' => $request->kri_type_seuil,
                'valeur_actuelle' => $request->kri_valeur_actuelle,
                'commentaire' => $request->kri_commentaire,
                'date_maj_valeur' => now()->toDateString(),
            ]);
        }

        return response()->json([
            'message' => 'Fiche de risque créée avec succès',
            'data' => $fiche
        ], 201);
    }






    private function getNetImpactValue($appeciation, $impact_brut)
    {
        $taux = [
            'INEXISTANT' => 0,
            'INSUFFISANT' => 25,
            'ACCEPTABLE' => 50,
            'CONFORME' => 75,
            'EFFICACE' => 90,
        ];
        if (!isset($taux[$appeciation])) {
            throw new \InvalidArgumentException("Appréciation invalide : $appeciation");
        }
        $pourcentage = $taux[$appeciation];
        $impact_net =  ($pourcentage / 100) * $impact_brut;
        return (int) ($impact_brut - $impact_net);
    }

    private function getCotationFromValue($value)
    {
        if ($value < 10_000) {
            return 'FAIBLE';
        } elseif ($value <= 100_000) {
            return 'MODERE';
        } elseif ($value <= 1_000_000) {
            return 'MOYEN';
        } elseif ($value <= 10_000_000) {
            return 'FORT';
        } elseif ($value <= 100_000_000) {
            return 'MAJEUR';
        } else {
            return 'CRITIQUE';
        }
    }
    private function getCotationFinale(string $cotation, string $frequence): string
    {
        $matrix = [
            'FAIBLE' => [
                'EXTREMEMENT_RARE' => 'FAIBLE',
                'RARE' => 'FAIBLE',
                'PEU_FREQUENT' => 'FAIBLE',
                'FREQUENT' => 'FAIBLE',
                'TRES_FREQUENT' => 'MOYEN',
                'PERMANENT' => 'MOYEN',
            ],
            'MODERE' => [
                'EXTREMEMENT_RARE' => 'FAIBLE',
                'RARE' => 'FAIBLE',
                'PEU_FREQUENT' => 'FAIBLE',
                'FREQUENT' => 'MOYEN',
                'TRES_FREQUENT' => 'FORT',
                'PERMANENT' => 'FORT',
            ],
            'MOYEN' => [
                'EXTREMEMENT_RARE' => 'FAIBLE',
                'RARE' => 'FAIBLE',
                'PEU_FREQUENT' => 'MOYEN',
                'FREQUENT' => 'FORT',
                'TRES_FREQUENT' => 'CRITIQUE',
                'PERMANENT' => 'CRITIQUE',
            ],
            'FORT' => [
                'EXTREMEMENT_RARE' => 'FAIBLE',
                'RARE' => 'MOYEN',
                'PEU_FREQUENT' => 'FORT',
                'FREQUENT' => 'CRITIQUE',
                'TRES_FREQUENT' => 'INACCEPTABLE',
                'PERMANENT' => 'INACCEPTABLE',
            ],
            'MAJEUR' => [
                'EXTREMEMENT_RARE' => 'MOYEN',
                'RARE' => 'FORT',
                'PEU_FREQUENT' => 'CRITIQUE',
                'FREQUENT' => 'INACCEPTABLE',
                'TRES_FREQUENT' => 'INACCEPTABLE',
                'PERMANENT' => 'INACCEPTABLE',
            ],
            'CRITIQUE' => [
                'EXTREMEMENT_RARE' => 'FORT',
                'RARE' => 'CRITIQUE',
                'PEU_FREQUENT' => 'INACCEPTABLE',
                'FREQUENT' => 'INACCEPTABLE',
                'TRES_FREQUENT' => 'INACCEPTABLE',
                'PERMANENT' => 'INACCEPTABLE',
            ],
        ];

        // Vérifie si la combinaison existe
        if (isset($matrix[$cotation][$frequence])) {
            return $matrix[$cotation][$frequence];
        }

        return 'Fréquence à renseigner';
    }
    private function getRiskNextIndex(string $prefix): string
    {
        $last = DB::table('fiche_risques')
            ->where('index', 'like', $prefix . '-%')
            ->selectRaw("MAX(CAST(SUBSTRING_INDEX(index, '-', -1) AS UNSIGNED)) as last_number")
            ->first();

        $nextNumber = ($last->last_number ?? 0) + 1;

        return $prefix . '-' . $nextNumber;
    }
    public function getNextKriIndex(string $prefix): string
    {
        $last = DB::table('indicateurs')
            ->where('index', 'like', $prefix . '%')
            ->selectRaw("MAX(CAST(REGEXP_SUBSTR(index, '[0-9]+$') AS UNSIGNED)) as last_number")
            ->first();

        $nextNumber = ($last->last_number ?? 0) + 1;

        return $prefix . $nextNumber;
    }
    public function getNextPaIndex(string $prefix): string
    {
        $last = DB::table('indicateurs')
            ->where('index', 'like', $prefix . '%')
            ->selectRaw("MAX(CAST(REGEXP_SUBSTR(index, '[0-9]+$') AS UNSIGNED)) as last_number")
            ->first();

        $nextNumber = ($last->last_number ?? 0) + 1;

        return $prefix . $nextNumber;
    }
}
