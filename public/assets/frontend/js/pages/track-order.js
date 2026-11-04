/* Track order (demo: any order number + valid phone shows a sample timeline) */
(function () {
  'use strict';
  var f = document.getElementById('track-form'); if (!f) return;
  f.addEventListener('submit', function (e) {
    e.preventDefault(); var n = f.order.value.trim().toUpperCase().replace(/^#/, ''), p = f.phone.value.replace(/[\s-]/g, ''), msg = document.getElementById('track-err'), res = document.getElementById('track-result');
    if (!n || !/^(?:\+?88)?01[3-9]\d{8}$/.test(p)) { msg.textContent = 'Enter your order number and a valid phone number.'; res.hidden = true; return; }
    msg.textContent = ''; res.querySelector('[data-t-order]').textContent = '#' + n; res.hidden = false; res.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  });
})();
