@if ($banners->isNotEmpty())
    @php($hasMultipleSlides = $banners->count() > 1)

    <section class="hero" aria-roledescription="carousel" aria-label="Featured collections">

        <div class="hero-track">

            @foreach ($banners as $banner)
                @php($bannerLink = $banner->link ? (\Illuminate\Support\Str::startsWith($banner->link, ['http://', 'https://']) ? $banner->link : url($banner->link)) : null)

                <div class="hero-slide {{ $loop->first ? 'is-active' : '' }}" data-slide>
                    @if ($bannerLink)
                        <a href="{{ $bannerLink }}" aria-label="{{ $banner->alt_text }}">
                            <img src="{{ $banner->image_url }}" alt="{{ $banner->alt_text }}" class="hero-img" @unless ($loop->first) loading="lazy" @endunless>
                        </a>
                    @else
                        <img src="{{ $banner->image_url }}" alt="{{ $banner->alt_text }}" class="hero-img" @unless ($loop->first) loading="lazy" @endunless>
                    @endif
                </div>
            @endforeach

        </div>

        <button type="button" class="hero-nav prev" data-hero-prev aria-label="Previous slide" @unless ($hasMultipleSlides) style="display:none" @endunless>
            <svg class="ic " aria-hidden="true">
                <use href="#i-chev-left" />
            </svg>
        </button>

        <button type="button" class="hero-nav next" data-hero-next aria-label="Next slide" @unless ($hasMultipleSlides) style="display:none" @endunless>
            <svg class="ic " aria-hidden="true">
                <use href="#i-chev-right" />
            </svg>
        </button>

        <div class="hero-dots" data-hero-dots @unless ($hasMultipleSlides) style="display:none" @endunless></div>

    </section>
@endif
