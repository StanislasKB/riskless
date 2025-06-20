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
}
