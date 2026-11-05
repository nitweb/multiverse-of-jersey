<div data-results data-total="{{ $products->total() }}">

    @if ($products->isNotEmpty())

        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 sm:gap-5">
            @foreach ($products as $product)
                @include('frontend.home._product_card', ['product' => $product])
            @endforeach
        </div>

        @if ($products->hasPages())
            <nav class="pagination" aria-label="Pagination">

                @if ($products->onFirstPage())
                    <span class="pg disabled" aria-label="Previous page">
                        <svg class="ic w-4 h-4" aria-hidden="true">
                            <use href="#i-chev-left" />
                        </svg>
                    </span>
                @else
                    <a href="{{ $products->previousPageUrl() }}" class="pg" rel="prev" aria-label="Previous page">
                        <svg class="ic w-4 h-4" aria-hidden="true">
                            <use href="#i-chev-left" />
                        </svg>
                    </a>
                @endif

                @foreach ($products->getUrlRange(max(1, $products->currentPage() - 2), min($products->lastPage(), $products->currentPage() + 2)) as $page => $url)
                    <a href="{{ $url }}" class="pg {{ $page === $products->currentPage() ? 'active' : '' }}" @if ($page === $products->currentPage()) aria-current="page" @endif>{{ $page }}</a>
                @endforeach

                @if ($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" class="pg" rel="next" aria-label="Next page">
                        <svg class="ic w-4 h-4" aria-hidden="true">
                            <use href="#i-chev-right" />
                        </svg>
                    </a>
                @else
                    <span class="pg disabled" aria-label="Next page">
                        <svg class="ic w-4 h-4" aria-hidden="true">
                            <use href="#i-chev-right" />
                        </svg>
                    </span>
                @endif

            </nav>
        @endif

    @else

        <div class="empty">
            <svg class="ic w-10 h-10 mx-auto text-ink/30" aria-hidden="true">
                <use href="#i-search" />
            </svg>
            <h3 class="font-display font-bold text-xl mt-3">No products match your filters</h3>
            <p class="text-ink/55 mt-1">Try removing a filter or searching for something else.</p>
            <a href="{{ route('shop') }}" class="btn btn-primary mt-5" data-filter-reset>Reset filters</a>
        </div>

    @endif

</div>
