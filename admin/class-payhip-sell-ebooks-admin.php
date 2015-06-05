<?php
/**
 * Payhip - Sell ebooks direct.
 *
 * @package   Payhip_ebooks_Admin
 * @author    Payhip <contact@payhip.com>
 * @license   GPL-2.0+
 * @link      https://payhip.com
 * @copyright 2015 Payhip
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-payhip-sell-ebooks.php`
 *
 * @TODO: Rename this class to a proper name for your plugin.
 *
 * @package Payhip_ebooks_Admin
 * @author  Payhip <contact@payhip.com>
 */
class Payhip_ebooks_Admin {

    /**
     * Instance of this class.
     *
     * @since    1.0.0
     *
     * @var      object
     */
    protected static $instance = null;

    /**
     * Slug of the plugin screen.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $plugin_screen_hook_suffix = null;

    /**
     * Initialize the plugin by loading admin scripts & styles and adding a
     * settings page and menu.
     *
     * @since     1.0.0
     */
    private function __construct() {

        /*
         * @TODO :
         *
         * - Uncomment following lines if the admin class should only be available for super admins
         */
        /* if( ! is_super_admin() ) {
          return;
          } */

        /*
         * Call $plugin_slug from public plugin class.
         *
         * @TODO:
         *
         * - Rename "Payhip_ebooks" to the name of your initial plugin class
         *
         */
        $plugin = Payhip_ebooks::get_instance();
        $this->plugin_slug = $plugin->get_plugin_slug();

        // Load admin style sheet and JavaScript.
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

        // Add the options page and menu item.
        add_action('admin_menu', array($this, 'add_plugin_admin_menu'));

        // Add an action link pointing to the options page.
        $plugin_basename = plugin_basename(plugin_dir_path(realpath(dirname(__FILE__))) . $this->plugin_slug . '.php');
        add_filter('plugin_action_links_' . $plugin_basename, array($this, 'add_action_links'));

        /*
         * Define custom functionality.
         *
         * Read more about actions and filters:
         * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
         */
        add_action('@TODO', array($this, 'action_method_name'));
        add_filter('@TODO', array($this, 'filter_method_name'));

        // ajax call
        add_action('wp_ajax_pfPayhipData', array($this, 'pfPayhipData_callback'));
        add_action('wp_ajax_nopriv_pfPayhipData', array($this, 'pfPayhipData_callback'));
    }

    /**
     * Return an instance of this class.
     *
     * @since     1.0.0
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {

        /*
         * @TODO :
         *
         * - Uncomment following lines if the admin class should only be available for super admins
         */
        /* if( ! is_super_admin() ) {
          return;
          } */

        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Register and enqueue admin-specific style sheet.
     *
     * @TODO:
     *
     * - Rename "Payhip_ebooks" to the name your plugin
     *
     * @since     1.0.0
     *
     * @return    null    Return early if no settings page is registered.
     */
    public function enqueue_admin_styles() {

        if (!isset($this->plugin_screen_hook_suffix)) {
            return;
        }

        $screen = get_current_screen();
        if ($this->plugin_screen_hook_suffix == $screen->id) {
            wp_enqueue_style($this->plugin_slug . '-admin-styles', plugins_url('assets/css/admin.css', __FILE__), array(), Payhip_ebooks::VERSION);
        }
    }

