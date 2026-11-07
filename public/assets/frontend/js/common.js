/* MULTIVERSE OF JERSEY — common JS (loaded on every page): theme, header, nav, cart, wishlist, toast, chat.
   Cart talks to the server (CartService); the wishlist is saved on the signed-in customer's account. */
(function () {
  'use strict';
  var $ = function (s, c) { return (c || document).querySelector(s); };
  var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };
  var KEY_THEME = 'jbd_theme';
  var CFG = { currency: '৳', freeAbove: 5000, minOrder: 500, coupons: { JERSEY10: { type: 'percent', value: 10, label: 'JERSEY10' }, WELCOME200: { type: 'flat', value: 200, label: 'WELCOME200' } } };

  function read(k, d) { try { var v = JSON.parse(localStorage.getItem(k)); return v == null ? d : v; } catch (e) { return d; } }
  function write(k, v) { try { localStorage.setItem(k, JSON.stringify(v)); } catch (e) {} }
  function fmt(n) { return CFG.currency + Math.round(n).toLocaleString('en-US'); }
  function esc(s) { return String(s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; }); }

  /* ---------- toast ---------- */
  function toast(msg, type) {
    var box = $('#toasts'); if (!box) return;
    var t = document.createElement('div'); t.className = 'toast' + (type === 'error' ? ' is-error' : ''); t.setAttribute('role', type === 'error' ? 'alert' : 'status');
    t.innerHTML = '<span>' + esc(msg) + '</span><button type="button" class="toast-x" aria-label="Dismiss"><svg class="ic w-4 h-4" aria-hidden="true"><use href="#i-x"/></svg></button>';
    function gone() { t.style.opacity = '0'; t.style.transition = 'opacity .3s'; setTimeout(function () { t.remove(); }, 300); }
    t.querySelector('.toast-x').addEventListener('click', gone);
    box.appendChild(t); setTimeout(gone, 3500);
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

  /* ---------- wishlist (server, per signed-in customer) ---------- */
  var WISH = SRV.wish || { auth: false, ids: [], toggle: '', login: '' };
  var wishIds = (WISH.ids || []).map(String);
  var wish = {
    get: function () { return wishIds.slice(); },
    has: function (id) { return wishIds.indexOf(String(id)) > -1; },
    toggle: function (id) {
      if (!WISH.auth) {
        toast('Please sign in to save items to your wishlist.', 'error');
        return Promise.resolve({ ok: false });
      }
      return fetch(WISH.toggle, {
        method: 'POST', credentials: 'same-origin',
        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ product_id: +id })
      }).then(function (res) {
        if (res.status === 401 || res.status === 419) { return { ok: false, message: 'Please sign in to save items to your wishlist.' }; }
        return res.json().catch(function () { return {}; }).then(function (data) {
          if (!res.ok) return { ok: false, message: data.message || 'Something went wrong. Please try again.' };
          wishIds = wishIds.filter(function (x) { return x !== String(id); });
          if (data.wished) wishIds.push(String(id));
          update();
          document.dispatchEvent(new CustomEvent('wish:change', { detail: { id: String(id), wished: !!data.wished, count: data.count } }));
          return { ok: true, wished: !!data.wished, message: data.message };
        });
      }).catch(function () { return { ok: false, message: 'Network problem. Please check your connection.' }; });
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
    if (!qvars.length) { cart.add({ product_id: +qa.id, quantity: 1 }).then(function (r) { done(r, 'Added to cart'); }); return; }
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
      cart.add({ product_id: +qa.id, variant_id: +qvar, quantity: 1 }).then(function (r) { done(r, 'Added to cart'); if (r.ok) closeAll(); });
      return;
    }
    if ((el = q('[data-wish]'))) {
      var host = el.closest('[data-id]'); if (!host || el.disabled) return;
      el.disabled = true;
      wish.toggle(host.dataset.id).then(function (r) { el.disabled = false; if (r.message) toast(r.message, r.ok ? undefined : 'error'); });
      return;
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

  window.JBD = { cfg: CFG, cart: cart, wish: wish, toast: toast, fmt: fmt, esc: esc, update: update, openDrawer: openDrawer, closeAll: closeAll, read: read, write: write, done: done, lineHtml: lineHtml };
  update();
})();

