<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidentController extends Controller
{
    //

    public function index($uuid)
    {
        $service = Service::where('uuid', $uuid)->firstOrFail();
        $incidents = Incident::where('service_id', $service->id)->get();


        return view('service_manager.pages.plan_action.index')->with([
                'service' => $service,
                'incidents' => $incidents,
            ]);
    }

    public function store($service_uuid, Request $request)
    {
        $service = Service::where('uuid', $service_uuid)->firstOrFail();
        $account_id = Auth::user()->account->id;

        $request->merge([
            'account_id' => $account_id,
            'service_id' => $service->id,
            'created_by' => Auth::user()->id,
        ]);


        $validated = $request->validate([
            'fiche_risque_id' => 'required|exists:fiche_risques,id',
            'libelle' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:255',
            'frequence_susceptible' => 'nullable|string|max:255',
            'identifie_par' => 'nullable|string|max:255',
            'account_id' => 'required|exists:accounts,id',
            'service_id' => 'required|exists:services,id',
            'created_by' => 'required|exists:users,id',
        ]);

        $incident = Incident::create($validated);
        activity()
            ->causedBy(Auth::user())
            ->performedOn($incident)
            ->action('create')
            ->log("Création d'un incident");

        return redirect()->back()->with('success', 'Incident créé avec succès.');
    }

    public function update($service_uuid, $id, Request $request)
    {
        $service = Service::where('uuid', $service_uuid)->firstOrFail();
        $incident = Incident::findOrFail($id);

        $validated = $request->validate([
            'fiche_risque_id' => 'required|exists:fiche_risques,id',
            'libelle' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:255',
            'frequence_susceptible' => 'nullable|string|max:255',
            'identifie_par' => 'nullable|string|max:255',
        ]);

        $incident->update($validated);
        activity()
            ->causedBy(Auth::user())
            ->performedOn($incident)
            ->action('update')
            ->log("Modification d'un incident");
        return redirect()->back()->with('success', 'Incident mis à jour avec succès.');
    }

    public function destroy($service_uuid, $id)
    {
        $service = Service::where('uuid', $service_uuid)->firstOrFail();
        $incident = Incident::findOrFail($id);
        activity()
            ->causedBy(Auth::user())
            ->action('delete')
             ->withProperties([
                'snapshot' => $incident->toArray(),
            ])
            ->log("Suppression d'un incident");
        $incident->delete();
        return redirect()->back()->with('success', 'Incident supprimé avec succès.');
    }
}
