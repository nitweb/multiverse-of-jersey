@extends('frontend.dashboard')

@php
    $storeName = $setting('store_name', 'Multiverse of Jersey');
    $pageTitle = ($product->meta_title ?: $product->name).' | '.$storeName;
    $pageDescription = $product->meta_description ?: ($product->short_description ?: $product->name);
    $oldPrice = $product->display_old_price;
    $discount = $product->display_discount_percent;
    $whatsapp = preg_replace('/\D+/', '', (string) $setting('chat_whatsapp', ''));
    $returnDays = (int) $setting('return_days', 7);
    $freeAbove = (float) $setting('free_delivery_above', 0);
    $trackInventory = (bool) $product->track_inventory;
@endphp

@section('frontend_title', $pageTitle)
@section('title', $pageTitle)
@section('meta_description', $pageDescription)
@section('og_type', 'product')
@section('og_image', $product->cover_url)

@push('frontend_styles')
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/pages/product.css') }}">
@endpush

@push('frontend_scripts')
    <script src="{{ asset('assets/frontend/js/pages/product.js') }}" defer></script>
@endpush

@section('frontend_contents')

    <div class="container-x pt-6">

        <nav class="crumbs" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <svg class="ic w-3.5 h-3.5 opacity-50" aria-hidden="true"><use href="#i-chev-right" /></svg>
            <a href="{{ route('shop') }}">Shop</a>
            <svg class="ic w-3.5 h-3.5 opacity-50" aria-hidden="true"><use href="#i-chev-right" /></svg>
            @foreach ($trail as $node)
                <a href="{{ $node->url }}">{{ $node->name }}</a>
                <svg class="ic w-3.5 h-3.5 opacity-50" aria-hidden="true"><use href="#i-chev-right" /></svg>
            @endforeach
            <span aria-current="page">{{ $product->name }}</span>
        </nav>

    </div>

    <section class="section !pt-6" id="product" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->display_price }}" data-compare="{{ $oldPrice ?? 0 }}" data-image="{{ $gallery->first() ?? $product->cover_url }}" data-url="{{ $product->url }}" data-stock="{{ $product->stock_total }}" data-track="{{ $trackInventory ? 1 : 0 }}" data-checkout="{{ route('checkout.show') }}" data-cat="{{ $product->category?->slug }}" data-brand="{{ $product->brand?->name }}">

        <div class="container-x grid lg:grid-cols-2 gap-8 lg:gap-14">

            <div class="gallery">

                <div class="gallery-main">
                    <img id="main-img" src="{{ $gallery->first() ?? $product->cover_url }}" alt="{{ $product->name }}">
                    @if ($discount > 0)
                        <span class="badge bg-accent text-white absolute top-4 left-4">-{{ $discount }}%</span>
                    @endif
                </div>

                @if ($gallery->count() > 1)
                    <div class="grid grid-cols-4 gap-3 mt-3">
                        @foreach ($gallery as $url)
                            <button type="button" class="thumb {{ $loop->first ? 'is-active' : '' }}" data-thumb="{{ $url }}" aria-label="View image {{ $loop->iteration }}">
                                <img src="{{ $url }}" alt="">
                            </button>
                        @endforeach
                    </div>
                @endif

            </div>

            <div>

                <p class="text-xs font-bold uppercase tracking-[.16em] text-primary">
                    {{ collect([$product->category?->name, $product->brand?->name])->filter()->implode(' · ') }}
                </p>

                <h1 class="font-display font-extrabold text-2xl sm:text-4xl tracking-tight mt-2">{{ $product->name }}</h1>

                <div class="flex items-center gap-3 mt-3 text-sm" id="stock-line">
                    @if ($product->in_stock)
                        <span class="text-success font-semibold flex items-center gap-1">
                            <svg class="ic w-4 h-4" aria-hidden="true"><use href="#i-check" /></svg>
                            <span data-stock-text>{{ $product->is_low_stock ? 'Only '.$product->stock_total.' left' : 'In stock' }}</span>
                        </span>
                    @else
                        <span class="text-danger font-semibold" data-stock-text>Out of stock</span>
                    @endif
                </div>

                <div class="flex items-end gap-3 mt-5">
                    <b id="price" class="font-display font-extrabold text-3xl">৳{{ number_format($product->display_price) }}</b>
                    @if ($oldPrice)
                        <s class="text-ink/40 text-lg" id="old-price">৳{{ number_format($oldPrice) }}</s>
                        <span class="badge bg-accent/10 text-accent" id="save-badge">Save ৳{{ number_format($oldPrice - $product->display_price) }}</span>
                    @endif
                </div>

                @if ($product->short_description)
                    <p class="mt-5 text-ink/70">{{ $product->short_description }}</p>
                @endif

                @if ($sizes->isNotEmpty())
                    <div class="mt-6">

                        <div class="flex items-center justify-between mb-2">
                            <p class="font-semibold text-sm">Size <span class="text-danger" id="size-err" hidden>— please select a size</span></p>
                            <button type="button" class="link text-sm flex items-center gap-1" data-open-tab="size">
                                <svg class="ic w-4 h-4" aria-hidden="true"><use href="#i-ruler" /></svg> Size guide
                            </button>
                        </div>

                        <div class="flex flex-wrap gap-2" id="sizes">
                            @foreach ($sizes as $size)
                                <label class="size-chip lg">
                                    <input type="radio" name="size" value="{{ $size }}">
                                    <span>{{ $size }}</span>
                                </label>
                            @endforeach
                        </div>

                    </div>
                @endif

                @if ($colors->isNotEmpty())
                    <div class="mt-6">

                        <p class="font-semibold text-sm mb-2">Color <span class="text-danger" id="color-err" hidden>— please select a color</span></p>

                        <div class="flex flex-wrap gap-2" id="colors">
                            @foreach ($colors as $color)
                                <label class="size-chip lg">
                                    <input type="radio" name="color" value="{{ $color }}">
                                    <span>{{ $color }}</span>
                                </label>
                            @endforeach
                        </div>

                    </div>
                @endif

                <div class="custom card p-5 mt-6 space-y-4" id="custom">

                    <label class="chk !items-start !py-0">
                        <input type="checkbox" id="opt-patch" data-price="200">
                        <span>
                            <b>Official UCL Patch</b> <span class="text-ink/50">(Optional)</span>
                            <small class="block text-ink/60 text-[13px] !ml-0 mt-0.5">UCL 5 Star + UEFA Foundation for Children <b class="text-primary">(+৳200)</b></small>
                        </span>
                    </label>

                    <label class="chk !items-start !py-0">
                        <input type="checkbox" id="opt-custom" data-price="250">
                        <span>
                            <b>Jersey Personalization</b> <span class="text-ink/50">(Optional)</span>
                            <small class="block text-ink/60 text-[13px] !ml-0 mt-0.5">Custom name &amp; number <b class="text-primary">(+৳250)</b></small>
                        </span>
                    </label>

                    <div id="custom-fields" class="grid sm:grid-cols-[1fr_130px] gap-4" hidden>

                        <div>
                            <label class="lbl" for="c-name">Custom name</label>
                            <input class="input uppercase" id="c-name" maxlength="20" placeholder="e.g. LAMINE YAMAL">
                            <p class="err" id="c-name-err"></p>
                        </div>

                        <div>
                            <label class="lbl" for="c-num">Number at back</label>
                            <input class="input" id="c-num" type="number" min="0" max="99" placeholder="0–99">
                            <p class="err" id="c-num-err"></p>
                        </div>

                        <p class="sm:col-span-2 text-[13px] leading-relaxed rounded-xl bg-accent-soft text-ink/80 p-3">
                            Please make <b>৳250 advance</b> via bKash Merchant: <b>01723821714</b> to confirm customization. Our team will contact you to confirm the details and update the final price if needed. Important: please double-check your jersey size and customization details before placing the order.
                        </p>

                    </div>

                </div>

                <div class="flex flex-wrap items-center gap-3 mt-6">

                    <div class="qty" data-qty>
                        <button type="button" data-qty-minus aria-label="Decrease"><svg class="ic w-4 h-4" aria-hidden="true"><use href="#i-minus" /></svg></button>
                        <input type="number" value="1" min="1" max="10" aria-label="Quantity" id="qty">
                        <button type="button" data-qty-plus aria-label="Increase"><svg class="ic w-4 h-4" aria-hidden="true"><use href="#i-plus" /></svg></button>
                    </div>

                    <button type="button" id="add-cart" class="btn btn-primary btn-lg flex-1 min-w-[180px]" @disabled(! $product->in_stock)>
                        <svg class="ic " aria-hidden="true"><use href="#i-bag" /></svg> {{ $product->in_stock ? 'Add to cart' : 'Out of stock' }}
                    </button>

                    <button type="button" class="btn btn-ghost btn-lg !px-4 wish-big" data-wish aria-label="Add to wishlist" aria-pressed="{{ in_array($product->id, $wishlistIds ?? [], true) ? 'true' : 'false' }}">
                        <svg class="ic " aria-hidden="true"><use href="#i-heart" /></svg>
                    </button>

                </div>

                @if ($product->in_stock)
                    @if ($product->is_cod)
                        <button type="button" id="buy-now" class="btn btn-accent btn-lg w-full mt-3">Buy now — Cash on delivery</button>
                    @else
                        <button type="button" id="buy-now" class="btn btn-accent btn-lg w-full mt-3">Buy now</button>
                    @endif
                @endif

                @if ($whatsapp !== '')
                    <a href="https://wa.me/{{ $whatsapp }}?text={{ rawurlencode('I want to order '.$product->name) }}" target="_blank" rel="noopener" class="btn btn-ghost w-full mt-3">
                        <svg class="ic w-5 h-5" aria-hidden="true"><use href="#i-chat" /></svg> Order via WhatsApp
                    </a>
                @endif

                <div class="grid gap-4 mt-8 p-5 rounded-2xl bg-bg border border-line">

                    <div class="flex gap-3 items-start">
                        <span class="trust-ic !w-10 !h-10"><svg class="ic " aria-hidden="true"><use href="#i-truck" /></svg></span>
                        <div class="text-sm">
                            <b class="block">{{ $product->is_free_delivery ? 'Free delivery' : 'Delivery all over Bangladesh' }}</b>
                            @if ($product->is_free_delivery)
                                <span class="text-ink/55">Delivery charge is waived on this item</span>
                            @elseif ($zones->isNotEmpty())
                                <span class="text-ink/55">{{ $zones->map(fn ($zone) => $zone['label'].' ৳'.number_format($zone['fee']))->implode(' · ') }}</span>
                            @endif
                        </div>
                    </div>

                    @if ($product->is_cod)
                        <div class="flex gap-3 items-start">
                            <span class="trust-ic !w-10 !h-10"><svg class="ic " aria-hidden="true"><use href="#i-package" /></svg></span>
                            <div class="text-sm">
                                <b class="block">Cash on delivery</b>
                                <span class="text-ink/55">Check the jersey, then pay the courier</span>
                            </div>
                        </div>
                    @endif

                    <div class="flex gap-3 items-start">
                        <span class="trust-ic !w-10 !h-10"><svg class="ic " aria-hidden="true"><use href="#i-return" /></svg></span>
                        <div class="text-sm">
                            @if ($product->is_returnable)
                                <b class="block">{{ $returnDays }}-day size exchange</b>
                                <span class="text-ink/55">Unused with tags intact</span>
                            @else
                                <b class="block">Non-returnable item</b>
                                <span class="text-ink/55">This item cannot be returned or exchanged</span>
                            @endif
                        </div>
                    </div>

                </div>

                <dl class="mt-6 text-sm grid grid-cols-[110px_1fr] gap-y-2">

                    @if ($product->sku)
                        <dt class="text-ink/50">SKU</dt>
                        <dd id="sku" data-base="{{ $product->sku }}">{{ $product->sku }}</dd>
                    @endif

                    @if ($product->category)
                        <dt class="text-ink/50">Category</dt>
                        <dd><a class="link" href="{{ $product->category->url }}">{{ $product->category->name }}</a></dd>
                    @endif

                    @if ($product->brand)
                        <dt class="text-ink/50">Brand</dt>
                        <dd><a class="link" href="{{ $product->brand->url }}">{{ $product->brand->name }}</a></dd>
                    @endif

                    @if ($product->tag_list)
                        <dt class="text-ink/50">Tags</dt>
                        <dd>{{ implode(', ', $product->tag_list) }}</dd>
                    @endif

                    <dt class="text-ink/50">Share</dt>
                    <dd class="flex gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ rawurlencode($product->url) }}" target="_blank" rel="noopener" class="soc-sm" aria-label="Facebook">
                            <svg class="ic w-4 h-4" aria-hidden="true"><use href="#i-facebook" /></svg>
                        </a>
                        <button type="button" class="soc-sm" data-copy-link aria-label="Copy link">
                            <svg class="ic w-4 h-4" aria-hidden="true"><use href="#i-share" /></svg>
                        </button>
                    </dd>

                </dl>

            </div>

        </div>

        <script type="application/json" id="variants-data">@json($variants)</script>

    </section>

    <section class="section !pt-0">

        <div class="container-x">

            <div class="tabs" data-tabs-wrap>

                <div class="tab-list" role="tablist">
                    <button type="button" class="tab is-active" data-tab-btn="desc" role="tab">Description</button>
                    <button type="button" class="tab" data-tab-btn="size" role="tab">Size guide</button>
                    <button type="button" class="tab" data-tab-btn="ship" role="tab">Delivery &amp; returns</button>
                </div>

                <div class="tab-panel card p-6 sm:p-8 is-active" data-tab-panel="desc">
                    <div class="prose-lite">
                        @if (filled($product->description))
                            {!! $product->description !!}
                        @elseif ($product->short_description)
                            <p>{{ $product->short_description }}</p>
                        @else
                            <p>No description has been added for this product yet.</p>
                        @endif
                    </div>
                </div>

                <div class="tab-panel card p-6 sm:p-8" data-tab-panel="size" hidden>
                    <p class="text-sm text-ink/60 mb-4">Measurements in cm. Player edition runs slim — size up if you prefer a relaxed fit.</p>
                    <div class="overflow-x-auto">
                        <table class="tbl">
                            <thead>
                                <tr><th>Size</th><th>Chest</th><th>Length</th><th>Height</th></tr>
                            </thead>
                            <tbody>
                                <tr><td>S</td><td>50</td><td>70</td><td>165–170</td></tr>
                                <tr><td>M</td><td>52</td><td>72</td><td>170–175</td></tr>
                                <tr><td>L</td><td>54</td><td>74</td><td>175–180</td></tr>
                                <tr><td>XL</td><td>56</td><td>76</td><td>180–185</td></tr>
                                <tr><td>XXL</td><td>58</td><td>78</td><td>185–190</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-panel card p-6 sm:p-8" data-tab-panel="ship" hidden>
                    <div class="prose-lite">
                        @if ($zones->isNotEmpty())
                            <p>
                                @foreach ($zones as $zone)
                                    {{ $zone['label'] }}: ৳{{ number_format($zone['fee']) }}@if ($zone['time']) ({{ $zone['time'] }})@endif{{ $loop->last ? '.' : ' · ' }}
                                @endforeach
                                @if ($freeAbove > 0)
                                    Delivery is free above ৳{{ number_format($freeAbove) }}.
                                @endif
                            </p>
                        @endif
                        <p>
                            @if ($product->is_returnable)
                                Unused jerseys with tags can be exchanged within {{ $returnDays }} days of delivery.
                            @else
                                This item is non-returnable.
                            @endif
                        </p>
                    </div>
                </div>

            </div>

        </div>

    </section>

    @if ($related->isNotEmpty())
        <section class="section !pt-0">

            <div class="container-x">

                <div class="sec-head">
                    <div>
                        <p class="eyebrow">You may also like</p>
                        <h2>Related jerseys</h2>
                    </div>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
                    @foreach ($related as $item)
                        @include('frontend.home._product_card', ['product' => $item])
                    @endforeach
                </div>

            </div>

        </section>
    @endif

    <div class="buy-bar" data-buy-bar>
        <div class="min-w-0">
            <p class="text-xs text-ink/55 truncate">{{ $product->name }}</p>
            <b class="font-display font-extrabold" data-bar-price>৳{{ number_format($product->display_price) }}</b>
        </div>
        <button type="button" class="btn btn-primary" data-sticky-add @disabled(! $product->in_stock)>Add to cart</button>
    </div>

@endsection
