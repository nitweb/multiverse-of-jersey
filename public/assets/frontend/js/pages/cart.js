/* Cart page (server cart) */
(function () {
  'use strict';
  var $ = function (s, c) { return (c || document).querySelector(s); };
  var lines = $('[data-cart-lines]'); if (!lines) return;
  function draw() {
    var c = JBD.cart.get(); $('#cart-empty').hidden = c.length > 0; $('#cart-filled').hidden = !c.length;
    lines.innerHTML = c.map(function (x) {
      return '<div class="cart-line" data-key="' + JBD.esc(x.key) + '"><a href="' + JBD.esc(x.url) + '" class="flex gap-4 min-w-0"><img src="' + JBD.esc(x.image) + '" alt=""><span class="min-w-0"><b class="font-semibold leading-snug line-clamp-2 block">' + JBD.esc(x.name) + '</b>' + (x.variant ? '<small class="text-ink/50">' + JBD.esc(x.variant) + '</small>' : '') + '</span></a>' +
        '<span class="cl-price">' + JBD.fmt(x.unit_price) + '</span><div class="qty cl-qty"><button type="button" data-dq="-1" aria-label="Decrease"><svg class="ic" style="width:14px;height:14px"><use href="#i-minus"/></svg></button><input value="' + x.quantity + '" readonly aria-label="Quantity"><button type="button" data-dq="1" aria-label="Increase"><svg class="ic" style="width:14px;height:14px"><use href="#i-plus"/></svg></button></div>' +
        '<span class="cl-total"><b class="font-display">' + JBD.fmt(x.line_total) + '</b><button type="button" class="text-ink/40 hover:text-danger" data-drm="' + JBD.esc(x.key) + '" aria-label="Remove"><svg class="ic" style="width:18px;height:18px"><use href="#i-trash"/></svg></button></span></div>';
    }).join('');
  }
  $('[data-clear-cart]').addEventListener('click', function () { if (confirm('Remove all items from your cart?')) JBD.cart.clear().then(function (r) { if (!r.ok) JBD.done(r); }); });
  document.addEventListener('cart:change', draw); draw();
})();
