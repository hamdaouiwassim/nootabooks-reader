# Intelligent Arabic Search for Laravel

## 1. Feature Goal

Implement an intelligent, Arabic-aware search system for the Laravel application.

The search must understand common Arabic spelling variations and minor user input differences.

### Example

A user searches:

```text
الفيل الازرق
```

The application should return:

```text
الفيل الأزرق
```

The search should not require the user to type the exact Unicode representation stored in the database.

---

# 2. Important Context

This is a Laravel application using:

* Laravel
* Blade
* PHP
* MySQL
* Alpine.js where frontend interaction is required

The implementation must integrate with the application's **existing search functionality** rather than creating a completely separate search system without first inspecting the current codebase.

Before modifying anything:

1. Locate the current search controller/action.
2. Locate the current search Blade view/component.
3. Locate the model(s) being searched.
4. Inspect existing database indexes.
5. Determine how search results are currently ranked.
6. Determine whether Laravel Scout or another search engine is already installed.
7. Preserve existing search behavior unless there is a clear reason to improve it.

Do not assume the project structure.

---

# 3. Functional Requirements

## 3.1 Arabic normalization

Create a reusable Arabic text normalization mechanism.

The normalization should handle at least:

### Alef variations

Normalize:

```text
أ
إ
آ
ٱ
```

to:

```text
ا
```

Example:

```text
أحمد
احمد
إحمد
آحمد
```

should normalize consistently.

---

### Diacritics / Tashkeel

Remove Arabic diacritics:

```text
َ
ً
ُ
ٌ
ِ
ٍ
ْ
ّ
ٰ
```

Example:

```text
الفِيل الأزْرَق
```

should normalize approximately to:

```text
الفيل الازرق
```

---

### Alef Maqsura

Normalize:

```text
ى
```

to:

```text
ي
```

Example:

```text
على
علي
```

Important:

Do not blindly assume these are semantically identical. This normalization is for search matching, not for displaying or modifying the original title.

The original database value must remain unchanged.

---

### Tatweel

Remove:

```text
ـ
```

Example:

```text
الفــــيل
```

should match:

```text
الفيل
```

---

### Whitespace

Normalize repeated whitespace.

Example:

```text
الفيل    الأزرق
```

should become:

```text
الفيل الأزرق
```

Also trim leading/trailing whitespace.

---

# 4. Do NOT Modify Original Data

Never replace the original book title with its normalized version.

For example:

```text
title:
الفيل الأزرق
```

must remain exactly as stored.

Normalization is only for search purposes.

Use a dedicated searchable/normalized field when appropriate.

Recommended approach:

```text
title
search_title
```

Example:

```text
title       = الفيل الأزرق
search_title = الفيل الازرق
```

---

# 5. Database Design

Inspect the existing database before creating migrations.

If the application primarily searches books, consider adding:

```text
search_title
```

to the `books` table.

If the search also covers:

* authors
* descriptions
* categories
* tags

do not automatically create fields for everything.

First inspect the current search requirements and architecture.

Possible future structure:

```text
search_title
search_author
search_description
search_keywords
```

Only introduce fields that are actually needed.

---

# 6. Normalization Service

Do not duplicate Arabic normalization logic throughout controllers and models.

Create a reusable service/helper.

Preferred architecture:

```text
app/
    Services/
        Search/
            ArabicTextNormalizer.php
```

Example conceptual API:

```php
ArabicTextNormalizer::normalize($text);
```

The implementation must:

1. Accept UTF-8 Arabic text.
2. Remove Arabic diacritics.
3. Normalize Alef variants.
4. Normalize Alef Maqsura if appropriate for search.
5. Remove Tatweel.
6. Normalize whitespace.
7. Return a trimmed UTF-8 string.
8. Never modify the original database value.

Keep the service independently testable.

---

# 7. Search Query Normalization

Every user search query must pass through the same normalization process.

Example:

```text
User input:
الفيل الازرق

Normalized:
الفيل الازرق
```

Stored title:

```text
الفيل الأزرق
```

Stored searchable title:

```text
الفيل الازرق
```

The query should therefore match.

---

# 8. Searchable Fields

At minimum, support intelligent matching against book titles.

Potential search priority:

