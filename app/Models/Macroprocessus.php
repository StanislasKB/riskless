<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Macroprocessus extends Model
{
    protected $fillable = [
        'account_id',
        'created_by',
        'name',
        'entite',
        
    ];
     public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
     public function processus()
    {
        return $this->hasMany(Processus::class);
    }
}
