<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('frontend_title')</title>
    <meta name="description" content="@yield('meta_description', 'Authentic football jerseys for collectors in Bangladesh. Premier League, La Liga, Serie A, retro and player edition jerseys with cash on delivery all over Bangladesh.')">
    <meta name="theme-color" content="#006a4e">

    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph / Facebook / WhatsApp --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="Multiverse of Jersey">
    <meta property="og:title" content="@yield('title', 'Multiverse of Jersey — Authentic football jerseys in Bangladesh')">
    <meta property="og:description" content="@yield('meta_description', 'Authentic football jerseys for collectors in Bangladesh. Premier League, La Liga, Serie A, retro and player edition jerseys with cash on delivery all over Bangladesh.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('assets/frontend/img/og.jpg'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="Multiverse of Jersey">
    <meta property="og:locale" content="en_US">

    {{-- Twitter / X --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Multiverse of Jersey — Authentic football jerseys in Bangladesh')">
    <meta name="twitter:description" content="@yield('meta_description', 'Authentic football jerseys for collectors in Bangladesh. Premier League, La Liga, Serie A, retro and player edition jerseys with cash on delivery all over Bangladesh.')">
    <meta name="twitter:image" content="@yield('og_image', asset('assets/frontend/img/og.jpg'))">
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/frontend/img/favicon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/frontend/img/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/frontend/img/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/frontend/img/favicon.ico') }}">
    <script>
        try {
            var t = localStorage.getItem("jbd_theme");
            if (t === "dark") document.documentElement.classList.add("dark")
        } catch (e) {}
    </script>
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/tailwind.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/pages/home.css') }}">
</head>

<body class="min-h-screen flex flex-col">

    <svg xmlns="http://www.w3.org/2000/svg" class="hidden" style="display:none" aria-hidden="true">
        <symbol id="i-menu" viewBox="0 0 24 24">
            <path d="M4 6h16M4 12h16M4 18h16" />
        </symbol>
        <symbol id="i-x" viewBox="0 0 24 24">
            <path d="M18 6 6 18M6 6l12 12" />
        </symbol>
        <symbol id="i-search" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="7" />
            <path d="m21 21-4.3-4.3" />
        </symbol>
        <symbol id="i-bag" viewBox="0 0 24 24">
            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
            <path d="M3 6h18" />
            <path d="M16 10a4 4 0 0 1-8 0" />
        </symbol>
        <symbol id="i-heart" viewBox="0 0 24 24">
            <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z" />
        </symbol>
        <symbol id="i-user" viewBox="0 0 24 24">
            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
            <circle cx="12" cy="7" r="4" />
        </symbol>
        <symbol id="i-sun" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="4" />
            <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" />
        </symbol>
        <symbol id="i-moon" viewBox="0 0 24 24">
            <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" />
        </symbol>
        <symbol id="i-chev-down" viewBox="0 0 24 24">
            <path d="m6 9 6 6 6-6" />
        </symbol>
        <symbol id="i-chev-right" viewBox="0 0 24 24">
            <path d="m9 18 6-6-6-6" />
        </symbol>
        <symbol id="i-chev-left" viewBox="0 0 24 24">
            <path d="m15 18-6-6 6-6" />
        </symbol>
        <symbol id="i-phone" viewBox="0 0 24 24">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.58 2.81.7A2 2 0 0 1 22 16.92z" />
        </symbol>
        <symbol id="i-chat" viewBox="0 0 24 24">
            <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z" />
        </symbol>
        <symbol id="i-msg" viewBox="0 0 24 24">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
        </symbol>
        <symbol id="i-mail" viewBox="0 0 24 24">
            <rect x="2" y="4" width="20" height="16" rx="2" />
            <path d="m22 7-10 6L2 7" />
        </symbol>
        <symbol id="i-pin" viewBox="0 0 24 24">
            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
            <circle cx="12" cy="10" r="3" />
        </symbol>
        <symbol id="i-clock" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" />
            <path d="M12 6v6l4 2" />
        </symbol>
        <symbol id="i-truck" viewBox="0 0 24 24">
            <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2" />
            <path d="M15 18H9" />
            <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.62l-3.48-4.35A1 1 0 0 0 17.52 8H14" />
            <circle cx="17" cy="18" r="2" />
            <circle cx="7" cy="18" r="2" />
        </symbol>
        <symbol id="i-shield" viewBox="0 0 24 24">
            <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
            <path d="m9 12 2 2 4-4" />
        </symbol>
        <symbol id="i-return" viewBox="0 0 24 24">
            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
            <path d="M3 3v5h5" />
        </symbol>
        <symbol id="i-star" viewBox="0 0 24 24">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
        </symbol>
        <symbol id="i-plus" viewBox="0 0 24 24">
            <path d="M5 12h14M12 5v14" />
        </symbol>
        <symbol id="i-minus" viewBox="0 0 24 24">
            <path d="M5 12h14" />
        </symbol>
        <symbol id="i-trash" viewBox="0 0 24 24">
            <path d="M3 6h18" />
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
        </symbol>
        <symbol id="i-sliders" viewBox="0 0 24 24">
            <path d="M4 21v-7M4 10V3M12 21v-9M12 8V3M20 21v-5M20 12V3M1 14h6M9 8h6M17 16h6" />
        </symbol>
        <symbol id="i-check" viewBox="0 0 24 24">
            <path d="M20 6 9 17l-5-5" />
        </symbol>
        <symbol id="i-facebook" viewBox="0 0 24 24">
            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
        </symbol>
        <symbol id="i-instagram" viewBox="0 0 24 24">
            <rect x="2" y="2" width="20" height="20" rx="5" />
            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
            <path d="M17.5 6.5h.01" />
        </symbol>
        <symbol id="i-youtube" viewBox="0 0 24 24">
            <path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17" />
            <path d="m10 15 5-3-5-3z" />
        </symbol>
        <symbol id="i-tiktok" viewBox="0 0 24 24">
            <path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5" />
        </symbol>
        <symbol id="i-eye" viewBox="0 0 24 24">
            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
            <circle cx="12" cy="12" r="3" />
        </symbol>
        <symbol id="i-eye-off" viewBox="0 0 24 24">
            <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
            <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
            <path d="M6.61 6.61A13.53 13.53 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
            <path d="m2 2 20 20" />
        </symbol>
        <symbol id="i-package" viewBox="0 0 24 24">
            <path d="m7.5 4.27 9 5.15" />
            <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
            <path d="m3.3 7 8.7 5 8.7-5M12 22V12" />
        </symbol>
        <symbol id="i-zap" viewBox="0 0 24 24">
            <path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z" />
        </symbol>
        <symbol id="i-arrow-right" viewBox="0 0 24 24">
            <path d="M5 12h14m-7-7 7 7-7 7" />
        </symbol>
        <symbol id="i-arrow-up" viewBox="0 0 24 24">
            <path d="m5 12 7-7 7 7M12 19V5" />
        </symbol>
        <symbol id="i-lock" viewBox="0 0 24 24">
            <rect x="3" y="11" width="18" height="11" rx="2" />
            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
        </symbol>
        <symbol id="i-ticket" viewBox="0 0 24 24">
            <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
            <path d="M13 5v2M13 17v2M13 11v2" />
        </symbol>
        <symbol id="i-grid" viewBox="0 0 24 24">
            <rect x="3" y="3" width="7" height="7" />
            <rect x="14" y="3" width="7" height="7" />
            <rect x="14" y="14" width="7" height="7" />
            <rect x="3" y="14" width="7" height="7" />
        </symbol>
        <symbol id="i-home" viewBox="0 0 24 24">
            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
            <path d="M9 22V12h6v10" />
        </symbol>
        <symbol id="i-logout" viewBox="0 0 24 24">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
            <path d="m16 17 5-5-5-5M21 12H9" />
        </symbol>
        <symbol id="i-info" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" />
            <path d="M12 16v-4M12 8h.01" />
        </symbol>
        <symbol id="i-ruler" viewBox="0 0 24 24">
            <path d="M21.3 15.3a2.4 2.4 0 0 1 0 3.4l-2.6 2.6a2.4 2.4 0 0 1-3.4 0L2.7 8.7a2.4 2.4 0 0 1 0-3.4l2.6-2.6a2.4 2.4 0 0 1 3.4 0z" />
            <path d="m14.5 12.5 2-2M11.5 9.5l2-2M8.5 6.5l2-2M17.5 15.5l2-2" />
        </symbol>
        <symbol id="i-share" viewBox="0 0 24 24">
            <circle cx="18" cy="5" r="3" />
            <circle cx="6" cy="12" r="3" />
            <circle cx="18" cy="19" r="3" />
            <path d="m8.59 13.51 6.83 3.98M15.41 6.51l-6.82 3.98" />
        </symbol>
        <symbol id="i-award" viewBox="0 0 24 24">
            <circle cx="12" cy="8" r="6" />
            <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11" />
        </symbol>
        <symbol id="i-headset" viewBox="0 0 24 24">
            <path d="M3 11v3a2 2 0 0 0 2 2h1v-7H5a2 2 0 0 0-2 2zM21 11v3a2 2 0 0 1-2 2h-1v-7h1a2 2 0 0 1 2 2z" />
            <path d="M4 11a8 8 0 0 1 16 0M18 16v1a3 3 0 0 1-3 3h-3" />
        </symbol>
    </svg>

    @include('frontend.layouts.top_bar')

    @include('frontend.layouts.header')



    <main id="main" class="flex-1">

        @yield('frontend_contents')

    </main>

    @include('frontend.layouts.footer')

    <div id="overlay" class="overlay" hidden></div>

    <aside id="cart-drawer" class="drawer" aria-label="Shopping cart" aria-hidden="true">
        <div class="flex items-center justify-between px-5 h-16 border-b border-line shrink-0">
            <h2 class="font-display font-extrabold text-lg flex items-center gap-2">
                <svg class="ic " aria-hidden="true">
                    <use href="#i-bag" />
                </svg>
                Your cart
                <span class="text-ink/45 text-sm font-semibold" data-cart-count-text></span>
            </h2>
            <button type="button" class="icon-btn" data-cart-close aria-label="Close cart">
                <svg class="ic " aria-hidden="true">
                    <use href="#i-x" />
                </svg>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto px-5" data-drawer-items></div>
        <div class="border-t border-line p-5 shrink-0 space-y-3" data-drawer-foot>
            <div class="flex justify-between font-semibold">
                <span>Subtotal</span>
                <b class="font-display text-lg" data-drawer-subtotal>৳0</b>
            </div>
            <p class="text-xs text-ink/50">Delivery charge is calculated at checkout.</p>
            <div class="grid grid-cols-2 gap-3">
                <a href="cart.html" class="btn btn-ghost">View cart</a>
                <a href="checkout.html" class="btn btn-primary">Checkout</a>
            </div>
        </div>
    </aside>

    <div id="quick-modal" class="modal" hidden role="dialog" aria-modal="true" aria-labelledby="qm-title">
        <div class="modal-box">
            <button type="button" class="icon-btn absolute top-3 right-3" data-modal-close aria-label="Close">
                <svg class="ic " aria-hidden="true">
                    <use href="#i-x" />
                </svg>
            </button>
            <div class="flex gap-4">
                <img id="qm-img" src="" alt="" class="w-24 h-24 rounded-xl object-cover bg-bg shrink-0">
                <div class="min-w-0">
                    <h3 id="qm-title" class="font-display font-bold leading-snug pr-8"></h3>
                    <p id="qm-price" class="font-display font-extrabold text-lg mt-1"></p>
                </div>
            </div>
            <p class="font-semibold mt-5 mb-2 text-sm">Select size</p>
            <div id="qm-sizes" class="flex flex-wrap gap-2"></div>
            <p id="qm-err" class="text-danger text-xs mt-2" hidden>Please select a size.</p>
            <button type="button" id="qm-add" class="btn btn-primary w-full mt-6">Add to cart</button>
        </div>
    </div>

    <div class="chat" data-chat>
        <div class="chat-menu" data-chat-menu>
            <a href="tel:+8801700000000" class="chat-item">
                <svg class="ic w-5 h-5" aria-hidden="true">
                    <use href="#i-phone" />
                </svg>
                <span>Call us</span>
            </a>
            <a href="https://wa.me/8801700000000" target="_blank" rel="noopener" class="chat-item">
                <svg class="ic w-5 h-5" aria-hidden="true">
                    <use href="#i-chat" />
                </svg>
                <span>WhatsApp</span>
            </a>
            <a href="https://m.me/multiverseofjersey" target="_blank" rel="noopener" class="chat-item">
                <svg class="ic w-5 h-5" aria-hidden="true">
                    <use href="#i-msg" />
                </svg>
                <span>Messenger</span>
            </a>
        </div>
        <button type="button" class="chat-fab" data-chat-toggle aria-label="Chat with us" aria-expanded="false">
            <span class="chat-open">
                <svg class="ic w-6 h-6" aria-hidden="true">
                    <use href="#i-headset" />
                </svg>
            </span>
            <span class="chat-close">
                <svg class="ic w-6 h-6" aria-hidden="true">
                    <use href="#i-x" />
                </svg>
            </span>
        </button>
    </div>

    <button type="button" class="to-top" data-to-top aria-label="Back to top" hidden>
        <svg class="ic w-5 h-5" aria-hidden="true">
            <use href="#i-arrow-up" />
        </svg>
    </button>

    <div id="toasts" class="toasts" aria-live="polite"></div>

    <script src="{{ asset('assets/frontend/js/common.js') }}" defer></script>
    <script src="{{ asset('assets/frontend/js/pages/home.js') }}" defer></script>

</body>

</html>
