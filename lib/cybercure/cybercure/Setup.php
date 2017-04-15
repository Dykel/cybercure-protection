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

class Setup
{

    public function __construct()
    {
        register_activation_hook(cybercure_security_WORDPRESS_PLUGIN_BASE_PATH . 'cybercure.php', array($this, 'install'));
    }

    public function install()
    {
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        # Create the config table if it doe snot already exist
        if($wpdb->get_var("show tables like '" .$wpdb->prefix . 'cybercure_security_bans'. "'") != $wpdb->prefix . 'cybercure_security_bans') {

            $sql = "CREATE TABLE " . $wpdb->prefix . 'cybercure_security_bans' . " (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `ip` varchar(40) NOT NULL UNIQUE,
              `banned_until` timestamp NULL DEFAULT NULL,
              `reason` text NOT NULL,
              `created_on` timestamp NULL DEFAULT NULL,
              `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`)
            ) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

            dbDelta($sql);
        }

        if($wpdb->get_var("show tables like '" .$wpdb->prefix . 'cybercure_security_bans_country'. "'") != $wpdb->prefix . 'cybercure_security_bans_country') {

            $sql = "CREATE TABLE " . $wpdb->prefix . 'cybercure_security_bans_country' . " (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `country_code` varchar(2) NOT NULL,
              `created_on` timestamp NULL DEFAULT NULL,
              `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`),
              UNIQUE KEY `country_code` (`country_code`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
            ";

            dbDelta($sql);
        }

        # Create the coupons types table if it doe snot already exist
        if($wpdb->get_var("show tables like '" .$wpdb->prefix . 'cybercure_security_config'. "'") != $wpdb->prefix . 'cybercure_security_config') {

            $sql = "CREATE TABLE " . $wpdb->prefix . 'cybercure_security_config' . " (
              `key` varchar(255) NOT NULL,
              `value` text NOT NULL,
              UNIQUE KEY `key` (`key`)
            );";

            dbDelta($sql);

            $sql = "INSERT INTO " . $wpdb->prefix . 'cybercure_security_config' . "(`key`, `value`) VALUES
                ('auto_ban_ip_mass_requests', '1'),
                ('auto_ban_ip_proxies', '1'),
                ('auto_ban_ip_spammers', '1'),
                ('auto_ban_ip_sql_injections', '1'),
                ('auto_ban_ip_xss_attacks', '1'),
                ('auto_ban_sync_with_cloudflare', '0'),
                ('ban_ip_sync_with_cloudflare', '0'),
                ('cloudflare_api_key', ''),
                ('cloudflare_email', ''),
                ('log_mass_requests', '1'),
                ('log_proxies', '1'),
                ('log_spammers', '1'),
                ('log_sql_injections', '1'),
                ('hide_wp_login_key', ''),
                ('hide_wp_admin', '0'),
                ('log_xss_attacks', '1'),
                ('project_honeypot_api_key', ''),
                ('protection_mass_requests', '1'),
                ('protection_proxies', '1'),
                ('protection_spammers', '1'),
                ('protection_sql_injections', '1'),
                ('protection_xss_attacks', '1'),
                ('redirect_banned', '" .cybercure_security_WORDPRESS_PLUGIN_BASE_URL. "/pages/access_denied_banned.html'),
                ('redirect_mass_requests', '" .cybercure_security_WORDPRESS_PLUGIN_BASE_URL. "/pages/access_denied_ddos.html'),
                ('redirect_proxies', '" .cybercure_security_WORDPRESS_PLUGIN_BASE_URL. "/pages/access_denied_ip.html'),
                ('redirect_spammers', '" .cybercure_security_WORDPRESS_PLUGIN_BASE_URL. "/pages/access_denied_ip.html'),
                ('redirect_sql_injections', '" .cybercure_security_WORDPRESS_PLUGIN_BASE_URL. "/pages/access_denied_infected.html'),
                ('redirect_xss_attacks', '" .cybercure_security_WORDPRESS_PLUGIN_BASE_URL. "/pages/access_denied_infected.html')
            ;";

            dbDelta($sql);
        }

        # Create the coupons table if it doe snot already exist
        if($wpdb->get_var("show tables like '" .$wpdb->prefix . 'cybercure_security_detected_attacks'. "'") != $wpdb->prefix . 'cybercure_security_detected_attacks') {

            $sql = "CREATE TABLE " . $wpdb->prefix . 'cybercure_security_detected_attacks' . " (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `type` varchar(255) NOT NULL,
              `ip` varchar(39) NOT NULL,
              `auto_banned` int(1) NOT NULL,
              `custom_data` text NOT NULL,
              `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
              PRIMARY KEY (`id`)
            ) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

            dbDelta($sql);
        }
    }

    public function uninstall()
    {

    }
}