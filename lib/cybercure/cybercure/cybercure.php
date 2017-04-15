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

class Cybercure extends Application {

    public $notices = array();
    public $isAdmin;
    private $Protect;

    public function __construct()
    {
        parent::__construct();
        add_action('wp_enqueue_scripts', array($this, 'enqueue_custom_style'));
    }

    /**
     * Enqueue custom style
     *
     * We need to enqueue our style.css at this point
     * cause we also need it for the frontend
     * @return  void
     */
    public function enqueue_custom_style()
    {
        wp_register_style('cybercure-security-pro-style', cybercure_security_WORDPRESS_PLUGIN_BASE_URL . '/templates/assets/style.css');
        wp_enqueue_style('cybercure-security-pro-style');
    }

    /**
     * Start Protection
     *
     * Perform some checks and then start the protection features
     * @return  void
     */
    public function start_protection()
    {
        if ( !current_user_can( 'manage_options' ) ) {

            $this->Protect = new Protect($this->config);
            $this->protect();
        }
    }

    /**
     * Protect
     *
     * Start up the following protection features:
     * Detect banned ip's/countries, mass requests,
     * proxies, vpn's, spammers, xss and mysql injections
     * @return  void
     */
    public function protect()
    {
        # Hide wp-login.php
        if(!empty($this->config['application']['hide_wp_login_key'])) {
            add_action('init', array($this->Protect, 'hide_wp_login'));
        }

        # Hide /wp-admin
        if($this->config['application']['hide_wp_admin'] == 1) {
            add_action('init', array($this->Protect, 'hide_wp_admin'));
        }

        # Detect a banned ip
        $this->Protect->detect_banned_ip();
        # Detect a banned ip
        $this->Protect->detect_banned_ip();

        # Detect a banned country
        $this->Protect->detect_banned_country();

        # Detect mass requests
        $this->Protect->detect_mass_requests();

        # Detect spammer
        $this->Protect->detect_spammer();

        # Detect a proxy/VPN
        $this->Protect->detect_proxy();

        # Detect XSS attacks
        foreach($_POST as $string) {
            $this->Protect->detect_xss($string);
        }
        foreach($_GET as $string) {
            $this->Protect->detect_xss($string);
        }

        # Detect SQL Injections
        $this->Protect->detect_sql_injection($_GET);
        $this->Protect->detect_sql_injection($_POST);
        $this->Protect->detect_sql_injection($_COOKIE);
    }
}
