<?php

namespace App\Imports;

use App\Models\FicheRisque;
use Exception;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
// use Maatwebsite\Excel\Concerns\WithoutHeadingRow;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithConditionalSheets;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FicheRisqueImport implements WithMultipleSheets
{
    // use WithConditionalSheets;

    private $accountId;
    private $createdBy;
    private $validatedBy;
    private $serviceId;
    private $filePath;

    public function __construct($accountId, $createdBy, $validatedBy = null,$serviceId=null, $filePath)
    {
        $this->accountId = $accountId;
        $this->createdBy = $createdBy;
        $this->validatedBy = $validatedBy;
        $this->serviceId = $serviceId;
        $this->filePath = $filePath;
    }

    public function sheets(): array
    {
        $sheetNames = $this->getSecSheetNames($this->filePath);
        $sheets=[];
        foreach ($sheetNames as $sheetName) {
            if (str_starts_with($sheetName, 'SEC')) {
                $sheets[$sheetName] = new FicheRisqueSheetImport(
                    $this->accountId,
                    $this->createdBy,
                    $this->validatedBy,
                    $this->serviceId,
                );
            }
        }


        return $sheets;
    }



private function getSecSheetNames(string $filePath): array
{
    try {
        // Créer le reader approprié
        $reader = IOFactory::createReaderForFile($filePath);

        // Optimiser les paramètres de lecture pour les métadonnées seulement
        $reader->setReadDataOnly(true);
        $reader->setReadEmptyCells(false);

        // Lire seulement les noms des feuilles sans charger le contenu
        $worksheetNames = $reader->listWorksheetNames($filePath);
        $sheetNames = [];

        // Filtrer les feuilles qui commencent par 'SEC'
        foreach ($worksheetNames as $sheetName) {
            if (str_starts_with($sheetName, 'SEC')) {
                $sheetNames[] = $sheetName;
            }
        }

        // Libérer la mémoire
        unset($reader);

        return $sheetNames;

    } catch (Exception $e) {
        // Gestion d'erreur
        error_log("Erreur lors de la lecture du fichier Excel : " . $e->getMessage());
        return [];
    }
}

}

class FicheRisqueSheetImport implements ToCollection
{
    private $accountId;
    private $createdBy;
    private $validatedBy;
    private $serviceId;

    public function __construct($accountId, $createdBy, $validatedBy = null,$serviceId=null)
    {
        $this->accountId = $accountId;
        $this->createdBy = $createdBy;
        $this->validatedBy = $validatedBy;
        $this->serviceId = $serviceId;
    }

    public function collection(Collection $rows)
    {


        try {
            // Convertir les lignes en tableau pour faciliter l'accès
            $data = $rows->toArray();
            dd($data);

            // Analyser et extraire les données de la fiche
            $ficheData = $this->extractFicheData($data);

            if ($ficheData && !empty($ficheData['libelle_risk'])) {
                $this->createFicheRisque($ficheData);
                Log::info('Fiche de risque importée avec succès: ' . $ficheData['libelle_risk']);
            }

        } catch (\Exception $e) {
            dd($e);
            Log::error('Erreur lors de l\'import de la fiche de risque: ' . $e->getMessage());
            throw $e;
        }
    }

