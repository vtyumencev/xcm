<?php

namespace XenioCookies;

class AdminPage
{
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'register_admin_dashboard' ));

        add_action( 'admin_enqueue_scripts', function () {
            wp_enqueue_script( 'xcm-scripts', XCM_DIR_URL . 'admin-dashboard/dist/scripts.js', array(), '', array('in_footer' => true));
            wp_enqueue_style( 'xcm-style', XCM_DIR_URL . 'admin-dashboard/dist/style.css', array(), '');
            wp_localize_script( 'xcm-scripts', 'xenioCookiesSettings', array(
                'root' => esc_url_raw( rest_url() ),
                'nonce' => wp_create_nonce( 'wp_rest' )
            ) );
        });
    }
    public function register_admin_dashboard(): void
    {
        add_submenu_page(
            'options-general.php',
            __( 'Consent Manager', 'xcm' ),
            'Consent Manager',
            'manage_options',
            'xcm',
            array($this, 'render_admin_dashboard'),
        );
    }

    public function render_admin_dashboard(): void
    {
        require XCM_DIR . '/templates/admin-dashboard.php';
    }

}