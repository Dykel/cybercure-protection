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

class WhatIsMyIPAddress
{
    public function __construct($curl)
    {
        $this->curl = $curl;
        $this->detect_proxy_url = 'http://whatismyipaddress.com/ip/';
    }


    /**
     * Detect proxy
     *
     * Detects a proxy/VPN
     *
     * @param  String         $ip                IP we want to check
     * @return boolean
     *
     */
    public function detect_proxy($ip)
    {
        $url = $this->detect_proxy_url . $ip;

        $this->curl->get($url);
        $result = $this->curl->result;

        if(preg_match_all('/([nN]etwork [sS]haring [dD]evice [oO]r [pP]roxy [sS]erver|[sS]uspected [pP]roxy [sS]erver|[cC]onfirmed [pP]roxy [sS]erver|[oO]pen [pP]roxy [sS]erver|[tT]or [eE]xit [nN]ode)/', $result, $matches)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}

?>