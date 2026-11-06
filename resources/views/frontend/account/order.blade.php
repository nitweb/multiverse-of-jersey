@extends('frontend.account.layout')

@php
    use App\Models\Order;
    use App\Models\Setting;

    $storeName = $setting('store_name', 'Multiverse of Jersey');
    $tz = Setting::tz();
    $order->loadMissing('items.returnItems.orderReturn');

    $ranks = ['pending' => 1, 'processing' => 2, 'shipped' => 3, 'out_for_delivery' => 4, 'delivered' => 5];
    $stepLabels = [
        'pending' => 'Order placed',
        'processing' => 'Processing / packed',
        'shipped' => 'Handed to courier',
        'out_for_delivery' => 'Out for delivery',
        'delivered' => 'Delivered',
    ];
    $final = $order->isFinal();
    $reached = $order->events->pluck('status')->push($order->status)->unique();
    $current = $final
        ? $reached->map(fn ($status) => $ranks[$status] ?? 0)->max()
        : ($ranks[$order->status] ?? 1);
    $timeOf = fn (string $status) => $order->events->firstWhere('status', $status)?->created_at?->timezone($tz);
@endphp

@section('frontend_title', 'Order '.$order->number.' | '.$storeName)
@section('account_parent', true)
@section('account_title', 'Order '.$order->number)
@section('account_sub', 'Placed on '.$order->created_at->timezone($tz)->format('d M Y, h:i A'))

@section('account_body')

    <section class="card p-5 sm:p-7" aria-labelledby="h-status">

        <div class="flex flex-wrap justify-between gap-4">
            <div>
                <p class="text-sm text-ink/55">Status</p>
                <span class="tk-badge is-{{ $order->status }}">{{ $order->status_label }}</span>
            </div>
            @if ($order->courier_label)
                <div>
                    <p class="text-sm text-ink/55">Courier</p>
                    <p class="font-semibold">{{ $order->courier_label }}</p>
                </div>
            @endif
            @if ($order->tracking_id)
                <div>
                    <p class="text-sm text-ink/55">Consignment ID</p>
                    <p class="font-semibold">{{ $order->tracking_id }}</p>
                </div>
            @endif
        </div>

        <h2 id="h-status" class="sr-only">Order progress</h2>

        <ol class="timeline mt-8">
            @foreach ($stepLabels as $status => $label)
                @php
                    $rank = $ranks[$status];
                    $state = $rank < $current || ($rank === $current && $order->status === 'delivered') ? 'done' : ($rank === $current && ! $final ? 'current' : ($rank === $current ? 'done' : ''));
                    $time = $timeOf($status);
                @endphp

                @continue($final && $rank > $current)

                <li class="{{ $state }}">
                    <span class="dot">
                        @if ($state === 'done')
                            <svg class="ic w-3.5 h-3.5" aria-hidden="true"><use href="#i-check" /></svg>
                        @endif
                    </span>
                    <div>
                        <b>{{ $label }}</b>
                        <small>{{ $time ? $time->format('d M Y, h:i A') : ($state === 'current' ? 'Now' : ($state === 'done' ? '' : 'Pending')) }}</small>
                    </div>
                </li>
            @endforeach

            @if ($final)
                <li class="stop">
                    <span class="dot"><svg class="ic w-3.5 h-3.5" aria-hidden="true"><use href="#i-x" /></svg></span>
                    <div>
                        <b>{{ $order->status_label }}</b>
                        <small>{{ $timeOf($order->status)?->format('d M Y, h:i A') }}</small>
                    </div>
                </li>
            @endif
        </ol>

        <div class="flex flex-wrap gap-3 mt-8">
            <form method="POST" action="{{ route('account.orders.reorder', $order->number) }}">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm">Order again</button>
            </form>
            <a href="{{ route('track.show', ['order' => $order->number]) }}" class="btn btn-ghost btn-sm">Track order</a>
            @if ($order->canRequestReturn())
                <a href="{{ route('account.returns.create', $order->number) }}" class="btn btn-ghost btn-sm">{{ $order->status === 'delivered' ? 'Return products' : 'Cancel / return products' }}</a>
            @endif
        </div>

    </section>

    <section class="card p-5 sm:p-7" aria-labelledby="h-items">

        <h2 id="h-items" class="font-display font-extrabold text-xl">Items</h2>

        <div class="space-y-4 mt-5">
            @foreach ($order->items as $item)
                <div class="flex items-center gap-3">
                    <img src="{{ $item->image }}" alt="" class="acc-thumb" loading="lazy">
                    <div class="min-w-0 flex-1">
                        @if ($item->product)
                            <a href="{{ $item->product->url }}" class="font-semibold text-sm leading-snug hover:text-primary">{{ $item->name }}</a>
                        @else
                            <p class="font-semibold text-sm leading-snug">{{ $item->name }}</p>
                        @endif
                        <p class="text-xs text-ink/50">{{ $item->variant_label ? $item->variant_label.' · ' : '' }}Qty {{ $item->quantity }} × ৳{{ number_format($item->unit_price) }}</p>
                    </div>
                    <b class="text-sm">৳{{ number_format($item->line_total) }}</b>
                </div>
            @endforeach
        </div>

        <dl class="acc-dl max-w-sm ml-auto mt-6">
            <div><dt>Subtotal</dt><dd>৳{{ number_format($order->subtotal) }}</dd></div>
            @if ($order->discount > 0)
                <div><dt>Coupon {{ $order->coupon_code }}</dt><dd class="text-success">-৳{{ number_format($order->discount) }}</dd></div>
            @endif
            <div><dt>Delivery ({{ $order->zone_label }})</dt><dd>{{ $order->delivery_fee > 0 ? '৳'.number_format($order->delivery_fee) : 'Free' }}</dd></div>
        </dl>
        <div class="acc-total max-w-sm ml-auto">
            <b class="font-display text-lg">Total</b>
            <b class="font-display font-extrabold text-2xl text-primary">৳{{ number_format($order->total) }}</b>
        </div>

    </section>

    <div class="grid md:grid-cols-2 gap-6">

        <section class="card p-5 sm:p-7" aria-labelledby="h-ship">
            <h2 id="h-ship" class="font-display font-extrabold text-xl">Delivery address</h2>
            <dl class="acc-dl">
                <div><dt>Name</dt><dd>{{ $order->name }}</dd></div>
                <div><dt>Mobile</dt><dd>{{ $order->phone }}</dd></div>
                <div><dt>Address</dt><dd>{{ $order->full_address }}</dd></div>
                @if ($order->note)
                    <div><dt>Note</dt><dd>{{ $order->note }}</dd></div>
                @endif
            </dl>
        </section>

        <section class="card p-5 sm:p-7" aria-labelledby="h-pay">
            <h2 id="h-pay" class="font-display font-extrabold text-xl">Payment</h2>
            <dl class="acc-dl">
                <div><dt>Method</dt><dd>{{ $order->payment_label }}</dd></div>
                <div><dt>Status</dt><dd>{{ ucfirst($order->payment_status) }}</dd></div>
                <div><dt>Total</dt><dd>৳{{ number_format($order->total) }}</dd></div>
            </dl>
        </section>

    </div>

@endsection
