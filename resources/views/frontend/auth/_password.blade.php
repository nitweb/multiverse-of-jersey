<div>
    <label class="lbl" for="{{ $id }}">{{ $label }} <span class="text-danger">*</span></label>
    <div class="relative">
        <input class="input !pr-12 @error($name) invalid @enderror" id="{{ $id }}" name="{{ $name }}" type="password" required autocomplete="{{ $autocomplete ?? 'current-password' }}">
        <button type="button" class="pw-toggle" data-pw-toggle aria-label="Show password">
            <span class="pw-show"><svg class="ic w-5 h-5" aria-hidden="true"><use href="#i-eye" /></svg></span>
            <span class="pw-hide"><svg class="ic w-5 h-5" aria-hidden="true"><use href="#i-eye-off" /></svg></span>
        </button>
    </div>
    <p class="err">{{ $errors->first($name) }}</p>
</div>
