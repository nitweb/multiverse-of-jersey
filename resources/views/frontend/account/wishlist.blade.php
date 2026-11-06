@extends('frontend.account.layout')

@php $storeName = $setting('store_name', 'Multiverse of Jersey'); @endphp

@section('frontend_title', 'Wishlist | '.$storeName)
@section('account_parent', true)
@section('account_title', 'Wishlist')
@section('account_sub', 'Jerseys you saved. They stay on your account on every device.')

@push('frontend_scripts')
    <script src="{{ asset('assets/frontend/js/pages/wishlist.js') }}" defer></script>
@endpush

@section('account_body')

    <div id="wish-empty" class="empty card" @if ($products->isNotEmpty()) hidden @endif>
        <svg class="ic w-10 h-10 mx-auto text-ink/30" aria-hidden="true"><use href="#i-heart" /></svg>
        <h2 class="font-display font-bold text-xl mt-3">No saved jerseys yet</h2>
        <p class="text-ink/55 mt-1">Tap the heart on any jersey to save it here.</p>
        <a href="{{ route('shop') }}" class="btn btn-primary mt-5">Browse jerseys</a>
    </div>

    <div id="wish-grid" class="grid grid-cols-2 xl:grid-cols-3 gap-3 sm:gap-5" @if ($products->isEmpty()) hidden @endif>
        @foreach ($products as $product)
            @include('frontend.home._product_card', ['product' => $product])
        @endforeach
    </div>

@endsection
