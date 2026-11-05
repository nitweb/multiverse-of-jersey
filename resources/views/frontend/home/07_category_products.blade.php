@foreach ($categorySections as $section)

    <section class="section{{ $loop->first ? '' : ' !pt-0' }}">

        <div class="container-x">

            <div class="sec-head">
                <div>
                    <p class="eyebrow">Collection</p>
                    <h2>{{ $section['category']->name }}</h2>
                </div>
                <a href="{{ route('category.show', $section['category']) }}" class="link-arrow">
                    View all
                    <svg class="ic w-4 h-4" aria-hidden="true">
                        <use href="#i-arrow-right" />
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
                @foreach ($section['products'] as $product)
                    @include('frontend.home._product_card', ['product' => $product])
                @endforeach
            </div>

        </div>

    </section>
@endforeach
