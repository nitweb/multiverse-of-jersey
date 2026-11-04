@if ($homeCategories->isNotEmpty())

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

            <div class="cat-slider">

                <button type="button" class="cat-nav prev" data-cat-prev aria-label="Previous categories">
                    <svg class="ic" aria-hidden="true">
                        <use href="#i-chev-left" />
                    </svg>
                </button>

                <div class="cat-track" data-cat-track>
                    @foreach ($homeCategories as $category)
                        <a href="{{ route('category.show', $category) }}" class="cat-tile group">
                            <img src="{{ $category->image_url ?? asset('assets/admin/img/no-image.svg') }}" alt="{{ $category->name }}" loading="lazy">
                            <span class="cat-name">
                                <b>{{ $category->name }}</b>
                                @if ($category->description)
                                    <small>{{ \Illuminate\Support\Str::limit($category->description, 40) }}</small>
                                @endif
                            </span>
                        </a>
                    @endforeach
                </div>

                <button type="button" class="cat-nav next" data-cat-next aria-label="Next categories">
                    <svg class="ic" aria-hidden="true">
                        <use href="#i-chev-right" />
                    </svg>
                </button>

            </div>

        </div>

    </section>

@endif
