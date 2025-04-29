<?php

namespace XenioCookies;

use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

class RestAPI
{
    private $routes;

    private $defaultLanguage;

    public function __construct()
    {
        $this->define_routes();

        $this->defaultLanguage = get_locale();
        //$this->defaultLanguage = get_option('xcm_default_language') ?? get_locale();

        add_action( 'rest_api_init', array( $this, 'register_v1_routes' ) );
    }


    /**
     * Defines REST Routes.
     *
     * @return void
     */
    public function define_routes()
    {
        $this->routes = array(
            array(
                'path'      => 'categories',
                'method'   => WP_REST_Server::READABLE,
                'callback' => 'get_categories',
            ),
            array(
                'path'      => 'categories/(?P<id>\d+)',
                'method'   => WP_REST_Server::READABLE,
                'callback' => 'get_category',
            ),
            array(
                'path'      => 'categories',
                'method'   => WP_REST_Server::CREATABLE,
                'callback' => 'add_category',
                'args' => array(
                    'name' => array(
                        'required' => true,
                        'validate_callback' => array($this, 'validate_name')
                    ),
                )
            ),
            array(
                'path'      => 'categories/(?P<id>\d+)',
                'method'    => WP_REST_Server::EDITABLE,
                'callback'  => 'save_category',
                'args' => array(
                    'name' => array(
                        'required' => true,
                        'validate_callback' => array($this, 'validate_name')
                    ),
                ),
            ),
            array(
                'path'      => 'categories/(?P<id>\d+)',
                'method'    => WP_REST_Server::DELETABLE,
                'callback'  => 'delete_category',
            ),
            array(
                'path'      => 'vendors',
                'method'    => WP_REST_Server::READABLE,
                'callback'  => 'get_vendors',
            ),
            array(
                'path'      => 'vendors/(?P<id>\d+)',
                'method'   => WP_REST_Server::READABLE,
                'callback' => 'get_vendor',
            ),
            array(
                'path'      => 'vendors',
                'method'   => WP_REST_Server::CREATABLE,
                'callback' => 'add_vendor',
                'args' => array(
                    'category_id' => array(
                        'required' => true,
                        'validate_callback' => array($this, 'validate_numeric'),
                    ),
                    'name' => array(
                        'required' => true,
                        'validate_callback' => array($this, 'validate_name'),
                    ),
                )
            ),
            array(
                'path'      => 'vendors/(?P<id>\d+)',
                'method'   => WP_REST_Server::EDITABLE,
                'callback' => 'save_vendor',
                'args' => array(
                    'category_id' => array(
                        'required' => true,
                        'validate_callback' => array($this, 'validate_numeric'),
                    ),
                    'name' => array(
                        'required' => true,
                        'validate_callback' => array($this, 'validate_name')
                    ),
                )
            ),
            array(
                'path'      => 'vendors/(?P<id>\d+)',
                'method'   => WP_REST_Server::DELETABLE,
                'callback' => 'delete_vendor',
            ),
            array(
                'path'      => 'languages',
                'method'   => WP_REST_Server::READABLE,
                'callback' => 'get_languages',
            ),
            array(
                'path'      => 'settings',
                'method'   => WP_REST_Server::READABLE,
                'callback' => 'get_settings',
            ),
            array(
                'path'      => 'settings',
                'method'   => WP_REST_Server::EDITABLE,
                'callback' => 'save_settings',
            ),
            array(
                'path'      => 'customization',
                'method'   => WP_REST_Server::READABLE,
                'callback' => 'get_customization',
            ),
            array(
                'path'      => 'customization',
                'method'   => WP_REST_Server::EDITABLE,
                'callback' => 'save_customization',
            ),
        );
    }

    /**
     * Registers v1 REST routes.
     *
     * @return void
     */
    public function register_v1_routes()
    {
        foreach ( $this->routes as $route ) {
            register_rest_route(
                XCM_NAME . "/v1",
                "/{$route['path']}",
                array(
                    'methods'             => $route['method'],
                    'callback'            => array( $this, $route['callback'] ),
                    'permission_callback' => array( $this, 'rest_permission_check' ),
                    'args'                => $route['args'] ?? array(),
                )
            );
        }
    }