/* Header live search: asks /search/suggest as you type, Enter / "See all" opens the results page. */
(function () {
  'use strict';
  function esc(s) { return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; }); }
  function rx(s) { return s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); }
  var qs = new URLSearchParams(location.search).get('q') || '';
  Array.prototype.forEach.call(document.querySelectorAll('form[role=search][data-suggest-url]'), function (form, n) {
    var input = form.querySelector('input[type=search]'), api = form.getAttribute('data-suggest-url'), shop = form.getAttribute('action') || '/search';
    if (!input || !api) return;
    var pop = document.createElement('div'), act = -1, tm, seq = 0, ctl = null, cache = {};
    pop.className = 's-pop'; pop.hidden = true; pop.id = 'search-list-' + n; pop.setAttribute('role', 'listbox'); form.appendChild(pop);
    input.setAttribute('autocomplete', 'off'); input.setAttribute('role', 'combobox'); input.setAttribute('aria-expanded', 'false'); input.setAttribute('aria-controls', pop.id);
    if (qs && /^\/search\/?$/.test(location.pathname)) input.value = qs;
    function shopUrl(q) { return shop + (shop.indexOf('?') < 0 ? '?' : '&') + 'q=' + encodeURIComponent(q); }
    function show(h) { pop.innerHTML = h; pop.hidden = false; act = -1; input.setAttribute('aria-expanded', 'true'); }
    function hide() { pop.hidden = true; act = -1; input.setAttribute('aria-expanded', 'false'); }
    function fetchHits(q) {
      if (cache[q]) return Promise.resolve(cache[q]);
      if (ctl) ctl.abort();
      ctl = window.AbortController ? new AbortController() : null;
      return fetch(api + (api.indexOf('?') < 0 ? '?' : '&') + 'q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin', signal: ctl ? ctl.signal : undefined })
        .then(function (r) { if (!r.ok) throw new Error(r.status); return r.json(); })
        .then(function (d) { return (cache[q] = d); });
    }
    function render(q, d) {
      var hit = d.products || [], toks = q.toLowerCase().split(/\s+/).filter(Boolean), re = new RegExp('(' + toks.map(rx).join('|') + ')', 'gi');
      if (!hit.length) { show('<div class="s-msg">No products found for “' + esc(q) + '”</div>'); return; }
      function hl(t) { return String(t).split(re).map(function (p, i) { return i % 2 ? '<mark>' + esc(p) + '</mark>' : esc(p); }).join(''); }
      show(hit.map(function (p) {
        return '<a class="s-item" role="option" href="' + esc(p.url) + '"><img src="' + esc(p.image) + '" alt="" width="52" height="52"><span class="s-t"><span class="s-n">' + hl(p.name) + '</span><span class="s-p">' + esc(p.price) + '</span><span class="s-c">' + hl(p.brand || p.category || '') + '</span></span></a>';
      }).join('') + '<a class="s-all" href="' + esc(shopUrl(q)) + '">See all ' + d.total + ' result' + (d.total === 1 ? '' : 's') + ' for “' + esc(q) + '”</a>');
    }
    function lookup() {
      var q = input.value.trim(), id = ++seq;
      if (q.length < 2) { if (ctl) ctl.abort(); hide(); return; }
      if (!cache[q]) show('<div class="s-msg">Searching…</div>');
      fetchHits(q).then(function (d) { if (id === seq) render(q, d); }).catch(function (e) {
        if (id !== seq || (e && e.name === 'AbortError')) return;
        show('<a class="s-all" href="' + esc(shopUrl(q)) + '">Search for “' + esc(q) + '” in shop</a>');
      });
    }
    input.addEventListener('input', function () { clearTimeout(tm); tm = setTimeout(lookup, 200); });
    input.addEventListener('focus', function () { if (input.value.trim() && pop.innerHTML) pop.hidden = false; });
    input.addEventListener('keydown', function (e) {
      var its = pop.querySelectorAll('.s-item');
      if (e.key === 'Escape') { hide(); return; }
      if ((e.key === 'ArrowDown' || e.key === 'ArrowUp') && its.length) {
        e.preventDefault(); act = (act + (e.key === 'ArrowDown' ? 1 : -1) + its.length) % its.length;
        Array.prototype.forEach.call(its, function (el, i) { el.classList.toggle('on', i === act); }); its[act].scrollIntoView({ block: 'nearest' });
      }
    });
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var on = pop.querySelector('.s-item.on'); if (on) { location.href = on.getAttribute('href'); return; }
      var q = input.value.trim(); if (q) location.href = shopUrl(q);
    });
    document.addEventListener('click', function (e) { if (!form.contains(e.target)) hide(); });
  });
})();
