<?php

namespace XenioCookies;

use XenioCookies\Interfaces\StorageInterface;

class Storage implements StorageInterface
{
    private static $categories;

    private $isPluginActive;

    public function __construct()
    {
        $this->isPluginActive = get_option(XCM_NAME . "_is_active") === '1';
    }

    public function isPluginActive()
    {
        return $this->isPluginActive;
    }

    public function getCategories()
    {
        if (empty(self::$categories)) {
            $this->fetchCategories();
        }
        return self::$categories;
    }

    private function fetchCategories()
    {
        $locale = get_locale();

        global $wpdb;
        $table_name_categories = $wpdb->prefix . XCM_NAME . '_categories';
        $table_name_vendors = $wpdb->prefix . XCM_NAME . '_vendors';

        $categories = $wpdb->get_results("
        SELECT 
            id,
            JSON_UNQUOTE(JSON_EXTRACT(name, '$.{$locale}')) as name,
            JSON_UNQUOTE(JSON_EXTRACT(description, '$.{$locale}')) as description,
            consent_type
        FROM {$table_name_categories}");

        $vendors = $wpdb->get_results("
        SELECT 
            id,
            name,
            category_id,
            provider
        FROM {$table_name_vendors}");

        foreach ($categories as $category) {
            $category->vendors = [];
            foreach ($vendors as $vendor) {
                if ($vendor->category_id === $category->id) {
                    $category->vendors[] = $vendor;
                }
            }
        }

        $consented = [];

        $consentRaw = $_COOKIE[XCM_NAME] ?? null;
        if ($consentRaw) {
            $consentArray = json_decode($consentRaw, true);
            if ($consentArray) {
                $consented = $consentArray['consent'] ?? [];
            }
        }

        $consentTypeMap = [
            'advertisement'   => 'ad_storage,ad_personalization,ad_user_data',
            'analytics'       => 'analytics_storage',
            'functional'      => 'functionality_storage',
            'personalization' => 'personalization_storage',
        ];

        foreach ($categories as $category) {
            $category->consent_types = $consentTypeMap[$category->consent_type] ?? '';

            if (isset($consented[$category->id]) && $consented[$category->id] === true) {
                $category->consented = true;
            } else {
                $category->consented = false;
            }
        }

        self::$categories = $categories;
    }
}