1. Exact original title
2. Exact normalized title
3. Title prefix
4. All search words present
5. Partial title match
6. Typo-tolerant match

Do not make all matching strategies have equal priority.

---

# 9. Result Ranking

Search results must be ranked by relevance.

For example, if the user searches:

```text
الفيل الازرق
```

a book with:

```text
الفيل الأزرق
```

should rank above a book whose description merely contains:

```text
الفيل
```

Recommended conceptual ranking:

```text
Exact title match
    ↓
Exact normalized title match
    ↓
Title starts with query
    ↓
All query words match title
    ↓
Partial title match
    ↓
Other searchable fields
```

Do not expose the internal ranking implementation to users.

---

# 10. Word-Based Search

The search should not depend exclusively on the complete query being a substring.

Example:

```text
الفيل الازرق
```

should find:

```text
الفيل الأزرق
```

If the user searches:

```text
ازرق الفيل
```

the system should be able to identify the same book when technically possible.

However, preserve relevance ranking so that naturally ordered title matches rank higher.

---

# 11. Typo Tolerance

The system should support reasonable typing mistakes.

Examples:

```text
الفيل الازرق
الفيل الأزرق
الفيل الازرك
الفيل الازرقق
```

The exact tolerance level should depend on the search engine.

Do NOT implement an expensive custom Levenshtein comparison against every database row.

For a small dataset, a Laravel/MySQL implementation may be acceptable initially.

For a growing catalog, use a proper search engine.

---

# 12. Search Engine Recommendation

Before implementing a custom fuzzy-search system, inspect the project dependencies.

If Laravel Scout is already installed, evaluate whether it can be used.

For a growing book catalog, prefer:

```text
Laravel Scout
+
Meilisearch
```

when appropriate.

Benefits:

* typo tolerance
* prefix search
* relevance ranking
* fast search
* filtering
* searchable attributes
* sortable attributes
* scalable architecture

Do not install Meilisearch automatically if the project does not need it.

First inspect:

```bash
composer.json
.env
config/
```

and existing search implementation.

---

# 13. Laravel Scout

If Scout + Meilisearch is selected:

Inspect whether the model already uses:

```php
use Laravel\Scout\Searchable;
```

If not, integrate it carefully.

Example conceptual model:

```php
class Book extends Model
{
    use Searchable;
}
```

Implement an appropriate:

```php
toSearchableArray()
```

Do not blindly index every database column.

Only index fields needed for search.

Example conceptual structure:

```php
public function toSearchableArray(): array
{
    return [
        'id' => $this->id,
        'title' => $this->title,
        'search_title' => ArabicTextNormalizer::normalize($this->title),
        'author' => $this->author?->name,
    ];
}
```

Adapt this to the actual project relationships.

---

# 14. Search UI

Inspect the current search UI before modifying it.

The user should be able to type naturally:

```text
الفيل الازرق
```

and receive relevant results without needing to understand normalization.

Do not visually alter the user's query unless there is a strong UX reason.

Search results should display the original title:

```text
الفيل الأزرق
```

NOT:

```text
الفيل الازرق
```

Normalization is an internal search mechanism.

---

# 15. Autocomplete

If the application already has autocomplete/search suggestions, apply the same normalization strategy.

Example:

User types:

```text
الفيل الاز
```

Suggestions may include:

```text
الفيل الأزرق
الفيل الأزرق 2
```

The suggestion must display the original title.

---

# 16. Search Analytics

Do not introduce analytics unless the application already has a suitable architecture.

If search analytics are later implemented, useful data includes:

```text
original_query
normalized_query
results_count
selected_result
created_at
```

Never store unnecessary personal information.

---

# 17. Performance Requirements

Avoid queries such as:

```sql
WHERE LOWER(REPLACE(REPLACE(...)))
```

against large datasets for every request.

Avoid applying multiple SQL functions to indexed columns because this can prevent efficient index usage.

Preferred options:

### Small dataset

A normalized database column:

```text
search_title
```

with appropriate indexing.

### Growing dataset

Use:

```text
Laravel Scout + Meilisearch
```

for full-text/fuzzy search.

---

# 18. Database Indexing

Inspect the actual query patterns before adding indexes.

For MySQL:

