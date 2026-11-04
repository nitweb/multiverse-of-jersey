/* Product page: gallery, size, qty, add to cart, tabs, sticky buy bar */
(function () {
  'use strict';
  var $ = function (s, c) { return (c || document).querySelector(s); }, $$ = function (s, c) { return [].slice.call((c || document).querySelectorAll(s)); };
  var prod = $('#product'); if (!prod) return;

  var main = $('#main-img');
  $$('[data-thumb]').forEach(function (t) { t.addEventListener('click', function () { main.src = t.dataset.thumb; $$('[data-thumb]').forEach(function (x) { x.classList.toggle('is-active', x === t); }); }); });

  var qty = $('#qty');
  $('[data-qty-minus]').addEventListener('click', function () { qty.value = Math.max(1, +qty.value - 1); });
  $('[data-qty-plus]').addEventListener('click', function () { qty.value = Math.min(10, +qty.value + 1); });
  qty.addEventListener('change', function () { qty.value = Math.max(1, Math.min(10, +qty.value || 1)); });

  var base = +prod.dataset.price, patch = $('#opt-patch'), cust = $('#opt-custom'), cname = $('#c-name'), cnum = $('#c-num');
  function unit() { return base + (patch.checked ? 200 : 0) + (cust.checked ? 250 : 0); }
  function price() { $('#price').textContent = JBD.fmt(unit()); $('[data-bar-price]').textContent = JBD.fmt(unit()); $('#custom-fields').hidden = !cust.checked; }
  [patch, cust].forEach(function (c) { c.addEventListener('change', price); }); price();

  function item() {
    var s = $('input[name=size]:checked'); if (!s) { $('#size-err').hidden = false; $('#sizes').scrollIntoView({ behavior: 'smooth', block: 'center' }); return null; }
    $('#size-err').hidden = true; var d = prod.dataset, opt = [];
    if (cust.checked) {
      var n = cname.value.trim(), x = cnum.value.trim(), bad = false;
      $('#c-name-err').textContent = n ? '' : 'Enter the custom name.'; $('#c-num-err').textContent = x !== '' && +x >= 0 && +x <= 99 ? '' : 'Enter a number (0–99).';
      if (!n || x === '' || +x < 0 || +x > 99) { cname.scrollIntoView({ behavior: 'smooth', block: 'center' }); return null; }
      opt.push('Name: ' + n.toUpperCase() + ' #' + x);
    }
    if (patch.checked) opt.unshift('UCL patch');
    return { id: d.id, opt: opt.join(' · '), name: d.name, price: unit(), compare: d.compare, image: d.image, url: d.url, size: s.value, qty: +qty.value };
  }
  $$('input[name=size]').forEach(function (i) { i.addEventListener('change', function () { $('#size-err').hidden = true; }); });
  $('#add-cart').addEventListener('click', function () { var i = item(); if (!i) return; JBD.cart.add(i); JBD.toast('Added to cart'); JBD.openDrawer(); });
  $('#buy-now').addEventListener('click', function () { var i = item(); if (!i) return; JBD.cart.add(i); location.href = 'checkout.html'; });

  function tab(n) { $$('[data-tab-btn]').forEach(function (b) { b.classList.toggle('is-active', b.dataset.tabBtn === n); }); $$('[data-tab-panel]').forEach(function (p) { var on = p.dataset.tabPanel === n; p.hidden = !on; p.classList.toggle('is-active', on); }); }
  $$('[data-tab-btn]').forEach(function (b) { b.addEventListener('click', function () { tab(b.dataset.tabBtn); }); });
  $$('[data-open-tab]').forEach(function (b) { b.addEventListener('click', function () { tab(b.dataset.openTab); $('.tabs').scrollIntoView({ behavior: 'smooth' }); }); });

  var bar = $('[data-buy-bar]'), btn = $('#add-cart');
  if (bar && 'IntersectionObserver' in window) {
    new IntersectionObserver(function (e) { bar.classList.toggle('show', !e[0].isIntersecting && e[0].boundingClientRect.top < 0); }).observe(btn);
    $('[data-sticky-add]').addEventListener('click', function () { btn.click(); });
  }
})();
