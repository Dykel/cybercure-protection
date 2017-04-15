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


/**
* Curl class
*
* This class is just a wrapper for the curl function which
* is used to perform http requests
*
* @package Cybercure/Cybercure
* @author Cybercure <dykelaway@gmail.com>
* @copyright Copyright (c) 2017 by Cybercure Project Team
*/

class Curl
{

    private $ch = null;
	private $ref = "";
	public $result = "";

	public function __construct() 
	{
		$this->ch = curl_init();
	}
 
	public function __destruct() 
	{
		curl_close($this->ch);
	}
 
	public function close() 
	{
		curl_close($this->ch);
	}
	
	public function post($url, $data) 
	{
		$this->result = "";
		curl_setopt($this->ch, CURLOPT_URL, $url);
		curl_setopt($this->ch, CURLOPT_POST, 1);
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($data));
		$this->refer($url);
		$this->setopts();
		ob_start();  
		$this->result = curl_exec($this->ch);
		ob_end_clean();
	}
 
	public function get($url) 
	{
		$this->result = "";
		curl_setopt($this->ch, CURLOPT_URL, $url);
		$this->refer($url);
		$this->setopts();
		ob_start();  
		$this->result = curl_exec($this->ch);
		ob_end_clean();
	}
 
	private function refer($url) 
	{
		curl_setopt ($this->ch, CURLOPT_REFERER, $this->ref);
		$this->ref = $url;
	}
 
	private function setopts() 
	{
		If (ini_get('open_basedir') == false && ini_get('safe_mode') == false) {
			curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1);
		}
		else {
			curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 0);
		}
                
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, 18);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 18);

		curl_setopt($this->ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:25.0) Gecko/20100101 Firefox/25.0");
		curl_setopt($this->ch, CURLOPT_COOKIEJAR, "cookie");
		curl_setopt($this->ch, CURLOPT_COOKIEFILE, "cookie");
                
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
    }
}

?>