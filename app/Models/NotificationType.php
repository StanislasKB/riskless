<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationType extends Model
{
    protected $fillable = ['code', 'label'];

    public function userSettings()
    {
        return $this->hasMany(UserNotificationSetting::class);
    }
}
