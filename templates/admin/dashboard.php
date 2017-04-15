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
        Cybercure - Dashboard
    </h2>
    <hr/>

    <div id="tabs">
        <ul>
            <li><a href="#tabs-statistics">Statistics</a></li>
            <li><a href="#tabs-protection-settings">Protection Status</a></li>
            <li><a href="#tabs-logging-settings">Logging Status</a></li>
            <li><a href="#tabs-auto-ban-settings">Auto-Ban Status</a></li>
        </ul>

        <div id="tabs-statistics">
            <table class="wp-list-table widefat fixed posts">
                <thead>
                <tr>
                    <th>Time Frame</th>
                    <th>Attacks</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1 Day</td>
                        <td>
                            <span class="label"><?php echo $stats['day'] ?></span>
                        </td>
                    </tr>

                    <tr>
                        <td>1 Week</td>
                        <td>
                            <span class="label"><?php echo $stats['week'] ?></span>
                        </td>
                    </tr>

                    <tr>
                        <td>1 Month</td>
                        <td>
                            <span class="label"><?php echo $stats['month'] ?></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="tabs-protection-settings">
            <table class="wp-list-table widefat fixed posts">
                <thead>
                <tr>
                    <th>Type</th>
                    <th>Value</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Mass requests (DDos)</td>
                        <td>
                            <?php
                            if($this->config['application']['protection_mass_requests'] == TRUE) {
                                echo '<span class="label label-success">Active</span>';
                            } else {
                                echo '<span class="label label-danger">Inactive</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Proxies & VPN</td>
                        <td>
                            <?php
                            if($this->config['application']['protection_proxies'] == TRUE) {
                                echo '<span class="label label-success">Active</span>';
                            } else {
                                echo '<span class="label label-danger">Inactive</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>SQL Injections</td>
                        <td>
                            <?php
                            if($this->config['application']['protection_sql_injections'] == TRUE) {
                                echo '<span class="label label-success">Active</span>';
                            } else {
                                echo '<span class="label label-danger">Inactive</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>XSS Attacks</td>
                        <td>
                            <?php
                            if($this->config['application']['protection_xss_attacks'] == TRUE) {
                                echo '<span class="label label-success">Active</span>';
                            } else {
                                echo '<span class="label label-danger">Inactive</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Spammers</td>
                        <td>
                            <?php
                            if($this->config['application']['protection_spammers'] == TRUE) {
                                echo '<span class="label label-success">Active</span>';
                            } else {
                                echo '<span class="label label-danger">Inactive</span>';
                            }
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="tabs-logging-settings">
            <table class="wp-list-table widefat fixed posts">
                <thead>
                <tr>
                    <th>Type</th>
                    <th>Value</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Mass requests (DDos)</td>
                        <td>
                            <?php
                            if($this->config['application']['log_mass_requests'] == TRUE) {
                                echo '<span class="label label-success">Active</span>';
                            } else {
                                echo '<span class="label label-danger">Inactive</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Proxies & VPN</td>
                        <td>
                            <?php
                            if($this->config['application']['log_proxies'] == TRUE) {
                                echo '<span class="label label-success">Active</span>';
                            } else {
                                echo '<span class="label label-danger">Inactive</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>SQL Injections</td>
                        <td>
                            <?php
                            if($this->config['application']['log_sql_injections'] == TRUE) {
                                echo '<span class="label label-success">Active</span>';
                            } else {
                                echo '<span class="label label-danger">Inactive</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>XSS Attacks</td>
                        <td>
                            <?php
                            if($this->config['application']['log_xss_attacks'] == TRUE) {
                                echo '<span class="label label-success">Active</span>';
                            } else {
                                echo '<span class="label label-danger">Inactive</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Spammers</td>
                        <td>
                            <?php
                            if($this->config['application']['log_spammers'] == TRUE) {
                                echo '<span class="label label-success">Active</span>';
                            } else {
                                echo '<span class="label label-danger">Inactive</span>';
                            }
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="tabs-auto-ban-settings">
            <table class="wp-list-table widefat fixed posts">
                <thead>
                <tr>
                    <th>Type</th>
                    <th>Value</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Mass requests (DDos)</td>
                        <td>
                            <?php
                            if($this->config['application']['auto_ban_ip_mass_requests'] == TRUE) {
                                echo '<span class="label label-success">Active</span>';
                            } else {
                                echo '<span class="label label-danger">Inactive</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Proxies & VPN</td>
                        <td>
                            <?php
                            if($this->config['application']['auto_ban_ip_proxies'] == TRUE) {
                                echo '<span class="label label-success">Active</span>';
                            } else {
                                echo '<span class="label label-danger">Inactive</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>SQL Injections</td>
                        <td>
                            <?php
                            if($this->config['application']['auto_ban_ip_sql_injections'] == TRUE) {
                                echo '<span class="label label-success">Active</span>';
                            } else {
                                echo '<span class="label label-danger">Inactive</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>XSS Attacks</td>
                        <td>
                            <?php
                            if($this->config['application']['auto_ban_ip_xss_attacks'] == TRUE) {
                                echo '<span class="label label-success">Active</span>';
                            } else {
                                echo '<span class="label label-danger">Inactive</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Spammers</td>
                        <td>
                            <?php
                            if($this->config['application']['auto_ban_ip_spammers'] == TRUE) {
                                echo '<span class="label label-success">Active</span>';
                            } else {
                                echo '<span class="label label-danger">Inactive</span>';
                            }
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>