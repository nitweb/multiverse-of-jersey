<footer class="site-footer">

    <div class="container-x pt-14 pb-8">

        <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-[1.4fr_1fr_1fr_1.2fr]">

            <div>

                <a href="{{ route('home') }}" aria-label="Multiverse of Jersey">
                    <img src="{{ asset('assets/frontend/img/logo_white.png') }}" alt="Multiverse of Jersey" class="w-auto" style="height:70px;">
                </a>

                <p class="mt-4 text-sm text-white/60 max-w-sm">Authentic football jerseys for collectors in Bangladesh. Verified stock, careful packaging and delivery to all 64 districts.</p>

                <div class="flex gap-2 mt-5">
                    <a href="#" class="soc" aria-label="Facebook">
                        <svg class="ic " aria-hidden="true">
                            <use href="#i-facebook" />
                        </svg>
                    </a>
                    <a href="#" class="soc" aria-label="Instagram">
                        <svg class="ic " aria-hidden="true">
                            <use href="#i-instagram" />
                        </svg>
                    </a>
                    <a href="#" class="soc" aria-label="YouTube">
                        <svg class="ic " aria-hidden="true">
                            <use href="#i-youtube" />
                        </svg>
                    </a>
                    <a href="#" class="soc" aria-label="TikTok">
                        <svg class="ic " aria-hidden="true">
                            <use href="#i-tiktok" />
                        </svg>
                    </a>
                </div>

            </div>

            <div>
                <h4>Collections</h4>
                <ul>
                    @foreach ($menuCategories->take(6) as $menuCategory)
                        <li><a href="{{ $menuCategory->url }}">{{ $menuCategory->name }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4>Help</h4>
                <ul>
                    <li><a href="{{ route('track.show') }}">Track order</a></li>
                    <li><a href="faq.html">FAQs</a></li>
                    <li><a href="page.html">Shipping &amp; returns</a></li>
                    <li><a href="page.html">Terms &amp; conditions</a></li>
                    <li><a href="page.html">Privacy policy</a></li>
                    <li><a href="account.html">My account</a></li>
                </ul>
            </div>

            <div>
                <h4>Contact</h4>
                <ul class="space-y-3 text-sm text-white/65">
                    <li class="flex gap-2.5">
                        <svg class="ic w-4 h-4 mt-0.5" aria-hidden="true">
                            <use href="#i-pin" />
                        </svg>
                        <span>House 12, Road 5, Dhanmondi, Dhaka 1209</span>
                    </li>
                    <li class="flex gap-2.5">
                        <svg class="ic w-4 h-4 mt-0.5" aria-hidden="true">
                            <use href="#i-phone" />
                        </svg>
                        <a href="tel:+8801700000000">+880 1700-000000</a>
                    </li>
                    <li class="flex gap-2.5">
                        <svg class="ic w-4 h-4 mt-0.5" aria-hidden="true">
                            <use href="#i-mail" />
                        </svg>
                        <a href="mailto:support@multiverseofjersey.com">support@multiverseofjersey.com</a>
                    </li>
                    <li class="flex gap-2.5">
                        <svg class="ic w-4 h-4 mt-0.5" aria-hidden="true">
                            <use href="#i-clock" />
                        </svg>
                        <span>Sat – Thu, 10:00 AM – 9:00 PM</span>
                    </li>
                </ul>
            </div>

        </div>

        <div class="mt-12 pt-6 border-t border-white/10 flex flex-col sm:flex-row gap-3 items-center justify-between text-xs text-white/50">
            <p>© 2026 Multiverse of Jersey. All rights reserved.</p>
            <p class="flex items-center gap-2">
                <svg class="ic w-4 h-4" aria-hidden="true">
                    <use href="#i-truck" />
                </svg>
                Cash on Delivery · Pathao · Steadfast · RedX
            </p>
        </div>

    </div>

</footer>
