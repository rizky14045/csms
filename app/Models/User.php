<?php

namespace App\Models;

use App\Models\BujpProfile;
use App\Models\UserProfile;
use App\Models\AdminProfile;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    protected $dates = ['deleted_at'];
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'session_expired_date' => 'datetime',
    ];

    public function adminProfile()
    {
        return $this->hasOne(AdminProfile::class, 'user_id', 'id');
    }
    public function userProfile()
    {
        return $this->hasOne(UserProfile::class, 'user_id', 'id');
    }
    public function bujpProfile()
    {
        return $this->hasOne(BujpProfile::class, 'user_id', 'id');
    }
    public function vendor()
    {
        // A vendor/BUJP user can have multiple contract rows over time
        // (a new one is added each time a contract is renewed/created).
        // Always resolve to the most recently created contract.
        return $this->hasOne(Vendor::class, 'user_id', 'id')->latestOfMany();
    }

    public function vendors()
    {
        // All contract rows for this vendor/BUJP user, newest first.
        return $this->hasMany(Vendor::class, 'user_id', 'id')->orderByDesc('created_at');
    }

    /**
     * Alamat email untuk pengiriman: user LDAP memakai `ldap_email` (kolom `email`-nya berisi
     * username/NID); user biasa memakai `email`.
     */
    public function getMailAddressAttribute()
    {
        if ((int) $this->login_type === 1) {
            if ($this->ldap_email) {
                return $this->ldap_email;
            }

            return filter_var($this->email, FILTER_VALIDATE_EMAIL) ? $this->email : null;
        }

        return $this->email;
    }

    public function routeNotificationForMail($notification = null)
    {
        return $this->mail_address;
    }
}
