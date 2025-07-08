<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'email_verified_at',
        'profile_url',
        'status',
        'confirmation_token',
        'token_expires_at',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function account()
    {
        return $this->hasOneThrough(
            Account::class,
            AccountUser::class,
            'user_id',    // Foreign key on account_users
            'id',         // Foreign key on accounts
            'id',         // Local key on users
            'account_id'  // Local key on account_users
        );
    }
    public function ownedAccount()
    {
        return $this->hasOne(Account::class, 'owner_id');
    }
    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_users');
    }

    public function notificationSettings()
    {
        return $this->hasMany(UserNotificationSetting::class);
    }

    public function notificationSettingFor(string $typeCode): ?UserNotificationSetting
    {
        return $this->notificationSettings()
            ->whereHas('notificationType', fn($q) => $q->where('code', $typeCode))
            ->first();
    }

    public function isNotificationEnabled(string $typeCode): bool
    {
        $setting = $this->notificationSettingFor($typeCode);
        return $setting ? $setting->enabled : true;
    }

      public function quizzs()
    {
        return $this->hasMany(Quizz::class,'user_id');
    }


    public function quizzResponses()
    {
        return $this->hasManyThrough(
            QuizzResponse::class, // Modèle final
            Quizz::class,         // Modèle intermédiaire
            'created_by',            // Clé étrangère dans la table quizz (indiquant le créateur)
            'quizz_id'            // Clé étrangère dans la table quizzresponse
        );
    }
}
