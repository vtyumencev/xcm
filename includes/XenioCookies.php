<?php

namespace XenioCookies;

class XenioCookies
{
    /**
     * @var bool
     */
    private $consentedFunctional = false;

    /**
     * @var bool
     */
    private $consentedAnalytics = false;

    /**
     * @var bool
     */
    private $consentedAd = false;
    /**
     * The Singleton's instance is stored in a static field. This field is an
     * array, because we'll allow our Singleton to have subclasses. Each item in
     * this array will be an instance of a specific Singleton's subclass. You'll
     * see how this works in a moment.
     */
    private static $instances = [];

    /**
     * The Singleton's constructor should always be private to prevent direct
     * construction calls with the `new` operator.
     */
    protected function __construct()
    {
        register_activation_hook( XCM_FILE, array($this, 'pluginActivation'));

        load_plugin_textdomain( 'xcm', false, basename( dirname( XCM_FILE ) ) . '/languages' );

        $storage = new Storage();

        new RestAPI();

        if (is_admin()) {
            new AdminPage();
        }

        if ($storage->isPluginActive()) {
            new PublicView($storage);
        }

        if ($storage->isPluginActive() && !is_admin()) {
            new EmbedReplacer($storage);
        }

        $functionalStorage = array_filter(
            $storage->getCategories(),
            fn($category) =>
                $category->consent_type === 'functional' &&
                $category->consented === true
        );

        $this->consentedFunctional = !empty($functionalStorage);

        $adStorage = array_filter(
            $storage->getCategories(),
            fn($category) =>
                $category->consent_type === 'advertisement' &&
                $category->consented === true
        );

        $this->consentedAd = !empty($adStorage);

        $analyticsStorage = array_filter(
            $storage->getCategories(),
            fn($category) =>
                $category->consent_type === 'analytics' &&
                $category->consented === true
        );

        $this->consentedAnalytics = !empty($analyticsStorage);
    }

    /**
     * Singletons should not be cloneable.
     */
    protected function __clone()
    {
    }

    /**
     * Singletons should not be restorable from strings.
     * @throws \Exception
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    /**
     * This is the static method that controls the access to the singleton
     * instance. On the first run, it creates a singleton object and places it
     * into the static field. On subsequent runs, it returns the client existing
     * object stored in the static field.
     *
     * This implementation lets you subclass the Singleton class while keeping
     * just one instance of each subclass around.
     *
     * @return XenioCookies
     */
    public static function getInstance()
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }

        return self::$instances[$cls];
    }

    /**
     * @return void
     */
    public function pluginActivation()
    {
        global $wpdb;
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $charset_collate = $wpdb->get_charset_collate();

        $categories_table_name = $wpdb->prefix . XCM_NAME . '_categories';

        $sql = "CREATE TABLE $categories_table_name (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            name JSON NOT NULL,
            description JSON,
            consent_type MEDIUMTEXT,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        dbDelta($sql);

        $vendors_table_name = $wpdb->prefix . XCM_NAME . '_vendors';

        $sql = "CREATE TABLE IF NOT EXISTS $vendors_table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            category_id INT UNSIGNED NOT NULL,
            name MEDIUMTEXT NOT NULL,
            link MEDIUMTEXT,
            description JSON,
            provider MEDIUMTEXT,
            cookies JSON,
            PRIMARY KEY  (id),
            FOREIGN KEY  (category_id) REFERENCES `$categories_table_name`(id)
        ) $charset_collate;";

        dbDelta( $sql );

        $entries = $wpdb->get_results("SELECT id FROM {$categories_table_name} LIMIT 1");

        $title = "Einstellungen für die Zustimmung anpassen";

        $description = "<p>Wir verwenden Cookies, damit Sie effizient navigieren und bestimmte Funktionen ausführen können. Detaillierte Informationen zu allen Cookies finden Sie unten unter jeder Einwilligungskategorie.</p>";

        $presets = [
            [
                'name' => 'Notwendige',
                'description' => '<p>Notwendige Cookies sind für die Grundfunktionen der Website von entscheidender Bedeutung. Ohne sie kann die Website nicht in der vorgesehenen Weise funktionieren. Diese Cookies speichern keine personenbezogenen Daten.</p>',
                'consent_type' => 'necessary',
            ],
            [
                'name' => 'Funktionale',
                'description' => '<p>Funktionale Cookies unterstützen bei der Ausführung bestimmter Funktionen, z. B. beim Teilen des Inhalts der Website auf Social Media-Plattformen, beim Sammeln von Feedbacks und anderen Funktionen von Drittanbietern.</p>',
                'consent_type' => 'functional',
            ],
            [
                'name' => 'Analyse',
                'description' => '<p>Analyse-Cookies werden verwendet um zu verstehen, wie Besucher mit der Website interagieren. Diese Cookies dienen zu Aussagen über die Anzahl der Besucher, Absprungrate, Herkunft der Besucher usw.</p>',
                'consent_type' => 'analytics',
            ],
            [
                'name' => 'Werbe',
                'description' => '<p>Werbe-Cookies werden verwendet, um Besuchern auf der Grundlage der von ihnen zuvor besuchten Seiten maßgeschneiderte Werbung zu liefern und die Wirksamkeit von Werbekampagne nzu analysieren.</p>',
                'consent_type' => 'advertisement',
            ],
            [
                'name' => 'Nicht kategorisiert',
                'description' => '<p>Andere nicht kategorisierte Cookies sind solche, die analysiert werden und noch nicht in eine Kategorie eingestuft wurden.</p>',
                'consent_type' => '',
            ],
        ];

        $languages = array_merge(['en_US'], get_available_languages());

        if (empty($entries)) {
            foreach ($presets as $preset) {

                $nameArray = [];

                $descriptionArray = [];

                foreach ($languages as $locale) {
                    $nameArray[$locale] = $preset['name'];
                }

                foreach ($languages as $locale) {
                    $descriptionArray[$locale] = $preset['description'];
                }

                $wpdb->insert(
                    $categories_table_name,
                    array(
                        'name' => json_encode($nameArray),
                        'description' => json_encode($descriptionArray),
                        'consent_type' => $preset['consent_type'],
                    )
                );
            }
        }

        $consent_logs_table_name = $wpdb->prefix . XCM_NAME . '_consent_logs';

        $sql = "CREATE TABLE $consent_logs_table_name (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            datetime DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            consent JSON,
            plugin_version VARCHAR(255),
            content_version INT UNSIGNED,
            hash VARCHAR(255),
            previous_consent_id INT UNSIGNED,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        dbDelta($sql);

        update_option( XCM_NAME . '_db_version',  XCM_DB_VERSION, 'no');

        if (! get_option( XCM_NAME . "_is_active")) {
            update_option( XCM_NAME . "_is_active",  true, 'no');
        }

        foreach ($languages as $locale) {
            if (! get_option( XCM_NAME . "_{$locale}_overview_title")) {
                update_option( XCM_NAME . "_{$locale}_overview_title", $title, false );
            }

            if (! get_option( XCM_NAME . "_{$locale}_overview_description")) {
                update_option( XCM_NAME . "_{$locale}_overview_description", $description, false );
            }
        }

        update_option( XCM_NAME . '_is_active',  true, false);
    }

    /**
     * @return bool
     */
    public function consentedFunctional()
    {
        return $this->consentedFunctional;
    }

    /**
     * @return bool
     */
    public function consentedAnalytics()
    {
        return $this->consentedAnalytics;
    }

    /**
     * @return bool
     */
    public function consentedAd()
    {
        return $this->consentedAd;
    }
}