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
}
