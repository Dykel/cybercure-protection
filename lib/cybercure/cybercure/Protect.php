<?php namespace Cybercure\Cybercure;
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

class Protect extends Application
{
    public function __construct($config = FALSE, $fetchOnly = FALSE)
    {
        parent::__construct();

        if($fetchOnly === FALSE) {
            $curl  = new Curl();
            $this->WhatIsMyIPAdress = new WhatIsMyIPAddress($curl);
            $this->sql_inject_class = new SQLInject();
            $this->local_proxys_file = 'ip_list.txt';
        }
    }

    /**
     * Hide WP Login
     *
     * Hide wp-login.php - A special GET param is required to access it
     * @return  void
     */
    public function hide_wp_login()
    {
        $enabled = TRUE;

        if($enabled == TRUE) {
            # Set the redirection URL for an logout
            # to the homepage to avoid problems with
            # the missing GET parameter (see below)
            add_action('wp_logout',create_function('','wp_redirect(home_url());exit();'));

            global $pagenow;
            if($pagenow == 'wp-login.php') {
                if(empty($_GET) || !isset($_GET['cybercure-security-pro']) || $_GET['cybercure-security-pro'] != '1234') {
                    # Check if we are processing a login request, that would be a
                    # excuse for the missing GET parameter, the same for a logout request
                    if(!isset($_POST['log']) && (!isset($_GET['action']) || $_GET['action'] != 'logout')) {
                        # Throw 404 error
                        $this->throw_404();
                    }
                }
            }
        }
    }

    /**
     * Hide WP Admin
     *
     * Hide /wp-admin - Guests get a 404 error shown
     * @return  void
     */
    public function hide_wp_admin()
    {
        # We only will hide it if we are in admin (/wp-admin/)
        if(is_admin()) {
            # Non logged in users.
            if(!is_user_logged_in()) {
                $this->throw_404();
            }
        }
    }

    /**
     * Detect Proxy
     *
     * Detect a proxy/VON
     *
     * Optional @param  Integer         $method             Method to use. 1 has a high detection. 2 detects only proxys.
     * Optional @param  String          $ip                 IP you want to check. Default: Connecting IP
     * @return String
     *
     */
    public function detect_proxy($method = 1, $ip = null)
    {
        # Check if the proxy protection has been enabled
        if($this->config['application']['protection_proxies'] == '1') {
            if($ip === null) $ip = $_SERVER['REMOTE_ADDR'];

            if($method == 1) {
                # Check if this a known ip and already in our local list
                if(file_exists($this->local_proxys_file) && filesize($this->local_proxys_file) > 0 ) {
                    # The local file does already exists therefore we read it
                    $local_list = file_get_contents($this->local_proxys_file);
                    $local_list = unserialize($local_list);

                    foreach ($local_list as $entry) {
                        if ($entry['ip'] === $ip) {
                            $ip_status = $entry['status'];
                            break;
                        }
                    }
                }

                if(isset($ip_status)) {
                    # IP is already in our list
                    # Check if it is a non allowed one
                    if($ip_status != 'none') {
                        # This IP is not allowed
                        # Redirect to the defined page
                        header('location: '. $this->config['application']['redirect_proxies']);
                        exit();
                    }
                } else {
                    # This IP is currently not in our list
                    # Detect proxy using WhatIsMyIPAddress.com
                    $detect = $this->WhatIsMyIPAdress->detect_proxy($ip);

                    if($detect === TRUE) {
                        # A proxy has been detected
                        # Ban the ip if the auto banning has been enabled
                        $auto_ban = $this->check_autoban('auto_ban_ip_proxies');

                        # If the proxy logging has been enabled, log it
                        if($this->config['application']['log_proxies'] == 1) {
                            $this->write_log('proxy', array('auto_banned' => $auto_ban));
                        }

                        $this->status = $this->WhatIsMyIPAdress->status;

                        # Write the IP into our local list
                        $array = array('ip' => $ip, 'status' => $this->status);
                        $this->add_item($array);

                        # Redirect to the defined page
                        header('location: '. $this->config['application']['redirect_proxies']);
                        exit();
                    } else {
                        # No proxy has been detected
                        # Write the IP into our local list
                        $array = array('ip' => $ip, 'status' => 'none');
                        $this->add_item($array);
                    }
                }

                # Detect proxy by checking the headers
            } elseif ($method == 2) {
                $proxy_headers = array(
                    'HTTP_VIA',
                    'HTTP_X_FORWARDED_FOR',
                    'HTTP_FORWARDED_FOR',
                    'HTTP_X_FORWARDED',
                    'HTTP_FORWARDED',
                    'HTTP_CLIENT_IP',
                    'HTTP_FORWARDED_FOR_IP',
                    'VIA',
                    'X_FORWARDED_FOR',
                    'FORWARDED_FOR',
                    'X_FORWARDED',
                    'FORWARDED',
                    'CLIENT_IP',
                    'FORWARDED_FOR_IP',
                    'HTTP_PROXY_CONNECTION'
                );
                foreach($proxy_headers as $x){
                    if (isset($_SERVER[$x])) {
                        # Ban the ip if the auto banning has been enabled
                        $auto_ban = $this->check_autoban('auto_ban_ip_proxies');

                        # If the proxy logging has been enabled, log it
                        if($this->config['application']['log_proxys'] == 1) {
                            $this->write_log('proxy', array('auto_banned' => $auto_ban));
                        }

                        # Redirect to the defined page
                        header('location: '. $this->config['application']['redirect_proxys']);
                        exit();
                    }
                }
            }
        }
    }

