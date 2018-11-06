
    @if ($paginator->lastPage() > 1)
    <ul class="pagination">
        
            @if($paginator->currentPage() == 1)
                <li class="disabled">
                    <a href="javascript:void(0)" rel="prev">
                        <span aria-hidden="true"><img src="{{ asset('/front/images/left-arrow.png') }}" alt="" title=""></span>
                    </a>
                </li>
            @else
            
            <li class="disabled">
                <a href="{{ $paginator->url(1) }}" rel="prev">
                    <span aria-hidden="true"><img src="{{ asset('/front/images/left-arrow.png') }}" alt="" title=""></span>
                </a>
            </li>
            @endif
        

        @for ($i = 1; $i <= $paginator->lastPage(); $i++)
             @if($i === 1)
                <li> Page </li>
            @endif
            <li class="{{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
                <a href="{{ $paginator->url($i) }}">{{ $i }}</a>
            </li>
        @endfor
        
            @if($paginator->currentPage() == $paginator->lastPage())
                <li class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
                        <a href="javascript:void(0)" rel="next">
                            <span aria-hidden="true"><img src="{{ asset('/front/images/right-arrow1.png') }}" alt="" title=""></span>
                        </a>
                </li>
            @else
            
                <li>
                <a href="{{ $paginator->url($paginator->currentPage()+1) }}" rel="next">
                <span aria-hidden="true"><img src="{{ asset('/front/images/right-arrow1.png') }}" alt="" title=""></span>
                </a>
                </li>
            @endif
        
    </ul>
    @endif
