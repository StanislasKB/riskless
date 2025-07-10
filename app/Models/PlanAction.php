<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanAction extends Model
{
     protected $fillable = [
        'account_id',
        'created_by',
        'service_id',
        'responsable',
        'index',
        'priorite',
        'type',
        'description',
        'date_debut_prevue',
        'date_fin_prevue',
        'statut',
        'progression',

    ];

     public function fiche_risques()
    {
        return $this->belongsToMany(FicheRisque::class, 'fiche_risque_plan_actions');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
     public function service()
    {
        return $this->belongsTo(Service::class);
    }
        public function avancements()
        {
            return $this->hasMany(AvancementPlanAction::class, 'plan_action_id');
        }

        public function scopeFilterByService($query, $serviceId)
        {
            return $query->where('service_id', $serviceId);
        }
}
