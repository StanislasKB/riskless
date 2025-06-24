<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskCause extends Model
{
    protected $fillable = [
        'account_id',
        'created_by',
        'libelle',
        'level',
    ];
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
