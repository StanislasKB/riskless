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

    public function __construct($accountId, $createdBy, $validatedBy = null, $serviceId = null, $filePath)
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
        $sheets = [];
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

    public function __construct($accountId, $createdBy, $validatedBy = null, $serviceId = null)
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
            $filteredData = $this->filterValidRows($data);


            // Analyser et extraire les données de la fiche
            $ficheData = $this->extractFicheData($filteredData);


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
    /**
     * Filtrer les lignes pour exclure celles qui sont vides ou contiennent des formules Excel
     */
    private function filterValidRows($data)
    {
        $validRows = [];

        foreach ($data as $rowIndex => $row) {
            if (empty($row) || !is_array($row)) {
                continue;
            }

            // Vérifier si la ligne contient des données valides
            if ($this->isValidRow($row)) {
                $validRows[$rowIndex] = $row;
            }
        }

        return $validRows;
    }

    /**
     * Vérifier si une ligne contient des données valides
     */
    private function isValidRow($row)
    {
        $hasValidData = false;

        foreach ($row as $cell) {
            // Ignorer les cellules nulles
            if ($cell === null) {
                continue;
            }

            // Ignorer les cellules vides (chaînes vides ou espaces)
            if (is_string($cell) && trim($cell) === '') {
                continue;
            }

            // Ignorer les formules Excel qui commencent par "="
            if (is_string($cell) && strpos(trim($cell), '=') === 0) {
                continue;
            }

            // Si on arrive ici, la cellule contient des données valides
            $hasValidData = true;
            break;
        }

        return $hasValidData;
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

            // Extraction des informations d'en-tête - Ligne 1
            if (isset($row[0]) && $row[0] === 'Entité :') {
                $ficheData['entite'] = $this->cleanValue($row[2] ?? '');
                $ficheData['version'] = $this->cleanValue($row[7] ?? '');
            }

            // Extraction département - Ligne 2
            if (isset($row[0]) && $row[0] === 'Département :') {
                $ficheData['departement'] = $this->cleanValue($row[2] ?? '');
                $ficheData['maj_date'] = $this->cleanValue($row[7] ?? '');
            }

            // Extraction service - Ligne 3
            if (isset($row[0]) && $row[0] === 'Service :') {
                $ficheData['service'] = $this->cleanValue($row[2] ?? '');
            }

            // Extraction du risque métier - Ligne 5
            if (isset($row[0]) && $row[0] === 'Risque Métier') {
                $ficheData['code_risque'] = $this->cleanValue($row[1] ?? '');
                $ficheData['ref_supp'] = $this->cleanValue($row[2] ?? '');
                $ficheData['libelle_risk'] = $this->cleanValue($row[3] ?? '');
                $ficheData['type_risque_majeur'] = $this->cleanValue($row[10] ?? '');
                $ficheData['type_risque_operationnel'] = $this->cleanValue($row[11] ?? '');
            }

            // Extraction de la description - Ligne 7
            if (isset($row[1]) && $row[1] === 'Description') {
                $ficheData['description'] = $row[2];
            }

            // Extraction du macro-processus - Ligne 7
            if (isset($row[1]) && $row[1] === 'Macro-processus concerné') {
                $ficheData['macroprocessus'] = $this->cleanValue($row[2] ?? '');
            }

            // Extraction du processus - Ligne 8
            if (isset($row[1]) && $row[1] === 'Processus concerné') {
                $ficheData['processus'] = $this->cleanValue($row[2] ?? '');
            }

            // Extraction de l'identification - Ligne 9
            if (isset($row[1]) && $row[1] === 'Identification par : ') {
                $ficheData['identified_by'] = $this->cleanValue($row[2] ?? '');
            }

            // Extraction des causes - Lignes 11-13
            if (isset($row[0]) && $row[0] === 'Cause') {
                $ficheData['cause_niveau_1'] = $this->cleanValue($row[2] ?? '');
            }
            if (isset($row[1]) && $row[1] === 'Niveau 2 (Sous-catégorie Bâle II)') {
                $ficheData['cause_niveau_2'] = $this->cleanValue($row[2] ?? '');
            }
            if (isset($row[1]) && $row[1] === 'Niveau 3') {
                $ficheData['cause_niveau_3'] = $this->cleanValue($row[2] ?? '');
            }

            // Extraction de la fréquence - Ligne 14
            if (isset($row[1]) && $row[1] === 'Fréquence de survenance de la cause') {
                $ficheData['frequence'] = $this->extractFrequence($row[2] ?? '');
            }

            // Extraction des impacts - Lignes 16-17
            if (isset($row[0]) && $row[0] === 'Description de l\'impact') {
                // Risque perte nette
                $ficheData['net_impact_value'] = $this->extractNumericValue($row[2] ?? '');
                $ficheData['net_impact'] = $this->cleanValue($row[4] ?? '');
            }
            if (isset($row[1]) && $row[1] === 'Risque perte brute') {
                $ficheData['brut_impact_value'] = $this->extractNumericValue($row[2] ?? '');
                $ficheData['brut_impact'] = $this->cleanValue($row[4] ?? '');
            }

            // Extraction des conséquences boolean - Lignes 18-26
            if (isset($row[1]) && $row[1] === 'Manque à gagner') {
                $ficheData['manque_a_gagner'] = $this->convertToBoolean($row[2] ?? '');
            }
            if (isset($row[1]) && $row[1] === 'Conséquences réglementaires') {
                $ficheData['consequence_reglementaire'] = $this->convertToBoolean($row[2] ?? '');
            }
            if (isset($row[1]) && $row[1] === 'Conséquences juridiques ') {
                $ficheData['consequence_juridique'] = $this->convertToBoolean($row[2] ?? '');
            }
            if (isset($row[1]) && $row[1] === 'Conséqu. humaines et sociales') {
                $ficheData['consequence_humaine'] = $this->convertToBoolean($row[2] ?? '');
            }
            if (isset($row[1]) && $row[1] === 'Interruption de processus') {
                $ficheData['interruption_processus'] = $this->convertToBoolean($row[2] ?? '');
            }
            if (isset($row[1]) && $row[1] === 'Risque d\'image') {
                $ficheData['risque_image'] = $this->convertToBoolean($row[2] ?? '');
            }
            if (isset($row[1]) && $row[1] === 'Insatisfaction client') {
                $ficheData['insatisfaction_client'] = $this->convertToBoolean($row[2] ?? '');
            }
            if (isset($row[1]) && $row[1] === 'Impact risque de crédit') {
                $ficheData['impact_risque_credit'] = $this->convertToBoolean($row[2] ?? '');
            }
            if (isset($row[1]) && $row[1] === 'Impact risque de marché') {
                $ficheData['impact_risque_marche'] = $this->convertToBoolean($row[2] ?? '');
            }

            // Extraction du contrôle - Lignes 29-30
            if (isset($row[0]) && $row[0] === 'Contrôle') {
                $ficheData['description_DMR'] = $this->cleanValue($row[2] ?? '');
            }
            if (isset($row[1]) && $row[1] === 'Appréciation globale du dispositif de contrôle') {
                $ficheData['appreciation_DMR'] = $this->extractAppreciation($row[2] ?? '');
            }

            // Extraction des indicateurs - Lignes 32+
            if (isset($row[1]) && $row[1] === 'Risque à piloter') {
                $ficheData['risque_a_piloter'] = $this->convertToBoolean($row[2] ?? '');
            }

            // Plan action
            if (isset($row[1]) && $row[1] === 'Action(s) de maîtrise du risque') {
                $ficheData['action_maitrise_risque'] = $this->convertToBoolean($row[2] ?? '');
            }

            // description action

            if (isset($row[1]) && $row[1] === 'Description du plan d\'action') {
                $ficheData['plan_action_description'] = $this->cleanValue($row[2] ?? '');
            }

            // plan_action_type

            if (isset($row[1]) && $row[1] === 'Type de plan d\'action') {
                $ficheData['plan_action_type'] = $this->cleanValue($row[2] ?? '');
            }

            // priorite de plan d'action
            if (isset($row[1]) && $row[1] === 'Priorité des plans d\'action') {
                $ficheData['plan_action_priorite'] = $this->cleanValue($row[2] ?? '');
            }

            // statut de plan d'action
            if (isset($row[1]) && $row[1] === 'Statut') {
                $ficheData['plan_action_statut'] = $this->cleanValue($row[2] ?? '');
            }

            // responsable du plan d'action
            if (isset($row[1]) && $row[1] === 'Responsable') {
                $ficheData['plan_action_responsable'] = $this->cleanValue($row[2] ?? '');
            }
        }

        // Formatage des causes en JSON
        $ficheData['risk_cause'] = $this->formatRiskCause($ficheData);

        return $ficheData;
    }

    /**
     * Nettoyer une valeur en supprimant les espaces et les caractères indésirables
     */
    private function cleanValue($value)
    {
        if (is_null($value) || $value === '') {
            return null;
        }

        // Convertir en string si c'est un nombre
        $value = (string) $value;

        // Supprimer les espaces en début et fin
        $value = trim($value);

        // Ignorer les formules Excel
        if (strpos($value, '=') === 0) {
            return null;
        }

        // Retourner null si la valeur est vide après nettoyage
        return $value === '' ? null : $value;
    }

    /**
     * Extraire une valeur numérique d'une cellule
     */
    private function extractNumericValue($value)
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        // Extraire les nombres d'une chaîne
        if (is_string($value)) {
            preg_match('/[\d,\.]+/', $value, $matches);
            if (!empty($matches)) {
                return (float) str_replace(',', '.', $matches[0]);
            }
        }

        return null;
    }

    /**
     * Convertir une valeur en boolean
     */
    private function convertToBoolean($value)
    {
        if (is_null($value) || $value === '') {
            return false;
        }

        $value = strtolower(trim($value));

        return in_array($value, ['oui', 'yes', '1', 'true', 'vrai']);
    }

    /**
     * Formater les causes en JSON
     */
    private function formatRiskCause($ficheData)
    {
        $causes = [
            'niveau_1' => $ficheData['cause_niveau_1'] ?? null,
            'niveau_2' => $ficheData['cause_niveau_2'] ?? null,
            'niveau_3' => $ficheData['cause_niveau_3'] ?? null,
        ];

        // Supprimer les niveaux null
        $causes = array_filter($causes, function ($value) {
            return !is_null($value) && $value !== '';
        });

        return empty($causes) ? null : json_encode($causes);
    }


    private function createFicheRisque($data)
    {
        $macroprocessus_id = $this->getOrCreateCategoryId($data['category'] ?? 'Risque Métier', $data['account_id'], $data['created_by']);
        return FicheRisque::create([
            'account_id' => $data['account_id'],
            'entite' => $data['entite'] ?? '',
            'departement' => $data['departement'] ?? '',
            'service_id' => $data['service_id'],
            'created_by' => $data['created_by'],
            'validated_by' => $data['validated_by'],
            'version' => $data['version'] ?? null,
            'ref_supp' => $data['ref_supp'] ?? null,
            'libelle_risk' => $data['libelle_risk'] ?? '',
            'category_id' => $this->getOrCreateCategoryId($data['category'] ?? 'Risque Métier', $data['account_id'], $data['created_by']),
            'description' => $data['description'] ?? null,
            'macroprocessus_id' => $macroprocessus_id,
            'processus_id' => $this->getOrCreateProcessId($data['processus'] ?? '', $macroprocessus_id, $data['created_by']),
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

    private function getOrCreateProcessId(
        string $processName,
        int $macroprocessusId,
        int $createdBy
    ): int {
        if (empty($processName)) {
            throw new \InvalidArgumentException('Process name cannot be empty');
        }

        $process = DB::table('processuses')
            ->where('name', $processName)
            ->where('macroprocessus_id', $macroprocessusId)
            ->first();

        if ($process) {
            return $process->id;
        }

        return DB::table('processuses')->insertGetId([
            'macroprocessus_id' => $macroprocessusId,
            'created_by' => $createdBy,
            'name' => $processName,
            'created_at' => now(),
            'updated_at' => now(),
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

    private function getOrCreateCategoryId(string $categoryName, int $account_id, int $created_by)
    {
        if (empty($categoryName)) {
            return null;
        }

        $category = DB::table('risk_categories')
            ->where('libelle', $categoryName)
            ->where('account_id', $account_id)
            ->first();

        if ($category) {
            return $category->id;
        }

        return DB::table('risk_categories')->insertGetId([
            'account_id' => $account_id,
            'created_by' => $created_by,
            'libelle' => $categoryName,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function getOrCreateMacroProcessId(
        string $processName,
        string $entity,
        int $account_id,
        int $created_by
    ): int {
        if (empty($processName)) {
            throw new \InvalidArgumentException('Process name cannot be empty');
        }

        $macroProcess = DB::table('macroprocessuses')
            ->where('name', $processName)
            ->where('entite', $entity)
            ->where('account_id', $account_id)
            ->first();

        if ($macroProcess) {
            return $macroProcess->id;
        }

        return DB::table('macroprocessuses')->insertGetId([
            'account_id' => $account_id,
            'created_by' => $created_by,
            'name' => $processName,
            'entite' => $entity,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function getCategoryId($categoryName)
    {
        if (empty($categoryName)) return 1;
        return DB::table('risk_categories')->where('name', 'like', "%$categoryName%")->value('id') ?? 1;
    }

    private function getOrCreateMacroprocessusId($macroprocessusName, $account_id, $created_by)
    {
        if (empty($macroprocessusName)) return null;
        return DB::table('macroprocessuses')->where('name', 'like', "%$macroprocessusName%")->value('id') ?? 1;
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
