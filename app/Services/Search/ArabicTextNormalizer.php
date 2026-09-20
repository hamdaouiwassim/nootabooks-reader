<?php

namespace App\Services\Search;

class ArabicTextNormalizer
{
    /**
     * Arabic diacritics (tashkeel) — fatha, tanwin fath, damma, tanwin damm,
     * kasra, tanwin kasr, sukun, shadda, and the superscript alef (dagger
     * alef, U+0670) some Qur'anic-style text uses.
     */
    private const DIACRITICS = "\u{064B}\u{064C}\u{064D}\u{064E}\u{064F}\u{0650}\u{0651}\u{0652}\u{0670}";

    private const TATWEEL = "\u{0640}";

    /**
     * Collapses common Arabic spelling variations into one canonical form so
     * search can match "الفيل الازرق" against a stored "الفيل الأزرق" —
     * never used to alter what's actually stored/displayed, only to build a
     * separate matching key (see Book::search_title).
     */
    public static function normalize(?string $text): string
    {
        if ($text === null || $text === '') {
            return '';
        }

        // Alef variants (hamza-above/below, madda, wasla) -> bare alef.
        $normalized = preg_replace('/[أإآٱ]/u', 'ا', $text);

        // Alef maqsura -> ya (search matching only; never applied to the
        // stored title, since ى and ي aren't always interchangeable).
        $normalized = str_replace('ى', 'ي', $normalized);

        // Diacritics and tatweel are pure decoration for matching purposes.
        $normalized = preg_replace('/['.self::DIACRITICS.self::TATWEEL.']/u', '', $normalized);

        // Collapse repeated whitespace, then trim.
        $normalized = preg_replace('/\s+/u', ' ', $normalized);

        return trim($normalized);
    }

    /**
     * Splits an already-normalized query into individual search words,
     * dropping empty tokens — used for order-independent "all words present"
     * matching (see Book::scopeMatchingTitle()). Capped so a huge pasted
     * string can't blow up the query with dozens of ANDed LIKE clauses.
     *
     * @return array<int, string>
     */
    public static function words(string $normalized, int $limit = 6): array
    {
        $words = preg_split('/\s+/u', trim($normalized), -1, PREG_SPLIT_NO_EMPTY);

        return array_slice($words ?: [], 0, $limit);
    }
}
