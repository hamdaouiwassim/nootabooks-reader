<?php

namespace App\Services\Import;

use App\Models\Book;
use App\Models\Category;
use App\Models\Writer;
use App\Services\Search\ArabicTextNormalizer;
use Illuminate\Support\Collection;

/**
 * Bulk-creates books from an admin-uploaded CSV (see
 * Admin\BookImportController). Cover image and the book's PDF file can't
 * come from a CSV cell, so imported books are created without them and get
 * uploaded afterward through the normal edit page, same as any other book.
 */
class BookCsvImporter
{
    /**
     * Required headers, matched by trimmed name (not column position) so
     * sheet reordering — and the stray trailing space in the real sample
     * file's "عنوان " header — never break the mapping.
     */
    private const HEADERS = [
        'title' => 'عنوان',
        'description' => 'وصف الكتاب',
        'category' => 'التصنيف',
        'writer' => 'الكاتب',
        'writer_bio' => 'وصف الكاتب',
        'available' => 'الحالة',
        'writer_is_new' => 'كاتب جديد',
        'category_is_new' => 'تصنيف جديد',
        'writer_name_en' => 'الكاتب بالإنجليزيّة',
        'is_translated' => 'مترجم',
        'translator_name' => 'إسم المترجم',
        'title_en' => 'عنوان الكتاب بالإنجليزية',
    ];

    /**
     * @return Collection<int, array{row: int, title: string, outcome: string, message: ?string}>
     */
    public function import(string $path): Collection
    {
        $handle = fopen($path, 'r');

        if ($handle === false) {
            return collect([['row' => 0, 'title' => '', 'outcome' => 'error', 'message' => 'تعذّر فتح الملف']]);
        }

        $results = collect();

        try {
            $header = fgetcsv($handle, 0, ',', '"', '');

            if ($header === false) {
                return collect([['row' => 0, 'title' => '', 'outcome' => 'error', 'message' => 'الملف فارغ']]);
            }

            // Strip a leading UTF-8 BOM, common in Excel-exported CSVs.
            $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) $header[0]);

            $columnIndex = $this->mapColumns($header);
            $rowNumber = 1;

            while (($row = fgetcsv($handle, 0, ',', '"', '')) !== false) {
                $rowNumber++;

                $title = trim((string) ($row[$columnIndex['title'] ?? -1] ?? ''));

                if ($title === '') {
                    continue; // blank row (e.g. trailing empty rows) — not an error
                }

                $results->push($this->importRow($row, $columnIndex, $rowNumber, $title));
            }
        } finally {
            fclose($handle);
        }

        return $results;
    }

    /**
     * @return array<string, int>
     */
    private function mapColumns(array $header): array
    {
        $trimmed = array_map(fn ($cell) => trim((string) $cell), $header);

        $index = [];
        foreach (self::HEADERS as $key => $label) {
            $found = array_search($label, $trimmed, true);
            if ($found !== false) {
                $index[$key] = $found;
            }
        }

        return $index;
    }

    private function importRow(array $row, array $columnIndex, int $rowNumber, string $title): array
    {
        $cell = fn (string $key): string => trim((string) ($row[$columnIndex[$key] ?? -1] ?? ''));

        try {
            $normalizedTitle = ArabicTextNormalizer::normalize($title);

            if (Book::where('search_title', $normalizedTitle)->exists()) {
                return $this->result($rowNumber, $title, 'skipped', 'كتاب بنفس العنوان موجود مسبقًا');
            }

            $categoryName = $cell('category');
            if ($categoryName === '') {
                return $this->result($rowNumber, $title, 'error', 'التصنيف مفقود');
            }

            $category = $this->resolveCategory($categoryName, $cell('category_is_new') === '1');
            if ($category === null) {
                return $this->result($rowNumber, $title, 'error', "التصنيف غير موجود: {$categoryName}");
            }

            $writerId = null;
            $writerName = $cell('writer');
            if ($writerName !== '') {
                $writer = $this->resolveWriter(
                    $writerName,
                    $cell('writer_is_new') === '1',
                    $cell('writer_bio'),
                    $cell('writer_name_en')
                );

                if ($writer === null) {
                    return $this->result($rowNumber, $title, 'error', "المؤلف غير موجود: {$writerName}");
                }

                $writerId = $writer->id;
            }

            // '1' = published and available now; '0' or blank = "coming soon".
            $isComingSoon = $cell('available') !== '1';

            $isTranslated = $cell('is_translated') === '1';

            $book = Book::create([
                'title' => $title,
                'title_en' => $cell('title_en') ?: null,
                'description' => $cell('description') ?: null,
                'category_id' => $category->id,
                'writer_id' => $writerId,
                'translator_name' => $isTranslated ? ($cell('translator_name') ?: null) : null,
                'language' => $isTranslated ? 'مترجم إلى العربية' : 'العربية',
                'status' => 'published',
                'is_coming_soon' => $isComingSoon,
            ]);

            $book->categories()->sync([$category->id]);

            return $this->result($rowNumber, $title, 'created', null);
        } catch (\Throwable $e) {
            return $this->result($rowNumber, $title, 'error', 'فشل غير متوقع: '.$e->getMessage());
        }
    }

    private function resolveCategory(string $name, bool $isNew): ?Category
    {
        $existing = $this->findByNormalizedName(Category::all(), $name);

        if ($existing) {
            return $existing;
        }

        return $isNew ? Category::create(['name' => $name]) : null;
    }

    private function resolveWriter(string $name, bool $isNew, string $bio, string $nameEn): ?Writer
    {
        $existing = $this->findByNormalizedName(Writer::all(), $name);

        if ($existing) {
            return $existing;
        }

        if (! $isNew) {
            return null;
        }

        return Writer::create([
            'name' => $name,
            'bio' => $bio ?: null,
            'name_en' => $nameEn ?: null,
        ]);
    }

    /**
     * Arabic-normalization-aware name match (e.g. "إقتصاد" vs "اقتصاد") so a
     * spelling variant doesn't create a duplicate category/writer.
     *
     * @param  Collection<int, Category|Writer>  $models
     */
    private function findByNormalizedName(Collection $models, string $name): Category|Writer|null
    {
        $normalized = ArabicTextNormalizer::normalize($name);

        return $models->first(
            fn ($model) => ArabicTextNormalizer::normalize($model->name) === $normalized
        );
    }

    private function result(int $row, string $title, string $outcome, ?string $message): array
    {
        return ['row' => $row, 'title' => $title, 'outcome' => $outcome, 'message' => $message];
    }
}
