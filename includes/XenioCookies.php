<?php

namespace XenioCookies;

class XenioCookies
{
    public function __construct()
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

        $sql = "CREATE TABLE IF NOT EXISTS $categories_table_name (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            name JSON NOT NULL,
            necessary BOOL NOT NULL,
            description JSON,
            consent_types MEDIUMTEXT,
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
                'necessary' => true,
            ],
            [
                'name' => 'Funktionale',
                'description' => '<p>Funktionale Cookies unterstützen bei der Ausführung bestimmter Funktionen, z. B. beim Teilen des Inhalts der Website auf Social Media-Plattformen, beim Sammeln von Feedbacks und anderen Funktionen von Drittanbietern.</p>',
                'necessary' => false,
            ],
            [
                'name' => 'Analyse',
                'description' => '<p>Analyse-Cookies werden verwendet um zu verstehen, wie Besucher mit der Website interagieren. Diese Cookies dienen zu Aussagen über die Anzahl der Besucher, Absprungrate, Herkunft der Besucher usw.</p>',
                'necessary' => false,
            ],
            [
                'name' => 'Leistungs',
                'description' => '<p>Leistungs-Cookies werden verwendet, um die wichtigsten Leistungsindizes der Website zu verstehen und zu analysieren. Dies trägt dazu bei, den Besuchern ein besseres Nutzererlebnis zu bieten.</p>',
                'necessary' => false,
            ],
            [
                'name' => 'Werbe',
                'description' => '<p>Werbe-Cookies werden verwendet, um Besuchern auf der Grundlage der von ihnen zuvor besuchten Seiten maßgeschneiderte Werbung zu liefern und die Wirksamkeit von Werbekampagne nzu analysieren.</p>',
                'necessary' => false,
            ],
            [
                'name' => 'Nicht kategorisiert',
                'description' => '<p>Andere nicht kategorisierte Cookies sind solche, die analysiert werden und noch nicht in eine Kategorie eingestuft wurden.</p>',
                'necessary' => false,
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
                        'necessary' => $preset['necessary'],
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
}