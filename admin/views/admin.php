<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Payhip_ebooks_Admin
 * @author    Payhip <contact@payhip.com>
 * @license   GPL-2.0+
 * @link      https://payhip.com
 * @copyright 2015 Payhip
 */
?>

<div class="wrap">

    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
    <div class="pfClear-20"></div>
    <div class="pfWrap form-wrap">
        <div class="postbox pfLeft">
            <div class="inside">
                <div class="pfDisp">
                    <?php
                    // form elements array
                    $pfSettingsArray = array(
                        array(
                            'label' => 'Payhip Account Information',
                            'type' => 'heading',
                            'tag' => 'h2'
                        ),
                        array(
                            'type' => 'gap'
                        ),
                        array(
                            'label' => '<p>
                                            <a target="_blank" href="' . PF_PAYHIP_CREATE_ACT_URL . '" class="button-primary">If you do not have a payhip account, create one.</a>
                                        </p>',
                            'type' => 'html'
                        ),
                        array(
                            'type' => 'gap'
                        ),
                        array(
                            'label' => 'Payhip Account Username',
                            'name' => 'pf_payhip_username',
                            'type' => 'text',
                            'req' => 'y',
                            'value' => '',
                            'desc' => '<em>If your profile URL is <a href="https://payhip.com/wordpress" target="_blank">payhip.com/wordpress</a> then your username will be <strong>wordpress</strong>.</em>',
                            'jsCall' => 'pfValid_payhip_user'
                        ),
                        array(
                            'label' => 'Payhip Affiliate ID',
                            'name' => 'pf_affiliate_id',
                            'type' => 'text',
                            'value' => '',
                            'desc' => '<em>If you have an affilite ID, then paste it above. Example: af54t649ofn8</em>'
                        )
                    );
                    if (is_array($pfSettingsArray) && count($pfSettingsArray) > 0) {
                        foreach ($pfSettingsArray as $pfSetKey => $pfSetVal) {
                            if ($pfSetVal['name'] != '') {
                                $pfEachOptionVal = '';
                                $pfEachOptionVal = get_option($pfSetVal['name']);
                                $pfSettingsArray[$pfSetKey]['value'] = $pfEachOptionVal;
                            }
                        }
                    }

                    if ($_POST['pfFormSubmit'] == 'yes') {
                        if (!wp_verify_nonce($_POST['wpnonce_pfSettings'], 'pfSettings')) {
                            die('Security check');
                        } else {
                            echo "<div class='updated'><p>" . __('Payhip Settings saved.', 'wp_admin_style') . "</p></div>";
                            unset($_POST['wpnonce_pfSettings']);
                            unset($_POST['submit']);
                            unset($_POST['pfFormSubmit']);
                            if (is_array($_POST) && count($_POST) > 0) {
                                foreach ($_POST as $pfPostKey => $pfPostVal) {
                                    update_option($pfPostKey, $pfPostVal);
                                }
                                if (is_array($pfSettingsArray) && count($pfSettingsArray) > 0) {
                                    foreach ($pfSettingsArray as $pfSetKey => $pfSetVal) {
                                        if ($pfSetVal['name'] != '') {
                                            $pfEachOptionVal = '';
                                            $pfEachOptionVal = $_POST[$pfSetVal['name']];
                                            $pfSettingsArray[$pfSetKey]['value'] = $pfEachOptionVal;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    ?>
                    <form class="pfSaveSettings" id="pfSaveSettings" method="post" action="<?php echo admin_url('admin.php?page=payhip-sell-ebooks', '') ?>" class="validate" enctype="multipart/form-data">
                        <input type="hidden" name="wpnonce_pfSettings" value="<?php echo wp_create_nonce('pfSettings') ?>" />
                        <input type="hidden" name="pfFormSubmit" value="yes" />
                        <?php
                        $pfSettings = $this->pfGenerateFormLayout($pfSettingsArray);
                        echo $pfSettings;
                        ?>
                        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Settings"></p>
                    </form>
                </div>
            </div>
        </div>
        <div class="postbox pfRight">
            <div class="inside">
                <?php
                $pfSettingsArray = array(
                    array(
                        'label' => 'Frontend Usage',
                        'type' => 'heading',
                        'tag' => 'h2'
                    ),
                    array(
                        'label' => '<h3>Shortcode [Payhip_Products]</h3>
                                <p>This will display ebooks a in grid format on any page you embed this shortcode.</p>',
                        'type' => 'html'
                    ),
                    array(
                        'type' => 'gap'
                    ),
                    array(
                        'label' => '<h3>Shortcode Parameter [Payhip_Products items="Number"]</h3>
                                <p>If you have lots of ebooks, you can have multiple pages to show them. Simply pick how many you want to show each time.</p>',
                        'type' => 'html'
                    ),
                    array(
                        'type' => 'gap'
                    )
                );

                $pfSettings = $this->pfGenerateFormLayout($pfSettingsArray);
                echo $pfSettings;
                ?>

            </div>
        </div>
    </div>

</div>
