@extends('frontend.dashboard')
@section('frontend_title', 'Multiverse of Jersey — Authentic football jerseys in Bangladesh')
@section('frontend_contents')

    @include('frontend.home.01_hero')

    @include('frontend.home.02_feature_highlights')

    @include('frontend.home.03_shop_by_category')

    @include('frontend.home.04_flash_deals')

    @include('frontend.home.05_new_arrivals')

    @include('frontend.home.06_promo')

    <section class="section !pt-0">
        <div class="container-x">
            <div class="sec-head">
                <div>
                    <p class="eyebrow">Fan favourites</p>
                    <h2>Best sellers</h2>
                </div><a href="shop.html" class="link-arrow">Shop all <svg class="ic w-4 h-4" aria-hidden="true">
                        <use href="#i-arrow-right" />
                    </svg></a>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
                <article class="pcard group" data-id="5" data-name="Player Edition Red Home 24/25" data-price="4450" data-compare="5200" data-image="assets/images/products/player-edition-red-home-24-25-1.svg" data-url="product.html" data-cat="player-edition" data-brand="Nike" data-sizes="S,M,L,XL,XXL" data-stock="48" data-added="5" data-sold="182">
                    <div class="pcard-img"><a href="product.html" aria-label="Player Edition Red Home 24/25"><img src="assets/images/products/player-edition-red-home-24-25-1.svg" alt="Player Edition Red Home 24/25" loading="lazy" class="pcard-i1"><img src="assets/images/products/player-edition-red-home-24-25-2.svg" alt="" loading="lazy" class="pcard-i2"></a>
                        <div class="pcard-badges"><span class="badge bg-accent text-white">-14%</span><span class="badge bg-ink text-bg">Player</span></div><button type="button" class="wish-btn" data-wish aria-label="Add to wishlist" aria-pressed="false"><svg class="ic " aria-hidden="true">
                                <use href="#i-heart" />
                            </svg></button>
                    </div>
                    <div class="pcard-body">
                        <p class="text-[11px] font-bold uppercase tracking-[.14em] text-ink/45">Player Edition · Nike</p>
                        <h3 class="font-display font-bold text-[15px] leading-snug mt-1 line-clamp-2"><a href="product.html" class="hover:text-primary">Player Edition Red Home 24/25</a></h3>
                        <div class="flex items-center gap-1.5 mt-1.5 text-xs text-ink/55"><span class="inline-flex" aria-label="Rated 4.8 out of 5"><svg class="w-3.5 h-3.5 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                    <use href="#i-star" />
                                </svg><svg class="w-3.5 h-3.5 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                    <use href="#i-star" />
                                </svg><svg class="w-3.5 h-3.5 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                    <use href="#i-star" />
                                </svg><svg class="w-3.5 h-3.5 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                    <use href="#i-star" />
                                </svg><svg class="w-3.5 h-3.5 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                    <use href="#i-star" />
                                </svg></span><span>(24)</span></div>
                        <div class="flex items-end justify-between gap-2 mt-3">
                            <p class="leading-none"><b class="font-display font-extrabold text-lg">৳4,450</b><s class="ml-1.5 text-xs text-ink/40 font-medium">৳5,200</s></p><button type="button" class="btn-add" data-quick-add aria-label="Add Player Edition Red Home 24/25 to cart"><svg class="ic w-4 h-4" aria-hidden="true">
                                    <use href="#i-bag" />
                                </svg><span>Add</span></button>
                        </div>
                    </div>
                </article>
                <article class="pcard group" data-id="10" data-name="Green &amp; White Hoops Retro 1996" data-price="3300" data-compare="0" data-image="assets/images/products/green-white-hoops-retro-1996-1.svg" data-url="product.html" data-cat="retro" data-brand="Umbro" data-sizes="S,M,L,XL,XXL" data-stock="48" data-added="10" data-sold="182">
                    <div class="pcard-img"><a href="product.html" aria-label="Green &amp; White Hoops Retro 1996"><img src="assets/images/products/green-white-hoops-retro-1996-1.svg" alt="Green &amp; White Hoops Retro 1996" loading="lazy" class="pcard-i1"><img src="assets/images/products/green-white-hoops-retro-1996-2.svg" alt="" loading="lazy" class="pcard-i2"></a>
                        <div class="pcard-badges"><span class="badge bg-ink text-bg">Retro</span></div><button type="button" class="wish-btn" data-wish aria-label="Add to wishlist" aria-pressed="false"><svg class="ic " aria-hidden="true">
                                <use href="#i-heart" />
                            </svg></button>
                    </div>
                    <div class="pcard-body">
                        <p class="text-[11px] font-bold uppercase tracking-[.14em] text-ink/45">Retro · Umbro</p>
                        <h3 class="font-display font-bold text-[15px] leading-snug mt-1 line-clamp-2"><a href="product.html" class="hover:text-primary">Green &amp; White Hoops Retro 1996</a></h3>
                        <div class="flex items-center gap-1.5 mt-1.5 text-xs text-ink/55"><span class="inline-flex" aria-label="Rated 4.8 out of 5"><svg class="w-3.5 h-3.5 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                    <use href="#i-star" />
                                </svg><svg class="w-3.5 h-3.5 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                    <use href="#i-star" />
                                </svg><svg class="w-3.5 h-3.5 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                    <use href="#i-star" />
                                </svg><svg class="w-3.5 h-3.5 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                    <use href="#i-star" />
                                </svg><svg class="w-3.5 h-3.5 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                    <use href="#i-star" />
                                </svg></span><span>(24)</span></div>
                        <div class="flex items-end justify-between gap-2 mt-3">
                            <p class="leading-none"><b class="font-display font-extrabold text-lg">৳3,300</b></p><button type="button" class="btn-add" data-quick-add aria-label="Add Green &amp; White Hoops Retro 1996 to cart"><svg class="ic w-4 h-4" aria-hidden="true">
                                    <use href="#i-bag" />
                                </svg><span>Add</span></button>
                        </div>
                    </div>
                </article>
                <article class="pcard group" data-id="15" data-name="Mint Green Training Jersey" data-price="1850" data-compare="0" data-image="assets/images/products/mint-green-training-jersey-1.svg" data-url="product.html" data-cat="la-liga" data-brand="New Balance" data-sizes="S,M,L,XL,XXL" data-stock="48" data-added="15" data-sold="182">
                    <div class="pcard-img"><a href="product.html" aria-label="Mint Green Training Jersey"><img src="assets/images/products/mint-green-training-jersey-1.svg" alt="Mint Green Training Jersey" loading="lazy" class="pcard-i1"><img src="assets/images/products/mint-green-training-jersey-2.svg" alt="" loading="lazy" class="pcard-i2"></a>
                        <div class="pcard-badges"></div><button type="button" class="wish-btn" data-wish aria-label="Add to wishlist" aria-pressed="false"><svg class="ic " aria-hidden="true">
                                <use href="#i-heart" />
                            </svg></button>
                    </div>
                    <div class="pcard-body">
                        <p class="text-[11px] font-bold uppercase tracking-[.14em] text-ink/45">La Liga · New Balance</p>
                        <h3 class="font-display font-bold text-[15px] leading-snug mt-1 line-clamp-2"><a href="product.html" class="hover:text-primary">Mint Green Training Jersey</a></h3>
                        <div class="flex items-center gap-1.5 mt-1.5 text-xs text-ink/55"><span class="inline-flex" aria-label="Rated 4.8 out of 5"><svg class="w-3.5 h-3.5 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                    <use href="#i-star" />
                                </svg><svg class="w-3.5 h-3.5 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                    <use href="#i-star" />
                                </svg><svg class="w-3.5 h-3.5 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                    <use href="#i-star" />
                                </svg><svg class="w-3.5 h-3.5 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                    <use href="#i-star" />
                                </svg><svg class="w-3.5 h-3.5 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                    <use href="#i-star" />
                                </svg></span><span>(24)</span></div>
                        <div class="flex items-end justify-between gap-2 mt-3">
                            <p class="leading-none"><b class="font-display font-extrabold text-lg">৳1,850</b></p><button type="button" class="btn-add" data-quick-add aria-label="Add Mint Green Training Jersey to cart"><svg class="ic w-4 h-4" aria-hidden="true">
                                    <use href="#i-bag" />
                                </svg><span>Add</span></button>
                        </div>
                    </div>
                </article>
                <article class="pcard group" data-id="1" data-name="Retro Red Home Jersey 1999" data-price="3200" data-compare="4200" data-image="assets/images/products/retro-red-home-jersey-1999-1.svg" data-url="product.html" data-cat="retro" data-brand="Umbro" data-sizes="S,M,L,XL,XXL" data-stock="12" data-added="1" data-sold="164">
                    <div class="pcard-img"><a href="product.html" aria-label="Retro Red Home Jersey 1999"><img src="assets/images/products/retro-red-home-jersey-1999-1.svg" alt="Retro Red Home Jersey 1999" loading="lazy" class="pcard-i1"><img src="assets/images/products/retro-red-home-jersey-1999-2.svg" alt="" loading="lazy" class="pcard-i2"></a>
                        <div class="pcard-badges"><span class="badge bg-accent text-white">-24%</span><span class="badge bg-ink text-bg">Retro</span></div><button type="button" class="wish-btn" data-wish aria-label="Add to wishlist" aria-pressed="false"><svg class="ic " aria-hidden="true">
                                <use href="#i-heart" />
                            </svg></button>
                    </div>
                    <div class="pcard-body">
                        <p class="text-[11px] font-bold uppercase tracking-[.14em] text-ink/45">Retro · Umbro</p>
                        <h3 class="font-display font-bold text-[15px] leading-snug mt-1 line-clamp-2"><a href="product.html" class="hover:text-primary">Retro Red Home Jersey 1999</a></h3>
                        <div class="flex items-center gap-1.5 mt-1.5 text-xs text-ink/55"><span class="inline-flex" aria-label="Rated 4.6 out of 5"><svg class="w-3.5 h-3.5 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                    <use href="#i-star" />
                                </svg><svg class="w-3.5 h-3.5 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                    <use href="#i-star" />
                                </svg><svg class="w-3.5 h-3.5 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                    <use href="#i-star" />
                                </svg><svg class="w-3.5 h-3.5 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                    <use href="#i-star" />
                                </svg><svg class="w-3.5 h-3.5 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                    <use href="#i-star" />
                                </svg></span><span>(12)</span></div>
                        <div class="flex items-end justify-between gap-2 mt-3">
                            <p class="leading-none"><b class="font-display font-extrabold text-lg">৳3,200</b><s class="ml-1.5 text-xs text-ink/40 font-medium">৳4,200</s></p><button type="button" class="btn-add" data-quick-add aria-label="Add Retro Red Home Jersey 1999 to cart"><svg class="ic w-4 h-4" aria-hidden="true">
                                    <use href="#i-bag" />
                                </svg><span>Add</span></button>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="border-y border-line bg-surface">
        <div class="container-x py-8">
            <p class="text-center text-xs font-bold uppercase tracking-[.2em] text-ink/45 mb-5">Brands we stock</p>
            <div class="grid grid-cols-3 md:grid-cols-6 gap-3"><a href="shop.html?brand=Nike" class="brand-chip">Nike</a><a href="shop.html?brand=Adidas" class="brand-chip">Adidas</a><a href="shop.html?brand=Puma" class="brand-chip">Puma</a><a href="shop.html?brand=Umbro" class="brand-chip">Umbro</a><a href="shop.html?brand=Kappa" class="brand-chip">Kappa</a><a href="shop.html?brand=New+Balance" class="brand-chip">New Balance</a></div>
        </div>
    </section>

    <section class="section">
        <div class="container-x">
            <div class="sec-head">
                <div>
                    <p class="eyebrow">Why Multiverse of Jersey</p>
                    <h2>Made for people who love the shirt</h2>
                </div>
            </div>
            <div class="grid md:grid-cols-3 gap-4 sm:gap-6">
                <div class="card p-6"><span class="trust-ic mb-4"><svg class="ic " aria-hidden="true">
                            <use href="#i-shield" />
                        </svg></span>
                    <h3 class="font-display font-bold text-lg">Authenticity first</h3>
                    <p class="text-ink/60 mt-2 text-sm">Every shirt is sourced from trusted suppliers and checked for tags, stitching and print quality before it ships.</p>
                </div>
                <div class="card p-6"><span class="trust-ic mb-4"><svg class="ic " aria-hidden="true">
                            <use href="#i-award" />
                        </svg></span>
                    <h3 class="font-display font-bold text-lg">Built for collectors</h3>
                    <p class="text-ink/60 mt-2 text-sm">Rare retro shirts, limited kits and player editions — described honestly with condition notes.</p>
                </div>
                <div class="card p-6"><span class="trust-ic mb-4"><svg class="ic " aria-hidden="true">
                            <use href="#i-headset" />
                        </svg></span>
                    <h3 class="font-display font-bold text-lg">Real support</h3>
                    <p class="text-ink/60 mt-2 text-sm">Chat on WhatsApp or call us for size help, restock requests and order updates.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section !pt-0">
        <div class="container-x">
            <div class="sec-head">
                <div>
                    <p class="eyebrow">Customer love</p>
                    <h2>What collectors say</h2>
                </div>
            </div>
            <div class="grid md:grid-cols-3 gap-4 sm:gap-6">
                <figure class="card p-6">
                    <div><span class="inline-flex" aria-label="Rated 5 out of 5"><svg class="w-4 h-4 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                <use href="#i-star" />
                            </svg><svg class="w-4 h-4 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                <use href="#i-star" />
                            </svg><svg class="w-4 h-4 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                <use href="#i-star" />
                            </svg><svg class="w-4 h-4 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                <use href="#i-star" />
                            </svg><svg class="w-4 h-4 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                <use href="#i-star" />
                            </svg></span></div>
                    <blockquote class="mt-3 text-sm text-ink/75">“Retro shirt arrived in perfect condition, packed like a gift. Exactly as described.”</blockquote>
                    <figcaption class="mt-4 flex items-center gap-3"><span class="avatar">R</span><span class="text-sm"><b class="block">Rafiq Hasan</b><span class="text-ink/50">Dhaka</span></span></figcaption>
                </figure>
                <figure class="card p-6">
                    <div><span class="inline-flex" aria-label="Rated 5 out of 5"><svg class="w-4 h-4 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                <use href="#i-star" />
                            </svg><svg class="w-4 h-4 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                <use href="#i-star" />
                            </svg><svg class="w-4 h-4 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                <use href="#i-star" />
                            </svg><svg class="w-4 h-4 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                <use href="#i-star" />
                            </svg><svg class="w-4 h-4 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                <use href="#i-star" />
                            </svg></span></div>
                    <blockquote class="mt-3 text-sm text-ink/75">“Size M fit perfectly and delivery to Chattogram took just 2 days. Will order again.”</blockquote>
                    <figcaption class="mt-4 flex items-center gap-3"><span class="avatar">T</span><span class="text-sm"><b class="block">Tanvir Ahmed</b><span class="text-ink/50">Chattogram</span></span></figcaption>
                </figure>
                <figure class="card p-6">
                    <div><span class="inline-flex" aria-label="Rated 5 out of 5"><svg class="w-4 h-4 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                <use href="#i-star" />
                            </svg><svg class="w-4 h-4 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                <use href="#i-star" />
                            </svg><svg class="w-4 h-4 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                <use href="#i-star" />
                            </svg><svg class="w-4 h-4 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                <use href="#i-star" />
                            </svg><svg class="w-4 h-4 star-on" viewBox="0 0 24 24" aria-hidden="true">
                                <use href="#i-star" />
                            </svg></span></div>
                    <blockquote class="mt-3 text-sm text-ink/75">“Finally a trusted place for player edition kits in Bangladesh. Great support on WhatsApp.”</blockquote>
                    <figcaption class="mt-4 flex items-center gap-3"><span class="avatar">S</span><span class="text-sm"><b class="block">Sadia Islam</b><span class="text-ink/50">Sylhet</span></span></figcaption>
                </figure>
            </div>
        </div>
    </section>

    <section class="section !pt-0">
        <div class="container-x">
            <div class="cta">
                <div>
                    <h2 class="font-display font-extrabold text-2xl sm:text-3xl tracking-tight">Can’t find your shirt?</h2>
                    <p class="text-white/75 mt-2 max-w-lg">Tell us the club, season and size — we’ll source it for you.</p>
                </div>
                <div class="flex flex-wrap gap-3"><a href="https://wa.me/8801700000000" target="_blank" rel="noopener" class="btn btn-accent btn-lg"><svg class="ic w-5 h-5" aria-hidden="true">
                            <use href="#i-chat" />
                        </svg> WhatsApp us</a><a href="contact.html" class="btn btn-ghost-light btn-lg">Send request</a></div>
            </div>
        </div>
    </section>
@endsection
