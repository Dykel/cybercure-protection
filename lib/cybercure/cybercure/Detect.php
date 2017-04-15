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

class Detect {

    private $user_agent;

    public function __construct()
    {
        $this->init();
    }

    private function init()
    {
        $this->user_agent = $_SERVER['HTTP_USER_AGENT'];
    }

    /**
     * OS
     *
     * Detect the current user´s operating system
     *
     * @return  array
     */
    public function OS()
    {
        # Get the user agent
        $this->user_agent = $_SERVER['HTTP_USER_AGENT'];

        # List with the strings which represent
        # the os
        $os_data = array (
            'Windows 3.11' => 'windows|Win16',
            'Windows 95' => 'windows|Windows 95|Win95|Windows_95',
            'Windows 98' => 'windows|Windows 98|Win98',
            'Windows 2000' => 'windows|Windows NT 5.0|Windows 2000',
            'Windows XP' => 'windows|Windows NT 5.1|Windows XP',
            'Windows 2003' => 'windows|Windows NT 5.2',
            'Windows Vista' => 'windows|Windows NT 6.0|Windows Vista',
            'Windows 7' => 'windows|Windows NT 6.1|Windows 7',
            'Windows 8' => 'windows|Windows NT 6.2|Windows 8',
            'Windows 8.1' => 'windows|Windows NT 6.3|Windows 8.1',
            'Windows NT 4.0' => 'windows|Windows NT 4.0|WinNT4.0|WinNT|Windows NT',
            'Windows ME' => 'windows|Windows ME',
            'Open BSD'=>'openbsd|OpenBSD',
            'Sun OS'=>'unknown|SunOS',
            'Linux'=>'linux|Linux|X11',
            'Macintosh'=>'apple|Mac_PowerPC|Macintosh',
            'iPad'=>'apple|iPad',
            'iPhone'=>'apple|iPhone',
            'Safari' => 'unknown|Safari',
            'QNX'=>'unknown|QNX',
            'BeOS'=>'unknown|BeOS',
            'OS/2'=>'unknown|OS/2',
            'Search Bot'=>'unknown|nuhk|Googlebot|Yammybot|Openbot|Slurp/cat|msnbot|ia_archiver'
        );

        # Check if one of the above strings
        # matches the client´s user agent
        $return = array('name' => 'Unknown', 'code' => 'unknown');

        foreach($os_data as $os => $pattern) {
            $patterns_exploded = explode('|', $pattern);

            $i_pattern = 0;
            foreach($patterns_exploded as $pattern_exploded) {
                if($i_pattern > 0) {
                    if(strpos($this->user_agent, $pattern_exploded) !== FALSE) {
                        # OS has been found
                        $os_name = $os;
                        $return = array('name' => $os_name, 'code' => $patterns_exploded[0]);
                        return $return;
                    }
                }
                $i_pattern ++;
            }
        }

        return $return;
    }

    /**
     * Browser
     *
     * Detect the current user´s browser
     *
     * @return  array
     */
    public function Browser()
    {
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version = $ub = "";

        # First get the platform
        if (preg_match('/linux/i', $this->user_agent)) {
            $platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $this->user_agent)) {
            $platform = 'mac';
        } elseif (preg_match('/windows|win32/i', $this->user_agent)) {
            $platform = 'windows';
        }

        # Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/Trident/i', $this->user_agent) && !preg_match('/Opera/i',$this->user_agent)) {
            if(preg_match('/MSIE/i', $this->user_agent)) {
                if(preg_match('/chromeframe/i', $this->user_agent)) {
                    $bname = 'IE with Chrome Frame';
                    $ub = "chromeframe";
                } else {
                    $bname = 'Internet Explorer';
                    $ub = "MSIE";
                }
            } else {
                $bname = 'Internet Explorer';
                $ub = "MSIE";
            }
        } elseif(preg_match('/Firefox/i',$this->user_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        } elseif(preg_match('/Chrome/i',$this->user_agent)) {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        } elseif(preg_match('/Safari/i',$this->user_agent)) {
            $bname = 'Apple Safari';
            $ub = "Safari";
        } elseif(preg_match('/Opera/i',$this->user_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        } elseif(preg_match('/Netscape/i',$this->user_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        # finally get the correct version number
        # Only for IE > 10 (not a cosmetic code !)
        if(preg_match('/Trident/i', $this->user_agent) && !preg_match('/MSIE/i', $this->user_agent)) {
            $pattern = '/Trident\/.*rv:([0-9]{1,}[\.0-9.]{0,})/';
            if(preg_match($pattern, $this->user_agent, $matches) AND isset($matches[1])) {
                $version = $matches[1];
            }
        } else {
            $known = array('Version', $ub, 'other');
            $pattern = '#(?<browser>' . join('|', $known) .
                ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
            if(preg_match_all($pattern, $this->user_agent, $matches)) {
                # see how many we have
                $i = count($matches['browser']); // we have matching
                if ($i != 1) {
                    # we will have two since we are not using 'other' argument yet
                    # see if version is before or after the name
                    if (strripos($this->user_agent,"Version") < strripos($this->user_agent,$ub)){
                        $version= $matches['version'][0];
                    } else {
                        $version= isset($matches['version'][1])?$matches['version'][1]:'';
                    }
                } else {
                    $version= $matches['version'][0];
                }
            }
        }

        # Check if we have a version number
        if ($version==null || $version=="") {
            $version="?";
        }

        $info = array(
            'name' => $bname,
            'version' => $version,
            'platform' => $platform,
            'code' => strtolower($ub),
            'userAgent' => $this->user_agent,
            'pattern' => $pattern
        );

        return $info;
    }

    /**
     * Location
     *
     * Detect the current user´s location
     *
     * @return  array
     */
    public function Location()
    {

        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];
        $result  = "Unknown";
        if(filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif(filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));

        if($ip_data && $ip_data->geoplugin_countryName != null && $ip_data->geoplugin_city != null && $ip_data->geoplugin_countryCode != null ) {
            $result = array('name' => $ip_data->geoplugin_countryName . ' (' . $ip_data->geoplugin_city . ')', 'country_code' => $ip_data->geoplugin_countryCode);
        } elseif($ip_data && $ip_data->geoplugin_countryName != null && $ip_data->geoplugin_countryCode != null) {
            $result = array('name' => $ip_data->geoplugin_countryName, 'country_code' => $ip_data->geoplugin_countryCode);
        } else {
            $result = array('name' => 'Unknown', 'country_code' => 'Unknown');
        }

        return $result;
    }
}