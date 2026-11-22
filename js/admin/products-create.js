// Product administration: one handler per action. Editing is handled by actualizar_productos.js.
document.addEventListener('DOMContentLoaded', () => {
  const byId = id => document.getElementById(id);
  const save = byId('guardar-producto');
  const clear = byId('limpiar-formulario');
  const remove = document.querySelector('.borrar-producto');

  save?.addEventListener('click', async event => {
    event.preventDefault();
    const category = byId('producto-categoria')?.value.trim() || '';
    const code = byId('codigo')?.value.trim() || '';
    const name = byId('nombre')?.value.trim() || '';
    const price = byId('precio')?.value.trim() || '';
    const image = byId('imagen')?.files[0];
    if (!category || !code || !name || !Number.isFinite(Number(price)) || Number(price) <= 0 || !image) {
      alert('Completa la categoría, el código, el nombre, el precio y la imagen.');
      return;
    }
    const body = new FormData();
    [['categoria', category], ['codigo', code], ['nombre', name], ['precio', price]].forEach(([k, v]) => body.append(k, v));
    body.append('imagen', image);
    try {
      const res = await TambitoAdmin.request('api/guardar_producto.php', { method: 'POST', body });
      const data = await res.json();
      alert(data.success ? 'Producto creado correctamente.' : (data.message || 'No se pudo crear el producto.'));
      if (data.success) location.reload();
    } catch (_) { alert('No se pudo contactar el servidor.'); }
  });

  clear?.addEventListener('click', event => {
    event.preventDefault();
    ['producto-categoria', 'codigo', 'nombre', 'precio', 'imagen'].forEach(id => {
      if (byId(id)) byId(id).value = '';
    });
  });

  remove?.addEventListener('click', async event => {
    event.preventDefault();
    const code = byId('buscar-codigo')?.value.trim() || '';
    if (!code) return alert('Escribe el código del producto.');
    if (!confirm('¿Eliminar el producto ' + code + ' del catálogo de demostración?')) return;
    const body = new FormData();
    body.append('codigo', code);
    try {
      const res = await TambitoAdmin.request('api/eliminar_producto.php', { method: 'POST', body });
      const data = await res.json();
      alert(data.success ? 'Producto eliminado.' : (data.message || 'No se pudo eliminar.'));
      if (data.success) location.reload();
    } catch (_) { alert('No se pudo contactar el servidor.'); }
  });
});
