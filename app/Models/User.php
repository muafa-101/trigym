<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'photo',
        'package_id',
        'expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'expires_at' => 'date',
        ];
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(GymClass::class, 'enrollments', 'user_id', 'class_id');
    }

    public function checkins(): HasMany
    {
        return $this->hasMany(Checkin::class);
    }

    public function memberCode(): string
    {
        return 'M-' . str_pad((string) $this->id, 5, '0', STR_PAD_LEFT);
    }

    public function expiryLabel(): string
    {
        return $this->expires_at ? $this->expires_at->format('m/Y') : '-';
    }

    public function daysLeft(): int
    {
        if (!$this->expires_at) {
            return 0;
        }

        $now = Carbon::today();
        $diff = $now->diff($this->expires_at);

        return $diff->invert === 1 ? 0 : (int) $diff->days;
    }
}
