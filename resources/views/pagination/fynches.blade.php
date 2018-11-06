<ul class="pagination">
    <!-- Previous Page Link -->

    @if ($paginator->onFirstPage())
        <li class="disabled"><a href="javascript:void(0)" rel="prev">
            <span aria-hidden="true"><img src="{{ asset('/front/images/left-arrow.png') }}" alt="" title=""></span>
        </a></li>
    @else
        <li>
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev">
                <span aria-hidden="true"><img src="{{ asset('/front/images/left-arrow.png') }}" alt="" title=""></span>
            </a>
        </li>
    @endif

    <!-- Pagination Elements -->

    @foreach ($elements as $key => $element)
        <?php 
        
        ?>
        @if($key === 0)
            <li> Page </li>
        @endif
        <!-- "Three Dots" Separator -->
        @if (is_string($element))
            <li class="disabled"><span>{{ $element }}</span></li>
        @endif

        <!-- Array Of Links -->
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <li class="active"><span>{{ $page }}</span></li>
                @else
                    <li><a href="{{ $url }}">{{ $page }}</a></li>
                @endif
            @endforeach
        @endif
    @endforeach

    <!-- Next Page Link -->
    @if ($paginator->hasMorePages())
        <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">
            <span aria-hidden="true"><img src="{{ asset('/front/images/right-arrow1.png') }}" alt="" title=""></span>
        </a></li>
    @else
        <li class="disabled">
            <a href="javascript:void(0)" rel="next">
                <span aria-hidden="true"><img src="{{ asset('/front/images/right-arrow1.png') }}" alt="" title=""></span>
            </a>
        </li>
    @endif
</ul>