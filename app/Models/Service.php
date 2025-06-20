<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
      protected $fillable = [
        'account_id',
        'name',
    ];

     public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
