<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Writer extends Model
{
    use HasFactory;

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected $fillable = [
        'name',
        'slug',
        'photo',
        'genre_tag',
        'bio',
        'followers_count',
        'rating_average',
        'joined_year',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'followers_count' => 'integer',
            'rating_average' => 'decimal:1',
            'joined_year' => 'integer',
            'is_featured' => 'boolean',
        ];
    }

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'following')->withTimestamps();
    }
}
