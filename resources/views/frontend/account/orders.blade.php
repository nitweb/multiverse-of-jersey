@extends('frontend.account.layout')

@php
    $storeName = $setting('store_name', 'Multiverse of Jersey');
    $tz = \App\Models\Setting::tz();
    $filters = ['' => 'All'] + \App\Models\Order::STATUSES;
@endphp

@section('frontend_title', 'My orders | '.$storeName)
@section('account_parent', true)
@section('account_title', 'My orders')
@section('account_sub', 'Every order placed with your mobile number.')

@section('account_body')

    <div class="pills" role="navigation" aria-label="Filter orders">
        @foreach ($filters as $key => $label)
            <a href="{{ route('account.orders', $key === '' ? [] : ['status' => $key]) }}" class="pill grid place-items-center {{ $status === $key ? 'is-active' : '' }}" @if ($status === $key) aria-current="true" @endif>{{ $label }}</a>
        @endforeach
    </div>

    <div class="space-y-4">

        @forelse ($orders as $order)
            <details class="card order-row" @if ($loop->first && $orders->currentPage() === 1) open @endif>

                <summary>
                    <div class="flex flex-wrap items-center gap-x-8 gap-y-1 flex-1">
                        <b class="font-display">{{ $order->number }}</b>
                        <span class="text-sm text-ink/55">{{ $order->created_at->timezone($tz)->format('d M Y') }}</span>
                        <span class="tk-badge flat is-{{ $order->status }}">{{ $order->status_label }}</span>
                        <b class="ml-auto">৳{{ number_format($order->total) }}</b>
                    </div>
                    <svg class="ic w-5 h-5 chev" aria-hidden="true"><use href="#i-chev-down" /></svg>
                </summary>

                <div class="p-5 pt-5 space-y-3 border-t border-line">

                    @foreach ($order->items as $item)
                        <div class="flex items-center gap-3">
                            <img src="{{ $item->image }}" alt="" class="acc-thumb" loading="lazy">
                            <div class="min-w-0 flex-1">
                                <p class="font-semibold text-sm truncate">{{ $item->name }}</p>
                                <p class="text-xs text-ink/50">{{ $item->variant_label ? $item->variant_label.' · ' : '' }}Qty {{ $item->quantity }}</p>
                            </div>
                            <b class="text-sm">৳{{ number_format($item->line_total) }}</b>
                        </div>
                    @endforeach

                    <div class="flex flex-wrap gap-3 pt-2">
                        <a href="{{ route('account.orders.show', $order->number) }}" class="btn btn-primary btn-sm">View details</a>
                        <form method="POST" action="{{ route('account.orders.reorder', $order->number) }}">
                            @csrf
                            <button type="submit" class="btn btn-ghost btn-sm">Order again</button>
                        </form>
                        <a href="{{ route('track.show', ['order' => $order->number]) }}" class="btn btn-ghost btn-sm">Track order</a>
                        @if ($order->canRequestReturn())
                            <a href="{{ route('account.returns.create', $order->number) }}" class="btn btn-ghost btn-sm">{{ $order->status === 'delivered' ? 'Return' : 'Cancel / return' }}</a>
                        @endif
                    </div>

                </div>

            </details>
        @empty
            <div class="empty card">
                <svg class="ic w-10 h-10 mx-auto text-ink/30" aria-hidden="true"><use href="#i-package" /></svg>
                <h2 class="font-display font-bold text-xl mt-3">No orders found</h2>
                <p class="text-ink/55 mt-1">When you place an order it will show up here.</p>
                <a href="{{ route('shop') }}" class="btn btn-primary mt-5">Start shopping</a>
            </div>
        @endforelse

    </div>

    @if ($orders->hasPages())
        <nav class="pagination tight" aria-label="Pagination">

            @if ($orders->onFirstPage())
                <span class="pg disabled" aria-label="Previous page"><svg class="ic w-4 h-4" aria-hidden="true"><use href="#i-chev-left" /></svg></span>
            @else
                <a href="{{ $orders->previousPageUrl() }}" class="pg" rel="prev" aria-label="Previous page"><svg class="ic w-4 h-4" aria-hidden="true"><use href="#i-chev-left" /></svg></a>
            @endif

            @foreach ($orders->getUrlRange(max(1, $orders->currentPage() - 2), min($orders->lastPage(), $orders->currentPage() + 2)) as $page => $url)
                <a href="{{ $url }}" class="pg {{ $page === $orders->currentPage() ? 'active' : '' }}" @if ($page === $orders->currentPage()) aria-current="page" @endif>{{ $page }}</a>
            @endforeach

            @if ($orders->hasMorePages())
                <a href="{{ $orders->nextPageUrl() }}" class="pg" rel="next" aria-label="Next page"><svg class="ic w-4 h-4" aria-hidden="true"><use href="#i-chev-right" /></svg></a>
            @else
                <span class="pg disabled" aria-label="Next page"><svg class="ic w-4 h-4" aria-hidden="true"><use href="#i-chev-right" /></svg></span>
            @endif

        </nav>
    @endif

@endsection
