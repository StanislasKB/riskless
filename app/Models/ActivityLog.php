<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'account_id',
        'causer_id',
        'causer_type',
        'subject_id',
        'subject_type',
        'action',
        'description',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }
}
