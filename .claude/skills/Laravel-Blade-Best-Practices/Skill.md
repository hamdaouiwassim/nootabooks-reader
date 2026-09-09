# Laravel Blade Best Practices Skill

## Purpose

You are an expert Laravel and Laravel Blade developer.

Your goal is to build maintainable, scalable, secure, performant, and clean Laravel applications using:

- Laravel
- PHP
- Blade
- Tailwind CSS when appropriate
- Vite
- Alpine.js only when lightweight frontend interactivity is needed
- MySQL or PostgreSQL
- Laravel's native features before introducing external packages

Always prioritize Laravel conventions and maintainability.

---

# 1. Core Development Principles

When developing a Laravel Blade application:

1. Follow Laravel conventions.
2. Prefer simple solutions over unnecessary abstractions.
3. Keep controllers thin.
4. Put business logic in appropriate service/action classes.
5. Keep Blade views focused on presentation.
6. Avoid duplicating code.
7. Prefer Laravel native functionality before installing packages.
8. Write code that is easy for another developer to understand.
9. Optimize only when necessary, but avoid obvious performance problems.
10. Maintain clear separation between:
   - Presentation
   - HTTP handling
   - Validation
   - Business logic
   - Database access

Never create unnecessary layers or enterprise-style abstractions for simple features.

---

# 2. Recommended Project Architecture

Use the following architecture when appropriate:

```text
app/
├── Actions/
├── Console/
├── Enums/
├── Events/
├── Exceptions/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   ├── Front/
│   │   └── Api/
│   ├── Middleware/
│   └── Requests/
├── Jobs/
├── Listeners/
├── Models/
├── Notifications/
├── Observers/
├── Policies/
├── Providers/
├── Services/
├── Support/
└── View/
    └── Components/
```

Use folders only when they provide real organizational value.

Do not create empty architectural layers.

---

# 3. Controllers Best Practices

Controllers must remain thin.

Controllers should primarily:

1. Receive the request.
2. Delegate validation to Form Requests.
3. Call an Action or Service when business logic is complex.
4. Return a view or redirect.

Example:

```php
public function store(StoreBookRequest $request, CreateBookAction $action)
{
    $book = $action->handle($request->validated());

    return redirect()
        ->route('books.show', $book)
        ->with('success', __('Book created successfully.'));
}
```

Avoid:

```php
public function store(Request $request)
{
    // Validation
    // Database queries
    // File uploads
    // Business logic
    // Notifications
    // Events
    // Multiple model operations
}
```

Do not place large business workflows directly inside controllers.

---

# 4. Form Request Validation

Always use Form Request classes for non-trivial validation.

Example:

```text
app/Http/Requests/StoreBookRequest.php
```

Example:

```php
class StoreBookRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'cover' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
```

Controllers should use:

```php
$request->validated();
```

Avoid manually calling validation inside large controllers.

---

# 5. Business Logic

Use Actions or Services when business logic becomes complex.

Recommended:

```text
app/
├── Actions/
│   └── Books/
│       ├── CreateBookAction.php
│       ├── UpdateBookAction.php
│       └── DeleteBookAction.php
```

Example:

```php
class CreateBookAction
{
    public function handle(array $data): Book
    {
        return DB::transaction(function () use ($data) {
            return Book::create($data);
        });
    }
}
```

Use Actions for specific business operations.

Examples:

```text
CreateBookAction
PublishArticleAction
ApproveCommentAction
GenerateInvoiceAction
```

Avoid generic classes such as:

```text
BookManager
BookHelper
Utils
CommonService
GlobalHelper
```

unless they have a clearly defined responsibility.

---

# 6. Blade Views Architecture

Organize views clearly.

Recommended:

```text
resources/views/
├── layouts/
│   ├── app.blade.php
│   ├── admin.blade.php
│   └── guest.blade.php
│
├── components/
│   ├── buttons/
│   ├── forms/
│   ├── navigation/
│   └── ui/
│
├── pages/
│   ├── home/
│   ├── books/
│   ├── articles/
│   └── profile/
│
├── admin/
│   ├── dashboard/
│   ├── books/
│   └── users/
│
└── partials/
```

Use predictable naming.

Examples:

```text
index.blade.php
show.blade.php
create.blade.php
edit.blade.php
_form.blade.php
```

---

# 7. Blade Components

Prefer Blade components for reusable UI.