* Do not assume a normal B-tree index will optimize `%query%`.
* Prefix searches can benefit from appropriate indexes.
* Full substring/fuzzy search should generally use a search engine or FULLTEXT where appropriate.

Do not add indexes blindly.

---

# 19. Security

Search input is user-controlled.

Use Laravel's query builder/Eloquent bindings.

Never concatenate raw user input directly into SQL.

Bad:

```php
DB::raw("title LIKE '%{$query}%'");
```

Prefer parameter binding.

Example:

```php
->where('search_title', 'LIKE', "%{$query}%")
```

If `whereRaw()` is necessary, always use bindings.

Also consider:

* maximum query length
* empty queries
* excessive repeated requests
* rate limiting if the search endpoint is public
* pagination

---

# 20. Empty and Short Queries

Define behavior for:

```text
""
" "
"a"
"ف"
```

Do not execute expensive fuzzy searches for extremely short queries.

Recommended:

```text
empty query → no search
1 character → limited/prefix search or no search
2+ characters → normal search
```

Adjust based on the application's existing UX.

---

# 21. Pagination

Search results must remain paginated.

Do not load the entire books table into PHP to perform fuzzy matching.

Example:

```php
$books = Book::query()
    ->where(...)
    ->paginate(20);
```

If using Meilisearch/Scout, use its pagination capabilities.

Preserve existing pagination behavior where possible.

---

# 22. Testing

Create automated tests for Arabic normalization.

At minimum test:

```text
الفيل الأزرق
→ الفيل الازرق
```

```text
أحمد
→ احمد
```

```text
إيمان
→ ايمان
```

```text
آدم
→ ادم
```

```text
الفِيل الأزْرَق
→ الفيل الازرق
```

```text
الفــــيل
→ الفيل
```

```text
الفيل    الأزرق
→ الفيل الأزرق
```

Also test that original values are NOT modified.

---

# 23. Search Integration Tests

Create feature tests for actual search behavior.

Examples:

### Test 1

Database:

```text
الفيل الأزرق
```

Search:

```text
الفيل الازرق
```

Expected:

```text
الفيل الأزرق
```

---

### Test 2

Database:

```text
أرض زيكولا
```

Search:

```text
ارض زيكولا
```

Expected:

```text
أرض زيكولا
```

---

### Test 3

Database:

```text
موسم الهجرة إلى الشمال
```

Search:

```text
موسم الهجره الى الشمال
```

Expected to match according to the configured normalization rules.

---

# 24. Important Arabic Search Considerations

Do not treat Arabic normalization as a universal linguistic equivalence system.

For example:

```text
ة → ه
```

can create false matches in some contexts.

Therefore:

* Use normalization primarily to improve retrieval.
* Preserve original text.
* Test against the application's real book catalog.
* Avoid aggressive normalization that creates many unrelated matches.
* Keep normalization rules configurable if necessary.

Start with the safest transformations:

```text
remove tashkeel
normalize Alef variants
remove Tatweel
normalize whitespace
```

Only add more aggressive transformations after testing search quality.

---

# 25. Suggested Architecture

Preferred architecture:

```text
User
  │
  ▼
Search Input
  │
  ▼
Search Controller / Action
  │
  ▼
ArabicTextNormalizer
  │
  ▼
Search Service
  │
  ├── MySQL search
  │
  └── Meilisearch / Scout
  │
  ▼
Relevance Ranking
  │
  ▼
Paginated Results
  │
  ▼
Blade View
```

Keep search logic out of Blade templates.

Avoid putting normalization logic directly into controllers if it will be reused.

---

# 26. Recommended Laravel Structure

Adapt to the existing application architecture, but a possible structure is:

```text
app/
├── Http/
│   └── Controllers/
│       └── SearchController.php
│
├── Services/
│   └── Search/
│       ├── ArabicTextNormalizer.php
│       └── SearchService.php
│
├── Models/
│   └── Book.php
│
└── ...

database/
└── migrations/
    └── xxxx_add_search_title_to_books_table.php

tests/
├── Unit/
│   └── Services/
│       └── Search/
│           └── ArabicTextNormalizerTest.php
│
└── Feature/
    └── SearchTest.php
```

