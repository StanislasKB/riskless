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
}
