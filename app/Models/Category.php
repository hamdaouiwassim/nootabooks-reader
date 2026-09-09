<?php

namespace App\Models;

use App\Models\Concerns\GeneratesUniqueSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory, GeneratesUniqueSlug;

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
