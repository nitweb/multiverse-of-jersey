/* Contact form validation (demo) */
(function () {
  'use strict';
  var f = document.querySelector('[data-contact-form]'); if (!f) return;
  f.addEventListener('submit', function (e) {
    e.preventDefault(); var ok = true, el = f.elements;
    function v(n, m) { var s = f.querySelector('[data-err=' + n + ']'); s.textContent = m || ''; el[n].classList.toggle('invalid', !!m); if (m) ok = false; }
    v('name', el.name.value.trim().length < 2 ? 'Please enter your name.' : '');
    v('phone', /^(?:\+?88)?01[3-9]\d{8}$/.test(el.phone.value.replace(/[\s-]/g, '')) ? '' : 'Enter a valid Bangladeshi mobile number.');
    v('email', el.email.value && !/^\S+@\S+\.\S+$/.test(el.email.value) ? 'Enter a valid email address.' : '');
    v('message', el.message.value.trim().length < 10 ? 'Please write at least 10 characters.' : '');
    if (!ok) return; f.reset(); f.querySelector('[data-contact-ok]').hidden = false; JBD.toast('Message sent');
  });
})();