    public function rest_permission_check()
    {
        return current_user_can( 'manage_options' );
        //return true;
    }

    public function get_categories( WP_REST_Request $request )
    {
        global $wpdb;

        $params = $request->get_params();

        $locale = $params['locale'];

        $table_name = $wpdb->prefix . XCM_NAME . '_categories';

        $table_name_vendors = $wpdb->prefix . XCM_NAME . '_vendors';

        $entries = $wpdb->get_results("
            SELECT 
                c.id,
                JSON_UNQUOTE(JSON_EXTRACT(c.name, '$.{$locale}')) AS name,
                JSON_UNQUOTE(JSON_EXTRACT(c.name, '$.{$this->defaultLanguage}')) AS name_default,
                COUNT(v.category_id) AS vendors_count
            FROM {$table_name} AS c
            LEFT JOIN $table_name_vendors AS v ON c.id = v.category_id
            GROUP BY c.id");

        return new WP_REST_Response( $entries, 200 );
    }

    public function get_category(WP_REST_Request $request)
    {
        $params = $request->get_params();

        $locale = $params['locale'];

        global $wpdb;

        $table_name = $wpdb->prefix . XCM_NAME . '_categories';

        $entry = $wpdb->get_results("
            SELECT 
                id,
                necessary,
                JSON_UNQUOTE(JSON_EXTRACT(name, '$.{$locale}')) as name,
                JSON_UNQUOTE(JSON_EXTRACT(description, '$.{$locale}')) as description,
                consent_types
            FROM {$table_name}
            WHERE id = {$params['id']}");

        if ($entry) {
            return new WP_REST_Response( $entry[0], 200 );
        }

        return new WP_Error(404);
    }

    public function save_category(WP_REST_Request $request)
    {
        $params = $request->get_params();

        $locale = $params['locale'];

        global $wpdb;

        $table_name = $wpdb->prefix . XCM_NAME . '_categories';

        $necessary = $params['necessary'] === 'on' ? 1 : 0;

        $wpdb->query($wpdb->prepare("
            UPDATE {$table_name}
            SET
                necessary = {$necessary},
                name = JSON_SET(name, '$.{$locale}', '{$params['name']}'),
                description = JSON_SET(description, '$.{$locale}', '{$params['description']}'),
                consent_types = \"{$params['consent_types']}\"
            WHERE id = {$params['id']}"));


        return new WP_REST_Response( array(), 200 );
    }

    public function delete_category(WP_REST_Request $request)
    {
        $params = $request->get_params();

        global $wpdb;

        $table_name_vendors = $wpdb->prefix . XCM_NAME . '_vendors';

        $wpdb->delete($table_name_vendors, array(
            'category_id' =>  $params['id']
        ));

        $table_name = $wpdb->prefix . XCM_NAME . '_categories';

        $result = $wpdb->delete($table_name, array(
            'id' =>  $params['id']
        ));

        if ($result) {
            return new WP_REST_Response( array(), 200 );
        }

        return new WP_Error( 404, 'Could not find this entry.' );
    }

    public function add_category(WP_REST_Request $request)
    {
        $params = $request->get_params();

        $languages = array_merge(['en_US'], get_available_languages());

        global $wpdb;

        $table_name = $wpdb->prefix . XCM_NAME . '_categories';

        $nameArray = [];

        foreach ($languages as $locale) {
            $nameArray[$locale] = $params['name'];
        }

        $wpdb->insert(
            $table_name,
            array(
                'name' => json_encode($nameArray),
            )
        );

        return new WP_REST_Response( array(
            'id' => $wpdb->insert_id,
        ), 200 );
    }

    public function get_vendors(WP_REST_Request $request)
    {
        $params = $request->get_params();

        global $wpdb;

        $table_name = $wpdb->prefix . XCM_NAME . '_vendors';

        $entries = $wpdb->get_results("
            SELECT 
                id,
                name,
                JSON_LENGTH(cookies) as cookies_count
            FROM {$table_name}
            WHERE category_id = {$params['category']}");

        return new WP_REST_Response( $entries, 200 );
    }

    public function get_vendor(WP_REST_Request $request)
    {
        $params = $request->get_params();

        $locale = $params['locale'];

        global $wpdb;

        $table_name = $wpdb->prefix . XCM_NAME . '_vendors';

        $entry = $wpdb->get_results("
            SELECT 
                id,
                name,
                link,
                provider,
                cookies,
                JSON_UNQUOTE(JSON_EXTRACT(description, '$.{$locale}')) as description
            FROM {$table_name}
            WHERE id = {$params['id']}");

        if ($entry) {
            $entry[0]->cookies = json_decode($entry[0]->cookies);
            return new WP_REST_Response( $entry[0], 200 );
        }

        return new WP_Error(404);
    }
    public function add_vendor(WP_REST_Request $request)
    {
        $params = $request->get_params();

        global $wpdb;

        $table_name = $wpdb->prefix . XCM_NAME . '_vendors';

        $wpdb->insert(
            $table_name,
            array(
                'name' => $params['name'],
                'category_id' => $params['category_id']
            )
        );

        return new WP_REST_Response( array(
            'id' => $wpdb->insert_id,
        ), 200 );
    }

    public function save_vendor(WP_REST_Request $request)
    {
        $params = $request->get_params();

        global $wpdb;

        $table_name = $wpdb->prefix . XCM_NAME . '_vendors';

        $locale = $params['locale'];

        $wpdb->query($wpdb->prepare("
            UPDATE {$table_name}
            SET
                name = '{$params['name']}',
                description = JSON_SET(IFNULL(description,'{}' ), '$.{$locale}', '{$params['description']}'),
                link = '{$params['link']}',
                provider = '{$params['provider']}',
                cookies = '{$params['cookies']}'
            WHERE id = {$params['id']}"));

        return new WP_REST_Response( array(), 200 );
    }

    public function delete_vendor(WP_REST_Request $request)
    {
        $params = $request->get_params();

        global $wpdb;

        $table_name = $wpdb->prefix . XCM_NAME . '_vendors';

        $result = $wpdb->delete($table_name, array(
           'id' =>  $params['id']
        ));

        if ($result) {
            return new WP_REST_Response( array(), 200 );
        }

        return new WP_Error( 404, 'Could not find this entry.' );
    }

    public function get_languages()
    {
//        $list = array_merge(['en'], get_available_languages());
//
//        foreach ($list as &$item) {
//            $item = preg_replace('/_\w*/', '', $item);
//        }
//
//        $list = array_unique($list);

//        $userLocale = preg_replace('/_\w*/', '', get_locale());

        return new WP_REST_Response( array(
            'current' => get_locale(),
            'userLocale' => get_locale(),
            'list' => array_merge(['en_US'], get_available_languages()),
        ), 200 );
    }

    public function get_settings()
    {
        return new WP_REST_Response( array(
            'is_active' => get_option( XCM_NAME . "_is_active") === '1',
//            'languages' => array(
//                'default' => $this->defaultLanguage,
//                'list' => get_available_languages(),
//            ),
        ), 200 );
    }

    public function save_settings(WP_REST_Request $request)
    {
        $params = $request->get_params();
        update_option( XCM_NAME . "_is_active", $params['is_active'], false );
        return new WP_REST_Response( array(), 200 );
    }

    public function get_customization(WP_REST_Request $request)
    {
        $params = $request->get_params();
        $locale = $params['locale'];

        return new WP_REST_Response( array(
            'overview_title' => get_option("xcm_{$locale}_overview_title") ?: "",
            'overview_description' => get_option("xcm_{$locale}_overview_description") ?: "",
        ), 200 );
    }

    public function save_customization(WP_REST_Request $request)
    {
        $params = $request->get_params();
        $locale = $params['locale'];
        update_option( "xcm_{$locale}_overview_title", $params['overview_title'], false );
        update_option( "xcm_{$locale}_overview_description", $params['overview_description'], false );
        return new WP_REST_Response( array(), 200 );
    }

    public function validate_name($param)
    {
        if (strlen( $param ) <= 2) {
            return new WP_Error( 'rest_invalid_param', 'Name is too short.', array( 'status' => 400 ) );
        }

        return true;
    }

    public function validate_numeric($param)
    {
        return is_numeric( $param );
    }
}