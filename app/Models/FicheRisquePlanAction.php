<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FicheRisquePlanAction extends Model
{
    protected $fillable = [
        'fiche_risque_id',
        'plan_action_id',
    ];
}
