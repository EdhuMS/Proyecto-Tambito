document.addEventListener('DOMContentLoaded', () => {
  const list = document.getElementById('cart-list');
  const totalEl = document.getElementById('cart-summary');
  if (!list || !window.TambitoCart) return;
  const money = number => 'S/ ' + number.toFixed(2);
  function render() {
    const items = window.TambitoCart.items();
    list.replaceChildren();
    if (!items.length) {
      const p = document.createElement('p');
      p.textContent = 'Aún no has agregado productos.';
      list.appendChild(p);
      totalEl.textContent = '';
      return;
    }
    let total = 0;
    items.forEach(item => {
      total += item.precio * item.cantidad;
      const row = document.createElement('article');
      row.className = 'cart-row';
      const image = document.createElement('img');
      image.src = item.imagen_url;
      image.alt = item.nombre;
      const info = document.createElement('div');
      const title = document.createElement('strong');
      title.textContent = item.nombre;
      const price = document.createElement('p');
      price.textContent = money(item.precio) + ' c/u';
      info.append(title, price);
      const controls = document.createElement('div');
      controls.className = 'cart-actions';
      const minus = document.createElement('button');
      minus.textContent = '−'; minus.setAttribute('aria-label', 'Reducir ' + item.nombre);
      minus.addEventListener('click', () => window.TambitoCart.setQuantity(item.codigo, item.cantidad - 1));
      const count = document.createElement('span');
      count.textContent = String(item.cantidad);
      const plus = document.createElement('button');
      plus.textContent = '+'; plus.setAttribute('aria-label', 'Aumentar ' + item.nombre);
      plus.addEventListener('click', () => window.TambitoCart.setQuantity(item.codigo, item.cantidad + 1));
      const remove = document.createElement('button');
      remove.textContent = 'Eliminar';
      remove.addEventListener('click', () => window.TambitoCart.remove(item.codigo));
      controls.append(minus, count, plus, remove);
      row.append(image, info, controls);
      list.appendChild(row);
    });
    totalEl.textContent = 'Total estimado (sin pago): ' + money(total);
  }
  window.addEventListener('tambito:cart-updated', render);
  render();
});
