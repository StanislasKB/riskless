<?php

namespace App\Http\Controllers;

use App\Models\AvancementPlanAction;
use App\Models\PlanAction;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanActionAvancementController extends Controller
{
    public function index($serviceId, $plan_action_id)
    {
        $service = Service::where('uuid', $serviceId)->firstOrFail();
        $plan = PlanAction::findOrFail($plan_action_id);
        $avancements = $plan->avancements; // Assuming PlanAction has a relationship with AvancementPlanAction

        return view('service_manager.pages.plan_action.avancements')->with([
                'service' => $service,
                'plan' => $plan,
                'avancements' => $avancements,
            ]);
    }

    public function store($serviceUuid, $plan_action_id, Request $request)
    {
        $account_id = Auth::user()->account->id;
        // Récupérer l'id du service depuis l'UUID (exemple)
        $service = Service::where('uuid', $serviceUuid)->firstOrFail();
        $planAction = PlanAction::findOrFail($plan_action_id);


        // Fusionner les champs nécessaires AVANT validation
        $request->merge([
            'plan_action_id' => $planAction->id,
            'created_by' => Auth::user()->id,
            'mois' => now()->month,
            'annee' => now()->year,
        ]);


        // Validation
        $validated = $request->validate([
            // 'index' => 'nullable|string|max:255',
            'mois' => 'nullable',
            'annee' => 'nullable',
            'statut' => 'required',
            'reste_a_faire' => 'required|numeric|min:0|max:100',
            'commentaire' => 'nullable|string',
            'plan_action_id' => 'required',
            'created_by' => 'required|exists:users,id',
        ]);


        // Création de plan_action avec les données validées
        $avancementPlanAction = AvancementPlanAction::create($validated);

        //creation de l'avancement du plan action
        $planAction->progression = 100 - $request->input('reste_a_faire', 0);
        $planAction->statut = $request->input('statut', 'A_LANCER');
        $planAction->save();


        return redirect()->back()->with('success', 'Action plan created successfully.');
    }

    public function update($serviceID, $plan_action_id, $avancement_id, Request $request)
    {
        $avancement = AvancementPlanAction::findOrFail($avancement_id);
        $planAction = PlanAction::findOrFail($plan_action_id);
        if (!$avancement) {
            return redirect()->back()->with('error', 'Avancement not found.');
        }

        // Validation
        $validated = $request->validate([
            'statut' => 'required|string',
            'reste_a_faire' => 'required|numeric|min:0|max:100',
            'commentaire' => 'nullable|string',
        ]);

        // Mise à jour des champs du plan_action
        $avancement->update($validated);
        // check if is the last avancements
        $avancements = $planAction->avancements;
        if ($avancements->count() > 1) {
            $lastAvancement = $avancements->last();
            if ($lastAvancement->id !== $avancement->id) {
                $planAction->progression = 100 - $request->input('reste_a_faire', 0);
                $planAction->statut = $request->input('statut', 'A_LANCER');
                $planAction->save();
            }
        }


        return redirect()->back()->with('success', 'Plan action updated successfully.');
    }
    public function destroy($serviceID, $planAction,$avancementId)
    {
        $plan_action = PlanAction::findOrFail($planAction);
        $avancement = AvancementPlanAction::findOrFail($avancementId);
        if (!$avancement) {
            return redirect()->back()->with('error', 'Avancement not found.');
        }
        // check if the avancement is the last one
        $avancements = $plan_action->avancements;
        if ($avancements->count()> 0) {
            $lastAvancement = $avancements->last();
            if ($lastAvancement->id === $avancement->id) {
                $precedentAvancement = $avancements->where('id', '!=', $lastAvancement->id)->last();
                if ($precedentAvancement) {
                    $plan_action->progression = 100 - $precedentAvancement->reste_a_faire;
                    $plan_action->statut = $precedentAvancement->statut;
                    $plan_action->save();
                }
            }
        }
        $avancement->delete();
        return redirect()->back()->with('success', 'Avancement deleted successfully.');
    }
}
