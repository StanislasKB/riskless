<nav aria-label="Page navigation example">
    <ul class="pagination">
        @if ($pagination_list->onFirstPage())
            <li class="page-item">
                <a class="page-link" href="javascript:;" aria-label="Précédent"><span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        @else
        <li class="page-item">
            <a class="page-link" href="{{ $pagination_list->previousPageUrl() }}" aria-label="Previous"> <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        @endif
         @php
        $currentPage = $pagination_list->currentPage();
        $lastPage = $pagination_list->lastPage();
        $pages = $pagination_list->getUrlRange(1, $lastPage);
        $dotsAdded = false;
    @endphp
          @foreach ($pages as $page => $url)
          @if ($page == $currentPage)
              <li class="page-item"><a class="page-link" href="javascript:;">{{ $page }}</a>
        </li>
             @elseif ($lastPage <= 5 || abs($currentPage - $page) < 2 || $loop->first || $loop->last)
             <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a>
        </li>
        @elseif (!$dotsAdded)
        <li class="page-item">
            <span aria-hidden="true">...</span>
            
        </li>
        @php $dotsAdded = true; @endphp
          @endif
              
          @endforeach  

        @if ($pagination_list->hasMorePages())
        <li class="page-item">
            <a class="page-link" href="{{ $pagination_list->nextPageUrl() }}" aria-label="Suivant"> <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
           @else
            <li class="page-item">
            <a class="page-link" href="javascript:;" aria-label="Suivant"> <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
        @endif
        
        
    </ul>
</nav>