Do not create duplicate services if equivalent infrastructure already exists.

---

# 27. Implementation Workflow for Claude Code

Before writing code:

### Step 1 — Inspect

Find:

```bash
composer.json
package.json
.env
routes/web.php
routes/api.php
app/Models/
app/Http/Controllers/
resources/views/
database/migrations/
```

Then identify the existing search implementation.

---

### Step 2 — Understand

Determine:

* Which model is searched?
* Which fields are searched?
* Is Scout installed?
* Is Meilisearch already configured?
* How are results displayed?
* Is there autocomplete?
* How is pagination implemented?
* Are there existing search tests?

Do not modify anything before understanding this.

---

### Step 3 — Design

Choose the least invasive architecture that provides the required functionality.

Priority:

```text
Existing search architecture
        ↓
Arabic normalization
        ↓
Normalized searchable fields
        ↓
Relevance improvements
        ↓
Search engine if needed
```

Do not introduce Meilisearch merely because it is technically possible.

---

### Step 4 — Implement

Implement:

1. Arabic normalization service.
2. Search query normalization.
3. Normalized searchable title.
4. Relevant database migration if needed.
5. Search ranking.
6. Typo tolerance where supported.
7. Tests.
8. UI integration if required.

---

### Step 5 — Validate

Run:

```bash
php artisan test
```

and any relevant existing test suites.

If Scout/Meilisearch is introduced, verify indexing:

```bash
php artisan scout:import "App\Models\Book"
```

Only run commands appropriate to the actual installed/configured setup.

---

# 28. Acceptance Criteria

The feature is complete when:

* [ ] `الفيل الازرق` finds `الفيل الأزرق`.
* [ ] `الفيل الأزرق` still finds the book.
* [ ] Arabic diacritics do not prevent matching.
* [ ] Alef variations do not prevent matching.
* [ ] Original book titles remain unchanged.
* [ ] Search results are ordered by relevance.
* [ ] Partial searches work appropriately.
* [ ] Reasonable typos are handled if the selected search engine supports them.
* [ ] Empty searches are handled safely.
* [ ] Search input is protected against SQL injection.
* [ ] Search remains performant.
* [ ] Pagination continues to work.
* [ ] Existing search behavior is not unnecessarily broken.
* [ ] Automated tests cover normalization.
* [ ] Automated tests cover actual search behavior.
* [ ] The implementation follows the existing Laravel architecture.

---

# 29. Claude Code Rules

When implementing this feature:

### MUST

* Inspect the existing project first.
* Reuse existing services/components where possible.
* Preserve original Arabic titles.
* Use UTF-8-safe string operations.
* Use parameterized database queries.
* Write tests.
* Keep normalization reusable.
* Keep search logic maintainable.
* Consider performance before choosing SQL-based fuzzy matching.

### MUST NOT

* Replace original titles with normalized values.
* Hard-code normalization in multiple controllers.
* Load the entire books table and fuzzy-match in PHP.
* Use raw SQL with interpolated user input.
* Install unnecessary dependencies without justification.
* Introduce Meilisearch without checking the existing architecture first.
* Break existing pagination or filters.
* Change the visual design unnecessarily.
* Expose normalized/internal search fields to users.
* Assume all Arabic spelling variations are linguistically equivalent.

---

# 30. Final Implementation Report

After implementation, report:

### Files changed

List every modified/created file.

### Database changes

Explain migrations and indexes.

### Search behavior

Show examples such as:

```text
الفيل الازرق
        ↓
الفيل الأزرق
```

### Dependencies

List any newly installed packages/services.

### Tests

Report:

```text
Tests:
X passed
Y failed
```

### Performance

Explain the chosen search strategy and why it is appropriate for the current dataset.

### Future improvements

Only mention improvements that are actually relevant, such as:

* Meilisearch
* autocomplete
* search analytics
* synonyms
* Arabic stemming
* transliteration

Do not implement future improvements unless explicitly required.

---

# 31. Target Result

The final user experience should feel natural.

A user should be able to search:

```text
الفيل الازرق
```

without knowing that the correct title is stored as:

```text
الفيل الأزرق
```

and the application should intelligently return the correct book while preserving the original Arabic title everywhere it is displayed.
