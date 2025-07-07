<?php

namespace App\Http\Controllers;

use App\Models\Indicateur;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GrapheController extends Controller
{
    public function graphe_indicateur($uuid, $id)
    {
        Carbon::setLocale('fr');
        $service = Service::where('uuid', $uuid)->first();
        $indicateur = Indicateur::findOrFail($id);
        // Date actuelle
        $now = Carbon::now();

        // 12 mois en arrière
        $startDate = $now->copy()->subMonths(11)->startOfMonth();

        // Récupérer les évolutions sur les 12 derniers mois
        $evolutions = $indicateur->evolutions()
            ->where(DB::raw("STR_TO_DATE(CONCAT(annee, '-', mois, '-01'), '%Y-%m-%d')"), '>=', $startDate)
            ->get();

        foreach ($evolutions as $evo) {
            $carbonDate = Carbon::createFromDate($evo->annee, $evo->mois, 1);
            $displayKey = $carbonDate->isoFormat('MMM YYYY');
            $sortKey    = $carbonDate->format('Y-m');

            if (!isset($tempData[$sortKey])) {
                $tempData[$sortKey] = [
                    'label' => $displayKey,
                    'values' => []
                ];
            }
            $tempData[$sortKey]['values'][] = (float) $evo->valeur;
        }

        // Trier les données par clé chronologique (Y-m)
        ksort($tempData);
        $kriData = [];
        foreach ($tempData as $item) {
            $kriData[$item['label']] = $item['values'];
        }

        // Construction du format final
        $graphData = [
            'kri' => $kriData,
            'seuil' => (float) $indicateur->seuil_alerte,
        ];

        return view('service_manager.pages.graphe_indicateur.index', [
            'service' => $service,
            'indicateur' => $indicateur,
            'data' => json_encode($graphData),
        ]);
    }
}
