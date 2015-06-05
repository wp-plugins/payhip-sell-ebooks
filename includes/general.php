<?php

function pf_schedule_hook_call() {

    $pf_pyhip_username = get_option('pf_payhip_username');
    if ($pf_pyhip_username) {
        $pf_json_path = PF_USER_JSON_URL . $pf_pyhip_username;
        
        $pf_json_file_cont = '';
        $pf_json_file_cont = file_get_contents($pf_json_path);
        $pf_json_file_cont = str_replace('\/', '/', $pf_json_file_cont);

        // saving payhip json data on option key pf_json_resp
        update_option('pf_json_resp', $pf_json_file_cont);
    }
}

add_filter('cron_schedules', 'pf_cron_schedule');

function pf_cron_schedule($schedules) {

    $schedules['sixsec'] = array(
        'interval' => 21600, // Every 6 hours
        'display' => __('Every 6 hours'),
    );

    return $schedules;
}

//Schedule an action if it's not already scheduled

if (!wp_next_scheduled('pf_curl_cron_action')) {

    wp_schedule_event(time(), 'sixsec', 'pf_curl_cron_action');
}

//Hook into that action that'll fire sixhour
add_action('pf_curl_cron_action', 'pf_schedule_hook_call');

function pf_pre($arr) {
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}