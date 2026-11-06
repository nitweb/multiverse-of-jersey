/* MULTIVERSE OF JERSEY — common JS (loaded on every page): theme, header, nav, cart, wishlist, toast, chat.
   Cart talks to the server (CartService). Wishlist still uses localStorage. */
(function () {
  'use strict';
  var $ = function (s, c) { return (c || document).querySelector(s); };
  var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };
  var KEY_CART = 'jbd_cart', KEY_WISH = 'jbd_wish', KEY_THEME = 'jbd_theme';
  var CFG = { currency: '৳', freeAbove: 5000, minOrder: 500, coupons: { JERSEY10: { type: 'percent', value: 10, label: 'JERSEY10' }, WELCOME200: { type: 'flat', value: 200, label: 'WELCOME200' } } };

  function read(k, d) { try { var v = JSON.parse(localStorage.getItem(k)); return v == null ? d : v; } catch (e) { return d; } }
  function write(k, v) { try { localStorage.setItem(k, JSON.stringify(v)); } catch (e) {} }
  function fmt(n) { return CFG.currency + Math.round(n).toLocaleString('en-US'); }
  function esc(s) { return String(s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; }); }

  /* ---------- toast ---------- */
  function toast(msg, type) {
    var box = $('#toasts'); if (!box) return;
    var t = document.createElement('div'); t.className = 'toast'; t.setAttribute('role', 'status');
    t.innerHTML = '<svg class="ic" style="color:' + (type === 'error' ? '#f87171' : '#34d399') + '"><use href="#i-' + (type === 'error' ? 'info' : 'check') + '"/></svg><span>' + esc(msg) + '</span>';
    box.appendChild(t); setTimeout(function () { t.style.opacity = '0'; t.style.transition = 'opacity .3s'; }, 2400); setTimeout(function () { t.remove(); }, 2800);
  }

  /* ---------- cart (server) ---------- */
  var SRV = window.JBD_SERVER || { cart: { items: [], count: 0, subtotal: 0 }, routes: {} };
  var csrf = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
  var state = SRV.cart;

  function api(method, url, body) {
    return fetch(url, {
      method: method, credentials: 'same-origin',
      headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
      body: body ? JSON.stringify(body) : undefined
    }).then(function (res) {
      return res.json().catch(function () { return {}; }).then(function (data) {
        if (data.cart) { state = data.cart; update(); document.dispatchEvent(new CustomEvent('cart:change')); }
        var msg = data.message || (data.errors ? Object.keys(data.errors).map(function (k) { return data.errors[k][0]; })[0] : null) || (res.ok ? null : 'Something went wrong. Please try again.');
        return { ok: res.ok && data.ok !== false, message: msg, cart: state };
      });
    }).catch(function () { return { ok: false, message: 'Network problem. Please check your connection.', cart: state }; });
  }
  function done(r, okMsg) { if (r.message || okMsg) toast(r.ok ? (r.message || okMsg) : r.message, r.ok ? undefined : 'error'); return r; }

  var cart = {
    summary: function () { return state; },
    get: function () { return state.items || []; },
    add: function (it) { return api('POST', SRV.routes.items, it); },
    setQty: function (k, q) { return api('PATCH', SRV.routes.itemBase + '/' + k, { quantity: q }); },
    remove: function (k) { return api('DELETE', SRV.routes.itemBase + '/' + k); },
    clear: function () { return api('DELETE', SRV.routes.clear); },
    coupon: function (code) { return api('POST', SRV.routes.coupon, { code: code }); },
    couponRemove: function () { return api('DELETE', SRV.routes.couponRemove); },
    zone: function (zone) { return api('PUT', SRV.routes.zone, { zone: zone }); },
    count: function () { return state.count || 0; },
    subtotal: function () { return state.subtotal || 0; }
  };

  /* ---------- wishlist ---------- */
  var wish = {
    get: function () { return read(KEY_WISH, []); },
    has: function (id) { return wish.get().some(function (x) { return String(x.id) === String(id); }); },
    toggle: function (it) {
      var w = wish.get(), i = -1; w.forEach(function (x, n) { if (String(x.id) === String(it.id)) i = n; });
      if (i > -1) w.splice(i, 1); else w.push(it);
      write(KEY_WISH, w); update(); document.dispatchEvent(new CustomEvent('wish:change')); return i === -1;
    }
  };

  /* ---------- UI refresh ---------- */
  function update() {
    var n = cart.count();
    $$('[data-cart-count]').forEach(function (e) { e.textContent = n; e.hidden = !n; });
    $$('[data-cart-count-text]').forEach(function (e) { e.textContent = n ? '(' + n + ')' : ''; });
    var wn = wish.get().length;
    $$('[data-wish-count]').forEach(function (e) { e.textContent = wn; e.hidden = !wn; });
    $$('[data-wish]').forEach(function (b) { var host = b.closest('[data-id]'); if (host) b.setAttribute('aria-pressed', wish.has(host.dataset.id)); });
    renderDrawer();
  }

  function lineHtml(x) {
    return '<div class="line"><a href="' + esc(x.url) + '"><img src="' + esc(x.image) + '" alt=""></a><div class="min-w-0 flex-1"><a href="' + esc(x.url) + '" class="font-semibold text-sm leading-snug line-clamp-2 hover:text-primary">' + esc(x.name) + '</a>' + (x.variant ? '<p class="text-xs text-ink/50 mt-0.5">' + esc(x.variant) + '</p>' : '') +
      '<div class="flex items-center justify-between mt-2"><div class="qty !h-9" data-key="' + esc(x.key) + '" data-max="' + x.max_quantity + '"><button type="button" data-dq="-1" aria-label="Decrease"><svg class="ic" style="width:14px;height:14px"><use href="#i-minus"/></svg></button><input value="' + x.quantity + '" readonly aria-label="Quantity"><button type="button" data-dq="1" aria-label="Increase"><svg class="ic" style="width:14px;height:14px"><use href="#i-plus"/></svg></button></div><b class="font-display">' + fmt(x.line_total) + '</b></div></div>' +
      '<button type="button" class="self-start text-ink/40 hover:text-danger" data-drm="' + esc(x.key) + '" aria-label="Remove"><svg class="ic" style="width:18px;height:18px"><use href="#i-trash"/></svg></button></div>';
  }
  function renderDrawer() {
    var box = $('[data-drawer-items]'); if (!box) return;
    var c = cart.get();
    $('[data-drawer-foot]').style.display = c.length ? '' : 'none';
    $('[data-drawer-subtotal]').textContent = fmt(cart.subtotal());
    var go = $('[data-drawer-checkout]'); if (go && state.checkout_url) go.href = state.checkout_url;
    if (!c.length) { box.innerHTML = '<div class="empty"><svg class="ic" style="width:48px;height:48px;opacity:.25;margin:auto"><use href="#i-bag"/></svg><p class="font-display font-bold text-lg mt-4">Your cart is empty</p><p class="text-sm text-ink/55 mt-1">Add a jersey to get started.</p><a href="' + esc(SRV.routes.shop || '#') + '" class="btn btn-primary mt-5">Shop jerseys</a></div>'; return; }
    box.innerHTML = c.map(lineHtml).join('');
  }

  /* ---------- layers (drawer / nav / modal) ---------- */
  var header = $('#site-header'), overlay = $('#overlay'), drawer = $('#cart-drawer'), nav = $('#site-nav'), modal = $('#quick-modal');
  function lock() { document.body.classList.toggle('no-scroll', !!($('.drawer.open') || $('.site-nav.open') || (modal && !modal.hidden) || $('.filters.open'))); }
  function closeAll() {
    if (drawer) { drawer.classList.remove('open'); drawer.setAttribute('aria-hidden', 'true'); }
    if (header) header.classList.remove('nav-open');
    if (nav) { nav.classList.remove('open'); var t = $('[data-nav-toggle]'); if (t) t.setAttribute('aria-expanded', 'false'); }
    if (modal) modal.hidden = true;
    if (overlay) overlay.hidden = true; lock();
  }
  function openDrawer() { closeAll(); drawer.classList.add('open'); drawer.setAttribute('aria-hidden', 'false'); overlay.hidden = false; lock(); }
  function openNav() { closeAll(); header.classList.add('nav-open'); nav.classList.add('open'); overlay.hidden = false; $('[data-nav-toggle]').setAttribute('aria-expanded', 'true'); lock(); }

  /* ---------- quick add ---------- */
  var qa = null, qvars = [], qvar = null;
  function quickAdd(card) {
    qa = card.dataset; qvar = null; qvars = [];
    try { qvars = JSON.parse(qa.variants || 'null'); } catch (e) { qvars = null; }
    if (qvars === null) { location.href = qa.url; return; } /* card without variant data: open the product page */
    if (!qvars.length) { cart.add({ product_id: +qa.id, quantity: 1 }).then(function (r) { done(r, 'Added to cart'); if (r.ok) openDrawer(); }); return; }
    $('#qm-img').src = qa.image; $('#qm-title').textContent = qa.name; $('#qm-price').textContent = fmt(qa.price);
    $('#qm-err').hidden = true;
    $('#qm-sizes').innerHTML = qvars.map(function (v) { var out = v.stock <= 0; return '<button type="button" class="size-chip' + (out ? ' is-out' : '') + '" data-variant="' + v.id + '"' + (out ? ' disabled' : '') + ' style="' + (out ? 'opacity:.4;text-decoration:line-through;cursor:not-allowed' : '') + '"><span>' + esc(v.label || 'Standard') + '</span></button>'; }).join('');
    closeAll(); modal.hidden = false; overlay.hidden = true; lock();
  }

  /* ---------- events ---------- */
  document.addEventListener('click', function (e) {
    var t = e.target;
    var q = function (s) { return t.closest(s); };
    var el;
    if ((el = q('[data-nav-toggle]'))) return openNav();
    if (q('[data-nav-close]')) return closeAll();
    if ((el = q('.sub-toggle'))) { var p = el.closest('.has-sub'); p.classList.toggle('open'); el.setAttribute('aria-expanded', p.classList.contains('open')); return; }
    if (q('[data-cart-open]')) return openDrawer();
    if (q('[data-cart-close]') || t === overlay || q('[data-modal-close]') || t === modal) return closeAll();
    if ((el = q('[data-dq]'))) { var k = el.closest('[data-key]').dataset.key, it = cart.get().filter(function (x) { return x.key === k; })[0]; if (it) { var nq = it.quantity + (+el.dataset.dq); if (nq < 1) return; if (nq > it.max_quantity) return toast('Only ' + it.max_quantity + ' available', 'error'); cart.setQty(k, nq).then(function (r) { if (!r.ok) done(r); }); } return; }
    if ((el = q('[data-drm]'))) { cart.remove(el.dataset.drm).then(function (r) { if (!r.ok) done(r); }); return; }
    if ((el = q('[data-quick-add]'))) return quickAdd(el.closest('.pcard'));
    if ((el = q('#qm-sizes .size-chip'))) { if (el.disabled) return; $$('#qm-sizes .size-chip').forEach(function (b) { b.classList.remove('on'); }); el.classList.add('on'); qvar = el.dataset.variant; $('#qm-err').hidden = true; return; }
    if (q('#qm-add')) {
      if (!qvar) { $('#qm-err').hidden = false; return; }
      cart.add({ product_id: +qa.id, variant_id: +qvar, quantity: 1 }).then(function (r) { done(r, 'Added to cart'); if (r.ok) openDrawer(); });
      return;
    }
    if ((el = q('[data-wish]'))) {
      var host = el.closest('[data-id]'); if (!host) return; var d = host.dataset;
      var on = wish.toggle({ id: d.id, name: d.name, price: +d.price, compare: +d.compare, image: d.image, url: d.url, cat: d.cat, brand: d.brand, sizes: d.sizes || 'S,M,L,XL,XXL', stock: d.stock });
      return toast(on ? 'Saved to wishlist' : 'Removed from wishlist');
    }
    if (q('[data-theme-toggle]')) { var dark = document.documentElement.classList.toggle('dark'); try { localStorage.setItem(KEY_THEME, dark ? 'dark' : 'light'); } catch (er) {} return; }
    if (q('[data-chat-toggle]')) { var ch = $('[data-chat]'); ch.classList.toggle('open'); $('[data-chat-toggle]').setAttribute('aria-expanded', ch.classList.contains('open')); return; }
    if (q('[data-to-top]')) return window.scrollTo({ top: 0, behavior: 'smooth' });
    if (q('[data-copy-link]')) { try { navigator.clipboard.writeText(location.href); } catch (er) {} return toast('Link copied'); }
    if (q('.site-nav a') && nav.classList.contains('open')) closeAll();
  });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeAll(); });

  var top = $('[data-to-top]');
  function onScroll() { var y = window.scrollY; if (header) header.classList.toggle('is-stuck', y > 8); if (top) top.hidden = y < 600; }
  window.addEventListener('scroll', onScroll, { passive: true }); onScroll();
  window.addEventListener('storage', function (e) { if (e.key === KEY_WISH) update(); });

  window.JBD = { cfg: CFG, cart: cart, wish: wish, toast: toast, fmt: fmt, esc: esc, update: update, openDrawer: openDrawer, closeAll: closeAll, read: read, write: write, done: done, lineHtml: lineHtml };
  update();
})();
