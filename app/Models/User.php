<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'oauth_provider',
        'oauth_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'type' => 'integer',
    ];

    protected $appends = [
        'type_text'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getTypeTextAttribute(): string
    {
        return match ($this->attributes['type']) {
            1       => 'Seller',
            2       => 'Admin',
            default => 'Customer',
        };
    }

    /**
     * Scope a query to only include customers.
     *
     * Example: User::isCustomer()->get();
     * Example: User::isCustomer()->where(...)->get();
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsCustomer($query)
    {
        return $query->where('type', '=', 0);
    }

    /**
     * Scope a query to only include sellers.
     *
     * Example: User::isSeller()->get();
     * Example: User::isSeller()->where(...)->get();
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsSeller($query)
    {
        return $query->where('type', '=', 1);
    }

    /**
     * Scope a query to only include admins.
     *
     * Example: User::isAdmin()->get();
     * Example: User::isAdmin()->where(...)->get();
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsAdmin($query)
    {
        return $query->where('type', '=', 2);
    }
}