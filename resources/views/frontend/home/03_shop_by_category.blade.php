@if ($homeCategories->isNotEmpty())

    @php
        $reps = max(1, (int) ceil(10 / $homeCategories->count()));
        $duration = max(30, $homeCategories->count() * $reps * 4);
    @endphp

    <style>
        .cat-marquee{overflow:hidden}
        .cat-mtrack{display:flex;width:max-content;animation:cat-scroll var(--cat-dur,40s) linear infinite;will-change:transform}
        .cat-group{display:flex;flex:none}
        .cat-marquee .cat-tile{flex:none;width:150px;aspect-ratio:1/1;margin-right:12px}
        @media(min-width:640px){.cat-marquee .cat-tile{width:200px;margin-right:16px}}
        @media(min-width:1024px){.cat-marquee .cat-tile{width:220px}}
        .cat-name b{transition:color .25s}
        .cat-tile:hover .cat-name b,.cat-tile:focus-visible .cat-name b{color:rgb(52 211 153)}
        @keyframes cat-scroll{from{transform:translate3d(0,0,0)}to{transform:translate3d(-50%,0,0)}}
    </style>

    <section class="section">

        <div class="container-x">

            <div class="sec-head">
                <div>
                    <p class="eyebrow">Collections</p>
                    <h2>Shop by category</h2>
                </div>
                <a href="{{ route('shop') }}" class="link-arrow">
                    All jerseys
                    <svg class="ic w-4 h-4" aria-hidden="true">
                        <use href="#i-arrow-right" />
                    </svg>
                </a>
            </div>

            <div class="cat-marquee">

                <div class="cat-mtrack" style="--cat-dur: {{ $duration }}s">
                    @foreach ([false, true] as $hidden)
                        <div class="cat-group" @if ($hidden) aria-hidden="true" @endif>
                            @for ($i = 0; $i < $reps; $i++)
                                @foreach ($homeCategories as $category)
                                    <a href="{{ route('category.show', $category) }}" class="cat-tile group" @if ($hidden) tabindex="-1" @endif>
                                        <img src="{{ $category->image_url ?? asset('assets/admin/img/no-image.svg') }}" alt="{{ $hidden ? '' : $category->name }}" loading="lazy">
                                        <span class="cat-name">
                                            <b>{{ $category->name }}</b>
                                            @if ($category->description)
                                                <small>{{ \Illuminate\Support\Str::limit($category->description, 40) }}</small>
                                            @endif
                                        </span>
                                    </a>
                                @endforeach
                            @endfor
                        </div>
                    @endforeach
                </div>

            </div>

        </div>

    </section>

@endif
