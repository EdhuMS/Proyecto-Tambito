# Tambito — tienda de demostración académica

**Proyecto de aprendizaje de desarrollo web full stack:** PHP, MySQL, HTML, CSS, JavaScript y un catálogo editable mediante JSON.

> **Importante:** Tambito es una **demostración educativa no oficial**, inspirada en un formato de tienda de conveniencia. **No está afiliado, patrocinado ni respaldado por Tambo+**. No acepta compras, pagos, encargos ni datos comerciales reales. Algunos nombres, imágenes o signos de terceros se muestran con fines ilustrativos y mantienen sus derechos.

## Funcionalidades

| Módulo | Estado |
| --- | --- |
| Página de inicio, secciones informativas y contacto de demostración | Implementado |
| Catálogo con categorías, búsqueda y fichas de producto | Implementado con datos de muestra |
| Carrito con cantidades, eliminar y total | Implementado, **solo en localStorage** |
| Registro e inicio de sesión (MySQL) | Implementado, requiere configuración local |
| Roles usuario / administrador | Implementado |
| Gestión de contenido, productos y categorías | Implementado con sesión de administrador y token CSRF |
| Subida de imágenes | JPG, PNG o WebP de hasta 3 MB; validación en servidor |
| Recuperación de contraseñas | **Deshabilitada por seguridad** |
| Pedidos, pagos, inventario real y entregas | **No implementados** |

La administración guarda algunos datos en `data/*.json` y utiliza MySQL para las cuentas. No se presenta como producto listo para producción.

## Requisitos

- PHP **8.1 o superior**, con extensiones `mysqli` y `fileinfo` (y soporte de sesiones).
- MySQL o MariaDB compatible con el esquema SQL.
- Un servidor PHP local, por ejemplo PHP CLI o Apache/XAMPP.
- Un navegador moderno.

No requiere Composer ni npm para la ejecución de la aplicación. La biblioteca de iconos Font Awesome se carga desde un CDN externo.

## Instalación local

1. Clona el repositorio:

   ```bash
   git clone https://github.com/EdhuMS/Proyecto-Tambito.git
   cd Proyecto-Tambito
   ```

2. Importa el esquema desde **`scritpSQL/tienda_tambo.sql`** en una base local. Por ejemplo:

   ```bash
   mysql -u root -p < scritpSQL/tienda_tambo.sql
   ```

3. Crea un usuario MySQL de **mínimos privilegios** para el esquema `tienda_tambo` (con acceso solo desde tu equipo). Ejemplo orientativo, ejecutado desde una sesión de administración de MySQL:

   ```sql
   CREATE USER 'tambito_app'@'127.0.0.1' IDENTIFIED BY 'REEMPLAZAR_CON_UNA_CLAVE_LOCAL_SEGURA';
   GRANT SELECT, INSERT, UPDATE, DELETE ON tienda_tambo.* TO 'tambito_app'@'127.0.0.1';
   ```

4. Copia `config.example.php` a **`config.local.php`**, ajusta host, puerto, usuario y contraseña. **No publiques ese archivo**: está excluido mediante `.gitignore`.

5. Inicia el servidor para una prueba **exclusivamente local**:

   ```bash
   php -S 127.0.0.1:8000
   ```

