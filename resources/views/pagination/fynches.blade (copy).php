<ul class="pagination">
    <!-- Previous Page Link -->

    @if ($paginator->onFirstPage())
        <li class="disabled"><a href="javascript:void(0)" rel="prev">
            <span aria-hidden="true"><img src="{{ asset('/front/images/left-arrow.png') }}" alt="" title=""></span>
        </a></li>
    @else
        <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li>
    @endif

    <!-- Pagination Elements -->
    <?php 
    //pr($elements);
    ?>
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
        <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li>
    @endif
</ul>


@if ($paginator->hasPages())
    <ul class="pagination">
        
        @if ($paginator->onFirstPage())
            <li class="disabled"><a href="javascript:void(0)" rel="prev">
            <span aria-hidden="true"><img src="{{ asset('/front/images/left-arrow.png') }}" alt="" title=""></span>
        </a></li>
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li>
        @endif

        @if($paginator->currentPage() > 3)
            <li class="hidden-xs"><a href="{{ $paginator->url(1) }}">1</a></li>
        @endif
        @if($paginator->currentPage() > 4)
            <li class="disabled hidden-xs"><span>...</span></li>
        @endif
        
        @foreach(range(1, $paginator->lastPage()) as $key => $i)
            @if($key === 0)
                <li> Page </li>
            @endif
            @if($i >= $paginator->currentPage() - 2 && $i <= $paginator->currentPage() + 2)
                @if ($i == $paginator->currentPage())
                    <li class="active"><span>{{ $i }}</span></li>
                @else
                    <li><a href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                @endif
            @endif
        @endforeach

        @if($paginator->currentPage() < $paginator->lastPage() - 3)
            <li class="disabled hidden-xs"><span>...</span></li>
        @endif

        @if($paginator->currentPage() < $paginator->lastPage() - 2)
            <li class="hidden-xs"><a href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a></li>
        @endif

        @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">
            <span aria-hidden="true"><img src="{{ asset('/front/images/right-arrow1.png') }}" alt="" title=""></span>
        </a></li>
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li>
        @endif
    </ul>
@endif