// Shared accessible interactions for the public MVC layout.
document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('[data-menu-toggle]');
  const menu = document.getElementById('nav-menu');
  if (!toggle || !menu) return;

  const setOpen = open => {
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? 'Cerrar menú de navegación' : 'Abrir menú de navegación');
    toggle.querySelector('span').textContent = open ? '✕' : '☰';
    if (open) menu.setAttribute('data-open', '');
    else menu.removeAttribute('data-open');
  };

  toggle.addEventListener('click', () => setOpen(toggle.getAttribute('aria-expanded') !== 'true'));
  menu.addEventListener('click', event => {
    if (event.target.closest('a')) setOpen(false);
  });
  document.addEventListener('keydown', event => {
    if (event.key === 'Escape') setOpen(false);
  });
  const media = window.matchMedia('(min-width: 901px)');
  media.addEventListener?.('change', () => setOpen(false));
});
