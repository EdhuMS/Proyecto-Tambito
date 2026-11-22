// Every same-origin admin POST carries the CSRF token provided by the server.
(() => {
  const nativeFetch = window.fetch.bind(window);
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
  window.fetch = (resource, options = {}) => {
    const requestUrl = new URL(typeof resource === 'string' ? resource : resource.url, location.href);
    const method = String(options.method || (resource instanceof Request ? resource.method : 'GET')).toUpperCase();
    if (requestUrl.origin !== location.origin || method !== 'POST' || !csrf) {
      return nativeFetch(resource, options);
    }
    const headers = new Headers(options.headers || (resource instanceof Request ? resource.headers : undefined));
    headers.set('X-CSRF-Token', csrf);
    return nativeFetch(resource, { ...options, headers });
  };
})();
