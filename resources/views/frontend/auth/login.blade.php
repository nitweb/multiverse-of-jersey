@extends('frontend.dashboard')

@section('frontend_title', 'Login | '.$setting('store_name', 'Multiverse of Jersey'))
@section('title', 'Login | '.$setting('store_name', 'Multiverse of Jersey'))
@section('meta_robots', 'noindex,nofollow')

@push('frontend_styles')
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/pages/auth.css') }}">
@endpush

@push('frontend_scripts')
    <script src="{{ asset('assets/frontend/js/pages/auth.js') }}" defer></script>
@endpush

@section('frontend_contents')

    <section class="section">
        <div class="container-x">
            <div class="auth card overflow-hidden grid lg:grid-cols-2">

                @include('frontend.auth._side')

                <div class="p-6 sm:p-10">

                    <h1 class="font-display font-extrabold text-3xl tracking-tight">Welcome back</h1>
                    <p class="text-ink/60 mt-2">Login to view orders and checkout faster.</p>

                    @if (session('status'))
                        <p class="flash flash-ok mt-5" role="status">{{ session('status') }}</p>
                    @endif

                    <form class="space-y-4 mt-7" method="POST" action="{{ route('login') }}" data-auth-form novalidate>
                        @csrf

                        <div>
                            <label class="lbl" for="login">Phone or email <span class="text-danger">*</span></label>
                            <input class="input @error('login') invalid @enderror" id="login" name="login" type="text" placeholder="01XXXXXXXXX or you@example.com" required maxlength="150" autocomplete="username" autofocus value="{{ old('login') }}">
                            <p class="err">{{ $errors->first('login') }}</p>
                        </div>

                        @include('frontend.auth._password', ['id' => 'password', 'name' => 'password', 'label' => 'Password'])

                        <div class="flex items-center justify-between text-sm">
                            <label class="chk"><input type="checkbox" name="remember" value="1" @checked(old('remember'))><span>Remember me</span></label>
                            <a href="{{ route('password.request') }}" class="link">Forgot password?</a>
                        </div>

                        <button class="btn btn-primary btn-lg w-full" type="submit">Login</button>
                    </form>

                    <p class="text-sm text-center text-ink/60 mt-6">New to Multiverse of Jersey? <a href="{{ route('register') }}" class="link font-semibold">Create an account</a></p>

                </div>
            </div>
        </div>
    </section>

@endsection
