<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvolutionIndicateur extends Model
{
    protected $fillable = [
        'indicateur_id',
        'mois',
        'annee',
        'created_by',
        'valeur',
        
    ];
}
