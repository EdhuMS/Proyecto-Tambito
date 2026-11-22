/* Client-only demo cart: no checkout, payment, account or remote storage. */
(() => {
  'use strict';
  const KEY = 'tambito_demo_cart_v1';
  const maxQuantity = 99;
  let cart = [];
  try {
    const stored = JSON.parse(localStorage.getItem(KEY) || '[]');
    if (Array.isArray(stored)) cart = stored.filter(item =>
      item && typeof item.codigo === 'string' && typeof item.nombre === 'string' &&
      Number.isFinite(item.precio) && item.precio >= 0 &&
      Number.isInteger(item.cantidad) && item.cantidad > 0
    ).slice(0, 100);
  } catch (_) { cart = []; }

  const persist = () => {
    try { localStorage.setItem(KEY, JSON.stringify(cart)); } catch (_) {
      // Private browsing may disallow localStorage: cart survives current page only.
    }
    document.querySelectorAll('.cart-count').forEach(el => {
      el.textContent = String(cart.reduce((sum, item) => sum + item.cantidad, 0));
    });
    window.dispatchEvent(new Event('tambito:cart-updated'));
  };

  const normalize = raw => {
    if (!raw || typeof raw !== 'object') return null;
    const codigo = String(raw.codigo || '').trim().slice(0, 50);
    const nombre = String(raw.nombre || '').trim().slice(0, 150);
    const precio = Number(raw.precio);
    const imagen_url = String(raw.imagen_url || '').slice(0, 250);
    if (!codigo || !nombre || !Number.isFinite(precio) || precio < 0 ||
      !/^(\.\/)?(img|uploads)\/[\w./-]+$/i.test(imagen_url) ||
      imagen_url.includes('..')) return null;
    return { codigo, nombre, precio, imagen_url };
  };

  function add(raw, quantity = 1) {
    const item = normalize(raw);
    const qty = Math.max(1, Math.min(maxQuantity, Math.floor(Number(quantity) || 1)));
    if (!item) return false;
    const existing = cart.find(value => value.codigo === item.codigo);
    if (existing) existing.cantidad = Math.min(maxQuantity, existing.cantidad + qty);
    else cart.push({ ...item, cantidad: qty });
    persist();
    return true;
  }

  function remove(code) {
    cart = cart.filter(item => item.codigo !== String(code));
    persist();
  }

  function setQuantity(code, quantity) {
    const item = cart.find(value => value.codigo === String(code));
    if (!item) return;
    const qty = Math.floor(Number(quantity));
    if (!Number.isFinite(qty) || qty <= 0) return remove(code);
    item.cantidad = Math.min(maxQuantity, qty);
    persist();
  }

  window.TambitoCart = { add, remove, setQuantity, items: () => cart.map(item => ({ ...item })) };
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.cart-count').forEach(el => {
      el.textContent = String(cart.reduce((sum, item) => sum + item.cantidad, 0));
    });
  });

  document.addEventListener('click', e => {
    const button = e.target.closest('.product-card .add-to-cart');
    if (!button) return;
    e.preventDefault();
    e.stopPropagation();
    const card = button.closest('.product-card');
    if (!card) return;
    try {
      const item = JSON.parse(decodeURIComponent(card.dataset.product || ''));
      if (add(item, 1)) {
        button.textContent = '✓';
        button.setAttribute('aria-label', 'Producto agregado al carrito de demostración');
        setTimeout(() => { button.textContent = '+'; }, 900);
      }
    } catch (_) { /* invalid product data is ignored */ }
  });
})();