    /**
     * Detect SQL Injection
     *
     * Checks a string for a SQL injection
     *
     * @param  String         $query             Query you want to check
     * @return void
     *
     */
    public function detect_sql_injection($query)
    {

        # Check if the SQL injection protection has been enabled
        if($this->config['application']['protection_sql_injections'] == '1') {

            if($this->sql_inject_class->testArray($query) !== FALSE) {
                # Ban the ip if the auto banning has been enabled
                $auto_ban = $this->check_autoban('auto_ban_ip_sql_injections');

                # If the proxy logging has been enabled, log it
                if($this->config['application']['log_sql_injections'] == 1) {
                    $this->write_log('sql_injection', array('query' => $this->sql_inject_class->query, 'auto_banned' => $auto_ban));
                }

                # Redirect to the defined page
                header('location: '. $this->config['application']['redirect_sql_injections']);
                exit();
            }
        }
    }

    /**
     * Detect Mass Requests (DDos)
     *
     * Detects mass requests (DDos attacks)
     *
     * @return boolean
     * @return void
     *
     */
    public function detect_mass_requests()
    {
        # Check if the mass requests protection has been enabled
        if($this->config['application']['protection_mass_requests'] == '1') {
            if(!isset($_SESSION['protect']['mass_request_time']) || $_SESSION['protect']['mass_request_time'] == null) {
                $_SESSION['protect']['mass_request_time'] = microtime(true);
                $_SESSION['protect']['mass_request_request'] = 1;
            } else {
                $_SESSION['protect']['mass_request_request'] += 1;
                if($_SESSION['protect']['mass_request_request'] >= 3 && microtime(true) - $_SESSION['protect']['mass_request_time'] < 1) {
                    return TRUE;
                } elseif(microtime(true) - $_SESSION['protect']['mass_request_time'] > 1) {
                    # Reset the counter since more than a second is over
                    $_SESSION['protect']['mass_request_time'] = null;

                    # Check if there were mass requests in the last second
                    if($_SESSION['protect']['mass_request_request'] >= 3) {
                        # Ban the ip if the auto banning has been enabled
                        $auto_ban = $this->check_autoban('auto_ban_ip_mass_requests');

                        # Log them if logging has been enabled
                        if($this->config['application']['log_mass_requests'] == 1) {
                            $this->write_log('mass_requests', array('requests' => $_SESSION['protect']['mass_request_request'], 'auto_banned' => $auto_ban));
                        }

                        # Redirect to the defined page
                        header('location: '. $this->config['application']['redirect_mass_requests']);
                        exit();
                    }
                }
            }
        }
    }

