-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2017 at 08:02 PM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ccure`
--

-- --------------------------------------------------------

--
-- Table structure for table `ccure_bans`
--

CREATE TABLE `ccure_bans` (
  `id` int(11) NOT NULL,
  `ip` char(15) COLLATE utf8_unicode_ci NOT NULL,
  `date` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `time` char(5) COLLATE utf8_unicode_ci NOT NULL,
  `reason` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `redirect` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `autoban` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ccure_bans`
--

INSERT INTO `ccure_bans` (`id`, `ip`, `date`, `time`, `reason`, `redirect`, `url`, `autoban`) VALUES
(1, '197.227.26.44', '16 April 2017', '03:27', 'akoz to pilon gogot', 'Yes', 'https://www.facebook.com', 'No');

-- --------------------------------------------------------

--
-- Table structure for table `ccure_bans-country`
--

CREATE TABLE `ccure_bans-country` (
  `id` int(11) NOT NULL,
  `country` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `redirect` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Banned countries table';

-- --------------------------------------------------------

--
-- Table structure for table `ccure_bans-other`
--

CREATE TABLE `ccure_bans-other` (
  `id` int(11) NOT NULL,
  `type` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ccure_content-protection`
--

CREATE TABLE `ccure_content-protection` (
  `id` int(11) NOT NULL,
  `function` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `alert` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  `message` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ccure_content-protection`
--

INSERT INTO `ccure_content-protection` (`id`, `function`, `enabled`, `alert`, `message`) VALUES
(1, 'rightclick', 'Yes', 'Yes', 'Context Menu not allowed'),
(2, 'rightclick_images', 'Yes', 'Yes', 'Context Menu on Images not allowed'),
(3, 'cut', 'Yes', 'Yes', 'Cut not allowed'),
(4, 'copy', 'Yes', 'Yes', 'Copy not allowed'),
(5, 'paste', 'Yes', 'Yes', 'Paste not allowed'),
(6, 'drag', 'Yes', 'No', ''),
(7, 'drop', 'Yes', 'No', ''),
(8, 'printscreen', 'Yes', 'Yes', 'It is not allowed to use the Print Screen button'),
(9, 'print', 'Yes', 'Yes', 'It is not allowed to Print'),
(10, 'view_source', 'Yes', 'Yes', 'Source code protected by Cybercure'),
(11, 'offline_mode', 'Yes', 'Yes', 'You have no access to save the page'),
(12, 'iframe_out', 'Yes', 'No', ''),
(13, 'exit_confirmation', 'Yes', 'Yes', 'Do you really want to exit our website?'),
(14, 'selecting', 'Yes', 'No', '');

-- --------------------------------------------------------

--
-- Table structure for table `ccure_dnsbl-databases`
--

CREATE TABLE `ccure_dnsbl-databases` (
  `id` int(11) NOT NULL,
  `database` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ccure_dnsbl-databases`
--

INSERT INTO `ccure_dnsbl-databases` (`id`, `database`) VALUES
(1, 'sbl.spamhaus.org'),
(2, 'xbl.spamhaus.org');

-- --------------------------------------------------------

--
-- Table structure for table `ccure_ip-whitelist`
--

CREATE TABLE `ccure_ip-whitelist` (
  `id` int(11) NOT NULL,
  `ip` char(15) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ccure_logs`
--

CREATE TABLE `ccure_logs` (
  `id` int(11) NOT NULL,
  `ip` char(15) COLLATE utf8_unicode_ci NOT NULL,
  `date` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `time` char(5) COLLATE utf8_unicode_ci NOT NULL,
  `page` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `query` text COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `browser` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Unknown',
  `browser_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `os` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Unknown',
  `os_code` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(120) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Unknown',
  `country_code` char(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'XX',
  `region` varchar(120) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Unknown',
  `city` varchar(120) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Unknown',
  `latitude` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `longitude` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `isp` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Unknown',
  `useragent` text COLLATE utf8_unicode_ci NOT NULL,
  `referer_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ccure_logs`
--

INSERT INTO `ccure_logs` (`id`, `ip`, `date`, `time`, `page`, `query`, `type`, `browser`, `browser_code`, `os`, `os_code`, `country`, `country_code`, `region`, `city`, `latitude`, `longitude`, `isp`, `useragent`, `referer_url`) VALUES
(1, '196.192.68.241', '16 April 2017', '04:53', '/index.php', 'id=1%20gender%201%2C2%2C3%2C4%2C5%2C6--', 'SQLi', 'Google Chrome 57.0.2987.133', 'chrome', 'Windows 7 x64', 'win-4', 'Mauritius', 'MU', 'Port Louis District', 'Port Louis', '-20.1653', '57.4964', 'DCL', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36', '');

-- --------------------------------------------------------

--
-- Table structure for table `ccure_malwarescanner-settings`
--

CREATE TABLE `ccure_malwarescanner-settings` (
  `id` int(11) NOT NULL,
  `file-extensions` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'php|php3|php4|php5|phps|htm|html|htaccess|js',
  `ignored-dirs` text COLLATE utf8_unicode_ci NOT NULL,
  `scan-dir` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '../'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ccure_malwarescanner-settings`
--

INSERT INTO `ccure_malwarescanner-settings` (`id`, `file-extensions`, `ignored-dirs`, `scan-dir`) VALUES
(1, 'php|phtml|php3|php4|php5|phps|htaccess|txt|gif', '.|..|.DS_Store|.svn|.git', '../');

-- --------------------------------------------------------

--
-- Table structure for table `ccure_massrequests-settings`
--

CREATE TABLE `ccure_massrequests-settings` (
  `id` int(11) NOT NULL,
  `protection` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  `logging` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  `autoban` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `redirect` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pages/mass-requests',
  `mail` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ccure_massrequests-settings`
--

INSERT INTO `ccure_massrequests-settings` (`id`, `protection`, `logging`, `autoban`, `redirect`, `mail`) VALUES
(1, 'Yes', 'Yes', 'Yes', 'http://cybercure.ngrok.io/cybercure/pages/mass-requests', 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `ccure_monitoring`
--

CREATE TABLE `ccure_monitoring` (
  `id` int(11) NOT NULL,
  `domain` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ccure_monitoring`
--

INSERT INTO `ccure_monitoring` (`id`, `domain`, `url`) VALUES
(1, 'http://cybercure.ngrok.io/', 'http://cybercure.ngrok.io/');

-- --------------------------------------------------------

--
-- Table structure for table `ccure_optimization-settings`
--

CREATE TABLE `ccure_optimization-settings` (
  `id` int(11) NOT NULL,
  `html-minify` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ccure_optimization-settings`
--

INSERT INTO `ccure_optimization-settings` (`id`, `html-minify`) VALUES
(1, 'No');

-- --------------------------------------------------------

--
-- Table structure for table `ccure_pages-layolt`
--

CREATE TABLE `ccure_pages-layolt` (
  `id` int(11) NOT NULL,
  `page` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ccure_pages-layolt`
--

INSERT INTO `ccure_pages-layolt` (`id`, `page`, `text`, `image`) VALUES
(1, 'Banned', 'You are banned and you cannot continue to the site', 'http://cybercure.ngrok.io/cybercure/assets/img/banned.png'),
(2, 'Blocked', 'Une attaque a &eacute;t&eacute; d&eacute;tect&eacute;e', 'http://cybercure.ngrok.io/cybercure/assets/img/hacker.png'),
(3, 'Mass_Requests', 'Attention, you performed too many connections', 'http://cybercure.ngrok.io/cybercure/assets/img/mass-requests.png'),
(4, 'Proxy', 'Access to the website via Proxy is not allowed (Disable Chrome Data Compression if you have it enabled)', 'http://cybercure.ngrok.io/cybercure/assets/img/proxy.png'),
(5, 'Spam', 'You are in the Blacklist of Spammers and you cannot continue to the website', 'http://cybercure.ngrok.io/cybercure/assets/img/spam.png'),
(6, 'Tor', 'We detected that you are using Tor', 'http://cybercure.ngrok.io/cybercure/assets/img/tor.png'),
(7, 'Banned_Country', 'Sorry, but your country is banned and you cannot continue to the website', 'http://cybercure.ngrok.io/cybercure/assets/img/blocked-country.png'),
(8, 'Blocked_Browser', 'L&amp;#39;acc&egrave;s au site Web via votre navigateur n&amp;#39;est pas autoris&eacute;, utilisez un autre navigateur Internet', 'http://cybercure.ngrok.io/cybercure/assets/img/blocked-browser.png'),
(9, 'Blocked_OS', 'L&amp;#39;acc&egrave;s au site Web via votre syst&egrave;me d&amp;#39;exploitation n&amp;#39;est pas autoris&eacute;', 'http://cybercure.ngrok.io/cybercure/assets/img/blocked-os.png'),
(10, 'Blocked_ISP', 'Your Internet Service Provider is blacklisted and you cannot continue to the website', 'http://cybercure.ngrok.io/cybercure/assets/img/blocked-isp.png'),
(11, 'Bad_Bot', 'Vous avez &eacute;t&eacute; identifi&eacute; comme un Bad Bot et vous ne pouvez pas continuer sur le site', ''),
(12, 'Fake_Bot', 'You were identified as a Fake Bot and you cannot continue to the website', ''),
(13, 'Tor', 'We detected that you are using Tor', 'http://cybercure.ngrok.io/cybercure/assets/img/tor.png');

-- --------------------------------------------------------

--
-- Table structure for table `ccure_proxy-settings`
--

CREATE TABLE `ccure_proxy-settings` (
  `id` int(11) NOT NULL,
  `protection` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  `protection2` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  `protection3` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `logging` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  `autoban` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `redirect` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'http://cybercure.ngrok.io/cybercure/pages/proxy',
  `mail` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ccure_proxy-settings`
--

INSERT INTO `ccure_proxy-settings` (`id`, `protection`, `protection2`, `protection3`, `logging`, `autoban`, `redirect`, `mail`) VALUES
(1, 'No', 'No', 'No', 'Yes', 'Yes', 'http://cybercure.ngrok.io/cybercure/pages/proxy', 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `ccure_settings`
--

CREATE TABLE `ccure_settings` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `realtime_protection` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  `mail_notifications` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  `countryban_blacklist` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  `jquery_include` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `error_reporting` int(11) NOT NULL DEFAULT '1',
  `display_errors` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='All Project SECURITY settings will be stored here.';

--
-- Dumping data for table `ccure_settings`
--

INSERT INTO `ccure_settings` (`id`, `email`, `realtime_protection`, `mail_notifications`, `countryban_blacklist`, `jquery_include`, `error_reporting`, `display_errors`) VALUES
(1, 'admin@mail.com', 'Yes', 'Yes', 'Yes', 'No', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ccure_spam-settings`
--

CREATE TABLE `ccure_spam-settings` (
  `id` int(11) NOT NULL,
  `protection` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `logging` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  `redirect` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'http://cybercure.ngrok.io/cybercure/pages/spammer',
  `autoban` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `mail` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ccure_spam-settings`
--

INSERT INTO `ccure_spam-settings` (`id`, `protection`, `logging`, `redirect`, `autoban`, `mail`) VALUES
(1, 'Yes', 'Yes', 'http://cybercure.ngrok.io/cybercure/pages/spammer', 'Yes', 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `ccure_sqli-settings`
--

CREATE TABLE `ccure_sqli-settings` (
  `id` int(11) NOT NULL,
  `protection` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  `protection2` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  `protection3` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  `protection4` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  `protection5` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  `protection6` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  `protection7` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `logging` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  `redirect` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'http://cybercure.ngrok.io/cybercure/pages/blocked',
  `autoban` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `mail` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ccure_sqli-settings`
--

INSERT INTO `ccure_sqli-settings` (`id`, `protection`, `protection2`, `protection3`, `protection4`, `protection5`, `protection6`, `protection7`, `logging`, `redirect`, `autoban`, `mail`) VALUES
(1, 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'http://cybercure.ngrok.io/cybercure/pages/blocked', 'Yes', 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `ccure_tor-settings`
--

CREATE TABLE `ccure_tor-settings` (
  `id` int(11) NOT NULL,
  `protection` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  `logging` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  `redirect` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pages/blocked',
  `autoban` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `mail` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ccure_tor-settings`
--

INSERT INTO `ccure_tor-settings` (`id`, `protection`, `logging`, `redirect`, `autoban`, `mail`) VALUES
(1, 'Yes', 'Yes', 'http://cybercure.ngrok.io/cybercure/pages/tor-detected', 'Yes', 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `ccure_users`
--

CREATE TABLE `ccure_users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ccure_users`
--

INSERT INTO `ccure_users` (`id`, `username`, `password`, `email`) VALUES
(1, 'admin', 'RGprZWwzNjA=', 'dykelaway@gmail.com'),
(2, 'Driano', 'MTIzNA==', 'joseph.edouard2@gmail.com'),
(3, 'Harry', 'SkB6aTE5ODk=', 'aziejeanharry@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ccure_bans`
--
ALTER TABLE `ccure_bans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ccure_bans-country`
--
ALTER TABLE `ccure_bans-country`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ccure_bans-other`
--
ALTER TABLE `ccure_bans-other`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ccure_content-protection`
--
ALTER TABLE `ccure_content-protection`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ccure_dnsbl-databases`
--
ALTER TABLE `ccure_dnsbl-databases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ccure_ip-whitelist`
--
ALTER TABLE `ccure_ip-whitelist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ccure_logs`
--
ALTER TABLE `ccure_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ccure_malwarescanner-settings`
--
ALTER TABLE `ccure_malwarescanner-settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ccure_massrequests-settings`
--
ALTER TABLE `ccure_massrequests-settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ccure_monitoring`
--
ALTER TABLE `ccure_monitoring`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ccure_optimization-settings`
--
ALTER TABLE `ccure_optimization-settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ccure_pages-layolt`
--
ALTER TABLE `ccure_pages-layolt`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ccure_proxy-settings`
--
ALTER TABLE `ccure_proxy-settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ccure_settings`
--
ALTER TABLE `ccure_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ccure_spam-settings`
--
ALTER TABLE `ccure_spam-settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ccure_sqli-settings`
--
ALTER TABLE `ccure_sqli-settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ccure_tor-settings`
--
ALTER TABLE `ccure_tor-settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ccure_users`
--
ALTER TABLE `ccure_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ccure_bans`
--
ALTER TABLE `ccure_bans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ccure_bans-country`
--
ALTER TABLE `ccure_bans-country`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ccure_bans-other`
--
ALTER TABLE `ccure_bans-other`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ccure_content-protection`
--
ALTER TABLE `ccure_content-protection`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `ccure_dnsbl-databases`
--
ALTER TABLE `ccure_dnsbl-databases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ccure_ip-whitelist`
--
ALTER TABLE `ccure_ip-whitelist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ccure_logs`
--
ALTER TABLE `ccure_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ccure_malwarescanner-settings`
--
ALTER TABLE `ccure_malwarescanner-settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ccure_massrequests-settings`
--
ALTER TABLE `ccure_massrequests-settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ccure_monitoring`
--
ALTER TABLE `ccure_monitoring`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ccure_optimization-settings`
--
ALTER TABLE `ccure_optimization-settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ccure_pages-layolt`
--
ALTER TABLE `ccure_pages-layolt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `ccure_proxy-settings`
--
ALTER TABLE `ccure_proxy-settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ccure_settings`
--
ALTER TABLE `ccure_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ccure_spam-settings`
--
ALTER TABLE `ccure_spam-settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ccure_sqli-settings`
--
ALTER TABLE `ccure_sqli-settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ccure_tor-settings`
--
ALTER TABLE `ccure_tor-settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ccure_users`
--
ALTER TABLE `ccure_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
