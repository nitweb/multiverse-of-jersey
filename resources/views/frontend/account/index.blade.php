@extends('frontend.account.layout')

@php
    $storeName = $setting('store_name', 'Multiverse of Jersey');
    $tz = \App\Models\Setting::tz();
@endphp

@section('frontend_title', 'My account | '.$storeName)
@section('account_title', 'My account')
@section('account_sub', 'Hello, '.\Illuminate\Support\Str::before($user->name, ' ').'. Here is what is going on with your orders.')

@section('account_body')

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('account.orders') }}" class="card stat">
            <small>Total orders</small>
            <b>{{ $orderCount }}</b>
        </a>
        <a href="{{ route('account.orders') }}" class="card stat">
            <small>On the way</small>
            <b>{{ $activeCount }}</b>
        </a>
        <div class="card stat">
            <small>Total spent</small>
            <b>৳{{ number_format($spent) }}</b>
        </div>
        <a href="{{ route('account.wishlist') }}" class="card stat">
            <small>Wishlist</small>
            <b>{{ $wishlistCount }}</b>
        </a>
    </div>

    <section class="card p-5 sm:p-7" aria-labelledby="h-recent">

        <div class="flex items-center justify-between gap-3">
            <h2 id="h-recent" class="font-display font-extrabold text-xl">Recent orders</h2>
            @if ($orderCount)
                <a href="{{ route('account.orders') }}" class="link-arrow">View all</a>
            @endif
        </div>

        @forelse ($recentOrders as $order)
            <a href="{{ route('account.orders.show', $order->number) }}" class="flex flex-wrap items-center justify-between gap-3 py-4 {{ $loop->first ? 'mt-2' : 'border-t border-line' }}">
                <span>
                    <b class="font-display">{{ $order->number }}</b>
                    <span class="block text-sm text-ink/55">{{ $order->created_at->timezone($tz)->format('d M Y') }} · {{ $order->items->sum('quantity') }} item(s)</span>
                </span>
                <span class="flex items-center gap-3">
                    <b>৳{{ number_format($order->total) }}</b>
                    <span class="tk-badge flat is-{{ $order->status }}">{{ $order->status_label }}</span>
                </span>
            </a>
        @empty
            <div class="empty">
                <svg class="ic w-10 h-10 mx-auto text-ink/30" aria-hidden="true"><use href="#i-package" /></svg>
                <p class="font-display font-bold text-lg mt-3">No orders yet</p>
                <p class="text-ink/55 mt-1">Orders placed with your mobile number will show up here.</p>
                <a href="{{ route('shop') }}" class="btn btn-primary mt-5">Start shopping</a>
            </div>
        @endforelse

    </section>

    <div class="grid md:grid-cols-2 gap-6">

        <section class="card p-5 sm:p-7" aria-labelledby="h-me">
            <h2 id="h-me" class="font-display font-extrabold text-xl">Your details</h2>
            <p class="mt-3 font-semibold">{{ $user->name }}</p>
            <p class="text-sm text-ink/60">{{ $user->phone ?: 'No mobile number yet' }}</p>
            <p class="text-sm text-ink/60">{{ $user->email ?: 'No email yet' }}</p>
            <a href="{{ route('account.profile') }}" class="link-arrow mt-4">Edit details</a>
        </section>

        <section class="card p-5 sm:p-7" aria-labelledby="h-addr">
            <h2 id="h-addr" class="font-display font-extrabold text-xl">Default address</h2>
            @if ($address)
                <p class="mt-3 font-semibold">{{ $address->name }} <span class="badge badge-soft ml-1">{{ $address->label }}</span></p>
                <p class="text-sm text-ink/60">{{ $address->full_address }}</p>
                <p class="text-sm text-ink/60">{{ $address->phone }}</p>
            @else
                <p class="mt-3 text-sm text-ink/60">No saved address yet. Add one to speed up checkout.</p>
            @endif
            <a href="{{ route('account.addresses') }}" class="link-arrow mt-4">Manage addresses</a>
        </section>

    </div>

@endsection
