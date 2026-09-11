<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'bio',
        'location',
        'points',
        'is_public',
        'show_reading_activity',
        'allow_messages',
        'two_factor_enabled',
        'notification_preferences',
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
            'points' => 'integer',
            'is_public' => 'boolean',
            'show_reading_activity' => 'boolean',
            'allow_messages' => 'boolean',
            'two_factor_enabled' => 'boolean',
            'notification_preferences' => 'array',
        ];
    }

    public function followedWriters(): BelongsToMany
    {
        return $this->belongsToMany(Writer::class, 'following')->withTimestamps();
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function discussions(): HasMany
    {
        return $this->hasMany(Discussion::class);
    }

    public function likedDiscussions(): BelongsToMany
    {
        return $this->belongsToMany(Discussion::class, 'discussion_likes')->withTimestamps();
    }
}
