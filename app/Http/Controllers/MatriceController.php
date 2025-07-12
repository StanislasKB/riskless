<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MatriceController extends Controller
{
    public function index($uuid)
    {
        $service = Service::where('uuid', $uuid)->first();
        $fiche_risques = $service->fiche_risques()->get();
        $data_net = [];
        $data_brut = [];
        $data_dmr = [];

        foreach ($fiche_risques as $fiche) {
            $data_net[$fiche->index] = [
                'impact' => $fiche->net_impact,
                'frequence' => $fiche->frequence,
            ];
            $data_brut[$fiche->index] = [
                'impact' => $fiche->brut_impact,
                'frequence' => $fiche->frequence,
            ];
            $data_dmr[$fiche->index] = [
                'impact' => $fiche->appreciation_DMR,
                'frequence' => $fiche->echelle_risque,
            ];
        }

        $data_net = json_encode($data_net);
        $data_brut = json_encode($data_brut);
        $data_dmr = json_encode($data_dmr);

        $data_table = $this->data_process($fiche_risques);
        $causes_level_one = $this->regrouperCauses($fiche_risques, '1');
        $causes_level_two = $this->regrouperCauses($fiche_risques, '2');
        $causes_level_three = $this->regrouperCauses($fiche_risques, '3');
        $repartition = $this->traiterRepartitionRisque($fiche_risques);


        return view('service_manager.pages.matrice.index', [
            'service' => $service,
            'data_brut' => $data_brut,
            'data_net' => $data_net,
            'data_dmr' => $data_dmr,
            'data_table' => $data_table,
            'causes_level_one' => $causes_level_one,
            'causes_level_two' => $causes_level_two,
            'causes_level_three' => $causes_level_three,
            'repartition' => $repartition,

        ]);
    }
    public function global()
    {
        $fiche_risques = Auth::user()->account->fiche_risques()->get();
        $data_net = [];
        $data_brut = [];
        $data_dmr = [];

        foreach ($fiche_risques as $fiche) {
            $data_net[$fiche->index] = [
                'impact' => $fiche->net_impact,
                'frequence' => $fiche->frequence,
            ];
            $data_brut[$fiche->index] = [
                'impact' => $fiche->brut_impact,
                'frequence' => $fiche->frequence,
            ];
            $data_dmr[$fiche->index] = [
                'impact' => $fiche->appreciation_DMR,
                'frequence' => $fiche->echelle_risque,
            ];
        }


        $data_net = json_encode($data_net);
        $data_brut = json_encode($data_brut);
        $data_dmr = json_encode($data_dmr);
        $data_table = $this->data_process($fiche_risques);
        $causes_level_one = $this->regrouperCauses($fiche_risques, '1');
        $causes_level_two = $this->regrouperCauses($fiche_risques, '2');
        $causes_level_three = $this->regrouperCauses($fiche_risques, '3');
        $data_service = $this->compterRisquesParService(Auth::user()->account);
        $repartition = $this->traiterRepartitionRisque($fiche_risques);

        return view('global_manager.page.matrice.index', [
            'data_brut' => $data_brut,
            'data_net' => $data_net,
            'data_dmr' => $data_dmr,
            'data_table' => $data_table,
            'causes_level_one' => $causes_level_one,
            'causes_level_two' => $causes_level_two,
            'causes_level_three' => $causes_level_three,
            'data_service' => $data_service,
            'repartition' => $repartition,


        ]);
    }



    private function getEchelleFreq($frequence)
    {
        $echelle = [
            'EXTREMEMENT_RARE' => 0.5,
            'RARE' => 1.5,
            'PEU_FREQUENT' => 2.5,
            'FREQUENT' => 3.5,
            'TRES_FREQUENT' => 4.5,
            'PERMANENT' => 5.5,
        ];
        return $echelle[$frequence];
    }
    private function getEchelleImpact($impact)
    {
        $echelle = [
            'FAIBLE' => 0.5,
            'MODERE' => 1.5,
            'MOYEN' => 2.5,
            'FORT' => 3.5,
            'MAJEUR' => 4.5,
            'CRITIQUE' => 5.5,
        ];
        return $echelle[$impact];
    }
    private function getEchelleControle($controle)
    {
        $echelle = [
            'INEXISTANT' => 4.5,
            'INSUFFISANT' => 3.5,
            'ACCEPTABLE' => 2.5,
            'CONFORME' => 1.5,
            'EFFICACE' => 0.5,
        ];
        return $echelle[$controle];
    }
    private function getEchelleCotation($cotation)
    {
        $echelle = [
            'FAIBLE' => 0.5,
            'MOYEN' => 1.5,
            'FORT' => 3.5,
            'CRITIQUE' => 4.5,
            'INACCEPTABLE' => 5.5,
        ];
        return $echelle[$cotation];
    }

    private function data_process($fiche_risques)
    {


        $concatNetList = [];
        $concatBrutList = [];
        $concatControleList = [];
        $ficheConcat = [];

        foreach ($fiche_risques as $i => $fiche) {
            $ef = (int) ceil($this->getEchelleFreq($fiche->frequence));
            $in = (int) ceil($this->getEchelleImpact($fiche->net_impact));
            $ib = (int) ceil($this->getEchelleImpact($fiche->brut_impact));
            $ecn = (int) ceil($this->getEchelleCotation($fiche->net_cotation));
            $ecc = $this->getEchelleControle($fiche->appreciation_DMR);

            $concat_net = (string)$ef . (string)$in;
            $concat_brut = (string)$ef . (string)$ib;
            $concat_controle = (string)$ecn . (string)((int)ceil($ecc));

            $concatNetList[] = $concat_net;
            $concatBrutList[] = $concat_brut;
            $concatControleList[] = $concat_controle;

            $ficheConcat[$i] = [
                'fiche' => $fiche,
                'ef' => $ef,
                'in' => $in,
                'ib' => $ib,
                'ecn' => $ecn,
                'ecc' => $ecc,
                'concat_net' => $concat_net,
                'concat_brut' => $concat_brut,
                'concat_controle' => $concat_controle,
            ];
        }

        $indexes = [];

        foreach ($ficheConcat as $i => $data) {
            $fiche = $data['fiche'];
            $indexKey = $fiche->index;

            $ef = $data['ef'];
            $in = $data['in'];
            $ib = $data['ib'];
            $ecn = $data['ecn'];
            $ecc = (int) ceil($data['ecc']);

            $concat_net = $data['concat_net'];
            $concat_brut = $data['concat_brut'];
            $concat_controle = $data['concat_controle'];

            if (!isset($indexes[$indexKey])) {
                $indexes[$indexKey] = [
                    'appreciation' => $fiche->appreciation_DMR,
                    'echelles' => [
                        'echelle_frequence' => $ef,
                        'echelle_impact_net' => $in,
                        'echelle_impact_brut' => $ib,
                        'echelle_cotation_net' => $ecn,
                        'echelle_cotation_brut' => $ecn,
                        'echelle_cotation_controle' => $data['ecc'],
                    ],
                    'impact_net' => [],
                    'impact_brut' => [],
                    'controle' => [],
                    'echelle_net' => [],
                    'echelle_brut' => [],
                    'echelle_controle' => [],
                ];
            }

            // Impact Net
            $net_positions = array_keys(array_filter($concatNetList, fn($v) => $v === $concat_net));
            $net_total = count($net_positions);
            $net_avant = array_search($i, $net_positions) + 1;

            $net_lignes = $net_total <= 5 ? 1 : ($net_total <= 10 ? 2 : 3);
            $net_limite = (int) ceil($net_total / $net_lignes);
            $net_ligne = $net_avant <= $net_limite ? 1 : ($net_avant <= 2 * $net_limite ? 2 : 3);
            $net_col = (int) ceil($net_total / $net_lignes);
            $net_col_x = $net_avant - ($net_ligne - 1) * $net_col;

            // Impact Brut
            $brut_positions = array_keys(array_filter($concatBrutList, fn($v) => $v === $concat_brut));
            $brut_total = count($brut_positions);
            $brut_avant = array_search($i, $brut_positions) + 1;

            $brut_lignes = $brut_total <= 5 ? 1 : ($brut_total <= 10 ? 2 : 3);
            $brut_limite = (int) ceil($brut_total / $brut_lignes);
            $brut_ligne = $brut_avant <= $brut_limite ? 1 : ($brut_avant <= 2 * $brut_limite ? 2 : 3);
            $brut_col = (int) ceil($brut_total / $brut_lignes);
            $brut_col_x = $brut_avant - ($brut_ligne - 1) * $brut_col;

            // Contrôle
            $ctrl_positions = array_keys(array_filter($concatControleList, fn($v) => $v === $concat_controle));
            $ctrl_total = count($ctrl_positions);
            $ctrl_avant = array_search($i, $ctrl_positions) + 1;

            $ctrl_lignes = $ctrl_total <= 5 ? 1 : ($ctrl_total <= 10 ? 2 : 3);
            $ctrl_limite = (int) ceil($ctrl_total / $ctrl_lignes);
            $ctrl_ligne = $ctrl_avant <= $ctrl_limite ? 1 : ($ctrl_avant <= 2 * $ctrl_limite ? 2 : 3);
            $ctrl_col = (int) ceil($ctrl_total / $ctrl_lignes);
            $ctrl_col_x = $ctrl_avant - ($ctrl_ligne - 1) * $ctrl_col;

            $indexes[$indexKey]['impact_net'] = [
                'concatenation' => $concat_net,
                'nb_total' => $net_total,
                'nb_avant' => $net_avant,
                'nb_lignes' => $net_lignes,
                'ligne_un_trois' => $net_ligne,
                'nb_colonne' => $net_col,
                'colonne_un_x' => $net_col_x,
            ];

            $indexes[$indexKey]['impact_brut'] = [
                'concatenation' => $concat_brut,
                'nb_total' => $brut_total,
                'nb_avant' => $brut_avant,
                'nb_lignes' => $brut_lignes,
                'ligne_un_trois' => $brut_ligne,
                'nb_colonne' => $brut_col,
                'colonne_un_x' => $brut_col_x,
            ];

            $indexes[$indexKey]['controle'] = [
                'concatenation' => $concat_controle,
                'nb_total' => $ctrl_total,
                'nb_avant' => $ctrl_avant,
                'nb_lignes' => $ctrl_lignes,
                'ligne_un_trois' => $ctrl_ligne,
                'nb_colonne' => $ctrl_col,
                'colonne_un_x' => $ctrl_col_x,
            ];

            $indexes[$indexKey]['echelle_net'] = [
                'freq' => round(($ef - 1) + $net_col_x / ($net_col + 1), 2),
                'impact' => round(($in - 1) + $net_ligne / ($net_lignes + 1), 2),
            ];

            $indexes[$indexKey]['echelle_brut'] = [
                'freq' => round(($ef - 1) + $brut_col_x / ($brut_col + 1), 2),
                'impact' => round(($ib - 1) + $brut_ligne / ($brut_lignes + 1), 2),
            ];

            $indexes[$indexKey]['echelle_controle'] = [
                'cotation' => round(($ecn - 1) + $ctrl_col_x / ($ctrl_col + 1), 2),
                'controle' => round(($ecc - 1) + $ctrl_ligne / ($ctrl_lignes + 1), 2),
            ];
        }

        return $indexes;
    }

    private function regrouperCauses($fiches, $level)
    {
        $resultat = [];

        foreach ($fiches as $fiche) {
            switch ($level) {
                case '1':
                    $cause = $fiche->getCauseLevelOneAttribute();
                    break;
                case '2':
                    $cause = $fiche->getCauseLevelTwoAttribute();
                    break;
                case '3':
                    $cause = $fiche->getCauseLevelThreeAttribute();
                    break;

                default:
                    return false;
                    break;
            }

            if (!$cause || !$cause->id) {
                continue; // skip si pas de cause valide
            }

            $id = $cause->id;
            $libelle = $cause->libelle;

            if (!isset($resultat[$id])) {
                $resultat[$id] = [
                    'libelle' => $libelle,
                    'nb' => 0,
                ];
            }

            $resultat[$id]['nb']++;
        }

        return $resultat;
    }

    private function compterRisquesParService($account)
    {
        $resultats = [];
        $total_general = [
            'faible' => 0,
            'moyen' => 0,
            'fort' => 0,
            'critique' => 0,
            'inacceptable' => 0,
            'total' => 0
        ];

        $services = $account->services()->get();

        foreach ($services as $service) {
            $compteur = [
                'id' => $service->id,
                'faible' => 0,
                'moyen' => 0,
                'fort' => 0,
                'critique' => 0,
                'inacceptable' => 0,
                'total' => 0
            ];

            $fiches = $service->fiche_risques()->get();

            foreach ($fiches as $fiche) {
                $niveau = strtolower(trim($fiche->echelle_risque)); // ex: "FAIBLE" → "faible"

                if (in_array($niveau, ['faible', 'moyen', 'fort', 'critique', 'inacceptable'])) {
                    $compteur[$niveau]++;
                    $compteur['total']++;

                    $total_general[$niveau]++;
                    $total_general['total']++;
                }
            }

            $resultats[$service->name] = $compteur;
        }

        $resultats['total_general'] = $total_general;

        return $resultats;
    }

    private function traiterRepartitionRisque($fiche_risques)
    {
        $result = [
            'faible' => ['nb_moyen' => 0, 'pourcentage_moyen' => 0, 'nb_max' => 0, 'pourcentage_max' => 0],
            'moyen' => ['nb_moyen' => 0, 'pourcentage_moyen' => 0, 'nb_max' => 0, 'pourcentage_max' => 0],
            'fort' => ['nb_moyen' => 0, 'pourcentage_moyen' => 0, 'nb_max' => 0, 'pourcentage_max' => 0],
            'critique' => ['nb_moyen' => 0, 'pourcentage_moyen' => 0, 'nb_max' => 0, 'pourcentage_max' => 0],
            'inacceptable' => ['nb_moyen' => 0, 'pourcentage_moyen' => 0, 'nb_max' => 0, 'pourcentage_max' => 0],
        ];

        $total_moyen = 0;
        $total_max = 0;

        foreach ($fiche_risques as $fiche) {
            // Traitement net_cotation (moyen)
            $cotation_moyen = strtolower(trim($fiche->net_cotation));
            if (isset($result[$cotation_moyen])) {
                $result[$cotation_moyen]['nb_moyen'] += 1;
                $total_moyen += 1;
            }

            // Traitement brut_cotation (max)
            $cotation_max = strtolower(trim($fiche->brut_cotation));
            if (isset($result[$cotation_max])) {
                $result[$cotation_max]['nb_max'] += 1;
                $total_max += 1;
            }
        }


        foreach ($result as $key => &$data) {
            $pourc_moyen = $total_moyen > 0 ? round(($data['nb_moyen'] / $total_moyen) * 100, 1) : 0;
            $pourc_max = $total_max > 0 ? round(($data['nb_max'] / $total_max) * 100, 1) : 0;

            $data['pourcentage_moyen'] = (fmod($pourc_moyen, 1) == 0) ? (int) $pourc_moyen : $pourc_moyen;
            $data['pourcentage_max'] = (fmod($pourc_max, 1) == 0) ? (int) $pourc_max : $pourc_max;
        }
        return $result;
    }
}
