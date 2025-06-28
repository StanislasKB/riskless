<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FicheRisqueIndicateur extends Model
{
    protected $fillable = [
        'fiche_risque_id',
        'indicateur_id',
    ];
}
