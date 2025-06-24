<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskCategory extends Model
{
    protected $fillable = [
        'account_id',
        'created_by',
        'libelle',
        
    ];
}
