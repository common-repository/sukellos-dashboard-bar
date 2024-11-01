<?php

namespace Sukellos\Admin;

use Sukellos\WP_Sukellos_Dashboard_Bar_Loader;
use Sukellos\WPFw\Singleton;
use Sukellos\WPFw\AdminBuilder\Admin_Builder;
use Sukellos\WPFw\WP_Plugin_Admin;
use Sukellos\WPFw\Utils\WP_Log;
use Sukellos\WPFw\AdminBuilder\Item_Type;
use Sukellos\WPFw\Utils\WP_Helper;

defined( 'ABSPATH' ) or exit;

/**
 * Admin class.
 * Main admin is used as controller to init admin menu and all other admin pages
 *
 * @since 1.0.0
 */
class WP_Sukellos_Dashboard_Bar_Admin extends WP_Plugin_Admin {

    // Use Trait Singleton
    use Singleton;

    /**
     * Default init method called when instance created
     * This method can be overridden if needed.
     *
     * @since 1.0.0
     * @access protected
     */
    public function init() {

        parent::init();

        // Add action to delegate settings fields creation to Sukellos Fw Tools admin
        // Use priority to order Tools
        add_action( 'sukellos_fw/admin/create_tools_fields', array( $this, 'action_create_tools_fields' ), 8, 1 );


        WP_Log::info( 'WP_Sukellos_Dashboard_Bar_Admin->init OK!',[], WP_Sukellos_Dashboard_Bar_Loader::instance()->get_text_domain());
    }


    /**
     * Gets the plugin configuration URL
     * This is used to build actions list in plugins page
     * Leave blank ('') to disable
     *
     * @since 1.0.0
     *
     * @return string plugin settings URL
     */
    public function get_settings_url() {

        return admin_url( 'admin.php?page='.WP_Sukellos_Dashboard_Bar_Loader::instance()->get_options_suffix_param().'_tools' );
    }

    /**
     *          ===============
     *      =======================
     *  ============ HOOKS ===========
     *      =======================
     *          ===============
     */


    /***
     * Adding CSS and JS into header
     * Default add assets/admin.css and assets/admin.js
     */
    public function admin_enqueue_scripts() {}


    /***
     * Admin page
     * Settings managed by main Sukellos Fw Tools admin
     */
    public function create_items() {}

    /**
     * Tools fields creation
     */
    public function action_create_tools_fields( $admin_page ) {


        // Admin page is a Tabs page
        $admin_tab = $admin_page->create_tab(
            array(
                'id' => WP_Sukellos_Dashboard_Bar_Loader::instance()->get_options_suffix_param().'_dashboard_bar_tab',
                'name' => WP_Helper::sk__('Dashboard Bar' ),
                'desc' => '',
            )
        );

        // Create a header
        $admin_tab->create_header(
            array(
                'id' => WP_Sukellos_Dashboard_Bar_Loader::instance()->get_options_suffix_param().'_header_dashboard_bar',
                'name' => WP_Helper::sk__('Dashboard Bar' ),
                'desc' => WP_Helper::sk__( 'Hide the admin dashboard bar. Can be applied to certain profiles only.' ),
            )
        );

        // Get all roles
        global $wp_roles;
        $roles = $wp_roles->roles;

        // Build options
        $role_options = array();
        foreach ( $roles as $role_slug => $role ) {

            $role_options[ ''.$role_slug ] = $role[ 'name' ];
        }

        // Create an multicheck option field
        $admin_tab->create_option(
            array(
                'type' => Item_type::MULTICHECK,
                'id' => WP_Sukellos_Dashboard_Bar_Loader::instance()->get_options_suffix_param().'_multicheck_enable_dashboard_bar',
                'name' => WP_Helper::sk__('Enable dashboard bar' ),
                'desc' => WP_Helper::sk__('Enable to activate the dashboard bar' ),
                'options' => $role_options,
                'select_all' => __( 'Select all', 'text_domain' ),
            )
        );
    }
}