/* Checkout page: validation, division/district, delivery zone, place order */
(function () {
  'use strict';
  var $ = function (s, c) { return (c || document).querySelector(s); }, $$ = function (s, c) { return [].slice.call((c || document).querySelectorAll(s)); };
  var form = $('#checkout-form'); if (!form) return;
  var NEARBY = ['Gazipur', 'Narayanganj', 'Narsingdi', 'Munshiganj', 'Manikganj'];

  function toggle() { var has = JBD.cart.count() > 0; $('#co-empty').hidden = has; $('#co-filled').hidden = !has; }
  toggle(); document.addEventListener('cart:change', toggle);

  var div = form.division, dist = form.district;
  Object.keys(BD_LOC).forEach(function (d) { div.add(new Option(d, d)); });
  div.addEventListener('change', function () {
    dist.length = 1; dist.disabled = !div.value; (BD_LOC[div.value] || []).forEach(function (d) { dist.add(new Option(d, d)); });
  });
  function zoneFor(d) { return d === 'Dhaka' ? 'inside' : NEARBY.indexOf(d) > -1 ? 'nearby' : 'outside'; }
  dist.addEventListener('change', function () { if (!dist.value) return; var z = $('input[name=zone][value=' + zoneFor(dist.value) + ']', form); z.checked = true; pick(); });

  function pick() {
    $$('.opt', form).forEach(function (o) { var i = $('input', o); o.classList.toggle('is-on', i.checked); });
    JBD.summary.ship = +$('input[name=zone]:checked', form).dataset.charge; JBD.summary.render();
  }
  form.addEventListener('change', function (e) { if (e.target.name === 'zone' || e.target.name === 'payment') pick(); });
  pick();

  function err(n, m) { var e = $('[data-err=' + n + ']', form), i = form.elements[n]; if (e) e.textContent = m || ''; if (i && i.classList) i.classList.toggle('invalid', !!m); return !m; }
  function validate() {
    var ok = true, v = function (n, m) { if (!err(n, m)) ok = false; };
    var f = form.elements;
    v('name', f.name.value.trim().length < 3 ? 'Please enter your full name.' : '');
    v('phone', /^(?:\+?88)?01[3-9]\d{8}$/.test(f.phone.value.replace(/[\s-]/g, '')) ? '' : 'Enter a valid Bangladeshi mobile number.');
    v('email', f.email.value && !/^\S+@\S+\.\S+$/.test(f.email.value) ? 'Enter a valid email address.' : '');
    v('division', f.division.value ? '' : 'Select your division.'); v('district', f.district.value ? '' : 'Select your district.');
    v('thana', f.thana.value.trim() ? '' : 'Enter your thana / area.'); v('address', f.address.value.trim().length < 6 ? 'Enter your full address.' : '');
    v('terms', f.terms.checked ? '' : 'Please accept the terms to continue.');
    return ok;
  }
  $$('.input', form).forEach(function (i) { i.addEventListener('input', function () { if (i.classList.contains('invalid')) validate(); }); });

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    if (JBD.cart.subtotal() < JBD.cfg.minOrder) return JBD.toast('Minimum order is ' + JBD.fmt(JBD.cfg.minOrder), 'error');
    if (!validate()) { var bad = $('.invalid', form) || $('.err:not(:empty)', form); if (bad) bad.scrollIntoView({ behavior: 'smooth', block: 'center' }); return; }
    var f = form.elements, sub = JBD.cart.subtotal(), d = JBD.summary.discount(sub), zone = $('input[name=zone]:checked', form), ship = sub >= JBD.cfg.freeAbove ? 0 : +zone.dataset.charge;
    var order = { number: 'MOJ-' + (10483 + Math.floor(Math.random() * 400)), items: JBD.cart.get(), subtotal: sub, discount: d, shipping: ship, total: sub - d + ship, name: f.name.value, phone: f.phone.value,
      address: [f.address.value, f.thana.value, f.district.value, f.division.value, f.postcode.value].filter(Boolean).join(', '), placed: new Date().toISOString() };
    try { sessionStorage.setItem('jbd_last_order', JSON.stringify(order)); } catch (er) {}
    JBD.write('jbd_coupon', null); JBD.cart.clear(); location.href = 'order-success.html';
  });
})();
