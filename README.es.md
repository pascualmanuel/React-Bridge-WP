# React Bridge WP

_Este README se puede leer también en [Inglés](README.md), [Alemán](README.de.md), y [Frances](README.fr.md)._

## Descripción

El **React Bridge WP** permite integrar fácilmente aplicaciones React en tu sitio de WordPress. Puedes cargar tu aplicación React directamente desde el panel de administración de WordPress.

## Instalación

1. **Instala el Plugin React**

   - Descarga el plugin desde el repositorio. Es el zip llamado [react-plugin](https://github.com/pascualmanuel/React-Bridge-WP/blob/main/react-plugin.zip).
   - Sube el plugin a tu WordPress.
   - Activa el plugin desde el panel de administración de WordPress. Al activar el plugin, se creará y activará automáticamente un tema vacío llamado **React Bridge Empty Theme**. Además, el plugin generará una página de ejemplo, que debería mostrarse en la página principal del sitio para verificar que el plugin está funcionando correctamente.

2. **Configura el Proyecto React**

   - En el archivo `package.json` de tu proyecto React, agrega la siguiente línea al final del archivo:
     ```json
     "homepage": "/wp-content/plugins/react-plugin/build"
     ```
   - Construye tu proyecto React ejecutando `npm run build` o `yarn build`.

3. **Sube el Build al Plugin**

   - En el panel de administración de WordPress, ve a la página de configuración del React Bridge WP.
   - Arrastra y suelta el archivo ZIP que contiene la carpeta `build` de tu proyecto React.
   - Ese build suplantará el build actual de ejemplo mostrando tu aplicación de React. Se pueden subir tantos builds como sea necesario, y el último que se suba sobrescribirá el anterior.

4. **Solución de Problemas**

   - Si el sitio no carga el build, verifica que tu hosting permita subir archivos pesados.
   - Asegúrate de que la estructura del build sea correcta. El build debe contener una carpeta `static` que incluya las subcarpetas `js`, `css`, y `media`. Puedes ver un ejemplo de la estructura correcta en el repositorio [build-example](https://github.com/pascualmanuel/React-Bridge-WP/tree/main/build-example).

## Requisitos del Hosting

- **Capacidad para Subir Archivos Grandes:**

  - Asegúrate de que tu hosting permita la carga de archivos grandes, ya que el build de React puede ser significativo en tamaño. Revisa la configuración de `upload_max_filesize` y `post_max_size` en el archivo `php.ini`.

- **PHP y WordPress:**

  - El plugin debe ser compatible con la versión de PHP que está usando tu instalación de WordPress. Asegúrate de usar una versión actualizada de PHP y WordPress. Idealmente, PHP 7.4 o superior y WordPress 5.8 o superior.

- **Permisos de Archivo y Carpeta:**

  - El servidor debe permitir la escritura en los directorios donde se almacenan los archivos del build de React. Verifica que los permisos para los directorios `wp-content/plugins/` y `wp-content/themes/` sean adecuados.

- **Recursos del Servidor:**

  - Si el build de React es pesado, tu servidor debe tener suficientes recursos (CPU y RAM) para manejar la carga. Los servidores compartidos podrían tener limitaciones en comparación con servidores dedicados o VPS.

- **Compatibilidad con Plugins y Temas:**
  - Asegúrate de que no haya conflictos con otros plugins o temas que puedan interferir con la carga de scripts y estilos. Un entorno de prueba es útil para validar estas configuraciones.

## Nota Adicional

- **Seguridad:**
  - Asegúrate de que el hosting tenga medidas adecuadas de seguridad para proteger tanto el plugin como la aplicación React. Esto incluye configuraciones básicas como firewalls y protección contra malware.

## Uso

Una vez que hayas subido y activado tu aplicación React, el contenido se mostrará en la página creada por el plugin. No necesitas modificar el tema de WordPress, ya que el plugin manejará la carga de tu aplicación React.

## Licencia

Este plugin está disponible bajo la [Licencia MIT](enlace-a-la-licencia).
