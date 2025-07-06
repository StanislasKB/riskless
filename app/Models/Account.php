<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Account extends Model
{
    protected $fillable = [
        'owner_id',
        'name',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users()
    {
        return $this->hasManyThrough(
            User::class,
            AccountUser::class, // si tu as un modèle AccountUser
            'account_id',       // Foreign key on AccountUser
            'id',               // Foreign key on User
            'id',               // Local key on Account
            'user_id'           // Local key on AccountUser
        );
    }
    public function services()
    {
        return $this->hasMany(Service::class);
    }
    public function causes()
    {
        return $this->hasMany(RiskCause::class);
    }
    public function categories()
    {
        return $this->hasMany(RiskCategory::class);
    }
    public function processus()
    {
        return $this->hasManyThrough(
            Processus::class,          // Le modèle final (cible)
            Macroprocessus::class,     // Le modèle intermédiaire
            'account_id',              // Clé étrangère sur le modèle intermédiaire (Macroprocessus)
            'macroprocessus_id',       // Clé étrangère sur le modèle final (Processus)
            'id',                      // Clé locale sur le modèle Account
            'id'                       // Clé locale sur le modèle Macroprocessus
        );
    }
     public function fiche_risques()
    {
        return $this->hasMany(FicheRisque::class);
    }
    public function indicateurs()
    {
        return $this->hasMany(Indicateur::class);
    }
    public function plan_actions()
    {
        return $this->hasMany(PlanAction::class);
    }

    public function indicateursNonLies()
    {
        return $this->hasMany(Indicateur::class)
            ->whereDoesntHave('fiche_risques');
    }
    public function planActionsNonLies()
    {
        return $this->hasMany(PlanAction::class)
            ->whereDoesntHave('fiche_risques');
    }

    public function planActionsDisponibles($ficheId = null)
    {
        return $this->plan_actions()
            ->whereDoesntHave('fiche_risques', function ($query) use ($ficheId) {
                if ($ficheId) {
                    $query->where('fiche_risque_id', '!=', $ficheId);
                }
            })->get();
    }
    public function indicateursDisponibles($ficheId = null)
    {
        return $this->indicateurs()
            ->whereDoesntHave('fiche_risques', function ($query) use ($ficheId) {
                if ($ficheId) {
                    $query->where('fiche_risque_id', '!=', $ficheId);
                }
            })->get();
    }
}
