# Informe de refactorización MVC y UI

- **Proyecto:** Tambito (demostración académica).
- **Rama de trabajo:** `refactor/mvc-component-ui`.
- **Integración:** pendiente de verificación final.
- **Licencia:** sin cambios; no se concede MIT automáticamente.
- **Objetivo:** corregir arquitectura, duplicación, CSS y adaptación móvil.

## Alcance implementado

1. **PHP MVC:** controladores separados para storefront, autenticación, administración y API; repositorios de contenido y de edición.
2. **Vistas:** layouts públicos y administrativos y plantillas por pantalla.
3. **Componentes:** cabecera, footer, tarjetas de producto, tarjetas de categoría, cabeceras de sección y campos de formulario.
4. **Diseño:** tokens y CSS modular reutilizable, responsive desde móviles pequeños hasta escritorio; eliminación de hojas antiguas.
5. **JavaScript:** módulo de menú accesible y cliente común de API administrativo; módulos aislados por operación; carrito local reutilizable.
6. **Compatibilidad:** rutas PHP existentes delegan a los controladores; no se cambian los formatos de las APIs.
7. **Calidad:** smoke MVC, pruebas de UI con Playwright y pruebas previas de seguridad/servidor y MySQL.

## Criterios de aceptación

- Todas las páginas públicas muestran un solo layout y no presentan errores PHP.
- Los formularios de inicio y registro conservan token CSRF y el backend funcional.
- Catálogo conserva 40 productos, búsquedas y filtro de categorías.
- Carrito conserva estado en localStorage.
- El panel mantiene permisos en servidor y no duplica listeners.
- Ninguna página pública tiene scroll horizontal a 360, 390, 768 o escritorio.
- La CI queda verde antes de fusionar.

## Limitaciones

- El diseño nuevo debe validarse visualmente, además de las pruebas automatizadas de desbordamiento.
- No se habilitan pagos, entregas, recuperación por email ni operación comercial.
- Los assets existentes se mantienen; revisar titularidad antes de distribución.
- El historial previo se conserva mediante Git.

## Cierre previsto

El PR podrá fusionarse únicamente cuando las validaciones del último commit pasen y queden identificadas las tareas de seguimiento. Los resultados deben enlazarse desde este informe; nunca se declararán pruebas no ejecutadas como satisfactorias.
