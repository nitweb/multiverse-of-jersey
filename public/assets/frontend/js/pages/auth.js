/* Auth + profile forms: validation, show/hide password. Replace the submit handlers with real requests. */
(function () {
  'use strict';
  var $ = function (s, c) { return (c || document).querySelector(s); }, $$ = function (s, c) { return [].slice.call((c || document).querySelectorAll(s)); };
  document.addEventListener('click', function (e) {
    var b = e.target.closest('[data-pw-toggle]'); if (!b) return; var i = b.parentNode.querySelector('input'); var show = i.type === 'password'; i.type = show ? 'text' : 'password'; b.classList.toggle('on', show); b.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
  });
  var PHONE = /^(?:\+?88)?01[3-9]\d{8}$/, MAIL = /^\S+@\S+\.\S+$/;
  $$('[data-auth-form]').forEach(function (f) {
    function err(n, m) { var e = $('[data-err=' + n + ']', f), i = f.elements[n]; if (e) e.textContent = m || ''; if (i && i.classList) i.classList.toggle('invalid', !!m); return !m; }
    f.addEventListener('submit', function (e) {
      e.preventDefault(); var t = f.dataset.authForm, el = f.elements, ok = true, v = function (n, m) { if (el[n] && !err(n, m)) ok = false; };
      if (el.login) { var l = el.login.value.trim(); v('login', PHONE.test(l.replace(/[\s-]/g, '')) || MAIL.test(l) ? '' : 'Enter a valid phone number or email.'); }
      if (el.name) v('name', el.name.value.trim().length < 3 ? 'Please enter your full name.' : '');
      if (el.phone) v('phone', PHONE.test(el.phone.value.replace(/[\s-]/g, '')) ? '' : 'Enter a valid Bangladeshi mobile number.');
      if (el.email && el.email.value) v('email', MAIL.test(el.email.value) ? '' : 'Enter a valid email address.');
      if (el.current) v('current', el.current.value.length < 6 ? 'Enter your current password.' : '');
      if (el.password) v('password', el.password.value.length < 6 ? 'Password must be at least 6 characters.' : '');
      if (el.confirm) v('confirm', el.confirm.value !== el.password.value ? 'Passwords do not match.' : '');
      if (el.terms) v('terms', el.terms.checked ? '' : 'Please accept the terms to continue.');
      if (!ok) return;
      if (t === 'login') { JBD.toast('Logged in'); setTimeout(function () { location.href = 'account.html'; }, 600); }
      else if (t === 'register') { JBD.toast('Account created'); setTimeout(function () { location.href = 'account.html'; }, 600); }
      else if (t === 'forgot') { var o = $('[data-auth-ok]', f); o.classList.remove('hidden'); }
      else { JBD.toast(t === 'password' ? 'Password updated' : 'Profile saved'); f.reset && t === 'password' && f.reset(); }
    });
  });
})();
