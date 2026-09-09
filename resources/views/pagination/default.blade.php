@if ($paginator->hasPages())
  <nav class="pagination" aria-label="Pagination">
    @if ($paginator->onFirstPage())
      <button class="page-btn" aria-label="previous" disabled><i class="fa-solid fa-chevron-right"></i></button>
    @else
      <a href="{{ $paginator->previousPageUrl() }}" class="page-btn" aria-label="previous"><i class="fa-solid fa-chevron-right"></i></a>
    @endif

    @foreach ($elements as $element)
      @if (is_string($element))
        <span class="page-btn" aria-hidden="true">{{ $element }}</span>
      @endif

      @if (is_array($element))
        @foreach ($element as $page => $url)
          @if ($page == $paginator->currentPage())
            <button class="page-btn active">{{ $page }}</button>
          @else
            <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
          @endif
        @endforeach
      @endif
    @endforeach

    @if ($paginator->hasMorePages())
      <a href="{{ $paginator->nextPageUrl() }}" class="page-btn" aria-label="next"><i class="fa-solid fa-chevron-left"></i></a>
    @else
      <button class="page-btn" aria-label="next" disabled><i class="fa-solid fa-chevron-left"></i></button>
    @endif
  </nav>
@endif
