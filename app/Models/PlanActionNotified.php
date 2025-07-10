<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanActionNotified extends Model
{
    protected $fillable = [
        'is_notified',
        'plan_action_id',
    ];
 
}
