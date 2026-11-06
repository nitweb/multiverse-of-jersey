/* Checkout page (server cart): division → district picker, delivery zone from district, validation, place order. */
(function () {
  'use strict';
  var $ = function (s, c) { return (c || document).querySelector(s); }, $$ = function (s, c) { return [].slice.call((c || document).querySelectorAll(s)); };
  var form = $('#checkout-form'), cfg = window.JBD_CHECKOUT; if (!form || !cfg) return;

  var division = $('#division'), district = $('#district'), zoneMsg = $('#zone-msg');

  /* empty cart: send the visitor back to the cart page */
  function guardEmpty() { if (!JBD.cart.get().length) location.href = (window.JBD_SERVER.routes || {}).cart || '/cart'; }

  function fillDistricts(keep) {
    var list = cfg.divisions[division.value] || [];
    district.innerHTML = '<option value="">Select district</option>' + list.map(function (d) { return '<option value="' + JBD.esc(d) + '">' + JBD.esc(d) + '</option>'; }).join('');
    district.disabled = !list.length;
    if (keep && list.indexOf(keep) > -1) district.value = keep;
  }

  var zones = {};
  $$('#zones input[name=zone]').forEach(function (i) { zones[i.value] = i; });
  var lastZone = null;

  function applyZone() {
    var z = cfg.zoneOf[district.value] || null;
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

  division.addEventListener('change', function () { fillDistricts(); applyZone(); });
  district.addEventListener('change', applyZone);

  /* saved addresses */
  var saved = $('#saved-address');
  function useSaved() {
    var o = saved.options[saved.selectedIndex]; if (!o) return;
    form.name.value = o.dataset.name || form.name.value; form.phone.value = o.dataset.phone || form.phone.value;
    var dName = Object.keys(cfg.divisions).filter(function (k) { return cfg.divisions[k].indexOf(o.dataset.district) > -1; })[0] || '';
    division.value = dName; fillDistricts(o.dataset.district);
    $('#thana').value = o.dataset.area || ''; $('#address').value = o.dataset.address || '';
    applyZone();
  }
  if (saved) saved.addEventListener('change', useSaved);

  /* validation */
  var rules = {
    name: function (v) { return v.trim().length >= 2 ? '' : 'Enter your full name.'; },
    phone: function (v) { return /^(\+?88)?01[3-9]\d{8}$/.test(v.replace(/[\s-]/g, '')) ? '' : 'Enter a valid mobile number, like 01XXXXXXXXX.'; },
    email: function (v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim()) ? '' : 'Enter a valid email address.'; },
    division: function (v) { return v ? '' : 'Select your division.'; },
    district: function (v) { return v ? '' : 'Select your district.'; },
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
  if (cfg.prefillDivision) { division.value = cfg.prefillDivision; fillDistricts(cfg.prefillDistrict); }
  document.addEventListener('cart:change', guardEmpty);
  guardEmpty(); applyZone();
})();