    /**
     * Detect Spammer
     *
     * Checks if the connecting IP is a spammer
     *
     * @return void
     *
     */
    public function detect_spammer() {
        # Check if the spammer protection has been enabled
        if($this->config['application']['protection_spammers'] == '1') {
            $ip = $_SERVER['REMOTE_ADDR'];

            $lookup = $this->config['application']['project_honeypot_api_key'] . '.' . implode('.', array_reverse(explode ('.', $ip ))) . '.dnsbl.httpbl.org';
            $result = explode( '.', gethostbyname($lookup));

            if ($result[0] == 127) {
                $activity = $result[1];
                $threat = $result[2];
                $type = $result[3];

                # Do not block search engines
                if($type != '0') {
                    # Ban the ip if the auto banning has been enabled
                    $auto_ban = $this->check_autoban('auto_ban_ip_spammers');

                    # Log them if logging has been enabled
                    if($this->config['application']['log_spammers'] == 1) {
                        $this->write_log('spammer', array('auto_banned' => $auto_ban));
                    }

                    # Redirect to the defined page
                    header('location: '. $this->config['application']['redirect_spammers']);
                    exit();
                }
            }
        }
    }

    /**
     * Detect XSS
     *
     * Detects XSS attacks
     *
     * @param  String         $string            String we want to check
     * @return boolean
     *
     */
    public function detect_xss($string) {
        # Check if the xss protection has been enabled
        if($this->config['application']['protection_xss_attacks'] == '1') {
            $string_input = $string;
            $contains_xss = FALSE;

            // Skip any null or non string values
            if(is_null($string) || !is_string($string)) {
                return FALSE;
            }

            // Keep a copy of the original string before cleaning up
            $orig = $string;

            // URL decode
            $string = urldecode($string);

            // Convert Hexadecimals
//            $string = preg_replace('!(&#|\\\)[xX]([0-9a-fA-F]+);?!e','chr(hexdec("$2"))', $string);
            $string = preg_replace_callback('!(&#|\\\)[xX]([0-9a-fA-F]+);?!', function ($m) {
                return chr(hexdec($m[2]));
            }, $string);

            // Clean up entities
            $string = preg_replace('!(&#0+[0-9]+)!','$1;',$string);

            // Decode entities
            $string = html_entity_decode($string, ENT_NOQUOTES, 'UTF-8');

            // Strip whitespace characters
            $string = preg_replace('!\s!','',$string);

            // Set the patterns we'll test against
            $patterns = array(
                // Match any attribute starting with "on" or xmlns
                '#(<[^>]+[\x00-\x20\"\'\/])(on|xmlns)[^>]*>?#iUu',

                // Match javascript:, livescript:, vbscript: and mocha: protocols
                '!((java|live|vb)script|mocha|feed|data):(\w)*!iUu',
                '#-moz-binding[\x00-\x20]*:#u',

                // Match style attributes
                '#(<[^>]+[\x00-\x20\"\'\/])style=[^>]*>?#iUu',

                // Match unneeded tags
                '#</*(applet|meta|xml|blink|link|style|script|embed|object|iframe|frame|frameset|ilayer|layer|bgsound|title|base)[^>]*>?#i'
            );

            foreach($patterns as $pattern) {
                // Test both the original string and clean string
                if(preg_match($pattern, $string) || preg_match($pattern, $orig)){
                    $contains_xss = TRUE;
                }

                if ($contains_xss === TRUE) {
                    # Ban the ip if the auto banning has been enabled
                    $auto_ban = $this->check_autoban('auto_ban_ip_xss_attacks');

                    # Log them if logging has been enabled
                    if($this->config['application']['log_xss_attacks'] == 1) {
                        $this->write_log('xss_attack', array('query' => $string_input, 'auto_banned' => $auto_ban));
                    }

                    # Redirect to the defined page
                    header('location: '. $this->config['application']['redirect_xss_attacks']);
                    exit();
                }
            }
        }
        return FALSE;
    }

