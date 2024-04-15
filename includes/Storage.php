<?php

namespace XenioCookies;

use XenioCookies\Interfaces\StorageInterface;

class Storage implements StorageInterface
{
    private $categories;

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
        if (empty($this->categories)) {
            $this->fetchCategories();
        }
        return $this->categories;
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
            necessary,
            JSON_UNQUOTE(JSON_EXTRACT(name, '$.{$locale}')) as name,
            JSON_UNQUOTE(JSON_EXTRACT(description, '$.{$locale}')) as description,
            contest_types
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

        $this->categories = $categories;
    }
}