<?php

namespace App\Http\Controllers;

use App\Models\FicheRisque;
use App\Models\Macroprocessus;
use App\Models\Processus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProcessusController extends Controller
{
    public function index()
    {
        $account = Auth::user()->account;
        $processus = $account->processus()->get();
        return view('global_manager.page.processus.index', compact('processus'));
    }

    public function add()
    {
        $macroprocessus = Macroprocessus::where('account_id', Auth::user()->account->id)->get();
        return view('global_manager.page.processus.create', compact('macroprocessus'));
    }
    public function update_view($id)
    {
        $macroprocessus = Macroprocessus::where('account_id', Auth::user()->account->id)->get();
        $processus = Processus::findOrFail($id);
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('owner') && Auth::id() != $processus->created_by) {
            abort(403);
        }
        return view('global_manager.page.processus.update', compact('macroprocessus', 'processus'));
    }

    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'macroprocessus' => 'required|exists:macroprocessuses,id',
            'name' => 'required|string|max:255',
            'domaine' => 'required|string|max:255',
            'intervenant' => 'required|string|max:255',
            'procedure' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'pilote' => 'required|string|max:255',
            'controle_interne' => 'required|string|max:255',
            'periodicite' => 'required|string|max:255',
            'piste_audit' => 'required|string|max:255',
            'indicateur_performance' => 'required|string|max:255',
            'actif' => 'required',
            'commentaire' => 'nullable|string',
        ]);

        $processus = Processus::create([
            'macroprocessus_id' => (int) $request->macroprocessus,
            'created_by' => Auth::id(),
            'name' => $request->name,
            'domaine' => $request->domaine,
            'intervenant' => $request->intervenant,
            'procedure' => $request->procedure,
            'description' => $request->description,
            'pilote' => $request->pilote,
            'controle_interne' => $request->controle_interne,
            'periodicite' => $request->periodicite,
            'piste_audit' => $request->piste_audit,
            'indicateur_performance' => $request->indicateur_performance,
            'actif' => $request->actif,
            'commentaire' => $request->commentaire,
        ]);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($processus)
            ->action('create')
            ->log("Création d'un processus");

        return redirect()->back()->with('success', 'Processus créé avec succès.');
    }



    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'macroprocessus' => 'required|exists:macroprocessuses,id',
            'name' => 'required|string|max:255',
            'domaine' => 'required|string|max:255',
            'intervenant' => 'required|string|max:255',
            'procedure' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'pilote' => 'required|string|max:255',
            'controle_interne' => 'required|string|max:255',
            'periodicite' => 'required|string|max:255',
            'piste_audit' => 'required|string|max:255',
            'indicateur_performance' => 'required|string|max:255',
            'actif' => 'required',
            'commentaire' => 'nullable|string',
        ]);

        // Récupération du processus
        $processus = Processus::findOrFail($id);
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('owner') && Auth::id() != $processus->created_by) {
            abort(403);
        }

        // Mise à jour
        $processus->update([
            'macroprocessus_id' => (int) $request->macroprocessus,
            'name' => $request->name,
            'domaine' => $request->domaine,
            'intervenant' => $request->intervenant,
            'procedure' => $request->procedure,
            'description' => $request->description,
            'pilote' => $request->pilote,
            'controle_interne' => $request->controle_interne,
            'periodicite' => $request->periodicite,
            'piste_audit' => $request->piste_audit,
            'indicateur_performance' => $request->indicateur_performance,
            'actif' => $request->actif,
            'commentaire' => $request->commentaire,
        ]);
        activity()
            ->causedBy(Auth::user())
            ->performedOn($processus)
            ->action('update')
            ->log("Modification d'un processus");

        return redirect()->back()->with('success', 'Processus mis à jour avec succès.');
    }


    public function destroy($id)
    {
        $processus = Processus::findOrFail($id);
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('owner') && Auth::id() != $processus->created_by) {
            abort(403);
        }

        $utilise = FicheRisque::where('processus_id', $id)->exists();

        if ($utilise) {
            return redirect()->back()->with(['error' => 'Impossible de supprimer : ce processus est lié à une ou plusieurs fiches de risque.']);
        }
        activity()
            ->causedBy(Auth::user())
            ->action('delete')
            ->withProperties([
                'snapshot' => $processus->toArray(),
            ])
            ->log("Suppression d'un processus");
        $processus->delete();

        return redirect()->back()->with('success', 'Processus supprimé avec succès.');
    }
}
