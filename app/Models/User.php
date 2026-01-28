<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\UserLogin;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'two_factor_code',
        'two_factor_expires_at',
        'status',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_code',
    ];

    /**
     * Attribute casting.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'     => 'datetime',
            'two_factor_expires_at' => 'datetime',
            'password'              => 'hashed',
            'status'                => 'boolean',
        ];
    }

    /**
     * Override default password reset notification
     */
    public function sendPasswordResetNotification($token): void
    {
        // 🔐 garante que apenas usuários ativos recebam o reset
        if (! $this->status) {
            return;
        }

        $this->notify(new ResetPasswordNotification($token));
    }

    public function logins()
    {
        return $this->hasMany(UserLogin::class);
    }

    public function lastLogin()
    {
        return $this->hasOne(UserLogin::class)->latestOfMany('logged_in_at');
    }


    public function isSuspiciousLogin(?string $ip = null): bool
    {
        $lastLogin = $this->logins()
            ->whereNotNull('logged_in_at')
            ->latest('logged_in_at')
            ->skip(1) // login anterior
            ->first();

        if (! $lastLogin) {
            return false;
        }

        $currentIp = $ip ?? request()->ip();

        return $lastLogin->ip_address !== $currentIp;
    }

    
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasPermission(string $permission): bool
    {
        return $this->roles()
            ->whereHas('permissions', function ($q) use ($permission) {
                $q->where('name', $permission);
            })
            ->exists();
    }



}
