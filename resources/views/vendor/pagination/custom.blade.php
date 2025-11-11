@if ($paginator->hasPages())
    <nav class="pagination-nav" role="navigation" aria-label="Pagination">
        <ul class="pagination-nav__list">
            {{-- vorige --}}
            <li>
                @if ($paginator->onFirstPage())
                    <span class="pagination-nav__link is-disabled">Vorige</span>
                @else
                    <a class="pagination-nav__link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        Vorige
                    </a>
                @endif
            </li>

            @foreach ($elements as $element)
        
                @if (is_string($element))
                    <li><span class="pagination-nav__ellipsis">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li>
                            @if ($page == $paginator->currentPage())
                                <span class="pagination-nav__link is-active">{{ $page }}</span>
                            @else
                                <a class="pagination-nav__link" href="{{ $url }}">{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            {{-- volgende --}}
            <li>
                @if ($paginator->hasMorePages())
                    <a class="pagination-nav__link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        Volgende
                    </a>
                @else
                    <span class="pagination-nav__link is-disabled">Volgende</span>
                @endif
            </li>
        </ul>
    </nav>
@endif
