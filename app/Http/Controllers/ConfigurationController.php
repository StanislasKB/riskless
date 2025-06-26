<?php

namespace App\Http\Controllers;

use App\Models\FicheRisque;
use App\Models\Macroprocessus;
use App\Models\Processus;
use App\Models\RiskCategory;
use App\Models\RiskCause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConfigurationController extends Controller
{
    public function index()
    {
        $causes = RiskCause::where('account_id', Auth::user()->account->id)->get();
        $categories = RiskCategory::where('account_id', Auth::user()->account->id)->get();
        $macroprocessus = Macroprocessus::where('account_id', Auth::user()->account->id)->get();
        return view('global_manager.page.configuration.index', [
            'causes' => $causes,
            'categories' => $categories,
            'macroprocessus' => $macroprocessus,
        ]);
    }

    public function add()
    {
        return view('global_manager.page.configuration.create');
    }

    public function store_risque_cause(Request $request)
    {

        $causes = $request->input('causes');
        $niveaux = $request->input('niveaux');

        foreach ($causes as $index => $libelle) {
            $niveau = $niveaux[$index];

            RiskCause::create([
                'libelle' => $libelle,
                'level' => (int) $niveau,
                'account_id' => Auth::user()->account->id,
                'created_by' => Auth::id(),
            ]);
        }

        return redirect()->back()->with('success', 'Causes de risque enregistrées.');
    }


    public function store_risque_category(Request $request)
    {
        $todos = $request->input('categories');

        foreach ($todos as $todoText) {
            RiskCategory::create([
                'libelle' => $todoText,
                'account_id' => Auth::user()->account->id,
                'created_by' => Auth::id(),
            ]);
        }

        return redirect()->back()->with('success', 'Categories de risque enregistrées.');
    }

    public function store_macroprocessus(Request $request)
    {
        $names = $request->input('names');
        $entites = $request->input('entites');

        if (!$names || !$entites || count($names) !== count($entites)) {
            return redirect()->back()->withErrors(['error'  =>'Veuillez remplir tous les champs.']);
        }

        foreach ($names as $index => $name) {
            Macroprocessus::create([
                'name' => $name,
                'entite' => $entites[$index],
                'account_id' => Auth::user()->account->id,
                'created_by' => Auth::id(),
            ]);
        }

        return redirect()->back()->with('success', 'Macroprocessus enregistrés avec succès.');
    }

    public function update_risque_cause(Request $request, $id)
    {
        $request->validate([
            'libelle' => 'required',
            'niveau' => 'required|string|max:255',
        ]);
        $cause = RiskCause::findOrFail($id);
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('owner') && Auth::id() != $cause->created_by) {
            abort(403);
        }

        $cause->update([
            'libelle' => $request->libelle,
            'level' => (int) $request->niveau,
        ]);
        return redirect()->back()->with('success', 'Cause de risque mis à jour avec succès.');
    }

    public function delete_risque_cause($id)
    {
        $cause = RiskCause::findOrFail($id);
        $level = $cause->level;
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('owner') && Auth::id() != $cause->created_by) {
            abort(403);
        }
        $column = "risk_cause->level_$level"; // ex: risk_cause->level_2

        $utilisee = FicheRisque::where(DB::raw("JSON_EXTRACT(risk_cause, '$.level_$level')"), '=', $id)->exists();
        if ($utilisee) {
            return redirect()->back()->withErrors(['error'  => "Impossible de supprimer : cette cause de risque est utilisée dans au moins une fiche."]);
        }

        $cause->delete();
        return redirect()->back()->with('success', "Cause de risque supprimée avec succès.");
    }

    public function update_risque_category(Request $request, $id)
    {
        $request->validate([
            'libelle' => 'required',
        ]);
        $category = RiskCategory::findOrFail($id);
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('owner') && Auth::id() != $category->created_by) {
            abort(403);
        }

        $category->update([
            'libelle' => $request->libelle,
        ]);
        return redirect()->back()->with('success', 'Category de risque mis à jour avec succès.');
    }

    public function delete_risque_category($id)
    {
        $category = RiskCategory::findOrFail($id);
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('owner') && Auth::id() != $category->created_by) {
            abort(403);
        }
        $utilisee = FicheRisque::where('category_id', $id)->exists();

        if ($utilisee) {
            return redirect()->back()->withErrors(['error' =>'Impossible de supprimer : cette catégorie est liée à au moins une fiche de risque.']);
        }

        // 3. Suppression
        $category->delete();

        return redirect()->back()->with('success', "Categorie de risque supprimée avec succès.");
    }

    public function update_macroprocessus(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'entite' => 'required',
        ]);
        $macro = Macroprocessus::findOrFail($id);
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('owner') && Auth::id() != $macro->created_by) {
            abort(403);
        }

        $macro->update([
            'name' => $request->name,
            'entite' => $request->entite,
        ]);
        return redirect()->back()->with('success', 'Macroprocessus mis à jour avec succès.');
    }


    public function delete_macroprocessus($id)
    {
        $macro = Macroprocessus::findOrFail($id);

        $utilise_dans_processus = Processus::where('macroprocessus_id', $id)->exists();
         if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('owner') && Auth::id() != $macro->created_by) {
            abort(403);
        }
        // dd($utilise_dans_processus);

        if ($utilise_dans_processus) {
            return redirect()->back()->withErrors(['error' =>'Impossible de supprimer : ce macroprocessus est utilisé dans au moins un processus.']);
        }

        $utilise_dans_fiche = FicheRisque::where('macroprocessus_id', $id)->exists();

        if ($utilise_dans_fiche) {
            return redirect()->back()->withErrors(['error'  =>'Impossible de supprimer : ce macroprocessus est utilisé dans au moins une fiche de risque.']);
        }

        $macro->delete();
        return redirect()->back()->with('success', 'Macroprocessus supprimé avec succès.');
    }
}
