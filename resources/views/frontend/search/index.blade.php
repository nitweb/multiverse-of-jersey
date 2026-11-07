@extends('frontend.dashboard')

@php
    $storeName = $setting('store_name', 'Multiverse of Jersey');
@endphp

@section('frontend_title', 'Search results for “'.$term.'” | '.$storeName)
@section('title', 'Search results for “'.$term.'” | '.$storeName)
@section('meta_description', 'Products matching “'.$term.'”.')

@section('frontend_contents')

    <section class="page-head">

        <div class="container-x">

            <nav class="crumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <svg class="ic w-3.5 h-3.5 opacity-50" aria-hidden="true"><use href="#i-chev-right" /></svg>
                <span aria-current="page">Search</span>
            </nav>

            <h1 class="font-display font-extrabold text-3xl sm:text-4xl tracking-tight mt-3">Search results for “{{ $term }}”</h1>

            <p class="text-ink/60 mt-2" aria-live="polite">
                @if ($products->total())
                    {{ $products->total() }} {{ \Illuminate\Support\Str::plural('product', $products->total()) }} found
                @else
                    No products found
                @endif
            </p>

        </div>

    </section>

    <section class="section !pt-8" aria-label="Search results">

        <div class="container-x">

            @if ($products->isNotEmpty())

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-5">
                    @foreach ($products as $product)
                        @include('frontend.home._product_card', ['product' => $product])
                    @endforeach
                </div>

                @if ($products->hasPages())
                    <nav class="pagination" aria-label="Pagination">

                        @if ($products->onFirstPage())
                            <span class="pg disabled" aria-label="Previous page"><svg class="ic w-4 h-4" aria-hidden="true"><use href="#i-chev-left" /></svg></span>
                        @else
                            <a href="{{ $products->previousPageUrl() }}" class="pg" rel="prev" aria-label="Previous page"><svg class="ic w-4 h-4" aria-hidden="true"><use href="#i-chev-left" /></svg></a>
                        @endif

                        @foreach ($products->getUrlRange(max(1, $products->currentPage() - 2), min($products->lastPage(), $products->currentPage() + 2)) as $page => $url)
                            <a href="{{ $url }}" class="pg {{ $page === $products->currentPage() ? 'active' : '' }}" @if ($page === $products->currentPage()) aria-current="page" @endif>{{ $page }}</a>
                        @endforeach

                        @if ($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}" class="pg" rel="next" aria-label="Next page"><svg class="ic w-4 h-4" aria-hidden="true"><use href="#i-chev-right" /></svg></a>
                        @else
                            <span class="pg disabled" aria-label="Next page"><svg class="ic w-4 h-4" aria-hidden="true"><use href="#i-chev-right" /></svg></span>
                        @endif

                    </nav>
                @endif

            @else

                <div class="empty">
                    <svg class="ic w-10 h-10 mx-auto text-ink/30" aria-hidden="true"><use href="#i-search" /></svg>
                    <h3 class="font-display font-bold text-xl mt-3">Nothing matches “{{ $term }}”</h3>
                    <p class="text-ink/55 mt-1">Try a different word, or browse everything we have.</p>
                    <a href="{{ route('shop') }}" class="btn btn-primary mt-5">Browse all jerseys</a>
                </div>

            @endif

        </div>

    </section>

@endsection
