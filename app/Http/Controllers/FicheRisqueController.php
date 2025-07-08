<?php

namespace App\Http\Controllers;

use App\Mail\NewKriMail;
use App\Mail\NewRiskMail;
use App\Mail\PlanActionAlertMail;
use App\Models\AvancementPlanAction;
use App\Models\EvolutionIndicateur;
use App\Models\FicheRisque;
use App\Models\FicheRisqueIndicateur;
use App\Models\FicheRisquePlanAction;
use App\Models\Indicateur;
use App\Models\Macroprocessus;
use App\Models\PlanAction;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class FicheRisqueController extends Controller
{
    public function index($uuid)
    {
        $service = Service::where('uuid', $uuid)->first();
        $fiche_risques = $service->fiche_risques()->get();
        return view('service_manager.pages.referentiel.index', [
            'service' => $service,
            'fiche_risques' => $fiche_risques,
        ]);
    }

    public function form_view($uuid)
    {
        // dd($this->getCotationFinale('CRITIQUE','PERMANENT'));
        $service = Service::where('uuid', $uuid)->first();
        $account = Auth::user()->account;
        $processus = $account->processus()->get();
        $causes = $account->causes()->get();
        $categories = $account->categories()->get();
        $macroprocessus = Macroprocessus::where('account_id', Auth::user()->account->id)->get();
        return view('service_manager.pages.referentiel.create', [
            'service' => $service,
            'macroprocessus' => $macroprocessus,
            'processus' => $processus,
            'causes' => $causes,
            'categories' => $categories,
        ]);
    }

    public function edit_view($uuid, $id)
    {
        $service = Service::where('uuid', $uuid)->first();
        $fiche_risque = FicheRisque::findOrFail($id);
        $account = Auth::user()->account;
        $processus = $account->processus()->get();
        $causes = $account->causes()->get();
        $categories = $account->categories()->get();
        $macroprocessus = Macroprocessus::where('account_id', Auth::user()->account->id)->get();
        return view('service_manager.pages.referentiel.edit', [
            'service' => $service,
            'fiche_risque' => $fiche_risque,
            'macroprocessus' => $macroprocessus,
            'processus' => $processus,
            'causes' => $causes,
            'categories' => $categories,
        ]);
    }
    public function detail_view($uuid, $id)
    {

        $service = Service::where('uuid', $uuid)->first();
        $fiche_risque = FicheRisque::findOrFail($id);
        $account = Auth::user()->account;
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('owner') && $account->id != $fiche_risque->creator->account->id) {
            abort(403);
        }

        return view('service_manager.pages.fiche_risque.index', [
            'service' => $service,
            'fiche_risque' => $fiche_risque,
        ]);
    }


    public function store(Request $request, $uuid)
    {
        $service = Service::where('uuid', $uuid)->first();
        $messages = [
            // Champs simples
            'part_index.required' => 'L’index est requis.',
            'entite.required' => 'L’entité est requise.',
            'departement.required' => 'Le département est requis.',
            'version.required' => 'La version est requise.',
            'entretiens.required' => 'Le champ des entretiens est requis.',
            'ref_supp.string' => 'La référence supplémentaire doit être une chaîne de caractères.',
            'libelle_risk.required' => 'Le libellé du risque est requis.',
            'description.required' => 'La description est requise.',
            'identified_by.required' => 'Le champ "Identifié par" est requis.',

            // Relations
            'category.exists' => 'La catégorie de risque sélectionnée est invalide.',
            'macroprocessus.exists' => 'Le macroprocessus sélectionné est invalide.',
            'processus.exists' => 'Le processus sélectionné est invalide.',
            'cause_level_1.exists' => 'La cause niveau 1 est invalide.',
            'cause_level_2.exists' => 'La cause niveau 2 est invalide.',
            'cause_level_3.exists' => 'La cause niveau 3 est invalide.',

            // Fréquence et impact
            'frequence.required' => 'La fréquence est requise.',
            'frequence.in' => 'La fréquence sélectionnée est invalide.',
            'brut_impact_value.required' => 'La valeur d’impact brut est requise.',
            'brut_impact_value.integer' => 'La valeur d’impact brut doit être un nombre entier.',

            // Conséquences
            'manque_a_gagner.required' => 'Le champ "Manque à gagner" est requis.',
            'consequence_reglementaire.required' => 'Le champ "Conséquence réglementaire" est requis.',
            'consequence_juridique.required' => 'Le champ "Conséquence juridique" est requis.',
            'consequence_humaine.required' => 'Le champ "Conséquence humaine" est requis.',
            'interruption_processus.required' => 'Le champ "Interruption de processus" est requis.',
            'risque_image.required' => 'Le champ "Risque d’image" est requis.',
            'insatisfaction_client.required' => 'Le champ "Insatisfaction client" est requis.',
            'impact_risque_credit.required' => 'Le champ "Impact risque crédit" est requis.',
            'impact_risque_marche.required' => 'Le champ "Impact risque marché" est requis.',

            // DMR
            'description_DMR.required' => 'La description DMR est requise.',
            'appreciation_DMR.required' => 'L’appréciation DMR est requise.',
            'appreciation_DMR.in' => 'L’appréciation DMR sélectionnée est invalide.',

            // Indicateurs
            'indicateur_exposition.required' => 'L’indicateur d’exposition est requis.',
            'indicateur_risque_survenu.required' => 'L’indicateur de risque survenu est requis.',
            'indicateur_risque_avere.required' => 'L’indicateur de risque avéré est requis.',
            'indicateur_risque_evite.required' => 'L’indicateur de risque évité est requis.',

            // kri_choice
            'kri_choice.required_if' => 'Le choix de l’indicateur KRI est requis si le risque est à piloter.',
            'kri_existing.required_if' => 'Un indicateur existant est requis si vous avez choisi "sélectionner".',
            // 'kri_existing.exists' => 'L’indicateur KRI sélectionné est invalide.',

            // kri create fields
            'kri_departement.required_if' => 'Le département de l’indicateur est requis.',
            'kri_libelle.required_if' => 'Le libellé de l’indicateur est requis.',
            'kri_type.required_if' => 'Le type de l’indicateur est requis.',
            'kri_precision_indicateur.required_if' => 'La précision de l’indicateur est requise.',
            'kri_source.required_if' => 'La source de l’indicateur est requise.',
            'kri_chemin_access.required_if' => 'Le chemin d’accès à l’indicateur est requis.',
            'kri_periodicite.required_if' => 'La périodicité de l’indicateur est requise.',
            'kri_type_seuil.required_if' => 'Le type de seuil est requis.',
            'kri_seuil_alerte.required_if' => 'Le seuil d’alerte est requis.',
            'kri_valeur_actuelle.required_if' => 'La valeur actuelle est requise.',

            // Plan d'action
            'pa_choice.required_if' => 'Le choix du plan d’action est requis si une action de maîtrise est activée.',
            'pa_type.required_if' => 'Le type de plan d’action est requis.',
            'pa_priorite.required_if' => 'La priorité du plan d’action est requise.',
            'pa_responsable.required_if' => 'Le responsable du plan d’action est requis.',
            'pa_statut.required_if' => 'Le statut du plan d’action est requis.',
            'pa_date_debut.required_if' => 'La date de début du plan d’action est requise.',
            'pa_date_debut.date' => 'La date de début n’est pas valide.',
            'pa_date_fin.required_if' => 'La date de fin du plan d’action est requise.',
            'pa_date_fin.date' => 'La date de fin n’est pas valide.',
            'pa_description.required_if' => 'La description du plan d’action est requise.',
            'pa_existing.required_if' => 'Un plan d’action existant doit être sélectionné.',
            // 'pa_existing.exists' => 'Le plan d’action sélectionné est invalide.',
            'pa_date_debut.after_or_equal' => 'La date de début ne peut pas être dans le passé.',
            'pa_date_fin.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',

        ];
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

            'kri_choice' => 'required_if:risque_a_piloter,1|string',
            'kri_departement' => 'required_if:kri_choice,create|string',
            'kri_libelle' => 'required_if:kri_choice,create|string',
            'kri_type' => 'required_if:kri_choice,create|string',
            'kri_precision_indicateur' => 'required_if:kri_choice,create|string',
            'kri_source' => 'required_if:kri_choice,create|string',
            'kri_chemin_access' => 'required_if:kri_choice,create|string',
            'kri_periodicite' => 'required_if:kri_choice,create|string',
            'kri_type_seuil' => 'required_if:kri_choice,create|string',
            'kri_seuil_alerte' => 'required_if:kri_choice,create',
            'kri_valeur_actuelle' => 'required_if:kri_choice,create',
            'kri_commentaire' => 'nullable|string',

            'kri_existing' => 'required_if:kri_choice,select|nullable',


            'pa_choice' => 'required_if:action_maitrise_risque,1|string|nullable',
            'pa_type' => 'required_if:pa_choice,create_pa|string|nullable',
            'pa_priorite' => 'required_if:pa_choice,create_pa|string|nullable',
            'pa_responsable' => 'required_if:pa_choice,create_pa|string|nullable',
            'pa_statut' => 'required_if:pa_choice,create_pa|string|nullable',
            'pa_date_debut' => 'required_if:pa_choice,create_pa|date|after_or_equal:today|nullable',
            'pa_date_fin' => 'required_if:pa_choice,create_pa|date|after_or_equal:pa_date_debut|nullable',
            'pa_description' => 'required_if:pa_choice,create_pa|string|nullable',
            'pa_existing' => 'required_if:pa_choice,select_pa|nullable',


        ], $messages);


        $net_impact_value = $this->getNetImpactValue($request->appreciation_DMR, $request->brut_impact_value);


        $fiche = FicheRisque::create([
            'created_by' => Auth::id(),
            'account_id' => Auth::user()->account->id,
            'service_id' => $service->id,
            'entite' => $request->entite,
            'departement' => $request->departement,
            'version' => $request->version,
            'entretiens' => $request->entretiens,
            'index' => $this->getNextRiskIndex($request->part_index),
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

            'indicateur_exposition' => $request->indicateur_exposition,
            'indicateur_risque_survenu' => $request->indicateur_risque_survenu,
            'indicateur_risque_avere' => $request->indicateur_risque_avere,
            'indicateur_risque_evite' => $request->indicateur_risque_evite,

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
            'other_informations' => $request->other_informations ?? null,
        ]);
        $users = $fiche->creator->account->users()->get();
        foreach ($users as $user) {
            if ($user->hasRole('admin') || $user->hasRole('owner') || $user->hasRole('viewer') || in_array($fiche->service_id, $user->services()->pluck('services.id')->toArray())) {
                if ($user->isNotificationEnabled('new_risk')) {
                    Mail::to($user->email)->send(new NewRiskMail($fiche, $user->username));
                }
            }
        }
        if ($request->boolean('risque_a_piloter')) {
            if ($request->kri_choice == 'create') {
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
                    'seuil_alerte' => (float) $request->kri_seuil_alerte,
                    'valeur_actuelle' => (float)  $request->kri_valeur_actuelle,
                    'commentaire' => $request->kri_commentaire,
                    'date_maj_valeur' => now()->toDateString(),
                ]);
                $risk_indicateur = FicheRisqueIndicateur::create([
                    'fiche_risque_id' => $fiche->id,
                    'indicateur_id' => $indicateur->id
                ]);
                $evolution = EvolutionIndicateur::create([
                    'created_by' => Auth::id(),
                    'indicateur_id' => $indicateur->id,
                    'valeur' => (float)  $request->kri_valeur_actuelle,
                    'annee' => Carbon::now()->year,
                    'mois' => Carbon::now()->month,

                ]);
                $users = $indicateur->creator->account->users()->get();
                foreach ($users as $user) {
                    if ($user->hasRole('admin') || $user->hasRole('owner') || $user->hasRole('viewer') || in_array($indicateur->service_id, $user->services()->pluck('services.id')->toArray())) {
                        if ($user->isNotificationEnabled('new_indicateur')) {
                            Mail::to($user->email)->send(new NewKriMail($indicateur, $user->username));
                        }
                    }
                }
            } elseif ($request->kri_choice == 'select') {
                $indicateur = Indicateur::findOrFail($request->kri_existing);
                $risk_indicateur = FicheRisqueIndicateur::create([
                    'fiche_risque_id' => $fiche->id,
                    'indicateur_id' => $indicateur->id
                ]);
                $indicateur->index = $this->getNextKriIndex($fiche->index);
                $indicateur->save();
            }
        }

        if ($request->boolean('action_maitrise_risque')) {
            if ($request->pa_choice == 'create_pa') {
                $plan_action = PlanAction::create([
                    'created_by' => Auth::id(),
                    'account_id' => Auth::user()->account->id,
                    'service_id' => $service->id,
                    'index' => $this->getNextPaIndex($fiche->index),
                    'type' => $request->pa_type,
                    'priorite' => $request->pa_priorite,
                    'responsable' => $request->pa_responsable,
                    'description' => $request->pa_description,
                    'date_debut_prevue' => $request->pa_date_debut,
                    'date_fin_prevue' => $request->pa_date_fin,
                    'statut' => $request->pa_statut,
                    'progression' => 0,
                ]);
                $risk_plan_action = FicheRisquePlanAction::create([
                    'fiche_risque_id' => $fiche->id,
                    'plan_action_id' => $plan_action->id,

                ]);
                $avancement = AvancementPlanAction::create([
                    'created_by' => Auth::id(),
                    'plan_action_id' => $plan_action->id,
                    'annee' => Carbon::now()->year,
                    'mois' => Carbon::now()->month,
                    'reste_a_faire' => 100,
                ]);
                 $users = $plan_action->creator->account->users()->get();
                foreach ($users as $user) {
                    if ($user->hasRole('admin') || $user->hasRole('owner') || $user->hasRole('viewer') || in_array($plan_action->service_id, $user->services()->pluck('services.id')->toArray())) {
                        if ($user->isNotificationEnabled('new_plan_action')) {
                            Mail::to($user->email)->send(new PlanActionAlertMail($plan_action, $user->username));
                        }
                    }
                }
            } elseif ($request->pa_choice == 'select_pa') {
                $plan_action = PlanAction::findOrFail($request->pa_existing);
                $risk_plan_action = FicheRisquePlanAction::create([
                    'fiche_risque_id' => $fiche->id,
                    'plan_action_id' => $plan_action->id,

                ]);
                $plan_action->index = $this->getNextPaIndex($fiche->index);
                $plan_action->save();
            }
        }

        return redirect()->back()->with('success', 'Risque enregistré avec succès.');
    }

    public function update(Request $request, $uuid, $id)
    {
        $service = Service::where('uuid', $uuid)->first();
        $fiche_risque = FicheRisque::findOrFail($id);
        $account = Auth::user()->account;
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('owner') && Auth::id() != $fiche_risque->created_by) {
            abort(403);
        }
        $messages = [
            // Champs simples
            'index.required' => 'L’index est requis.',
            'entite.required' => 'L’entité est requise.',
            'departement.required' => 'Le département est requis.',
            'version.required' => 'La version est requise.',
            'entretiens.required' => 'Le champ des entretiens est requis.',
            'ref_supp.string' => 'La référence supplémentaire doit être une chaîne de caractères.',
            'libelle_risk.required' => 'Le libellé du risque est requis.',
            'description.required' => 'La description est requise.',
            'identified_by.required' => 'Le champ "Identifié par" est requis.',

            // Relations
            'category.exists' => 'La catégorie de risque sélectionnée est invalide.',
            'macroprocessus.exists' => 'Le macroprocessus sélectionné est invalide.',
            'processus.exists' => 'Le processus sélectionné est invalide.',
            'cause_level_1.exists' => 'La cause niveau 1 est invalide.',
            'cause_level_2.exists' => 'La cause niveau 2 est invalide.',
            'cause_level_3.exists' => 'La cause niveau 3 est invalide.',

            // Fréquence et impact
            'frequence.required' => 'La fréquence est requise.',
            'frequence.in' => 'La fréquence sélectionnée est invalide.',
            'brut_impact_value.required' => 'La valeur d’impact brut est requise.',
            'brut_impact_value.integer' => 'La valeur d’impact brut doit être un nombre entier.',

            // Conséquences
            'manque_a_gagner.required' => 'Le champ "Manque à gagner" est requis.',
            'consequence_reglementaire.required' => 'Le champ "Conséquence réglementaire" est requis.',
            'consequence_juridique.required' => 'Le champ "Conséquence juridique" est requis.',
            'consequence_humaine.required' => 'Le champ "Conséquence humaine" est requis.',
            'interruption_processus.required' => 'Le champ "Interruption de processus" est requis.',
            'risque_image.required' => 'Le champ "Risque d’image" est requis.',
            'insatisfaction_client.required' => 'Le champ "Insatisfaction client" est requis.',
            'impact_risque_credit.required' => 'Le champ "Impact risque crédit" est requis.',
            'impact_risque_marche.required' => 'Le champ "Impact risque marché" est requis.',

            // DMR
            'description_DMR.required' => 'La description DMR est requise.',
            'appreciation_DMR.required' => 'L’appréciation DMR est requise.',
            'appreciation_DMR.in' => 'L’appréciation DMR sélectionnée est invalide.',

            // Indicateurs
            'indicateur_exposition.required' => 'L’indicateur d’exposition est requis.',
            'indicateur_risque_survenu.required' => 'L’indicateur de risque survenu est requis.',
            'indicateur_risque_avere.required' => 'L’indicateur de risque avéré est requis.',
            'indicateur_risque_evite.required' => 'L’indicateur de risque évité est requis.',



        ];
        $validated = $request->validate([
            'index' => 'required|string|max:255',
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
            'indicateur.*' =>  'exists:indicateurs,id',
            'pa.*' =>  'exists:plan_actions,id',
        ], $messages);
        $net_impact_value = $this->getNetImpactValue($request->appreciation_DMR, $request->brut_impact_value);

        $fiche_risque->update([
            'entite' => $request->entite,
            'departement' => $request->departement,
            'version' => $request->version,
            'entretiens' => $request->entretiens,
            'index' => $request->index,
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

            'indicateur_exposition' => $request->indicateur_exposition,
            'indicateur_risque_survenu' => $request->indicateur_risque_survenu,
            'indicateur_risque_avere' => $request->indicateur_risque_avere,
            'indicateur_risque_evite' => $request->indicateur_risque_evite,

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
            'other_informations' => $request->other_informations ?? null,
        ]);
        if ($request->boolean('risque_a_piloter')) {

            if ($request->indicateur) {
                $currentIds = $fiche_risque->indicateurs()->pluck('indicateurs.id')->toArray();
                $newIds = $request->input('indicateur', []);
                $toDetach = array_diff($currentIds, $newIds);
                $toAttach = array_diff($newIds, $currentIds);
                if (!empty($toDetach)) {
                    $fiche_risque->indicateurs()->detach($toDetach);
                    foreach ($toDetach as $id) {
                        $indicateur = Indicateur::findOrFail($id);
                        $indicateur->index = null;
                        $indicateur->save();
                    }
                }
                if (!empty($toAttach)) {
                    $fiche_risque->indicateurs()->attach($toAttach);
                    foreach ($toAttach as $id) {
                        $indicateur = Indicateur::findOrFail($id);
                        $indicateur->index = $this->getNextKriIndex($fiche_risque->index);
                        $indicateur->save();
                    }
                }
            }
        } else {
            $fiche_risque->indicateurs()->detach();
        }


        if ($request->boolean('action_maitrise_risque')) {
            if ($request->pa) {
                $currentIds = $fiche_risque->plan_actions()->pluck('plan_actions.id')->toArray();
                $newIds = $request->input('pa', []);
                $toDetach = array_diff($currentIds, $newIds);
                $toAttach = array_diff($newIds, $currentIds);
                if (!empty($toDetach)) {
                    $fiche_risque->plan_actions()->detach($toDetach);
                    foreach ($toDetach as $id) {
                        $plan_action = PlanAction::findOrFail($id);
                        $plan_action->index = null;
                        $plan_action->save();
                    }
                }
                if (!empty($toAttach)) {
                    $fiche_risque->plan_actions()->attach($toAttach);
                    foreach ($toAttach as $id) {
                        $plan_action = PlanAction::findOrFail($id);
                        $plan_action->index = $this->getNextPaIndex($fiche_risque->index);
                        $plan_action->save();
                    }
                }
            }
        } else {
            $fiche_risque->plan_actions()->detach();
        }

        return redirect()->back()->with('success', 'Risque modifié avec succès.');
    }


    public function validateFicheRisque(Request $request, $uuid, $id)
    {
        $service = Service::where('uuid', $uuid)->first();
        $fiche_risque = FicheRisque::findOrFail($id);
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('owner') && !Auth::user()->can('validate risk')) {
            abort(403);
        }
        $fiche_risque->is_validated = true;
        $fiche_risque->validated_by = Auth::id();
        $fiche_risque->save();
        return redirect()->back()->with('success', 'Risque validé avec succès.');
    }


    public function deleteFicheRisque($uuid, $id)
    {
        $service = Service::where('uuid', $uuid)->first();
        $fiche_risque = FicheRisque::findOrFail($id);
        $account = Auth::user()->account;
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('owner') && Auth::id() != $fiche_risque->created_by) {
            abort(403);
        }
        foreach ($fiche_risque->indicateurs()->get() as $indicateur) {
            $indicateur->index = null;
            $indicateur->save();
        }
        $fiche_risque->indicateurs()->detach();
        foreach ($fiche_risque->plan_actions()->get() as $plan) {
            $plan->index = null;
            $plan->save();
        }
        $fiche_risque->plan_actions()->detach();
        $fiche_risque->delete();
        return redirect()->back()->with('success', 'Risque supprimé avec succès.');
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
    public function getNextRiskIndex(string $prefix): string
    {
        $last = DB::table('fiche_risques')
            ->where('index', 'like', $prefix . '-%')
            ->selectRaw("MAX(CAST(SUBSTRING_INDEX(`index`, '-', -1) AS UNSIGNED)) as last_number")
            ->first();

        $nextNumber = ($last->last_number ?? 0) + 1;

        return $prefix . '-' . $nextNumber;
    }
    public function getNextKriIndex(string $prefix): string
    {
        $last = DB::table('indicateurs')
            ->where('index', 'like', $prefix . '%')
            ->selectRaw("MAX(CAST(REGEXP_SUBSTR(`index`, '[0-9]+$') AS UNSIGNED)) as last_number")
            ->first();

        $nextNumber = ($last->last_number ?? 0) + 1;

        return $prefix . '-IN-' . $nextNumber;
    }
    public function getNextPaIndex(string $prefix): string
    {
        $last = DB::table('plan_actions')
            ->where('index', 'like', $prefix . '%')
            ->selectRaw("MAX(CAST(REGEXP_SUBSTR(`index`, '[0-9]+$') AS UNSIGNED)) as last_number")
            ->first();

        $nextNumber = ($last->last_number ?? 0) + 1;

        return $prefix . '-PA-' . $nextNumber;
    }
}