    /**
     * Detect Banned IP
     *
     * Check if the current IP is banned
     *
     * @return boolean
     *
     */
    public function detect_banned_ip()
    {
        $ban_object = new Ban();
        $check = $ban_object->check_ip();

        if($check !== FALSE) {
            header('location: '. $this->config['application']['redirect_banned']);
            exit();
        }
    }

    /**
     * Detect Banned Country
     *
     * Check if the current country is banned
     *
     * @return boolean
     *
     */
    public function detect_banned_country()
    {
        $ban_object = new Ban();
        $check = $ban_object->check_country();

        if($check !== FALSE) {
            header('location: '. $this->config['application']['redirect_banned']);
            exit();
        }
    }


    /**
     * Write Log
     *
     * Write the threat into our logs
     *
     * @param  Integer       $type               Type of the threat we want to log
     * @param  Array         $data               Array with the data needed to log this type of threat
     * @return Void
     *
     */
    public function write_log($type, $data)
    {
        global $wpdb;

        # Fill the required variables
        $time = current_time( 'mysql' );
        $ip = $_SERVER['REMOTE_ADDR'];


        $auto_banned = $data['auto_banned'];


        if(isset($_SERVER['HTTP_REFERER'])) {
            $referrer = $_SERVER['HTTP_REFERER'];
        } else {
            $referrer = 'Unknown';
        }

        # Add the personal data
        $personal_data = $this->get_personal_data();

        # Browser Data
        $custom_data['personal']['browser']['name'] = $personal_data['browser']['name'] . ' ' . $personal_data['browser']['version'];
        $custom_data['personal']['browser']['code'] = $personal_data['browser']['code'];

        # OS Data
        $custom_data['personal']['OS'] = $personal_data['OS'];

        # Location Data
        $custom_data['personal']['location']['name'] = $personal_data['location']['name'];
        $custom_data['personal']['location']['code'] = strtolower($personal_data['location']['country_code']);


        # Add some additional details to the log
        # User agent
        $custom_data['personal']['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

        # Referrer
        $custom_data['personal']['referrer'] = $referrer;


        # Write the data into our database
        if($type == 'mass_requests') {
            # Store the amount of requests
            $custom_data['attack']['requests'] = $data['requests'];
        } elseif($type == 'sql_injection' || $type == 'xss_attack') {
            # Store the query
            $custom_data['attack']['query'] = $data['query'];
        }

        $custom_data = serialize($custom_data);

        $insert = $wpdb->insert(
            $wpdb->prefix . 'cybercure_security_detected_attacks',
            array(
                'ip' => $ip,
                'type' => $type,
                'created_on' => $time,
                'auto_banned' => $auto_banned,
                'custom_data' => $custom_data,
            ),
            array(
                '%s',
                '%s',
                '%s',
                '%d',
                '%s',
            )
        );

    }

    /**
     * Fetch Log
     *
     * Fetch the log for a specific threat type
     *
     * @param  String          $type             Type, e.g. 'xss_attack'
     * @param  String          $ip_full_log      IP if we should fetch the logs for a specific ip only
     * @return String
     *
     */
    public function fetch_log($type, $ip_full_log = null)
    {
        global $wpdb;

        # Should we fetch the detail log for a single ip
        $result = array();
        if($ip_full_log != null) {
            $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "cybercure_security_detected_attacks WHERE type = %s and ip = %s", $type, $ip_full_log);
            $fetch = $wpdb->get_results($query, ARRAY_A);
        } else {
            $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "cybercure_security_detected_attacks WHERE type = %s", $type);
            $fetch = $wpdb->get_results($query, ARRAY_A);
        }

        foreach($fetch as $row) {
            $temp = array();
            $merged[$row['id']] = array_merge($temp, $row);
            $result = $merged + $result;
        }

