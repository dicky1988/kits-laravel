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
        'name',
        'nip','nip_lama',
        'username',
        'email',
        'password',
        'api_token',
        'api_token_web',
        'api_token_smile',
        'jwt_token',
        'active_role_id'
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

    /**
     * Disable remember token if not needed
     * (Optional – Breeze default uses it)
     */
    protected $rememberTokenName = 'remember_token';

    /**
     * Get the identifier used for authentication.
     * Breeze default: email → diganti username
     */
    public function getAuthIdentifierName()
    {
        return 'username';
    }

    public function activeRole()
    {
        return $this->belongsTo(
            \Spatie\Permission\Models\Role::class,
            'active_role_id'
        );
    }
}
