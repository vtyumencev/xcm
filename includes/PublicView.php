<?php

namespace XenioCookies;

use XenioCookies\Interfaces\StorageInterface;

/**
 * Enqueuing scripts and styles
 * Exposing gateways for AJAX requests from the cookie banner
 */
class PublicView
{
    public StorageInterface $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;

        add_action( 'wp_enqueue_scripts', [$this, 'enqueueStyle']);
        add_action( 'wp_enqueue_scripts', [$this, 'enqueueScript']);

        add_action("wp_ajax_xcm_overview", [$this, 'categories']);
        add_action("wp_ajax_nopriv_xcm_overview", [$this, 'categories']);

        add_action("wp_ajax_xcm_vendor", [$this, 'vendor']);
        add_action("wp_ajax_nopriv_xcm_vendor", [$this, 'vendor']);

        add_action("wp_ajax_xcm_update", [$this, 'update']);
        add_action("wp_ajax_nopriv_xcm_update", [$this, 'update']);

        add_action("wp_ajax_xcm_accept_consent_type", [$this, 'acceptConsentType']);
        add_action("wp_ajax_nopriv_xcm_accept_consent_type", [$this, 'acceptConsentType']);
    }

    /**
     * Ajax method for listing categories in the cookie banner
     * @return void
     */
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

    /**
     * Ajax method for listing vendors in the cookie banner
     * @return void
     */
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

    /**
     * Ajax method for saving user's consent made with the banner
     * @return void
     */
    public function update()
    {
        global $wpdb;
        $table_name_categories = $wpdb->prefix . XCM_NAME . '_categories';

        $categories = $wpdb->get_results("
        SELECT id
        FROM {$table_name_categories}");

        $consentList = [];

        foreach ($categories as $category) {
            $consentList[$category->id] = isset($_POST['category'][$category->id]) && $_POST['category'][$category->id] === 'on';
        }

        $settingsJson = $this->updateConsent($consentList);

        echo $settingsJson;

        wp_die();
    }

    public function acceptConsentType()
    {
        if (!isset($_POST['consent_type'])) {
            return;
        }

        $consentList = [];

        $consentType = esc_html($_POST['consent_type']);

        foreach($this->storage->getCategories() as $category) {
            $consentList[$category->id] = $category->consent_type === $consentType || $category->consented;
        }

        $settingsJson = $this->updateConsent($consentList);

        echo $settingsJson;

        wp_die();
    }

    private function updateConsent($consentList)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . XCM_NAME . '_consent_logs';

        $previousConsentId = null;

        // Try to get the previous consent and verify its existence
        try {
            if (isset($_COOKIE['xcm'])) {
                $currentConsent = json_decode(stripslashes($_COOKIE['xcm']));
                if ($currentConsent) {
                    $results = $wpdb->get_results("
                        SELECT id
                        FROM {$table_name}
                        WHERE hash = '" . esc_sql($currentConsent->hash) . "' AND id = " . esc_sql($currentConsent->consent_id)
                    );

                    if ($results) {
                        $previousConsentId = $currentConsent->consent_id;
                    }
                }
            }
        } catch (\Exception $e) {

        }

        $newHash = crc32(time());

        $wpdb->insert(
            $table_name,
            [
                'plugin_version' => XCM_VERSION,
                'content_version' => 1,
                'consent' => json_encode($consentList),
                'hash' => $newHash,
                'previous_consent_id' => $previousConsentId,
            ]
        );

        $settings = [
            'plugin_version' => XCM_VERSION,
            'content_version' => 1,
            'consent' => $consentList,
            'consent_id' => $wpdb->insert_id,
            'hash' => $newHash,
        ];

        $settingsJson = json_encode($settings);

        // Set the cookie for one year
        setcookie(XCM_NAME, $settingsJson, time() + 31536000, '/');

        return $settingsJson;
    }

    /**
     * @return void
     */
    public function enqueueStyle()
    {
        wp_enqueue_style(
            XCM_NAME,
            XCM_DIR_URL . 'public/dist/style.css',
            [],
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
            [],
            XCM_VERSION
        );

        $data = [
            'restUrl' => esc_url_raw(rest_url()),
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'categories' => $this->storage->getCategories(),
            'reloadOnUpdate' => (bool)get_option(XCM_NAME . '_reload_on_update', false),
        ];

        wp_add_inline_script(XCM_NAME,
            'window.XCMSettingsPublic = ' . wp_json_encode($data) . ';',
            'before'
        );
    }
}