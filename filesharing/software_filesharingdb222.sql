-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 23, 2022 at 12:52 AM
-- Server version: 5.7.37
-- PHP Version: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `software_filesharingdb222`
--

-- --------------------------------------------------------

--
-- Table structure for table `SFS355_config`
--

CREATE TABLE `SFS355_config` (
  `id` int(4) NOT NULL,
  `timezone` varchar(100) NOT NULL DEFAULT 'Europe/Vienna',
  `db_timezoneCorrection` varchar(8) NOT NULL DEFAULT '+00:00',
  `siteName` varchar(100) NOT NULL,
  `maxFileSize` float NOT NULL DEFAULT '15',
  `multiUpload` tinyint(1) NOT NULL DEFAULT '1',
  `maxMultiFiles` smallint(5) UNSIGNED NOT NULL DEFAULT '3',
  `addAnotherFiles` tinyint(1) NOT NULL DEFAULT '1',
  `delDays` smallint(6) NOT NULL DEFAULT '14',
  `delOn` varchar(30) NOT NULL DEFAULT 'download' COMMENT 'download, upload',
  `delSettingsByUploader` tinyint(1) NOT NULL DEFAULT '1',
  `delDownloadsNumbers` varchar(150) DEFAULT NULL COMMENT '1,2,3,5,10,15,...',
  `maxRcpt` tinyint(4) NOT NULL DEFAULT '3',
  `downloadProtection` varchar(15) NOT NULL DEFAULT 'SESSION' COMMENT '0, IP, SESSION',
  `passwordProtection` tinyint(1) NOT NULL DEFAULT '0',
  `extDenied` varchar(200) NOT NULL DEFAULT 'exe' COMMENT 'exe,bat,...',
  `extAllowed` varchar(200) DEFAULT NULL COMMENT 'jpg,jpeg,xml,doc,...',
  `downloadSeconds` smallint(5) UNSIGNED NOT NULL DEFAULT '10',
  `imagePreview` tinyint(1) NOT NULL DEFAULT '1',
  `prevWidth` smallint(5) UNSIGNED NOT NULL DEFAULT '400',
  `prevHeight` smallint(5) UNSIGNED NOT NULL DEFAULT '300',
  `XSendFile` tinyint(1) NOT NULL DEFAULT '0',
  `kbps` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `captchaContact` tinyint(1) NOT NULL DEFAULT '0',
  `shortUrls` varchar(20) DEFAULT NULL COMMENT 'bitly,adfly,linkpay,scb,sfs,...',
  `bitlyUser` varchar(100) DEFAULT NULL,
  `bitlyKey` varchar(100) DEFAULT NULL,
  `adflyUid` varchar(100) DEFAULT NULL,
  `adflyKey` varchar(100) DEFAULT NULL,
  `adflyAdvertType` varchar(100) DEFAULT NULL,
  `connectionMethod` varchar(20) NOT NULL DEFAULT 'auto' COMMENT 'auto,curl,url_fopen',
  `adminOnlyUploads` tinyint(1) NOT NULL DEFAULT '0',
  `admin_mail` varchar(100) NOT NULL DEFAULT 'admin@yourdomain.com',
  `automaileraddr` varchar(100) NOT NULL DEFAULT 'no-reply@yourdomain.com',
  `contact_mail` varchar(100) NOT NULL DEFAULT 'office@yourdomain.com',
  `mailParams` varchar(100) DEFAULT NULL,
  `defaultLanguage` varchar(10) NOT NULL DEFAULT 'en' COMMENT 'en,de-Du,de-Sie,...',
  `version` float DEFAULT '3.6',
  `created` datetime NOT NULL,
  `edited` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `SFS355_config`
--

INSERT INTO `SFS355_config` (`id`, `timezone`, `db_timezoneCorrection`, `siteName`, `maxFileSize`, `multiUpload`, `maxMultiFiles`, `addAnotherFiles`, `delDays`, `delOn`, `delSettingsByUploader`, `delDownloadsNumbers`, `maxRcpt`, `downloadProtection`, `passwordProtection`, `extDenied`, `extAllowed`, `downloadSeconds`, `imagePreview`, `prevWidth`, `prevHeight`, `XSendFile`, `kbps`, `captchaContact`, `shortUrls`, `bitlyUser`, `bitlyKey`, `adflyUid`, `adflyKey`, `adflyAdvertType`, `connectionMethod`, `adminOnlyUploads`, `admin_mail`, `automaileraddr`, `contact_mail`, `mailParams`, `defaultLanguage`, `version`, `created`, `edited`) VALUES
(1, 'Europe/Vienna', '+00:00', 'SimpleFileSharer', 15, 1, 3, 1, 14, 'download', 1, NULL, 3, 'SESSION', 0, 'exe', NULL, 10, 1, 400, 300, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'auto', 0, 'admin@yourdomain.com', 'no-reply@yourdomain.com', 'office@yourdomain.com', NULL, 'en', 3.6, '2022-02-17 06:00:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `SFS355_download_handler`
--

CREATE TABLE `SFS355_download_handler` (
  `id` int(11) NOT NULL,
  `files_id` int(11) NOT NULL,
  `d_ip` varchar(50) NOT NULL,
  `d_sid` varchar(50) NOT NULL,
  `d_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `SFS355_error_log`
--

CREATE TABLE `SFS355_error_log` (
  `id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `line` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `referer` varchar(255) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `SFS355_error_log`
--

INSERT INTO `SFS355_error_log` (`id`, `message`, `file`, `line`, `url`, `referer`, `ip`, `created`) VALUES
(1, 'Undefined property: stdClass::$downloadSeconds', '/home/softwarestore22/public_html/filesharing/includes/header.php', 60, 'http://filesharing.softwarestore.biz/setup.php?todo=createtables', 'http://filesharing.softwarestore.biz/setup.php', '182.66.37.94', '2022-02-17 06:00:22');

-- --------------------------------------------------------

--
-- Table structure for table `SFS355_files`
--

CREATE TABLE `SFS355_files` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL DEFAULT '0',
  `fname` varchar(50) NOT NULL,
  `ftype` varchar(255) NOT NULL,
  `fsize` bigint(20) UNSIGNED NOT NULL,
  `descr` varchar(150) NOT NULL,
  `descr_long` varchar(250) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `downloads` int(11) NOT NULL DEFAULT '0',
  `d_ip` varchar(50) DEFAULT NULL,
  `d_sid` varchar(50) DEFAULT NULL,
  `u_key` varchar(50) DEFAULT NULL,
  `d_time` datetime DEFAULT NULL,
  `last_download` datetime DEFAULT NULL,
  `pwd_protected` tinyint(1) NOT NULL DEFAULT '0',
  `pwd` varchar(20) DEFAULT NULL,
  `del_days` int(11) NOT NULL DEFAULT '-1',
  `del_downloads` int(11) NOT NULL DEFAULT '-1',
  `locked` tinyint(1) NOT NULL DEFAULT '0',
  `adfly_dele` varchar(30) DEFAULT NULL,
  `adfly_down` varchar(30) DEFAULT NULL,
  `shortkey` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `SFS355_files`
--

INSERT INTO `SFS355_files` (`id`, `uid`, `fname`, `ftype`, `fsize`, `descr`, `descr_long`, `status`, `created`, `downloads`, `d_ip`, `d_sid`, `u_key`, `d_time`, `last_download`, `pwd_protected`, `pwd`, `del_days`, `del_downloads`, `locked`, `adfly_dele`, `adfly_down`, `shortkey`) VALUES
(1, 0, '3e0e596f002481d20bd3fa70a8908236.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 15945, 'Laravel 8.docx', NULL, 1, '2022-02-17 06:06:00', 0, NULL, NULL, '1e1dec13a7924c7e4db92b0330205310', NULL, '2022-02-17 06:06:00', 0, NULL, -1, -1, 0, NULL, NULL, 'qqsJZ');

-- --------------------------------------------------------

--
-- Table structure for table `SFS355_messages`
--

CREATE TABLE `SFS355_messages` (
  `u_key` varchar(50) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `SFS355_modules`
--

CREATE TABLE `SFS355_modules` (
  `id` int(11) NOT NULL,
  `modname` varchar(50) NOT NULL,
  `installed` tinyint(1) NOT NULL DEFAULT '0',
  `installed_version` float NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `SFS355_overall_stats`
--

CREATE TABLE `SFS355_overall_stats` (
  `id` int(11) NOT NULL,
  `downloads` int(11) NOT NULL DEFAULT '0',
  `d_size` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `uploads` int(11) NOT NULL DEFAULT '0',
  `u_size` bigint(20) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `SFS355_overall_stats`
--

INSERT INTO `SFS355_overall_stats` (`id`, `downloads`, `d_size`, `uploads`, `u_size`) VALUES
(1, 0, 0, 1, 15945);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `SFS355_config`
--
ALTER TABLE `SFS355_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `SFS355_download_handler`
--
ALTER TABLE `SFS355_download_handler`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `SFS355_error_log`
--
ALTER TABLE `SFS355_error_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `SFS355_files`
--
ALTER TABLE `SFS355_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `SFS355_messages`
--
ALTER TABLE `SFS355_messages`
  ADD PRIMARY KEY (`u_key`);

--
-- Indexes for table `SFS355_modules`
--
ALTER TABLE `SFS355_modules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mod` (`modname`);

--
-- Indexes for table `SFS355_overall_stats`
--
ALTER TABLE `SFS355_overall_stats`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `SFS355_config`
--
ALTER TABLE `SFS355_config`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `SFS355_download_handler`
--
ALTER TABLE `SFS355_download_handler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `SFS355_error_log`
--
ALTER TABLE `SFS355_error_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `SFS355_files`
--
ALTER TABLE `SFS355_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `SFS355_modules`
--
ALTER TABLE `SFS355_modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `SFS355_overall_stats`
--
ALTER TABLE `SFS355_overall_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
