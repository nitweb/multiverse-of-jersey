@extends('frontend.account.layout')

@php
    $storeName = $setting('store_name', 'Multiverse of Jersey');
    $selected = collect(old('items', []))->map(fn ($id) => (int) $id)->all();
@endphp

@section('frontend_title', 'Cancel / return products | '.$storeName)
@section('account_parent', true)
@section('account_title', $order->status === 'delivered' ? 'Return products' : 'Cancel / return products')
@section('account_sub', 'Order '.$order->number.' · choose what you want to send back or cancel.')

@section('account_body')

    <form method="POST" action="{{ route('account.returns.store', $order->number) }}" class="space-y-6" novalidate>
        @csrf

        <section class="card p-5 sm:p-7" aria-labelledby="h-pick">
            <h2 id="h-pick" class="font-display font-extrabold text-xl">Products</h2>
            <p class="err">{{ $errors->first('items') }}</p>

            <div class="mt-3">
                @foreach ($order->items as $item)
                    @php $available = $item->returnable_quantity; @endphp
                    <div class="acc-sel {{ $available < 1 ? 'is-off' : '' }}">
                        <input type="checkbox" name="items[]" value="{{ $item->id }}" id="item-{{ $item->id }}" @checked(in_array($item->id, $selected, true)) @disabled($available < 1)>
                        <img src="{{ $item->image }}" alt="" class="acc-thumb sm" loading="lazy">
                        <label for="item-{{ $item->id }}" class="flex-1 min-w-0">
                            <span class="block font-semibold text-sm leading-snug">{{ $item->name }}</span>
                            <span class="block text-xs text-ink/50">{{ $item->variant_label ? $item->variant_label.' · ' : '' }}৳{{ number_format($item->unit_price) }} each @if ($available < 1) · already requested @endif</span>
                        </label>
                        @if ($available > 0)
                            <div class="shrink-0">
                                <label for="qty-{{ $item->id }}" class="lbl text-xs">Qty</label>
                                <select class="input" name="qty[{{ $item->id }}]" id="qty-{{ $item->id }}">
                                    @for ($i = 1; $i <= $available; $i++)
                                        <option value="{{ $i }}" @selected((int) old("qty.{$item->id}", $available) === $i)>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>

        <section class="card p-5 sm:p-7" aria-labelledby="h-why">
            <h2 id="h-why" class="font-display font-extrabold text-xl">Reason</h2>

            <div class="space-y-4 mt-5">
                <div>
                    <label class="lbl" for="reason">Why are you sending it back? <span class="text-danger">*</span></label>
                    <select class="input @error('reason') invalid @enderror" id="reason" name="reason" required>
                        <option value="">Select a reason</option>
                        @foreach (\App\Models\OrderReturn::REASONS as $key => $label)
                            <option value="{{ $key }}" @selected(old('reason') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="err">{{ $errors->first('reason') }}</p>
                </div>

                <div>
                    <label class="lbl" for="details">More details (optional)</label>
                    <textarea class="input" id="details" name="details" rows="3" maxlength="500">{{ old('details') }}</textarea>
                    <p class="err">{{ $errors->first('details') }}</p>
                </div>
            </div>

            <p class="text-sm text-ink/55 mt-4">Payment is Cash on Delivery, so nothing is charged online. Our team reviews every request before it is approved.</p>

            <div class="flex flex-wrap gap-3 mt-5">
                <button type="submit" class="btn btn-primary">Send request</button>
                <a href="{{ route('account.orders.show', $order->number) }}" class="btn btn-ghost">Back</a>
            </div>
        </section>

    </form>

@endsection
