<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($staticPages as $page)
    <url>
        <loc>{{ $page['url'] }}</loc>
        <priority>{{ $page['priority'] }}</priority>
    </url>
@endforeach
@foreach ($books as $book)
    <url>
        <loc>{{ route('book-details', $book->slug) }}</loc>
        <lastmod>{{ $book->updated_at->toAtomString() }}</lastmod>
        <priority>0.9</priority>
    </url>
@endforeach
@foreach ($writers as $writer)
    <url>
        <loc>{{ route('writer-details', $writer->slug) }}</loc>
        <lastmod>{{ $writer->updated_at->toAtomString() }}</lastmod>
        <priority>0.6</priority>
    </url>
@endforeach
@foreach ($categories as $category)
    <url>
        <loc>{{ route('category-details', $category->slug) }}</loc>
        <lastmod>{{ $category->updated_at->toAtomString() }}</lastmod>
        <priority>0.6</priority>
    </url>
@endforeach
</urlset>
