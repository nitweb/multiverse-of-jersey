@if ($homeBrands->isNotEmpty())

    @php
        $reps = max(1, (int) ceil(12 / $homeBrands->count()));
        $duration = max(24, $homeBrands->count() * $reps * 3);
    @endphp

    <style>
        .brand-marquee{overflow:hidden}
        .brand-track{display:flex;width:max-content;animation:brand-scroll var(--brand-dur,30s) linear infinite;will-change:transform}
        .brand-group{display:flex;flex:none}
        .brand-marquee .brand-chip{flex:none;width:160px;margin-right:12px}
        @media(min-width:640px){.brand-marquee .brand-chip{width:190px}}
        @keyframes brand-scroll{from{transform:translate3d(0,0,0)}to{transform:translate3d(-50%,0,0)}}
    </style>

    <section class="border-y border-line bg-surface">

        <div class="container-x py-8">

            <p class="text-center text-xs font-bold uppercase tracking-[.2em] text-ink/45 mb-5">Brands we stock</p>

            <div class="brand-marquee">

                <div class="brand-track" style="--brand-dur: {{ $duration }}s">
                    @foreach ([false, true] as $hidden)
                        <div class="brand-group" @if ($hidden) aria-hidden="true" @endif>
                            @for ($i = 0; $i < $reps; $i++)
                                @foreach ($homeBrands as $brand)
                                    <a href="{{ route('brand.show', $brand) }}" class="brand-chip" @if ($hidden) tabindex="-1" @endif>{{ $brand->name }}</a>
                                @endforeach
                            @endfor
                        </div>
                    @endforeach
                </div>

            </div>

        </div>

    </section>
@endif
