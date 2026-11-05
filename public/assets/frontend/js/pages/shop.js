/* Shop page: AJAX filtering, sorting, search and pagination (no page reload), URL sync, mobile filter drawer */
(function () {
  'use strict';
  var $ = function (s, c) { return (c || document).querySelector(s); }, $$ = function (s, c) { return [].slice.call((c || document).querySelectorAll(s)); };
  var form = $('#filter-form'), results = $('#shop-results');
  if (!form || !results) return;

  var baseUrl = results.dataset.baseUrl || form.getAttribute('action'), shopUrl = results.dataset.shopUrl || '/shop', categoryName = results.dataset.category || '', countEl = $('[data-result-count]'), chipsBox = $('#active-filters');
  var controller = null, timer = null;

  function params() {
    var p = new URLSearchParams();
    new FormData(form).forEach(function (v, k) { v = String(v).trim(); if (v !== '') p.append(k, v); });
    if (p.get('sort') === 'new') p.delete('sort');
    return p;
  }
  function urlFor(p) { var s = p.toString(); return baseUrl + (s ? (baseUrl.indexOf('?') > -1 ? '&' : '?') + s : ''); }

  function load(url, push, scroll) {
    if (controller) controller.abort();
    controller = new AbortController();
    results.classList.add('is-loading'); results.setAttribute('aria-busy', 'true');
    fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, signal: controller.signal })
      .then(function (r) { if (!r.ok) throw new Error('Request failed'); return r.json(); })
      .then(function (d) {
        results.innerHTML = d.html;
        var box = $('[data-results]', results), total = box ? +box.dataset.total : 0;
        if (countEl) { countEl.textContent = total; countEl.parentNode.lastChild.textContent = ' ' + (total === 1 ? 'product' : 'products'); }
        if (push) history.pushState(null, '', url);
        chips(); sync();
        if (scroll) { var top = results.getBoundingClientRect().top + window.scrollY - 120; window.scrollTo({ top: Math.max(0, top), behavior: 'smooth' }); }
      })
      .catch(function (e) { if (e.name !== 'AbortError') location.href = url; })
      .then(function () { results.classList.remove('is-loading'); results.removeAttribute('aria-busy'); });
  }
  function run() { clearTimeout(timer); load(urlFor(params()), true, false); }
  function sync() { if (window.JBD && JBD.update) JBD.update(); }

  /* active filter chips + "Clear all" */
  function chips() {
    var out = [], q = form.elements.q ? form.elements.q.value.trim() : '';
    if (categoryName) out.push(['category', 'Category: ' + categoryName]);
    if (q) out.push([form.elements.q, 'Search: ' + q]);
    $$('input[type=checkbox]:checked', form).forEach(function (i) { out.push([i, i.nextElementSibling.textContent.trim()]); });
    var min = form.elements.min.value, max = form.elements.max.value;
    if (min) out.push([form.elements.min, 'From ৳' + min]);
    if (max) out.push([form.elements.max, 'Up to ৳' + max]);
    chipsBox.innerHTML = '';
    out.forEach(function (o) {
      var b = document.createElement('button'); b.type = 'button'; b.className = 'pill is-active'; b.textContent = o[1] + ' ×';
      b.addEventListener('click', function () {
        if (o[0] === 'category') { var s = params().toString(); location.href = shopUrl + (s ? '?' + s : ''); return; }
        if (o[0].type === 'checkbox') o[0].checked = false; else o[0].value = '';
        run();
      });
      chipsBox.appendChild(b);
    });
    if (out.length) {
      var c = document.createElement('button'); c.type = 'button'; c.className = 'pill'; c.textContent = 'Clear all'; c.setAttribute('data-filter-reset', '');
      chipsBox.appendChild(c);
    }
  }
  /* reset every filter, sort and the category: back to all products */
  function reset() {
    $$('input[type=checkbox]', form).forEach(function (i) { i.checked = false; });
    ['min', 'max'].forEach(function (n) { form.elements[n].value = ''; });
    if (form.elements.q) form.elements.q.value = ''; if (form.elements.sort) form.elements.sort.value = 'new';
    if (categoryName || baseUrl !== shopUrl) { location.href = shopUrl; return; }
    run();
  }
  function fromUrl() {
    var p = new URLSearchParams(location.search);
    $$('input[type=checkbox]', form).forEach(function (i) { i.checked = p.getAll(i.name).indexOf(i.value) > -1; });
    ['min', 'max'].forEach(function (n) { form.elements[n].value = p.get(n) || ''; });
    if (form.elements.q) form.elements.q.value = p.get('q') || ''; if (form.elements.sort) form.elements.sort.value = p.get('sort') || 'new';
  }

  form.addEventListener('submit', function (e) { e.preventDefault(); run(); });
  /* checkboxes inside the form */
  form.addEventListener('change', function (e) { if (e.target.type === 'checkbox') run(); });
  /* search and price type-ahead (debounced) */
  [form.elements.q, form.elements.min, form.elements.max].forEach(function (el) {
    if (el) el.addEventListener('input', function () { clearTimeout(timer); timer = setTimeout(run, 350); });
  });
  /* sort sits outside the form element (form="filter-form"), so its change event never bubbles to it */
  if (form.elements.sort) form.elements.sort.addEventListener('change', run);
  /* switching category keeps every other active filter */
  $$('[data-category-link]').forEach(function (a) {
    a.addEventListener('click', function (e) {
      var s = params().toString(); if (!s || e.metaKey || e.ctrlKey || e.shiftKey) return;
      e.preventDefault(); location.href = a.href.split('?')[0] + '?' + s;
    });
  });
  document.addEventListener('click', function (e) {
    var r = e.target.closest('[data-filter-reset]'); if (r) { e.preventDefault(); reset(); }
  });
  results.addEventListener('click', function (e) {
    var a = e.target.closest('.pagination a.pg'); if (!a) return;
    e.preventDefault(); load(a.href, true, true);
  });
  window.addEventListener('popstate', function () { fromUrl(); load(location.href, false, false); });

  /* mobile filter drawer */
  var panel = $('#filters'), ov = $('#filter-overlay');
  function drawer(open) { panel.classList.toggle('open', open); ov.hidden = !open; document.body.classList.toggle('no-scroll', open); }
  $('[data-filter-open]').addEventListener('click', function () { drawer(true); });
  $('[data-filter-close]').addEventListener('click', function () { drawer(false); });
  ov.addEventListener('click', function () { drawer(false); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') drawer(false); });

  chips();
})();
