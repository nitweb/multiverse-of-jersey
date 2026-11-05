@extends('frontend.dashboard')

@php
    $heading = $brand?->name ?? $category?->name ?? 'Shop all products';
    $intro = $brand?->description ?? $category?->description ?? 'Filter by category, brand, color, size and price to find exactly what you need.';
    $storeName = $setting('store_name', 'Multiverse of Jersey');
    $pageTitle = ($brand?->meta_title ?: $category?->meta_title) ?: ($heading.' | '.$storeName);
    $pageDescription = ($brand?->meta_description ?: $category?->meta_description) ?: $intro;
    $baseUrl = $brand ? $brand->url : ($category ? $category->url : route('shop'));
    $hasFilters = $filters['search'] !== '' || $filters['brands'] || $filters['colors'] || $filters['sizes'] || $filters['min'] !== null || $filters['max'] !== null;
@endphp

@section('frontend_title', $pageTitle)
@section('title', $pageTitle)
@section('meta_description', $pageDescription)

@push('frontend_styles')
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/pages/shop.css') }}">
@endpush

@push('frontend_scripts')
    <script src="{{ asset('assets/frontend/js/pages/shop.js') }}" defer></script>
@endpush

@section('frontend_contents')

    <section class="page-head">

        <div class="container-x">

            <nav class="crumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <svg class="ic w-3.5 h-3.5 opacity-50" aria-hidden="true">
                    <use href="#i-chev-right" />
                </svg>
                @if ($brand || $category)
                    <a href="{{ route('shop') }}">Shop</a>
                    <svg class="ic w-3.5 h-3.5 opacity-50" aria-hidden="true">
                        <use href="#i-chev-right" />
                    </svg>
                @endif
                @foreach ($trail as $node)
                    @continue($loop->last)
                    <a href="{{ $node->url }}">{{ $node->name }}</a>
                    <svg class="ic w-3.5 h-3.5 opacity-50" aria-hidden="true">
                        <use href="#i-chev-right" />
                    </svg>
                @endforeach
                <span aria-current="page">{{ $brand || $category ? $heading : 'Shop' }}</span>
            </nav>

            <h1 class="font-display font-extrabold text-3xl sm:text-4xl tracking-tight mt-3">{{ $heading }}</h1>
            <p class="text-ink/60 mt-2 max-w-2xl">{{ $intro }}</p>

        </div>

    </section>

    <section class="section !pt-8">

        <div class="container-x grid lg:grid-cols-[260px_1fr] gap-8">

            <aside id="filters" class="filters" aria-label="Filters">

                <div class="flex items-center justify-between lg:hidden mb-4">
                    <h2 class="font-display font-extrabold text-lg">Filters</h2>
                    <button type="button" class="icon-btn" data-filter-close aria-label="Close filters">
                        <svg class="ic " aria-hidden="true">
                            <use href="#i-x" />
                        </svg>
                    </button>
                </div>

                <form id="filter-form" method="GET" action="{{ $baseUrl }}">

                    <div class="filter-group">
                        <h3>Price (৳)</h3>
                        <div class="flex items-center gap-2">
                            <input type="number" name="min" min="0" value="{{ $filters['min'] }}" placeholder="Min" class="input" aria-label="Minimum price">
                            <span class="text-ink/40">–</span>
                            <input type="number" name="max" min="0" value="{{ $filters['max'] }}" placeholder="{{ $priceCeiling ?: 'Max' }}" class="input" aria-label="Maximum price">
                        </div>
                    </div>

                    @if (! $brand)
                        <div class="filter-group">
                            <h3>Category</h3>
                            <div class="space-y-1">
                                <a href="{{ route('shop') }}" data-category-link class="chk {{ $category ? '' : 'font-bold text-primary' }}">
                                    <span>All products</span>
                                </a>
                                @foreach ($menuCategories as $top)
                                    @include('frontend.shop.partials.category_node', ['node' => $top, 'depth' => 0])
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if (! $brand && $facetBrands->isNotEmpty())
                        <div class="filter-group">
                            <h3>Brand</h3>
                            <div class="space-y-1">
                                @foreach ($facetBrands as $facet)
                                    <label class="chk">
                                        <input type="checkbox" name="brand[]" value="{{ $facet->slug }}" @checked(in_array($facet->slug, $filters['brands'], true))>
                                        <span>{{ $facet->name }}</span>
                                        <small>{{ $facet->facet_count }}</small>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($facetColors->isNotEmpty())
                        <div class="filter-group">
                            <h3>Color</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($facetColors as $color)
                                    <label class="size-chip">
                                        <input type="checkbox" name="color[]" value="{{ $color }}" @checked(in_array($color, $filters['colors'], true))>
                                        <span>{{ $color }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($facetSizes->isNotEmpty())
                        <div class="filter-group">
                            <h3>Size</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($facetSizes as $size)
                                    <label class="size-chip">
                                        <input type="checkbox" name="size[]" value="{{ $size }}" @checked(in_array($size, $filters['sizes'], true))>
                                        <span>{{ $size }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <noscript>
                        <button type="submit" class="btn btn-primary w-full mb-3">Apply filters</button>
                    </noscript>

                    <button type="button" class="btn btn-ghost w-full" data-filter-reset>Reset filters</button>

                </form>

            </aside>

            <div>

                <div class="flex flex-wrap items-center gap-3 mb-5">

                    <button type="button" class="btn btn-ghost lg:hidden" data-filter-open>
                        <svg class="ic w-4 h-4" aria-hidden="true">
                            <use href="#i-sliders" />
                        </svg>
                        Filters
                    </button>

                    <label class="relative flex-1 min-w-[12rem]" for="shop-q">
                        <span class="sr-only">Search products</span>
                        <input id="shop-q" type="search" name="q" form="filter-form" value="{{ $filters['search'] }}" placeholder="Search products by name" autocomplete="off" enterkeyhint="search" class="input">
                    </label>

                    <label class="flex items-center gap-2 text-sm">
                        <span class="text-ink/55 hidden sm:inline">Sort by</span>
                        <select id="sort" name="sort" form="filter-form" class="input !w-auto !py-2">
                            <option value="new" @selected($filters['sort'] === 'new')>Newest</option>
                            <option value="pop" @selected($filters['sort'] === 'pop')>Most popular</option>
                            <option value="lo" @selected($filters['sort'] === 'lo')>Price: low to high</option>
                            <option value="hi" @selected($filters['sort'] === 'hi')>Price: high to low</option>
                        </select>
                    </label>

                </div>

                <div class="flex flex-wrap items-center gap-2 mb-4">

                    <p class="text-sm text-ink/60 mr-1" aria-live="polite">
                        <b class="text-ink" data-result-count>{{ $products->total() }}</b> {{ \Illuminate\Support\Str::plural('product', $products->total()) }}
                    </p>

                    <div id="active-filters" style="display: contents"></div>

                </div>

                <div id="shop-results" data-base-url="{{ $baseUrl }}" data-shop-url="{{ route('shop') }}" data-category="{{ $category?->name }}">
                    @include('frontend.shop.partials.results', ['products' => $products, 'baseUrl' => $baseUrl])
                </div>

            </div>

        </div>

    </section>

    <div id="filter-overlay" class="overlay" hidden></div>

@endsection
