/* MULTIVERSE OF JERSEY — common JS (loaded on every page): theme, header, nav, cart, wishlist, toast, chat.
   Cart & wishlist use localStorage for the HTML demo. Replace the JBD.cart / JBD.wish methods with API calls on the backend. */
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

  /* ---------- cart ---------- */
  var cart = {
    get: function () { return read(KEY_CART, []); },
    save: function (c) { write(KEY_CART, c); update(); document.dispatchEvent(new CustomEvent('cart:change')); },
    add: function (it) {
      var c = cart.get(), k = it.id + '|' + it.size + '|' + (it.opt || ''), f = c.filter(function (x) { return x.key === k; })[0];
      if (f) f.qty = Math.min(10, f.qty + it.qty); else c.push({ key: k, id: it.id, name: it.name, price: +it.price, compare: +it.compare || 0, image: it.image, url: it.url || 'product.html', size: it.size, opt: it.opt || '', qty: it.qty });
      cart.save(c);
    },
    setQty: function (k, q) { var c = cart.get(); c.forEach(function (x) { if (x.key === k) x.qty = Math.max(1, Math.min(10, q)); }); cart.save(c); },
    remove: function (k) { cart.save(cart.get().filter(function (x) { return x.key !== k; })); },
    clear: function () { cart.save([]); },
    count: function () { return cart.get().reduce(function (a, x) { return a + x.qty; }, 0); },
    subtotal: function () { return cart.get().reduce(function (a, x) { return a + x.price * x.qty; }, 0); }
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

  function renderDrawer() {
    var box = $('[data-drawer-items]'); if (!box) return;
    var c = cart.get();
    $('[data-drawer-foot]').style.display = c.length ? '' : 'none';
    $('[data-drawer-subtotal]').textContent = fmt(cart.subtotal());
    if (!c.length) { box.innerHTML = '<div class="empty"><svg class="ic" style="width:48px;height:48px;opacity:.25;margin:auto"><use href="#i-bag"/></svg><p class="font-display font-bold text-lg mt-4">Your cart is empty</p><p class="text-sm text-ink/55 mt-1">Add a jersey to get started.</p><a href="shop.html" class="btn btn-primary mt-5">Shop jerseys</a></div>'; return; }
    box.innerHTML = c.map(function (x) {
      return '<div class="line"><a href="' + esc(x.url) + '"><img src="' + esc(x.image) + '" alt=""></a><div class="min-w-0 flex-1"><a href="' + esc(x.url) + '" class="font-semibold text-sm leading-snug line-clamp-2 hover:text-primary">' + esc(x.name) + '</a><p class="text-xs text-ink/50 mt-0.5">Size ' + esc(x.size) + (x.opt ? ' · ' + esc(x.opt) : '') + '</p>' +
        '<div class="flex items-center justify-between mt-2"><div class="qty !h-9" data-key="' + esc(x.key) + '"><button type="button" data-dq="-1" aria-label="Decrease"><svg class="ic" style="width:14px;height:14px"><use href="#i-minus"/></svg></button><input value="' + x.qty + '" readonly aria-label="Quantity"><button type="button" data-dq="1" aria-label="Increase"><svg class="ic" style="width:14px;height:14px"><use href="#i-plus"/></svg></button></div><b class="font-display">' + fmt(x.price * x.qty) + '</b></div></div>' +
        '<button type="button" class="self-start text-ink/40 hover:text-danger" data-drm="' + esc(x.key) + '" aria-label="Remove"><svg class="ic" style="width:18px;height:18px"><use href="#i-trash"/></svg></button></div>';
    }).join('');
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
  var qa = null, qsize = null;
  function quickAdd(card) {
    qa = card.dataset; qsize = null;
    $('#qm-img').src = qa.image; $('#qm-title').textContent = qa.name; $('#qm-price').textContent = fmt(qa.price);
    $('#qm-err').hidden = true;
    $('#qm-sizes').innerHTML = (qa.sizes || 'S,M,L,XL').split(',').map(function (s) { return '<button type="button" class="size-chip" data-size="' + s + '"><span>' + s + '</span></button>'; }).join('');
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
    if ((el = q('[data-dq]'))) { var k = el.closest('[data-key]').dataset.key, it = cart.get().filter(function (x) { return x.key === k; })[0]; if (it) cart.setQty(k, it.qty + (+el.dataset.dq)); return; }
    if ((el = q('[data-drm]'))) return cart.remove(el.dataset.drm);
    if ((el = q('[data-quick-add]'))) return quickAdd(el.closest('.pcard'));
    if ((el = q('#qm-sizes .size-chip'))) { $$('#qm-sizes .size-chip').forEach(function (b) { b.classList.remove('on'); }); el.classList.add('on'); qsize = el.dataset.size; $('#qm-err').hidden = true; return; }
    if (q('#qm-add')) {
      if (!qsize) { $('#qm-err').hidden = false; return; }
      cart.add({ id: qa.id, name: qa.name, price: qa.price, compare: qa.compare, image: qa.image, url: qa.url, size: qsize, qty: 1 }); toast('Added to cart'); return openDrawer();
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
  window.addEventListener('storage', update);

  window.JBD = { cfg: CFG, cart: cart, wish: wish, toast: toast, fmt: fmt, esc: esc, update: update, openDrawer: openDrawer, closeAll: closeAll, read: read, write: write };
  update();
})();
