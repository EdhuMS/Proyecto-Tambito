/* Shared API client for administration. No global fetch monkey-patch. */
const TambitoAdmin = Object.freeze({
  async request(url, options = {}) {
    const target = new URL(url, location.href);
    if (target.origin !== location.origin) {
      throw new Error('Solo se admiten solicitudes al servidor de la demostración.');
    }
    const method = String(options.method || 'GET').toUpperCase();
    const headers = new Headers(options.headers || {});
    if (method !== 'GET' && method !== 'HEAD') {
      const token = document.querySelector('meta[name="csrf-token"]')?.content;
      if (!token) throw new Error('Falta token de seguridad.');
      headers.set('X-CSRF-Token', token);
    }
    return fetch(url, { ...options, method, headers, credentials: 'same-origin' });
  },
});
