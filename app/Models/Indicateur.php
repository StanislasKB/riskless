<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Indicateur extends Model
{
   protected $fillable = [
        'account_id',
        'created_by',
        'service_id',
        'departement',
        'index',
        'libelle',
        'type',
        'precision_indicateur',
        'source',
        'chemin_access',
        'periodicite',
        'type_seuil',
        'seuil_alerte',
        'valeur_actuelle',
        'date_maj_valeur',
        'commentaire',
        
    ];
}
