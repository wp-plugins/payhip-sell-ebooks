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
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `class-payhip-sell-ebooks-admin.php`
 *
 * @TODO: Rename this class to a proper name for your plugin.
 *
 * @package Payhip_ebooks
 * @author  Payhip <contact@payhip.com>
 */
class Payhip_ebooks {
    /**
     * Plugin version, used for cache-busting of style and script file references.
     *
     * @since   1.0.0
     *
     * @var     string
     */

    const VERSION = '1.0.0';

    /**
     * @TODO - Rename "payhip-sell-ebooks" to the name your your plugin
     *
     * Unique identifier for your plugin.
     *
     *
     * The variable name is used as the text domain when internationalizing strings
     * of text. Its value should match the Text Domain file header in the main
     * plugin file.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $plugin_slug = 'payhip-sell-ebooks';

    /**
     * Instance of this class.
     *
     * @since    1.0.0
     *
     * @var      object
     */
    protected static $instance = null;

    /**
     * Initialize the plugin by setting localization and loading public scripts
     * and styles.
     *
     * @since     1.0.0
     */
    private function __construct() {

        // Load plugin text domain
        add_action('init', array($this, 'load_plugin_textdomain'));

        // Activate plugin when new blog is added
        add_action('wpmu_new_blog', array($this, 'activate_new_site'));

        // Load public-facing style sheet and JavaScript.
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

        /* Define custom functionality.
         * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
         */
        add_action('init', array($this, 'wp_action_hook'));
        add_filter('@TODO', array($this, 'filter_method_name'));

        // payhip custom
        add_shortcode('Payhip_Products', array($this, 'Payhip_Products_shortcode'));
    }

    /**
     * Return the plugin slug.
     *
     * @since    1.0.0
     *
     * @return    Plugin slug variable.
     */
    public function get_plugin_slug() {
        return $this->plugin_slug;
    }

    /**
     * Return an instance of this class.
     *
     * @since     1.0.0
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Fired when the plugin is activated.
     *
     * @since    1.0.0
     *
     * @param    boolean    $network_wide    True if WPMU superadmin uses
     *                                       "Network Activate" action, false if
     *                                       WPMU is disabled or plugin is
     *                                       activated on an individual blog.
     */
    public static function activate($network_wide) {

        if (function_exists('is_multisite') && is_multisite()) {

            if ($network_wide) {

                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ($blog_ids as $blog_id) {

                    switch_to_blog($blog_id);
                    self::single_activate();
                }

                restore_current_blog();
            } else {
                self::single_activate();
            }
        } else {
            self::single_activate();
        }
    }

    /**
     * Fired when the plugin is deactivated.
     *
     * @since    1.0.0
     *
     * @param    boolean    $network_wide    True if WPMU superadmin uses
     *                                       "Network Deactivate" action, false if
     *                                       WPMU is disabled or plugin is
     *                                       deactivated on an individual blog.
     */
    public static function deactivate($network_wide) {

        if (function_exists('is_multisite') && is_multisite()) {

            if ($network_wide) {

                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ($blog_ids as $blog_id) {

                    switch_to_blog($blog_id);
                    self::single_deactivate();
                }

                restore_current_blog();
            } else {
                self::single_deactivate();
            }
        } else {
            self::single_deactivate();
        }
    }

    /**
     * Fired when a new site is activated with a WPMU environment.
     *
     * @since    1.0.0
     *
     * @param    int    $blog_id    ID of the new blog.
     */
    public function activate_new_site($blog_id) {

        if (1 !== did_action('wpmu_new_blog')) {
            return;
        }

        switch_to_blog($blog_id);
        self::single_activate();
        restore_current_blog();
    }

    /**
     * Get all blog ids of blogs in the current network that are:
     * - not archived
     * - not spam
     * - not deleted
     *
     * @since    1.0.0
     *
     * @return   array|false    The blog ids, false if no matches.
     */
    private static function get_blog_ids() {

        global $wpdb;

        // get an array of blog ids
        $sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

        return $wpdb->get_col($sql);
    }

    /**
     * Fired for each blog when the plugin is activated.
     *
     * @since    1.0.0
     */
    private static function single_activate() {
        // @TODO: Define activation functionality here
    }

