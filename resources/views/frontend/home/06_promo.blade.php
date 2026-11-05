@if ($promos->isNotEmpty())
    <section class="section !pt-0">

        <div class="container-x grid md:grid-cols-2 gap-4 sm:gap-6">
            @foreach ($promos as $promo)
                @php($promoLink = $promo->link ? (\Illuminate\Support\Str::startsWith($promo->link, ['http://', 'https://']) ? $promo->link : url($promo->link)) : null)

                @if ($promoLink)
                    <a href="{{ $promoLink }}" class="promo group">
                        <img src="{{ $promo->image_url }}" alt="" loading="lazy">
                    </a>
                @else
                    <div class="promo">
                        <img src="{{ $promo->image_url }}" alt="" loading="lazy">
                    </div>
                @endif
            @endforeach
        </div>

    </section>
@endif
