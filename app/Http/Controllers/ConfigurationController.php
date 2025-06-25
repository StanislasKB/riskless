<?php

namespace App\Http\Controllers;

use App\Models\Macroprocessus;
use App\Models\RiskCategory;
use App\Models\RiskCause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            return redirect()->back()->with('error', 'Veuillez remplir tous les champs.');
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
        
    }
}
