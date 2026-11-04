/* Home page: hero slider, flash-deal countdown, product tabs */
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

  /* countdown (data-countdown = end date; rolls forward weekly so the demo never expires) */
  var cd = $('[data-countdown]');
  if (cd) {
    var end = new Date(cd.dataset.countdown).getTime(), week = 7 * 864e5;
    var pad = function (n) { return String(n).padStart(2, '0'); };
    var tick = function () {
      var diff = end - Date.now(); while (diff <= 0) { end += week; diff = end - Date.now(); }
      var s = Math.floor(diff / 1000);
      $('[data-d]', cd).textContent = pad(Math.floor(s / 86400)); $('[data-h]', cd).textContent = pad(Math.floor(s % 86400 / 3600));
      $('[data-m]', cd).textContent = pad(Math.floor(s % 3600 / 60)); $('[data-s]', cd).textContent = pad(s % 60);
    };
    tick(); setInterval(tick, 1000);
  }

  /* new-arrivals tabs */
  var tabs = $('[data-tabs]'), grid = $('[data-tab-grid]');
  if (tabs && grid) tabs.addEventListener('click', function (e) {
    var b = e.target.closest('[data-tab]'); if (!b) return;
    $$('.pill', tabs).forEach(function (p) { p.classList.toggle('is-active', p === b); });
    $$('.pcard', grid).forEach(function (c) { c.hidden = b.dataset.tab !== 'all' && c.dataset.cat !== b.dataset.tab; });
  });

  /* shop by category slider (autoplay + infinite loop) */
  var catTrack = $('[data-cat-track]');
  if (catTrack) {
    var catPrev = $('[data-cat-prev]'), catNext = $('[data-cat-next]'), catSlider = catTrack.parentNode;
    var catOriginals = [].slice.call(catTrack.children), catCount = catOriginals.length;
    var catCols = 1, catStepPx = 0, catTimer, catSettle, catResize, catTouching = false;

    var catClone = function (el) {
      var c = el.cloneNode(true);
      c.setAttribute('data-clone', ''); c.setAttribute('aria-hidden', 'true'); c.tabIndex = -1;
      return c;
    };
    var catJump = function (x) {
      catTrack.style.scrollSnapType = 'none'; catTrack.scrollLeft = x;
      requestAnimationFrame(function () { catTrack.style.scrollSnapType = ''; });
    };
    var catStep = function (dir) { catTrack.scrollBy({ left: dir * catStepPx, behavior: 'smooth' }); };
    var catPause = function () { clearInterval(catTimer); };
    var catPlay = function () {
      catPause();
      if (catCount > catCols && !matchMedia('(prefers-reduced-motion: reduce)').matches) {
        catTimer = setInterval(function () { if (!document.hidden) catStep(1); }, 3000);
      }
    };
    var catWrap = function () {
      if (catTouching || catCount <= catCols) return;
      var start = catCols * catStepPx, span = catCount * catStepPx, x = catTrack.scrollLeft;
      if (x >= start + span - 2) catJump(x - span); else if (x < start - 2) catJump(x + span);
    };
    var catBuild = function () {
      [].slice.call(catTrack.querySelectorAll('[data-clone]')).forEach(function (n) { n.remove(); });
      var cs = getComputedStyle(catSlider);
      catCols = parseInt(cs.getPropertyValue('--cat-cols'), 10) || 1;
      catStepPx = catOriginals[0].offsetWidth + (parseFloat(cs.getPropertyValue('--cat-gap')) || 0);
      var loop = catCount > catCols;
      catPrev.hidden = catNext.hidden = !loop;
      if (!loop) { catJump(0); return; }
      catOriginals.slice(-catCols).forEach(function (el) { catTrack.insertBefore(catClone(el), catOriginals[0]); });
      catOriginals.slice(0, catCols).forEach(function (el) { catTrack.appendChild(catClone(el)); });
      catJump(catCols * catStepPx);
    };

    catPrev.addEventListener('click', function () { catStep(-1); catPlay(); });
    catNext.addEventListener('click', function () { catStep(1); catPlay(); });
    catTrack.addEventListener('scroll', function () { clearTimeout(catSettle); catSettle = setTimeout(catWrap, 120); }, { passive: true });
    catSlider.addEventListener('mouseenter', catPause); catSlider.addEventListener('mouseleave', catPlay);
    catSlider.addEventListener('focusin', catPause); catSlider.addEventListener('focusout', catPlay);
    catSlider.addEventListener('touchstart', function () { catTouching = true; catPause(); }, { passive: true });
    catSlider.addEventListener('touchend', function () { catTouching = false; catPlay(); clearTimeout(catSettle); catSettle = setTimeout(catWrap, 120); }, { passive: true });
    window.addEventListener('resize', function () { clearTimeout(catResize); catResize = setTimeout(function () { catBuild(); catPlay(); }, 150); });
    catBuild(); catPlay();
  }
})();
