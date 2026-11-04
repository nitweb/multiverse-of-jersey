/* Wishlist page: renders saved items from localStorage */
(function () {
  'use strict';
  var grid = document.getElementById('wish-grid'), empty = document.getElementById('wish-empty'); if (!grid) return;
  function draw() {
    var w = JBD.wish.get(); empty.hidden = w.length > 0; grid.hidden = !w.length;
    grid.innerHTML = w.map(function (x) {
      var off = x.compare > 0 ? Math.round((x.compare - x.price) * 100 / x.compare) : 0;
      return '<article class="pcard" data-id="' + JBD.esc(x.id) + '" data-name="' + JBD.esc(x.name) + '" data-price="' + x.price + '" data-compare="' + (x.compare || 0) + '" data-image="' + JBD.esc(x.image) + '" data-url="' + JBD.esc(x.url) + '" data-cat="' + JBD.esc(x.cat || '') + '" data-brand="' + JBD.esc(x.brand || '') + '" data-sizes="' + JBD.esc(x.sizes || 'S,M,L,XL,XXL') + '" data-stock="' + (x.stock || 1) + '">' +
        '<div class="pcard-img"><a href="' + JBD.esc(x.url) + '"><img src="' + JBD.esc(x.image) + '" alt="' + JBD.esc(x.name) + '"></a>' + (off ? '<div class="pcard-badges"><span class="badge bg-accent text-white">-' + off + '%</span></div>' : '') +
        '<button type="button" class="wish-btn" data-wish aria-pressed="true" aria-label="Remove from wishlist"><svg class="ic"><use href="#i-heart"/></svg></button></div>' +
        '<div class="pcard-body"><h3 class="font-display font-bold text-[15px] leading-snug line-clamp-2"><a href="' + JBD.esc(x.url) + '" class="hover:text-primary">' + JBD.esc(x.name) + '</a></h3><div class="flex items-end justify-between gap-2 mt-3"><p class="leading-none"><b class="font-display font-extrabold text-lg">' + JBD.fmt(x.price) + '</b>' + (x.compare > 0 ? '<s class="ml-1.5 text-xs text-ink/40">' + JBD.fmt(x.compare) + '</s>' : '') + '</p>' +
        '<button type="button" class="btn-add" data-quick-add aria-label="Add to cart"><svg class="ic" style="width:16px;height:16px"><use href="#i-bag"/></svg><span>Add</span></button></div></div></article>';
    }).join('');
  }
  document.addEventListener('wish:change', draw); draw();
})();
