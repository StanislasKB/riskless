<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    protected $fillable = [
        'account_id',
        'name',
        'uuid',
    ];
    protected static function booted()
    {
        static::creating(function ($service) {
            if (empty($service->uuid)) {
                $service->uuid = Str::uuid();
            }
        });
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    public function fiche_risques()
    {
        return $this->hasMany(FicheRisque::class, 'service_id');
    }
    public function indicateurs()
    {
        return $this->hasMany(Indicateur::class, 'service_id');
    }
    public function plan_actions()
    {
        return $this->hasMany(PlanAction::class, 'service_id');
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