        if($type == 'mass_requests' && isset($result) && !is_null($result) && $ip_full_log == null) {
            $output = array();
            foreach($result as $key1) {
                $custom_data = unserialize($key1['custom_data']);
                $key1['requests'] = $custom_data['attack']['requests'];

                $check = $this->filter($key1['ip']);
                if($check === TRUE) {
                    # Ip is not in the array. Let's add it.
                    $temp_array = array('id' => $key1['id'], 'ip' => $key1['ip']);
                    $this->check[] = $temp_array;
                    $output[$key1['id']] = $key1;
                } else {
                    # Ip is already in the array
                    # Count the total amount of requests from this ip
                    $i = 0;
                    foreach($output as $temp) {
                        $i++;
                        if($temp['ip'] == $key1['ip']) {
                            $output[$check['id']]['requests'] += $key1['requests'];
                            break;
                        }
                    }
                }
            }
        } else {
            if(isset($result)) {
                $output = $result;
            }
        }

        if (isset($output) && !is_null($output)) {
            return $output;
        } else {
            return FALSE;
        }
    }

    /**
     * Delete from log
     *
     * Delete a specific entry from our logs
     *
     * @param  Integer         $type               Type of the threat
     * @param  Integer         $id                 ID of the entry
     * @return boolean
     *
     */
    public function delete_from_log($type, $id)
    {
        global $wpdb;

        $sql = 'DELETE FROM `' . $wpdb->prefix . 'cybercure_security_detected_attacks` WHERE `id` = %d and `type` = %s';
        $sql = $wpdb->prepare($sql, $id, $type);
        $query = $wpdb->query($sql);

        return TRUE;
    }

    /**
     * Get Personal Data
     *
     * Get a client´s personal data (OS, Browser, Location)
     *
     * @return array
     *
     */
    public function get_personal_data()
    {
        # Create a detection instance
        $detect = new Detect();

        # Detect the OS
        $os = $detect->OS();
        $browser = $detect->Browser();
        $location = $detect->Location();
        # Return the gathered data
        $result = array('OS' => $os, 'browser' => $browser, 'location' => $location);
        return $result;

    }

    private function check_autoban($type)
    {
        # Check if the auto banning
        # has been enabled for this type
        if($this->config['application'][$type] > 0) {
            # Auto banning has been enabled
            $ip = $_SERVER['REMOTE_ADDR'];

            # Add the ban
            $ban_object = new Ban();
            $ban_until = current_time('timestamp') + (60*$this->config['application'][$type]);
            $ban = $ban_object->ban_ip($ip, $ban_until, 'auto_ban');

            # Make sure that the ban has been added successfully
            if($ban !== FALSE) {
                # The ban has been added
                # Return the id
                return $ban;
            } else {
                # Something went wrong
                # Unable to a add the ban
                return FALSE;
            }
        } else {
            # Auto banning has been disabled
            # for this type
            return FALSE;
        }
    }

    /**
     * Filter
     *
     * Private function. Needed to sort our "Mass requests" logs
     *
     * @param  Integer          $ip                 Type of the threat we want to log
     * @return Array
     *
     */
    private function filter($ip)
    {
        if(!isset($this->check)) $this->check = array();
        for($i=0; $i<count($this->check); $i++) {
            if($ip == $this->check[$i]['ip']) {
                return $this->check[$i];
            }
        }
        return TRUE;
    }

    /**
     * Add item
     *
     * Private function. Add a IP to our local ip list
     *
     * @param  Array            $array               Array with the data for the proxy/VPN
     * @return array
     *
     */
    private function add_item($array) {
        if(file_exists($this->local_proxys_file) && filesize($this->local_proxys_file) > 0) {
            $old = file_get_contents($this->local_proxys_file);
            $old = unserialize($old);
        } else {
            $old = array();
        }

        array_push($old, $array);
        $new = serialize($old);
        file_put_contents($this->local_proxys_file, $new);
        return $new;
    }
}
