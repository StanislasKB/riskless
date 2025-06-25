<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Processus extends Model
{
    protected $fillable = [
        'macroprocessus_id',
        'created_by',
        'name',
        'domaine',
        'intervenant',
        'procedure',
        'description',
        'pilote',
        'controle_interne',
        'periodicite',
        'piste_audit',
        'indicateur_performance',
        'actif',
        'commentaire',
    ];
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function macroprocessus()
{
    return $this->belongsTo(Macroprocessus::class);
}
}
