/* Shared order summary (cart + checkout): totals, coupon, free-delivery bar. All numbers come from the server. */
(function () {
  'use strict';
  var $ = function (s, c) { return (c || document).querySelector(s); };
  var S = JBD.summary = {
    showShip: false, // checkout turns this on once a district is chosen
    render: function () {
      var c = JBD.cart.summary(), sub = c.subtotal || 0, d = c.discount || 0, ship = c.shipping || {};
      var set = function (s, v) { var e = $(s); if (e) e.textContent = v; };
      set('[data-sum-sub]', JBD.fmt(sub));
      var row = $('[data-discount-row]'); if (row) row.hidden = !(c.coupon && (d || c.coupon.free_delivery));
      set('[data-sum-discount]', c.coupon && c.coupon.free_delivery && !d ? 'Free delivery' : '-' + JBD.fmt(d));
      set('[data-coupon-label]', c.coupon ? '(' + c.coupon.code + ')' : '');
      set('[data-sum-ship]', !S.showShip ? 'Calculated at checkout' : (ship.free ? 'Free' : JBD.fmt(ship.fee || 0)));
      set('[data-sum-total]', JBD.fmt(S.showShip ? c.total : Math.max(0, sub - d)));
      var bar = $('[data-ship-bar]');
      if (bar) {
        var rem = c.free_delivery_remaining || 0, unlocked = ship.free_reason === 'threshold';
        var box = bar.closest('.ship-bar'); if (box) box.hidden = !(rem > 0 || unlocked);
        bar.style.width = (unlocked ? 100 : Math.min(100, sub / ((sub + rem) || 1) * 100)) + '%';
        set('[data-ship-msg]', unlocked ? '🎉 You’ve unlocked free delivery!' : 'Add ' + JBD.fmt(rem) + ' more for free delivery');
      }
      var items = $('[data-sum-items]');
      if (items) items.innerHTML = (c.items || []).map(function (x) {
        return '<div class="flex items-center gap-3"><span class="relative shrink-0"><img src="' + JBD.esc(x.image) + '" alt="" class="w-14 h-14 rounded-xl object-cover bg-bg"><i class="absolute -top-2 -right-2 min-w-5 h-5 px-1 rounded-full bg-ink text-bg text-[11px] font-bold grid place-items-center not-italic">' + x.quantity + '</i></span><div class="min-w-0 flex-1"><p class="text-sm font-semibold leading-snug line-clamp-2">' + JBD.esc(x.name) + '</p>' + (x.variant ? '<p class="text-xs text-ink/50">' + JBD.esc(x.variant) + '</p>' : '') + '</div><b class="text-sm">' + JBD.fmt(x.line_total) + '</b></div>';
      }).join('');
      var btn = $('[data-checkout-btn]');
      if (btn) { if (c.checkout_url) btn.href = c.checkout_url; btn.classList.toggle('opacity-50', !c.can_checkout); btn.classList.toggle('pointer-events-none', !c.can_checkout); }
      var form = $('[data-coupon-form]'), msg = $('[data-coupon-msg]');
      if (form && msg && !S.busy) {
        if (c.coupon) { form.coupon.value = c.coupon.code; msg.textContent = 'Coupon ' + c.coupon.code + ' applied: ' + c.coupon.label + '. Leave the box empty and press Apply to remove it.'; msg.className = 'text-xs mt-2 text-success'; }
        else if (!S.keepMsg) { msg.textContent = ''; }
      }
      var notes = $('#cart-notices');
      if (notes) notes.innerHTML = (c.notices || []).map(function (n) { return '<p class="rounded-xl bg-accent-soft text-danger font-semibold text-sm px-4 py-3">' + JBD.esc(n) + '</p>'; }).join('');
      document.dispatchEvent(new CustomEvent('summary:render', { detail: c }));
    }
  };
  var f = $('[data-coupon-form]'), msg = $('[data-coupon-msg]');
  if (f) {
    f.addEventListener('submit', function (e) {
      e.preventDefault(); var code = f.coupon.value.trim(), applied = JBD.cart.summary().coupon;
      S.busy = true; S.keepMsg = true;
      var req = !code ? (applied ? JBD.cart.couponRemove() : Promise.resolve({ ok: true })) : JBD.cart.coupon(code);
      req.then(function (r) {
        S.busy = false;
        if (!r.ok) { msg.textContent = r.message || 'This code is not valid.'; msg.className = 'text-xs mt-2 text-danger'; return; }
        if (!code) { msg.textContent = applied ? 'Coupon removed.' : ''; msg.className = 'text-xs mt-2 text-ink/60'; f.coupon.value = ''; }
        S.render();
      });
    });
  }
  document.addEventListener('cart:change', S.render);
  S.render();
})();
