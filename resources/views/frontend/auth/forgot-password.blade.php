@extends('frontend.dashboard')

@section('frontend_title', 'Forgot password | '.$setting('store_name', 'Multiverse of Jersey'))
@section('title', 'Forgot password | '.$setting('store_name', 'Multiverse of Jersey'))
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

                    <h1 class="font-display font-extrabold text-3xl tracking-tight">Forgot password?</h1>
                    <p class="text-ink/60 mt-2">Enter the email address of your account and we’ll send you a link to reset your password.</p>

                    @if (session('status'))
                        <p class="flash flash-ok mt-5" role="status">{{ session('status') }}</p>
                    @endif

                    <form class="space-y-4 mt-7" method="POST" action="{{ route('password.email') }}" data-auth-form novalidate>
                        @csrf

                        <div>
                            <label class="lbl" for="email">Email <span class="text-danger">*</span></label>
                            <input class="input @error('email') invalid @enderror" id="email" name="email" type="email" placeholder="you@example.com" required maxlength="150" autocomplete="email" autofocus value="{{ old('email') }}">
                            <p class="err">{{ $errors->first('email') }}</p>
                        </div>

                        <button class="btn btn-primary btn-lg w-full" type="submit">Send reset link</button>
                    </form>

                    <p class="text-sm text-center text-ink/60 mt-6"><a href="{{ route('login') }}" class="link font-semibold">← Back to login</a></p>

                </div>
            </div>
        </div>
    </section>

@endsection