Examples:

```blade
<x-button>
    Save
</x-button>
```

```blade
<x-form.input
    name="title"
    label="Title"
    :value="old('title', $book->title)"
/>
```

Use components for:

- Buttons
- Inputs
- Modals
- Alerts
- Cards
- Badges
- Pagination wrappers
- Navigation
- Dropdowns
- Repeated UI patterns

Avoid creating a component for a UI fragment that is used only once.

---

# 8. Component Design

Components should be flexible but not overly generic.

Good:

```blade
<x-button variant="primary">
    Save
</x-button>
```

Better than:

```blade
<x-component
    type="button"
    style="primary"
    layout="horizontal"
    animation="fade"
    theme="default"
/>
```

Do not over-engineer component APIs.

Use sensible defaults.

---

# 9. Layout Best Practices

Use layouts for shared application structure.

Example:

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>
        @yield('title', config('app.name'))
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body>
    <x-navigation />

    <main>
        {{ $slot ?? '' }}

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
```

Do not duplicate headers, footers, scripts, and meta tags across multiple views.

---

# 10. Blade Logic Rules

Keep Blade files focused on presentation.

Avoid complex business logic:

```blade
@if($user->orders()->where(...)->count() > 5)
```

Instead, prepare data before rendering:

```php
return view('dashboard', [
    'hasPremiumOrders' => $user->hasPremiumOrders(),
]);
```

Blade should handle:

- Display logic
- Conditional rendering
- Loops
- Formatting
- Component composition

Avoid:

- Complex database queries
- Heavy calculations
- Business rules
- Service calls

---

# 11. Avoid Database Queries in Blade

Never write:

```blade
@foreach(App\Models\Book::latest()->get() as $book)
```

Never access relationships without considering eager loading.

Bad:

```blade
@foreach($books as $book)
    {{ $book->author->name }}
@endforeach
```

if the relationship was not eager loaded.

Controller:

```php
$books = Book::with('author')->paginate(20);
```

Then:

```blade
@foreach($books as $book)
    {{ $book->author->name }}
@endforeach
```

Always consider N+1 queries.

---

# 12. Eloquent Best Practices

Prefer relationships.

Good:

```php
$book->author;
```

Use eager loading:

```php
Book::with([
    'author',
    'categories',
])->get();
```

Load only required relationships.

Avoid:

```php
Book::with([
    'author',
    'categories',
    'comments',
    'reviews',
    'favorites',
])->get();
```

unless all relationships are actually required.

Use:

```php
select()
```

when large tables contain unnecessary columns.

---

# 13. Route Model Binding

Prefer route model binding.

Good:

```php
Route::get('/books/{book}', [BookController::class, 'show']);
```

Controller:

```php
public function show(Book $book)
{
    return view('books.show', compact('book'));
}
```

Avoid manually retrieving the model unless custom behavior is required.

---

# 14. Named Routes

Always use named routes.

Good:

```php
route('books.show', $book)
```

Avoid:

```blade
<a href="/books/{{ $book->id }}">
```

Recommended route names:

```text
books.index
books.create
books.store
books.show
books.edit
books.update
books.destroy
```

For admin:

```text
admin.dashboard
admin.books.index
admin.books.create
admin.books.edit
```

---

# 15. Authorization

Never rely only on hidden buttons.

Bad:

```blade
@if(auth()->user()->is_admin)
    <button>Delete</button>
@endif
```

Authorization must also be enforced on the backend.

Use:

- Policies
- Gates
- Middleware

Example:

```php
$this->authorize('update', $book);
```

Blade:

```blade
@can('update', $book)
    <a href="{{ route('books.edit', $book) }}">
        Edit
    </a>
@endcan
```

---

# 16. CSRF Protection

All forms performing state-changing operations must include CSRF protection.

```blade
<form method="POST">
    @csrf
</form>
```

For PUT/PATCH:

```blade
@method('PUT')
```

For DELETE:

```blade
@method('DELETE')
```

Never remove CSRF protection without a strong architectural reason.

---

# 17. Form Best Practices

Use:

```blade
old('title', $book->title ?? '')
```

Display validation errors:

```blade
@error('title')
    <p class="text-sm text-red-500">
        {{ $message }}
    </p>
@enderror
```

For reusable forms, prefer components.

Example:

```blade
<x-form.input
    name="title"
    label="Title"
    :value="old('title', $book->title ?? '')"
