<?php

namespace App\Http\Controllers;

use App\Models\AvancementPlanAction;
use App\Models\FicheRisquePlanAction;
use App\Models\PlanAction;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanActionController extends Controller
{
    //
    public function index($uuid){
    $service = Service::where('uuid', $uuid)->firstOrFail();
    $plans = PlanAction::where('service_id', $service->id)->get();


        return view('service_manager.pages.plan_action.index')->
            with([
                'service' => $service,
                'plans' => $plans,
            ]);
    }

    public function store($uuid, Request $request)
{
    $account_id = Auth::user()->account->id;
    // Récupérer l'id du service depuis l'UUID (exemple)
    $service = Service::where('uuid', $uuid)->firstOrFail();

    // Fusionner les champs nécessaires AVANT validation
    $request->merge([
        'account_id' => $account_id,
        'service_id' => $service->id,
        'created_by' => Auth::user()->id,
    ]);

    // Validation
    $validated = $request->validate([
        // 'index' => 'nullable|string|max:255',
        'type' => 'nullable|string',
        'priorite' => 'nullable|string',
        'responsable' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'fiche_risque_id' => 'nullable|exists:fiche_risques,id',
        'date_debut_prevue' => 'nullable|date',
        'date_fin_prevue' => 'nullable|date|after_or_equal:date_debut_prevue',
        'statut' => 'nullable|string',
        // 'progression' => 'nullable|integer|min:0|max:100',
        'account_id' => 'required|exists:accounts,id',
        'service_id' => 'required|exists:services,id',
        'created_by' => 'required|exists:users,id',
    ]);

    // Création de plan_action avec les données validées
    $planAction = PlanAction::create($validated);

    //creation de l'avancement du plan action
    $planAction->progression = 0; // Initialisation de la progression à 0
    $planAction->save();


    //creation de l'avancement du plan action
    $avancement=AvancementPlanAction::create([
        'plan_action_id' => $planAction->id,
        'created_by' => Auth::user()->id,
        'mois' => now()->month,
        'annee' => now()->year,
        'statut' => $request->input('statut', 'A_LANCER'),
        'reste_a_faire' => 100, // Initialisation à 100
        'commentaire' => '', // Initialisation à une chaîne vide
    ]);


    // Lier fiche risque si présent
    if (!empty($validated['fiche_risque_id'])) {
        FicheRisquePlanAction::updateOrCreate(
            [
                'fiche_risque_id' => $validated['fiche_risque_id'],
                'plan_action_id' => $planAction->id,
            ],
            [
                'fiche_risque_id' => $validated['fiche_risque_id'],
                'plan_action_id' => $planAction->id,
            ] // pas de champs à mettre à jour ici, sinon ajoute un tableau avec les valeurs à updater
        );
    }

    return redirect()->back()->with('success', 'Action plan created successfully.');
}

public function update($serviceID, $planAction, Request $request){
    $plan_action=PlanAction::findOrFail($planAction);
    if (!$plan_action) {
        return redirect()->back()->with('error', 'Plan action not found.');
    }

    // Validation
    $validated = $request->validate([
        'index' => 'nullable|string|max:255',
        'type' => 'nullable|string',
        'priorite' => 'nullable|string',
        'responsable' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'fiche_risque_id' => 'nullable|exists:fiche_risques,id',
        'date_debut_prevue' => 'nullable|date',
        'date_fin_prevue' => 'nullable|date|after_or_equal:date_debut_prevue',
        'statut' => 'nullable|string',
        'progression' => 'nullable|integer|min:0|max:100',
    ]);

    // Mise à jour des champs du plan_action
    $plan_action->update($validated);

    // Lier fiche risque si présent
    if (!empty($validated['fiche_risque_id'])) {
        FicheRisquePlanAction::updateOrCreate(
            [
                'fiche_risque_id' => $validated['fiche_risque_id'],
                'plan_action_id' => $plan_action->id,
            ],
            [
                'fiche_risque_id' => $validated['fiche_risque_id'],
                'plan_action_id' => $plan_action->id,
            ]
        );
    }

    return redirect()->back()->with('success', 'Plan action updated successfully.');
}
public function destroy($serviceID,$planAction){
    $plan_action=PlanAction::findOrFail($planAction);
    if (!$plan_action) {
        return redirect()->back()->with('error', 'Plan action not found.');
    }
    $plan_action->delete();
    return redirect()->back()->with('success', 'Plan action deleted successfully.');

}



}
