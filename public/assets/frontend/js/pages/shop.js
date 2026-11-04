/* Shop page: client-side filtering, sorting, URL params, mobile filter drawer */
(function () {
  'use strict';
  var $ = function (s, c) { return (c || document).querySelector(s); }, $$ = function (s, c) { return [].slice.call((c || document).querySelectorAll(s)); };
  var grid = $('#grid'), form = $('#filter-form'), cards = $$('.pcard', grid), sortEl = $('#sort');
  if (!grid) return;
  var q = new URLSearchParams(location.search), search = (q.get('q') || '').toLowerCase().trim();
  var names = { '': 'All' };

  function vals(n) { return $$('input[name="' + n + '"]:checked', form).map(function (i) { return i.value; }); }
  function apply() {
    var cat = (form.cat.value || ''), brands = vals('brand'), sizes = vals('size'), min = +form.min.value || 0, max = +form.max.value || Infinity, stock = form.stock.checked, sale = form.sale.checked, shown = 0;
    cards.forEach(function (c) {
      var d = c.dataset, p = +d.price, ok = (!cat || d.cat === cat) && (!brands.length || brands.indexOf(d.brand) > -1) && (!sizes.length || sizes.some(function (s) { return d.sizes.split(',').indexOf(s) > -1; })) &&
        p >= min && p <= max && (!stock || +d.stock > 0) && (!sale || +d.compare > 0) && (!search || (d.name + ' ' + d.brand + ' ' + d.cat).toLowerCase().indexOf(search) > -1);
      c.hidden = !ok; if (ok) shown++;
    });
    $('[data-result-count]').textContent = shown; $('#empty').hidden = shown > 0; grid.hidden = !shown; sort(); chips();
  }
  function sort() {
    var m = sortEl.value, k = { new: function (a, b) { return b.dataset.added - a.dataset.added; }, popular: function (a, b) { return b.dataset.sold - a.dataset.sold; }, low: function (a, b) { return a.dataset.price - b.dataset.price; }, high: function (a, b) { return b.dataset.price - a.dataset.price; } }[m];
    cards.slice().sort(k).forEach(function (c) { grid.appendChild(c); });
  }
  function chips() {
    var box = $('#active-filters'), out = [];
    if (search) out.push(['q', 'Search: ' + search]);
    if (form.cat.value) out.push(['cat', $('input[name=cat]:checked', form).nextElementSibling.textContent]);
    $$('input[name=brand]:checked,input[name=size]:checked', form).forEach(function (i) { out.push([i, i.value]); });
    if (form.stock.checked) out.push([form.stock, 'In stock']); if (form.sale.checked) out.push([form.sale, 'On sale']);
    box.innerHTML = ''; out.forEach(function (o) {
      var b = document.createElement('button'); b.type = 'button'; b.className = 'pill is-active'; b.innerHTML = JBD.esc(o[1]) + ' ×';
      b.onclick = function () { if (o[0] === 'q') { search = ''; } else if (o[0] === 'cat') { form.cat.value = ''; $('input[name=cat][value=""]', form).checked = true; } else o[0].checked = false; apply(); };
      box.appendChild(b);
    });
  }
  function counts() { $$('[data-cat-count]').forEach(function (s) { var c = s.dataset.catCount; s.textContent = cards.filter(function (x) { return !c || x.dataset.cat === c; }).length; }); }

  if (q.get('cat')) { var r = $('input[name=cat][value="' + q.get('cat') + '"]', form); if (r) r.checked = true; }
  if (q.get('brand')) { var b = $('input[name=brand][value="' + q.get('brand') + '"]', form); if (b) b.checked = true; }
  if (q.get('sale')) form.sale.checked = true;
  if (search) { var s = $('.search input'); if (s) s.value = q.get('q'); }
  form.addEventListener('change', apply); form.addEventListener('input', function (e) { if (e.target.type === 'number') apply(); }); sortEl.addEventListener('change', sort);
  $$('[data-filter-reset]').forEach(function (b) { b.addEventListener('click', function () { form.reset(); search = ''; apply(); }); });

  var panel = $('#filters'), ov = $('#filter-overlay');
  function fo(open) { panel.classList.toggle('open', open); ov.hidden = !open; document.body.classList.toggle('no-scroll', open); }
  $('[data-filter-open]').addEventListener('click', function () { fo(true); }); $('[data-filter-close]').addEventListener('click', function () { fo(false); }); ov.addEventListener('click', function () { fo(false); });
  counts(); apply();
})();
