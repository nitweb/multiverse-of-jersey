/* Shared order summary (cart + checkout): totals, coupon, free-delivery bar */
(function () {
  'use strict';
  var $ = function (s, c) { return (c || document).querySelector(s); };
  var S = JBD.summary = {
    coupon: function () { return JBD.read('jbd_coupon', null); },
    discount: function (sub) { var c = S.coupon(); if (!c) return 0; return Math.min(sub, Math.round(c.type === 'percent' ? sub * c.value / 100 : c.value)); },
    ship: null, // set by page (number) or null = not chosen yet
    render: function () {
      var sub = JBD.cart.subtotal(), d = S.discount(sub), free = sub >= JBD.cfg.freeAbove, ship = S.ship == null ? null : (free ? 0 : S.ship);
      var set = function (s, v) { var e = $(s); if (e) e.textContent = v; };
      set('[data-sum-sub]', JBD.fmt(sub)); var row = $('[data-discount-row]'); if (row) row.hidden = !d;
      set('[data-sum-discount]', '-' + JBD.fmt(d)); set('[data-coupon-label]', S.coupon() ? '(' + S.coupon().label + ')' : '');
      set('[data-sum-ship]', ship == null ? 'Calculated at checkout' : (ship === 0 ? 'Free' : JBD.fmt(ship)));
      set('[data-sum-total]', JBD.fmt(sub - d + (ship || 0)));
      var bar = $('[data-ship-bar]'); if (bar) { bar.style.width = Math.min(100, sub / JBD.cfg.freeAbove * 100) + '%'; set('[data-ship-msg]', free ? '🎉 You’ve unlocked free delivery!' : 'Add ' + JBD.fmt(JBD.cfg.freeAbove - sub) + ' more for free delivery'); }
      var items = $('[data-sum-items]'); if (items) items.innerHTML = JBD.cart.get().map(function (x) {
        return '<div class="flex items-center gap-3"><span class="relative shrink-0"><img src="' + JBD.esc(x.image) + '" alt="" class="w-14 h-14 rounded-xl object-cover bg-bg"><i class="absolute -top-2 -right-2 min-w-5 h-5 px-1 rounded-full bg-ink text-bg text-[11px] font-bold grid place-items-center not-italic">' + x.qty + '</i></span><div class="min-w-0 flex-1"><p class="text-sm font-semibold leading-snug line-clamp-2">' + JBD.esc(x.name) + '</p><p class="text-xs text-ink/50">Size ' + JBD.esc(x.size) + (x.opt ? ' · ' + JBD.esc(x.opt) : '') + '</p></div><b class="text-sm">' + JBD.fmt(x.price * x.qty) + '</b></div>';
      }).join('');
      var btn = $('[data-checkout-btn]'); if (btn) { var low = sub < JBD.cfg.minOrder; btn.classList.toggle('opacity-50', low); btn.classList.toggle('pointer-events-none', low); }
      document.dispatchEvent(new CustomEvent('summary:render', { detail: { sub: sub, discount: d, ship: ship || 0, total: sub - d + (ship || 0) } }));
    }
  };
  var f = $('[data-coupon-form]'), msg = $('[data-coupon-msg]');
  if (f) {
    var cur = S.coupon(); if (cur && msg) { msg.textContent = 'Coupon ' + cur.label + ' applied.'; msg.className = 'text-xs mt-2 text-success'; f.coupon.value = cur.label; }
    f.addEventListener('submit', function (e) {
      e.preventDefault(); var code = f.coupon.value.trim().toUpperCase(), c = JBD.cfg.coupons[code];
      if (!code) { JBD.write('jbd_coupon', null); msg.textContent = ''; } else if (!c) { msg.textContent = 'Invalid or expired coupon.'; msg.className = 'text-xs mt-2 text-danger'; return; } else { JBD.write('jbd_coupon', c); msg.textContent = 'Coupon ' + code + ' applied.'; msg.className = 'text-xs mt-2 text-success'; }
      S.render();
    });
  }
  document.addEventListener('cart:change', S.render);
})();
