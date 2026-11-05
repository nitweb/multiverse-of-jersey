<div class="grid lg:grid-cols-3 gap-6 items-start">
    <div class="lg:col-span-2 space-y-6">

        <section class="card">
            <div class="card-h">
                <h3>Photo</h3>
            </div>
            <div class="card-b">
                <label class="drop block">
                    <input type="file" name="image" class="hidden" accept="image/*" data-preview="#fimg">
                    <img id="fimg" src="{{ $feedback ? $feedback->image_url : asset('assets/admin/img/no-image.svg') }}" class="w-full max-h-96 object-contain rounded-2xl mb-3" alt="">
                    <p class="font-bold">Upload customer photo</p>
                    <p class="text-xs text-ink/50 mt-1">Auto converted to 800×1000 · WEBP</p>
                </label>
                @error('image')
                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>
        </section>

    </div>

    <div class="space-y-6">
        <section class="card">
            <div class="card-h">
                <h3>Visibility</h3>
            </div>
            <div class="card-b">
                <label class="lbl" for="is_active">Status</label>
                <select class="select" id="is_active" name="is_active">
                    <option value="1" @selected(old('is_active', $feedback?->is_active ?? true) == 1)>Active</option>
                    <option value="0" @selected(old('is_active', $feedback?->is_active ?? true) == 0)>Draft</option>
                </select>
            </div>
        </section>
    </div>
</div>

<div class="savebar mt-6">
    <a href="{{ route('admin.customer-feedbacks.index') }}" class="btn btn-ghost">Cancel</a>
    <button type="submit" class="btn btn-primary">
        <svg class="ic ic-sm">
            <use href="#i-check" />
        </svg>Save photo
    </button>
</div>
