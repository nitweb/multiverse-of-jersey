@if ($featuredProducts->isNotEmpty())

    <section class="section" style="padding-block: 20px;">

        <div class="container-x">

            <div class="sec-head">
                <div>
                    <p class="eyebrow">Handpicked for you</p>
                    <h2>Featured products</h2>
                </div>
                <a href="{{ route('shop') }}" class="link-arrow">
                    View all jerseys
                    <svg class="ic w-4 h-4" aria-hidden="true">
                        <use href="#i-arrow-right" />
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
                @foreach ($featuredProducts as $product)
                    @include('frontend.home._product_card', ['product' => $product])
                @endforeach
            </div>

        </div>

    </section>
@endif
