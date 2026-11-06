@extends('frontend.dashboard')

@php
    use App\Models\Order;
    use App\Models\Setting;

    $storeName = $setting('store_name', 'Multiverse of Jersey');
    $tz = Setting::tz();

    if ($order) {
        $ranks = ['pending' => 1, 'processing' => 2, 'shipped' => 3, 'out_for_delivery' => 4, 'delivered' => 5];
        $stepLabels = [
            'pending' => 'Order placed',
            'processing' => 'Processing / packed',
            'shipped' => 'Handed to courier',
            'out_for_delivery' => 'Out for delivery',
            'delivered' => 'Delivered',
        ];
        $final = in_array($order->status, Order::FINAL_STATUSES, true);
        $reachedStatuses = $order->events->pluck('status')->push($order->status)->unique();
        $current = $final
            ? $reachedStatuses->map(fn ($status) => $ranks[$status] ?? 0)->max()
            : ($ranks[$order->status] ?? 1);
        $timeOf = fn (string $status) => $order->events->firstWhere('status', $status)?->created_at?->timezone($tz);
    }
@endphp

@section('frontend_title', 'Track your order | '.$storeName)
@section('title', 'Track your order | '.$storeName)
@section('meta_description', 'Enter your order number and phone number to see where your order is.')

@push('frontend_styles')
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/pages/checkout.css') }}">
@endpush

@section('frontend_contents')

    <section class="page-head">

        <div class="container-x">

            <nav class="crumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <svg class="ic w-3.5 h-3.5 opacity-50" aria-hidden="true"><use href="#i-chev-right" /></svg>
                <span aria-current="page">Track order</span>
            </nav>

            <h1 class="font-display font-extrabold text-3xl sm:text-4xl tracking-tight mt-3">Track your order</h1>
            <p class="text-ink/60 mt-2 max-w-2xl">Enter your order number and the phone number used at checkout.</p>

        </div>

    </section>

    <section class="section !pt-8">

        <div class="container-x max-w-3xl">

            <form id="track-form" class="card p-5 sm:p-6 grid sm:grid-cols-[1fr_1fr_auto] gap-4 items-end" method="POST" action="{{ route('track.lookup') }}" novalidate>
                @csrf

                <div>
                    <label class="lbl" for="order">Order number</label>
                    <input class="input" id="order" name="order_number" placeholder="MOJ-10001" required maxlength="30" value="{{ old('order_number', $prefill) }}">
                </div>

                <div>
                    <label class="lbl" for="tphone">Phone number</label>
                    <input class="input" id="tphone" name="phone" type="tel" placeholder="01XXXXXXXXX" required maxlength="20" inputmode="numeric" value="{{ old('phone') }}">
                </div>

                <button class="btn btn-primary btn-lg" type="submit">Track</button>

                <p class="err sm:col-span-3" id="track-err">
                    @if ($errors->any())
                        {{ $errors->first() }}
                    @elseif ($searched && ! $order)
                        We couldn’t find an order with that number and phone. Please check both and try again.
                    @endif
                </p>

            </form>

            @if ($order)
                <div id="track-result" class="card p-5 sm:p-8 mt-6">

                    <div class="flex flex-wrap justify-between gap-4">

                        <div>
                            <p class="text-sm text-ink/55">Order</p>
                            <p class="font-display font-extrabold text-xl">{{ $order->number }}</p>
                        </div>

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

                </div>
            @endif

        </div>

    </section>

@endsection
