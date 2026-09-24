<?php

namespace App\Services\Search;

use App\Models\Book;
use Illuminate\Support\Collection;

/**
 * Book::scopeMatchingTitle() is AND-only — every query word must appear in
 * the same search_title, or nothing matches at all. This surfaces the
 * closest real titles for a zero-result search instead of a dead end, by
 * scoring on word overlap + edit distance rather than requiring all words.
 */
class DidYouMeanSuggester
{
    /**
     * @return Collection<int, Book>
     */
    public function suggest(string $search, int $limit = 3): Collection
    {
        $normalized = ArabicTextNormalizer::normalize($search);
        $words = array_values(array_filter(
            ArabicTextNormalizer::words($normalized),
            fn (string $word) => mb_strlen($word) >= 2
        ));

        if ($words === []) {
            return collect();
        }

        // Fetched per word (not one combined OR query) so a common word
        // can't fill the whole candidate cap and crowd out a rarer word's
        // correct match before it's ever scored.
        $candidates = collect();
        foreach ($words as $word) {
            $candidates = $candidates->concat(
                Book::published()
                    ->where('search_title', 'like', '%'.$word.'%')
                    ->limit(10)
                    ->get(['id', 'title', 'slug', 'search_title'])
            );
        }
        $candidates = $candidates->unique('id')->take(50);

        return $candidates
            ->map(function (Book $book) use ($words, $normalized) {
                $overlap = collect($words)->filter(
                    fn (string $word) => str_contains($book->search_title, $word)
                )->count();

                return [
                    'book' => $book,
                    'overlap' => $overlap,
                    'distance' => self::multibyteLevenshtein($normalized, $book->search_title),
                ];
            })
            ->sortBy([
                ['overlap', 'desc'],
                ['distance', 'asc'],
            ])
            ->take($limit)
            ->pluck('book')
            ->values();
    }

    /**
     * PHP's built-in levenshtein() operates on bytes, not characters — most
     * Arabic letters are 2 UTF-8 bytes each, and since some letter pairs
     * share a lead byte while others don't, byte-wise distance doesn't just
     * inflate uniformly, it skews relative ranking between candidates.
     */
    private static function multibyteLevenshtein(string $a, string $b): int
    {
        $a = mb_str_split($a);
        $b = mb_str_split($b);

        $lenA = count($a);
        $lenB = count($b);

        $previousRow = range(0, $lenB);

        for ($i = 1; $i <= $lenA; $i++) {
            $currentRow = [$i];

            for ($j = 1; $j <= $lenB; $j++) {
                $cost = $a[$i - 1] === $b[$j - 1] ? 0 : 1;

                $currentRow[$j] = min(
                    $previousRow[$j] + 1,
                    $currentRow[$j - 1] + 1,
                    $previousRow[$j - 1] + $cost
                );
            }

            $previousRow = $currentRow;
        }

        return $previousRow[$lenB];
    }
}
