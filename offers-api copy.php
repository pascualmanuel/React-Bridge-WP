    <?php
    /**
     * Plugin Name: Offers API
     * Description: Provee un endpoint REST + panel admin minimalista para gestionar ofertas (guardadas en JSON).
     * Version: 1.8
     * Author: Labba Studio - Manuel Labba
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    define( 'SOA_DIR', plugin_dir_path( __FILE__ ) );
    define( 'SOA_URL', plugin_dir_url( __FILE__ ) );
    define( 'SOA_DATA_FILE', SOA_DIR . 'data/offers.json' );

    /**
     * Activation hook: ensure data folder and file exist
     */
    function soa_activate() {
        if ( ! file_exists( SOA_DIR . 'data' ) ) {
            @mkdir( SOA_DIR . 'data', 0755, true );
        }

        if ( ! file_exists( SOA_DATA_FILE ) ) {
            $default = json_encode( array(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
            file_put_contents( SOA_DATA_FILE, $default );
        }
    }
    register_activation_hook( __FILE__, 'soa_activate' );

    /**
     * Load admin menu
     */
    function soa_admin_menu() {
        add_menu_page(
            'Offers API',
            'Offers API',
            'manage_options',
            'simple-offers-api',
            'soa_admin_page',
            'dashicons-megaphone',
            56
        );
    }
    add_action( 'admin_menu', 'soa_admin_menu' );

    /**
     * Render admin page (separate file)
     */
    function soa_admin_page() {
        require SOA_DIR . 'admin/admin-page.php';
    }

    /**
     * Enqueue admin assets
     */
    function soa_admin_assets( $hook ) {
        if ( $hook !== 'toplevel_page_simple-offers-api' ) return;
    
        // 🔥 Esto carga la librería de medios de WordPress
        wp_enqueue_media();
    
        wp_enqueue_style( 'soa-admin-css', SOA_URL . 'assets/admin.css', array(), '1.0' );
        wp_enqueue_script( 'soa-admin-js', SOA_URL . 'admin/admin.js', array( 'jquery' ), '1.0', true );
    
        // Pass REST data & nonce
        $rest_root = esc_url_raw( rest_url() );
        wp_localize_script( 'soa-admin-js', 'SOA_Settings', array(
            'restRoot' => $rest_root,
            'nonce'    => wp_create_nonce( 'wp_rest' ),
        ) );
    }
    
    add_action( 'admin_enqueue_scripts', 'soa_admin_assets' );

    /**
     * Utility: read offers from file
     */
    function soa_read_offers() {
        error_log('SOA: Reading offers from file: ' . SOA_DATA_FILE);
        if ( ! file_exists( SOA_DATA_FILE ) ) {
            error_log('SOA: Data file does not exist');
            return array();
        }
        $raw = file_get_contents( SOA_DATA_FILE );
        error_log('SOA: Raw file content: ' . $raw);
        $data = json_decode( $raw, true );
        if ( ! is_array( $data ) ) {
            error_log('SOA: Invalid JSON data, returning empty array');
            return array();
        }
        error_log('SOA: Successfully read ' . count($data) . ' offers');
        return $data;
    }

    /**
     * Utility: write offers to file (atomic)
     */
    function soa_write_offers( $array ) {
        error_log('SOA: Writing offers to file: ' . SOA_DATA_FILE);
        $json = json_encode( $array, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
        if ( $json === false ) {
            error_log('SOA: Failed to encode JSON');
            return false;
        }
        $tmp = SOA_DATA_FILE . '.tmp';
        error_log('SOA: Writing to temp file: ' . $tmp);
        $ok = (bool) file_put_contents( $tmp, $json );
        if ( ! $ok ) {
            error_log('SOA: Failed to write to temp file');
            return false;
        }
        $rename_ok = rename( $tmp, SOA_DATA_FILE );
        if ( ! $rename_ok ) {
            error_log('SOA: Failed to rename temp file to final file');
            return false;
        }
        error_log('SOA: Successfully wrote offers to file');
        return true;
    }

    /**
     * REST: GET /offers/v1/list
     */
    function soa_rest_list( $request ) {
        error_log('SOA: List request received');
        $offers = soa_read_offers();
        error_log('SOA: Returning ' . count($offers) . ' offers');
        return new WP_REST_Response( $offers, 200 );
    }

    /**
     * REST: POST /offers/v1/save  -> recibe array completo de offers y lo persiste
     */
    function soa_rest_save( $request ) {
        if ( ! current_user_can( 'manage_options' ) ) {
            error_log('SOA: Save request denied - no permission');
            return new WP_Error( 'forbidden', 'No permission', array( 'status' => 403 ) );
        }

        $body = $request->get_json_params();
        error_log('SOA: Save request body: ' . json_encode($body));
        
        if ( ! isset( $body['offers'] ) || ! is_array( $body['offers'] ) ) {
            error_log('SOA: Invalid payload - missing or invalid offers array');
            return new WP_Error( 'invalid', 'Invalid payload', array( 'status' => 400 ) );
        }

        // sanitize each offer
        $clean = array();
        foreach ( $body['offers'] as $offer ) {
            $clean[] = array(
                'id' => isset( $offer['id'] ) ? sanitize_text_field( $offer['id'] ) : uniqid(),
                'image' => isset( $offer['image'] ) ? esc_url_raw( $offer['image'] ) : '',
                'title' => isset( $offer['title'] ) ? sanitize_text_field( $offer['title'] ) : '',
                'description' => isset( $offer['description'] ) ? sanitize_text_field( $offer['description'] ) : '',
                'voucher' => isset( $offer['voucher'] ) ? sanitize_text_field( $offer['voucher'] ) : '',
                'buttonText' => isset( $offer['buttonText'] ) ? sanitize_text_field( $offer['buttonText'] ) : '',
                'buttonLink' => isset( $offer['buttonLink'] ) ? esc_url_raw( $offer['buttonLink'] ) : '',
            );
        }

        error_log('SOA: Cleaned offers: ' . json_encode($clean));
        
        $ok = soa_write_offers( $clean );
        if ( ! $ok ) {
            error_log('SOA: Failed to write offers to file');
            return new WP_Error( 'write_error', 'Could not write file', array( 'status' => 500 ) );
        }

        error_log('SOA: Successfully saved offers');
        return new WP_REST_Response( array( 'success' => true ), 200 );
    }

    /**
     * REST: POST /offers/v1/delete  -> recibe id y elimina la oferta
     */
    function soa_rest_delete( $request ) {
        if ( ! current_user_can( 'manage_options' ) ) {
            return new WP_Error( 'forbidden', 'No permission', array( 'status' => 403 ) );
        }

        $body = $request->get_json_params();
        if ( ! isset( $body['id'] ) ) {
            return new WP_Error( 'invalid', 'Missing id', array( 'status' => 400 ) );
        }
        $id = sanitize_text_field( $body['id'] );

        $offers = soa_read_offers();
        $new = array();
        $found = false;
        foreach ( $offers as $o ) {
            if ( isset( $o['id'] ) && $o['id'] === $id ) {
                $found = true;
                continue;
            }
            $new[] = $o;
        }
        if ( ! $found ) {
            return new WP_Error( 'not_found', 'Offer not found', array( 'status' => 404 ) );
        }

        $ok = soa_write_offers( $new );
        if ( ! $ok ) {
            return new WP_Error( 'write_error', 'Could not write file', array( 'status' => 500 ) );
        }

        return new WP_REST_Response( array( 'success' => true ), 200 );
    }

    /**
     * REST: POST /offers/v1/refresh-nonce  -> refresca el nonce
     */
    function soa_rest_refresh_nonce( $request ) {
        if ( ! current_user_can( 'manage_options' ) ) {
            return new WP_Error( 'forbidden', 'No permission', array( 'status' => 403 ) );
        }
        
        $new_nonce = wp_create_nonce( 'wp_rest' );
        error_log('SOA: Refreshing nonce: ' . $new_nonce);
        return new WP_REST_Response( array( 'nonce' => $new_nonce ), 200 );
    }

    /**
     * Register REST routes
     */
    function soa_register_routes() {
        register_rest_route( 'offers/v1', '/list', array(
            'methods'  => 'GET',
            'callback' => 'soa_rest_list',
            'permission_callback' => '__return_true',
        ) );

        register_rest_route( 'offers/v1', '/save', array(
            'methods'  => 'POST',
            'callback' => 'soa_rest_save',
            'permission_callback' => function() {
                return current_user_can( 'manage_options' );
            },
        ) );

        register_rest_route( 'offers/v1', '/delete', array(
            'methods'  => 'POST',
            'callback' => 'soa_rest_delete',
            'permission_callback' => function() {
                return current_user_can( 'manage_options' );
            },
        ) );

        register_rest_route( 'offers/v1', '/refresh-nonce', array(
            'methods'  => 'POST',
            'callback' => 'soa_rest_refresh_nonce',
            'permission_callback' => function() {
                return current_user_can( 'manage_options' );
            },
        ) );
    }
    add_action( 'rest_api_init', 'soa_register_routes' );
