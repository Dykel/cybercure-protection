<?php namespace Cybercure\Cybercure\Admin;
/*======================================================================*\
|| #################################################################### ||
|| #                This file is part of Cybercure                    # ||
|| #                          for  #RISK[Solutions]Maurice            # ||
|| # ---------------------------------------------------------------- # ||
|| #         Copyright © 2017 cybercure.ngrok.io. All Rights Reserved.# ||
|| #                                                                  # ||
|| # ----------     Cybercure IS AN OPENSOURCE SOFTWARE    ---------- # ||
|| # -------------------- https://cybercure.ngrok.io -------- ------- # ||
|| #################################################################### ||
\*======================================================================*/

use Cybercure\Cybercure\Application;
use Cybercure\Cybercure\Protect;

class Log extends Application
{

    public function stats($frame = 'default')
    {
        if($frame == 'default') {
            # Fetch stats for one day
            $day = $this->wpdb->get_var('SELECT COUNT(*) FROM ' . $this->wpdb->prefix . 'cybercure_security_detected_attacks WHERE created_on > DATE_SUB(NOW(), INTERVAL 1 DAY)');

            # Fetch stats for one week
            $week = $this->wpdb->get_var('SELECT COUNT(*) FROM ' . $this->wpdb->prefix . 'cybercure_security_detected_attacks WHERE created_on > DATE_SUB(NOW(), INTERVAL 1 WEEK)');

            # Fetch stats for one month
            $month = $this->wpdb->get_var('SELECT COUNT(*) FROM ' . $this->wpdb->prefix . 'cybercure_security_detected_attacks WHERE created_on > DATE_SUB(NOW(), INTERVAL 1 MONTH)');

            # Return the stats
            $return = array('day' => $day, 'week' => $week, 'month' => $month);
            return $return;
        }
    }

    public function ajax_detailed_log() {

        $id = intval($_POST['id']);

        # Get the type where we should fetch the logs form
        if(isset($_POST['type']) && !empty($_POST['type'])) {
            $type = $_POST['type'];

            # Should we fetch the detail log for a single ip
            $protect_class = new Protect($this->config, TRUE);
            if(isset($_POST['detail']) && $_POST['detail'] == 'full') {
                $data['fetch_log']['detail'] = 'full';
                $fetch_ip = $_POST['ip'];
                $fetch = $protect_class->fetch_log($type, $fetch_ip);
            } else {
                $fetch = $protect_class->fetch_log($type);
            }
        }


        $custom_data = unserialize($fetch[$id]['custom_data']);

        $auto_banned = $fetch[$id]['auto_banned'];
        if(!is_null($auto_banned) && !empty($auto_banned) && $auto_banned >= 1) {
            $auto_banned_status = TRUE;
            $auto_banned = '<span class="label label-success">Yes</span>';
        } else {
            $auto_banned_status = FALSE;
            $auto_banned = '<span class="label label-important">No</span>';
        }


        $response = '
<table class="wp-list-table widefat fixed posts detailed-log-table">
                    <thead>
                        <tr>
                            <th><strong>Type</strong></th>
                            <th><strong>Value</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Date & Time</td>
                            <td>' .$fetch[$id]['created_on']. ' </td>
                        </tr>
                        <tr>
                            <td>IP Address</td>
                            <td>' .$fetch[$id]['ip']. ' </td>
                        </tr>';

            if(isset($result['requests'])) {
                $response .='
                                <tr>
                                    <td>Total requests</td>
                                    <td>' .$result['requests']. ' </td>
                                </tr>';
            }

        if(isset($custom_data['attack']['requests'])) {
            $response .='
                                <tr>
                                    <td>Requests</td>
                                    <td>' .$custom_data['attack']['requests']. ' </td>
                                </tr>';
        }

        if(isset($custom_data['attack']['query'])) {
            $response .='
                            <tr>
                                <td>Query</td>
                                <td>' .htmlspecialchars($custom_data['attack']['query']). '</td>
                            </tr>';
        }

        $response .='
                        <tr>
                            <td>User agent</td>
                            <td>' .$custom_data['personal']['user_agent']. ' </td>
                        </tr>
                        <tr>
                            <td>Referrer</td>
                            <td>' .$custom_data['personal']['referrer']. ' </td>
                        </tr>
                        <tr>
                            <td>Operating system</td>
                            <td><img src="' .cybercure_security_WORDPRESS_PLUGIN_BASE_URL . '/templates/assets/img/os/' .$custom_data['personal']['OS']['code']. '.png"> ' .$custom_data['personal']['OS']['name']. ' </td>
                        </tr>
                        <tr>
                            <td>Browser</td>
                            <td><img src="' .cybercure_security_WORDPRESS_PLUGIN_BASE_URL . '/templates/assets/img/browsers/' .$custom_data['personal']['browser']['code']. '.png"> ' .$custom_data['personal']['browser']['name']. ' </td>
                        </tr>
                        <tr>
                            <td>Location</td>
                            <td><img src="' .cybercure_security_WORDPRESS_PLUGIN_BASE_URL . '/templates/assets/img/flags/' .$custom_data['personal']['location']['code']. '.png"> ' .$custom_data['personal']['location']['name']. ' </td>
                        </tr>
                        <tr>
                            <td>Auto Banned</td>
                            <td>' .$auto_banned. '</td>
                        </tr>
                    </tbody>
                </table>
        ';

        die($response);
    }
}