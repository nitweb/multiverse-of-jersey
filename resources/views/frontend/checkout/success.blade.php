@extends('frontend.dashboard')

@php
    $storeName = $setting('store_name', 'Multiverse of Jersey');
    $firstName = \Illuminate\Support\Str::before($order->name, ' ');
    $reached = match ($order->status) {
        'processing' => 2,
        'shipped', 'out_for_delivery' => 3,
        'delivered' => 4,
        default => 1,
    };
    $steps = ['Order placed', 'Confirmed', 'On the way', 'Delivered'];
@endphp

@section('frontend_title', 'Order placed | '.$storeName)
@section('title', 'Order placed | '.$storeName)
@section('meta_description', 'Your order has been placed.')

@push('frontend_styles')
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/pages/checkout.css') }}">
@endpush

@push('frontend_scripts')
    <script>
        document.addEventListener('click', function (e) {
            var b = e.target.closest('[data-copy-order]'); if (!b) return;
            try { navigator.clipboard.writeText(b.dataset.copyOrder); } catch (er) {}
            var t = b.querySelector('span'), old = t.textContent; t.textContent = 'Copied'; setTimeout(function () { t.textContent = old; }, 1600);
        });
    </script>
@endpush

@section('frontend_contents')

    <section class="section !pt-8">

        <div class="container-x max-w-5xl">

            <div class="os-hero">

                <div class="os-check">
                    <svg class="ic w-8 h-8" aria-hidden="true"><use href="#i-check" /></svg>
                </div>

                <h1>Thank you, {{ $firstName }}! Your order is placed.</h1>
                <p>We’ve received your order and will call {{ $order->phone }} shortly to confirm it. Keep your phone nearby.</p>

                <div class="os-number">
                    <span><small>Order number</small><br><b>{{ $order->number }}</b></span>
                    <button type="button" class="os-copy" data-copy-order="{{ $order->number }}">
                        <svg class="ic w-4 h-4" aria-hidden="true"><use href="#i-share" /></svg><span>Copy</span>
                    </button>
                </div>

            </div>

            <div class="os-stats">
                <div class="os-stat"><small>Date</small><b>{{ $order->created_at->format('d M Y') }}</b></div>
                <div class="os-stat"><small>Total</small><b>৳{{ number_format($order->total) }}</b></div>
                <div class="os-stat"><small>Payment</small><b>Cash on delivery</b></div>
                <div class="os-stat"><small>Estimated delivery</small><b>{{ $order->delivery_time ?: 'We’ll confirm by phone' }}</b></div>
            </div>

            <div class="card p-5 sm:p-7 mt-6">
                <div class="os-track" aria-label="Order progress">
                    @foreach ($steps as $label)
                        <div class="os-step {{ $loop->iteration <= $reached ? 'on' : '' }}">
                            <i>{!! $loop->iteration <= $reached ? '&#10003;' : $loop->iteration !!}</i>{{ $label }}
                        </div>
                    @endforeach
                </div>
            </div>

            @if (session('new_account') && ! auth()->check())
                <div class="os-note mt-6">
                    <svg class="ic w-5 h-5 shrink-0 text-primary mt-0.5" aria-hidden="true"><use href="#i-user" /></svg>
                    <p>We created an account for you. Sign in with <b>{{ $order->phone }}</b> and the password <b>{{ \App\Services\CheckoutService::startingPassword($order->phone) }}</b> to see all your orders. You can change the password after signing in.</p>
                </div>
            @endif

            <div class="grid lg:grid-cols-[1.4fr_1fr] gap-6 mt-6 items-start">

                <div class="card p-5 sm:p-7">

                    <h2 class="os-title"><svg class="ic" aria-hidden="true"><use href="#i-bag" /></svg> Order summary</h2>

                    <div class="mt-3">
                        @foreach ($order->items as $item)
                            <div class="os-item">
                                <img src="{{ $item->image }}" alt="">
                                <div class="min-w-0 flex-1">
                                    <p class="font-semibold text-sm leading-snug line-clamp-2">{{ $item->name }}</p>
                                    @if ($item->variant_label)
                                        <p class="text-xs text-ink/50 mt-0.5">{{ $item->variant_label }}</p>
                                    @endif
                                    <span class="q">৳{{ number_format($item->unit_price) }} × {{ $item->quantity }}</span>
                                </div>
                                <b class="text-sm">৳{{ number_format($item->line_total) }}</b>
                            </div>
                        @endforeach
                    </div>

                    <dl class="os-sum">
                        <div><dt>Subtotal</dt><dd class="font-semibold">৳{{ number_format($order->subtotal) }}</dd></div>
                        @if ($order->discount > 0)
                            <div><dt>Discount @if ($order->coupon_code)({{ $order->coupon_code }})@endif</dt><dd class="font-semibold text-success">-৳{{ number_format($order->discount) }}</dd></div>
                        @endif
                        <div><dt>Delivery @if ($order->zone_label)({{ $order->zone_label }})@endif</dt><dd class="font-semibold">{{ $order->delivery_fee > 0 ? '৳'.number_format($order->delivery_fee) : 'Free' }}</dd></div>
                        <div class="total"><dt class="font-bold">Total to pay</dt><dd>৳{{ number_format($order->total) }}</dd></div>
                    </dl>

                </div>

                <div class="space-y-6">

                    <div class="card p-5 sm:p-7">
                        <h2 class="os-title"><svg class="ic" aria-hidden="true"><use href="#i-pin" /></svg> Delivery details</h2>
                        <dl class="os-info">
                            <div><dt>Name</dt><dd>{{ $order->name }}</dd></div>
                            <div><dt>Phone</dt><dd>{{ $order->phone }}</dd></div>
                            <div><dt>Address</dt><dd>{{ $order->address }}, {{ $order->area }}, {{ $order->district }}</dd></div>
                            @if ($order->note)
                                <div><dt>Note</dt><dd>{{ $order->note }}</dd></div>
                            @endif
                        </dl>
                    </div>

                    <div class="card p-5 sm:p-7">
                        <h2 class="os-title"><svg class="ic" aria-hidden="true"><use href="#i-clock" /></svg> What happens next</h2>
                        <ol class="os-next">
                            <li>We call to confirm your order and details.</li>
                            <li>Your jersey is checked, packed and handed to the courier.</li>
                            <li>Pay cash to the courier when it arrives.</li>
                        </ol>
                    </div>

                </div>

            </div>

            <div class="os-actions flex flex-wrap gap-3 justify-center mt-8">
                <a href="{{ route('track.show') }}" class="btn btn-primary btn-lg">Track order</a>
                <a href="{{ route('checkout.invoice', $order->number) }}" class="btn btn-ghost btn-lg">View invoice</a>
                <a href="{{ route('shop') }}" class="btn btn-ghost btn-lg">Continue shopping</a>
            </div>

        </div>

    </section>

@endsection
