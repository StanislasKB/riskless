<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\FicheRisque;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class GlobalDashboardController extends Controller
{
    public function index()
    {
        return view('global_manager.page.dashboard.index');
    }
    public function referentiel()
    {

        $fiche_risques = Auth::user()->account->fiche_risques()->where('is_validated', true)->get();
        return view('global_manager.page.referentiel.index', [
            'fiche_risques' => $fiche_risques,
        ]);
    }

    public function detail_view($id)
    {

        $fiche_risque = FicheRisque::findOrFail($id);
        // activity()
        //     ->causedBy(Auth::user())
        //     ->performedOn($fiche_risque)
        //     ->action('update')
        //     ->log("Modification de la fiche de risque");
        $account = Auth::user()->account;
        // $logs = ActivityLog::with('causer', 'subject')
        //     ->where('account_id', $account->id)
        //     ->orderByDesc('created_at')
        //     ->first();
        //     dd($logs->description);
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('owner') && $account->id != $fiche_risque->creator->account->id) {
            abort(403);
        }

        return view('global_manager.page.referentiel.detail', [
            'fiche_risque' => $fiche_risque,
        ]);
    }
}
