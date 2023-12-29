@if ($paginator->hasPages())
    @php $paginator->setPageName($pageName);
    @endphp

    <div class="bar pagination fs-14 margin-top-bottom-1">

        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <a aria-disabled="true" aria-label="@lang('pagination.previous')"
               class="bar-item disabled button transparent" href="#">
                <span aria-hidden="true">&laquo;</span>
            </a>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')"
               class="bar-item button transparent">
                &laquo;
            </a>
        @endif


        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <a class="bar-item disabled button transparent" aria-disabled="true" href="#">
                    <span>{{ $element }}</span>
                </a>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <a class="bar-item button primary active gray-button" aria-current="page"
                           href="#">
                            <span>{{ $page }}</span>
                        </a>
                    @else
                        <a href="{{ $url }}" class="bar-item button transparent">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')"
               class="bar-item button transparent">&raquo;</a>
        @else
            <a class="bar-item disabled button transparent" aria-disabled="true" aria-label="@lang('pagination.next')"
               href="#">
                <span aria-hidden="true">&raquo;</span>
            </a>
        @endif

    </div>

@endif
