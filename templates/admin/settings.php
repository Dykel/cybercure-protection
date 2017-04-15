<?php
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
?>

<div class="wrap">
    <h2>
        Cybercure - Settings
    </h2>
    <hr/>

    <form method="post">
        <div id="tabs">
            <ul>
                <li><a href="#tabs-protection-outer">Protection</a></li>
                <li><a href="#tabs-accounts-outer">Accounts</a></li>
                <li><a href="#tabs-wordpress-outer"> #RISK[Solutions]Maurice</a></li>
            </ul>

            <div id="tabs-accounts-outer">
                <div id="tabs-accounts-inner">
                    <ul>
                        <li><a href="#accounts-project-honeypot">Project Honeypot</a></li>
                    </ul>
                    <div id="accounts-project-honeypot">
                        <table class="form-table">
                            <tbody>
                            <tr class="form-field">
                                <th valign="top" scope="row">
                                    <label for="project_honeypot_api_key">API Key</label>
                                    <hr/>
                                </th>
                                <td>
                                    <input type="text" style="width: 400px;" value="<?php echo $this->config['application']['project_honeypot_api_key'] ?>" id="project_honeypot_api_key" name="project_honeypot_api_key">
                                    <hr/>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="tabs-protection-outer">
                <div id="tabs-protection-inner">
                    <ul>
                        <li><a href="#proxy">Proxy/VPN</a></li>
                        <li><a href="#spammer">Spammer</a></li>
                        <li><a href="#mass_request">Mass requests</a></li>
                        <li><a href="#sql_injection">SQL injection</a></li>
                        <li><a href="#xss_attack">XSS attack</a></li>
                    </ul>
                    <div id="proxy">
                        <table class="form-table">
                            <tbody>
                                <tr class="form-field">
                                    <th valign="top" scope="row">
                                        <label for="redirect_proxies">Redirection URL</label>
                                        <hr/>
                                    </th>
                                    <td>
                                        <input type="text" style="width: 400px;" value="<?php echo $this->config['application']['redirect_proxies'] ?>" id="redirect_proxies" name="redirect_proxies">
                                        <hr/>
                                    </td>
                                </tr>
                                <tr class="form-field">
                                    <th valign="top" scope="row">
                                        <label for="auto_ban_ip_proxies">Auto Ban for (minutes)</label>
                                        <hr/>
                                    </th>
                                    <td>
                                        <input type="number" id="auto_ban_ip_proxies" name="auto_ban_ip_proxies" style="width: 85px;" value="<?php echo $this->config['application']['auto_ban_ip_proxies'] ?>">
                                        <hr/>
                                    </td>
                                </tr>
                                <tr class="form-field">
                                    <th valign="top" scope="row">
                                        <label for="protection_proxies"> Enable protection</label>
                                        <hr/>
                                    </th>
                                    <td>
                                        <div class="switch-wrapper">
                                            <input type="hidden" name="protection_proxies" value="0">
                                            <input type="checkbox" class="switchButton" value="1" id="protection_proxies" name="protection_proxies" <?php if($this->config['application']['protection_proxies'] == 1) echo 'checked' ?>>
                                        </div>
                                        <hr/>
                                    </td>
                                </tr>
                                <tr class="form-field">
                                    <th valign="top" scope="row">
                                        <label for="log_proxies"> Enable logging</label>
                                        <hr/>
                                    </th>
                                    <td>
                                        <div class="switch-wrapper">
                                            <input type="hidden" name="log_proxies" value="0">
                                            <input type="checkbox" class="switchButton" value="1" id="log_proxies" name="log_proxies" <?php if($this->config['application']['log_proxies'] == 1) echo 'checked' ?>>
                                        </div>
                                        <hr/>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="spammer">
                        <table class="form-table">
                            <tbody>
                            <tr class="form-field">
                                <th valign="top" scope="row">
                                    <label for="redirect_proxies">Redirection URL</label>
                                    <hr/>
                                </th>
                                <td>
                                    <input type="text" style="width: 400px;" value="<?php echo $this->config['application']['redirect_spammers'] ?>" id="redirect_spammers" name="redirect_spammers">
                                    <hr/>
                                </td>
                            </tr>
                            <tr class="form-field">
                                <th valign="top" scope="row">
                                    <label for="auto_ban_ip_proxies">Auto Ban for (minutes)</label>
                                    <hr/>
                                </th>
                                <td>
                                    <input type="number" id="auto_ban_ip_spammers" name="auto_ban_ip_spammers" style="width: 85px;" value="<?php echo $this->config['application']['auto_ban_ip_spammers'] ?>">
                                    <hr/>
                                </td>
                            </tr>
                            <tr class="form-field">
                                <th valign="top" scope="row">
                                    <label for="protection_proxies"> Enable protection</label>
                                    <hr/>
                                </th>
                                <td>
                                    <div class="switch-wrapper">
                                        <input type="hidden" name="protection_spammers" value="0">
                                        <input type="checkbox" class="switchButton" value="1" id="protection_spammers" name="protection_spammers" <?php if($this->config['application']['protection_spammers'] == 1) echo 'checked' ?>>
                                    </div>
                                    <hr/>
                                </td>
                            </tr>
                            <tr class="form-field">
                                <th valign="top" scope="row">
                                    <label for="log_proxies"> Enable logging</label>
                                    <hr/>
                                </th>
                                <td>
                                    <div class="switch-wrapper">
                                        <input type="hidden" name="log_spammers" value="0">
                                        <input type="checkbox" class="switchButton" value="1" id="log_spammers" name="log_spammers" <?php if($this->config['application']['log_spammers'] == 1) echo 'checked' ?>>
                                    </div>
                                    <hr/>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="mass_request">
                        <table class="form-table">
                            <tbody>
                            <tr class="form-field">
                                <th valign="top" scope="row">
                                    <label for="redirect_proxies">Redirection URL</label>
                                    <hr/>
                                </th>
                                <td>
                                    <input type="text" style="width: 400px;" value="<?php echo $this->config['application']['redirect_mass_requests'] ?>" id="redirect_mass_requests" name="redirect_mass_requests">
                                    <hr/>
                                </td>
                            </tr>
                            <tr class="form-field">
                                <th valign="top" scope="row">
                                    <label for="auto_ban_ip_proxies">Auto Ban for (minutes)</label>
                                    <hr/>
                                </th>
                                <td>
                                    <input type="number" id="auto_ban_ip_mass_requests" name="auto_ban_ip_mass_requests" style="width: 85px;" value="<?php echo $this->config['application']['auto_ban_ip_mass_requests'] ?>">
                                    <hr/>
                                </td>
                            </tr>
                            <tr class="form-field">
                                <th valign="top" scope="row">
                                    <label for="protection_proxies"> Enable protection</label>
                                    <hr/>
                                </th>
                                <td>
                                    <div class="switch-wrapper">
                                        <input type="hidden" name="protection_mass_requests" value="0">
                                        <input type="checkbox" class="switchButton" value="1" id="protection_mass_requests" name="protection_mass_requests" <?php if($this->config['application']['protection_mass_requests'] == 1) echo 'checked' ?>>
                                    </div>
                                    <hr/>
                                </td>
                            </tr>
                            <tr class="form-field">
                                <th valign="top" scope="row">
                                    <label for="log_proxies"> Enable logging</label>
                                    <hr/>
                                </th>
                                <td>
                                    <div class="switch-wrapper">
                                        <input type="hidden" name="log_mass_requests" value="0">
                                        <input type="checkbox" class="switchButton" value="1" id="log_mass_requests" name="log_mass_requests" <?php if($this->config['application']['log_mass_requests'] == 1) echo 'checked' ?>>
                                    </div>
                                    <hr/>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="sql_injection">
                        <table class="form-table">
                            <tbody>
                            <tr class="form-field">
                                <th valign="top" scope="row">
                                    <label for="redirect_proxies">Redirection URL</label>
                                    <hr/>
                                </th>
                                <td>
                                    <input type="text" style="width: 400px;" value="<?php echo $this->config['application']['redirect_sql_injections'] ?>" id="redirect_sql_injections" name="redirect_sql_injections">
                                    <hr/>
                                </td>
                            </tr>
                            <tr class="form-field">
                                <th valign="top" scope="row">
                                    <label for="auto_ban_ip_proxies">Auto Ban for (minutes)</label>
                                    <hr/>
                                </th>
                                <td>
                                    <input type="number" id="auto_ban_ip_sql_injections" name="auto_ban_ip_sql_injections" style="width: 85px;" value="<?php echo $this->config['application']['auto_ban_ip_sql_injections'] ?>">
                                    <hr/>
                                </td>
                            </tr>
                            <tr class="form-field">
                                <th valign="top" scope="row">
                                    <label for="protection_proxies"> Enable protection</label>
                                    <hr/>
                                </th>
                                <td>
                                    <div class="switch-wrapper">
                                        <input type="hidden" name="protection_sql_injections" value="0">
                                        <input type="checkbox" class="switchButton" value="1" id="protection_sql_injections" name="protection_sql_injections" <?php if($this->config['application']['protection_sql_injections'] == 1) echo 'checked' ?>>
                                    </div>
                                    <hr/>
                                </td>
                            </tr>
                            <tr class="form-field">
                                <th valign="top" scope="row">
                                    <label for="log_proxies"> Enable logging</label>
                                    <hr/>
                                </th>
                                <td>
                                    <div class="switch-wrapper">
                                        <input type="hidden" name="log_sql_injections" value="0">
                                        <input type="checkbox" class="switchButton" value="1" id="log_sql_injections" name="log_sql_injections" <?php if($this->config['application']['log_sql_injections'] == 1) echo 'checked' ?>>
                                    </div>
                                    <hr/>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="xss_attack">
                        <table class="form-table">
                            <tbody>
                            <tr class="form-field">
                                <th valign="top" scope="row">
                                    <label for="redirect_proxies">Redirection URL</label>
                                    <hr/>
                                </th>
                                <td>
                                    <input type="text" style="width: 400px;" value="<?php echo $this->config['application']['redirect_xss_attacks'] ?>" id="redirect_xss_attacks" name="redirect_xss_attacks">
                                    <hr/>
                                </td>
                            </tr>
                            <tr class="form-field">
                                <th valign="top" scope="row">
                                    <label for="auto_ban_ip_proxies">Auto Ban for (minutes)</label>
                                    <hr/>
                                </th>
                                <td>
                                    <input type="number" id="auto_ban_ip_xss_attacks" name="auto_ban_ip_xss_attacks" style="width: 85px;" value="<?php echo $this->config['application']['auto_ban_ip_xss_attacks'] ?>">
                                    <hr/>
                                </td>
                            </tr>
                            <tr class="form-field">
                                <th valign="top" scope="row">
                                    <label for="protection_proxies"> Enable protection</label>
                                    <hr/>
                                </th>
                                <td>
                                    <div class="switch-wrapper">
                                        <input type="hidden" name="protection_xss_attacks" value="0">
                                        <input type="checkbox" class="switchButton" value="1" id="protection_xss_attacks" name="protection_xss_attacks" <?php if($this->config['application']['protection_xss_attacks'] == 1) echo 'checked' ?>>
                                    </div>
                                    <hr/>
                                </td>
                            </tr>
                            <tr class="form-field">
                                <th valign="top" scope="row">
                                    <label for="log_proxies"> Enable logging</label>
                                    <hr/>
                                </th>
                                <td>
                                    <div class="switch-wrapper">
                                        <input type="hidden" name="log_xss_attacks" value="0">
                                        <input type="checkbox" class="switchButton" value="1" id="log_xss_attacks" name="log_xss_attacks" <?php if($this->config['application']['log_xss_attacks'] == 1) echo 'checked' ?>>
                                    </div>
                                    <hr/>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="tabs-wordpress-outer">
                <div id="tabs-wordpress-inner">
                    <ul>
                        <li><a href="#wordpress-login">Login</a></li>
                        <li><a href="#wordpress-admin">Admin</a></li>
                    </ul>
                    <div id="wordpress-login">
                        <table class="form-table">
                            <tbody>
                            <tr class="form-field">
                                <th valign="top" scope="row">
                                    <label id="hide_wp_login_key_desc" for="hide_wp_login_key">Login Access Key</label><br/>
                                    <span class="description" for="hide_wp_login_key_desc">Leave empty to disable</span>
                                    <hr/>
                                </th>
                                <td>
                                    <input type="text" style="width: 400px;" value="<?php echo $this->config['application']['hide_wp_login_key'] ?>" id="hide_wp_login_key" name="hide_wp_login_key"><br/>
                                    <span class="description" for="hide_wp_login_key">e.g. 1234 will become wp-login.php?cybercure-security-pro=1234</span>
                                    <hr/>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="wordpress-admin">
                        <table class="form-table">
                            <tbody>
                            <tr class="form-field">
                                <th valign="top" scope="row">
                                    <label for="project_honeypot_api_key">Hide Admin Area</label><br/>
                                    &nbsp;
                                    <hr/>
                                </th>
                                <td>
                                    <div class="switch-wrapper">
                                        <input type="hidden" name="hide_wp_admin" value="0">
                                        <input type="checkbox" class="switchButton" value="1" id="hide_wp_admin" name="hide_wp_admin" <?php if($this->config['application']['hide_wp_admin'] == 1) echo 'checked' ?>>
                                        <span class="description" for="hide_wp_admin">Guests will get a 404 (Not Found) when trying to access the admin area</span>
                                    </div>
                                    <hr/>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <p class="submit">
            <input class="button-primary" type="submit" name="update" value="Save Settings">
        </p>
    </form>
</div>

<script>
    $j=jQuery.noConflict();
    $j(function() {
        $j( "#tabs" ).tabs();
        $j( "#tabs-accounts-inner" ).tabs();
        $j( "#tabs-protection-inner" ).tabs();
        $j( "#tabs-wordpress-inner" ).tabs();
    });
</script>