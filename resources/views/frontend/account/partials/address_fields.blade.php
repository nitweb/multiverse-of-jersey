@php
    // Shows what was just submitted for this form, otherwise what is saved.
    $submitted = old('_form') === $form;
    $val = fn (string $key, mixed $default = '') => $submitted ? old($key) : ($saved?->{$key} ?? $default);
    $err = fn (string $key) => $submitted ? $errors->first($key) : '';
    $id = $form.'-';
@endphp

<div class="sm:col-span-2">
    <label class="lbl" for="{{ $id }}label">Address name <span class="text-danger">*</span></label>
    <input class="input @if ($err('label')) invalid @endif" id="{{ $id }}label" name="label" type="text" required maxlength="30" placeholder="Home, Office…" value="{{ $val('label', 'Home') }}">
    <p class="err">{{ $err('label') }}</p>
</div>

<div>
    <label class="lbl" for="{{ $id }}name">Full name <span class="text-danger">*</span></label>
    <input class="input @if ($err('name')) invalid @endif" id="{{ $id }}name" name="name" type="text" required maxlength="100" autocomplete="name" value="{{ $val('name', auth()->user()->name) }}">
    <p class="err">{{ $err('name') }}</p>
</div>

<div>
    <label class="lbl" for="{{ $id }}phone">Mobile number <span class="text-danger">*</span></label>
    <input class="input @if ($err('phone')) invalid @endif" id="{{ $id }}phone" name="phone" type="tel" required inputmode="numeric" maxlength="20" placeholder="01XXXXXXXXX" value="{{ $val('phone', auth()->user()->phone) }}">
    <p class="err">{{ $err('phone') }}</p>
</div>

<div>
    <label class="lbl" for="{{ $id }}district">District <span class="text-danger">*</span></label>
    <select class="input @if ($err('district')) invalid @endif" id="{{ $id }}district" name="district" required>
        <option value="">Select district</option>
        @foreach (config('locations.divisions') as $division => $districts)
            <optgroup label="{{ $division }}">
                @foreach ($districts as $district)
                    <option value="{{ $district }}" @selected($val('district') === $district)>{{ $district }}</option>
                @endforeach
            </optgroup>
        @endforeach
    </select>
    <p class="err">{{ $err('district') }}</p>
</div>

<div>
    <label class="lbl" for="{{ $id }}area">Area / Thana <span class="text-danger">*</span></label>
    <input class="input @if ($err('area')) invalid @endif" id="{{ $id }}area" name="area" type="text" required maxlength="120" placeholder="e.g. Dhanmondi" value="{{ $val('area') }}">
    <p class="err">{{ $err('area') }}</p>
</div>

<div class="sm:col-span-2">
    <label class="lbl" for="{{ $id }}address">Full address <span class="text-danger">*</span></label>
    <textarea class="input @if ($err('address')) invalid @endif" id="{{ $id }}address" name="address" rows="2" required maxlength="500" placeholder="House, road, block, landmark">{{ $val('address') }}</textarea>
    <p class="err">{{ $err('address') }}</p>
</div>

<div class="sm:col-span-2">
    <label class="chk">
        <input type="checkbox" name="is_default" value="1" @checked($submitted ? old('is_default') : ($saved?->is_default ?? false))>
        <span>Use as my default address</span>
    </label>
</div>
