<header id="site-header" class="site-header">

    <div class="container-x flex flex-wrap items-center gap-x-2 gap-y-2.5 py-3 lg:flex-nowrap lg:gap-x-4 lg:py-0 lg:h-[76px]">

        <button type="button" class="icon-btn lg:hidden" data-nav-toggle aria-label="Open menu" aria-expanded="false" aria-controls="site-nav">
            <svg class="ic " aria-hidden="true">
                <use href="#i-menu" />
            </svg>
        </button>

        <a href="{{ route('home') }}" class="logo mr-auto lg:mr-2 shrink-0" aria-label="Multiverse of Jersey home">
            <img src="{{asset('assets/frontend/img/logo.png')}}" alt="Multiverse of Jersey" class="logo-l w-auto" style="height:50px;">
            <img src="{{asset('assets/frontend/img/logo_white.png')}}" alt="" class="logo-d w-auto" style="height:50px;">
        </a>

        <form class="search hd-search" action="{{ route('search') }}" role="search">
            <svg class="ic ic-search" aria-hidden="true">
                <use href="#i-search" />
            </svg>
            <input type="search" name="q" placeholder="Search jerseys, clubs, years…" aria-label="Search jerseys" autocomplete="off">
        </form>
        <div class="flex items-center gap-0.5 sm:gap-1 hd-icons">

            <button type="button" class="icon-btn" data-theme-toggle aria-label="Toggle dark mode">
                <span class="theme-sun">
                    <svg class="ic " aria-hidden="true">
                        <use href="#i-sun" />
                    </svg>
                </span>
                <span class="theme-moon">
                    <svg class="ic " aria-hidden="true">
                        <use href="#i-moon" />
                    </svg>
                </span>
            </button>

            <a href="{{ route('account.wishlist') }}" class="icon-btn relative hidden sm:grid" aria-label="Wishlist">
                <svg class="ic " aria-hidden="true">
                    <use href="#i-heart" />
                </svg>
                <span class="count-badge" data-wish-count @if (empty($wishlistIds)) hidden @endif>{{ count($wishlistIds ?? []) }}</span>
            </a>

            <a href="{{ auth()->check() ? route('account.index') : route('login') }}" class="icon-btn" aria-label="{{ auth()->check() ? 'My account' : 'Login' }}">
                <svg class="ic " aria-hidden="true">
                    <use href="#i-user" />
                </svg>
            </a>

            <button type="button" class="icon-btn relative" data-cart-open aria-label="Open cart">
                <svg class="ic " aria-hidden="true">
                    <use href="#i-bag" />
                </svg>
                <span class="count-badge" data-cart-count hidden>0</span>
            </button>

        </div>

    </div>

    <div class="hd-nav">
        <div class="container-x">

            <nav id="site-nav" class="site-nav" aria-label="Main">

                <div class="nav-head lg:hidden">
                    <span class="font-display font-extrabold text-lg">Menu</span>
                    <button type="button" class="icon-btn" data-nav-close aria-label="Close menu">
                        <svg class="ic " aria-hidden="true">
                            <use href="#i-x" />
                        </svg>
                    </button>
                </div>

                <a href="{{ route('shop') }}" class="nav-link" @if (request()->routeIs('shop')) aria-current="page" @endif>All Jerseys</a>

                @foreach ($menuCategories->take(8) as $menuCategory)
                    <a href="{{ $menuCategory->url }}" class="nav-link" @if (url()->current() === $menuCategory->url) aria-current="page" @endif>{{ $menuCategory->name }}</a>
                @endforeach

                @if ($menuCategories->count() > 8)
                    <div class="has-sub nav-more">
                        <div class="flex items-center">
                            <span class="nav-link flex-1 cursor-pointer" tabindex="0">
                                More
                                <svg class="ic nav-chev" aria-hidden="true">
                                    <use href="#i-chev-down" />
                                </svg>
                            </span>
                        </div>
                        <div class="sub">
                            @foreach ($menuCategories->slice(8) as $menuCategory)
                                <a href="{{ $menuCategory->url }}">{{ $menuCategory->name }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="nav-foot lg:hidden">
                    @auth
                        <a href="{{ route('account.index') }}" class="btn btn-primary w-full">My account</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary w-full">Login / Register</a>
                    @endauth
                    <p class="text-xs text-ink/50 mt-3">Cash on Delivery all over Bangladesh</p>
                </div>

            </nav>

        </div>
    </div>

</header>
