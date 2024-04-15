<?php

namespace XenioCookies;

class AdminPage
{
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'register_admin_dashboard' ));

        add_action( 'admin_enqueue_scripts', function () {
            wp_enqueue_script( 'xcc-scripts', XCM_DIR_URL . 'admin-dashboard/dist/assets/index.js', array(), '', array('in_footer' => true));
            //wp_enqueue_style( 'xcc-style', XCM_DIR_URL . 'admin-dashboard/dist/assets/index.css', array(), '');
            wp_localize_script( 'xcc-scripts', 'xenioCookiesSettings', array(
                'root' => esc_url_raw( rest_url() ),
                'nonce' => wp_create_nonce( 'wp_rest' )
            ) );
        });
    }
    public function register_admin_dashboard(): void
    {
        add_submenu_page(
            'options-general.php',
            __( 'Cookies', 'xenio-cookies' ),
            'Cookies',
            'manage_options',
            'xenio-cookies',
            array($this, 'render_admin_dashboard'),
        );
    }

    public function render_admin_dashboard(): void
    {
        require XCM_DIR . '/templates/admin-dashboard.php';
    }

}