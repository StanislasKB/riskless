<?php

namespace App\Http\Controllers;

use App\Models\RiskCategory;
use App\Models\RiskCause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfigurationController extends Controller
{
    public function add()
    {
        return view('global_manager.page.configuration.index');
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
}
