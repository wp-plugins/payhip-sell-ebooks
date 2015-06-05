<?php
// include common general functions
include 'general.php';

// assets path
define("PF_ASSETS_ADMIN_URL", PF_PLUGIN_URL.'/admin/assets/');
define("PF_ASSETS_PUBLIC_URL", PF_PLUGIN_URL.'/public/assets/');

define("PF_PAYHIP_CREATE_ACT_URL", 'https://payhip.com/auth/register?utm_source=wordpress&utm_medium=link&utm_campaign=wp');
define("PF_PAYHIP_USER_JSON_FETCH_FREQUENCY", 6); // in hrs

// developer purpose
define("PF_USER_JSON_URL", 'https://payhip.com/wordpress/user/' ); // if false set then check for username repective json
define("PF_TIMTHUMB_WEB_PATH", 'https://payhip.com/timthumb.php' );
define("PF_TIMTHUMB_IMAGE_WIDTH", 250 );

// texts
define("PF_FRONT_WRONG_USERNAME", 'Payhip username is incorrect, please go to the plugin settings and change it.' );
define("PF_FRONT_OK_USERNAME_0_PROD", 'Payhip username given is correct but there are no items to display.' );

define("PF_BACK_WRONG_USERNAME", 'Payhip username is incorrect! Please correct the username.' );
define("PF_BACK_OK_USERNAME_0_PROD", 'Payhip username is correct but user has 0 items to display.' );
define("PF_BACK_OK_USERNAME", 'Payhip username is correct and user has {item_count} item(s) to display.' ); // {item_count} will automatically replaced with item count