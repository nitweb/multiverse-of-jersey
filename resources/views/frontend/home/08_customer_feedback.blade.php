@if ($feedbacks->isNotEmpty())

    @php
        $reps = max(1, (int) ceil(8 / $feedbacks->count()));
        $duration = max(24, $feedbacks->count() * $reps * 4);
    @endphp

    <style>
        .fb-marquee{overflow:hidden}
        .fb-track{display:flex;width:max-content;animation:fb-scroll var(--fb-dur,40s) linear infinite;will-change:transform}
        .fb-group{display:flex;flex:none}
        .fb-item{flex:none;width:160px;aspect-ratio:4/5;margin-right:12px;border-radius:var(--radius);overflow:hidden;background:rgb(var(--color-bg));border:1px solid rgb(var(--color-line))}
        .fb-item img{width:100%;height:100%;object-fit:cover;display:block}
        @media(min-width:640px){.fb-item{width:220px;margin-right:16px}}
        @media(min-width:1024px){.fb-item{width:240px}}
        @keyframes fb-scroll{from{transform:translate3d(0,0,0)}to{transform:translate3d(-50%,0,0)}}
    </style>

    <section class="section{{ $categorySections->isNotEmpty() ? ' !pt-0' : '' }}">

        <div class="container-x">

            <div class="sec-head" style="justify-content:center;text-align:center">
                <div>
                    <p class="eyebrow">Real photos from verified buyers</p>
                    <h2>Customers Feedback</h2>
                </div>
            </div>

            <div class="fb-marquee">

                <div class="fb-track" style="--fb-dur: {{ $duration }}s">
                    @foreach ([false, true] as $hidden)
                        <div class="fb-group" @if ($hidden) aria-hidden="true" @endif>
                            @for ($i = 0; $i < $reps; $i++)
                                @foreach ($feedbacks as $feedback)
                                    <figure class="fb-item">
                                        <img src="{{ $feedback->image_url }}" alt="{{ $hidden ? '' : 'Customer feedback photo' }}" loading="lazy">
                                    </figure>
                                @endforeach
                            @endfor
                        </div>
                    @endforeach
                </div>

            </div>

        </div>

    </section>
@endif