6. Abre [http://127.0.0.1:8000/index.php](http://127.0.0.1:8000/index.php).

**Advertencia:** el servidor integrado de PHP no interpreta reglas Apache `.htaccess`, por lo que no debe exponerse a Internet. Las reglas de protección de directorios están orientadas a Apache; comprueba las políticas equivalentes si utilizas otro servidor.

### Acceder al panel administrativo

Crea una cuenta local desde `registro.php`. Para convertirla en administrador **solo en tu base de datos de prueba**, ejecuta desde una sesión autorizada de MySQL:

```sql
UPDATE tienda_tambo.usuarios SET rol = 'admin' WHERE email = 'TU_CORREO_LOCAL_DE_PRUEBA';
```

Después, cierra la sesión e inicia nuevamente para cargar el rol. El panel está en `administracion.php`.

No se proporcionan contraseñas administrativas predeterminadas. El registro público **siempre** crea cuentas con el rol `usuario`.

## Uso

**Para visitantes:** busca productos desde la navegación, explora categorías, abre detalles, añade artículos al carrito y ajusta cantidades. El carrito se guarda en el navegador, no en MySQL; **no existe checkout real**.

**Para administradores locales:** edita el eslogan, categorías destacadas, información institucional ficticia, contacto de prueba y productos. Las escrituras del panel exigen una sesión de administración válida y un token CSRF.

**Acceso a cuentas:** inicia sesión con una cuenta creada localmente. La recuperación antigua basada en correo y edad se deshabilitó porque no verificaba de manera segura la identidad. Para una futura versión haría falta un canal de correo verificable, tokens aleatorios de uso único con vencimiento y controles contra abuso.

## Estructura

```text
api/                  Endpoints JSON de lectura y edición
css/, js/             Estilos e interacción del navegador
data/                 Catálogo y contenido de demostración (JSON)
img/, uploads/        Imágenes (revisar derechos de terceros)
scritpSQL/            Esquema inicial de MySQL
security.php          Sesiones, CSRF, carga de imágenes y persistencia JSON
conexion.php          Conexión MySQL desde config.local.php
config.example.php    Plantilla de configuración sin secretos
tests/                Pruebas de humo
.github/workflows/    Validaciones automáticas
```

## Pruebas

```bash
find . -type f -name '*.php' -print0 | xargs -0 -n 1 php -l
find js -type f -name '*.js' -print0 | xargs -0 -n 1 node --check
php tests/smoke.php
```

GitHub Actions realiza análisis sintáctico, pruebas del carrito, comprobaciones HTTP de autorización y una integración aislada con MySQL para registro, inicio de sesión, roles y CSRF. **Estas pruebas automatizadas no equivalen a una auditoría de seguridad de producción ni a pruebas E2E visuales.**

## Seguridad y limitaciones conocidas

- Las rutas administrativas de escritura requieren sesión con rol `admin` y token CSRF.
- La autenticación utiliza `password_hash`, `password_verify` y consultas preparadas.
- La conexión MySQL no contiene contraseñas comprometidas en el repositorio; utiliza `config.local.php`.
- Los archivos subidos se validan por tipo real y tamaño, con nombre aleatorio.
- El carrito no almacena datos bancarios ni realiza pagos.
- La recuperación de contraseña, checkout, pedidos y facturación **no existen como servicios operativos**.
- La configuración local y los datos de muestra deben sustituirse o evaluarse antes de cualquier despliegue externo.
- No se ha verificado un entorno público desplegado ni se ha demostrado resistencia frente a todas las vulnerabilidades.

Consulta [CIERRE-TECNICO.md](docs/CIERRE-TECNICO.md) para resultados comprobados, riesgos y pendientes de la demostración.

## Derechos de autor y atribución

**Licencia del proyecto pendiente de definir.** Hasta aclarar la titularidad de código y recursos, no se declara MIT ni otra licencia open source para este repositorio.

Las marcas, logotipos, imágenes de productos, diseños de terceros y demás recursos visuales mantienen los derechos de sus titulares. Este trabajo académico no implica permiso para explotar comercialmente identidades o materiales de terceros. Para cualquier redistribución, revisa esos derechos de forma independiente.

## Historial

- **2025:** prototipo inicial y funcionalidades de acceso de usuarios.
- **2026:** recuperación, corrección y documentación del proyecto como demostración académica; implementación de controles de seguridad básicos, carrito local, pruebas y alcance explícito.

Los cambios están versionados en el historial de GitHub. Este cierre es de **proyecto académico de portafolio**, no una certificación para producción.
