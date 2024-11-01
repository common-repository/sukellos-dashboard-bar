<?php

namespace Sukellos;

defined( 'ABSPATH' ) or exit;

use Sukellos\WPFw\AdminBuilder\Admin_Builder;
use Sukellos\WPFw\AdminBuilder\Item_Type;
use Sukellos\WPFw\Singleton;
use Sukellos\WPFw\Utils\WP_Log;

/**
 *  Dashboard Bar aager is responsible for enabling / disabling admin dashboard bar
 *
 * @since 1.0.0
 */
class Dashboard_Bar_Manager {


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

        add_action( 'init', array( $this, 'action_init'), 10, 0 );

    }

    /**
     *          ===============
     *      =======================
     *  ============ HOOKS ===========
     *      =======================
     *          ===============
     */

    /**
     * Init action hook
     */
    public function action_init() {

        // Show admin bar default false
        $show_admin_bar = false;
        if ( !is_user_logged_in() ) {

            return;
        }

        // Check if admin bar should be activated
        $activate_admin_bar_roles = Admin_Builder::get_option( WP_Sukellos_Dashboard_Bar_Loader::instance()->get_options_suffix_param().'_multicheck_enable_dashboard_bar', Item_Type::MULTICHECK );
        WP_Log::debug( __METHOD__, ['$activate_admin_bar_roles'=>$activate_admin_bar_roles] );

        if ( !$activate_admin_bar_roles || !is_array( $activate_admin_bar_roles ) ) return;

        // Get current user roles
        $user = wp_get_current_user();
        $roles = ( array ) $user->roles;
        WP_Log::debug( 'Dashboard_Bar_Manager->init', ['$roles'=>$roles] );

        foreach ( $activate_admin_bar_roles as $activate_admin_bar_role ) {

            if ( in_array( $activate_admin_bar_role, $roles ) ) {

                $show_admin_bar = true;
                break;
            }
        }

        if ( !$show_admin_bar ) {

            add_action( 'admin_print_scripts-profile.php', array( $this, 'action_admin_print_scripts_profile') );
            add_filter( 'show_admin_bar', '__return_false' );
        }
    }

    /**
     * Change CSS style to hide admin bar
     */
    public function action_admin_print_scripts_profile() {
        ?>
        <style type="text/css">
            .show-admin-bar {
                display: none;
            }
        </style>
        <?php
    }

}
