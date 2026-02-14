<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property Collection telegram_bots
 * @property Collection allowed_bots
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function telegram_bots() : BelongsToMany {
        return $this->belongsToMany(TelegramBot::class);
    }

    public function roles() : BelongsToMany {
        return $this->belongsToMany(Role::class);
    }

    public function author() : HasOne {
        return $this->hasOne(Author::class);
    }

    public function hasRole($roleName)
    {
        return $this->roles->contains('name', $roleName);
    }

    public function hasAnyRole(...$roles)
    {
        return $this->roles->pluck('name')->intersect($roles)->count() > 0;
    }

    public function scopeFilter(Builder $query, array $filters) : Builder
    {
        return $query
            ->when(
                $filters['search'] ?? false,
                fn ($query, $value) => $query->where('name', 'like', '%'.$value.'%')->orWhere('email', 'like', '%'.$value.'%')
            )
            ->when(
                $filters['telegram_bot'] ?? false,
                fn ($query, $value) => $query->whereHas('telegram_bots', fn($q) => $q->where('telegram_bots.id', $value))
            )
            ->when(
                $filters['role'] ?? false,
                fn ($query, $value) => $query->whereHas('roles', fn($q) => $q->where('roles.id', $value))
            );
    }

    public function getAllowedBotsAttribute() : Collection {
        return $this->hasRole('supervisor') ? TelegramBot::all() : $this->telegram_bots;
    }

}
