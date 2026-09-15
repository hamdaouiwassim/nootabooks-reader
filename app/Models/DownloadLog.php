<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class DownloadLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'user_id',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Real download counts per day for the last $days days — site-wide, or
     * scoped to one book when $bookId is given — including days with zero
     * downloads so a chart's x-axis stays a continuous timeline.
     */
    public static function perDay(int $days, ?int $bookId = null): array
    {
        return static::perDayRange(now()->subDays($days - 1), now(), $bookId);
    }

    /**
     * Same as perDay(), but for an explicit [from, to] date range (inclusive)
     * instead of "the last N days" — used for custom period selection.
     */
    public static function perDayRange(Carbon $from, Carbon $to, ?int $bookId = null): array
    {
        $from = $from->copy()->startOfDay();
        $to = $to->copy()->endOfDay();

        $counts = static::query()
            ->when($bookId, fn ($query) => $query->where('book_id', $bookId))
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('day')
            ->pluck('total', 'day');

        $result = [];
        $cursor = $from->copy();
        while ($cursor->lte($to)) {
            $key = $cursor->format('Y-m-d');
            $result[] = [
                'label' => $cursor->format('j'),
                'fullLabel' => $cursor->translatedFormat('j M'),
                'count' => (int) ($counts[$key] ?? 0),
            ];
            $cursor->addDay();
        }

        return $result;
    }

    /**
     * Real download counts per hour (0-23) for one calendar day (defaults to
     * today) — site-wide, or scoped to one book when $bookId is given.
     */
    public static function perHour(?int $bookId = null, ?Carbon $date = null): array
    {
        $date = ($date ?? now())->copy();
        $start = $date->copy()->startOfDay();
        $end = $date->copy()->endOfDay();

        $counts = static::query()
            ->when($bookId, fn ($query) => $query->where('book_id', $bookId))
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as total')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('hour')
            ->pluck('total', 'hour');

        $result = [];
        for ($hour = 0; $hour <= 23; $hour++) {
            $result[] = [
                'label' => str_pad((string) $hour, 2, '0', STR_PAD_LEFT),
                'fullLabel' => str_pad((string) $hour, 2, '0', STR_PAD_LEFT).':00',
                'count' => (int) ($counts[$hour] ?? 0),
            ];
        }

        return $result;
    }
}
