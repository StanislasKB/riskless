<?php

namespace App\Http\Controllers;

use App\Mail\AlerteSeuilKriMail;
use App\Mail\NewKriMail;
use App\Models\EvolutionIndicateur;
use App\Models\FicheRisque;
use App\Models\FicheRisqueIndicateur;
use App\Models\Indicateur;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class IndicateurController extends Controller
{
    public function index($uuid)
    {
        $service = Service::where('uuid', $uuid)->first();
        $indicateurs = $service->indicateurs()->get();
        return view('service_manager.pages.indicateur.index', [
            'service' => $service,
            'indicateurs' => $indicateurs,
        ]);
    }


    public function add_view($uuid)
    {
        $service = Service::where('uuid', $uuid)->first();
        return view('service_manager.pages.indicateur.create', [
            'service' => $service,
        ]);
    }
    public function details_view($uuid, $id)
    {
        $service = Service::where('uuid', $uuid)->first();
        $indicateur = Indicateur::findOrFail($id);
        $account = Auth::user()->account;
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('owner') && $account->id != $indicateur->creator->account->id) {
            abort(403);
        }
        return view('service_manager.pages.indicateur.details', [
            'service' => $service,
            'indicateur' => $indicateur,
        ]);
    }
    public function edit_view($uuid, $id)
    {
        $service = Service::where('uuid', $uuid)->first();
        $indicateur = Indicateur::findOrFail($id);
        $account = Auth::user()->account;
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('owner') && $account->id != $indicateur->creator->account->id) {
            abort(403);
        }
        return view('service_manager.pages.indicateur.edit', [
            'service' => $service,
            'indicateur' => $indicateur,
        ]);
    }

    public function store(Request $request, $uuid)
    {
        $service = Service::where('uuid', $uuid)->first();
        $messages = [
            'kri_departement.required' => 'Le département de l’indicateur est requis.',
            'kri_libelle.required' => 'Le libellé de l’indicateur est requis.',
            'kri_type.required' => 'Le type de l’indicateur est requis.',
            'kri_precision_indicateur.required' => 'La précision de l’indicateur est requise.',
            'kri_source.required' => 'La source de l’indicateur est requise.',
            'kri_chemin_access.required' => 'Le chemin d’accès à l’indicateur est requis.',
            'kri_periodicite.required' => 'La périodicité de l’indicateur est requise.',
            'kri_type_seuil.required' => 'Le type de seuil est requis.',
            'kri_seuil_alerte.required' => 'Le seuil d’alerte est requis.',
            'kri_valeur_actuelle.required' => 'La valeur actuelle est requise.',
            'risque.required' => 'Le risque associé est requis.',
        ];
        $validated = $request->validate([
            'kri_departement' => 'required|string',
            'kri_libelle' => 'required|string',
            'kri_type' => 'required|string',
            'kri_precision_indicateur' => 'required|string',
            'kri_source' => 'required|string',
            'kri_chemin_access' => 'required|string',
            'kri_periodicite' => 'required|string',
            'kri_type_seuil' => 'required|string',
            'kri_seuil_alerte' => 'required',
            'kri_valeur_actuelle' => 'required',
            'kri_commentaire' => 'nullable|string',
            'risque' => 'required',

        ], $messages);
        $fiche = FicheRisque::findOrFail($request->risque);

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


        return redirect()->back()->with('success', 'Indicateur enregistré avec succès.');
    }

    public function update(Request $request, $uuid, $id)
    {
        $service = Service::where('uuid', $uuid)->first();
        $indicateur = Indicateur::findOrFail($id);
        $messages = [
            'kri_departement.required' => 'Le département de l’indicateur est requis.',
            'kri_libelle.required' => 'Le libellé de l’indicateur est requis.',
            'kri_type.required' => 'Le type de l’indicateur est requis.',
            'kri_precision_indicateur.required' => 'La précision de l’indicateur est requise.',
            'kri_source.required' => 'La source de l’indicateur est requise.',
            'kri_chemin_access.required' => 'Le chemin d’accès à l’indicateur est requis.',
            'kri_periodicite.required' => 'La périodicité de l’indicateur est requise.',
            'kri_type_seuil.required' => 'Le type de seuil est requis.',
            'kri_seuil_alerte.required' => 'Le seuil d’alerte est requis.',
            'kri_valeur_actuelle.required' => 'La valeur actuelle est requise.',
        ];
        $validated = $request->validate([
            'kri_departement' => 'required|string',
            'kri_libelle' => 'required|string',
            'kri_type' => 'required|string',
            'kri_precision_indicateur' => 'required|string',
            'kri_source' => 'required|string',
            'kri_chemin_access' => 'required|string',
            'kri_periodicite' => 'required|string',
            'kri_type_seuil' => 'required|string',
            'kri_seuil_alerte' => 'required',
            'kri_valeur_actuelle' => 'required',
            'kri_commentaire' => 'nullable|string',

        ], $messages);

        $indicateur->update([
            'departement' => $request->kri_departement,
            'libelle' => $request->kri_libelle,
            'type' => $request->kri_type,
            'precision_indicateur' => $request->kri_precision_indicateur,
            'source' => $request->kri_source,
            'chemin_access' => $request->kri_chemin_access,
            'periodicite' => $request->kri_periodicite,
            'type_seuil' => $request->kri_type_seuil,
            'seuil_alerte' => (float) $request->kri_seuil_alerte,
            'commentaire' => $request->kri_commentaire,
        ]);
        if ($indicateur->valeur_actuelle != $request->kri_valeur_actuelle) {
            $indicateur->update([
                'valeur_actuelle' => (float)  $request->kri_valeur_actuelle,
                'date_maj_valeur' => now()->toDateString(),
            ]);
            $evolution = $indicateur->evolutions()->latest()->first();
            $evolution->valeur = $request->kri_valeur_actuelle;
            $evolution->save();
            if ($indicateur->valeur_actuelle > $indicateur->seuil_alerte) {
                $users = $indicateur->creator->account->users()->get();
                foreach ($users as $user) {
                    if ($user->hasRole('admin') || $user->hasRole('owner') || $user->hasRole('viewer') || in_array($indicateur->service_id, $user->services()->pluck('services.id')->toArray())) {
                        if ($user->isNotificationEnabled('kri_seuil_alerte')) {
                            Mail::to($user->email)->send(new AlerteSeuilKriMail($indicateur, $user->username));
                        }
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Indicateur modifié avec succès.');
    }

    public function delete($uuid, $id)
    {
        $service = Service::where('uuid', $uuid)->first();
        $indicateur = Indicateur::findOrFail($id);
        $account = Auth::user()->account;
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('owner') && $account->id != $indicateur->creator->account->id) {
            abort(403);
        }
        if ($indicateur->fiche_risques()->exists()) {
            return redirect()->back()->withErrors(['error'  => "Impossible de supprimer : cet indicateur risque est lié à un risque."]);
        }
        $indicateur->evolutions()->delete();
        $indicateur->delete();
        return redirect()->back()->with('success', 'Indicateur supprimé avec succès.');
    }


    public function evolution(Request $request, $uuid, $id)
    {
        $service = Service::where('uuid', $uuid)->first();
        $indicateur = Indicateur::findOrFail($id);
        $validated = $request->validate([

            'valeur_actuelle' => 'required',

        ]);
        $indicateur->update([
            'valeur_actuelle' => (float)  $request->valeur_actuelle,
            'date_maj_valeur' => now()->toDateString(),
        ]);
        $evolution = EvolutionIndicateur::create([
            'created_by' => Auth::id(),
            'indicateur_id' => $indicateur->id,
            'valeur' => (float)  $request->valeur_actuelle,
            'annee' => Carbon::now()->year,
            'mois' => Carbon::now()->month,
        ]);
        if ($indicateur->valeur_actuelle > $indicateur->seuil_alerte) {
            $users = $indicateur->creator->account->users()->get();
            foreach ($users as $user) {
                if ($user->hasRole('admin') || $user->hasRole('owner') || $user->hasRole('viewer') || in_array($indicateur->service_id, $user->services()->pluck('services.id')->toArray())) {
                    if ($user->isNotificationEnabled('kri_seuil_alerte')) {
                        Mail::to($user->email)->send(new AlerteSeuilKriMail($indicateur, $user->username));
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Nouvelle valeur ajoutée pour un indicateur.');
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
}
