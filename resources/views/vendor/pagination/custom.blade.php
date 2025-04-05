{{-- resources/views/vendor/pagination/custom.blade.php --}}

@if ($paginator->hasPages())
    <div class="pagination-one mt-20">
        <ul class="style-none d-flex align-items-center justify-content-center">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled"><span><i class="bi bi-arrow-left"></i></span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}"><i class="bi bi-arrow-left"></i></a></li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li>{{ $element }}</li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li><a href="#" class="active">{{ $page }}</a></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}">Last <i class="bi bi-arrow-right"></i></a></li>
            @else
                <li class="disabled"><span>Last <i class="bi bi-arrow-right"></i></span></li>
            @endif
        </ul>
    </div>
@endif