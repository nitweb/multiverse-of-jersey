@if ($newProducts->isNotEmpty())

    <section class="section">

        <div class="container-x">

            <div class="sec-head">
                <div>
                    <p class="eyebrow">Just landed</p>
                    <h2>New arrivals</h2>
                </div>
                <a href="{{ route('shop') }}" class="link-arrow">
                    View all jerseys
                    <svg class="ic w-4 h-4" aria-hidden="true">
                        <use href="#i-arrow-right" />
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">

                @foreach ($newProducts as $product)
                    @php
                        $frontImage = $product->front_image_url;
                        $backImage = $product->back_image_url;
                        $oldPrice = $product->display_old_price;
                        $discount = $product->display_discount_percent;
                        $rating = (int) round((float) ($product->reviews_avg_rating ?? 0));
                        $sizes = $product->variants->pluck('size')->filter()->unique()->implode(',');
                    @endphp

                    <article class="pcard group" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->display_price }}" data-compare="{{ $oldPrice ?? 0 }}" data-image="{{ $frontImage }}" data-url="{{ $product->url }}" data-cat="{{ $product->category?->slug }}" data-brand="{{ $product->brand?->name }}" data-sizes="{{ $sizes ?: 'S,M,L,XL,XXL' }}" data-stock="{{ $product->stock_total }}" data-added="{{ $product->id }}" data-sold="{{ $product->sold_count }}">

                        <div class="pcard-img">

                            <a href="{{ $product->url }}" aria-label="{{ $product->name }}">
                                <img src="{{ $frontImage }}" alt="{{ $product->name }}" loading="lazy" class="pcard-i1">
                                @if ($backImage)
                                    <img src="{{ $backImage }}" alt="" loading="lazy" class="pcard-i2">
                                @endif
                            </a>

                            <div class="pcard-badges">
                                @if ($discount > 0)
                                    <span class="badge bg-accent text-white">-{{ $discount }}%</span>
                                @endif
                                <span class="badge bg-ink text-bg">New</span>
                            </div>

                            <button type="button" class="wish-btn" data-wish aria-label="Add to wishlist" aria-pressed="false">
                                <svg class="ic " aria-hidden="true">
                                    <use href="#i-heart" />
                                </svg>
                            </button>

                        </div>

                        <div class="pcard-body">

                            <p class="text-[11px] font-bold uppercase tracking-[.14em] text-ink/45">
                                {{ collect([$product->category?->name, $product->brand?->name])->filter()->implode(' · ') }}
                            </p>

                            <h3 class="font-display font-bold text-[15px] leading-snug mt-1 line-clamp-2">
                                <a href="{{ $product->url }}" class="hover:text-primary">{{ $product->name }}</a>
                            </h3>

                            @if ($product->reviews_count > 0)
                                <div class="flex items-center gap-1.5 mt-1.5 text-xs text-ink/55">

                                    <span class="inline-flex" aria-label="Rated {{ number_format((float) $product->reviews_avg_rating, 1) }} out of 5">
                                        @for ($star = 1; $star <= 5; $star++)
                                            <svg class="w-3.5 h-3.5 {{ $star <= $rating ? 'star-on' : 'star-off' }}" viewBox="0 0 24 24" aria-hidden="true">
                                                <use href="#i-star" />
                                            </svg>
                                        @endfor
                                    </span>

                                    <span>({{ $product->reviews_count }})</span>

                                </div>
                            @endif

                            <div class="flex items-end justify-between gap-2 mt-3">

                                <p class="leading-none">
                                    <b class="font-display font-extrabold text-lg">৳{{ number_format($product->display_price) }}</b>
                                    @if ($oldPrice)
                                        <s class="ml-1.5 text-xs text-ink/40 font-medium">৳{{ number_format($oldPrice) }}</s>
                                    @endif
                                </p>

                                <button type="button" class="btn-add" data-quick-add aria-label="Add {{ $product->name }} to cart">
                                    <svg class="ic w-4 h-4" aria-hidden="true">
                                        <use href="#i-bag" />
                                    </svg>
                                    <span>Add</span>
                                </button>

                            </div>

                        </div>

                    </article>
                @endforeach

            </div>

        </div>

    </section>

@endif
