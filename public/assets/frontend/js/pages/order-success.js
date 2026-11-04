/* Order success: show the order saved by checkout.js */
(function () {
  'use strict';
  var $ = function (s) { return document.querySelector(s); }, o = null;
  try { o = JSON.parse(sessionStorage.getItem('jbd_last_order')); } catch (e) {}
  if (!o) return;
  $('[data-o-number]').textContent = '#' + o.number; $('[data-o-total]').textContent = JBD.fmt(o.total);
  $('[data-o-address]').innerHTML = '<b>' + JBD.esc(o.name) + '</b><br>' + JBD.esc(o.phone) + '<br>' + JBD.esc(o.address);
  $('[data-o-items]').innerHTML = o.items.map(function (x) { return '<div class="flex items-center gap-3"><img src="' + JBD.esc(x.image) + '" alt="" class="w-14 h-14 rounded-xl object-cover bg-bg"><div class="min-w-0 flex-1"><p class="text-sm font-semibold truncate">' + JBD.esc(x.name) + '</p><p class="text-xs text-ink/50">Size ' + JBD.esc(x.size) + (x.opt ? ' · ' + JBD.esc(x.opt) : '') + ' · Qty ' + x.qty + '</p></div><b class="text-sm">' + JBD.fmt(x.price * x.qty) + '</b></div>'; }).join('');
})();
