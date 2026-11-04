<section class="hero" aria-roledescription="carousel" aria-label="Featured collections">

    <div class="hero-track">

        <div class="hero-slide is-active" data-slide>
            <img src="{{ asset('assets/frontend/images/banners/hero-1.svg') }}" alt="" class="hero-img">
            <div class="container-x relative h-full flex items-end sm:items-center pb-16 sm:pb-0">
                <div class="max-w-xl text-white">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-xs font-bold uppercase tracking-[.16em]">
                        <svg class="ic w-3.5 h-3.5" aria-hidden="true">
                            <use href="#i-zap" />
                        </svg>
                        New season drops
                    </span>
                    <h2 class="font-display font-extrabold text-4xl sm:text-5xl lg:text-6xl leading-[1.05] tracking-tight mt-4">Wear the game.<br>Own the history.</h2>
                    <p class="mt-4 text-white/80 text-base sm:text-lg max-w-md">Verified authentic jerseys, shipped across Bangladesh with cash on delivery.</p>
                    <div class="flex flex-wrap gap-3 mt-7">
                        <a href="shop.html" class="btn btn-accent btn-lg">
                            Shop new arrivals
                            <svg class="ic w-4 h-4" aria-hidden="true">
                                <use href="#i-arrow-right" />
                            </svg>
                        </a>
                        <a href="shop.html" class="btn btn-ghost-light btn-lg">View all</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="hero-slide" data-slide>
            <img src="{{ asset('assets/frontend/images/banners/hero-2.svg') }}" alt="" class="hero-img" loading="lazy">
            <div class="container-x relative h-full flex items-end sm:items-center pb-16 sm:pb-0">
                <div class="max-w-xl text-white">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-xs font-bold uppercase tracking-[.16em]">
                        <svg class="ic w-3.5 h-3.5" aria-hidden="true">
                            <use href="#i-zap" />
                        </svg>
                        Retro collection
                    </span>
                    <h2 class="font-display font-extrabold text-4xl sm:text-5xl lg:text-6xl leading-[1.05] tracking-tight mt-4">Classic shirts.<br>Collector grade.</h2>
                    <p class="mt-4 text-white/80 text-base sm:text-lg max-w-md">Hunt down iconic 90s and 2000s kits in hand-checked condition.</p>
                    <div class="flex flex-wrap gap-3 mt-7">
                        <a href="shop.html?cat=retro" class="btn btn-accent btn-lg">
                            Explore retro
                            <svg class="ic w-4 h-4" aria-hidden="true">
                                <use href="#i-arrow-right" />
                            </svg>
                        </a>
                        <a href="shop.html" class="btn btn-ghost-light btn-lg">View all</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="hero-slide" data-slide>
            <img src="{{ asset('assets/frontend/images/banners/hero-3.svg') }}" alt="" class="hero-img" loading="lazy">
            <div class="container-x relative h-full flex items-end sm:items-center pb-16 sm:pb-0">
                <div class="max-w-xl text-white">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-xs font-bold uppercase tracking-[.16em]">
                        <svg class="ic w-3.5 h-3.5" aria-hidden="true">
                            <use href="#i-zap" />
                        </svg>
                        Player edition
                    </span>
                    <h2 class="font-display font-extrabold text-4xl sm:text-5xl lg:text-6xl leading-[1.05] tracking-tight mt-4">Match-fit.<br>Straight from the pitch.</h2>
                    <p class="mt-4 text-white/80 text-base sm:text-lg max-w-md">Authentic player-cut jerseys with the same tech worn on matchday.</p>
                    <div class="flex flex-wrap gap-3 mt-7"><a href="shop.html?cat=player-edition" class="btn btn-accent btn-lg">
                            Shop player edition
                            <svg class="ic w-4 h-4" aria-hidden="true">
                                <use href="#i-arrow-right" />
                            </svg>
                        </a>
                        <a href="shop.html" class="btn btn-ghost-light btn-lg">View all</a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <button type="button" class="hero-nav prev" data-hero-prev aria-label="Previous slide">
        <svg class="ic " aria-hidden="true">
            <use href="#i-chev-left" />
        </svg>
    </button>

    <button type="button" class="hero-nav next" data-hero-next aria-label="Next slide">
        <svg class="ic " aria-hidden="true">
            <use href="#i-chev-right" />
        </svg>
    </button>

    <div class="hero-dots" data-hero-dots></div>

</section>
