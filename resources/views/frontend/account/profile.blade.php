@extends('frontend.account.layout')

@php $storeName = $setting('store_name', 'Multiverse of Jersey'); @endphp

@section('frontend_title', 'Profile & password | '.$storeName)
@section('account_parent', true)
@section('account_title', 'Profile & password')
@section('account_sub', 'Keep your contact details up to date.')

@push('frontend_scripts')
    <script src="{{ asset('assets/frontend/js/pages/auth.js') }}" defer></script>
@endpush

@section('account_body')

    <form class="card p-5 sm:p-7" method="POST" action="{{ route('account.profile.update') }}" data-auth-form novalidate>
        @csrf
        @method('PATCH')

        <h2 class="font-display font-extrabold text-xl">Personal information</h2>

        <div class="grid sm:grid-cols-2 gap-4 mt-5">
            <div>
                <label class="lbl" for="name">Full name <span class="text-danger">*</span></label>
                <input class="input @error('name') invalid @enderror" id="name" name="name" type="text" required maxlength="100" autocomplete="name" value="{{ old('name', $user->name) }}">
                <p class="err">{{ $errors->first('name') }}</p>
            </div>
            <div>
                <label class="lbl" for="phone">Phone <span class="text-danger">*</span></label>
                <input class="input @error('phone') invalid @enderror" id="phone" name="phone" type="tel" required maxlength="20" inputmode="numeric" autocomplete="tel" value="{{ old('phone', $user->phone) }}">
                <p class="err">{{ $errors->first('phone') }}</p>
            </div>
            <div class="sm:col-span-2">
                <label class="lbl" for="email">Email <span class="text-danger">*</span></label>
                <input class="input @error('email') invalid @enderror" id="email" name="email" type="email" required maxlength="150" autocomplete="email" value="{{ old('email', $user->email) }}">
                <p class="err">{{ $errors->first('email') }}</p>
            </div>
        </div>

        <button class="btn btn-primary mt-5" type="submit">Save changes</button>
    </form>

    <form class="card p-5 sm:p-7" method="POST" action="{{ route('account.password.update') }}" data-auth-form novalidate>
        @csrf
        @method('PUT')

        <h2 class="font-display font-extrabold text-xl">Change password</h2>

        @php $pwErrors = $errors->getBag('password'); @endphp

        <div class="grid sm:grid-cols-3 gap-4 mt-5">
            @foreach ([['current_password', 'current_password', 'Current password', 'current-password'], ['password', 'new_password', 'New password', 'new-password'], ['password_confirmation', 'confirm_password', 'Confirm password', 'new-password']] as [$name, $id, $label, $autocomplete])
                <div>
                    <label class="lbl" for="{{ $id }}">{{ $label }} <span class="text-danger">*</span></label>
                    <div class="relative">
                        <input class="input !pr-12 {{ $pwErrors->has($name) ? 'invalid' : '' }}" id="{{ $id }}" name="{{ $name }}" type="password" required autocomplete="{{ $autocomplete }}">
                        <button type="button" class="pw-toggle" data-pw-toggle aria-label="Show password">
                            <span class="pw-show"><svg class="ic w-5 h-5" aria-hidden="true"><use href="#i-eye" /></svg></span>
                            <span class="pw-hide"><svg class="ic w-5 h-5" aria-hidden="true"><use href="#i-eye-off" /></svg></span>
                        </button>
                    </div>
                    <p class="err">{{ $pwErrors->first($name) }}</p>
                </div>
            @endforeach
        </div>
        <p class="text-xs text-ink/50 mt-3">At least 8 characters.</p>

        <button class="btn btn-primary mt-5" type="submit">Update password</button>
    </form>

@endsection
