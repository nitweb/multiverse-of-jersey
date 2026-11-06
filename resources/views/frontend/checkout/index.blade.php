@extends('frontend.dashboard')

@php
    $storeName = $setting('store_name', 'Multiverse of Jersey');
    $returnDays = (int) $setting('return_days', 7);
    $oldDistrict = old('district', $prefill?->district);
    $oldDivision = old('division');

    if (! $oldDivision && $oldDistrict) {
        foreach ($divisions as $divisionName => $districtList) {
            if (in_array($oldDistrict, $districtList, true)) {
                $oldDivision = $divisionName;
            }
        }
    }

    $zoneLabels = collect($cartSummary['zones'])->keyBy('key');
    $zoneHints = [
        'inside_dhaka' => 'Dhaka city',
        'nearby_dhaka' => 'Gazipur, Narayanganj, Narsingdi, Munshiganj, Manikganj',
        'outside_dhaka' => 'All other districts',
    ];
@endphp

@section('frontend_title', 'Checkout | '.$storeName)
@section('title', 'Checkout | '.$storeName)
@section('meta_description', 'Enter your delivery details and pay in cash when your order arrives.')

@push('frontend_styles')
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/pages/checkout.css') }}">
@endpush

@push('frontend_scripts')
    <script>
        window.JBD_CHECKOUT = {
            divisions: @json($divisions),
            zoneOf: @json($zoneOfDistrict),
            prefillDivision: @json($oldDivision),
            prefillDistrict: @json($oldDistrict)
        };
    </script>
    <script src="{{ asset('assets/frontend/js/pages/summary.js') }}" defer></script>
    <script src="{{ asset('assets/frontend/js/pages/checkout.js') }}" defer></script>
@endpush

