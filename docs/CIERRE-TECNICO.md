# Cierre técnico de Tambito — 2026-11-21

**Tipo de cierre:** demostración académica para portafolio, no aplicación comercial ni certificación de seguridad.
**Rama de trabajo:** `feat/completion-hardening-2026`.
**Fuente inicial:** repositorio existente; se respetó su historial.

## Problemas identificados en la auditoría

| Hallazgo | Tratamiento |
| --- | --- |
| APIs que escribían JSON sin comprobar sesión/rol | Control de acceso admin en servidor |
| APIs mutables sin protección contra CSRF | Token de sesión y encabezado en escrituras |
| Subidas con nombre arbitrario sin validar MIME | Control de extensión real, tamaño y nombre aleatorio |
| Recuperación de contraseña solo con correo y edad | Deshabilitada: insegura para restablecer cuentas |
| Conexión MySQL con `root` y contraseña vacía en código | Configuración local no versionada |
| Registro e inicio con validaciones insuficientes | Validación servidor, CSRF y regeneración de sesión |
| Carrito con datos estáticos y alertas sin persistencia | Carrito local funcional, sin backend de pedidos |
| Edición duplicada e incongruente de productos | Un solo manejador de edición y endpoint de eliminación |
| HTML anidado por parciales y enlaces oficiales engañosos | Parciales ajustados y aclaración de demo no oficial |
| README sin instrucciones comprobables | Guía local, arquitectura, pruebas y limitaciones |
| Datos reales de contacto como muestra | Sustituidos por texto de demostración |

## Alcance verificable

**Implementado en código:** lectura y edición autorizada del catálogo, búsqueda por nombre y código, secciones informativas, carrito en localStorage, registro e inicio de sesión mediante MySQL, validaciones en backend y protección básica contra CSRF.

**No implementado:** pedidos, pasarela de pagos, inventario transaccional, recuperación de contraseña por email, entrega y facturación. No se debe etiquetar como tienda en producción.

## Verificación

Las comprobaciones definidas en `.github/workflows/quality.yml` incluyen sintaxis PHP y JavaScript, validación JSON, persistencia local, pruebas unitarias de carrito, códigos HTTP de seguridad y una integración sobre MySQL temporal que ejercita registro, inicio de sesión, permisos por rol y rechazo/aceptación de escritura con CSRF.

**Evidencia:** el workflow de GitHub Actions [44976129269](https://github.com/EdhuMS/Proyecto-Tambito/actions/runs/44976129269) concluyó con resultado **success** para ambos jobs, incluido `mysql-integration`, sobre el commit `b7e2a728895b1876aad339a0742432ab28888f78`. Cambios posteriores en la misma rama requieren su propia validación. No se han ejecutado pruebas E2E visuales con navegador ni auditoría de producción.

## Riesgos y decisiones

1. El antiguo mecanismo de recuperación basado en edad permitía suplantación: se deshabilita hasta contar con un mecanismo seguro de tokens de un solo uso y correo verificado.
2. Imágenes, marca y textos de terceros no están licenciados por este repositorio. Cualquier publicación externa requiere revisión de su derecho de uso.
3. La aplicación conserva un catálogo de muestra y no verifica disponibilidad, precio real ni pagos.
4. La licencia del código queda **pendiente de decisión**, coherente con la exclusión anterior de Tambito de la regularización MIT.
5. Las pruebas existentes no incluyen un despliegue de producción.

## Criterio de cierre

El proyecto puede mostrarse como **prototipo académico documentado** cuando el PR de endurecimiento esté integrado y los checks estáticos/HTTP hayan pasado. La categoría **listo para producción** permanece descartada.

## Próximas acciones opcionales

- Incorporar pruebas automatizadas de registro/login contra MySQL aislado.
- Sustituir marcas e imágenes de terceros por activos de libre uso o propios si se desea distribución comercial.
- Diseñar recuperación de contraseñas con tokens, email verificado y limitación por cuenta/IP.
- Mejorar accesibilidad, visual móvil y casos E2E del flujo de compra simulada.
