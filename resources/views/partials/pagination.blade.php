@if($paginator->hasPages())
<nav class="pagination" aria-label="Halaman hasil">
    @if($paginator->onFirstPage())<span class="icon-button disabled"><x-icon name="chevron-left"/></span>@else<a class="icon-button" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya"><x-icon name="chevron-left"/></a>@endif
    <span>Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}</span>
    @if($paginator->hasMorePages())<a class="icon-button" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya"><x-icon name="chevron-right"/></a>@else<span class="icon-button disabled"><x-icon name="chevron-right"/></span>@endif
</nav>
@endif