    /**
     * Fired for each blog when the plugin is deactivated.
     *
     * @since    1.0.0
     */
    private static function single_deactivate() {
        // @TODO: Define deactivation functionality here
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain() {

        $domain = $this->plugin_slug;
        $locale = apply_filters('plugin_locale', get_locale(), $domain);

        load_textdomain($domain, trailingslashit(WP_LANG_DIR) . $domain . '/' . $domain . '-' . $locale . '.mo');
        load_plugin_textdomain($domain, FALSE, basename(plugin_dir_path(dirname(__FILE__))) . '/languages/');
    }

    /**
     * Register and enqueue public-facing style sheet.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_slug . '-plugin-styles', plugins_url('assets/css/public.css', __FILE__), array(), self::VERSION);
    }

    /**
     * Register and enqueues public-facing JavaScript files.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script('jquery');
        wp_enqueue_script($this->plugin_slug . '-plugin-script', plugins_url('assets/js/public.js', __FILE__), array('jquery'), self::VERSION);

        // check if masonry handle registered or not
        if (wp_script_is('masonry', 'registered')) {
            wp_enqueue_script('masonry');
        } else {
            wp_enqueue_script('masonry', plugins_url('assets/js/masonry.min.js', __FILE__), array(), '3.1.4', true);
        }
        // payhip masonry js enqueue
        wp_enqueue_script('masonry-init', plugins_url('assets/js/pf-masonry-init.js', __FILE__), array('masonry'), null, true);

        $pf_json_admin_data = array();
        $pf_json_admin_data['ajaxURL'] = admin_url('admin-ajax.php');
        wp_localize_script('masonry', 'pf_json_admin_data', $pf_json_admin_data);
    }

    /**
     * NOTE:  Actions are points in the execution of a page or process
     *        lifecycle that WordPress fires.
     *
     *        Actions:    http://codex.wordpress.org/Plugin_API#Actions
     *        Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
     *
     * @since    1.0.0
     */
    public function wp_action_hook() {
        global $ps_json_file_data;

        $pfJsresponse = get_option('pf_json_resp');
        if ($pfJsresponse != '') {
            $ps_json_file_data = json_decode($pfJsresponse);
        }
    }

    /**
     * NOTE:  Filters are points of execution in which WordPress modifies data
     *        before saving it or sending it to the browser.
     *
     *        Filters: http://codex.wordpress.org/Plugin_API#Filters
     *        Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
     *
     * @since    1.0.0
     */
    public function filter_method_name() {
        // @TODO: Define your filter hook callback here
    }

    public function pf_get_pagination_html($max_pages, $curPage, $showCount) {

        global $wp_query;

        /** Stop execution if there's only 1 page */
        if ($max_pages <= 1)
            return;

        $paged = $curPage ? absint($curPage) : 1;
        $max = intval($max_pages);

        /** 	Add current page to the array */
        if ($paged >= 1)
            $links[] = $paged;

        /** 	Add the pages around the current page to the array */
        if ($paged >= 3) {
            $links[] = $paged - 1;
            $links[] = $paged - 2;
        }

        if (( $paged + 2 ) <= $max) {
            $links[] = $paged + 2;
            $links[] = $paged + 1;
        }

        ob_start();
        echo '<div class="navigation"><ul data-pages="' . $max_pages . '" data-count="' . $showCount . '">' . "\n";

        /** 	Previous Post Link */
        if ($curPage > 1)
            printf('<li><a href="%s" class="filterResult" data-page="%s">«</a></li>' . "\n", esc_url(get_pagenum_link($curPage - 1)), ($curPage - 1));


        /** 	Link to first page, plus ellipses if necessary */
        if (!in_array(1, $links)) {
            $class = 1 == $paged ? ' class="active"' : '';

            printf('<li%s><a href="%s" data-page="%s" class="filterResult">%s</a></li>' . "\n", $class, esc_url(get_pagenum_link(1)), '1', '1');

            if (!in_array(2, $links))
                echo '<li>…</li>';
        }

        /** 	Link to current page, plus 2 pages in either direction if necessary */
        sort($links);
        foreach ((array) $links as $link) {
            $class = $paged == $link ? ' class="active"' : '';
            printf('<li%s><a href="%s" data-page="%s" class="filterResult">%s</a></li>' . "\n", $class, esc_url(get_pagenum_link($link)), $link, $link);
        }

        /** 	Link to last page, plus ellipses if necessary */
        if (!in_array($max, $links)) {
            if (!in_array($max - 1, $links))
                echo '<li>…</li>' . "\n";

            $class = $paged == $max ? ' class="active"' : '';
            printf('<li%s><a href="%s" data-page="%s" class="filterResult">%s</a></li>' . "\n", $class, esc_url(get_pagenum_link($max)), $max, $max);
        }

        /** 	Next Post Link */
        if ($curPage != $max_pages)
            printf('<li><a href="%s" class="filterResult" data-page="%s">»</a></li>' . "\n", esc_url(get_pagenum_link($curPage + 1)), ($curPage + 1));

        echo '</ul></div>' . "\n";
        $pagination_html = ob_get_clean();
        return $pagination_html;
    }

    //[Payhip_Products] shortcode with attribute number
    public function Payhip_Products_shortcode($atts) {
        $atts = shortcode_atts(array(
            'items' => 'all'
                ), $atts, 'Payhip_Products');

        if ($atts['items'] == 'all') {
            $pfPaging = false;
        } else {
            $pfPaging = true;
            $pfCount = $atts['items'];
        }

        // json file data global
        global $ps_json_file_data;
        if ($ps_json_file_data) {
            if ($ps_json_file_data->status == 'failure') {
                echo '<h4>' . PF_FRONT_WRONG_USERNAME . '</h4>';
            } elseif (is_array($ps_json_file_data->result->items) && count($ps_json_file_data->result->items) > 0) {
                $pf_item_count = count($ps_json_file_data->result->items);
                ?>
                <div class="pf_loadGif"></div>
                <ul class="pf_prod_disp" id="pf_mason_grid">
                    <?php
                    if ($pfPaging == true && $pfCount > 0 && is_numeric($pfCount)) {
                        $u = 1;
                    }
                    $pagedVal = (get_query_var('paged')) ? get_query_var('paged') : 1;
                    //echo 'paged val : ' . $pagedVal . ' & show count : ' . $pfCount;
                    if ($pagedVal == 1) {
                        $m = 0;
                    } else {
                        $m = (($pagedVal - 1) * $pfCount);
                    }
                    //echo '<br/>'.$m.' , '.($m+1).' , '.($m+2).' , '.($m+3);
                    for ($h = $m; $h < $pf_item_count; $h++) {
                        $pf_prod_link = $ps_json_file_data->result->items[$h]->link;
                        $pf_affiliate_id = get_option('pf_affiliate_id');
                        if ($pf_affiliate_id) {
                            $pf_prod_link = rtrim($pf_prod_link, "/") . '/' . $pf_affiliate_id;
                        }
                        // name
                        $pf_prod_name = $ps_json_file_data->result->items[$h]->name;
                        // image
                        $pf_prod_img = $ps_json_file_data->result->items[$h]->image;
                        // price
                        $pf_prod_price = $ps_json_file_data->result->items[$h]->currency_html . $ps_json_file_data->result->items[$h]->price;
                        if (strpos($pf_prod_img, 'timthumb') == FALSE) {
                            $pf_prod_img = PF_TIMTHUMB_WEB_PATH . '?w=' . PF_TIMTHUMB_IMAGE_WIDTH . '&src=' . $pf_prod_img;
                        }
                        ?>
                        <li class="pf_prod_box" style="opacity: 0;">
                            <div class="pfImg"><a href="<?php echo $pf_prod_link; ?>"><img src="<?php echo $pf_prod_img; ?>" /></a></div>
                            <div class="pfTit"><a href="<?php echo $pf_prod_link; ?>"><?php echo $pf_prod_name; ?></a></div>
                            <div class="pfPrice"><?php echo $pf_prod_price; ?></div>
                        </li>
                        <?php
                        $pf_prod_link = null;
                        $pf_prod_name = null;
                        $pf_prod_img = null;
                        $pf_prod_price = null;
                        if ($pfPaging == true && $pfCount > 0 && is_numeric($pfCount)) {
                            if ($u == $pfCount) {
                                break;
                            }
                            $u++;
                        }
                    }
                    ?>
                </ul>
                <?php
                if ($pfPaging == true && $pfCount > 0 && is_numeric($pfCount)) {
                    echo '<div class="pfPagination">';
                    $pfMaxPages = ceil($pf_item_count / $pfCount);
                    echo $this->pf_get_pagination_html($pfMaxPages, $pagedVal, $pfCount);
                    echo '</div>';
                }
            } else {
                echo '<h4>' . PF_FRONT_OK_USERNAME_0_PROD . '</h4>';
            }
        }
    }

}