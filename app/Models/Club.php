<?php

namespace App\Models;

use App\Models\Concerns\GeneratesUniqueSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Club extends Model
{
    use HasFactory, GeneratesUniqueSlug;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'rules',
        'created_by',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected function slugSource(): string
    {
        return 'name';
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'club_user')->withPivot('role')->withTimestamps();
    }

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'club_books')->withPivot(['started_at', 'finished_at'])->withTimestamps();
    }

    public function discussions(): HasMany
    {
        return $this->hasMany(Discussion::class);
    }

    public function currentBook(): ?Book
    {
        return $this->books()
            ->wherePivotNull('finished_at')
            ->orderByPivot('started_at', 'desc')
            ->first();
    }

    public function pastBooks(): BelongsToMany
    {
        return $this->books()->wherePivotNotNull('finished_at')->orderByPivot('finished_at', 'desc');
    }
}
