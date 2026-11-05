/* Home page: hero slider, product tabs */
(function () {
  'use strict';
  var $ = function (s, c) { return (c || document).querySelector(s); }, $$ = function (s, c) { return [].slice.call((c || document).querySelectorAll(s)); };

  /* hero slider */
  var slides = $$('[data-slide]'), dotsBox = $('[data-hero-dots]'), cur = 0, timer, hero = $('.hero');
  if (slides.length) {
    slides.forEach(function (_, i) { var b = document.createElement('button'); b.type = 'button'; b.setAttribute('aria-label', 'Go to slide ' + (i + 1)); b.addEventListener('click', function () { go(i, true); }); dotsBox.appendChild(b); });
    var dots = [].slice.call(dotsBox.children);
    function go(i, user) {
      cur = (i + slides.length) % slides.length;
      slides.forEach(function (s, n) { s.classList.toggle('is-active', n === cur); s.setAttribute('aria-hidden', n !== cur); });
      dots.forEach(function (d, n) { d.classList.toggle('is-active', n === cur); });
      if (user) restart();
    }
    function restart() { clearInterval(timer); if (!matchMedia('(prefers-reduced-motion: reduce)').matches) timer = setInterval(function () { go(cur + 1); }, 6000); }
    $('[data-hero-prev]').addEventListener('click', function () { go(cur - 1, true); });
    $('[data-hero-next]').addEventListener('click', function () { go(cur + 1, true); });
    hero.addEventListener('mouseenter', function () { clearInterval(timer); }); hero.addEventListener('mouseleave', restart);
    var sx = 0; hero.addEventListener('touchstart', function (e) { sx = e.touches[0].clientX; }, { passive: true });
    hero.addEventListener('touchend', function (e) { var dx = e.changedTouches[0].clientX - sx; if (Math.abs(dx) > 40) go(cur + (dx < 0 ? 1 : -1), true); });
    go(0); restart();
  }

  /* new-arrivals tabs */
  var tabs = $('[data-tabs]'), grid = $('[data-tab-grid]');
  if (tabs && grid) tabs.addEventListener('click', function (e) {
    var b = e.target.closest('[data-tab]'); if (!b) return;
    $$('.pill', tabs).forEach(function (p) { p.classList.toggle('is-active', p === b); });
    $$('.pcard', grid).forEach(function (c) { c.hidden = b.dataset.tab !== 'all' && c.dataset.cat !== b.dataset.tab; });
  });

})();
