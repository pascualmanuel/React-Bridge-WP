# WP React Bridge

## Instalación y Configuración

1. **Instalar el Plugin React**

   - Descarga e instala el plugin **React Plugin** desde el repositorio de WordPress o súbelo manualmente a través del panel de administración de WordPress.

2. **Instalar el Empty Theme**

   - Descarga e instala el tema **Empty Theme** desde el repositorio de WordPress o súbelo manualmente a través del panel de administración de temas.

3. **Configurar el Proyecto React**

   - Abre el archivo `package.json` en tu proyecto React.
   - Añade la siguiente línea en la sección de configuración:
     ```json
     "homepage": "/wp-content/plugins/react-plugin/build"
     ```

4. **Generar el Build de React**

   - Ejecuta el comando de build en tu proyecto React. Por lo general, esto se hace con:
     ```bash
     npm run build
     ```

5. **Subir el Build al Plugin**

   - Una vez generado el build, ve a la página de configuración del plugin **React Plugin** en el panel de administración de WordPress.
   - Usa el formulario para cargar el archivo ZIP que contiene la carpeta build generada.

6. **Verificar la Carga**

   - Si el sitio no muestra el contenido del build, verifica que el hosting permita la carga de archivos grandes.

7. **¡Listo!**
   - Tu aplicación React ahora debería estar integrada en tu sitio WordPress.

## Notas

- Asegúrate de que tu archivo ZIP contenga la estructura correcta de la carpeta build.
- Si encuentras problemas, verifica los permisos de archivo y las configuraciones de PHP en tu servidor de hosting.
