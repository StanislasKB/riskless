<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvancementPlanAction extends Model
{
     protected $fillable = [
        'plan_action_id',
        'created_by',
        'mois',
        'annee',
        'statut',
        'reste_a_faire',
        'commentaire',
    ];

    public function planAction()
    {
        return $this->belongsTo(PlanAction::class, 'plan_action_id');
    }
}