    private function extractFicheData($data)
    {
        $ficheData = [
            'account_id' => $this->accountId,
            'created_by' => $this->createdBy,
            'validated_by' => $this->validatedBy,
            'service_id' => $this->serviceId,

        ];

        foreach ($data as $rowIndex => $row) {
            if (empty($row) || !is_array($row)) continue;

            $rowText = implode(' ', array_filter($row));

            // Extraction des informations d'en-tête
            if (strpos($rowText, 'Entité') !== false) {
                $ficheData['entite'] = $this->extractAfterColon($rowText, 'Entité');
                $ficheData['version'] = $this->extractAfterColon($rowText, 'Version');
            }



            if (strpos($rowText, 'Département') !== false) {
                $ficheData['departement'] = $this->extractAfterColon($rowText, 'Département');
            }

            if (strpos($rowText, 'Service') !== false) {
                $ficheData['service'] = $this->extractAfterColon($rowText, 'Service');
            }

            // Extraction du libellé du risque et références
            if (strpos($rowText, 'SEC') !== false && strpos($rowText, 'Réf.sup') !== false) {
                $parts = explode('Réf.sup', $rowText);
                if (count($parts) >= 2) {
                    $ficheData['libelle_risk'] = trim($parts[1]);
                    $ficheData['ref_supp'] = $this->extractReference($parts[0]);
                }
            }

            // Extraction de la description
            if (strpos($rowText, 'Description') !== false && strlen($rowText) > 50) {
                $ficheData['description'] = $this->extractAfterLabel($rowText, 'Description');
            }

            // Extraction du macro-processus
            if (strpos($rowText, 'Macro-processus concerné') !== false) {
                $ficheData['macroprocessus'] = $this->extractAfterColon($rowText, 'Macro-processus concerné');
            }

            // Extraction du processus
            if (strpos($rowText, 'Processus concerné') !== false) {
                $ficheData['processus'] = $this->extractAfterColon($rowText, 'Processus concerné');
            }

            // Extraction de l'identification
            if (strpos($rowText, 'Identification par') !== false) {
                $ficheData['identified_by'] = $this->extractAfterColon($rowText, 'Identification par');
            }

            // Extraction des causes
            if (strpos($rowText, 'Niveau 1') !== false) {
                $ficheData['cause_niveau_1'] = $this->extractAfterColon($rowText, 'Niveau 1');
            }
            if (strpos($rowText, 'Niveau 2') !== false) {
                $ficheData['cause_niveau_2'] = $this->extractAfterColon($rowText, 'Niveau 2');
            }
            if (strpos($rowText, 'Niveau 3') !== false) {
                $ficheData['cause_niveau_3'] = $this->extractAfterColon($rowText, 'Niveau 3');
            }

            // Extraction de la fréquence
            if (strpos($rowText, 'Fréquence de survenance') !== false) {
                $ficheData['frequence'] = $this->extractFrequence($rowText);
            }

            // Extraction des impacts
            if (strpos($rowText, 'Risque perte nette') !== false) {
                $ficheData['net_impact_value'] = $this->extractNumber($rowText);
                $ficheData['net_impact'] = $this->extractImpactLevel($rowText);
            }

            if (strpos($rowText, 'Risque perte brute') !== false) {
                $ficheData['brut_impact_value'] = $this->extractNumber($rowText);
                $ficheData['brut_impact'] = $this->extractImpactLevel($rowText);
            }

            // Extraction des conséquences (boolean)
            $ficheData['manque_a_gagner'] = $this->extractBoolean($data, 'Manque à gagner');
            $ficheData['consequence_reglementaire'] = $this->extractBoolean($data, 'Conséquences réglementaires');
            $ficheData['consequence_juridique'] = $this->extractBoolean($data, 'Conséquences juridiques');
            $ficheData['consequence_humaine'] = $this->extractBoolean($data, 'Conséqu. humaines et sociales');
            $ficheData['interruption_processus'] = $this->extractBoolean($data, 'Interruption de processus');
            $ficheData['risque_image'] = $this->extractBoolean($data, 'Risque d\'image');
            $ficheData['insatisfaction_client'] = $this->extractBoolean($data, 'Insatisfaction client');
            $ficheData['impact_risque_credit'] = $this->extractBoolean($data, 'Impact risque de crédit');
            $ficheData['impact_risque_marche'] = $this->extractBoolean($data, 'Impact risque de marché');

            // Extraction DMR
            if (strpos($rowText, 'Description du dispositif') !== false) {
                $ficheData['description_DMR'] = $this->extractMultiLineText($data, $rowIndex);
            }

            if (strpos($rowText, 'Appréciation globale') !== false) {
                $ficheData['appreciation_DMR'] = $this->extractAppreciation($rowText);
            }

            // Extraction des indicateurs
            $ficheData['risque_a_piloter'] = $this->extractBoolean($data, 'Risque à piloter');
            $ficheData['action_maitrise_risque'] = $this->extractBoolean($data, 'Action(s) de maîtrise du risque');
        }

        // Formatage des causes en JSON
        $ficheData['risk_cause'] = $this->formatRiskCause($ficheData);

        return $ficheData;
    }

