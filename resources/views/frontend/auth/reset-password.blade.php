@extends('frontend.dashboard')

@section('frontend_title', 'Reset password | '.$setting('store_name', 'Multiverse of Jersey'))
@section('title', 'Reset password | '.$setting('store_name', 'Multiverse of Jersey'))
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

                    <h1 class="font-display font-extrabold text-3xl tracking-tight">Set a new password</h1>
                    <p class="text-ink/60 mt-2">Choose a new password for your account.</p>

                    <form class="space-y-4 mt-7" method="POST" action="{{ route('password.store') }}" data-auth-form novalidate>
                        @csrf
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <div>
                            <label class="lbl" for="email">Email <span class="text-danger">*</span></label>
                            <input class="input @error('email') invalid @enderror" id="email" name="email" type="email" required maxlength="150" autocomplete="email" value="{{ old('email', $request->email) }}">
                            <p class="err">{{ $errors->first('email') }}</p>
                        </div>

                        @include('frontend.auth._password', ['id' => 'password', 'name' => 'password', 'label' => 'New password', 'autocomplete' => 'new-password'])
                        @include('frontend.auth._password', ['id' => 'password_confirmation', 'name' => 'password_confirmation', 'label' => 'Confirm password', 'autocomplete' => 'new-password'])

                        <button class="btn btn-primary btn-lg w-full" type="submit">Reset password</button>
                    </form>

                    <p class="text-sm text-center text-ink/60 mt-6"><a href="{{ route('login') }}" class="link font-semibold">← Back to login</a></p>

                </div>
            </div>
        </div>
    </section>

@endsection
