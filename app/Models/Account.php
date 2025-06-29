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
}
