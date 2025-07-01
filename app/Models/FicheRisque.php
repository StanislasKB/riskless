<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FicheRisque extends Model
{
    protected $fillable = [
        'account_id',
        'entite',
        'departement',
        'service_id',
        'created_by',
        'validated_by',
        'version',
        'entretiens',
        'index',
        'ref_supp',
        'libelle_risk',
        'category_id',
        'description',
        'macroprocessus_id',
        'processus_id',
        'identified_by',
        'risk_cause',
        'frequence',
        'net_impact',
        'net_impact_value',
        'brut_impact',
        'brut_impact_value',
        'net_cotation',
        'brut_cotation',
        'echelle_risque',
        'manque_a_gagner',
        'is_validated',
        'consequence_reglementataire',
        'consequence_juridique',
        'consequence_humaine',
        'interruption_processus',
        'risque_image',
        'insatisfaction_client',
        'impact_risque_credit',
        'impact_risque_marche',
        'description_DMR',
        'appreciation_DMR',
        'risque_a_piloter',
        'indicateur_exposition',
        'indicateur_risque_survenu',
        'indicateur_risque_avere',
        'indicateur_risque_evite',
        'action_maitrise_risque',
        'plan_action_id',
        'a_indicateur',
        'indicateur_id',
        'other_informations',
    ];
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function category()
    {
        return $this->belongsTo(RiskCategory::class);
    }

    public function macroprocessus()
    {
        return $this->belongsTo(Macroprocessus::class);
    }

    public function processus()
    {
        return $this->belongsTo(Processus::class);
    }

    public function indicateurs()
    {
        return $this->belongsToMany(Indicateur::class, 'fiche_risque_indicateurs');
    }
    public function plan_actions()
    {
        return $this->belongsToMany(PlanAction::class, 'fiche_risque_plan_actions');
    }

    public function getCauseLevelOneAttribute(): ?RiskCause
    {
        $causeId = json_decode($this->risk_cause, true)['level_1'] ?? null;
        return $causeId ? RiskCause::find($causeId) : null;
    }
    public function getCauseLevelTwoAttribute(): ?RiskCause
    {
        $causeId = json_decode($this->risk_cause, true)['level_2'] ?? null;
        return $causeId ? RiskCause::find($causeId) : null;
    }
    public function getCauseLevelThreeAttribute(): ?RiskCause
    {
        $causeId = json_decode($this->risk_cause, true)['level_3'] ?? null;
        return $causeId ? RiskCause::find($causeId) : null;
    }
}
