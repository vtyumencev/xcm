<?php

namespace XenioCookies;

class AdminPage
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'register_admin_dashboard']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function register_admin_dashboard(): void
    {
        add_submenu_page(
            'options-general.php',
            __('Consent Manager', 'xcm'),
            __('Consent Manager', 'xcm'),
            'manage_options',
            'xcm',
            [$this, 'render_admin_dashboard']
        );
    }

    public function enqueue_assets(string $hook_suffix): void
    {
        if ($hook_suffix !== 'settings_page_xcm') {
            return;
        }

        wp_enqueue_script(
            'xcm-scripts',
            XCM_DIR_URL . 'admin-dashboard/dist/scripts.js',
            [],
            null,
            true
        );

        wp_enqueue_style(
            'xcm-style',
            XCM_DIR_URL . 'admin-dashboard/dist/style.css',
            [],
            null
        );

        wp_localize_script(
            'xcm-scripts',
            'xenioCookiesSettings',
            [
                'root'  => esc_url_raw(rest_url()),
                'nonce' => wp_create_nonce('wp_rest'),
            ]
        );
    }

    public function render_admin_dashboard(): void
    {
        if (!current_user_can('manage_options')) {
            wp_die(
                esc_html__('You do not have permission to access this page.', 'xcm')
            );
        }

        require XCM_DIR . '/templates/admin-dashboard.php';
    }
}