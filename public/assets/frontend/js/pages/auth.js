/* Auth forms: show / hide password and a double-submit guard. Validation runs on the server. */
(function () {
  'use strict';
  var $$ = function (s, c) { return [].slice.call((c || document).querySelectorAll(s)); };

  document.addEventListener('click', function (e) {
    var b = e.target.closest('[data-pw-toggle]'); if (!b) return;
    var i = b.parentNode.querySelector('input'); var show = i.type === 'password';
    i.type = show ? 'text' : 'password'; b.classList.toggle('on', show);
    b.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
  });

  $$('form[data-auth-form]').forEach(function (f) {
    f.addEventListener('submit', function () {
      var btn = f.querySelector('button[type=submit]'); if (!btn) return;
      setTimeout(function () { btn.disabled = true; }, 0);
    });
  });
})();
