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

    public function fiche_risques()
    {
        return $this->belongsToMany(FicheRisque::class, 'fiche_risque_indicateurs');
    }

    public function evolutions()
    {
        return $this->hasMany(EvolutionIndicateur::class,'indicateur_id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
