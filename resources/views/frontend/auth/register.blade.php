@extends('frontend.dashboard')

@section('frontend_title', 'Create account | '.$setting('store_name', 'Multiverse of Jersey'))
@section('title', 'Create account | '.$setting('store_name', 'Multiverse of Jersey'))
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

                    <h1 class="font-display font-extrabold text-3xl tracking-tight">Create account</h1>
                    <p class="text-ink/60 mt-2">Join Multiverse of Jersey to save your wishlist and track every order.</p>

                    <form class="space-y-4 mt-7" method="POST" action="{{ route('register') }}" data-auth-form novalidate>
                        @csrf

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="lbl" for="name">Full name <span class="text-danger">*</span></label>
                                <input class="input @error('name') invalid @enderror" id="name" name="name" type="text" placeholder="Your name" required maxlength="100" autocomplete="name" value="{{ old('name') }}">
                                <p class="err">{{ $errors->first('name') }}</p>
                            </div>
                            <div>
                                <label class="lbl" for="phone">Phone <span class="text-danger">*</span></label>
                                <input class="input @error('phone') invalid @enderror" id="phone" name="phone" type="tel" placeholder="01XXXXXXXXX" required maxlength="20" inputmode="numeric" autocomplete="tel" value="{{ old('phone') }}">
                                <p class="err">{{ $errors->first('phone') }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="lbl" for="email">Email <span class="text-danger">*</span></label>
                            <input class="input @error('email') invalid @enderror" id="email" name="email" type="email" placeholder="you@example.com" required maxlength="150" autocomplete="email" value="{{ old('email') }}">
                            <p class="err">{{ $errors->first('email') }}</p>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            @include('frontend.auth._password', ['id' => 'password', 'name' => 'password', 'label' => 'Password', 'autocomplete' => 'new-password'])
                            @include('frontend.auth._password', ['id' => 'password_confirmation', 'name' => 'password_confirmation', 'label' => 'Confirm password', 'autocomplete' => 'new-password'])
                        </div>
                        <p class="text-xs text-ink/50">At least 8 characters.</p>

                        <label class="chk !items-start">
                            <input type="checkbox" name="terms" value="1" required @checked(old('terms'))>
                            <span class="text-sm">I agree to the <a href="{{ route('terms.show') }}" class="link" target="_blank" rel="noopener">terms</a> and <a href="{{ route('privacy.show') }}" class="link" target="_blank" rel="noopener">privacy policy</a>.</span>
                        </label>
                        <p class="err">{{ $errors->first('terms') }}</p>

                        <button class="btn btn-primary btn-lg w-full" type="submit">Create account</button>
                    </form>

                    <p class="text-sm text-center text-ink/60 mt-6">Already have an account? <a href="{{ route('login') }}" class="link font-semibold">Login</a></p>

                </div>
            </div>
        </div>
    </section>

@endsection
