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
use Cybercure\Cybercure\Ban;
use Cybercure\Cybercure\Protect;

class Admin extends Application
{
    public function __construct()
    {
        parent::__construct();

        add_action('plugins_loaded', array($this, 'initialize_admin'));
    }

    public function initialize_admin()
    {
        # Create the admin menu if we are a admin
        if($this->isAdmin === TRUE) {
            add_action('admin_menu', array($this, 'create_menu'));
            add_action('admin_init', array($this, 'register_assets'));

            # Register the AJAX Callback for a detailed log report
            $Log = new Log();
            add_action('wp_ajax_detailed_log', array($Log, 'ajax_detailed_log'));
        }
    }

    public function create_menu()
    {
        # Dashboard
        add_menu_page('Cybercure', 'Cybercure', 'manage_options', 'cybercure_security_dashboard', array($this, 'dashboard'));

        # Attack Log
        add_submenu_page('cybercure_security_dashboard', '', 'Detected Attacks', 'manage_options', 'cybercure_security_detected_attacks', array($this, 'detected_attacks'));

        # Settings
        add_submenu_page('cybercure_security_dashboard', '', 'Settings', 'manage_options', 'cybercure_security_settings', array($this, 'settings'));

        # Bans
        add_submenu_page('cybercure_security_dashboard', '', 'Bans', 'manage_options', 'cybercure_security_bans', array($this, 'bans'));
    }

    function register_assets()
    {
        # Styles
        wp_register_style('jquery-ui', cybercure_security_WORDPRESS_PLUGIN_BASE_URL . '/templates/assets/jquery-ui/jquery-ui.min.css');
        wp_register_style('switchButton', cybercure_security_WORDPRESS_PLUGIN_BASE_URL . '/templates/assets/switchButton/jquery.switchButton.css');
        wp_register_style('cybercure-security-pro-style', cybercure_security_WORDPRESS_PLUGIN_BASE_URL . '/templates/assets/style.css');
        wp_enqueue_style('jquery-ui');
        wp_enqueue_style('switchButton');
        wp_enqueue_style('cybercure-security-pro-style');

        # Scripts
        wp_register_script('switchButton', cybercure_security_WORDPRESS_PLUGIN_BASE_URL . '/templates/assets/switchButton/jquery.switchButton.js', array('jquery', 'jquery-ui-widget', 'jquery-effects-core'));
        wp_register_script('cybercure', cybercure_security_WORDPRESS_PLUGIN_BASE_URL . '/templates/assets/js/cybercure.js', array('jquery', 'switchButton'));
        wp_enqueue_script("jquery-ui-dialog", FALSE, array('jquery'));
        wp_enqueue_script("jquery-ui-widget", FALSE, array('jquery'));
        wp_enqueue_script("jquery-ui-datepicker", FALSE, array('jquery'));
        wp_enqueue_script("jquery-ui-tabs", FALSE, array('jquery'));
        wp_enqueue_script("jquery-effects-core", FALSE, array('jquery'));
        wp_enqueue_script("switchButton", FALSE, array('jquery'));
        wp_enqueue_script("cybercure", FALSE, array('jquery', 'jquery-ui', 'switchButton'));
    }

    public function dashboard()
    {
        $Log = new Log();
        $stats = $Log->stats();

        # Render the template
        require_once cybercure_security_WORDPRESS_PLUGIN_BASE_PATH . 'templates/admin/dashboard.php';
    }

    public function bans()
    {
        # Should we ban a new ip
        $Ban = new Ban();

        if(isset($_POST['action']) && $_POST['action'] == 'ban_ip') {
            if(isset($_POST['ip']) && !empty($_POST['ip']) && isset($_POST['banned_until']) && !empty($_POST['banned_until'])) {
                $ip = $_POST['ip'];
                $banned_until = strtotime($_POST['banned_until']);

                $add_ban = $Ban->ban_ip($ip, $banned_until);

                # Make sure that the ban has been added successfully
                if($add_ban !== FALSE) {
                    echo '<div class="updated fade"><p><strong>Success!</strong> The selected IP address has been banned.</p></div>';
                } else {
                    echo '<div class="error fade"><p><strong>Error!</strong> Something went wrong. Unable to add the ban. Make sure that this IP is not already banned.</p></div>';
                }
            }
        # Should we ban a country
        } elseif(isset($_POST['action']) && $_POST['action'] == 'ban_country') {
            $country = $_POST['country'];

            $add_ban = $Ban->ban_country($country);

            # Make sure that the ban has been added successfully
            if($add_ban !== FALSE) {
                echo '<div class="updated fade"><p><strong>Success!</strong> The selected country has been banned.</p></div>';
            } else {
                echo '<div class="error fade"><p><strong>Error!</strong> Something went wrong. Unable to add the ban. Make sure that this country is not already banned.</p></div>';
            }

        # Or should we unban someone
        } elseif(isset($_GET['action']) && $_GET['action'] == 'unban') {
            if(isset($_GET['type']) && $_GET['type'] == 'ip') {
                if(isset($_GET['id']) && !empty($_GET['id'])) {
                    $unban = $Ban->unban_ip($_GET['id']);
                    echo '<div class="updated fade"><p><strong>Success!</strong> The selected IP address has been unbanned.</p></div>';
                }
            } elseif(isset($_GET['type']) && $_GET['type'] == 'country') {
                if(isset($_GET['id']) && !empty($_GET['id'])) {
                    $unban = $Ban->unban_country($_GET['id']);
                    echo '<div class="updated fade"><p><strong>Success!</strong> The selected country has been unbanned.</p></div>';
                }
            }
        }

        # Prepare the tables
        # Standard Bans
        $BansTableStandard = new BansTable();
        $BansTableStandard->prepare_items();

        # Country Bans
        $BansTableCountry = new BansTable('country');
        $BansTableCountry->prepare_items();

        require_once cybercure_security_WORDPRESS_PLUGIN_BASE_PATH . 'templates/admin/bans.php';
    }

    public function detected_attacks()
    {
        # Should we delete a entry from the log
        if(isset($_GET['delete']) && is_numeric($_GET['delete'])) {
            $Protect = new Protect();
            $Protect->delete_from_log($_GET['type'], $_GET['delete']);
            if(isset($_GET['detail']) && $_GET['detail'] == 'full' &&
                isset($_GET['ip']) && !empty($_GET['ip'])) {
//                $msg->add('s', '<strong>Success!</strong> The selected data has been deleted from the logs.', 'log.php?type=' . $type . '&detail=full&ip=' . $_GET['ip']);
            } else {
                echo '<div class="updated fade"><p><strong>Success!</strong> The selected entry has been deleted from the logs.</p></div>';
            }
        }

        require_once cybercure_security_WORDPRESS_PLUGIN_BASE_PATH . 'templates/admin/detected_attacks.php';

        $AdminTable = new LogTable();
        echo "<div class='wrap'><h2>Cybercure - Detected Attacks</h2><hr/>";
        $AdminTable->prepare_items();
        $AdminTable->display();
        echo "</div>";
    }

    public function settings()
    {
        # Should we update the settings
        if(isset($_POST['update'])) {
            foreach($_POST as $key => $value) {
                if(isset($this->config['application'][$key])) {
                    # If it is a valid config key
                    $this->update_config($key, $value);
                }
            }

            # Fetch the updated config
            $this->config = $this->fetch_config();

            echo '<div class="updated fade"><p><strong>Success!</strong> The settings have been saved.</p></div>';
        }

        # Show the form
        require_once cybercure_security_WORDPRESS_PLUGIN_BASE_PATH . 'templates/admin/settings.php';
    }
}