@if ($paginator->hasPages())
  @if ($paginator->onFirstPage())
    <button class="admin-page-btn" disabled><i class="fa-solid fa-chevron-right"></i></button>
  @else
    <a href="{{ $paginator->previousPageUrl() }}" class="admin-page-btn"><i class="fa-solid fa-chevron-right"></i></a>
  @endif

  @foreach ($elements as $element)
    @if (is_string($element))
      <span class="admin-page-btn" disabled>{{ $element }}</span>
    @endif

    @if (is_array($element))
      @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
          <button class="admin-page-btn active">{{ $page }}</button>
        @else
          <a href="{{ $url }}" class="admin-page-btn">{{ $page }}</a>
        @endif
      @endforeach
    @endif
  @endforeach

  @if ($paginator->hasMorePages())
    <a href="{{ $paginator->nextPageUrl() }}" class="admin-page-btn"><i class="fa-solid fa-chevron-left"></i></a>
  @else
    <button class="admin-page-btn" disabled><i class="fa-solid fa-chevron-left"></i></button>
  @endif
@endif
