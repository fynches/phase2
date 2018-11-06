@if(!$link_limit)
    @if ($paginator->lastPage() > 1)
    <ul class="pagination">
        <li class="">
            @if($paginator->currentPage() == 1)
                <label class="disabled">First</label>
            @else
            <a href="{{ $paginator->url(1) }}">First</a>
            @endif
        </li>
        @for ($i = 1; $i <= $paginator->lastPage(); $i++)
            <li class="{{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
                <a href="{{ $paginator->url($i) }}">{{ $i }}</a>
            </li>
        @endfor
        <li class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
            @if($paginator->currentPage() == $paginator->lastPage())
                <label class="disabled">Next</label>
            @else
            <a href="{{ $paginator->url($paginator->currentPage()+1) }}" >Next</a>
            @endif
        </li>
    </ul>
    @endif
@else
    @if ($paginator->lastPage() > 1)
    
    
        <ul class="pagination">
        	<li>
                <a href="{{ $paginator->url($paginator->currentPage() - 1) }}" aria-label="Previous">
                    <span aria-hidden="true"><img src="{{ asset('front/images/left-arrow.png') }}" alt="" title=""></span>
                </a>
            </li>
            <li>
                @if($paginator->currentPage() == 1)
                <label class="disabled">Page</label>
                @else
                <a href="{{ $paginator->url(1) }}" aria-label="Previous"></a>
                @endif
             </li>
             <?php
                $half_total_links = floor($link_limit / 2);
                $from = ($paginator->currentPage() - $half_total_links) < 1 ? 1 : $paginator->currentPage() - $half_total_links;
                $to = ($paginator->currentPage() + $half_total_links) > $paginator->lastPage() ? $paginator->lastPage() : ($paginator->currentPage() + $half_total_links);
                if ($from > $paginator->lastPage() - $link_limit) {
                   $from = ($paginator->lastPage() - $link_limit) + 1;
                   $to = $paginator->lastPage();
                }
                if ($to <= $link_limit) {
                    $from = 1;
                    $to = $link_limit < $paginator->lastPage() ? $link_limit : $paginator->lastPage();
                }
            ?>
            @for ($i = $from; $i < $to; $i++)
                    <li class="{{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
                    	<?php //pr($paginator); ?>
                        <a href="{{ $paginator->url($i) }}">{{ $i }}</a>
                    </li>
            @endfor
            <li class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
                @if($paginator->currentPage() == $paginator->lastPage())
                <label class="disabled"><span aria-hidden="true"><img src="{{ asset('front/images/right-arrow1.png') }}" alt="" title=""></span></label>
                @else
                <a href="{{ $paginator->url($paginator->currentPage() + 1) }}" aria-label="Next">
                	<span aria-hidden="true"><img src="{{ asset('front/images/right-arrow1.png') }}" alt="" title=""></span>
                </a>
                @endif
            </li>
        </ul>
    @endif
@endif