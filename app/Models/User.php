<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Traits\GlobalScopes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, GlobalScopes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nick',
        'password',
        'first_name',
        'last_name',
        'dni',
        'phone',
        'address',
        'role',
        'email',
        'state',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'abilities' => 'json',
    ];

    /**
     * The attributes that should add.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'full_name',
        'role_name',
        'state_name',
    ];

    public function isSuper(): bool
    {
        return $this->attributes['role'] === 1;
    }

    public function isAdmin(): bool
    {
        return in_array($this->attributes['role'], [1, 2]);
    }

    protected function firstName(): Attribute
    {
        return Attribute::make(
            get: fn($value) => ucwords(strtolower($value)),
            set: fn($value) => strtolower($value),
        );
    }

    protected function lastName(): Attribute
    {
        return Attribute::make(
            get: fn($value) => ucwords(strtolower($value)),
            set: fn($value) => strtolower($value),
        );
    }

    protected function password(): Attribute
    {
        return Attribute::set(fn($value) => bcrypt($value));
    }

    protected function fullName(): Attribute
    {
        return Attribute::get(
            fn($value, $attributes) => ucwords(
                strtolower(
                    ($attributes['first_name'] ?? null).' '.($attributes['last_name'] ?? null)
                )
            )
        );
    }

    protected function stateName(): Attribute
    {
        return Attribute::get(fn($value, $attributes) => $attributes['state'] === 1 ? 'Activo' : 'Inactivo');
    }

    protected function roleName(): Attribute
    {
        return Attribute::get(function($value, $attributes) {
            return match ($attributes['role']) {
                1 => 'Super',
                2 => 'Administrador',
                3 => 'Usuario',
                default => null,
            };
        });
    }

    public function scopeSearch($query, $value)
    {
        return $query->whereLike(DB::raw("concat_ws(' ', `first_name`, `last_name`)"), $value);
    }
}
