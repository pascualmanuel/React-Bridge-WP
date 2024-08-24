<?php
/*
Plugin Name: WP-React-Bridge
Description: Un plugin para integrar React en WordPress.
Version: 4.9
Author: Labba Studio - Manuel
Author URI: http://www.labba.studio/
*/

// Añadir el menú de WP-React-Bridge en el panel de administración
function react_plugin_menu() {
    add_menu_page(
        'WP-React-Bridge',            // Título de la página
        'WP-React-Bridge',            // Título del menú
        'manage_options',             // Capacidad
        'wp-react-bridge',            // Slug del menú
        'react_plugin_settings_page', // Función que muestra la página
        plugin_dir_url(__FILE__) . 'assets/icon.svg', // URL del ícono SVG personalizado
        6                             // Posición del menú
    );
}
add_action('admin_menu', 'react_plugin_menu');


// Mostrar la página de configuración
function react_plugin_settings_page() {
    ?>
    <div class="wrap">
        <h1>Configuración del Plugin React</h1>
        <form method="post" enctype="multipart/form-data" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="handle_file_upload" />
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Cargar Carpeta Build</th>
                    <td>
                        <input type="file" name="react_plugin_build_upload" accept=".zip" />
                        <p class="description">Adjunte un archivo ZIP que contenga la carpeta build de su proyecto React.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button('Subir Carpeta Build'); ?>
        </form>
        <?php
        // Mostrar mensajes de éxito o error
        if (isset($_GET['upload_status'])) {
            if ($_GET['upload_status'] === 'success') {
                echo '<div class="updated notice is-dismissible"><p>Carpeta build cargada exitosamente.</p></div>';
            } elseif ($_GET['upload_status'] === 'error') {
                echo '<div class="error notice is-dismissible"><p>Error al cargar la carpeta build. Asegúrate de que el archivo esté en formato zip.</p></div>';
            }
        }
        ?>
    </div>
    <?php
}

// Manejar la carga de archivos
function handle_file_upload() {
    if (!current_user_can('manage_options')) {
        wp_die('No tienes permisos suficientes para acceder a esta página.');
    }

    if (isset($_FILES['react_plugin_build_upload']) && !empty($_FILES['react_plugin_build_upload']['tmp_name'])) {
        $uploaded_file = $_FILES['react_plugin_build_upload'];
        $plugin_dir = plugin_dir_path(__FILE__);
        $build_path = $plugin_dir . 'build/';
        $temp_extract_path = $plugin_dir . 'temp_extract/';

        if (!file_exists($build_path)) {
            wp_mkdir_p($build_path);
        }
        if (!file_exists($temp_extract_path)) {
            wp_mkdir_p($temp_extract_path);
        }

        $zip = new ZipArchive;
        if ($zip->open($uploaded_file['tmp_name']) === TRUE) {
            // Limpia el directorio build si ya existe
            $files = glob($build_path . '*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                } elseif (is_dir($file)) {
                    $dir = new RecursiveDirectoryIterator($file, FilesystemIterator::SKIP_DOTS);
                    $files = new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::CHILD_FIRST);
                    foreach ($files as $fileinfo) {
                        $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
                        $todo($fileinfo->getRealPath());
                    }
                    rmdir($file);
                }
            }

            // Extrae el ZIP al directorio temporal
            $zip->extractTo($temp_extract_path);
            $zip->close();

            // Verifica si hay una carpeta adicional "build" dentro de la extracción
            $extracted_build_path = $temp_extract_path . 'build/';
            if (file_exists($extracted_build_path)) {
                $files = glob($extracted_build_path . '*');
                foreach ($files as $file) {
                    $dest = $build_path . basename($file);
                    rename($file, $dest);
                }

                // Elimina la carpeta adicional
                rmdir($extracted_build_path);
            } else {
                // Si no hay una carpeta adicional, mueve el contenido directamente
                $files = glob($temp_extract_path . '*');
                foreach ($files as $file) {
                    $dest = $build_path . basename($file);
                    rename($file, $dest);
                }
            }

            // Limpia el directorio temporal
            $temp_files = glob($temp_extract_path . '*');
            foreach ($temp_files as $file) {
                if (is_file($file)) {
                    unlink($file);
                } elseif (is_dir($file)) {
                    $dir = new RecursiveDirectoryIterator($file, FilesystemIterator::SKIP_DOTS);
                    $files = new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::CHILD_FIRST);
                    foreach ($files as $fileinfo) {
                        $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
                        $todo($fileinfo->getRealPath());
                    }
                    rmdir($file);
                }
            }
            rmdir($temp_extract_path);

            wp_redirect(add_query_arg('upload_status', 'success', wp_get_referer()));
            exit;
        } else {
            wp_redirect(add_query_arg('upload_status', 'error', wp_get_referer()));
            exit;
        }
    } else {
        wp_redirect(add_query_arg('upload_status', 'error', wp_get_referer()));
        exit;
    }
}

add_action('admin_post_handle_file_upload', 'handle_file_upload');
add_action('admin_post_nopriv_handle_file_upload', 'handle_file_upload');

// Encolar los scripts de React y ReactDOM
function enqueue_react_scripts() {
    // Encolar React y ReactDOM desde un CDN
    wp_enqueue_script('react-script', 'https://unpkg.com/react/umd/react.development.js', array(), null, true);
    wp_enqueue_script('react-dom-script', 'https://unpkg.com/react-dom/umd/react-dom.development.js', array('react-script'), null, true);

    $plugin_dir = plugin_dir_path(__FILE__) . 'build/';

    if (!file_exists($plugin_dir)) {
        return; // Si no existe la carpeta build, no hacer nada
    }

    $js_files = glob($plugin_dir . 'static/js/*.js');
    $css_files = glob($plugin_dir . 'static/css/*.css');

    $js_file = null;
    $css_file = null;

    // Filtra solo los archivos que comienzan con 'main.'
    foreach ($js_files as $file) {
        if (strpos(basename($file), 'main.') === 0 && pathinfo($file, PATHINFO_EXTENSION) === 'js') {
            $js_file = basename($file);
            break;
        }
    }

    foreach ($css_files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'css') {
            $css_file = basename($file);
            break;
        }
    }

    if ($js_file) {
        wp_enqueue_script('my-react-app-js', plugins_url('build/static/js/' . $js_file, __FILE__), array('react-script', 'react-dom-script'), null, true);
    }

    if ($css_file) {
        wp_enqueue_style('my-react-app-css', plugins_url('build/static/css/' . $css_file, __FILE__));
    }
}
add_action('wp_enqueue_scripts', 'enqueue_react_scripts');

function add_react_container() {
    if (is_page_template('template-react.php')) {
        echo '<div id="root"></div>';
    }
}
add_action('wp_footer', 'add_react_container');

// Agregar una página personalizada con un template específico
function create_react_page() {
    $page_title = 'React App';
    $page_content = '[react_app]';
    $page_template = 'template-react.php';

    if (!get_page_by_title($page_title)) {
        $page_id = wp_insert_post(array(
            'post_title' => $page_title,
            'post_content' => $page_content,
            'post_status' => 'publish',
            'post_type' => 'page',
            'page_template' => $page_template
        ));
    }
}
add_action('init', 'create_react_page');
?>
