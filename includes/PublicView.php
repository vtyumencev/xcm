<?php

namespace XenioCookies;

use XenioCookies\Interfaces\StorageInterface;

/**
 * Enqueuing scripts and styles
 * Exposing gateways for AJAX requests form clients
 */
class PublicView
{
    public function __construct(
        public StorageInterface $storage
    )
    {
        add_action( 'wp_enqueue_scripts', array($this, 'enqueueStyle'));
        add_action( 'wp_enqueue_scripts', array($this, 'enqueueScript'));

        add_action("wp_ajax_xcm_overview", array($this, 'categories'));
        add_action("wp_ajax_nopriv_xcm_overview", array($this, 'categories'));

        add_action("wp_ajax_xcm_vendor", array($this, 'vendor'));
        add_action("wp_ajax_nopriv_xcm_vendor", array($this, 'vendor'));

        add_action("wp_ajax_xcm_update", array($this, 'update'));
        add_action("wp_ajax_nopriv_xcm_update", array($this, 'update'));
    }

    public function categories()
    {
        $locale = get_locale();

        $categories = $this->storage->getCategories();
        $args['title'] = get_option( XCM_NAME . "_{$locale}_overview_title");
        $args['description'] = get_option( XCM_NAME . "_{$locale}_overview_description");
        $args['categories'] = $categories;

        require XCM_DIR . '/templates/categories-index.php';
        wp_die();
    }

    public function vendor()
    {
        $locale = get_locale();

        $vendorId = $_GET['vendorId'];

        $periods = [
            'session' => 'Session',
            'years' => '%d years',
            'months' => '%d months',
            'minutes' => '%d minutes',
            'hours' => '%d hours',
            'days' => '%d days',
        ];

        global $wpdb;
        $table_name_vendors = $wpdb->prefix . XCM_NAME . '_vendors';

        $vendors = $wpdb->get_results("
            SELECT 
                id,
                name,
                category_id,
                cookies,
                link,
                JSON_UNQUOTE(JSON_EXTRACT(description, '$.{$locale}')) as description
            FROM {$table_name_vendors}
            WHERE id = {$vendorId}");

        if (! $vendors) {
            die();
        }

        $vendors[0]->cookies = json_decode($vendors[0]->cookies);

        $args['vendor'] = $vendors[0];
        $args['vendorId'] = $vendorId;
        $args['periods'] = $periods;

        require XCM_DIR . '/templates/vendor-show.php';
        wp_die();
    }

    public function update()
    {
        global $wpdb;
        $table_name_categories = $wpdb->prefix . XCM_NAME . '_categories';

        $categories = $wpdb->get_results("
            SELECT id
            FROM {$table_name_categories}
            WHERE necessary = false");

        $consentList = array();

        foreach ($categories as $category) {
            $consentList[$category->id] = isset($_POST['category'][$category->id]) && $_POST['category'][$category->id] === 'on';
        }

        $table_name = $wpdb->prefix . XCM_NAME . '_consent_logs';

        $wpdb->insert(
            $table_name,
            array(
                'plugin_version' => XCM_VERSION,
                'content_version' => XCM_VERSION,
                'consent' => json_encode($consentList)
            )
        );

        $settings = array(
            'plugin_version' => XCM_VERSION,
            'content_version' => XCM_VERSION,
            'consent' => $consentList,
            'consent_id' => $wpdb->insert_id
        );

        $settingsJson = json_encode($settings);
        setcookie(XCM_NAME, $settingsJson, time() + 31536000, '/'); // One Year

        echo $settingsJson;
        wp_die();
    }

    /**
     * @return void
     */
    public function enqueueStyle()
    {
        wp_enqueue_style(
            XCM_NAME,
            XCM_DIR_URL . 'public/dist/style.css',
            array(),
            XCM_VERSION
        );
    }

    /**
     * @return void
     */
    public function enqueueScript()
    {
        wp_enqueue_script(
            XCM_NAME,
            XCM_DIR_URL . 'public/dist/scripts.js',
            array(),
            XCM_VERSION
        );

        wp_localize_script( XCM_NAME, 'XCMSettingsPublic', array(
            'restUrl' => esc_url_raw( rest_url() ),
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'categories' => $this->storage->getCategories(),
        ));
    }
}