    private function createFicheRisque($data)
    {
        return FicheRisque::create([
            'account_id' => $data['account_id'],
            'entite' => $data['entite'] ?? '',
            'departement' => $data['departement'] ?? '',
            'service_id' => $this->getServiceId($data['service'] ?? ''),
            'created_by' => $data['created_by'],
            'validated_by' => $data['validated_by'],
            'version' => $data['version'] ?? null,
            'ref_supp' => $data['ref_supp'] ?? null,
            'libelle_risk' => $data['libelle_risk'] ?? '',
            'category_id' => $this->getCategoryId($data['category'] ?? 'Risque Métier'),
            'description' => $data['description'] ?? null,
            'macroprocessus_id' => $this->getMacroprocessusId($data['macroprocessus'] ?? ''),
            'processus_id' => $this->getProcessusId($data['processus'] ?? ''),
            'identified_by' => $data['identified_by'] ?? null,
            'risk_cause' => $data['risk_cause'] ?? null,
            'frequence' => $this->normalizeFrequence($data['frequence'] ?? null),
            'net_impact' => $this->normalizeImpact($data['net_impact'] ?? null),
            'net_impact_value' => $data['net_impact_value'] ?? null,
            'brut_impact' => $this->normalizeImpact($data['brut_impact'] ?? null),
            'brut_impact_value' => $data['brut_impact_value'] ?? null,
            'manque_a_gagner' => $data['manque_a_gagner'] ?? false,
            'consequence_reglementaire' => $data['consequence_reglementaire'] ?? false,
            'consequence_juridique' => $data['consequence_juridique'] ?? false,
            'consequence_humaine' => $data['consequence_humaine'] ?? false,
            'interruption_processus' => $data['interruption_processus'] ?? false,
            'risque_image' => $data['risque_image'] ?? false,
            'insatisfaction_client' => $data['insatisfaction_client'] ?? false,
            'impact_risque_credit' => $data['impact_risque_credit'] ?? false,
            'impact_risque_marche' => $data['impact_risque_marche'] ?? false,
            'description_DMR' => $data['description_DMR'] ?? null,
            'appreciation_DMR' => $this->normalizeAppreciation($data['appreciation_DMR'] ?? null),
            'risque_a_piloter' => $data['risque_a_piloter'] ?? false,
            'action_maitrise_risque' => $data['action_maitrise_risque'] ?? false,
        ]);
    }

    // Méthodes utilitaires pour l'extraction
    private function extractAfterColon($text, $label)
    {
        $pattern = '/' . preg_quote($label, '/') . '\s*:\s*([^:]+?)(?=\s+\w+\s*:|$)/i';
        if (preg_match($pattern, $text, $matches)) {
            return trim($matches[1]);
        }
        return null;
    }

    private function extractAfterLabel($text, $label)
    {
        $pos = strpos($text, $label);
        if ($pos !== false) {
            return trim(substr($text, $pos + strlen($label)));
        }
        return null;
    }

    private function extractReference($text)
    {
        if (preg_match('/SEC\d+/', $text, $matches)) {
            return $matches[0];
        }
        return null;
    }

    private function extractNumber($text)
    {
        if (preg_match('/\d+(?:\s*\d+)*/', $text, $matches)) {
            return (int) str_replace(' ', '', $matches[0]);
        }
        return null;
    }

    private function extractImpactLevel($text)
    {
        $impacts = ['FAIBLE', 'MODERE', 'MOYEN', 'FORT', 'MAJEUR', 'CRITIQUE'];
        foreach ($impacts as $impact) {
            if (stripos($text, $impact) !== false) {
                return $impact;
            }
        }
        return null;
    }

