<?php

/**
 * Payhip - Sell ebooks direct.
 *
 *
 * @package   Payhip_ebooks_Admin
 * @author    Payhip <contact@payhip.com>
 * @license   GPL-2.0+
 * @link      https://payhip.com
 * @copyright 2015 Payhip
 *
 * @wordpress-plugin
 * Plugin Name:       Payhip - Sell your ebooks
 * Plugin URI:        https://payhip.com/
 * Description:       Sell your ebooks direct to your fans and followers
 * Version:           1.0.0
 * Author:            Payhip
 * Author URI:        https://payhip.com/
 * Text Domain:       payhip-sell-ebooks-locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/payhip/wordpress
 * WordPress-Plugin-Boilerplate: v2.6.1
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/* ----------------------------------------------------------------------------*
 * Public-Facing Functionality
 * ---------------------------------------------------------------------------- */

/*
 * @TODO:
 *
 * - replace `class-payhip-sell-ebooks.php` with the name of the plugin's class file
 *
 */
require_once( plugin_dir_path(__FILE__) . 'public/class-payhip-sell-ebooks.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 * @TODO:
 *
 * - replace Payhip_ebooks with the name of the class defined in
 *   `class-payhip-sell-ebooks.php`
 */
register_activation_hook(__FILE__, array('Payhip_ebooks', 'activate'));
register_deactivation_hook(__FILE__, array('Payhip_ebooks', 'deactivate'));

/*
 * @TODO:
 *
 * - replace Payhip_ebooks with the name of the class defined in
 *   `class-payhip-sell-ebooks.php`
 */
add_action('plugins_loaded', array('Payhip_ebooks', 'get_instance'));

/* ----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 * ---------------------------------------------------------------------------- */

/*
 * @TODO:
 *
 * - replace `class-payhip-sell-ebooks-admin.php` with the name of the plugin's admin file
 * - replace Payhip_ebooks_Admin with the name of the class defined in
 *   `class-payhip-sell-ebooks-admin.php`
 *
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 * othewise && if ( is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX )
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if (is_admin()) {
    require_once( plugin_dir_path(__FILE__) . 'admin/class-payhip-sell-ebooks-admin.php' );
    add_action('plugins_loaded', array('Payhip_ebooks_Admin', 'get_instance'));
}

// plugin serve path
define("PF_PLUGIN_DIR", plugin_dir_path(__FILE__));
define("PF_PLUGIN_URL", plugins_url('', __FILE__));

// plugins constants
require_once( plugin_dir_path(__FILE__) . 'includes/constants.php' );