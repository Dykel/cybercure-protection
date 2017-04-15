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

class Application extends Base
{
    public $config;
    public $isAdmin;

    public function __construct()
    {
        parent::__construct();
        $this->config_setter();

        add_action('plugins_loaded', array($this, 'check_admin'));
    }

    private function config_setter()
    {
        $config = $this->fetch_config();
        $this->config = $config;
    }

    public function check_admin() {
        if(current_user_can('manage_options')) {
            $this->isAdmin = TRUE;
        } else {
            $this->isAdmin = FALSE;
        }
    }

    /**
     * Fetch Config
     *
     * Fetch the config settings from the database
     * @return  array
     */
    public function fetch_config()
    {
        $query = "SELECT * FROM " . $this->wpdb->prefix . "cybercure_security_config";
        $result = $this->wpdb->get_results($query, ARRAY_A);

        $config = array();

        foreach($result as $row) {
            $config['application'][$row['key']] =  $row['value'];
        }

        if(is_array($config)) {
            return $config;
        } else {
            return FALSE;
        }
    }

    /**
     * Update Config
     *
     * Update a specific config value
     * @param   String          $key                The key you want to update
     * @param   String          $value              The value for the key you want to update
     * @return  boolean
     */
    public function update_config($key, $value)
    {
        $sql = "UPDATE `" . $this->wpdb->prefix . "cybercure_security_config` SET `value` = %s WHERE `key` = %s";
        $sql = $this->wpdb->prepare($sql, $value, $key);
        $query = $this->wpdb->query($sql);

        if ($query == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function throw_404()
    {
        # Set 404
        global $wp_query;
        $wp_query->set_404();
        status_header(404);


        # Disable the admin Bar
        add_filter('show_admin_bar', '__return_false', 900);
        remove_action('admin_footer', 'wp_admin_bar_render', 10);
        remove_action('wp_head', 'wp_admin_bar_header', 10);
        remove_action('wp_head', '_admin_bar_bump_cb', 10);
        wp_dequeue_script('admin-bar');
        wp_dequeue_style('admin-bar');

        # Show 404 error page
        get_template_part( 404 );

        # Quit
        exit;
    }
}