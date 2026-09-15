<?php

namespace App\Models;

use App\Models\Concerns\FlushesAppCache;
use App\Models\Concerns\GeneratesUniqueSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    /**
     * A book can belong to more than one category (see book_category
     * migration) — this is now the full many-to-many set, not just books
     * whose primary category is this one.
     */
    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'book_category');
    }
}