/>
```

---

# 18. Escaping and XSS Protection

Use normal Blade output by default:

```blade
{{ $book->title }}
```

Only use raw HTML:

```blade
{!! $content !!}
```

when the content has been properly sanitized and is trusted.

Never output user-generated HTML directly without sanitization.

---

# 19. Flash Messages

Use session flash messages consistently.

Controller:

```php
return back()->with(
    'success',
    __('Changes saved successfully.')
);
```

Layout:

```blade
@if(session('success'))
    <x-alert type="success">
        {{ session('success') }}
    </x-alert>
@endif
```

Standardize alert behavior across the application.

---

# 20. Pagination

Always paginate large collections.

Good:

```php
Book::latest()->paginate(20);
```

Avoid:

```php
Book::all();
```

for potentially large datasets.

Blade:

```blade
{{ $books->links() }}
```

---

# 21. File Uploads

Validate uploaded files.

Example:

```php
'cover' => [
    'nullable',
    'image',
    'max:2048',
]
```

Store files using Laravel storage:

```php
$path = $request
    ->file('cover')
    ->store('books/covers', 'public');
```

Avoid manually constructing filesystem paths.

Do not trust original filenames.

---

# 22. Database Transactions

Use transactions when multiple database operations must succeed together.

Example:

```php
DB::transaction(function () use ($data) {
    $book = Book::create($data);

    $book->categories()->sync(
        $data['categories']
    );
});
```

Do not use transactions for simple single-record operations unless necessary.

---

# 23. Events and Jobs

Use events when something happens that other parts of the application may react to.

Examples:

```text
BookPublished
UserRegistered
OrderCompleted
```

Use jobs for slow tasks:

- Sending emails
- Image processing
- PDF generation
- AI processing
- Notifications
- Large imports

Avoid making users wait for long-running operations.

---

# 24. Blade and Alpine.js

Use Alpine.js for lightweight interactions.

Good use cases:

- Dropdowns
- Modals
- Tabs
- Toggles
- Simple dynamic forms

Avoid using Alpine.js to build a large SPA inside Blade.

If frontend state becomes highly complex, evaluate whether Livewire, Inertia, or a separate frontend application is more appropriate.

---

# 25. JavaScript Architecture

Do not place large JavaScript blocks directly inside Blade views.

Avoid:

```blade
<script>
    // 500 lines of JavaScript
</script>
```

Instead:

```text
resources/js/
├── app.js
├── components/
├── pages/
└── modules/
```

Load page-specific JavaScript only when needed.

Use Vite for asset management.

---

# 26. CSS Best Practices

Prefer reusable utility classes when using Tailwind CSS.

Avoid large amounts of repeated class strings when they represent reusable UI patterns.

Use Blade components for repeated UI.

Example:

```blade
<x-button variant="primary">
    Save
</x-button>
```

instead of repeatedly copying:

```blade
<button class="px-4 py-2 rounded-lg ...">
```

across dozens of views.

Maintain consistent:

- Spacing
- Typography
- Border radius
- Shadows
- Colors
- Breakpoints

---

# 27. SEO Best Practices for Blade Applications

Every public page should have:

- Unique title
- Meta description
- Canonical URL when appropriate
- Open Graph metadata
- Structured data when useful

Use a reusable SEO component.

Example:

```blade
<x-seo
    :title="$book->title"
    :description="$book->short_description"
/>
```

Do not hardcode SEO metadata across multiple pages.

---

# 28. Localization

Use translation strings for user-facing text.

Good:

```php
__('Save changes')
```

Avoid hardcoding text throughout the application when multilingual support exists or may exist.

Organize translations clearly:

```text
lang/
├── en/
├── ar/
└── fr/
```

---

# 29. Performance Best Practices

Always consider:

### Database

- Eager loading
- Pagination
- Indexing
- Selecting only necessary columns

### Views

Use caching when appropriate.

Example:

```php
Cache::remember(
    'popular-books',
    now()->addHour(),
    fn () => Book::popular()->take(10)->get()
);
```

### Assets

Use:

- Vite
- Optimized images
- Lazy loading

Example:

```blade
<img
    src="{{ $book->cover_url }}"
    loading="lazy"
    alt="{{ $book->title }}"
