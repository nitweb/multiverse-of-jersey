/* Checkout page (server cart): district → area / thana pickers, delivery zone from district, validation, place order. */
(function () {
  'use strict';
  var $ = function (s, c) { return (c || document).querySelector(s); }, $$ = function (s, c) { return [].slice.call((c || document).querySelectorAll(s)); };
  var form = $('#checkout-form'), cfg = window.JBD_CHECKOUT; if (!form || !cfg) return;

  var district = $('#district'), thana = $('#thana'), zoneMsg = $('#zone-msg');

  /* empty cart: send the visitor back to the cart page */
  function guardEmpty() { if (!JBD.cart.get().length) location.href = (window.JBD_SERVER.routes || {}).cart || '/cart'; }

  /* district list (alphabetical) with its division, and the thana list per district */
  var ALL = [];
  Object.keys(cfg.divisions).forEach(function (d) { cfg.divisions[d].forEach(function (n) { ALL.push({ n: n, d: d }); }); });
  ALL.sort(function (a, b) { return a.n.localeCompare(b.n); });
  function find(v) { v = (v || '').trim().toLowerCase(); return ALL.filter(function (x) { return x.n.toLowerCase() === v; })[0] || null; }

  /* searchable pick list: used for both district and area / thana */
  function picker(input, list, source, onPick) {
    var shown = [], idx = -1;
    function items() { return list.querySelectorAll('li[data-i]'); }
    function render(q) {
      q = (q || '').trim().toLowerCase();
      shown = source().filter(function (x) { return !q || x.n.toLowerCase().indexOf(q) > -1; });
      idx = shown.length ? 0 : -1;
      list.innerHTML = shown.length ? shown.map(function (x, i) { return '<li role="option" data-i="' + i + '"' + (i === 0 ? ' class="on"' : '') + '>' + JBD.esc(x.n) + (x.d ? '<small>' + JBD.esc(x.d) + '</small>' : '') + '</li>'; }).join('') : '<li class="none">Nothing found</li>';
      list.hidden = false; input.setAttribute('aria-expanded', 'true');
    }
    function close() { list.hidden = true; input.setAttribute('aria-expanded', 'false'); }
    function pick(i) { if (!shown[i]) return; input.value = shown[i].n; close(); onPick(); }
    function mark() { [].forEach.call(items(), function (e, i) { e.classList.toggle('on', i === idx); }); if (items()[idx]) items()[idx].scrollIntoView({ block: 'nearest' }); }
    input.addEventListener('focus', function () { if (!input.disabled) { render(''); input.select(); } });
    input.addEventListener('input', function () { render(input.value); });
    input.addEventListener('keydown', function (e) {
      if (e.key === 'ArrowDown') { e.preventDefault(); if (list.hidden) render(''); else { idx = Math.min(idx + 1, shown.length - 1); mark(); } }
      else if (e.key === 'ArrowUp') { e.preventDefault(); idx = Math.max(idx - 1, 0); mark(); }
      else if (e.key === 'Enter' && !list.hidden) { e.preventDefault(); pick(idx); }
      else if (e.key === 'Escape' || e.key === 'Tab') close();
    });
    list.addEventListener('mousedown', function (e) { var li = e.target.closest('li[data-i]'); if (li) { e.preventDefault(); pick(+li.dataset.i); } });
    document.addEventListener('click', function (e) { if (!input.contains(e.target) && !list.contains(e.target)) close(); });
    input.addEventListener('blur', function () { setTimeout(function () { close(); onPick(true); }, 120); });
  }

  var lastDistrict = null;
  function syncDistrict() {
    var m = find(district.value), n = m ? m.n : '';
    if (m && district.value !== n) district.value = n;
    if (n !== lastDistrict) {
      if (lastDistrict !== null) thana.value = '';
      lastDistrict = n;
      thana.disabled = !n;
      thana.placeholder = n ? 'Select or type thana / upazila' : 'Select district first';
    }
    applyZone();
  }

  picker(district, $('#district-list'), function () { return ALL; }, syncDistrict);
  picker(thana, $('#area-list'), function () { return (cfg.thanas[(find(district.value) || {}).n] || []).map(function (n) { return { n: n }; }); }, function () {});

  var zones = {};
  $$('#zones input[name=zone]').forEach(function (i) { zones[i.value] = i; });
  var lastZone = null;

  function applyZone() {
    var m = find(district.value), z = m ? (cfg.zoneOf[m.n] || null) : null;
    JBD.summary.showShip = !!z;
    $$('#zones .opt').forEach(function (l) { l.classList.toggle('is-on', !!z && l.querySelector('input').value === z); });
    if (zones[z]) zones[z].checked = true;
    if (!z) { zoneMsg.textContent = 'Select your district to see the delivery charge.'; JBD.summary.render(); return; }
    if (z === lastZone && z === JBD.cart.summary().shipping.zone) { showMsg(); JBD.summary.render(); return; }
    lastZone = z;
    JBD.cart.zone(z).then(function (r) {
      if (!r.ok) { zoneMsg.textContent = r.message || 'We cannot deliver to this area right now.'; zoneMsg.className = 'text-xs text-danger mt-3'; return; }
      showMsg(); JBD.summary.render();
    });
  }
  function showMsg() {
    var s = JBD.cart.summary().shipping || {};
    zoneMsg.className = 'text-xs text-ink/55 mt-3';
    zoneMsg.textContent = s.label + ' delivery: ' + (s.free ? 'Free' : JBD.fmt(s.fee)) + (s.time ? ', arrives in ' + s.time : '') + '.';
  }

  /* saved addresses */
  var saved = $('#saved-address');
  function useSaved() {
    var o = saved.options[saved.selectedIndex]; if (!o) return;
    form.name.value = o.dataset.name || form.name.value; form.phone.value = o.dataset.phone || form.phone.value;
    district.value = o.dataset.district || ''; lastDistrict = null; syncDistrict();
    thana.value = o.dataset.area || ''; $('#address').value = o.dataset.address || '';
  }
  if (saved) saved.addEventListener('change', useSaved);

  /* validation */
  var rules = {
    name: function (v) { return v.trim().length >= 2 ? '' : 'Enter your full name.'; },
    phone: function (v) { return /^(\+?88)?01[3-9]\d{8}$/.test(v.replace(/[\s-]/g, '')) ? '' : 'Enter a valid mobile number, like 01XXXXXXXXX.'; },
    email: function (v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim()) ? '' : 'Enter a valid email address.'; },
    district: function (v) { return find(v) ? '' : 'Select a district from the list.'; },
    thana: function (v) { return v.trim() ? '' : 'Enter your area or thana.'; },
    address: function (v) { return v.trim().length >= 5 ? '' : 'Enter your full address.'; }
  };
  function setErr(k, m) { var e = $('[data-err="' + k + '"]'); if (e) e.textContent = m; }
  function check(k) { var el = form.elements[k === 'thana' ? 'area_txt' : k]; var m = rules[k](el.value); setErr(k, m); return m; }
  Object.keys(rules).forEach(function (k) { var el = form.elements[k === 'thana' ? 'area_txt' : k]; if (el) el.addEventListener('blur', function () { check(k); }); });

  form.addEventListener('submit', function (e) {
    var firstBad = null;
    Object.keys(rules).forEach(function (k) { if (check(k) && !firstBad) firstBad = form.elements[k === 'thana' ? 'area_txt' : k]; });
    if (!form.terms.checked) { setErr('terms', 'Please accept the terms to place your order.'); firstBad = firstBad || form.terms; } else setErr('terms', '');
    if (firstBad) { e.preventDefault(); firstBad.scrollIntoView({ behavior: 'smooth', block: 'center' }); try { firstBad.focus({ preventScroll: true }); } catch (er) {} return; }
    $$('[data-place-order]').forEach(function (b) { b.disabled = true; b.classList.add('opacity-60'); });
  });

  /* start */
  syncDistrict();
  document.addEventListener('cart:change', guardEmpty);
  guardEmpty(); applyZone();
})();
