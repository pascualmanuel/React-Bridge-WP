# WP-React-Bridge

## Descripción

El **WP-React-Bridge** permite integrar fácilmente aplicaciones React en tu sitio de WordPress. Puedes cargar tu aplicación React directamente desde el panel de administración de WordPress.

## Instalación

1. **Instala el Plugin React**

   - Descarga el plugin desde el repositorio. Es el zip llamado react-plugin
   - Sube el plugin a tu WordPress.
   - Activa el plugin desde el panel de administración de WordPress.

2. **Instala el Tema Vacío (Empty Theme)**

   - Descarga e instala el tema vacío desde el repositorio. Es el zip llamado empty-theme
   - Activa el tema vacío en la sección de Temas en el panel de administración de WordPress.

3. **Configura el Proyecto React**

   - En el archivo `package.json` de tu proyecto React, agrega la siguiente línea al final del archivo:
     ```json
     "homepage": "/wp-content/plugins/react-plugin/build"
     ```
   - Construye tu proyecto React ejecutando `npm run build` o `yarn build`.

4. **Sube el Build al Plugin**

   - En el panel de administración de WordPress, ve a la página de configuración del plugin React.
   - Arrastra y suelta el archivo ZIP que contiene la carpeta `build` de tu proyecto React.

5. **Solución de Problemas**

   - Si el sitio no carga el build, verifica que tu hosting permita subir archivos pesados.

6. **Ejemplo de Build**

   - Puedes encontrar un ejemplo de build en el [repositorio](enlace-al-repositorio).

## Uso

Una vez que hayas subido y activado tu aplicación React, el contenido se mostrará en el área del pie de página de tu sitio de WordPress. No necesitas modificar el tema de WordPress, ya que el plugin manejará la carga de tu aplicación React.

## Licencia

Este plugin está disponible bajo la [Licencia MIT](enlace-a-la-licencia).