>
```

---

# 30. Accessibility

Always consider accessibility.

Images must have meaningful alternative text:

```blade
<img
    src="{{ $book->cover_url }}"
    alt="{{ $book->title }}"
>
```

Forms should have labels.

Buttons should have accessible names.

Avoid clickable `<div>` elements when a `<button>` or `<a>` is appropriate.

Maintain sufficient color contrast.

---

# 31. Error Handling

Do not expose internal errors to users.

Use Laravel exception handling.

Log meaningful context.

Bad:

```php
catch (Exception $e) {
    return back()->with('error', $e->getMessage());
}
```

Better:

```php
catch (Exception $e) {
    report($e);

    return back()->with(
        'error',
        __('Something went wrong. Please try again.')
    );
}
```

---

# 32. Database Migration Rules

Migrations should be:

- Clear
- Reversible
- Properly indexed

Example:

```php
Schema::create('books', function (Blueprint $table) {
    $table->id();

    $table->foreignId('author_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->string('title');

    $table->string('slug')
        ->unique();

    $table->timestamps();

    $table->index('created_at');
});
```

Add indexes based on actual query patterns.

Do not blindly index every column.

---

# 33. Model Best Practices

Models should contain:

- Relationships
- Casts
- Scopes
- Small domain-specific helpers

Example:

```php
class Book extends Model
{
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_published' => 'boolean',
        ];
    }

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublished($query)
    {
        return $query
            ->where('is_published', true);
    }
}
```

Avoid putting massive business workflows inside models.

---

# 34. Avoid These Anti-Patterns

Never:

- Query the database directly from Blade.
- Put large business logic inside controllers.
- Put hundreds of lines of JavaScript inside Blade.
- Use raw SQL without a reason.
- Use `Model::all()` for large datasets.
- Create unnecessary repositories.
- Create unnecessary service layers.
- Duplicate UI.
- Duplicate validation rules.
- Ignore authorization.
- Ignore N+1 queries.
- Disable CSRF protection unnecessarily.
- Output untrusted HTML using `{!! !!}`.
- Hardcode URLs when named routes exist.
- Hardcode environment values in views.
- Expose exception messages to users in production.

---

# 35. Code Style

Generate code that is:

- Readable
- Explicit
- Consistent
- Laravel idiomatic
- Easy to maintain

Prefer:

```php
if ($book->isPublished()) {
    // ...
}
```

over deeply nested conditions.

Use early returns when appropriate.

Avoid unnecessary comments that merely describe obvious code.

Comments should explain:

- Why something exists.
- Non-obvious business decisions.
- Important constraints.

---

# 36. When Generating Blade Code

When generating a Blade page:

1. Determine whether a layout already exists.
2. Reuse existing Blade components.
3. Avoid duplicating navigation or layout markup.
4. Use named routes.
5. Use CSRF protection.
6. Use validation error handling.
7. Escape user-generated content.
8. Ensure responsive behavior.
9. Consider accessibility.
10. Keep logic minimal.

---

# 37. When Modifying Existing Laravel Projects

Before suggesting major architectural changes:

1. Understand the existing structure.
2. Preserve existing conventions when reasonable.
3. Avoid unnecessary refactoring.
4. Do not introduce a new architecture unless it solves a real problem.
5. Prefer incremental improvements.

Never rewrite an entire working feature merely to make it "cleaner".

---

# 38. Code Generation Requirements

When generating Laravel code:

- Generate complete code when requested.
- Include relevant imports.
- Use the project's Laravel version conventions.
- Follow existing naming conventions.
- Avoid pseudo-code unless explicitly requested.
- Do not invent database fields that are not required.
- Explain important assumptions.
- Mention when migrations or configuration changes are required.

---

# 39. Default Decision Framework

When multiple implementation approaches are possible, prefer:

1. Laravel native feature.
2. Simple Blade component.
3. Form Request.
4. Policy.
5. Eloquent relationship.
6. Action or Service for complex workflows.
7. Event/Job for asynchronous or decoupled work.
8. External package only when Laravel does not provide a suitable solution.

---

# Final Principle

The application should feel like a well-built Laravel application.

Prioritize:

> Simplicity over unnecessary abstraction.  
> Convention over custom architecture.  
> Reusability over duplication.  
> Security over convenience.  
> Performance without premature optimization.  
> Maintainability over clever code.

When uncertain, choose the simplest Laravel-native solution that remains maintainable as the application grows.