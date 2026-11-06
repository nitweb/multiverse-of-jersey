@extends('frontend.dashboard')

@section('meta_robots', 'noindex,nofollow')

@push('frontend_styles')
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/pages/checkout.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/pages/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/pages/account.css') }}">
@endpush

@section('frontend_contents')

    @php
        $accountUser = auth()->user();
        $accountNav = [
            ['account.index', 'Overview', 'i-grid', 'account.index'],
            ['account.orders', 'My orders', 'i-package', 'account.orders*'],
            ['account.returns', 'Returns', 'i-return', 'account.returns*'],
            ['account.wishlist', 'Wishlist', 'i-heart', 'account.wishlist'],
            ['account.addresses', 'Saved addresses', 'i-pin', 'account.addresses'],
            ['account.profile', 'Profile & password', 'i-user', 'account.profile*'],
        ];
    @endphp

    <section class="page-head">
        <div class="container-x">

            <nav class="crumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <svg class="ic w-3.5 h-3.5 opacity-50" aria-hidden="true"><use href="#i-chev-right" /></svg>
                @hasSection('account_parent')
                    <a href="{{ route('account.index') }}">My account</a>
                    <svg class="ic w-3.5 h-3.5 opacity-50" aria-hidden="true"><use href="#i-chev-right" /></svg>
                @endif
                <span aria-current="page">@yield('account_title', 'My account')</span>
            </nav>

            <h1 class="font-display font-extrabold text-3xl sm:text-4xl tracking-tight mt-3">@yield('account_title', 'My account')</h1>

            @hasSection('account_sub')
                <p class="text-ink/60 mt-2 max-w-2xl">@yield('account_sub')</p>
            @endif

        </div>
    </section>

    <section class="section !pt-8">
        <div class="container-x grid lg:grid-cols-[280px_1fr] gap-6 items-start">

            <aside class="card p-3 h-fit lg:sticky lg:top-28">

                <div class="flex items-center gap-3 p-3 mb-2">
                    <span class="avatar lg">{{ mb_strtoupper(mb_substr($accountUser->name, 0, 1)) }}</span>
                    <div class="leading-tight min-w-0">
                        <b class="block truncate">{{ $accountUser->name }}</b>
                        <span class="text-xs text-ink/50">{{ $accountUser->phone }}</span>
                    </div>
                </div>

                <nav class="acc-nav" aria-label="Account">
                    @foreach ($accountNav as [$route, $label, $icon, $pattern])
                        <a href="{{ route($route) }}" class="acc-link {{ request()->routeIs($pattern) ? 'is-active' : '' }}" @if (request()->routeIs($pattern)) aria-current="page" @endif>
                            <svg class="ic w-5 h-5" aria-hidden="true"><use href="#{{ $icon }}" /></svg>{{ $label }}
                        </a>
                    @endforeach

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="acc-link acc-logout">
                            <svg class="ic w-5 h-5" aria-hidden="true"><use href="#i-logout" /></svg> Logout
                        </button>
                    </form>
                </nav>

            </aside>

            <div class="min-w-0 space-y-6">

                @if (session('status'))
                    <p class="flash flash-ok" role="status">{{ session('status') }}</p>
                @endif

                @if (session('error'))
                    <p class="flash flash-err" role="alert">{{ session('error') }}</p>
                @endif

                @yield('account_body')

            </div>

        </div>
    </section>

@endsection
