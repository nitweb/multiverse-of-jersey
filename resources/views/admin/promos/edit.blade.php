@extends('admin.dashboard.dashboard')
@section('admin_title', 'Promos · Nebula Mart Admin')
@section('admin_contents')

    <div>
        <h1 class="font-display font-extrabold text-2xl sm:text-[28px] tracking-tight">Promos</h1>
        <p class="text-ink/55 mt-1">Two promo banners shown on the homepage.</p>
    </div>

    <form method="POST" action="{{ route('admin.promos.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid md:grid-cols-2 gap-6 items-start">
            @foreach ($promos as $slot => $promo)
                <section class="card">
                    <div class="card-h">
                        <h3>{{ \App\Models\Promo::SLOTS[$slot] }}</h3>
                    </div>
                    <div class="card-b space-y-5">
                        <div>
                            <label class="lbl" for="link{{ $slot }}">Link</label>
                            <input class="input" id="link{{ $slot }}" name="promos[{{ $slot }}][link]" type="text" value="{{ old("promos.$slot.link", $promo->link) }}" placeholder="/shop?cat=retro">
                            @error("promos.$slot.link")
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="drop block">
                                <input type="file" name="promos[{{ $slot }}][image]" class="hidden" accept="image/*" data-preview="#pimg{{ $slot }}">
                                <img id="pimg{{ $slot }}" src="{{ $promo->image_url ?? asset('assets/admin/img/no-image.svg') }}" class="w-full max-h-64 object-cover rounded-2xl mb-3" alt="">
                                <p class="font-bold">Upload promo image</p>
                                <p class="text-xs text-ink/50 mt-1">Auto converted to 1200×480 · WEBP</p>
                            </label>
                            @error("promos.$slot.image")
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </section>
            @endforeach
        </div>

        <div class="savebar mt-6">
            <button type="submit" class="btn btn-primary">
                <svg class="ic ic-sm">
                    <use href="#i-check" />
                </svg>Save promos
            </button>
        </div>
    </form>

@endsection
