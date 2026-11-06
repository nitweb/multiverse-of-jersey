@extends('frontend.dashboard')

@php
    $storeName = $setting('store_name', 'Multiverse of Jersey');
@endphp

@section('frontend_title', 'Order placed | '.$storeName)
@section('title', 'Order placed | '.$storeName)
@section('meta_description', 'Your order has been placed.')

@push('frontend_styles')
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/pages/checkout.css') }}">
@endpush

@section('frontend_contents')

    <section class="section">

        <div class="container-x max-w-3xl">

            <div class="card p-6 sm:p-10 text-center">

                <div class="success-ic">
                    <svg class="ic w-10 h-10" aria-hidden="true"><use href="#i-check" /></svg>
                </div>

                <h1 class="font-display font-extrabold text-3xl mt-6">Thank you! Order placed.</h1>
                <p class="text-ink/60 mt-2">We’ve received your order and will confirm it by phone shortly.</p>

                <div class="inline-flex flex-col sm:flex-row gap-x-8 gap-y-2 mt-6 text-sm rounded-2xl bg-bg border border-line px-6 py-4">
                    <span>Order no. <b class="font-display text-base">{{ $order->number }}</b></span>
                    <span>Total <b class="font-display text-base">৳{{ number_format($order->total) }}</b></span>
                    <span>Payment <b>Cash on delivery</b></span>
                </div>

                @if (session('new_account') && ! auth()->check())
                    <p class="mt-5 text-sm text-left rounded-xl bg-accent-soft text-ink/80 p-4">
                        We created an account for you. Sign in with <b>{{ $order->phone }}</b> and the password <b>{{ \App\Services\CheckoutService::startingPassword($order->phone) }}</b> to see all your orders. You can change the password after signing in.
                    </p>
                @endif

                <div class="text-left mt-8 grid sm:grid-cols-2 gap-6">

                    <div>
                        <h2 class="font-display font-bold mb-3">Items</h2>
                        <div class="space-y-3">
                            @foreach ($order->items as $item)
                                <div class="flex items-center gap-3">
                                    <span class="relative shrink-0">
                                        <img src="{{ $item->image }}" alt="" class="w-14 h-14 rounded-xl object-cover bg-bg">
                                        <i class="absolute -top-2 -right-2 min-w-5 h-5 px-1 rounded-full bg-ink text-bg text-[11px] font-bold grid place-items-center not-italic">{{ $item->quantity }}</i>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold leading-snug line-clamp-2">{{ $item->name }}</p>
                                        @if ($item->variant_label)
                                            <p class="text-xs text-ink/50">{{ $item->variant_label }}</p>
                                        @endif
                                    </div>
                                    <b class="text-sm">৳{{ number_format($item->line_total) }}</b>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <h2 class="font-display font-bold mb-3">Delivering to</h2>
                        <p class="text-sm text-ink/70 leading-relaxed">
                            {{ $order->name }}<br>
                            {{ $order->phone }}<br>
                            {{ $order->address }}, {{ $order->area }}, {{ $order->district }}
                        </p>

                        <h2 class="font-display font-bold mt-5 mb-2">What happens next</h2>
                        <ol class="text-sm text-ink/70 space-y-2 list-decimal pl-5">
                            <li>We call to confirm your order.</li>
                            <li>Your jersey is packed &amp; handed to the courier.</li>
                            <li>Pay cash on delivery.</li>
                        </ol>
                    </div>

                </div>

                <div class="flex flex-wrap gap-3 justify-center mt-8">
                    <a href="{{ route('track.show') }}" class="btn btn-primary btn-lg">Track order</a>
                    <a href="{{ route('shop') }}" class="btn btn-ghost btn-lg">Continue shopping</a>
                </div>

            </div>

        </div>

    </section>

@endsection
