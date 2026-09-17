<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SearchLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'query',
        'results_count',
    ];

    protected function casts(): array
    {
        return [
            'results_count' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Records one search hit — called from the discover/category search
     * inputs only on the first page of results, so paging through the same
     * query doesn't spam the log with duplicate rows.
     */
    public static function record(string $query, int $resultsCount): void
    {
        static::create([
            'user_id' => auth()->id(),
            'query' => $query,
            'results_count' => $resultsCount,
        ]);
    }

    /**
     * Most frequent search terms in the last $days days — case-insensitively
     * grouped so "كتاب" and "كتاب " (or differing case for latin terms)
     * collapse into one row.
     */
    public static function topQueries(int $days, int $limit = 10)
    {
        return static::query()
            ->where('created_at', '>=', now()->subDays($days))
            ->selectRaw('LOWER(TRIM(query)) as normalized_query, COUNT(*) as total')
            ->groupBy('normalized_query')
            ->orderByDesc('total')
            ->take($limit)
            ->get();
    }
}
