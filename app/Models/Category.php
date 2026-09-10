<?php

namespace App\Models;

use App\Models\Concerns\FlushesAppCache;
use App\Models\Concerns\GeneratesUniqueSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory, GeneratesUniqueSlug, FlushesAppCache;

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'color',
    ];

    protected function slugSource(): string
    {
        return 'name';
    }

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
