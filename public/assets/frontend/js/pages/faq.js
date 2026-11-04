/* FAQ: category filter + one-open-at-a-time accordion */
(function () {
  'use strict';
  var $$ = function (s) { return [].slice.call(document.querySelectorAll(s)); }, items = $$('.faq');
  var tabs = document.querySelector('[data-faq-tabs]'); if (!tabs) return;
  tabs.addEventListener('click', function (e) { var b = e.target.closest('[data-faq-tab]'); if (!b) return; $$('[data-faq-tab]').forEach(function (p) { p.classList.toggle('is-active', p === b); }); items.forEach(function (i) { i.hidden = b.dataset.faqTab !== 'all' && i.dataset.cat !== b.dataset.faqTab; }); });
  items.forEach(function (d) { d.addEventListener('toggle', function () { if (d.open) items.forEach(function (o) { if (o !== d) o.open = false; }); }); });
})();
