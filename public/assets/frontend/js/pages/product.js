/* Product page: gallery, size/color variants, qty, add to cart, tabs, sticky buy bar */
(function () {
  'use strict';
  var $ = function (s, c) { return (c || document).querySelector(s); }, $$ = function (s, c) { return [].slice.call((c || document).querySelectorAll(s)); };
  var prod = $('#product'); if (!prod) return;

  var variants = []; try { variants = JSON.parse($('#variants-data').textContent) || []; } catch (e) {}
  var track = prod.dataset.track === '1', baseStock = +prod.dataset.stock || 0, baseProductPrice = +prod.dataset.price;
  var hasSize = !!$('#sizes'), hasColor = !!$('#colors');

  var main = $('#main-img');
  $$('[data-thumb]').forEach(function (t) { t.addEventListener('click', function () { main.src = t.dataset.thumb; $$('[data-thumb]').forEach(function (x) { x.classList.toggle('is-active', x === t); }); }); });

  var qty = $('#qty');
  function maxQty() { var v = current(); var s = v ? v.stock : baseStock; return track ? Math.max(1, Math.min(10, s)) : 10; }
  $('[data-qty-minus]').addEventListener('click', function () { qty.value = Math.max(1, +qty.value - 1); });
  $('[data-qty-plus]').addEventListener('click', function () { qty.value = Math.min(maxQty(), +qty.value + 1); });
  qty.addEventListener('change', function () { qty.value = Math.max(1, Math.min(maxQty(), +qty.value || 1)); });

  function picked(name) { var i = $('input[name=' + name + ']:checked'); return i ? i.value : null; }
  function current() {
    if (!variants.length) return null;
    var s = picked('size'), c = picked('color');
    if ((hasSize && !s) || (hasColor && !c)) return null;
    return variants.filter(function (v) { return (!hasSize || v.size === s) && (!hasColor || v.color === c); })[0] || null;
  }

  /* disable chips that have no stock for the current choice of the other option */
  function refreshChips() {
    var s = picked('size'), c = picked('color');
    function ok(v) { return !track || v.stock > 0; }
    $$('input[name=size]').forEach(function (i) {
      var has = variants.some(function (v) { return v.size === i.value && ok(v) && (!hasColor || !c || v.color === c); });
      i.disabled = !has; if (!has && i.checked) i.checked = false;
    });
    $$('input[name=color]').forEach(function (i) {
      var has = variants.some(function (v) { return v.color === i.value && ok(v) && (!hasSize || !s || v.size === s); });
      i.disabled = !has; if (!has && i.checked) i.checked = false;
    });
  }

  var patch = $('#opt-patch'), cust = $('#opt-custom'), cname = $('#c-name'), cnum = $('#c-num');
  function unit() { var v = current(); return (v ? v.price : baseProductPrice) + (patch.checked ? 200 : 0) + (cust.checked ? 250 : 0); }
  function price() {
    var u = unit(), v = current(), basePrice = v ? v.price : baseProductPrice, cmp = +prod.dataset.compare || 0;
    $('#price').textContent = JBD.fmt(u); $('[data-bar-price]').textContent = JBD.fmt(u); $('#custom-fields').hidden = !cust.checked;
    var old = $('#old-price'), save = $('#save-badge');
    if (old) { var show = cmp > basePrice; old.style.display = show ? '' : 'none'; if (save) { save.style.display = show ? '' : 'none'; save.textContent = 'Save ' + JBD.fmt(cmp - basePrice); } }
    var sku = $('#sku'); if (sku) sku.textContent = (v && v.sku) || sku.dataset.base;
    var st = $('[data-stock-text]'), s = v ? v.stock : baseStock;
    if (st && track && (v || !variants.length)) { st.textContent = s <= 0 ? 'Out of stock' : (s <= 10 ? 'Only ' + s + ' left' : 'In stock'); }
    qty.value = Math.max(1, Math.min(maxQty(), +qty.value || 1));
  }
  [patch, cust].forEach(function (c) { c.addEventListener('change', price); });
  $$('input[name=size], input[name=color]').forEach(function (i) { i.addEventListener('change', function () { var e = $('#size-err'); if (e) e.hidden = true; e = $('#color-err'); if (e) e.hidden = true; refreshChips(); price(); }); });
  refreshChips(); price();

  function item() {
    var s = picked('size'), c = picked('color'), v = current();
    if (hasSize && !s) { $('#size-err').hidden = false; $('#sizes').scrollIntoView({ behavior: 'smooth', block: 'center' }); return null; }
    if (hasColor && !c) { $('#color-err').hidden = false; $('#colors').scrollIntoView({ behavior: 'smooth', block: 'center' }); return null; }
    if (variants.length && !v) { JBD.toast('This option is not available', 'error'); return null; }
    var d = prod.dataset, opt = [];
    if (cust.checked) {
      var n = cname.value.trim(), x = cnum.value.trim();
      $('#c-name-err').textContent = n ? '' : 'Enter the custom name.'; $('#c-num-err').textContent = x !== '' && +x >= 0 && +x <= 99 ? '' : 'Enter a number (0–99).';
      if (!n || x === '' || +x < 0 || +x > 99) { cname.scrollIntoView({ behavior: 'smooth', block: 'center' }); return null; }
      opt.push('Name: ' + n.toUpperCase() + ' #' + x);
    }
    if (patch.checked) opt.unshift('UCL patch');
    var label = [s, c].filter(Boolean).join(' · ') || 'Standard';
    return { id: d.id, opt: opt.join(' · '), name: d.name, price: unit(), compare: d.compare, image: d.image, url: d.url, size: label, qty: +qty.value };
  }
  var addBtn = $('#add-cart'), buyBtn = $('#buy-now');
  addBtn.addEventListener('click', function () { if (addBtn.disabled) return; var i = item(); if (!i) return; JBD.cart.add(i); JBD.toast('Added to cart'); JBD.openDrawer(); });
  if (buyBtn) buyBtn.addEventListener('click', function () { var i = item(); if (!i) return; JBD.cart.add(i); location.href = prod.dataset.checkout; });

  function tab(n) { $$('[data-tab-btn]').forEach(function (b) { b.classList.toggle('is-active', b.dataset.tabBtn === n); }); $$('[data-tab-panel]').forEach(function (p) { var on = p.dataset.tabPanel === n; p.hidden = !on; p.classList.toggle('is-active', on); }); }
  $$('[data-tab-btn]').forEach(function (b) { b.addEventListener('click', function () { tab(b.dataset.tabBtn); }); });
  $$('[data-open-tab]').forEach(function (b) { b.addEventListener('click', function () { tab(b.dataset.openTab); $('.tabs').scrollIntoView({ behavior: 'smooth' }); }); });

  var bar = $('[data-buy-bar]');
  if (bar && 'IntersectionObserver' in window) {
    new IntersectionObserver(function (e) { bar.classList.toggle('show', !e[0].isIntersecting && e[0].boundingClientRect.top < 0); }).observe(addBtn);
    $('[data-sticky-add]').addEventListener('click', function () { addBtn.click(); });
  }
})();
