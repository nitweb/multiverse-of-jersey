@extends('frontend.account.layout')

@php
    $storeName = $setting('store_name', 'Multiverse of Jersey');
    $tz = \App\Models\Setting::tz();
@endphp

@section('frontend_title', 'Returns | '.$storeName)
@section('account_parent', true)
@section('account_title', 'Returns')
@section('account_sub', 'Your return and cancellation requests.')

@section('account_body')

    <div class="space-y-4">

        @forelse ($returns as $return)
            <article class="card p-5 sm:p-6">

                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="font-display font-extrabold text-lg">{{ $return->number }} · {{ $return->type_label }}</p>
                        <p class="text-sm text-ink/55">
                            Order <a href="{{ route('account.orders.show', $return->order->number) }}" class="link">{{ $return->order->number }}</a>
                            · {{ $return->created_at->timezone($tz)->format('d M Y, h:i A') }}
                        </p>
                    </div>
                    <span class="tk-badge flat is-{{ $return->status }}">{{ $return->status_label }}</span>
                </div>

                <div class="space-y-3 mt-4">
                    @foreach ($return->items as $line)
                        @if ($line->orderItem)
                            <div class="flex items-center gap-3">
                                <img src="{{ $line->orderItem->image }}" alt="" class="acc-thumb sm" loading="lazy">
                                <div class="min-w-0 flex-1">
                                    <p class="font-semibold text-sm leading-snug">{{ $line->orderItem->name }}</p>
                                    <p class="text-xs text-ink/50">{{ $line->orderItem->variant_label ? $line->orderItem->variant_label.' · ' : '' }}Qty {{ $line->quantity }}</p>
                                </div>
                                <b class="text-sm">৳{{ number_format($line->orderItem->unit_price * $line->quantity) }}</b>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="acc-sep text-sm text-ink/70">
                    <p>Reason: <b class="text-ink">{{ $return->reason_label }}</b>@if ($return->details) · {{ $return->details }}@endif</p>
                    @if ($return->admin_note)
                        <p class="mt-2 rounded-xl bg-bg border border-line px-3 py-2">Store note: {{ $return->admin_note }}</p>
                    @endif
                </div>

            </article>
        @empty
            <div class="empty card">
                <svg class="ic w-10 h-10 mx-auto text-ink/30" aria-hidden="true"><use href="#i-return" /></svg>
                <h2 class="font-display font-bold text-xl mt-3">No return requests</h2>
                <p class="text-ink/55 mt-1">Open one of your orders to cancel or return products.</p>
                <a href="{{ route('account.orders') }}" class="btn btn-primary mt-5">My orders</a>
            </div>
        @endforelse

    </div>

    @if ($returns->hasPages())
        <nav class="pagination tight" aria-label="Pagination">

            @if ($returns->onFirstPage())
                <span class="pg disabled" aria-label="Previous page"><svg class="ic w-4 h-4" aria-hidden="true"><use href="#i-chev-left" /></svg></span>
            @else
                <a href="{{ $returns->previousPageUrl() }}" class="pg" rel="prev" aria-label="Previous page"><svg class="ic w-4 h-4" aria-hidden="true"><use href="#i-chev-left" /></svg></a>
            @endif

            @foreach ($returns->getUrlRange(max(1, $returns->currentPage() - 2), min($returns->lastPage(), $returns->currentPage() + 2)) as $page => $url)
                <a href="{{ $url }}" class="pg {{ $page === $returns->currentPage() ? 'active' : '' }}" @if ($page === $returns->currentPage()) aria-current="page" @endif>{{ $page }}</a>
            @endforeach

            @if ($returns->hasMorePages())
                <a href="{{ $returns->nextPageUrl() }}" class="pg" rel="next" aria-label="Next page"><svg class="ic w-4 h-4" aria-hidden="true"><use href="#i-chev-right" /></svg></a>
            @else
                <span class="pg disabled" aria-label="Next page"><svg class="ic w-4 h-4" aria-hidden="true"><use href="#i-chev-right" /></svg></span>
            @endif

        </nav>
    @endif

@endsection