@section('frontend_contents')

    <section class="page-head">

        <div class="container-x">

            <nav class="crumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <svg class="ic w-3.5 h-3.5 opacity-50" aria-hidden="true"><use href="#i-chev-right" /></svg>
                <a href="{{ route('cart.index') }}">Cart</a>
                <svg class="ic w-3.5 h-3.5 opacity-50" aria-hidden="true"><use href="#i-chev-right" /></svg>
                <span aria-current="page">Checkout</span>
            </nav>

            <h1 class="font-display font-extrabold text-3xl sm:text-4xl tracking-tight mt-3">Checkout</h1>

            <ol class="steps">
                <li class="done"><span>1</span>Cart</li>
                <li class="current"><span>2</span>Checkout</li>
                <li><span>3</span>Confirmation</li>
            </ol>

        </div>

    </section>

    <section class="section !pt-8">

        <div class="container-x">

            @if ($errors->has('cart'))
                <p class="rounded-xl bg-accent-soft text-danger font-semibold text-sm px-4 py-3 mb-5" role="alert">{{ $errors->first('cart') }}</p>
            @endif

            <div id="cart-notices" class="mb-5 space-y-2" role="status"></div>

            <div class="grid lg:grid-cols-[1fr_400px] gap-8 items-start">

                <form id="checkout-form" class="space-y-6" method="POST" action="{{ route('checkout.store') }}" novalidate>
                    @csrf

                    <section class="card p-5 sm:p-6">

                        <h2 class="co-h"><span>1</span>Contact details</h2>

                        @if ($addresses->count() > 1)
                            <div class="mt-5">
                                <label class="lbl" for="saved-address">Deliver to a saved address</label>
                                <select class="input" id="saved-address">
                                    @foreach ($addresses as $saved)
                                        <option value="{{ $saved->id }}" @selected($prefill && $prefill->id === $saved->id) data-name="{{ $saved->name }}" data-phone="{{ $saved->phone }}" data-district="{{ $saved->district }}" data-area="{{ $saved->area }}" data-address="{{ $saved->address }}">{{ $saved->label }}: {{ $saved->area }}, {{ $saved->district }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="grid sm:grid-cols-2 gap-4 mt-5">

                            <div>
                                <label class="lbl" for="name">Full name <span class="text-danger">*</span></label>
                                <input class="input" id="name" name="name" type="text" placeholder="Your full name" required maxlength="100" autocomplete="name" value="{{ old('name', $user?->name) }}">
                                <p class="err" data-err="name">{{ $errors->first('name') }}</p>
                            </div>

                            <div>
                                <label class="lbl" for="phone">Phone <span class="text-danger">*</span></label>
                                <input class="input" id="phone" name="phone" type="tel" placeholder="01XXXXXXXXX" required maxlength="14" autocomplete="tel" inputmode="numeric" value="{{ old('phone', $user?->phone) }}">
                                <p class="err" data-err="phone">{{ $errors->first('phone') }}</p>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="lbl" for="email">Email <span class="text-danger">*</span></label>
                                <input class="input" id="email" name="email" type="email" placeholder="you@example.com" required maxlength="150" autocomplete="email" value="{{ old('email', $user?->email) }}">
                                <p class="err" data-err="email">{{ $errors->first('email') }}</p>
                            </div>

                        </div>

                    </section>

                    <section class="card p-5 sm:p-6">

                        <h2 class="co-h"><span>2</span>Delivery address</h2>

                        <div class="grid sm:grid-cols-2 gap-4 mt-5">

                            <div>
                                <label class="lbl" for="division">Division <span class="text-danger">*</span></label>
                                <select class="input" id="division" name="division" required>
                                    <option value="">Select division</option>
                                    @foreach ($divisions as $divisionName => $districtList)
                                        <option value="{{ $divisionName }}" @selected($oldDivision === $divisionName)>{{ $divisionName }}</option>
                                    @endforeach
                                </select>
                                <p class="err" data-err="division"></p>
                            </div>

                            <div>
                                <label class="lbl" for="district">District <span class="text-danger">*</span></label>
                                <select class="input" id="district" name="district" required disabled>
                                    <option value="">Select district</option>
                                </select>
                                <p class="err" data-err="district">{{ $errors->first('district') }}</p>
                            </div>

                            <div>
                                <label class="lbl" for="thana">Thana / Area <span class="text-danger">*</span></label>
                                <input class="input" id="thana" name="area_txt" type="text" placeholder="e.g. Dhanmondi" required maxlength="120" value="{{ old('area_txt', old('area', $prefill?->area)) }}">
                                <p class="err" data-err="thana">{{ $errors->first('area') }}</p>
                            </div>

                            <div>
                                <label class="lbl" for="postcode">Postcode</label>
                                <input class="input" id="postcode" name="postcode" type="text" placeholder="e.g. 1209" inputmode="numeric" maxlength="10" value="{{ old('postcode') }}">
                                <p class="err" data-err="postcode">{{ $errors->first('postcode') }}</p>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="lbl" for="address">Full address <span class="text-danger">*</span></label>
                                <textarea class="input" id="address" name="address" rows="2" required maxlength="500" placeholder="House, road, area">{{ old('address', $prefill?->address) }}</textarea>
                                <p class="err" data-err="address">{{ $errors->first('address') }}</p>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="lbl" for="note">Order note <span class="text-ink/40 font-normal">(optional)</span></label>
                                <textarea class="input" id="note" name="note" rows="2" maxlength="500" placeholder="Anything we should know?">{{ old('note') }}</textarea>
                                <p class="err" data-err="note">{{ $errors->first('note') }}</p>
                            </div>

                        </div>

                    </section>

                    <section class="card p-5 sm:p-6">

                        <h2 class="co-h"><span>3</span>Delivery method</h2>

                        <div class="grid gap-3 mt-5" id="zones">
                            @foreach ($cartSummary['zones'] as $zone)
                                <label class="opt">
                                    <input type="radio" name="zone" value="{{ $zone['key'] }}" data-charge="{{ $zone['fee'] }}" @checked($cartSummary['shipping']['zone'] === $zone['key']) disabled>
                                    <span class="opt-t">
                                        <b>{{ $zone['label'] }}</b>
                                        <small>{{ $zoneHints[$zone['key']] ?? '' }}@if ($zone['time']) · {{ $zone['time'] }}@endif</small>
                                    </span>
                                    <b class="font-display">৳{{ number_format($zone['fee']) }}</b>
                                </label>
                            @endforeach
                        </div>

                        <p class="text-xs text-ink/55 mt-3" id="zone-msg">Select your district to see the delivery charge.</p>

                    </section>

                    <section class="card p-5 sm:p-6">

                        <h2 class="co-h"><span>4</span>Payment</h2>

                        <div class="grid gap-3 mt-5">

                            <label class="opt is-on">
                                <input type="radio" name="pay" value="cod" checked>
                                <span class="opt-t">
                                    <b>Cash on delivery</b>
                                    <small>Pay in cash when the courier hands you your order.</small>
                                </span>
                                <svg class="ic w-5 h-5 text-primary" aria-hidden="true"><use href="#i-check" /></svg>
                            </label>

                            <label class="opt is-off">
                                <input type="radio" name="payment" value="bkash" disabled>
                                <span class="opt-t">
                                    <b>bKash / Nagad</b>
                                    <small>Currently unavailable.</small>
                                </span>
                                <span class="badge bg-bg text-ink/50">Soon</span>
                            </label>

                        </div>

                    </section>

                    <label class="chk !items-start">
                        <input type="checkbox" name="terms" value="1" required @checked(old('terms'))>
                        <span class="text-sm">I agree to the <a href="{{ route('terms.show') }}" class="link">terms &amp; conditions</a> and the {{ $returnDays }}-day return policy.</span>
                    </label>
                    <p class="err" data-err="terms">{{ $errors->first('terms') }}</p>

                    <button type="submit" class="btn btn-primary btn-lg w-full lg:hidden" data-place-order>Place order</button>

                </form>

                <aside class="card p-5 sm:p-6 lg:sticky lg:top-28 h-fit">

                    <h2 class="font-display font-extrabold text-lg">Your order</h2>

                    <div class="mt-4 space-y-4 max-h-[320px] overflow-y-auto pr-1 empty:hidden" data-sum-items></div>

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

                    <button type="submit" form="checkout-form" class="btn btn-primary btn-lg w-full mt-5 hidden lg:inline-flex" data-place-order>
                        <svg class="ic w-4 h-4" aria-hidden="true"><use href="#i-lock" /></svg> Place order
                    </button>

                    <p class="text-xs text-ink/50 text-center mt-3">
                        <svg class="ic w-3.5 h-3.5 inline" aria-hidden="true"><use href="#i-shield" /></svg> Your details are safe with us
                    </p>

                </aside>

            </div>

        </div>

    </section>

@endsection