    /**
     * Register and enqueue admin-specific JavaScript.
     *
     * @TODO:
     *
     * - Rename "Payhip_ebooks" to the name your plugin
     *
     * @since     1.0.0
     *
     * @return    null    Return early if no settings page is registered.
     */
    public function enqueue_admin_scripts() {

        if (!isset($this->plugin_screen_hook_suffix)) {
            return;
        }

        $screen = get_current_screen();
        if ($this->plugin_screen_hook_suffix == $screen->id) {
            wp_enqueue_script($this->plugin_slug . '-admin-script', plugins_url('assets/js/admin.js', __FILE__), array('jquery'), Payhip_ebooks::VERSION);
        }
        $pf_json_admin_data['ajaxURL'] = admin_url('admin-ajax.php');
        wp_localize_script($this->plugin_slug . '-admin-script', 'pf_json_admin_data', $pf_json_admin_data);
    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu() {

        /*
         * Add a settings page for this plugin to the Settings menu.
         *
         * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
         *
         *        Administration Menus: http://codex.wordpress.org/Administration_Menus
         *
         * @TODO:
         *
         * - Change 'Page Title' to the title of your plugin admin page
         * - Change 'Menu Text' to the text for menu item for the plugin settings page
         * - Change 'manage_options' to the capability you see fit
         *   For reference: http://codex.wordpress.org/Roles_and_Capabilities
         */
        $this->plugin_screen_hook_suffix = add_menu_page(
                __('Payhip: Sell ebooks', $this->plugin_slug), __('Payhip', $this->plugin_slug), 'manage_options', $this->plugin_slug, array($this, 'display_plugin_admin_page'), '', 102
        );
    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */
    public function display_plugin_admin_page() {
        include_once( 'views/admin.php' );
    }

    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */
    public function add_action_links($links) {

        return array_merge(
                        array(
                    'settings' => '<a href="' . admin_url('options-general.php?page=' . $this->plugin_slug) . '">' . __('Settings', $this->plugin_slug) . '</a>'
                        ), $links
        );
    }

    /**
     * NOTE:     Actions are points in the execution of a page or process
     *           lifecycle that WordPress fires.
     *
     *           Actions:    http://codex.wordpress.org/Plugin_API#Actions
     *           Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
     *
     * @since    1.0.0
     */
    public function action_method_name() {
        // @TODO: Define your action hook callback here
    }

    /**
     * NOTE:     Filters are points of execution in which WordPress modifies data
     *           before saving it or sending it to the browser.
     *
     *           Filters: http://codex.wordpress.org/Plugin_API#Filters
     *           Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
     *
     * @since    1.0.0
     */
    public function filter_method_name() {
        // @TODO: Define your filter hook callback here
    }

    /*
     * allowed types : text, textarea, select, heading, gap
     * total array keys
     * label
     * name = ame attribute value
     * type
     * req = y if required
     * desc = if description present
     * value = if value present
     * options = if type select choosed
     */

    public function pfGenerateFormLayout($pfformDispData) {
        if (is_array($pfformDispData) && count($pfformDispData) > 0) {
            ob_start();
            foreach ($pfformDispData as $val) {
                if ($val['req'] == 'y') {
                    $pfFormClass = ' pfFormReq';
                } else {
                    $pfFormClass = '';
                }

                // js call back
                $pfDataCallback = null;
                if (isset($val['jsCall'])) {
                    if (isset($_POST['pf_payhip_username'])) {
                        $pfDataCallback = $this->pfPayhipData_callback($val['value']);
                    }
                }

                if ($val['type'] == 'gap') {
                    echo '<div class="pfClear-10"></div>';
                } elseif ($val['type'] == 'heading' && $val['tag'] != '') {
                    echo '<' . $val['tag'] . '>' . $val['label'] . '</' . $val['tag'] . '>';
                } elseif ($val['type'] == 'html') {
                    echo $val['label'];
                } else {
                    ?>
                    <div class="form-field">
                        <?php
                        // label
                        echo $val['label'] ? '<label>' . $val['label'] . '</label>' : '';

                        // input type text
                        if ($val['type'] == 'text') {
                            ?>
                            <input class="<?php echo $pfFormClass ?>" name="<?php echo $val['name']; ?>" id="<?php echo $val['name']; ?>" type="text" value="<?php echo $val['value']; ?>" />
                            <?php
                        } elseif ($val['type'] == 'select') {
                            ?>
                            <select class="<?php echo $pfFormClass ?>" name="<?php echo $val['name']; ?>" id="<?php echo $val['name']; ?>">
                                <?php
                                if (is_array($val['options']) && count($val['options']) > 0) {
                                    foreach ($val['options'] as $singkey => $singval) {
                                        $selected = '';
                                        if ($singkey == $val['value']) {
                                            $selected = ' selected';
                                        }
                                        echo '<option value="' . $singkey . '" ' . $selected . '>' . $singval . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <?php
                        } elseif ($val['type'] == 'textarea') {
                            ?>
                            <textarea class="<?php echo $pfFormClass ?>" name="<?php echo $val['name']; ?>" id="<?php echo $val['name']; ?>"><?php echo $val['value']; ?></textarea>
                            <?php
                        }

                        // error area
                        echo $pfDataCallback ? $pfDataCallback : '';

                        // description
                        echo $val['desc'] ? '<p>' . $val['desc'] . '</p>' : '';
                        ?>
                    </div>
                    <?php
                }
            }
            $retDisplayHtml = ob_get_clean();
            return $retDisplayHtml;
        }
    }

    public function pfPayhipData_callback($usernameVal) {
        if ($usernameVal != '') {
            $pf_json_path = PF_USER_JSON_URL . $usernameVal;
            $pf_json_file_cont = file_get_contents($pf_json_path);
            if ($pf_json_file_cont) {
                // update payhip json response data on option key pf_json_resp
                update_option('pf_json_resp', $pf_json_file_cont);

                $pfContCurl = json_decode($pf_json_file_cont);
                $et_res['status'] = 'failure';
                $et_res['count'] = '0';
                if ($pfContCurl->status == 'success') {
                    $et_res['status'] = 'success';
                    if ($pfContCurl->result->total != '') {
                        $et_res['count'] = $pfContCurl->result->total;
                    }
                }
            }
            if (isset($et_res['count']) && isset($et_res['status'])) {
                if ($et_res['status'] == 'failure') {
                    return '<div class="error"><p>' . PF_BACK_WRONG_USERNAME . '</p></div>';
                } elseif ($et_res['status'] == 'success' && $et_res['count'] == '0') {
                    return '<div class="error form-invalid"><p>' . PF_BACK_OK_USERNAME_0_PROD . '</p></div>';
                } else {
                    return '<div class="updated"><p>' . str_replace('{item_count}', $et_res['count'], PF_BACK_OK_USERNAME) . '</p></div>';
                    return false;
                }
            }
        }
    }

}

