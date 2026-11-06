/* Wishlist page: take a card off the grid as soon as its heart is switched off. */
(function () {
  'use strict';
  var grid = document.getElementById('wish-grid'), empty = document.getElementById('wish-empty'); if (!grid) return;

  document.addEventListener('wish:change', function (e) {
    var d = e.detail || {}; if (d.wished) return;
    var card = grid.querySelector('.pcard[data-id="' + d.id + '"]'); if (card) card.remove();
    if (!grid.querySelector('.pcard')) { grid.hidden = true; if (empty) empty.hidden = false; }
  });
})();
