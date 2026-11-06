@extends('frontend.dashboard')

@php
    $storeName = $setting('store_name', 'Multiverse of Jersey');
    $minOrder = (float) $setting('min_order', 0);
@endphp

@section('frontend_title', 'Shopping cart | '.$storeName)
@section('title', 'Shopping cart | '.$storeName)
@section('meta_description', 'Review the jerseys in your cart before checkout.')

@push('frontend_styles')
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/pages/cart.css') }}">
@endpush

@push('frontend_scripts')
    <script src="{{ asset('assets/frontend/js/pages/summary.js') }}" defer></script>
    <script src="{{ asset('assets/frontend/js/pages/cart.js') }}" defer></script>
@endpush

@section('frontend_contents')

    <section class="page-head">

        <div class="container-x">

            <nav class="crumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <svg class="ic w-3.5 h-3.5 opacity-50" aria-hidden="true"><use href="#i-chev-right" /></svg>
                <span aria-current="page">Cart</span>
            </nav>

            <h1 class="font-display font-extrabold text-3xl sm:text-4xl tracking-tight mt-3">Shopping cart</h1>

        </div>

    </section>

    <section class="section !pt-8">

        <div class="container-x">

            @if (session('error'))
                <p class="rounded-xl bg-accent-soft text-danger font-semibold text-sm px-4 py-3 mb-5" role="alert">{{ session('error') }}</p>
            @endif

            <div id="cart-notices" class="mb-5 space-y-2" role="status"></div>

            <div id="cart-empty" class="empty" hidden>
                <svg class="ic w-12 h-12 mx-auto text-ink/25" aria-hidden="true"><use href="#i-bag" /></svg>
                <h2 class="font-display font-bold text-2xl mt-4">Your cart is empty</h2>
                <p class="text-ink/55 mt-1">Looks like you haven’t added any jerseys yet.</p>
                <a href="{{ route('shop') }}" class="btn btn-primary btn-lg mt-6">Start shopping</a>
            </div>

            <div id="cart-filled" class="grid lg:grid-cols-[1fr_380px] gap-8" hidden>

                <div>

                    <div class="ship-bar card p-4 mb-5">
                        <p class="text-sm" data-ship-msg></p>
                        <div class="bar-track mt-2"><i data-ship-bar></i></div>
                    </div>

                    <div class="card overflow-hidden">
                        <div class="cart-head hidden md:grid">
                            <span>Product</span>
                            <span>Price</span>
                            <span>Quantity</span>
                            <span class="text-right">Total</span>
                        </div>
                        <div data-cart-lines></div>
                    </div>

                    <div class="flex flex-wrap gap-3 justify-between mt-5">
                        <a href="{{ route('shop') }}" class="btn btn-ghost">
                            <svg class="ic w-4 h-4" aria-hidden="true"><use href="#i-chev-left" /></svg> Continue shopping
                        </a>
                        <button type="button" class="btn btn-ghost !text-danger" data-clear-cart>
                            <svg class="ic w-4 h-4" aria-hidden="true"><use href="#i-trash" /></svg> Clear cart
                        </button>
                    </div>

                </div>

                <aside class="card p-5 sm:p-6 lg:sticky lg:top-28 h-fit">

                    <h2 class="font-display font-extrabold text-lg">Cart totals</h2>

                    <form class="mt-5 flex gap-2" data-coupon-form>
                        <input class="input" name="coupon" placeholder="Coupon code" aria-label="Coupon code" maxlength="40" autocomplete="off">
                        <button class="btn btn-ghost" type="submit">Apply</button>
                    </form>
                    <p class="text-xs mt-2" data-coupon-msg></p>

                    <dl class="mt-5 space-y-2.5 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-ink/60">Subtotal</dt>
                            <dd class="font-semibold" data-sum-sub>৳0</dd>
                        </div>
                        <div class="flex justify-between" data-discount-row hidden>
                            <dt class="text-ink/60">Discount <span data-coupon-label></span></dt>
                            <dd class="font-semibold text-success" data-sum-discount>-৳0</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-ink/60">Delivery</dt>
                            <dd class="font-semibold" data-sum-ship>—</dd>
                        </div>
                        <div class="flex justify-between pt-3 border-t border-line text-base">
                            <dt class="font-bold">Total</dt>
                            <dd class="font-display font-extrabold text-xl" data-sum-total>৳0</dd>
                        </div>
                    </dl>

                    <a href="{{ route('checkout.show') }}" class="btn btn-primary btn-lg w-full mt-5" data-checkout-btn>
                        Proceed to checkout <svg class="ic w-4 h-4" aria-hidden="true"><use href="#i-arrow-right" /></svg>
                    </a>

                    <p class="text-xs text-ink/50 text-center mt-3">
                        @if ($minOrder > 0) Minimum order ৳{{ number_format($minOrder) }} · @endif Cash on delivery
                    </p>

                </aside>

            </div>

        </div>

    </section>

@endsection
