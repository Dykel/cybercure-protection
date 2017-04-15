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

class Ban extends Application
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Fetch
     *
     * Fetch all bans from the database
     *
     * @param   $type
     * @return  array
     */
    public function fetch($type = 'ip')
    {
        if($type == 'ip') {
            $fetch = $this->wpdb->get_results("SELECT * FROM " . $this->wpdb->prefix . "bans", ARRAY_A);
        } elseif($type == 'country') {
            $fetch = $this->wpdb->get_results("SELECT * FROM " . $this->wpdb->prefix . "bans_country", ARRAY_A);
        }

        return $fetch;
    }

    /**
     * Ban IP
     *
     * Ban a specific IP until a specific time
     *
     * @param   String          $ip            The IP which we should ban
     * @param   String          $banned_until  Ban the IP until...
     * @param   String          $type          Type (ban or auto-ban) OPTIONAL
     * @return  boolean
     */
    public function ban_ip($ip, $banned_until, $type = 'ban')
    {
        # Check if we should sync
        # the bans with cloudflare
        if($type == 'ban') {
            if($this->config['application']['ban_ip_sync_with_cloudflare'] == 1) {
                $cloudflare = new Cloudflare();
                $ban = $cloudflare->blacklist($ip);
            }
        } elseif($type == 'auto_ban') {
            if($this->config['application']['auto_ban_sync_with_cloudflare'] == 1) {
                $cloudflare = new Cloudflare();
                $ban = $cloudflare->blacklist($ip);
            }
        }

        $insert = $this->wpdb->insert(
            $this->wpdb->prefix . 'cybercure_security_bans',
            array(
                'ip' => $ip,
                'banned_until' => date("Y-m-d H:i:s", $banned_until),
                'created_on' => current_time('mysql'),
                'updated_on' => current_time('mysql')
            ),
            array(
                '%s',
                '%s',
                '%s'
            )
        );

        if($insert == 1) {
            $inserted_id = $this->wpdb->insert_id;
            return $inserted_id;
        } else {
            return FALSE;
        }
    }

    /**
     * Ban Country
     *
     * Ban a specific country
     *
     * @param   String          $country            The IP which we should ban
     * @return  boolean
     */
    public function ban_country($country)
    {
        $insert = $this->wpdb->insert(
            $this->wpdb->prefix . 'cybercure_security_bans_country',
            array(
                'country_code' => $country,
                'created_on' => current_time('mysql')
            ),
            array(
                '%s',
                '%s'
            )
        );

        if($insert == 1) {
            $inserted_id = $this->wpdb->insert_id;
            return $inserted_id;
        } else {
            return FALSE;
        }
    }

    /**
     * Unban IP
     *
     * Remove an IP ban
     *
     * @param   Integer          $id            The ban which we should remove
     * @return  boolean
     */
    public function unban_ip($id)
    {
        # Make sure that a number has been passed
        if(!is_numeric($id)) return FALSE;

        # Check if we should sync bans and/or auto-bans
        # with cloudflare
        if($this->config['application']['auto_ban_sync_with_cloudflare'] == 1 || $this->config['application']['ban_ip_sync_with_cloudflare'] == 1) {

            $count = $this->wpdb->get_var('SELECT COUNT(*) FROM ' . $this->wpdb->prefix . 'cybercure_security_detected_attacks WHERE auto_banned = ' . $id);

            if($this->config['application']['auto_ban_sync_with_cloudflare'] == 1 && $count != 0) {
                # Remove the ban from cloudflare
                $ip = $this->wpdb->get_var('SELECT `ip` FROM ' . $this->wpdb->prefix . 'cybercure_security_bans WHERE id = ' . $id);

                $cloudflare = new Cloudflare();
                $unban = $cloudflare->remove($ip);

            } elseif($this->config['application']['ban_ip_sync_with_cloudflare'] == 1 && $count == 0) {
                # Remove the ban from cloudflare
                $ip = $this->wpdb->get_var('SELECT `ip` FROM ' . $this->wpdb->prefix . 'cybercure_security_bans WHERE id = ' . $id);

                $cloudflare = new Cloudflare();
                $unban = $cloudflare->remove($ip);
            }
        }

        # Remove the ban from the database
        $this->wpdb->query('DELETE FROM `' . $this->wpdb->prefix . 'cybercure_security_bans` WHERE `id` = ' . $id);
        return TRUE;
    }

    /**
     * Unban Country
     *
     * Remove an country ban
     *
     * @param   String          $id            The ban which we should remove
     * @return  boolean
     */
    public function unban_country($id)
    {
        # Make sure that a number has been passed
        if(!is_numeric($id)) return FALSE;

        # Remove the ban
        $this->wpdb->query('DELETE FROM `' . $this->wpdb->prefix . 'cybercure_security_bans_country` WHERE `id` = ' . $id);
        return TRUE;
    }


    /**
     * Check IP
     *
     * Check if the current IP is banned
     *
     * @return  boolean
     */
    public function check_ip()
    {
        $ip = $_SERVER['REMOTE_ADDR'];

        $query = "SELECT `id`, banned_until FROM `" . $this->wpdb->prefix . "cybercure_security_bans` WHERE ip = %s";
        $query = $this->wpdb->prepare($query, $ip);
        $result = $this->wpdb->get_row($query);

        if(!is_null($result)) {
            # Check if the ban is still active
            $banned_until = strtotime($result->banned_until);

            if($banned_until > current_time('timestamp')) {
                # The ban is still active
                $result = array('banned_until' => $banned_until);
                return $result;
            } else {
                # The ban has been expired
                # Remove it from the database
                $unban = $this->unban_ip($result->id);
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    /**
     * Check Country
     *
     * Check if the current country  is banned
     *
     * @return  boolean
     */
    public function check_country()
    {
        # Create a detection instance
        $detect = new Detect();

        # Detect the OS
        $location = $detect->Location();

        $query = $this->wpdb->get_var('SELECT `id` FROM ' . $this->wpdb->prefix . 'cybercure_security_bans_country WHERE country_code = "' . $location['country_code'] . '"');

        if($query == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}