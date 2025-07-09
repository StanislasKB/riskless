<?php

namespace App\Logging;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogger
{
    protected $causer = null;
    protected $subject = null;
    protected $account_id = null;
    protected $properties = [];
    protected $description = null;
    protected $action = null;

    public function causedBy($user)
    {
        $this->causer = $user;
        $this->account_id = $user->account->id ?? null;
        return $this;
    }

    public function performedOn($model)
    {
        $this->subject = $model;
        return $this;
    }

    public function withProperties(array $properties)
    {
        $this->properties = $properties;
        return $this;
    }

    public function action(string $action)
    {
        $this->action = $action;
        return $this;
    }

    public function log(string $description)
    {
        return ActivityLog::create([
            'account_id'    => $this->account_id,
            'causer_id'     => $this->causer?->id,
            'causer_type'   => $this->causer ? get_class($this->causer) : null,
            'subject_id'    => $this->subject?->id,
            'subject_type'  => $this->subject ? get_class($this->subject) : null,
            'action'        => $this->action ?? 'action',
            'description'   => $description,
            'properties'    => array_merge($this->properties, [
                'ip' => request()->ip(),
                'user_agent' => request()->header('User-Agent'),
            ]),
        ]);
    }
}