    private function extractFrequence($text)
    {
        if (stripos($text, 'Peu fréquent') !== false) return 'PEU_FREQUENT';
        if (stripos($text, 'Très fréquent') !== false) return 'TRES_FREQUENT';
        if (stripos($text, 'Fréquent') !== false) return 'FREQUENT';
        if (stripos($text, 'Extrêmement rare') !== false) return 'EXTREMEMENT_RARE';
        if (stripos($text, 'Rare') !== false) return 'RARE';
        if (stripos($text, 'Permanent') !== false) return 'PERMANENT';
        return null;
    }

    private function extractBoolean($data, $label)
    {
        foreach ($data as $row) {
            if (!is_array($row)) continue;
            $rowText = implode(' ', array_filter($row));
            if (strpos($rowText, $label) !== false) {
                return stripos($rowText, 'oui') !== false;
            }
        }
        return false;
    }

    private function extractMultiLineText($data, $startIndex)
    {
        $text = '';
        for ($i = $startIndex; $i < count($data) && $i < $startIndex + 3; $i++) {
            if (isset($data[$i]) && is_array($data[$i])) {
                $rowText = implode(' ', array_filter($data[$i]));
                if (!empty($rowText) && !preg_match('/^(Appréciation|Indicateurs|Risque à piloter)/i', $rowText)) {
                    $text .= ' ' . $rowText;
                }
            }
        }
        return trim($text);
    }

    private function extractAppreciation($text)
    {
        if (stripos($text, 'Efficace') !== false) return 'EFFICACE';
        if (stripos($text, 'Conforme') !== false) return 'CONFORME';
        if (stripos($text, 'Insuffisant') !== false) return 'INSUFFISANT';
        if (stripos($text, 'Acceptable') !== false) return 'ACCEPTABLE';
        if (stripos($text, 'Inexistant') !== false) return 'INEXISTANT';
        return null;
    }

    private function formatRiskCause($data)
    {
        $causes = [];
        if (!empty($data['cause_niveau_1'])) $causes['level_1'] = $data['cause_niveau_1'];
        if (!empty($data['cause_niveau_2'])) $causes['level_2'] = $data['cause_niveau_2'];
        if (!empty($data['cause_niveau_3'])) $causes['level_3'] = $data['cause_niveau_3'];

        return empty($causes) ? null : json_encode($causes);
    }

    // Méthodes de normalisation
    private function normalizeFrequence($value)
    {
        if (empty($value)) return null;
        $frequences = ['EXTREMEMENT_RARE', 'RARE', 'PEU_FREQUENT', 'FREQUENT', 'TRES_FREQUENT', 'PERMANENT'];
        return in_array($value, $frequences) ? $value : null;
    }

    private function normalizeImpact($value)
    {
        if (empty($value)) return null;
        $impacts = ['FAIBLE', 'MODERE', 'MOYEN', 'FORT', 'MAJEUR', 'CRITIQUE'];
        return in_array($value, $impacts) ? $value : null;
    }

    private function normalizeAppreciation($value)
    {
        if (empty($value)) return null;
        $appreciations = ['INEXISTANT', 'ACCEPTABLE', 'INSUFFISANT', 'CONFORME', 'EFFICACE'];
        return in_array($value, $appreciations) ? $value : null;
    }

    // Méthodes de récupération des IDs (à adapter selon votre BDD)
    private function getServiceId($serviceName)
    {
        if (empty($serviceName)) return 1;
        return DB::table('services')->where('name', 'like', "%$serviceName%")->value('id') ?? 1;
    }

    private function getCategoryId($categoryName)
    {
        if (empty($categoryName)) return 1;
        return DB::table('risk_categories')->where('name', 'like', "%$categoryName%")->value('id') ?? 1;
    }

    private function getMacroprocessusId($macroprocessusName)
    {
        if (empty($macroprocessusName)) return 1;
        return DB::table('macroprocessuses')->where('name', 'like', "%$macroprocessusName%")->value('id') ?? 1;
    }

    private function getProcessusId($processusName)
    {
        if (empty($processusName)) return 1;
        return DB::table('processuses')->where('name', 'like', "%$processusName%")->value('id') ?? 1;
    }
}
