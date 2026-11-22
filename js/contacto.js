document.addEventListener('DOMContentLoaded', async () => {
  try {
    const response = await fetch('api/contacto.php');
    if (!response.ok) throw new Error('No se pudo cargar la información');
    const payload = await response.json();
    const info = payload.contacto || {};
    const setText = (id, value) => {
      const el = document.getElementById(id);
      if (el) el.textContent = value || 'No disponible en la demostración';
    };
    setText('contacto-telefono', info.telefono);
    setText('contacto-email', info.email);
    setText('contacto-horario', info.horario);

    [['instagram', 'instagram_url', 'instagram_text'],
     ['facebook', 'facebook_url', 'facebook_text'],
     ['tiktok', 'tiktok_url', 'tiktok_text']].forEach(([name, urlKey, textKey]) => {
      const a = document.getElementById('contacto-' + name + '-link');
      const label = document.getElementById('contacto-' + name + '-text');
      if (label) label.textContent = info[textKey] || 'No disponible';
      if (!a) return;
      const url = String(info[urlKey] || '');
      if (/^https:\/\//i.test(url)) {
        a.href = url;
        a.rel = 'noopener noreferrer';
      } else {
        a.removeAttribute('href');
        a.setAttribute('aria-disabled', 'true');
      }
    });
  } catch (_) {
    console.error('No se pudieron cargar los datos de contacto de la demostración.');
  }
});
