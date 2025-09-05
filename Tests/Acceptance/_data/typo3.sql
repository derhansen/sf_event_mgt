-- MySQL dump 10.13  Distrib 9.3.0, for macos15.4 (arm64)
--
-- Host: 127.0.0.1    Database: typo3_sfeventmgt_acceptance_v12
-- ------------------------------------------------------
-- Server version	5.5.5-10.2.44-MariaDB-1:10.2.44+maria~bionic

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `backend_layout`
--

DROP TABLE IF EXISTS `backend_layout`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `backend_layout` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `sorting` int(11) NOT NULL DEFAULT 0,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `config` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `be_dashboards`
--

DROP TABLE IF EXISTS `be_dashboards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `be_dashboards` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `identifier` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `title` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `widgets` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`),
  KEY `identifier` (`identifier`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `be_groups`
--

DROP TABLE IF EXISTS `be_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `be_groups` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `non_exclude_fields` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `explicit_allowdeny` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `allowed_languages` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `custom_options` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `db_mountpoints` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pagetypes_select` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tables_select` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tables_modify` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `groupMods` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `availableWidgets` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_mountpoints` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_permissions` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `TSconfig` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subgroup` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `workspace_perms` smallint(6) NOT NULL DEFAULT 1,
  `category_perms` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mfa_providers` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `be_sessions`
--

DROP TABLE IF EXISTS `be_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `be_sessions` (
  `ses_id` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ses_iplock` varchar(39) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ses_userid` int(10) unsigned NOT NULL DEFAULT 0,
  `ses_tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `ses_data` longblob DEFAULT NULL,
  PRIMARY KEY (`ses_id`),
  KEY `ses_tstamp` (`ses_tstamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `be_users`
--

DROP TABLE IF EXISTS `be_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `be_users` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `disable` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `avatar` int(10) unsigned NOT NULL DEFAULT 0,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `admin` smallint(5) unsigned NOT NULL DEFAULT 0,
  `usergroup` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lang` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `db_mountpoints` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `options` smallint(5) unsigned NOT NULL DEFAULT 0,
  `realName` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `userMods` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `allowed_languages` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `uc` mediumblob DEFAULT NULL,
  `file_mountpoints` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_permissions` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `workspace_perms` smallint(6) NOT NULL DEFAULT 1,
  `TSconfig` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastlogin` int(11) NOT NULL DEFAULT 0,
  `workspace_id` int(11) NOT NULL DEFAULT 0,
  `category_perms` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_reset_token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `mfa` mediumblob DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `username` (`username`),
  KEY `parent` (`pid`,`deleted`,`disable`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache_adminpanel_requestcache`
--

DROP TABLE IF EXISTS `cache_adminpanel_requestcache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_adminpanel_requestcache` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `expires` int(10) unsigned NOT NULL DEFAULT 0,
  `content` longblob DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cache_id` (`identifier`(180),`expires`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache_adminpanel_requestcache_tags`
--

DROP TABLE IF EXISTS `cache_adminpanel_requestcache_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_adminpanel_requestcache_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `tag` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `cache_id` (`identifier`(191)),
  KEY `cache_tag` (`tag`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache_hash`
--

DROP TABLE IF EXISTS `cache_hash`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_hash` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `expires` int(10) unsigned NOT NULL DEFAULT 0,
  `content` longblob DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cache_id` (`identifier`(180),`expires`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache_hash_tags`
--

DROP TABLE IF EXISTS `cache_hash_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_hash_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `tag` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `cache_id` (`identifier`(191)),
  KEY `cache_tag` (`tag`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache_imagesizes`
--

DROP TABLE IF EXISTS `cache_imagesizes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_imagesizes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `expires` int(10) unsigned NOT NULL DEFAULT 0,
  `content` longblob DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cache_id` (`identifier`(180),`expires`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache_imagesizes_tags`
--

DROP TABLE IF EXISTS `cache_imagesizes_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_imagesizes_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `tag` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `cache_id` (`identifier`(191)),
  KEY `cache_tag` (`tag`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache_pages`
--

DROP TABLE IF EXISTS `cache_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `expires` int(10) unsigned NOT NULL DEFAULT 0,
  `content` longblob DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cache_id` (`identifier`(180),`expires`)
) ENGINE=InnoDB AUTO_INCREMENT=362 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache_pages_tags`
--

DROP TABLE IF EXISTS `cache_pages_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_pages_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `tag` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `cache_id` (`identifier`(191)),
  KEY `cache_tag` (`tag`(191))
) ENGINE=InnoDB AUTO_INCREMENT=598 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache_rootline`
--

DROP TABLE IF EXISTS `cache_rootline`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_rootline` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `expires` int(10) unsigned NOT NULL DEFAULT 0,
  `content` longblob DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cache_id` (`identifier`(180),`expires`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache_rootline_tags`
--

DROP TABLE IF EXISTS `cache_rootline_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_rootline_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `tag` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `cache_id` (`identifier`(191)),
  KEY `cache_tag` (`tag`(191))
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache_treelist`
--

DROP TABLE IF EXISTS `cache_treelist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_treelist` (
  `md5hash` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `pid` int(11) NOT NULL DEFAULT 0,
  `treelist` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tstamp` int(11) NOT NULL DEFAULT 0,
  `expires` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`md5hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fe_groups`
--

DROP TABLE IF EXISTS `fe_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fe_groups` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tx_extbase_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `subgroup` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `TSconfig` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `felogin_redirectPid` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fe_sessions`
--

DROP TABLE IF EXISTS `fe_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fe_sessions` (
  `ses_id` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ses_iplock` varchar(39) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ses_userid` int(10) unsigned NOT NULL DEFAULT 0,
  `ses_tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `ses_data` mediumblob DEFAULT NULL,
  `ses_permanent` smallint(5) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`ses_id`),
  KEY `ses_tstamp` (`ses_tstamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fe_users`
--

DROP TABLE IF EXISTS `fe_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fe_users` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `disable` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tx_extbase_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `usergroup` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `first_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `middle_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `last_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `telephone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `fax` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `uc` blob DEFAULT NULL,
  `title` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `zip` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `city` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `country` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `www` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `company` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `image` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `TSconfig` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastlogin` int(11) NOT NULL DEFAULT 0,
  `is_online` int(10) unsigned NOT NULL DEFAULT 0,
  `felogin_redirectPid` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `felogin_forgotHash` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `mfa` mediumblob DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`username`(100)),
  KEY `username` (`username`(100)),
  KEY `is_online` (`is_online`),
  KEY `felogin_forgotHash` (`felogin_forgotHash`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `fe_group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `sorting` int(11) NOT NULL DEFAULT 0,
  `rowDescription` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `editlock` smallint(5) unsigned NOT NULL DEFAULT 0,
  `sys_language_uid` int(11) NOT NULL DEFAULT 0,
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_source` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_state` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_diffsource` mediumblob DEFAULT NULL,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `perms_userid` int(10) unsigned NOT NULL DEFAULT 0,
  `perms_groupid` int(10) unsigned NOT NULL DEFAULT 0,
  `perms_user` smallint(5) unsigned NOT NULL DEFAULT 0,
  `perms_group` smallint(5) unsigned NOT NULL DEFAULT 0,
  `perms_everybody` smallint(5) unsigned NOT NULL DEFAULT 0,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `slug` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `doktype` int(10) unsigned NOT NULL DEFAULT 0,
  `TSconfig` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_siteroot` smallint(6) NOT NULL DEFAULT 0,
  `php_tree_stop` smallint(6) NOT NULL DEFAULT 0,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `shortcut` int(10) unsigned NOT NULL DEFAULT 0,
  `shortcut_mode` int(10) unsigned NOT NULL DEFAULT 0,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `layout` int(10) unsigned NOT NULL DEFAULT 0,
  `target` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `media` int(10) unsigned NOT NULL DEFAULT 0,
  `lastUpdated` int(11) NOT NULL DEFAULT 0,
  `keywords` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cache_timeout` int(10) unsigned NOT NULL DEFAULT 0,
  `cache_tags` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `newUntil` int(11) NOT NULL DEFAULT 0,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_search` smallint(5) unsigned NOT NULL DEFAULT 0,
  `SYS_LASTCHANGED` int(10) unsigned NOT NULL DEFAULT 0,
  `abstract` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `module` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `extendToSubpages` smallint(5) unsigned NOT NULL DEFAULT 0,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `author_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `nav_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `nav_hide` smallint(6) NOT NULL DEFAULT 0,
  `content_from_pid` int(10) unsigned NOT NULL DEFAULT 0,
  `mount_pid` int(10) unsigned NOT NULL DEFAULT 0,
  `mount_pid_ol` smallint(6) NOT NULL DEFAULT 0,
  `l18n_cfg` smallint(6) NOT NULL DEFAULT 0,
  `backend_layout` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `backend_layout_next_level` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `tsconfig_includes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tx_impexp_origuid` int(11) NOT NULL DEFAULT 0,
  `seo_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `no_index` smallint(6) NOT NULL DEFAULT 0,
  `no_follow` smallint(6) NOT NULL DEFAULT 0,
  `og_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `og_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `og_image` int(10) unsigned NOT NULL DEFAULT 0,
  `twitter_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `twitter_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_image` int(10) unsigned NOT NULL DEFAULT 0,
  `twitter_card` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `canonical_link` varchar(2048) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sitemap_priority` decimal(2,1) NOT NULL DEFAULT 0.5,
  `sitemap_changefreq` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `categories` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `determineSiteRoot` (`is_siteroot`),
  KEY `language_identifier` (`l10n_parent`,`sys_language_uid`),
  KEY `slug` (`slug`(127)),
  KEY `parent` (`pid`,`deleted`,`hidden`),
  KEY `translation_source` (`l10n_source`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_be_shortcuts`
--

DROP TABLE IF EXISTS `sys_be_shortcuts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_be_shortcuts` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT 0,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sorting` int(11) NOT NULL DEFAULT 0,
  `sc_group` smallint(6) NOT NULL DEFAULT 0,
  `route` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `arguments` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `event` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_category`
--

DROP TABLE IF EXISTS `sys_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_category` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `sorting` int(11) NOT NULL DEFAULT 0,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sys_language_uid` int(11) NOT NULL DEFAULT 0,
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_state` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_diffsource` mediumblob DEFAULT NULL,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `title` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent` int(10) unsigned NOT NULL DEFAULT 0,
  `items` int(11) NOT NULL DEFAULT 0,
  `slug` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `category_parent` (`parent`),
  KEY `category_list` (`pid`,`deleted`,`sys_language_uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_category_record_mm`
--

DROP TABLE IF EXISTS `sys_category_record_mm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_category_record_mm` (
  `uid_local` int(10) unsigned NOT NULL DEFAULT 0,
  `uid_foreign` int(10) unsigned NOT NULL DEFAULT 0,
  `tablenames` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `fieldname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sorting` int(10) unsigned NOT NULL DEFAULT 0,
  `sorting_foreign` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid_local`,`uid_foreign`,`tablenames`,`fieldname`),
  KEY `uid_local` (`uid_local`),
  KEY `uid_foreign` (`uid_foreign`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_csp_resolution`
--

DROP TABLE IF EXISTS `sys_csp_resolution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_csp_resolution` (
  `summary` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `scope` varchar(264) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mutation_identifier` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mutation_collection` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`summary`),
  KEY `created` (`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_file`
--

DROP TABLE IF EXISTS `sys_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_file` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `last_indexed` int(11) NOT NULL DEFAULT 0,
  `missing` smallint(6) NOT NULL DEFAULT 0,
  `storage` int(11) NOT NULL DEFAULT 0,
  `type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `metadata` int(11) NOT NULL DEFAULT 0,
  `identifier` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `identifier_hash` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `folder_hash` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `extension` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `name` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sha1` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `size` bigint(20) unsigned NOT NULL DEFAULT 0,
  `creation_date` int(11) NOT NULL DEFAULT 0,
  `modification_date` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `sel01` (`storage`,`identifier_hash`),
  KEY `folder` (`storage`,`folder_hash`),
  KEY `tstamp` (`tstamp`),
  KEY `lastindex` (`last_indexed`),
  KEY `sha1` (`sha1`),
  KEY `parent` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_file_collection`
--

DROP TABLE IF EXISTS `sys_file_collection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_file_collection` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sys_language_uid` int(11) NOT NULL DEFAULT 0,
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_state` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_diffsource` mediumblob DEFAULT NULL,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `title` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'static',
  `files` int(11) NOT NULL DEFAULT 0,
  `storage` int(11) NOT NULL DEFAULT 0,
  `folder` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recursive` smallint(6) NOT NULL DEFAULT 0,
  `category` int(10) unsigned NOT NULL DEFAULT 0,
  `folder_identifier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_file_metadata`
--

DROP TABLE IF EXISTS `sys_file_metadata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_file_metadata` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `sys_language_uid` int(11) NOT NULL DEFAULT 0,
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_state` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_diffsource` mediumblob DEFAULT NULL,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `file` int(11) NOT NULL DEFAULT 0,
  `title` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `width` int(11) NOT NULL DEFAULT 0,
  `height` int(11) NOT NULL DEFAULT 0,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alternative` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `categories` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `file` (`file`),
  KEY `fal_filelist` (`l10n_parent`,`sys_language_uid`),
  KEY `parent` (`pid`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_file_processedfile`
--

DROP TABLE IF EXISTS `sys_file_processedfile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_file_processedfile` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `tstamp` int(11) NOT NULL DEFAULT 0,
  `crdate` int(11) NOT NULL DEFAULT 0,
  `storage` int(11) NOT NULL DEFAULT 0,
  `original` int(11) NOT NULL DEFAULT 0,
  `identifier` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `name` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `configuration` blob DEFAULT NULL,
  `configurationsha1` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `originalfilesha1` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `task_type` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `checksum` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `width` int(11) DEFAULT 0,
  `height` int(11) DEFAULT 0,
  `processing_url` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `combined_1` (`original`,`task_type`(100),`configurationsha1`),
  KEY `identifier` (`storage`,`identifier`(180))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_file_reference`
--

DROP TABLE IF EXISTS `sys_file_reference`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_file_reference` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `sys_language_uid` int(11) NOT NULL DEFAULT 0,
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_state` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `l10n_diffsource` mediumblob DEFAULT NULL,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `uid_local` int(11) NOT NULL DEFAULT 0,
  `uid_foreign` int(11) NOT NULL DEFAULT 0,
  `tablenames` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `fieldname` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sorting_foreign` int(11) NOT NULL DEFAULT 0,
  `title` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alternative` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `crop` varchar(4000) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `autoplay` smallint(6) NOT NULL DEFAULT 0,
  `show_in_views` smallint(6) NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `tablenames_fieldname` (`tablenames`(32),`fieldname`(12)),
  KEY `deleted` (`deleted`),
  KEY `uid_local` (`uid_local`),
  KEY `uid_foreign` (`uid_foreign`),
  KEY `combined_1` (`l10n_parent`,`t3ver_oid`,`t3ver_wsid`,`t3ver_state`,`deleted`),
  KEY `parent` (`pid`,`deleted`,`hidden`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_file_storage`
--

DROP TABLE IF EXISTS `sys_file_storage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_file_storage` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `driver` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `configuration` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` smallint(6) NOT NULL DEFAULT 0,
  `is_browsable` smallint(6) NOT NULL DEFAULT 0,
  `is_public` smallint(6) NOT NULL DEFAULT 0,
  `is_writable` smallint(6) NOT NULL DEFAULT 0,
  `is_online` smallint(6) NOT NULL DEFAULT 1,
  `auto_extract_metadata` smallint(6) NOT NULL DEFAULT 1,
  `processingfolder` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_filemounts`
--

DROP TABLE IF EXISTS `sys_filemounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_filemounts` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `sorting` int(11) NOT NULL DEFAULT 0,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `read_only` smallint(5) unsigned NOT NULL DEFAULT 0,
  `identifier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_history`
--

DROP TABLE IF EXISTS `sys_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_history` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `actiontype` smallint(6) NOT NULL DEFAULT 0,
  `usertype` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BE',
  `userid` int(10) unsigned DEFAULT NULL,
  `originaluserid` int(10) unsigned DEFAULT NULL,
  `recuid` int(11) NOT NULL DEFAULT 0,
  `tablename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `history_data` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `workspace` int(11) DEFAULT 0,
  `correlation_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`),
  KEY `recordident_1` (`tablename`(100),`recuid`),
  KEY `recordident_2` (`tablename`(100),`tstamp`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_http_report`
--

DROP TABLE IF EXISTS `sys_http_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_http_report` (
  `uuid` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` smallint(5) unsigned NOT NULL DEFAULT 0,
  `created` int(10) unsigned NOT NULL,
  `changed` int(10) unsigned NOT NULL,
  `type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scope` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_time` bigint(20) unsigned NOT NULL,
  `meta` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `summary` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`uuid`),
  KEY `type_scope` (`type`,`scope`),
  KEY `created` (`created`),
  KEY `changed` (`changed`),
  KEY `request_time` (`request_time`),
  KEY `summary_created` (`summary`,`created`),
  KEY `all_conditions` (`type`,`status`,`scope`,`summary`,`request_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_lockedrecords`
--

DROP TABLE IF EXISTS `sys_lockedrecords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_lockedrecords` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `record_table` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `record_uid` int(11) NOT NULL DEFAULT 0,
  `record_pid` int(11) NOT NULL DEFAULT 0,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `feuserid` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `event` (`userid`,`tstamp`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_log`
--

DROP TABLE IF EXISTS `sys_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_log` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `userid` int(10) unsigned NOT NULL DEFAULT 0,
  `action` smallint(5) unsigned NOT NULL DEFAULT 0,
  `recuid` int(10) unsigned NOT NULL DEFAULT 0,
  `tablename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `recpid` int(11) NOT NULL DEFAULT 0,
  `error` smallint(5) unsigned NOT NULL DEFAULT 0,
  `details` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` smallint(5) unsigned NOT NULL DEFAULT 0,
  `details_nr` smallint(6) NOT NULL DEFAULT 0,
  `IP` varchar(39) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `log_data` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_pid` int(11) NOT NULL DEFAULT -1,
  `workspace` int(11) NOT NULL DEFAULT 0,
  `NEWid` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `request_id` varchar(13) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `time_micro` double NOT NULL DEFAULT 0,
  `component` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `level` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info',
  `message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `channel` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  PRIMARY KEY (`uid`),
  KEY `event` (`userid`,`event_pid`),
  KEY `recuidIdx` (`recuid`),
  KEY `user_auth` (`type`,`action`,`tstamp`),
  KEY `request` (`request_id`),
  KEY `combined_1` (`tstamp`,`type`,`userid`),
  KEY `errorcount` (`tstamp`,`error`),
  KEY `parent` (`pid`),
  KEY `channel` (`channel`),
  KEY `level` (`level`)
) ENGINE=InnoDB AUTO_INCREMENT=145 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_messenger_messages`
--

DROP TABLE IF EXISTS `sys_messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_messenger_messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `queue_name` (`queue_name`),
  KEY `available_at` (`available_at`),
  KEY `delivered_at` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_news`
--

DROP TABLE IF EXISTS `sys_news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_news` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `content` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_note`
--

DROP TABLE IF EXISTS `sys_note`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_note` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `cruser` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `sorting` int(11) NOT NULL DEFAULT 0,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `personal` smallint(5) unsigned NOT NULL DEFAULT 0,
  `category` smallint(5) unsigned NOT NULL DEFAULT 0,
  `position` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_redirect`
--

DROP TABLE IF EXISTS `sys_redirect`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_redirect` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `updatedon` int(10) unsigned NOT NULL DEFAULT 0,
  `createdon` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `disabled` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `source_host` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `source_path` varchar(2048) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `is_regexp` smallint(5) unsigned NOT NULL DEFAULT 0,
  `force_https` smallint(5) unsigned NOT NULL DEFAULT 0,
  `respect_query_parameters` smallint(5) unsigned NOT NULL DEFAULT 0,
  `keep_query_parameters` smallint(5) unsigned NOT NULL DEFAULT 0,
  `target` varchar(2048) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `target_statuscode` int(11) NOT NULL DEFAULT 307,
  `hitcount` int(11) NOT NULL DEFAULT 0,
  `lasthiton` int(11) NOT NULL DEFAULT 0,
  `disable_hitcount` smallint(5) unsigned NOT NULL DEFAULT 0,
  `protected` smallint(5) unsigned NOT NULL DEFAULT 0,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creation_type` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `index_source` (`source_host`(80),`source_path`(80)),
  KEY `parent` (`pid`,`deleted`,`disabled`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_refindex`
--

DROP TABLE IF EXISTS `sys_refindex`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_refindex` (
  `hash` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `tablename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `recuid` int(11) NOT NULL DEFAULT 0,
  `field` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `flexpointer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `softref_key` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `softref_id` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sorting` int(11) NOT NULL DEFAULT 0,
  `workspace` int(11) NOT NULL DEFAULT 0,
  `ref_table` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ref_uid` int(11) NOT NULL DEFAULT 0,
  `ref_string` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`hash`),
  KEY `lookup_rec` (`tablename`(100),`recuid`),
  KEY `lookup_uid` (`ref_table`(100),`ref_uid`),
  KEY `lookup_string` (`ref_string`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_registry`
--

DROP TABLE IF EXISTS `sys_registry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_registry` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entry_namespace` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `entry_key` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `entry_value` mediumblob DEFAULT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `entry_identifier` (`entry_namespace`,`entry_key`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_template`
--

DROP TABLE IF EXISTS `sys_template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sys_template` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `sorting` int(11) NOT NULL DEFAULT 0,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `root` smallint(5) unsigned NOT NULL DEFAULT 0,
  `clear` smallint(5) unsigned NOT NULL DEFAULT 0,
  `include_static_file` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `constants` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `config` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `basedOn` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `includeStaticAfterBasedOn` smallint(5) unsigned NOT NULL DEFAULT 0,
  `static_file_mode` smallint(5) unsigned NOT NULL DEFAULT 0,
  `tx_impexp_origuid` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `roottemplate` (`deleted`,`hidden`,`root`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tt_content`
--

DROP TABLE IF EXISTS `tt_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tt_content` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rowDescription` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `fe_group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `sorting` int(11) NOT NULL DEFAULT 0,
  `editlock` smallint(5) unsigned NOT NULL DEFAULT 0,
  `sys_language_uid` int(11) NOT NULL DEFAULT 0,
  `l18n_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_source` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_state` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  `l18n_diffsource` mediumblob DEFAULT NULL,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `CType` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `header` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `header_position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `bodytext` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bullets_type` smallint(5) unsigned NOT NULL DEFAULT 0,
  `uploads_description` smallint(5) unsigned NOT NULL DEFAULT 0,
  `uploads_type` smallint(5) unsigned NOT NULL DEFAULT 0,
  `assets` int(10) unsigned NOT NULL DEFAULT 0,
  `image` int(10) unsigned NOT NULL DEFAULT 0,
  `imagewidth` int(10) unsigned NOT NULL DEFAULT 0,
  `imageorient` smallint(5) unsigned NOT NULL DEFAULT 0,
  `imagecols` smallint(5) unsigned NOT NULL DEFAULT 0,
  `imageborder` smallint(5) unsigned NOT NULL DEFAULT 0,
  `media` int(10) unsigned NOT NULL DEFAULT 0,
  `layout` int(10) unsigned NOT NULL DEFAULT 0,
  `frame_class` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `cols` int(10) unsigned NOT NULL DEFAULT 0,
  `space_before_class` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `space_after_class` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `records` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pages` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `colPos` int(10) unsigned NOT NULL DEFAULT 0,
  `subheader` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `header_link` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `image_zoom` smallint(5) unsigned NOT NULL DEFAULT 0,
  `header_layout` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `list_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sectionIndex` smallint(5) unsigned NOT NULL DEFAULT 0,
  `linkToTop` smallint(5) unsigned NOT NULL DEFAULT 0,
  `file_collections` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `filelink_size` smallint(5) unsigned NOT NULL DEFAULT 0,
  `filelink_sorting` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `filelink_sorting_direction` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `target` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `date` int(11) NOT NULL DEFAULT 0,
  `recursive` smallint(5) unsigned NOT NULL DEFAULT 0,
  `imageheight` int(10) unsigned NOT NULL DEFAULT 0,
  `pi_flexform` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accessibility_title` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `accessibility_bypass` smallint(5) unsigned NOT NULL DEFAULT 0,
  `accessibility_bypass_text` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `selected_categories` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_field` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `table_class` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `table_caption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `table_delimiter` smallint(5) unsigned NOT NULL DEFAULT 0,
  `table_enclosure` smallint(5) unsigned NOT NULL DEFAULT 0,
  `table_header_position` smallint(5) unsigned NOT NULL DEFAULT 0,
  `table_tfoot` smallint(5) unsigned NOT NULL DEFAULT 0,
  `tx_impexp_origuid` int(11) NOT NULL DEFAULT 0,
  `categories` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`sorting`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`),
  KEY `language` (`l18n_parent`,`sys_language_uid`),
  KEY `translation_source` (`l10n_source`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tx_extensionmanager_domain_model_extension`
--

DROP TABLE IF EXISTS `tx_extensionmanager_domain_model_extension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tx_extensionmanager_domain_model_extension` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `extension_key` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `repository` int(11) NOT NULL DEFAULT 1,
  `version` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `alldownloadcounter` int(10) unsigned NOT NULL DEFAULT 0,
  `downloadcounter` int(10) unsigned NOT NULL DEFAULT 0,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` int(11) NOT NULL DEFAULT 0,
  `review_state` int(11) NOT NULL DEFAULT 0,
  `category` int(11) NOT NULL DEFAULT 0,
  `last_updated` int(11) NOT NULL DEFAULT 0,
  `serialized_dependencies` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `author_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ownerusername` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `md5hash` varchar(35) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `update_comment` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authorcompany` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `integer_version` int(11) NOT NULL DEFAULT 0,
  `current_version` int(11) NOT NULL DEFAULT 0,
  `lastreviewedversion` int(11) NOT NULL DEFAULT 0,
  `documentation_link` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remote` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ter',
  `distribution_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `distribution_welcome_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `versionextrepo` (`extension_key`,`version`,`remote`),
  KEY `index_currentversions` (`current_version`,`review_state`),
  KEY `parent` (`pid`),
  KEY `index_versionrepo` (`integer_version`,`remote`,`extension_key`),
  KEY `index_extrepo` (`extension_key`,`remote`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tx_impexp_presets`
--

DROP TABLE IF EXISTS `tx_impexp_presets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tx_impexp_presets` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_uid` int(10) unsigned NOT NULL DEFAULT 0,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `public` smallint(6) NOT NULL DEFAULT 0,
  `item_uid` int(11) NOT NULL DEFAULT 0,
  `preset_data` blob DEFAULT NULL,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `lookup` (`item_uid`),
  KEY `parent` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tx_sfeventmgt_domain_model_customnotificationlog`
--

DROP TABLE IF EXISTS `tx_sfeventmgt_domain_model_customnotificationlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tx_sfeventmgt_domain_model_customnotificationlog` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `event` int(10) unsigned NOT NULL DEFAULT 0,
  `details` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emails_sent` int(11) NOT NULL DEFAULT 0,
  `message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cruser_id` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `event` (`event`),
  KEY `parent` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tx_sfeventmgt_domain_model_event`
--

DROP TABLE IF EXISTS `tx_sfeventmgt_domain_model_event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tx_sfeventmgt_domain_model_event` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `rowDescription` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `fe_group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  `sorting` int(11) NOT NULL DEFAULT 0,
  `sys_language_uid` int(11) NOT NULL DEFAULT 0,
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_diffsource` mediumblob DEFAULT NULL,
  `l10n_state` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `teaser` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `program` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `startdate` int(11) NOT NULL DEFAULT 0,
  `enddate` int(11) NOT NULL DEFAULT 0,
  `max_participants` int(11) NOT NULL DEFAULT 0,
  `max_registrations_per_user` int(11) NOT NULL DEFAULT 1,
  `price` double NOT NULL DEFAULT 0,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `enable_payment` smallint(5) unsigned NOT NULL DEFAULT 0,
  `restrict_payment_methods` smallint(5) unsigned NOT NULL DEFAULT 0,
  `selected_payment_methods` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` int(10) unsigned NOT NULL DEFAULT 0,
  `registration` int(10) unsigned NOT NULL DEFAULT 0,
  `registration_waitlist` int(10) unsigned NOT NULL DEFAULT 0,
  `registration_fields` int(10) unsigned NOT NULL DEFAULT 0,
  `price_options` int(10) unsigned NOT NULL DEFAULT 0,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `files` int(11) NOT NULL DEFAULT 0,
  `related` int(11) NOT NULL DEFAULT 0,
  `additional_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `location` int(10) unsigned NOT NULL DEFAULT 0,
  `room` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `enable_registration` smallint(5) unsigned NOT NULL DEFAULT 0,
  `enable_waitlist` smallint(5) unsigned NOT NULL DEFAULT 0,
  `registration_deadline` int(11) NOT NULL DEFAULT 0,
  `link` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `top_event` smallint(5) unsigned NOT NULL DEFAULT 0,
  `organisator` int(10) unsigned NOT NULL DEFAULT 0,
  `speaker` int(10) unsigned NOT NULL DEFAULT 0,
  `notify_admin` smallint(5) unsigned NOT NULL DEFAULT 1,
  `notify_organisator` smallint(5) unsigned NOT NULL DEFAULT 0,
  `enable_cancel` smallint(5) unsigned NOT NULL DEFAULT 0,
  `cancel_deadline` int(11) NOT NULL DEFAULT 0,
  `enable_autoconfirm` smallint(5) unsigned NOT NULL DEFAULT 0,
  `unique_email_check` smallint(5) unsigned NOT NULL DEFAULT 0,
  `slug` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enable_waitlist_moveup` smallint(5) unsigned NOT NULL DEFAULT 0,
  `registration_startdate` int(11) NOT NULL DEFAULT 0,
  `custom_text` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alternative_title` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `allow_registration_until_enddate` smallint(5) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tx_sfeventmgt_domain_model_event_related_mm`
--

DROP TABLE IF EXISTS `tx_sfeventmgt_domain_model_event_related_mm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tx_sfeventmgt_domain_model_event_related_mm` (
  `uid_local` int(10) unsigned NOT NULL DEFAULT 0,
  `uid_foreign` int(10) unsigned NOT NULL DEFAULT 0,
  `sorting` int(10) unsigned NOT NULL DEFAULT 0,
  `sorting_foreign` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid_local`,`uid_foreign`),
  KEY `uid_local` (`uid_local`),
  KEY `uid_foreign` (`uid_foreign`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tx_sfeventmgt_domain_model_location`
--

DROP TABLE IF EXISTS `tx_sfeventmgt_domain_model_location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tx_sfeventmgt_domain_model_location` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  `sorting` int(11) NOT NULL DEFAULT 0,
  `sys_language_uid` int(11) NOT NULL DEFAULT 0,
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_diffsource` mediumblob DEFAULT NULL,
  `l10n_state` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `zip` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` decimal(9,6) NOT NULL DEFAULT 0.000000,
  `latitude` decimal(9,6) NOT NULL DEFAULT 0.000000,
  `slug` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tx_sfeventmgt_domain_model_organisator`
--

DROP TABLE IF EXISTS `tx_sfeventmgt_domain_model_organisator`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tx_sfeventmgt_domain_model_organisator` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  `sorting` int(11) NOT NULL DEFAULT 0,
  `sys_language_uid` int(11) NOT NULL DEFAULT 0,
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_diffsource` mediumblob DEFAULT NULL,
  `l10n_state` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `email_signature` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `slug` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tx_sfeventmgt_domain_model_priceoption`
--

DROP TABLE IF EXISTS `tx_sfeventmgt_domain_model_priceoption`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tx_sfeventmgt_domain_model_priceoption` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `fe_group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  `sys_language_uid` int(11) NOT NULL DEFAULT 0,
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_diffsource` mediumblob DEFAULT NULL,
  `l10n_state` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` double NOT NULL DEFAULT 0,
  `valid_until` int(11) NOT NULL DEFAULT 0,
  `event` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`),
  KEY `event` (`event`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tx_sfeventmgt_domain_model_registration`
--

DROP TABLE IF EXISTS `tx_sfeventmgt_domain_model_registration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tx_sfeventmgt_domain_model_registration` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  `sorting` int(11) NOT NULL DEFAULT 0,
  `event` int(10) unsigned NOT NULL DEFAULT 0,
  `main_registration` int(10) unsigned NOT NULL DEFAULT 0,
  `language` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `company` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `zip` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ignore_notifications` smallint(5) unsigned NOT NULL DEFAULT 0,
  `gender` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `accepttc` smallint(5) unsigned NOT NULL DEFAULT 0,
  `confirmed` smallint(5) unsigned NOT NULL DEFAULT 0,
  `notes` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` int(11) DEFAULT NULL,
  `confirmation_until` int(10) unsigned NOT NULL DEFAULT 0,
  `amount_of_registrations` int(11) NOT NULL DEFAULT 1,
  `recaptcha` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `fe_user` int(11) NOT NULL DEFAULT 0,
  `paid` smallint(5) unsigned NOT NULL DEFAULT 0,
  `paymentmethod` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `payment_reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `waitlist` smallint(5) unsigned NOT NULL DEFAULT 0,
  `field_values` int(10) unsigned NOT NULL DEFAULT 0,
  `registration_date` int(11) DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `event` (`event`,`waitlist`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB AUTO_INCREMENT=672 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tx_sfeventmgt_domain_model_registration_field`
--

DROP TABLE IF EXISTS `tx_sfeventmgt_domain_model_registration_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tx_sfeventmgt_domain_model_registration_field` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `fe_group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  `sorting` int(11) NOT NULL DEFAULT 0,
  `sys_language_uid` int(11) NOT NULL DEFAULT 0,
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_diffsource` mediumblob DEFAULT NULL,
  `l10n_state` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `required` smallint(5) unsigned NOT NULL DEFAULT 0,
  `placeholder` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_value` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `settings` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `datepickermode` smallint(6) NOT NULL DEFAULT 0,
  `event` int(10) unsigned NOT NULL DEFAULT 0,
  `feuser_value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`),
  KEY `event` (`event`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tx_sfeventmgt_domain_model_registration_fieldvalue`
--

DROP TABLE IF EXISTS `tx_sfeventmgt_domain_model_registration_fieldvalue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tx_sfeventmgt_domain_model_registration_fieldvalue` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  `value` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value_type` int(10) unsigned NOT NULL DEFAULT 0,
  `field` int(10) unsigned NOT NULL DEFAULT 0,
  `registration` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`),
  KEY `registration` (`registration`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tx_sfeventmgt_domain_model_speaker`
--

DROP TABLE IF EXISTS `tx_sfeventmgt_domain_model_speaker`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tx_sfeventmgt_domain_model_speaker` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT 0,
  `tstamp` int(10) unsigned NOT NULL DEFAULT 0,
  `crdate` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted` smallint(5) unsigned NOT NULL DEFAULT 0,
  `hidden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `starttime` int(10) unsigned NOT NULL DEFAULT 0,
  `endtime` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT 0,
  `t3ver_state` smallint(6) NOT NULL DEFAULT 0,
  `t3ver_stage` int(11) NOT NULL DEFAULT 0,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT 0,
  `sorting` int(11) NOT NULL DEFAULT 0,
  `sys_language_uid` int(11) NOT NULL DEFAULT 0,
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT 0,
  `l10n_diffsource` mediumblob DEFAULT NULL,
  `l10n_state` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `job_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` int(10) unsigned NOT NULL DEFAULT 0,
  `slug` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tx_sfeventmgt_event_speaker_mm`
--

DROP TABLE IF EXISTS `tx_sfeventmgt_event_speaker_mm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tx_sfeventmgt_event_speaker_mm` (
  `uid_local` int(10) unsigned NOT NULL DEFAULT 0,
  `uid_foreign` int(10) unsigned NOT NULL DEFAULT 0,
  `sorting` int(10) unsigned NOT NULL DEFAULT 0,
  `sorting_foreign` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid_local`,`uid_foreign`),
  KEY `uid_local` (`uid_local`),
  KEY `uid_foreign` (`uid_foreign`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-09-05 15:45:55
-- MySQL dump 10.13  Distrib 9.3.0, for macos15.4 (arm64)
--
-- Host: 127.0.0.1    Database: typo3_sfeventmgt_acceptance_v12
-- ------------------------------------------------------
-- Server version	5.5.5-10.2.44-MariaDB-1:10.2.44+maria~bionic

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `backend_layout`
--

LOCK TABLES `backend_layout` WRITE;
/*!40000 ALTER TABLE `backend_layout` DISABLE KEYS */;
/*!40000 ALTER TABLE `backend_layout` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `be_dashboards`
--

LOCK TABLES `be_dashboards` WRITE;
/*!40000 ALTER TABLE `be_dashboards` DISABLE KEYS */;
INSERT INTO `be_dashboards` VALUES (1,0,1586409656,1586409656,1,0,0,0,0,'37046ac03232c39ecfee7f5ac1f6e320c45ce1a2','My dashboard','{\"6def9f50aa9b5bfbcbacbafb9d92f30b96aa3331\":{\"identifier\":\"t3information\"},\"d15e31b52e33d83e4ae188836434f1745aec7e44\":{\"identifier\":\"typeOfUsers\"},\"a8efbb5deca32dc6a1d9772b3e296480b22da740\":{\"identifier\":\"t3news\"}}');
/*!40000 ALTER TABLE `be_dashboards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `be_groups`
--

LOCK TABLES `be_groups` WRITE;
/*!40000 ALTER TABLE `be_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `be_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `be_sessions`
--

LOCK TABLES `be_sessions` WRITE;
/*!40000 ALTER TABLE `be_sessions` DISABLE KEYS */;
INSERT INTO `be_sessions` VALUES ('78a3f714c6ff44ca1672f5e7b7077c605199e794362011de767f2cd8f690239a','[DISABLED]',1,1757079229,_binary 'a:1:{s:26:\"formProtectionSessionToken\";s:64:\"82c8315e1bc29e11122d778125fe58277e260947b7ad57c25eee1405729c3b35\";}');
/*!40000 ALTER TABLE `be_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `be_users`
--

LOCK TABLES `be_users` WRITE;
/*!40000 ALTER TABLE `be_users` DISABLE KEYS */;
INSERT INTO `be_users` VALUES (1,0,1586409640,1586409640,0,0,0,0,NULL,'admin',0,'$argon2i$v=19$m=65536,t=16,p=1$TUxnTkdPRWF1VEN6SzhiMA$Eh2Z8hDUAqSjH16pEKE1xgM6j7kX8Rm+LTRRfXEbh9c',1,'','defaul','',NULL,0,'',NULL,'',_binary 'a:12:{s:14:\"interfaceSetup\";s:7:\"backend\";s:10:\"moduleData\";a:7:{s:28:\"dashboard/current_dashboard/\";s:40:\"37046ac03232c39ecfee7f5ac1f6e320c45ce1a2\";s:10:\"web_layout\";a:3:{s:8:\"function\";s:1:\"1\";s:8:\"language\";s:1:\"0\";s:19:\"constant_editor_cat\";N;}s:6:\"web_ts\";a:3:{s:8:\"function\";s:85:\"TYPO3\\CMS\\Tstemplate\\Controller\\TypoScriptTemplateInformationModuleFunctionController\";s:8:\"language\";N;s:19:\"constant_editor_cat\";s:7:\"content\";}s:10:\"FormEngine\";a:2:{i:0;a:2:{s:32:\"c911e84eebb298f5d1c8e2c477bdf3d1\";a:5:{i:0;s:22:\"Event (reg, cat1) [EN]\";i:1;a:5:{s:4:\"edit\";a:1:{s:32:\"tx_sfeventmgt_domain_model_event\";a:1:{i:10;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:56:\"&edit%5Btx_sfeventmgt_domain_model_event%5D%5B10%5D=edit\";i:3;a:5:{s:5:\"table\";s:32:\"tx_sfeventmgt_domain_model_event\";s:3:\"uid\";i:10;s:3:\"pid\";i:4;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}i:4;s:91:\"/typo3/module/web/list?token=196c636e9f90b92c8db49378db764a9bfe3bb5cf&id=4&table=&pointer=1\";}s:32:\"e8f5181189149c302b2defe5b99ce260\";a:5:{i:0;s:32:\"Event (reg, cat1, multireg) [DE]\";i:1;a:5:{s:4:\"edit\";a:1:{s:32:\"tx_sfeventmgt_domain_model_event\";a:1:{i:21;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:56:\"&edit%5Btx_sfeventmgt_domain_model_event%5D%5B21%5D=edit\";i:3;a:5:{s:5:\"table\";s:32:\"tx_sfeventmgt_domain_model_event\";s:3:\"uid\";i:21;s:3:\"pid\";i:4;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}i:4;s:123:\"/typo3/module/web/list?token=196c636e9f90b92c8db49378db764a9bfe3bb5cf&id=4&table=tx_sfeventmgt_domain_model_event&pointer=1\";}}i:1;s:32:\"831786fd4612b658a2e11ed01c306877\";}s:57:\"TYPO3\\CMS\\Backend\\Utility\\BackendUtility::getUpdateSignal\";a:0:{}s:18:\"list/displayFields\";a:1:{s:32:\"tx_sfeventmgt_domain_model_event\";a:3:{i:0;s:5:\"title\";i:1;s:7:\"enddate\";i:2;s:9:\"startdate\";}}s:16:\"opendocs::recent\";a:8:{s:32:\"831786fd4612b658a2e11ed01c306877\";a:5:{i:0;s:5:\"user1\";i:1;a:5:{s:4:\"edit\";a:1:{s:8:\"fe_users\";a:1:{i:1;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:31:\"&edit%5Bfe_users%5D%5B1%5D=edit\";i:3;a:5:{s:5:\"table\";s:8:\"fe_users\";s:3:\"uid\";i:1;s:3:\"pid\";i:13;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}i:4;s:92:\"/typo3/module/web/list?token=196c636e9f90b92c8db49378db764a9bfe3bb5cf&id=13&table=&pointer=1\";}s:32:\"b85cc31aeb79546241f2165dc81f8709\";a:5:{i:0;s:22:\"Event (reg, cat1) [DE]\";i:1;a:5:{s:4:\"edit\";a:1:{s:32:\"tx_sfeventmgt_domain_model_event\";a:1:{i:2;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:55:\"&edit%5Btx_sfeventmgt_domain_model_event%5D%5B2%5D=edit\";i:3;a:5:{s:5:\"table\";s:32:\"tx_sfeventmgt_domain_model_event\";s:3:\"uid\";i:2;s:3:\"pid\";i:4;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}i:4;s:91:\"/typo3/module/web/list?token=196c636e9f90b92c8db49378db764a9bfe3bb5cf&id=4&table=&pointer=1\";}s:32:\"d566570ea5a4f3d14d856921624f055a\";a:5:{i:0;s:33:\"Event (reg, regfields, cat1) [DE]\";i:1;a:5:{s:4:\"edit\";a:1:{s:32:\"tx_sfeventmgt_domain_model_event\";a:1:{i:3;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:55:\"&edit%5Btx_sfeventmgt_domain_model_event%5D%5B3%5D=edit\";i:3;a:5:{s:5:\"table\";s:32:\"tx_sfeventmgt_domain_model_event\";s:3:\"uid\";i:3;s:3:\"pid\";i:4;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}i:4;s:91:\"/typo3/module/web/list?token=196c636e9f90b92c8db49378db764a9bfe3bb5cf&id=4&table=&pointer=1\";}s:32:\"de2cdd33419c997b28dda892c577bfd0\";a:5:{i:0;s:35:\"Event fully booked (reg, cat1) [DE]\";i:1;a:5:{s:4:\"edit\";a:1:{s:32:\"tx_sfeventmgt_domain_model_event\";a:1:{i:6;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:55:\"&edit%5Btx_sfeventmgt_domain_model_event%5D%5B6%5D=edit\";i:3;a:5:{s:5:\"table\";s:32:\"tx_sfeventmgt_domain_model_event\";s:3:\"uid\";i:6;s:3:\"pid\";i:4;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}i:4;s:91:\"/typo3/module/web/list?token=196c636e9f90b92c8db49378db764a9bfe3bb5cf&id=4&table=&pointer=1\";}s:32:\"6335fb883b0c1ed517b830be1e32a476\";a:5:{i:0;s:35:\"Event (reg, cat1, autoconfirm) [DE]\";i:1;a:5:{s:4:\"edit\";a:1:{s:32:\"tx_sfeventmgt_domain_model_event\";a:1:{i:7;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:55:\"&edit%5Btx_sfeventmgt_domain_model_event%5D%5B7%5D=edit\";i:3;a:5:{s:5:\"table\";s:32:\"tx_sfeventmgt_domain_model_event\";s:3:\"uid\";i:7;s:3:\"pid\";i:4;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}i:4;s:91:\"/typo3/module/web/list?token=196c636e9f90b92c8db49378db764a9bfe3bb5cf&id=4&table=&pointer=1\";}s:32:\"a941f45953ab26aad814218913c20e3d\";a:5:{i:0;s:44:\"Event fully booked waitlist (reg, cat1) [DE]\";i:1;a:5:{s:4:\"edit\";a:1:{s:32:\"tx_sfeventmgt_domain_model_event\";a:1:{i:8;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:55:\"&edit%5Btx_sfeventmgt_domain_model_event%5D%5B8%5D=edit\";i:3;a:5:{s:5:\"table\";s:32:\"tx_sfeventmgt_domain_model_event\";s:3:\"uid\";i:8;s:3:\"pid\";i:4;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}i:4;s:91:\"/typo3/module/web/list?token=196c636e9f90b92c8db49378db764a9bfe3bb5cf&id=4&table=&pointer=1\";}s:32:\"c57d6abb7b90844752365b70df571105\";a:5:{i:0;s:35:\"Event (reg, cat3, autoconfirm) [DE]\";i:1;a:5:{s:4:\"edit\";a:1:{s:32:\"tx_sfeventmgt_domain_model_event\";a:1:{i:17;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:56:\"&edit%5Btx_sfeventmgt_domain_model_event%5D%5B17%5D=edit\";i:3;a:5:{s:5:\"table\";s:32:\"tx_sfeventmgt_domain_model_event\";s:3:\"uid\";i:17;s:3:\"pid\";i:4;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}i:4;s:91:\"/typo3/module/web/list?token=196c636e9f90b92c8db49378db764a9bfe3bb5cf&id=4&table=&pointer=1\";}s:32:\"ea4efd4ec9a74eba1c8729aae8361ca6\";a:5:{i:0;s:40:\"Expired Event (reg, cat1, multireg) [EN]\";i:1;a:5:{s:4:\"edit\";a:1:{s:32:\"tx_sfeventmgt_domain_model_event\";a:1:{i:22;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";N;s:6:\"noView\";N;}i:2;s:56:\"&edit%5Btx_sfeventmgt_domain_model_event%5D%5B22%5D=edit\";i:3;a:5:{s:5:\"table\";s:32:\"tx_sfeventmgt_domain_model_event\";s:3:\"uid\";i:22;s:3:\"pid\";i:4;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}i:4;s:91:\"/typo3/module/web/list?token=7801962eadccd17682feb40a751f311593c4688c&id=4&table=&pointer=1\";}}}s:14:\"emailMeAtLogin\";i:0;s:8:\"titleLen\";i:50;s:8:\"edit_RTE\";s:1:\"1\";s:20:\"edit_docModuleUpload\";s:1:\"1\";s:25:\"resizeTextareas_MaxHeight\";i:500;s:4:\"lang\";s:6:\"defaul\";s:19:\"firstLoginTimeStamp\";i:1630086397;s:15:\"moduleSessionID\";a:7:{s:28:\"dashboard/current_dashboard/\";s:40:\"5b8d0afc419a78a3670256e3b3a9f0172ffaed1e\";s:10:\"web_layout\";s:40:\"5b8d0afc419a78a3670256e3b3a9f0172ffaed1e\";s:6:\"web_ts\";s:40:\"63ac208340a3412274b393936b06207c7080d08a\";s:10:\"FormEngine\";s:40:\"50176cbcc69c10798fe364764ddcdefd754cfbbc\";s:57:\"TYPO3\\CMS\\Backend\\Utility\\BackendUtility::getUpdateSignal\";s:40:\"50176cbcc69c10798fe364764ddcdefd754cfbbc\";s:18:\"list/displayFields\";s:40:\"926860861db33a72be6f1bebdf71e6dc47e1593c\";s:16:\"opendocs::recent\";s:40:\"50176cbcc69c10798fe364764ddcdefd754cfbbc\";}s:17:\"BackendComponents\";a:1:{s:6:\"States\";a:1:{s:8:\"Pagetree\";a:1:{s:9:\"stateHash\";a:4:{s:3:\"0_1\";s:1:\"1\";s:3:\"0_2\";s:1:\"0\";s:4:\"0_14\";s:1:\"1\";s:3:\"0_4\";s:1:\"1\";}}}}s:10:\"modulemenu\";s:2:\"{}\";}',NULL,NULL,1,NULL,1757077674,0,NULL,'',NULL);
/*!40000 ALTER TABLE `be_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `cache_imagesizes`
--

LOCK TABLES `cache_imagesizes` WRITE;
/*!40000 ALTER TABLE `cache_imagesizes` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_imagesizes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `cache_imagesizes_tags`
--

LOCK TABLES `cache_imagesizes_tags` WRITE;
/*!40000 ALTER TABLE `cache_imagesizes_tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_imagesizes_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `fe_groups`
--

LOCK TABLES `fe_groups` WRITE;
/*!40000 ALTER TABLE `fe_groups` DISABLE KEYS */;
INSERT INTO `fe_groups` VALUES (1,13,1586581341,1586581341,0,0,'','0','group1','','','');
/*!40000 ALTER TABLE `fe_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `fe_sessions`
--

LOCK TABLES `fe_sessions` WRITE;
/*!40000 ALTER TABLE `fe_sessions` DISABLE KEYS */;
INSERT INTO `fe_sessions` VALUES ('004a9800d068902aea6f188b7324e4033e96cf37a1089d8f05779be8f1dfd506','[DISABLED]',0,1757079302,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('091b68e415363d3bdd058ebf63065eb371beb0b99d55e603f7959a3b3966e3e5','[DISABLED]',0,1757079918,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('0e95e4f859668ee6f960a40d5d4dcaa19d3baad73ef873080c41bd0cbc9c4463','[DISABLED]',0,1757078868,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('13a10eff318b9545cc95e1364b217c71841acc21b209d8bf64e9cee0c3fa3ce5','[DISABLED]',0,1757079442,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('152051ec3082368a612ea1076346d0ddde842a8c08da7ee0d488808e8c71e738','[DISABLED]',0,1757079467,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('18b3c0b39343b4caae4a0ab63f47af67730a8babdacd3bd44a02c7c29a5e7e92','[DISABLED]',0,1757078178,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('1c29afcabdb3f702a1b5052471c6d902a67c3f82068c8136633558036f949f80','[DISABLED]',0,1757079913,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('2af84548abb2970b847bd7d85e690d2ee1689b049fbcb93e30c0125573b2deb1','[DISABLED]',0,1757079026,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('2d0310f832369f4bec75943a0cdad08fa46766c52fd9edd08a1c18a0cccf98e9','[DISABLED]',0,1757079811,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('35ccc0114067435ae555bd7cdefdc4df1b927f3f8a22a35d6e710384d8b97cb9','[DISABLED]',0,1757079853,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('383f6d5884f9b593292402c0154bd9c56f2def0a98fc66eef46de3280e51db52','[DISABLED]',0,1757079443,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('3a499e0d3089d97024b8dcfb67f565ebddf82686a474b926086523ffd00ddace','[DISABLED]',1,1757079295,'',0),('408daa7d25afe08c9efbc814c24746af5234500928818b39659c253f9326529e','[DISABLED]',0,1757078920,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('45eead72a28f9e4229df36ab58292fa7721b6debf6c564ae83b0d0b8da5fb3b9','[DISABLED]',0,1757079928,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('494d2277cfb48fedfb8050d31e4821eebb173edc249a4a682cdc9ed88eb68c40','[DISABLED]',0,1757079902,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('4debb397f4715dea62050309eb1ce8d45722e4df4dd6eae2a531785be70544b5','[DISABLED]',0,1757079468,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('52c308a39fcdc5708121c95ab61a4191cf4f58f526c735d274889015ea7cb3a6','[DISABLED]',0,1757079640,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('5612d6590364bd1fde2a3bca4d0f3159cc7887a677cfa662cf08c82a2c9c5921','[DISABLED]',0,1757079773,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('5a3cdc44157974e90a25517ea949333a45ebaf10a5a6ec1937eb82dd5e864c62','[DISABLED]',0,1757079658,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('60e77cd7f205fdef30eab1ec5a04691afd4783f8715d6d63da68c6fc7c1605d8','[DISABLED]',0,1757079011,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('6c4d163785e46837c981d0c37e6bf8f262796226f1a384bfb2d1d7e99b4fd6cf','[DISABLED]',0,1757079037,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('6e86cbc5ef40e210f791d7d3a8247ed3c7fd82fb1395b2975f5627d20bed19db','[DISABLED]',0,1757079303,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('70c215fa952aa0e86b9735aad79a411f21fe9eb5bde32bec92a565434e0b3e68','[DISABLED]',0,1757079286,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('7607a07f2e803959feabf312f9ad3ea37ce62bb4c4ef6810861ade66e44564c9','[DISABLED]',0,1757079823,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('7c227b11719e5df6fdeedeaa78715c5559ecb0d9441fd3ec68c2e3f410a96b05','[DISABLED]',0,1757078185,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('7f7d1fd575ca7ab19d65e0d786bcfa3d2502c38f2a186cfefbce54273ef0e463','[DISABLED]',0,1757079013,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('7fccd6dd887617dda9bdd909b1d4770cd7f6401a676b4ec68e1d1dc46f3d3216','[DISABLED]',1,1757079020,'',0),('8336e5d475f1c79dc97b3f63584939c1c60b52035f8d4cdaa622c2812b1af018','[DISABLED]',0,1757079566,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('8710a824793c8b9fd4785197b7825277783812e5a5841c773a17c9c57da6e27d','[DISABLED]',0,1757079242,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('8bd95de26ce32874d63e5e0f8c3eaf74e63b378ab12a42bb0478afb7163bd46d','[DISABLED]',0,1757078159,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('91c04cf1286d728d45b6856b082cc897903d28b0d6bc10b32ff209afd640520b','[DISABLED]',0,1757078196,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('922110e6dfd737c3240a89f6b0e5c062efc2d9a2316249dffd6f97a118d87f10','[DISABLED]',0,1757079800,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('94156fa4c52d08dd712640dc4cb004d25fe93ff24cf71c29e4f56a336e5279b7','[DISABLED]',0,1757079657,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('94a442b85f278c1c4703bca0563bc6fc592be673db3af91e2d8e95736fc6478a','[DISABLED]',0,1757078157,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('94ebbf5e4952a18a1823ea23c967b0877b8d6805c00d10c5de40bea7f6952622','[DISABLED]',1,1757079034,'',0),('a745406d953c16055a09da6554d226c2ef96d57ea045a346fe5101b236460f93','[DISABLED]',0,1757079879,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('aaf23f33179f4dbcf832dedea4fd7bef4a2ed9e973b6bbd08b6639545faa8ef2','[DISABLED]',0,1757079810,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('ab8caa14e65ac2f3be833e0021aa90a47ec29e9cb3589845cf9b5769284f1fd2','[DISABLED]',0,1757079298,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('b2cf585dec97913c82ec556922a87a5078309699b1849183769aae928ce41c41','[DISABLED]',0,1757079023,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('b57045b574a83fcc22b1ad425c5e0cd0181a457271d20cce807db8d3fe6d620e','[DISABLED]',0,1757079288,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('b9aa3c6a428e5c38a0b88484b6a04673c7ba3e8f3b09298bb6358d041aaffec2','[DISABLED]',1,1757079910,'',0),('ba6e3b19f3af7156705bf7cb8efcab8c064f1e3b58426a35c11b5eb60b45f730','[DISABLED]',0,1757079799,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('bb7a002f0c2a0c0e90ef830f0e947effc78d6f067b5bc54bb6061adc9bdb8625','[DISABLED]',0,1757079878,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('be0dd1db978690a8dde1fbbc8642b5a1ad5c111e79816e3d3b1618466f64b15c','[DISABLED]',1,1757079925,'',0),('c0b87008c27a3fe26d9d652ccf730a33a2dde41a5ade33f62ce534c3573ccd2e','[DISABLED]',0,1757079166,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('c3d793c640d3901aa6862ed75fedd7372b1d57bdf71ab780a53de03c742b88e4','[DISABLED]',0,1757079747,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('c3db9452a5440bdcd8def91ad32e891bf36ca31a2e78d7891825e3d826741bdd','[DISABLED]',0,1757079567,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('c4d0a13bc7d1f62378a015c54f62f0638fc389b0a8683b5a09be5f683bc3cc40','[DISABLED]',0,1757079840,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('c8339180bd46f7de40e14d0e24ea5983bc420749e4fc3c526a3272a10c97bf54','[DISABLED]',0,1757079312,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('ccfbad3a04b5cae2da375708d468c4d7453fd72823d63d037e2a10249294e4fe','[DISABLED]',0,1757079746,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('cdfa13394d3750b2783d847e7c815384fdbe60ad1e326fed3ef1548c93bf2b7d','[DISABLED]',0,1757079028,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('cfab946b3f0958a3c24eeb0745b7c4e0267ef5f7997449ffda81de8f0e0c2403','[DISABLED]',0,1757079839,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('dad3272acee1f28817aa0f3f1f0b8b339f508c93b15b1f402b8e7627f829b261','[DISABLED]',0,1757078918,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('dc7d4a079c8f86bf785b6f767c05a9486cf6e64a27207bae088fbde0a482e33a','[DISABLED]',0,1757079825,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('de1be04376f8131bcbe18d358dadfe28eb15294e0336676ff29561ae6ec605ee','[DISABLED]',0,1757078870,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('e0152dc9d408f0c8ca5a6bc20fafd151a23f99f19d344abc9c8f4f35998df24d','[DISABLED]',0,1757079854,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('e87dcf1d8ab6a2ae88bee4f0b2cfb84fd3a54d2da76dad63f305b3cc0beeef27','[DISABLED]',0,1757079641,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('f055ea6d66fdd0c1f30d25429a80a444ce8d820df187f8f6cac683b5582e1272','[DISABLED]',0,1757078187,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('f59863359e0759e257283b5b35a73825814989172b17cc115ad37696bfc9e4e9','[DISABLED]',0,1757079917,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('f5e3d8572a19fa451871080710e77b0692d345c283aa0bce26075a2be8e4d736','[DISABLED]',1,1757079309,'',0),('fc94ddd35a9a3251a088ddf50873dba4cbaaa0881e799f4be8b4f261e1c5cec1','[DISABLED]',0,1757079772,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('ff4bbfc91476f9fc469b574fa7d2a54489a89294e325d0c76028c28b0d81bce2','[DISABLED]',0,1757079900,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0);
/*!40000 ALTER TABLE `fe_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `fe_users`
--

LOCK TABLES `fe_users` WRITE;
/*!40000 ALTER TABLE `fe_users` DISABLE KEYS */;
INSERT INTO `fe_users` VALUES (1,13,1757078998,1586581354,0,0,0,0,'','0','user1','$argon2i$v=19$m=65536,t=16,p=1$VGVFWlRHWGo2aWEzLm4wRQ$QsgTbgD9saLJ7Ng51/GQso8HqxNS0Z3syK/6+nRz048','1','','','','','','','','',NULL,'','','','','','','0','',1757079925,1757079910,'','',NULL);
/*!40000 ALTER TABLE `fe_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,0,1586410645,1586409670,0,0,0,0,'',256,NULL,0,0,0,0,NULL,0,_binary '{\"doktype\":null,\"title\":null,\"slug\":null,\"nav_title\":null,\"subtitle\":null,\"seo_title\":null,\"description\":null,\"no_index\":null,\"no_follow\":null,\"canonical_link\":null,\"sitemap_changefreq\":null,\"sitemap_priority\":null,\"og_title\":null,\"og_description\":null,\"og_image\":null,\"twitter_title\":null,\"twitter_description\":null,\"twitter_image\":null,\"twitter_card\":null,\"abstract\":null,\"keywords\":null,\"author\":null,\"author_email\":null,\"lastUpdated\":null,\"layout\":null,\"newUntil\":null,\"backend_layout\":null,\"backend_layout_next_level\":null,\"content_from_pid\":null,\"target\":null,\"cache_timeout\":null,\"cache_tags\":null,\"is_siteroot\":null,\"no_search\":null,\"php_tree_stop\":null,\"module\":null,\"media\":null,\"tsconfig_includes\":null,\"TSconfig\":null,\"l18n_cfg\":null,\"hidden\":null,\"nav_hide\":null,\"starttime\":null,\"endtime\":null,\"extendToSubpages\":null,\"fe_group\":null,\"fe_login_mode\":null,\"editlock\":null,\"categories\":null,\"rowDescription\":null}',0,0,0,0,1,0,31,27,0,'Root Page','/',1,NULL,1,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1586410645,NULL,'',0,'','','',0,0,0,0,0,'','',NULL,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(2,1,1586581290,1586409695,0,0,0,0,'',832,NULL,0,0,0,0,NULL,0,_binary '{\"title\":null}',0,0,0,0,1,0,31,27,0,'Event List (all)','/event-list-all',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1586581290,NULL,'',0,'','','',0,0,0,0,0,'','',NULL,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(3,2,1586418303,1586409717,0,0,0,0,'',256,NULL,0,0,0,0,NULL,0,_binary '{\"slug\":null}',0,0,0,0,1,0,31,27,0,'Event Detail','/event-list-all/event-detail',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1586418303,NULL,'',0,'','','',0,0,0,0,0,'','',NULL,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(4,1,1586429535,1586410297,0,0,0,0,'0',64,NULL,0,0,0,0,NULL,0,_binary '{\"doktype\":null,\"title\":null,\"slug\":null,\"backend_layout\":null,\"backend_layout_next_level\":null,\"module\":null,\"media\":null,\"tsconfig_includes\":null,\"TSconfig\":null,\"hidden\":null,\"editlock\":null,\"categories\":null,\"rowDescription\":null}',0,0,0,0,1,0,31,27,0,'Events [DE]','/events-de',254,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'events',0,'','','',0,0,0,0,0,'','',NULL,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(5,1,1586581301,1586410400,1,0,0,0,'0',1152,NULL,0,0,0,0,NULL,0,_binary '{\"hidden\":null}',0,0,0,0,1,0,31,27,0,'Default Tests','/default-tests',254,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,'','',NULL,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(6,2,1586410987,1586410764,0,0,0,0,'',512,NULL,0,0,0,0,NULL,0,_binary '{\"doktype\":null,\"title\":null,\"slug\":null,\"nav_title\":null,\"subtitle\":null,\"seo_title\":null,\"description\":null,\"no_index\":null,\"no_follow\":null,\"canonical_link\":null,\"sitemap_changefreq\":null,\"sitemap_priority\":null,\"og_title\":null,\"og_description\":null,\"og_image\":null,\"twitter_title\":null,\"twitter_description\":null,\"twitter_image\":null,\"twitter_card\":null,\"abstract\":null,\"keywords\":null,\"author\":null,\"author_email\":null,\"lastUpdated\":null,\"layout\":null,\"newUntil\":null,\"backend_layout\":null,\"backend_layout_next_level\":null,\"content_from_pid\":null,\"target\":null,\"cache_timeout\":null,\"cache_tags\":null,\"is_siteroot\":null,\"no_search\":null,\"php_tree_stop\":null,\"module\":null,\"media\":null,\"tsconfig_includes\":null,\"TSconfig\":null,\"l18n_cfg\":null,\"hidden\":null,\"nav_hide\":null,\"starttime\":null,\"endtime\":null,\"extendToSubpages\":null,\"fe_group\":null,\"fe_login_mode\":null,\"editlock\":null,\"categories\":null,\"rowDescription\":null}',0,0,0,0,1,0,31,27,0,'Registration','/registration',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1586421201,NULL,'',0,'','','',0,0,0,0,0,'','',NULL,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(7,1,1586581290,1586418359,0,0,0,0,'',832,NULL,0,1,2,2,'{\"starttime\":\"parent\",\"endtime\":\"parent\",\"nav_hide\":\"parent\",\"url\":\"parent\",\"lastUpdated\":\"parent\",\"newUntil\":\"parent\",\"no_search\":\"parent\",\"shortcut\":\"parent\",\"shortcut_mode\":\"parent\",\"content_from_pid\":\"parent\",\"author\":\"parent\",\"author_email\":\"parent\",\"media\":\"parent\",\"og_image\":\"parent\",\"twitter_image\":\"parent\"}',0,_binary '{\"doktype\":1,\"title\":\"Event List (all)\",\"slug\":\"\\/event-list-all\",\"nav_title\":\"\",\"subtitle\":\"\",\"seo_title\":\"\",\"description\":null,\"canonical_link\":\"\",\"sitemap_changefreq\":\"\",\"sitemap_priority\":\"0.5\",\"og_title\":\"\",\"og_description\":null,\"twitter_title\":\"\",\"twitter_description\":null,\"twitter_card\":\"summary\",\"abstract\":null,\"keywords\":null,\"hidden\":0,\"categories\":0,\"rowDescription\":null,\"TSconfig\":null,\"php_tree_stop\":0,\"editlock\":0,\"layout\":0,\"fe_group\":\"\",\"extendToSubpages\":0,\"target\":\"\",\"cache_timeout\":0,\"cache_tags\":\"\",\"mount_pid\":0,\"is_siteroot\":0,\"mount_pid_ol\":0,\"module\":\"\",\"fe_login_mode\":0,\"l18n_cfg\":0,\"backend_layout\":\"\",\"backend_layout_next_level\":\"\",\"tsconfig_includes\":null,\"no_index\":0,\"no_follow\":0}',0,0,0,0,1,0,31,27,0,'Event List (all)','/event-list-all',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1586581290,NULL,'',0,'','','',0,0,0,0,0,'','','',0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(8,2,1586418412,1586418398,0,0,0,0,'',256,NULL,0,1,3,3,'{\"starttime\":\"parent\",\"endtime\":\"parent\",\"nav_hide\":\"parent\",\"url\":\"parent\",\"lastUpdated\":\"parent\",\"newUntil\":\"parent\",\"no_search\":\"parent\",\"shortcut\":\"parent\",\"shortcut_mode\":\"parent\",\"content_from_pid\":\"parent\",\"author\":\"parent\",\"author_email\":\"parent\",\"media\":\"parent\",\"og_image\":\"parent\",\"twitter_image\":\"parent\"}',0,_binary '{\"doktype\":1,\"title\":\"Event Detail\",\"slug\":\"\\/event-list-all\\/event-detail\",\"nav_title\":\"\",\"subtitle\":\"\",\"seo_title\":\"\",\"description\":null,\"canonical_link\":\"\",\"sitemap_changefreq\":\"\",\"sitemap_priority\":\"0.5\",\"og_title\":\"\",\"og_description\":null,\"twitter_title\":\"\",\"twitter_description\":null,\"twitter_card\":\"summary\",\"abstract\":null,\"keywords\":null,\"hidden\":0,\"categories\":0,\"rowDescription\":null,\"TSconfig\":null,\"php_tree_stop\":0,\"editlock\":0,\"layout\":0,\"fe_group\":\"\",\"extendToSubpages\":0,\"target\":\"\",\"cache_timeout\":0,\"cache_tags\":\"\",\"mount_pid\":0,\"is_siteroot\":0,\"mount_pid_ol\":0,\"module\":\"\",\"fe_login_mode\":0,\"l18n_cfg\":0,\"backend_layout\":\"\",\"backend_layout_next_level\":\"\",\"tsconfig_includes\":null,\"no_index\":0,\"no_follow\":0}',0,0,0,0,1,0,31,27,0,'Event Detail','/event-list-all/event-detail',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1586418427,NULL,'',0,'','','',0,0,0,0,0,'','','',0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(9,2,1586418437,1586418431,0,0,0,0,'',512,NULL,0,1,6,6,'{\"starttime\":\"parent\",\"endtime\":\"parent\",\"nav_hide\":\"parent\",\"url\":\"parent\",\"lastUpdated\":\"parent\",\"newUntil\":\"parent\",\"no_search\":\"parent\",\"shortcut\":\"parent\",\"shortcut_mode\":\"parent\",\"content_from_pid\":\"parent\",\"author\":\"parent\",\"author_email\":\"parent\",\"media\":\"parent\",\"og_image\":\"parent\",\"twitter_image\":\"parent\"}',0,_binary '{\"doktype\":1,\"title\":\"Registration\",\"slug\":\"\\/registration\",\"nav_title\":\"\",\"subtitle\":\"\",\"seo_title\":\"\",\"description\":null,\"canonical_link\":\"\",\"sitemap_changefreq\":\"\",\"sitemap_priority\":\"0.5\",\"og_title\":\"\",\"og_description\":null,\"twitter_title\":\"\",\"twitter_description\":null,\"twitter_card\":\"summary\",\"abstract\":null,\"keywords\":null,\"hidden\":0,\"categories\":0,\"rowDescription\":null,\"TSconfig\":null,\"php_tree_stop\":0,\"editlock\":0,\"layout\":0,\"fe_group\":\"\",\"extendToSubpages\":0,\"target\":\"\",\"cache_timeout\":0,\"cache_tags\":\"\",\"mount_pid\":0,\"is_siteroot\":0,\"mount_pid_ol\":0,\"module\":\"\",\"fe_login_mode\":0,\"l18n_cfg\":0,\"backend_layout\":\"\",\"backend_layout_next_level\":\"\",\"tsconfig_includes\":null,\"no_index\":0,\"no_follow\":0}',0,0,0,0,1,0,31,27,0,'Registration','/event-list-all/registration',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1586421210,NULL,'',0,'','','',0,0,0,0,0,'','','',0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(10,1,1586429526,1586429498,0,0,0,0,'',64,NULL,0,1,4,4,'{\"starttime\":\"parent\",\"endtime\":\"parent\",\"nav_hide\":\"parent\",\"url\":\"parent\",\"lastUpdated\":\"parent\",\"newUntil\":\"parent\",\"no_search\":\"parent\",\"shortcut\":\"parent\",\"shortcut_mode\":\"parent\",\"content_from_pid\":\"parent\",\"author\":\"parent\",\"author_email\":\"parent\",\"media\":\"parent\",\"og_image\":\"parent\",\"twitter_image\":\"parent\"}',0,_binary '{\"doktype\":254,\"title\":\"Events\",\"slug\":\"\\/events\",\"hidden\":0,\"categories\":0,\"rowDescription\":null,\"TSconfig\":null,\"php_tree_stop\":0,\"editlock\":0,\"layout\":0,\"fe_group\":\"0\",\"extendToSubpages\":0,\"target\":\"\",\"cache_timeout\":0,\"cache_tags\":\"\",\"mount_pid\":0,\"is_siteroot\":0,\"mount_pid_ol\":0,\"module\":\"events\",\"fe_login_mode\":0,\"l18n_cfg\":0,\"backend_layout\":\"\",\"backend_layout_next_level\":\"\",\"tsconfig_includes\":null,\"no_index\":0,\"no_follow\":0,\"starttime\":0,\"endtime\":0,\"nav_hide\":0,\"url\":\"\",\"lastUpdated\":0,\"newUntil\":0,\"no_search\":0,\"shortcut\":0,\"shortcut_mode\":0,\"content_from_pid\":0,\"author\":\"\",\"author_email\":\"\",\"media\":0,\"og_image\":0,\"twitter_image\":0}',0,0,0,0,1,0,31,27,0,'Events [EN]','/events-en',254,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'events',0,'','','',0,0,0,0,0,'','','',0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(11,1,1587579033,1586431308,0,0,0,0,'',1088,NULL,0,0,0,0,NULL,0,_binary '{\"doktype\":null,\"title\":null,\"slug\":null,\"nav_title\":null,\"subtitle\":null,\"seo_title\":null,\"description\":null,\"no_index\":null,\"no_follow\":null,\"canonical_link\":null,\"sitemap_changefreq\":null,\"sitemap_priority\":null,\"og_title\":null,\"og_description\":null,\"og_image\":null,\"twitter_title\":null,\"twitter_description\":null,\"twitter_image\":null,\"twitter_card\":null,\"abstract\":null,\"keywords\":null,\"author\":null,\"author_email\":null,\"lastUpdated\":null,\"layout\":null,\"newUntil\":null,\"backend_layout\":null,\"backend_layout_next_level\":null,\"content_from_pid\":null,\"target\":null,\"cache_timeout\":null,\"cache_tags\":null,\"is_siteroot\":null,\"no_search\":null,\"php_tree_stop\":null,\"module\":null,\"media\":null,\"tsconfig_includes\":null,\"TSconfig\":null,\"l18n_cfg\":null,\"hidden\":null,\"nav_hide\":null,\"starttime\":null,\"endtime\":null,\"extendToSubpages\":null,\"fe_group\":null,\"fe_login_mode\":null,\"editlock\":null,\"categories\":null,\"rowDescription\":null}',0,0,0,0,1,0,31,27,0,'Event List (category menu)','/event-list-category-menu',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1587579063,NULL,'',0,'','','',0,0,0,0,0,'','',NULL,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(12,1,1587579033,1586431322,0,0,0,0,'',1088,NULL,0,1,11,11,'{\"starttime\":\"parent\",\"endtime\":\"parent\",\"nav_hide\":\"parent\",\"url\":\"parent\",\"lastUpdated\":\"parent\",\"newUntil\":\"parent\",\"no_search\":\"parent\",\"shortcut\":\"parent\",\"shortcut_mode\":\"parent\",\"content_from_pid\":\"parent\",\"author\":\"parent\",\"author_email\":\"parent\",\"media\":\"parent\",\"og_image\":\"parent\",\"twitter_image\":\"parent\"}',0,_binary '{\"doktype\":1,\"title\":\"Event List (category menu)\",\"slug\":\"\\/event-list-category-menu\",\"nav_title\":\"\",\"subtitle\":\"\",\"seo_title\":\"\",\"description\":null,\"canonical_link\":\"\",\"sitemap_changefreq\":\"\",\"sitemap_priority\":\"0.5\",\"og_title\":\"\",\"og_description\":null,\"twitter_title\":\"\",\"twitter_description\":null,\"twitter_card\":\"summary\",\"abstract\":null,\"keywords\":null,\"hidden\":0,\"categories\":0,\"rowDescription\":null,\"TSconfig\":null,\"php_tree_stop\":0,\"editlock\":0,\"layout\":0,\"fe_group\":\"\",\"extendToSubpages\":0,\"target\":\"\",\"cache_timeout\":0,\"cache_tags\":\"\",\"mount_pid\":0,\"is_siteroot\":0,\"mount_pid_ol\":0,\"module\":\"\",\"fe_login_mode\":0,\"l18n_cfg\":0,\"backend_layout\":\"\",\"backend_layout_next_level\":\"\",\"tsconfig_includes\":null,\"no_index\":0,\"no_follow\":0,\"starttime\":0,\"endtime\":0,\"nav_hide\":0,\"url\":\"\",\"lastUpdated\":0,\"newUntil\":0,\"no_search\":0,\"shortcut\":0,\"shortcut_mode\":0,\"content_from_pid\":0,\"author\":\"\",\"author_email\":\"\",\"media\":0,\"og_image\":0,\"twitter_image\":0}',0,0,0,0,1,0,31,27,0,'Event List (category menu)','/event-list-category-menu',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1587579064,NULL,'',0,'','','',0,0,0,0,0,'','','',0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(13,1,1586581320,1586581309,0,0,0,0,'0',320,NULL,0,0,0,0,NULL,0,_binary '{\"doktype\":null,\"title\":null,\"slug\":null,\"backend_layout\":null,\"backend_layout_next_level\":null,\"module\":null,\"media\":null,\"tsconfig_includes\":null,\"TSconfig\":null,\"hidden\":null,\"editlock\":null,\"categories\":null,\"rowDescription\":null}',0,0,0,0,1,0,31,27,0,'Users','/users',254,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'fe_users',0,'','','',0,0,0,0,0,'','',NULL,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(14,1,1586581379,1586581375,0,0,0,0,'0',1856,NULL,0,0,0,0,NULL,0,_binary '{\"hidden\":null}',0,0,0,0,1,0,31,27,0,'Login','/login',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1586581516,NULL,'',0,'','','',0,0,0,0,0,'','',NULL,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(15,14,1586581479,1586581406,0,0,0,0,'1',256,NULL,0,0,0,0,NULL,0,_binary '{\"doktype\":null,\"title\":null,\"slug\":null,\"nav_title\":null,\"subtitle\":null,\"seo_title\":null,\"description\":null,\"no_index\":null,\"no_follow\":null,\"canonical_link\":null,\"sitemap_changefreq\":null,\"sitemap_priority\":null,\"og_title\":null,\"og_description\":null,\"og_image\":null,\"twitter_title\":null,\"twitter_description\":null,\"twitter_image\":null,\"twitter_card\":null,\"abstract\":null,\"keywords\":null,\"author\":null,\"author_email\":null,\"lastUpdated\":null,\"layout\":null,\"newUntil\":null,\"backend_layout\":null,\"backend_layout_next_level\":null,\"content_from_pid\":null,\"target\":null,\"cache_timeout\":null,\"cache_tags\":null,\"is_siteroot\":null,\"no_search\":null,\"php_tree_stop\":null,\"module\":null,\"media\":null,\"tsconfig_includes\":null,\"TSconfig\":null,\"l18n_cfg\":null,\"hidden\":null,\"nav_hide\":null,\"starttime\":null,\"endtime\":null,\"extendToSubpages\":null,\"fe_group\":null,\"fe_login_mode\":null,\"editlock\":null,\"categories\":null,\"rowDescription\":null}',0,0,0,0,1,0,31,27,0,'User Events','/login/user-events',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1586581557,NULL,'',0,'','','',0,0,0,0,0,'','',NULL,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(16,1,1586581490,1586581483,0,0,0,0,'',1856,NULL,0,1,14,14,'{\"starttime\":\"parent\",\"endtime\":\"parent\",\"nav_hide\":\"parent\",\"url\":\"parent\",\"lastUpdated\":\"parent\",\"newUntil\":\"parent\",\"no_search\":\"parent\",\"shortcut\":\"parent\",\"shortcut_mode\":\"parent\",\"content_from_pid\":\"parent\",\"author\":\"parent\",\"author_email\":\"parent\",\"media\":\"parent\",\"og_image\":\"parent\",\"twitter_image\":\"parent\"}',0,_binary '{\"doktype\":1,\"title\":\"Login\",\"slug\":\"\\/login\",\"nav_title\":\"\",\"subtitle\":\"\",\"seo_title\":\"\",\"description\":null,\"canonical_link\":\"\",\"sitemap_changefreq\":\"\",\"sitemap_priority\":\"0.5\",\"og_title\":\"\",\"og_description\":null,\"twitter_title\":\"\",\"twitter_description\":null,\"twitter_card\":\"summary\",\"abstract\":null,\"keywords\":null,\"hidden\":0,\"categories\":0,\"rowDescription\":null,\"TSconfig\":null,\"php_tree_stop\":0,\"editlock\":0,\"layout\":0,\"fe_group\":\"0\",\"extendToSubpages\":0,\"target\":\"\",\"cache_timeout\":0,\"cache_tags\":\"\",\"mount_pid\":0,\"is_siteroot\":0,\"mount_pid_ol\":0,\"module\":\"\",\"fe_login_mode\":0,\"l18n_cfg\":0,\"backend_layout\":\"\",\"backend_layout_next_level\":\"\",\"tsconfig_includes\":null,\"no_index\":0,\"no_follow\":0}',0,0,0,0,1,0,31,27,0,'Login','/login',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1586581529,NULL,'',0,'','','',0,0,0,0,0,'','','',0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(17,14,1586581960,1586581954,0,0,0,0,'1',256,NULL,0,1,15,15,'{\"starttime\":\"parent\",\"endtime\":\"parent\",\"nav_hide\":\"parent\",\"url\":\"parent\",\"lastUpdated\":\"parent\",\"newUntil\":\"parent\",\"no_search\":\"parent\",\"shortcut\":\"parent\",\"shortcut_mode\":\"parent\",\"content_from_pid\":\"parent\",\"author\":\"parent\",\"author_email\":\"parent\",\"media\":\"parent\",\"og_image\":\"parent\",\"twitter_image\":\"parent\"}',0,_binary '{\"doktype\":1,\"title\":\"User Events\",\"slug\":\"\\/login\\/user-events\",\"nav_title\":\"\",\"subtitle\":\"\",\"seo_title\":\"\",\"description\":null,\"canonical_link\":\"\",\"sitemap_changefreq\":\"\",\"sitemap_priority\":\"0.5\",\"og_title\":\"\",\"og_description\":null,\"twitter_title\":\"\",\"twitter_description\":null,\"twitter_card\":\"summary\",\"abstract\":null,\"keywords\":null,\"hidden\":0,\"categories\":0,\"rowDescription\":null,\"TSconfig\":null,\"php_tree_stop\":0,\"editlock\":0,\"layout\":0,\"fe_group\":\"1\",\"extendToSubpages\":0,\"target\":\"\",\"cache_timeout\":0,\"cache_tags\":\"\",\"mount_pid\":0,\"is_siteroot\":0,\"mount_pid_ol\":0,\"module\":\"\",\"fe_login_mode\":0,\"l18n_cfg\":0,\"backend_layout\":\"\",\"backend_layout_next_level\":\"\",\"tsconfig_includes\":null,\"no_index\":0,\"no_follow\":0}',0,0,0,0,1,0,31,27,0,'User Events','/login/user-events',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1586581977,NULL,'',0,'','','',0,0,0,0,0,'','','',0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(18,4,1586972298,1586972295,0,0,0,0,'0',256,NULL,0,0,0,0,NULL,0,_binary '{\"hidden\":null}',0,0,0,0,1,0,31,27,0,'Subfolder','/subfolder',254,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,'','',NULL,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(19,1,1718427943,1718427943,0,1,0,0,'0',1344,NULL,0,0,0,0,NULL,0,'',0,0,0,0,1,0,31,27,0,'Event List (verify confirm)','/event-list-verify-confirm',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,'','',NULL,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0);
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sys_be_shortcuts`
--

LOCK TABLES `sys_be_shortcuts` WRITE;
/*!40000 ALTER TABLE `sys_be_shortcuts` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_be_shortcuts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sys_category`
--

LOCK TABLES `sys_category` WRITE;
/*!40000 ALTER TABLE `sys_category` DISABLE KEYS */;
INSERT INTO `sys_category` VALUES (1,4,1586430237,1586410691,0,0,0,0,256,'',0,0,NULL,0,_binary '{\"title\":null,\"parent\":null,\"items\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"description\":null}',0,0,0,0,'Category 1 [DE]',0,14,NULL),(2,4,1586430243,1586410701,0,0,0,0,512,'',0,0,NULL,0,_binary '{\"title\":null,\"parent\":null,\"items\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"description\":null}',0,0,0,0,'Category 2 [DE]',0,2,NULL),(3,4,1586430251,1586410708,0,0,0,0,768,'',0,0,NULL,0,_binary '{\"title\":null,\"parent\":null,\"items\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"description\":null}',0,0,0,0,'Category 3 [DE]',0,0,NULL),(4,4,1586430272,1586410714,0,0,0,0,1024,'',0,0,NULL,0,_binary '{\"title\":null,\"parent\":null,\"items\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"description\":null}',0,0,0,0,'Category 4 [DE]',0,0,NULL),(5,4,1586430356,1586430349,0,0,0,0,384,'',1,1,'{\"starttime\":\"parent\",\"endtime\":\"parent\"}',1,_binary '{\"title\":\"Category 1 [DE]\",\"parent\":0,\"items\":14,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":null,\"endtime\":null,\"description\":\"\",\"l10n_parent\":0}',0,0,0,0,'Category 1 [EN]',0,14,NULL),(6,4,1586430364,1586430360,0,0,0,0,448,'',1,2,'{\"starttime\":\"parent\",\"endtime\":\"parent\"}',2,_binary '{\"title\":\"Category 2 [DE]\",\"parent\":0,\"items\":2,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":null,\"endtime\":null,\"description\":\"\",\"l10n_parent\":0}',0,0,0,0,'Category 2 [EN]',0,2,NULL),(7,4,1586430371,1586430367,0,0,0,0,480,'',1,3,'{\"starttime\":\"parent\",\"endtime\":\"parent\"}',3,_binary '{\"title\":\"Category 3 [DE]\",\"parent\":0,\"items\":0,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":null,\"endtime\":null,\"description\":\"\",\"l10n_parent\":0}',0,0,0,0,'Category 3 [EN]',0,0,NULL),(8,4,1586430378,1586430374,0,0,0,0,496,'',1,4,'{\"starttime\":\"parent\",\"endtime\":\"parent\"}',4,_binary '{\"title\":\"Category 4 [DE]\",\"parent\":0,\"items\":0,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":null,\"endtime\":null,\"description\":\"\",\"l10n_parent\":0}',0,0,0,0,'Category 4 [EN]',0,0,NULL);
/*!40000 ALTER TABLE `sys_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sys_category_record_mm`
--

LOCK TABLES `sys_category_record_mm` WRITE;
/*!40000 ALTER TABLE `sys_category_record_mm` DISABLE KEYS */;
INSERT INTO `sys_category_record_mm` VALUES (1,1,'tx_sfeventmgt_domain_model_event','category',1,1),(1,2,'tx_sfeventmgt_domain_model_event','category',2,2),(1,3,'tx_sfeventmgt_domain_model_event','category',3,2),(1,5,'tx_sfeventmgt_domain_model_event','category',4,1),(1,6,'tx_sfeventmgt_domain_model_event','category',5,2),(1,7,'tx_sfeventmgt_domain_model_event','category',6,2),(1,8,'tx_sfeventmgt_domain_model_event','category',7,2),(1,9,'tx_sfeventmgt_domain_model_event','category',8,1),(1,10,'tx_sfeventmgt_domain_model_event','category',9,2),(1,11,'tx_sfeventmgt_domain_model_event','category',10,2),(1,13,'tx_sfeventmgt_domain_model_event','category',11,1),(1,14,'tx_sfeventmgt_domain_model_event','category',12,2),(1,15,'tx_sfeventmgt_domain_model_event','category',13,2),(1,16,'tx_sfeventmgt_domain_model_event','category',14,2),(2,4,'tx_sfeventmgt_domain_model_event','category',1,1),(2,12,'tx_sfeventmgt_domain_model_event','category',2,1),(3,17,'tx_sfeventmgt_domain_model_event','category',0,1),(3,18,'tx_sfeventmgt_domain_model_event','category',0,1),(5,1,'tx_sfeventmgt_domain_model_event','category',1,0),(5,2,'tx_sfeventmgt_domain_model_event','category',2,1),(5,3,'tx_sfeventmgt_domain_model_event','category',3,1),(5,5,'tx_sfeventmgt_domain_model_event','category',4,0),(5,6,'tx_sfeventmgt_domain_model_event','category',5,1),(5,7,'tx_sfeventmgt_domain_model_event','category',6,1),(5,8,'tx_sfeventmgt_domain_model_event','category',7,1),(5,9,'tx_sfeventmgt_domain_model_event','category',8,0),(5,10,'tx_sfeventmgt_domain_model_event','category',9,1),(5,11,'tx_sfeventmgt_domain_model_event','category',10,1),(5,13,'tx_sfeventmgt_domain_model_event','category',11,0),(5,14,'tx_sfeventmgt_domain_model_event','category',12,1),(5,15,'tx_sfeventmgt_domain_model_event','category',13,1),(5,16,'tx_sfeventmgt_domain_model_event','category',14,1),(6,4,'tx_sfeventmgt_domain_model_event','category',1,0),(6,12,'tx_sfeventmgt_domain_model_event','category',2,0);
/*!40000 ALTER TABLE `sys_category_record_mm` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sys_csp_resolution`
--

LOCK TABLES `sys_csp_resolution` WRITE;
/*!40000 ALTER TABLE `sys_csp_resolution` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_csp_resolution` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sys_file`
--

LOCK TABLES `sys_file` WRITE;
/*!40000 ALTER TABLE `sys_file` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sys_file_collection`
--

LOCK TABLES `sys_file_collection` WRITE;
/*!40000 ALTER TABLE `sys_file_collection` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_file_collection` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sys_file_metadata`
--

LOCK TABLES `sys_file_metadata` WRITE;
/*!40000 ALTER TABLE `sys_file_metadata` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_file_metadata` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sys_file_processedfile`
--

LOCK TABLES `sys_file_processedfile` WRITE;
/*!40000 ALTER TABLE `sys_file_processedfile` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_file_processedfile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sys_file_reference`
--

LOCK TABLES `sys_file_reference` WRITE;
/*!40000 ALTER TABLE `sys_file_reference` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_file_reference` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sys_file_storage`
--

LOCK TABLES `sys_file_storage` WRITE;
/*!40000 ALTER TABLE `sys_file_storage` DISABLE KEYS */;
INSERT INTO `sys_file_storage` VALUES (1,0,1586409675,1586409675,0,'This is the local fileadmin/ directory. This storage mount has been created automatically by TYPO3.','fileadmin/ (auto-created)','Local','<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"basePath\">\n                    <value index=\"vDEF\">fileadmin/</value>\n                </field>\n                <field index=\"pathType\">\n                    <value index=\"vDEF\">relative</value>\n                </field>\n                <field index=\"caseSensitive\">\n                    <value index=\"vDEF\"></value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>',1,1,1,1,1,1,NULL);
/*!40000 ALTER TABLE `sys_file_storage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sys_filemounts`
--

LOCK TABLES `sys_filemounts` WRITE;
/*!40000 ALTER TABLE `sys_filemounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_filemounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sys_messenger_messages`
--

LOCK TABLES `sys_messenger_messages` WRITE;
/*!40000 ALTER TABLE `sys_messenger_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_messenger_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sys_news`
--

LOCK TABLES `sys_news` WRITE;
/*!40000 ALTER TABLE `sys_news` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sys_note`
--

LOCK TABLES `sys_note` WRITE;
/*!40000 ALTER TABLE `sys_note` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_note` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sys_redirect`
--

LOCK TABLES `sys_redirect` WRITE;
/*!40000 ALTER TABLE `sys_redirect` DISABLE KEYS */;
INSERT INTO `sys_redirect` VALUES (1,0,1586418321,1586409733,1,0,0,0,'sf-event-mgt-acceptance-v10.typo3.local','/autogenerated-2/en/',0,0,0,0,'/autogenerated-2/en/event-list',307,0,0,0,0,NULL,0),(2,0,1586418324,1586409733,1,0,0,0,'sf-event-mgt-acceptance-v10.typo3.local','/autogenerated-2/en/event-detasil',0,0,0,0,'/autogenerated-2/en/event-list/event-detasil',307,0,0,0,0,NULL,0),(3,0,1586418326,1586409742,1,0,0,0,'sf-event-mgt-acceptance-v10.typo3.local','/autogenerated-2/en/event-list/event-detasil',0,0,0,0,'/autogenerated-2/en/event-list/event-detail',307,0,0,0,0,NULL,0),(4,0,1586418328,1586418303,1,0,0,0,'sf-event-mgt-acceptance-v10.typo3.local','/de/event-list',0,0,0,0,'/de/event-list-all',307,0,0,0,0,NULL,0),(5,0,1586418330,1586418303,1,0,0,0,'sf-event-mgt-acceptance-v10.typo3.local','/de/event-list/event-detail',0,0,0,0,'/de/event-list-all/event-detail',307,0,0,0,0,NULL,0),(6,0,1586418379,1586418366,1,0,0,0,'sf-event-mgt-acceptance-v10.typo3.local','/en/translate-to-english-event-list-all',0,0,0,0,'/en/event-list-all',307,0,0,0,0,NULL,0),(7,0,1586418408,1586418406,1,0,0,0,'sf-event-mgt-acceptance-v10.typo3.local','/en/event-list-all/translate-to-english-event-detail',0,0,0,0,'/en/event-list-all/event-detail',307,0,0,0,0,NULL,0),(8,0,1586418438,1586418437,1,0,0,0,'sf-event-mgt-acceptance-v10.typo3.local','/en/event-list-all/translate-to-english-registration',0,0,0,0,'/en/event-list-all/registration',307,0,0,0,0,NULL,0),(9,0,1586429511,1586429507,1,0,0,0,'sf-event-mgt-acceptance-v10.typo3.local','/en/translate-to-english-events',0,0,0,0,'/en/events-en',307,0,0,0,0,NULL,0),(10,0,1586429531,1586429526,1,0,0,0,'sf-event-mgt-acceptance-v10.typo3.local','/de/events',0,0,0,0,'/de/events-de',307,0,0,0,0,NULL,0),(11,0,1586431332,1586431329,1,0,0,0,'sf-event-mgt-acceptance-v10.typo3.local','/en/translate-to-english-event-list-category-menu',0,0,0,0,'/en/event-list-category-menu',307,0,0,0,0,NULL,0),(12,0,1586581493,1586581490,1,0,0,0,'sf-event-mgt-acceptance-v10.typo3.local','/en/translate-to-english-login',0,0,0,0,'/en/login',307,0,0,0,0,NULL,0),(13,0,1586581963,1586581960,1,0,0,0,'sf-event-mgt-acceptance-v10.typo3.local','/en/login/translate-to-english-user-events',0,0,0,0,'/en/login/user-events',307,0,0,0,0,NULL,0);
/*!40000 ALTER TABLE `sys_redirect` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sys_refindex`
--

LOCK TABLES `sys_refindex` WRITE;
/*!40000 ALTER TABLE `sys_refindex` DISABLE KEYS */;
INSERT INTO `sys_refindex` VALUES ('001cd2e4390e0152274a1a31cabbf067','fe_users',1,'usergroup','','','',0,0,'fe_groups',1,''),('0023d602b989294bcc868365aa8663f8','tx_sfeventmgt_domain_model_registration',547,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('00c1d7eca02403f32aa88488dc90bb11','tx_sfeventmgt_domain_model_registration',69,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',33,''),('00d58dcc5e337fa60d20f31347d1cb9c','tx_sfeventmgt_domain_model_registration',343,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('00d7b2620a43080383be5bc913bf9d9c','tt_content',4,'pi_flexform','additional/lDEF/settings.registrationPid/vDEF/','','',0,0,'pages',6,''),('01479f26d6545968a6594e6e1bb17705','tx_sfeventmgt_domain_model_registration',339,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('01862f78265593278439f650d01db7f5','tx_sfeventmgt_domain_model_registration',646,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',39,''),('01d66ad9773e90d61633b36c0067a7bb','tx_sfeventmgt_domain_model_registration',253,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('021687ac9bd9629994729907e6b06590','tx_sfeventmgt_domain_model_registration',423,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('0219e76748bc25306811df251c0a6189','tx_sfeventmgt_domain_model_registration',421,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('02393a3dbe19db500a5de171b5a7e289','tx_sfeventmgt_domain_model_registration',359,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('029b3ce6a7e65defaeae33175b90f11b','tx_sfeventmgt_domain_model_registration',30,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_registration',1,''),('02d9dfddf87973ad41f2e2282ca5c96d','tx_sfeventmgt_domain_model_registration',157,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',75,''),('032877d26b8ad32a04228c3444cd5443','tx_sfeventmgt_domain_model_registration',308,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('036c02f74238b3f462500d33c6464c46','tx_sfeventmgt_domain_model_registration',348,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('03975fc7a02b1f7194f0ab9b958d597f','tx_sfeventmgt_domain_model_registration',530,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('039c054e23e995f7f2a77b13ab136267','tx_sfeventmgt_domain_model_registration',590,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('03ef35de0afaf8ca4062b1134615d243','tx_sfeventmgt_domain_model_registration',374,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-en@sfeventmgt.local'),('04711e4abe001b0305ab7c7cd4398c6a','tx_sfeventmgt_domain_model_registration',442,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('050db44015b502d33aecfc3236a9a83f','tx_sfeventmgt_domain_model_registration',498,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('0542b4bcd02825ac7711cd1953a7ee44','tx_sfeventmgt_domain_model_registration',586,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',584,''),('056748ad206752aa796816e03192e4f0','tx_sfeventmgt_domain_model_registration',551,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('05b065ee023deb6b378f70ae91ecdba5','tx_sfeventmgt_domain_model_registration_fieldvalue',39,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('05f67795b4d68a9bac382f6710d217c5','sys_category',8,'l10n_parent','','','',0,0,'sys_category',4,''),('06135df612f2ab302b3c3b32de23e5dd','tx_sfeventmgt_domain_model_registration',445,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',443,''),('061727233d248ff9ce7c15172a3c2dac','tx_sfeventmgt_domain_model_registration',240,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('06221b168d712f176c2e68d66e34ff79','tx_sfeventmgt_domain_model_registration',385,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',2,''),('064cccefe78186f79a1eab43d66dbc61','tx_sfeventmgt_domain_model_registration',411,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('06893fa851dd214846f55f875ef5cb8d','tx_sfeventmgt_domain_model_registration',49,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',24,''),('06a12329788a76995ac795edc982c664','pages',16,'l10n_parent','','','',0,0,'pages',14,''),('06bc8bd01cda02e7cc871f20bdfa6fac','tx_sfeventmgt_domain_model_registration',249,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',4,''),('06e8e9a7dd744609279017a7f3123a09','tx_sfeventmgt_domain_model_registration',270,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('073cb44bf0ecb209efcca4f00cd7367d','tx_sfeventmgt_domain_model_registration',482,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-en@sfeventmgt.local'),('0743d3ff63f91f074f5ebe8ec7131986','tx_sfeventmgt_domain_model_registration',439,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',9,''),('074f31ffbf10337433026534e07b1550','tx_sfeventmgt_domain_model_registration',117,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',57,''),('078c818205e3ea32e570e42902c7ed25','tx_sfeventmgt_domain_model_registration',480,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('07cafde600366a87f463f05b580d9049','tx_sfeventmgt_domain_model_registration',313,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('0835db035594e64ed5ca6adbc1b717ca','tx_sfeventmgt_domain_model_registration',315,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('08d14c1e9387cd78f36321913c4d8ff2','tx_sfeventmgt_domain_model_registration',619,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('0951b546fc625b1085861c88d2d513f7','tx_sfeventmgt_domain_model_registration',273,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',271,''),('098250fbd0c543c7ff7fedd2685b705a','tx_sfeventmgt_domain_model_registration',364,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('09b6e715c21bfcdf22f9b96787d7834e','tx_sfeventmgt_domain_model_registration',367,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',6,''),('09bae10d3ff8749744100af11ba9469a','tx_sfeventmgt_domain_model_registration',453,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('09cbe376dcf50977850fa723ec334eb6','tx_sfeventmgt_domain_model_registration',503,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('09db00e1367da8685562ca8869abcef5','tx_sfeventmgt_domain_model_registration',416,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('09e5bcf858a17624896397b0c6879759','tx_sfeventmgt_domain_model_registration',145,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',70,''),('0a0d066f1bec88a362c373fe12285ad4','tx_sfeventmgt_domain_model_registration_fieldvalue',1,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('0a2c13524ea592a853149c8fc0cfe894','tx_sfeventmgt_domain_model_registration',628,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',36,''),('0a946dcf0e1e4fc34b37bcef38b5065c','sys_category',1,'items','','','',12,0,'tx_sfeventmgt_domain_model_event',15,''),('0ab00ace433cfd4166fe9d84cf1f7a25','tx_sfeventmgt_domain_model_registration',384,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('0b10b1094ff025ff32c1de3d7be6da72','tx_sfeventmgt_domain_model_registration',113,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',55,''),('0b1b2c64e2a2edb50bd1a01b2d77078b','tx_sfeventmgt_domain_model_registration',10,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',3,''),('0bddac807b42566e9bf2643740694817','tx_sfeventmgt_domain_model_registration',248,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('0c178d36297abbcc716c292c7074f852','tx_sfeventmgt_domain_model_registration',256,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-de@sfeventmgt.local'),('0c4b70e2ed24b88655d11e13f2647a18','tx_sfeventmgt_domain_model_registration',576,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',27,''),('0ca5755f6af71a161202bbdbe4f2eda4','tx_sfeventmgt_domain_model_registration',241,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('0cefd5c1873c2b9d36afe1985856eff8','tx_sfeventmgt_domain_model_registration',305,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('0cf4bdf939c5fd9efd02a8d695079a7b','tx_sfeventmgt_domain_model_registration',568,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('0db476a9f8ec221fcda1fb9fae157f14','tx_sfeventmgt_domain_model_registration',227,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',226,''),('0dc5b19fca402dc76412a7da0b27a32a','tx_sfeventmgt_domain_model_registration_field',3,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('0dcb0372b0dbe8911c42986c21493a9f','tx_sfeventmgt_domain_model_registration',471,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',470,''),('0de23378023496ace28ded2218739d7f','tx_sfeventmgt_domain_model_registration',457,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('0de8048c05cd2adea2fe46e61f684f33','tx_sfeventmgt_domain_model_registration',498,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',4,''),('0deac8e1bd898ccf4113a57ae25935fd','tt_content',7,'pi_flexform','additional/lDEF/settings.detailPid/vDEF/','','',0,0,'pages',3,''),('0eac28ea9c26e3e9589baaa19a63381d','tx_sfeventmgt_domain_model_registration',558,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('0f31efed8cd8e2a95764e5f4c4787716','tx_sfeventmgt_domain_model_registration',229,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-en@sfeventmgt.local'),('0f7dca3022881b4565366291b345b8aa','sys_category',2,'items','','','',0,0,'tx_sfeventmgt_domain_model_event',4,''),('0f95d3c616384c13b487931ac1425ce4','tx_sfeventmgt_domain_model_registration',653,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-de@sfeventmgt.local'),('1074d1421ec28b5ed1cf5f647dcf5877','tx_sfeventmgt_domain_model_registration',612,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',610,''),('108c59c18e28c53a6c35ee621091b974','tx_sfeventmgt_domain_model_registration',224,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('108eba4dd8bc05cd13c2c5c23d9b2c61','tx_sfeventmgt_domain_model_registration',452,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('10d18e146f1060988efe13daca4a7841','sys_category',6,'l10n_parent','','','',0,0,'sys_category',2,''),('10e2fc5184c739f89429ad1d11cf5210','tx_sfeventmgt_domain_model_registration',409,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('110538dd76ef21792d93fbfabd796e26','tx_sfeventmgt_domain_model_registration',426,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('115b5b15de611df7b444aaa4e0dc7d0b','tx_sfeventmgt_domain_model_registration',580,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('12afad8a505a2a6528d7dd105f2fcfab','tx_sfeventmgt_domain_model_registration',507,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('131f862ba21e3cb7257ef42805122261','tx_sfeventmgt_domain_model_registration',574,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-en@sfeventmgt.local'),('13b33b58ee159c41f774841e42deff09','tx_sfeventmgt_domain_model_registration',220,'fe_user','','','',0,0,'fe_users',1,''),('13fc65214a8cd3c489230be0c3d4e051','tx_sfeventmgt_domain_model_registration',614,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',32,''),('14199c8ca6458a02c39ecdea83f06798','tx_sfeventmgt_domain_model_registration',554,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',552,''),('1443ef9274da2360b5467fefffff1f5e','tx_sfeventmgt_domain_model_registration',571,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('147adee42c5426bd72f62e9a749bbfd0','sys_category',5,'items','','','',4,0,'tx_sfeventmgt_domain_model_event',6,''),('14828154c0958b1e587bdc2d30e15038','tx_sfeventmgt_domain_model_registration',125,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',59,''),('14a7c33052cc2cda292a6c3903ed1ac2','tx_sfeventmgt_domain_model_registration',313,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',17,''),('14d13bc3879232a43ed79263a720f3db','tx_sfeventmgt_domain_model_registration',314,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('152faef5ffefcf8653d6c493f4adb40e','tx_sfeventmgt_domain_model_registration',548,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('153874bec868244f893b2d0c647331c8','pages',17,'fe_group','','','',0,0,'fe_groups',1,''),('15eade567571505300ebe53ad9a92191','tx_sfeventmgt_domain_model_registration',240,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',1,''),('161552879a6cbfe8ea291c2af336442f','pages',9,'l10n_parent','','','',0,0,'pages',6,''),('16227a51efdf0f32a68b2f65c719abbb','tx_sfeventmgt_domain_model_registration_fieldvalue',18,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('164b2ace5b2137c1e7151b42107b43ac','tx_sfeventmgt_domain_model_registration',513,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('16a342a1a1ec2bc51a5aad7c82f5cb1c','tx_sfeventmgt_domain_model_registration',611,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',610,''),('16d7eac567e659f61147fe584e239792','tx_sfeventmgt_domain_model_registration',436,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('16f4014ab652696d3802f3556e566a02','sys_category',5,'items','','','',13,0,'tx_sfeventmgt_domain_model_event',16,''),('17d988c4b93d68073a95d4ee9703d63f','tx_sfeventmgt_domain_model_registration',255,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('17f1589c7dc3e0cc77328c8647d3dde5','tx_sfeventmgt_domain_model_event',6,'registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',1,''),('184579ced74577fbbaec065c8fb46c8a','tx_sfeventmgt_domain_model_registration',399,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',398,''),('186cfc369d0a4fdbe0eab0ed24c32d20','tt_content',8,'l18n_parent','','','',0,0,'tt_content',7,''),('18e1cb7eeec8e45595ec03ba7ed34cd0','tx_sfeventmgt_domain_model_registration',626,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-en@sfeventmgt.local'),('19eb9dcdd0c4e53de29d87bd88e4ea09','tx_sfeventmgt_domain_model_registration',545,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('1a1e7c6b22ac944ae3a166ea9e5b3b9c','tx_sfeventmgt_domain_model_registration',266,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('1a45894ac213142cca7a6303b34d5f15','pages',10,'l10n_parent','','','',0,0,'pages',4,''),('1b741329141d75f6c1e1ded573af08a1','tx_sfeventmgt_domain_model_registration',623,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('1be1760be68160c89c755e83c6e6b51e','tx_sfeventmgt_domain_model_event',15,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_event',7,''),('1c5abce7fb56f918f57282be90b4edaa','tx_sfeventmgt_domain_model_registration',27,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',14,''),('1cf58e2be60030dab145863d997cdb52','tx_sfeventmgt_domain_model_registration',638,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('1d595c1e4ce5a01bdd1c1b595aa34be1','tx_sfeventmgt_domain_model_registration',267,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',8,''),('1d7ded1a3db509c5d53c63dff4f220d7','tx_sfeventmgt_domain_model_registration',484,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',20,''),('1ddf299219f2c502f8f0f7cd01facda4','tx_sfeventmgt_domain_model_registration',97,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',47,''),('1e9d5b60c9daa8d36de7472cc5cf73ea','tx_sfeventmgt_domain_model_registration',606,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',29,''),('1ed62131b7ed34fa977a16101ff3b935','tx_sfeventmgt_domain_model_registration',503,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',5,''),('1f86a1382affcba5f76ca8d353cf40e7','tx_sfeventmgt_domain_model_registration',667,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('1fa33d1166fa6a1f6d30cd006117b792','tx_sfeventmgt_domain_model_registration_fieldvalue',15,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('1fb600490e113efa306786d5aee144e6','tx_sfeventmgt_domain_model_registration',231,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',4,''),('1fbe304329dca9b6237c6b563d35005d','tx_sfeventmgt_domain_model_registration',297,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('205196235b91b8be325621f00bc73692','tx_sfeventmgt_domain_model_registration',12,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',5,''),('218c4ecd4b4c01188f432de01103e9c2','tx_sfeventmgt_domain_model_event',3,'registration_fields','','','',1,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('21b34861138f8bf9b3662460cc1432aa','tx_sfeventmgt_domain_model_registration',65,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',31,''),('21d7366697c81e1031c86325768cf42c','tx_sfeventmgt_domain_model_registration',345,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('222d6c7ce51cadbf6535f6607a5de0ea','tx_sfeventmgt_domain_model_registration',637,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',38,''),('223922715cc82b5b416081181c4e5607','tx_sfeventmgt_domain_model_registration',285,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('228fac7905b1370bdbe31f1c6b514bd9','tx_sfeventmgt_domain_model_registration',403,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('22d0a19a058bcd6a7d8bff0e7b446070','tx_sfeventmgt_domain_model_registration',508,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',8,''),('22e9b9e3cec6b6652f7b652befddf113','tx_sfeventmgt_domain_model_registration',361,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('2316ecd948a3a6f59e3ce6cd0f286b87','tx_sfeventmgt_domain_model_registration',161,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',77,''),('23170313f549b406162f9fcb092f0ec3','tx_sfeventmgt_domain_model_registration',93,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',46,''),('235b000170570b8f7a0b12671f7f13f4','tx_sfeventmgt_domain_model_registration',433,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('236ac8444ab988100aa60eaabb0aeb8b','tx_sfeventmgt_domain_model_registration',326,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('238b7b982340dbc7d23a5562d23f0f7e','pages',8,'sys_language_uid','','','',0,0,'sys_language',1,''),('23ad20e968188ed6ee255763b32ec06f','tx_sfeventmgt_domain_model_registration',585,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('2414bf977dbedb98903a3146c3e84e58','tx_sfeventmgt_domain_model_registration',409,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',407,''),('241a4c310a636bb309b072b854920595','tx_sfeventmgt_domain_model_registration',258,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('2436d09ea27bf07a35fc37194febf6a1','tx_sfeventmgt_domain_model_registration',137,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',66,''),('247fef25e205a54d7827a97ac26584df','tx_sfeventmgt_domain_model_registration',435,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('24c821b5fcfdc511cbb78c0a7454fde2','tx_sfeventmgt_domain_model_registration',619,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',34,''),('25b2cdb2b556df3824c28df3188ffb4b','tx_sfeventmgt_domain_model_event',9,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_event',1,''),('25b77149e19fc0f73682d73925f272f1','tt_content',10,'pi_flexform','sDEF/lDEF/settings.pages/vDEF/','','',0,0,'pages',13,''),('25baab69d437bba7a8714091e77fc307','tt_content',6,'pi_flexform','additional/lDEF/settings.detailPid/vDEF/','','',0,0,'pages',3,''),('25c4687ae8a1dc518863557fad51c1ba','tx_sfeventmgt_domain_model_registration',550,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',549,''),('2628df97878dfe9afcd7ade041b65da4','tx_sfeventmgt_domain_model_registration',548,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',546,''),('26c5d8087407595ecf9a98440e7bd66f','tx_sfeventmgt_domain_model_registration',354,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',353,''),('26d85ac3084b5b31ddd5d1cc3416ed30','tx_sfeventmgt_domain_model_registration',310,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',308,''),('2717f91966a0754900e30c6e08c777d2','tx_sfeventmgt_domain_model_registration',502,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('274cf53d838470615be97df1f064add8','tx_sfeventmgt_domain_model_registration_fieldvalue',38,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('276e1e3d53a6bb3006af60c915d78b03','tx_sfeventmgt_domain_model_registration',379,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('27ae0e3fc55cf67c2ac9ca412e432540','tx_sfeventmgt_domain_model_registration_fieldvalue',35,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('2807c8ac933beb769218f70da97649db','tx_sfeventmgt_domain_model_registration_fieldvalue',20,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('28a745c34e41846b4f3eda676e225b6a','tx_sfeventmgt_domain_model_registration',504,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('2959b84703088ed34eed210e0264b245','tx_sfeventmgt_domain_model_registration',465,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('29da1fe7abfb191d7869d5c220b43619','tx_sfeventmgt_domain_model_registration',438,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('2a02926f226336ddc77a7b4488ce8291','tx_sfeventmgt_domain_model_registration',543,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('2a0aa2d750715881c2e403912458aef9','tx_sfeventmgt_domain_model_registration',462,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('2acbc50a5ae0f461b3d638341fee43cc','tx_sfeventmgt_domain_model_registration',473,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-de@sfeventmgt.local'),('2af76c2c444f890ffb983a0e0d326bbe','tx_sfeventmgt_domain_model_registration',464,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-en@sfeventmgt.local'),('2b23c4f1ef16369bb90a5f537f0a4d85','tx_sfeventmgt_domain_model_registration',596,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('2b27822572dcf16557952a471488d5f0','tx_sfeventmgt_domain_model_registration',403,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',1,''),('2b73ebfd172a9a8d835bd589857a4757','tx_sfeventmgt_domain_model_registration',403,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',2,''),('2bbf278b1f34ba81eb1f7b4a5fdcc73c','tx_sfeventmgt_domain_model_registration',252,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('2c02466ab7731322cca9380c8bb757d0','sys_category',1,'items','','','',10,0,'tx_sfeventmgt_domain_model_event',13,''),('2c427144c5e4dc38b7a7d74c70e2a206','tx_sfeventmgt_domain_model_registration',81,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',39,''),('2c67f81449fb6340b64a5ed638846fa7','tx_sfeventmgt_domain_model_registration',651,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',650,''),('2c96b69002cf707333716c3a9ee74ee9','tx_sfeventmgt_domain_model_registration',660,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('2cadfca4bd87a582abb11abec1bd14cb','tx_sfeventmgt_domain_model_registration',606,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',30,''),('2cc31ac7e8cacd932f5c189f01139f41','tx_sfeventmgt_domain_model_registration',645,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('2ccdd7272892772617b2cb344726e76f','tx_sfeventmgt_domain_model_registration',598,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('2cd997f71e9f50176b4b65fe17e40e5e','tx_sfeventmgt_domain_model_registration',475,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',17,''),('2cfa4b124aa4200e262289d0409942ca','tx_sfeventmgt_domain_model_registration',522,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('2d7014950e3049d5820f6d75cd44240f','tx_sfeventmgt_domain_model_event',16,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_event',8,''),('2d9796470ac839e427c2ea9a9fdce7cd','tx_sfeventmgt_domain_model_registration',89,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',43,''),('2ddb19842ea7d4881f05d496c33074b1','tx_sfeventmgt_domain_model_registration',429,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('2def9fbb0198878fe30cec00f0fe4108','tx_sfeventmgt_domain_model_registration',566,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('2e234cf063e500452ca61da5f8b0a9c4','tx_sfeventmgt_domain_model_registration',77,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',37,''),('2eb89762380820e79b1788c08a30467b','tx_sfeventmgt_domain_model_registration',427,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('2f57a625c3a65bd2500bf49edd2e020a','tx_sfeventmgt_domain_model_registration',498,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',3,''),('2f6f64bab21a80d81e497a7c592132ac','tx_sfeventmgt_domain_model_registration',363,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',362,''),('2f779b08e68d3e8eb94a0553d3d77efc','tx_sfeventmgt_domain_model_registration',603,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('2f81aa147cb6cd91a960622e57a638d9','tx_sfeventmgt_domain_model_registration',249,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('2f8635ec7e3e4cba80637f97b198c6b0','tx_sfeventmgt_domain_model_registration',663,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('2fa1373d4e8a9d6751097faa081cf0d7','sys_category',1,'items','','','',3,0,'tx_sfeventmgt_domain_model_event',5,''),('2fa448ee42d26dec9268d7d24c5dce5b','tx_sfeventmgt_domain_model_registration',547,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',546,''),('2fb32db6a260d8878a492d1e97aa65b4','tx_sfeventmgt_domain_model_registration',223,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('3036f12294ab4f282b8dd0b935b012bb','tx_sfeventmgt_domain_model_registration',304,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',16,''),('3167e3677f602c5644971960cd484aaf','tx_sfeventmgt_domain_model_registration',61,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',29,''),('317f2c4a2c529dbd696f6abf4b0cd314','tx_sfeventmgt_domain_model_registration',477,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('31a7d343d55c2a09c7fd58fb4d930113','tt_content',7,'pi_flexform','categoryMenu/lDEF/settings.categoryMenu.categories/vDEF/','','',0,0,'sys_category',1,''),('31d69394ba06f54d5eef16eb070e9ab3','tx_sfeventmgt_domain_model_registration',262,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('31dddaf4eeafc4d58d960e7cde393374','tt_content',5,'pi_flexform','additional/lDEF/settings.registrationPid/vDEF/','','',0,0,'pages',6,''),('31e76451006e09188ef09aebc29854f9','tx_sfeventmgt_domain_model_registration',567,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',26,''),('31f58072c837852871520b88f4c2e262','tx_sfeventmgt_domain_model_registration',205,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',2,''),('323480f92213271c2116257a9a90a976','tx_sfeventmgt_domain_model_registration',294,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('32596f5b70994187b422e76735d794f4','tx_sfeventmgt_domain_model_registration',509,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('32d233e52b883041797febf5322ddc5b','tx_sfeventmgt_domain_model_registration',492,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('32f2ff2d6dade53946d13d913479204f','tx_sfeventmgt_domain_model_event',22,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_event',21,''),('32fd0d18af38a3bf4628dd01b0464798','tx_sfeventmgt_domain_model_registration',369,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('332c451dcc7c5a8f614205ddc9cefe1c','tx_sfeventmgt_domain_model_registration',601,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',599,''),('333b0aa988b309fac92da7536e29d899','tx_sfeventmgt_domain_model_registration',619,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',33,''),('33b66723b4b69973dae32ad18e060bff','tx_sfeventmgt_domain_model_registration',560,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('3407ab9b2da54ee9ff3aea24ce046097','tx_sfeventmgt_domain_model_registration',328,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('3412018483558d5f38f417c6c91e8d47','tx_sfeventmgt_domain_model_registration',414,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('342cdb5db51dad5665363b1a036bd5dd','tx_sfeventmgt_domain_model_event',23,'registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',220,''),('34893349f2aecb501800fea6a417f816','tx_sfeventmgt_domain_model_registration',355,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('34b986ef538f8bad4c8064c76b7d674d','tx_sfeventmgt_domain_model_registration',642,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('34ec836714672e11cecf901562aed8e8','tx_sfeventmgt_domain_model_registration',551,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',549,''),('3500bdaf9e3d0ef09655db430f3aeb81','tx_sfeventmgt_domain_model_registration',559,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('354094367dabbf377548c0d4f7c70c4a','tx_sfeventmgt_domain_model_registration',328,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',326,''),('35d989867f55163b889cfe913c38f8a2','tx_sfeventmgt_domain_model_registration',661,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',659,''),('369b82a1fd5211eba45b43874f638ef3','tx_sfeventmgt_domain_model_registration',242,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('36b057fe4f69268b85d5510330f3e775','sys_category',5,'items','','','',3,0,'tx_sfeventmgt_domain_model_event',5,''),('36e6a9391106af5fa60f0a7708fe0634','tx_sfeventmgt_domain_model_registration',245,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('3773525e00f15d122a6c6b2ab7416137','tx_sfeventmgt_domain_model_registration_fieldvalue',22,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('3775bd47246b285dc84ce7d625e09a04','tx_sfeventmgt_domain_model_registration',435,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',434,''),('37e4a10bb488cf84d544d798ec6a69dc','tx_sfeventmgt_domain_model_registration_fieldvalue',17,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('3857717a67ac76a32d37dcc4e36085ab','tx_sfeventmgt_domain_model_registration',194,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',87,''),('387099e2ea2e8fd6f00b05c10957c3c5','sys_category',1,'items','','','',7,0,'tx_sfeventmgt_domain_model_event',9,''),('3877537ed75a33515f61147ccfe7f18e','tx_sfeventmgt_domain_model_registration',346,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',344,''),('38a5165f8d5f2f40dffae19a64f08d26','tx_sfeventmgt_domain_model_registration',640,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('38bc08dbe87c85e2eadd1423c238000d','tx_sfeventmgt_domain_model_registration',643,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('38c47092c451a30da8840b81e716c35c','sys_category',5,'items','','','',2,0,'tx_sfeventmgt_domain_model_event',3,''),('38d96076180321ead895b20195c8bb8c','tx_sfeventmgt_domain_model_registration',573,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',571,''),('394f08eae6dac17c8bb218c1dd8e674a','tx_sfeventmgt_domain_model_registration',440,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('3970ffd2713a5f6ba08ea93e0b2587a4','tx_sfeventmgt_domain_model_registration',655,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',42,''),('399411fb4302bd2451fc86448b4adcbf','tx_sfeventmgt_domain_model_registration',331,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',21,''),('39c82315e4a6aad43de9b676a5f87fa0','tx_sfeventmgt_domain_model_registration',592,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',590,''),('3a210ee944ce29027555ac6e8aece72a','tx_sfeventmgt_domain_model_registration',652,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('3a21a5d40bf420bb173703447f6b79b0','tx_sfeventmgt_domain_model_event',11,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_event',3,''),('3b80d0ba21635600d28bc32ba40f1be7','tx_sfeventmgt_domain_model_registration',303,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('3c676ffba54c50cf225e7eb57fc1519b','sys_category',1,'items','','','',1,0,'tx_sfeventmgt_domain_model_event',2,''),('3ccae7afde57723255b207ea21d8e164','tx_sfeventmgt_domain_model_registration',669,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('3d308ff2c417c6fbabd46f18f78859b1','tx_sfeventmgt_domain_model_registration',563,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('3d58085608c756466410094ee369a0c4','tx_sfeventmgt_domain_model_registration',538,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',20,''),('3d6e3a46c8843fe6944fb6fcca82a5c2','tx_sfeventmgt_domain_model_registration',474,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('3d7dd918aa7427c40c83074e864b89b3','tx_sfeventmgt_domain_model_registration',413,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('3de6bf2af86a0f2c4cc3f9ede0ddfa2d','tx_sfeventmgt_domain_model_registration',644,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-en@sfeventmgt.local'),('3e3f475d376ab75c7d8492326acbd4fe','tt_content',7,'pi_flexform','additional/lDEF/settings.registrationPid/vDEF/','','',0,0,'pages',6,''),('3ed04b98efb66363f73eb9026e462c5a','tx_sfeventmgt_domain_model_registration_fieldvalue',9,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('3efe8b866a98a7aba24d972845453ebd','tx_sfeventmgt_domain_model_registration',356,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-en@sfeventmgt.local'),('3f5f259dcc452a010b7c4f238ea479df','tx_sfeventmgt_domain_model_registration',633,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('3f761457e4565320b98bd1ffca5660f7','tx_sfeventmgt_domain_model_registration',133,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',63,''),('3f86e63ef357541a787af852bedb7a2d','tx_sfeventmgt_domain_model_registration',407,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('3f90acb58da4e06a4728d7c2fb8f8316','tx_sfeventmgt_domain_model_registration',345,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',344,''),('3fa0f80af816575b78ab9f13ac487b22','tx_sfeventmgt_domain_model_registration',295,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('4050a9570c2eb20f311bc34675ccfd59','tx_sfeventmgt_domain_model_registration',353,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('40795b02e748ddd4c27a4f91a91f78d6','tx_sfeventmgt_domain_model_registration',290,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('41a3366f504c66c79b7468da314760b3','tx_sfeventmgt_domain_model_registration',441,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('41b7eddd794bf994e391832603ffb0c2','tx_sfeventmgt_domain_model_registration',664,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',44,''),('41d1c0e72df03fa0af867291a5f743b7','tx_sfeventmgt_domain_model_registration',499,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('41d9b0a7c083fc50dbb2204c37c86b66','tx_sfeventmgt_domain_model_registration',41,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',20,''),('41db1e577e0f2dd032adb282b538984b','tx_sfeventmgt_domain_model_registration',243,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('42206a6d5de71e3386b4c07cfa860c69','tx_sfeventmgt_domain_model_registration',323,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('422d2405a075342194c511e0e13a330c','tx_sfeventmgt_domain_model_registration',188,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',86,''),('427be8c153cb8ee9b0ce93d73f28e47d','tx_sfeventmgt_domain_model_registration',364,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',362,''),('42bcda1f8a047889bc2653ec9f347dbf','tx_sfeventmgt_domain_model_registration',628,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('4305e033032beec630a70f44cd88af87','tx_sfeventmgt_domain_model_registration',513,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',9,''),('435f393fdca062402c39582c24128a3a','pages',12,'sys_language_uid','','','',0,0,'sys_language',1,''),('43b38a9624a197a73745573db7183398','tx_sfeventmgt_domain_model_registration',10,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',4,''),('43eecbc9d303b062effa7afdbbf97bc7','tx_sfeventmgt_domain_model_registration',296,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('448d51b1161c448eeb2f4f2daf0886de','tx_sfeventmgt_domain_model_registration',512,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('44b13d8bbc0a2d01ef5ccdd34626ac69','tt_content',12,'pi_flexform','sDEF/lDEF/settings.registrationPid/vDEF/','','',0,0,'pages',6,''),('44c56e04358e357923a14bb3135aee26','tx_sfeventmgt_domain_model_registration',539,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('455d51668f2e9582c50d2a7fb002db68','tx_sfeventmgt_domain_model_registration',514,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('45a3d62b01399e58f5905c3afb634b5a','tx_sfeventmgt_domain_model_registration',281,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('45e63ea3781537a1465a3d1ed373604e','tx_sfeventmgt_domain_model_registration',230,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('45f7409f13726b6ecd8883c14884cf40','tx_sfeventmgt_domain_model_registration',460,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('462c6c72123f54f157b5a21cb40d7bd5','tx_sfeventmgt_domain_model_registration',592,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('4657c689788562189d47ec04b9591920','tx_sfeventmgt_domain_model_registration',594,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('46773277d8a50578dbb9ab1baebd48fa','tx_sfeventmgt_domain_model_registration',376,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',8,''),('467b16334c95fd5e3d98f12ad67e36ca','tx_sfeventmgt_domain_model_registration',222,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',2,''),('469da13befdfdb8cc9a9ddd0beb0d6bd','tx_sfeventmgt_domain_model_registration',331,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',22,''),('4707cb510449ff8ec275883f6fd2f1d2','tx_sfeventmgt_domain_model_registration',484,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',19,''),('47770ac91bb84876d362b419be2056e8','tx_sfeventmgt_domain_model_registration',346,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('479cbd8580ac2a43e793feaab1a79332','tx_sfeventmgt_domain_model_registration',634,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('479e8762980f9575162d4cd66e1d1995','tx_sfeventmgt_domain_model_registration',192,'fe_user','','','',0,0,'fe_users',1,''),('47bd16708ec09f586613e19d97f0a3f2','tx_sfeventmgt_domain_model_event',18,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_event',17,''),('4845f5a5bd0c4f4f64715704b7910997','tx_sfeventmgt_domain_model_registration',443,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('48cda3b436453dff9639870b9ce25d4f','tx_sfeventmgt_domain_model_registration',494,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('48e23d65c56ef838a35597182275665e','tx_sfeventmgt_domain_model_registration',372,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',371,''),('4a2b62945dce9a37961d1b95468d3571','tx_sfeventmgt_domain_model_registration',528,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('4a9d770ab1acfb346a0b14f04a5420cd','tx_sfeventmgt_domain_model_registration',426,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',425,''),('4aa5fdb9649be01aa630259d7999dfd2','tx_sfeventmgt_domain_model_registration',564,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('4ab9e9d4050a642c08caf41effc16af9','tx_sfeventmgt_domain_model_registration',385,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',1,''),('4ac8260629addfdf9a9b4ad2c5357d49','tt_content',8,'pi_flexform','sDEF/lDEF/settings.storagePage/vDEF/','','',0,0,'pages',4,''),('4bb355465b8fb8f9fb43e21d56dc06be','tx_sfeventmgt_domain_model_registration',393,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('4bb417aee87d729d7ffb76889e515aa0','tx_sfeventmgt_domain_model_registration',325,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('4c13ab4fd59e77cbc0ebee3850654f4c','sys_category',2,'items','','','',1,0,'tx_sfeventmgt_domain_model_event',12,''),('4c18ff4025f01f6edfe7dbd0a82ed4e2','tx_sfeventmgt_domain_model_registration',137,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',65,''),('4c5d1868b1fc64177f85cc0d0ace8976','tx_sfeventmgt_domain_model_registration',31,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_registration',2,''),('4c61f317fdf6bcd19a079790ae1dc0eb','sys_category',5,'items','','','',6,0,'tx_sfeventmgt_domain_model_event',8,''),('4cb060cf7232baf37018bd2ab3194f37','tx_sfeventmgt_domain_model_registration',85,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',42,''),('4cbdfd7f2654664fd0b055e0ce16114e','sys_category',5,'items','','','',1,0,'tx_sfeventmgt_domain_model_event',2,''),('4cf470c9eece2e7c77dc2e358c1f765f','tx_sfeventmgt_domain_model_registration',382,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',380,''),('4d1354ad320a41cc39184286faa1a3da','tx_sfeventmgt_domain_model_registration',81,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',40,''),('4dbe6bef1bb9041a9b3725826477e456','sys_category',1,'items','','','',8,0,'tx_sfeventmgt_domain_model_event',10,''),('4dd357ec86f369470e9da9900d2b0333','tt_content',6,'pi_flexform','additional/lDEF/settings.listPid/vDEF/','','',0,0,'pages',2,''),('4e5eb55e3480dcb4671603025b4c753f','tx_sfeventmgt_domain_model_registration',304,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('4e8585ea708447bee55a1b08525094e1','tx_sfeventmgt_domain_model_registration',286,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('4ecf4dd53561b5fd4a4ba67c6901e529','tx_sfeventmgt_domain_model_registration',424,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('4ffeffa607281741a1fdea4d825a80db','tx_sfeventmgt_domain_model_registration',600,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('500298f57643c4f1d6263b08e72055dc','tx_sfeventmgt_domain_model_registration',41,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',19,''),('50454cb43cd934f8f6f045319058fb89','tt_content',8,'pi_flexform','additional/lDEF/settings.detailPid/vDEF/','','',0,0,'pages',3,''),('50538db074534caf1d5ec1b4be405f4b','pages',7,'l10n_parent','','','',0,0,'pages',2,''),('508c293f22e7f24869fd34a8f47652f5','tx_sfeventmgt_domain_model_registration',634,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',632,''),('512ea520a781188dfeee158518f5ee84','tx_sfeventmgt_domain_model_registration',650,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('52dbb2e86f4130d68195db043319ff18','tx_sfeventmgt_domain_model_registration',301,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',299,''),('52dc091d47a4c58c39814b7055656898','tx_sfeventmgt_domain_model_registration',291,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',290,''),('52eaf59c5c0038bf84eb7914cf7092bb','tx_sfeventmgt_domain_model_registration',511,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('52f09c29b49c2b5e1003ab3cdb1c2b1d','tx_sfeventmgt_domain_model_registration',431,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('5321ff42bd2d7739a4359f7be135b83a','pages',17,'sys_language_uid','','','',0,0,'sys_language',1,''),('534c37becd7be9f77cfe5b6ca0453f64','tx_sfeventmgt_domain_model_registration',518,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',12,''),('5434d056abe170ba04896a8a334e7758','sys_category',7,'l10n_parent','','','',0,0,'sys_category',3,''),('5443dee0829f7a40fbba0dce12f1caa1','tt_content',2,'pi_flexform','additional/lDEF/settings.listPid/vDEF/','','',0,0,'pages',2,''),('54b2533a09334cf4f0ddfd258e4a56d9','tx_sfeventmgt_domain_model_registration',273,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('550a51b87b915099200541c1acc3c445','tx_sfeventmgt_domain_model_registration',481,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',479,''),('55bc06dc8a5dd625f04b8bd4530c61cc','tx_sfeventmgt_domain_model_registration_fieldvalue',41,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('56290ee672e52375a403c89aac4dfb92','tx_sfeventmgt_domain_model_registration',368,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('5692b19cb4b0b997d71ed74ae6ea7edd','tx_sfeventmgt_domain_model_registration',283,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('56994b0c163a67c02d02b4b139936980','tx_sfeventmgt_domain_model_registration',396,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('56db2c0ee0e4115361ff83608b7d5b9b','tx_sfeventmgt_domain_model_registration',398,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('572ab82fdf34401fe20407cf86f550d0','tx_sfeventmgt_domain_model_registration',646,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('575b23ef973eb301e42c203ffe0b10a8','tx_sfeventmgt_domain_model_registration',611,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('57718ba7f6bd8f976945fc2a783f656f','tx_sfeventmgt_domain_model_registration',45,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',21,''),('57758b48f471ff35fb9bfb0ac7f6da9e','pages',16,'sys_language_uid','','','',0,0,'sys_language',1,''),('581da613e24b4ba71589ef2cb070b295','tx_sfeventmgt_domain_model_registration',331,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('5868aa59aaf326452d54ec94a1f688b4','tx_sfeventmgt_domain_model_registration',471,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('589cfecadcf4a79357f4c235ba71793d','tx_sfeventmgt_domain_model_registration',299,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('5963940c72b5c56c4c07424558ccd2ad','tx_sfeventmgt_domain_model_registration',531,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('5980c1e7b94cf1831fdbccff0014932f','tt_content',7,'pi_flexform','categoryMenu/lDEF/settings.categoryMenu.categories/vDEF/','','',1,0,'sys_category',2,''),('5982e60277e881c85894a7add1e50d99','tx_sfeventmgt_domain_model_registration',380,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('5983f0beebdf77248e8932650909ef78','tx_sfeventmgt_domain_model_registration',412,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',3,''),('59bdbf7e12a0551780284457d49ba974','tx_sfeventmgt_domain_model_registration',295,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',14,''),('59e0acd5b6b5e7bed1ad832ae7d18f62','tx_sfeventmgt_domain_model_registration',575,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('5a0499e919902d4e2eaddbac76e25a0e','tx_sfeventmgt_domain_model_registration',583,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-de@sfeventmgt.local'),('5a25b313ddfec27e6d0818354dcf9f78','tx_sfeventmgt_domain_model_registration',304,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',15,''),('5a41538b3cc5a1c7c32a76db3ebf0590','tx_sfeventmgt_domain_model_registration',519,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('5b2e2e456be7c7d55086149d06a8039e','tx_sfeventmgt_domain_model_registration',246,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('5b3783f50b481704d759440853971516','tx_sfeventmgt_domain_model_registration',279,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('5c9799172ab200346eee7dac62bc8ca4','tx_sfeventmgt_domain_model_registration',340,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',24,''),('5c9d31443ffdf0ee0f1b596dd22961a8','tt_content',12,'pi_flexform','sDEF/lDEF/settings.detailPid/vDEF/','','',0,0,'pages',3,''),('5cc49401b8b631322046f8e0b24c61ce','tx_sfeventmgt_domain_model_registration',561,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',23,''),('5cca32a0bf522b52f8331a445d4f09a3','tx_sfeventmgt_domain_model_registration',15,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',8,''),('5cf38223f033440bfd1737bb99ae1917','tx_sfeventmgt_domain_model_registration',19,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',9,''),('5d3685ff6d2def9c933e6a508859cccb','tx_sfeventmgt_domain_model_registration',481,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('5d4a1dcadb6f5388ed07e3b43eaf569a','tx_sfeventmgt_domain_model_registration',259,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('5dca694932873d040f4b870eca4efe76','tx_sfeventmgt_domain_model_registration',105,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',51,''),('5e43e3f73c2b2dfc800b0e8ca753ccd6','tt_content',4,'l18n_parent','','','',0,0,'tt_content',1,''),('5ecf1e09222332d1b89ec38795be16e5','tx_sfeventmgt_domain_model_registration',517,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('5f66a5562fe7decf3de0f1bd88150e2b','tx_sfeventmgt_domain_model_registration',188,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',85,''),('5f87aba0934711e93c3a2d8426155922','tx_sfeventmgt_domain_model_registration',113,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',56,''),('5f92ff6d473a0fa7c35fe304a665679c','tx_sfeventmgt_domain_model_registration',469,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('6010b3c91dbacd8eb921c59aa515bb95','tx_sfeventmgt_domain_model_event',23,'location','','','',0,0,'tx_sfeventmgt_domain_model_location',1,''),('6029dd8dcb8f9f880710fc5a01de6e24','tx_sfeventmgt_domain_model_registration_fieldvalue',14,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('6031d6f79396bef3cdaba7fa06bc5596','tx_sfeventmgt_domain_model_registration',602,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('6034ba5b24afb2d0aeeb1cd7ab02bc7d','tx_sfeventmgt_domain_model_registration',603,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',602,''),('605bab91b15c80027719bdc9f8e1c59d','tx_sfeventmgt_domain_model_registration',245,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',244,''),('610e5cc5a9923798513a7d5d5ec28ca8','tx_sfeventmgt_domain_model_registration',584,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('61844f61dfbf278c6bccfb6fe4eb8229','tx_sfeventmgt_domain_model_registration',633,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',632,''),('619458f4081e823310efb1b3a2eb0598','tx_sfeventmgt_domain_model_registration',659,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('61a51b345e9ca0b88632a2928301b1ff','tx_sfeventmgt_domain_model_registration',612,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('61c03c43982fffaeab34771fc0a96817','tx_sfeventmgt_domain_model_registration',105,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',52,''),('6224f041f0704a641ea1e3e548778f42','tx_sfeventmgt_domain_model_registration',251,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('622b0cf90475e2c6c84882657f67980d','tx_sfeventmgt_domain_model_registration',604,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('62f59f57b5a0afdd54e4e990e2f1f1e7','tx_sfeventmgt_domain_model_registration',375,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('637610a035e804ed2655aa8b211df14f','tx_sfeventmgt_domain_model_registration',8,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',1,''),('63a1f9c1b44fde81babc2714634a49b8','tx_sfeventmgt_domain_model_registration',594,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',593,''),('63bcb1ab6073313662873ba486918dd5','sys_category',6,'items','','','',0,0,'tx_sfeventmgt_domain_model_event',4,''),('63cf1f1b06ade99503df3fca06dd4886','tt_content',11,'pi_flexform','sDEF/lDEF/settings.userRegistration.storagePage/vDEF/','','',0,0,'pages',4,''),('63d9de42591d3b1f7c2e559092a40fbe','tx_sfeventmgt_domain_model_registration',277,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',10,''),('63ed1a1bdb37725ed4899c9821c1dd47','sys_category',1,'items','','','',2,0,'tx_sfeventmgt_domain_model_event',3,''),('6428ad20e5f44e05a2d24f7725206005','tx_sfeventmgt_domain_model_location',4,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_location',2,''),('644cc3bc0e5c9d344ccfc475df83f465','tx_sfeventmgt_domain_model_registration',12,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',6,''),('645c9d6bceaf9d3d450ef112cfb7ea61','tx_sfeventmgt_domain_model_registration',408,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('652a239dec4aa81eaa7b6a718a29cb6a','tx_sfeventmgt_domain_model_registration',617,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('65c86269b5dd0de16e36350ecbd93b43','tx_sfeventmgt_domain_model_registration',373,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',371,''),('66f740a0d63e2020f1071c812fd63124','tx_sfeventmgt_domain_model_registration',616,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('670953a99177658b9728c7dec6ae521d','tx_sfeventmgt_domain_model_registration',327,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('67284fb256baa341130180846a49417d','tx_sfeventmgt_domain_model_registration',446,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-en@sfeventmgt.local'),('6730e4f09f265f70054fba38e9768df4','tx_sfeventmgt_domain_model_registration',609,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('67f5db50d3cebe5e6bd3b928199a959b','tx_sfeventmgt_domain_model_registration',254,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',253,''),('681684a2bf6ab696e85d15078ddfe4df','tt_content',1,'pi_flexform','sDEF/lDEF/settings.storagePage/vDEF/','','',0,0,'pages',4,''),('68397e245bb292895a35acc2abc5c72f','tx_sfeventmgt_domain_model_registration',199,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',89,''),('686fd28256382e7cc7732f080abcff61','tx_sfeventmgt_domain_model_registration',425,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('6881bdd239435fb386a7cef800001302','tx_sfeventmgt_domain_model_registration',310,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('68d5e2c807ca1e942c173bbc59a167cb','tx_sfeventmgt_domain_model_registration',415,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('68e828ac50fd269d921db974e33acb78','tx_sfeventmgt_domain_model_registration',372,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('68f41b94ac809cff91d61a8ed2ab8905','tx_sfeventmgt_domain_model_registration',292,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('692b71a6d46e3622efa945f5d39526a9','tx_sfeventmgt_domain_model_registration',301,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('69393fa8e7e8f2f41dcf2b521d19e85f','tx_sfeventmgt_domain_model_registration',614,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',31,''),('6a1cc59b1d6b8f91c190955143524ce2','tx_sfeventmgt_domain_model_registration_fieldvalue',32,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('6a3dd892526d507e13cb801955aaf601','tx_sfeventmgt_domain_model_registration',307,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('6a55f47130aa75ba29cc1fb1345c971b','tx_sfeventmgt_domain_model_registration',550,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('6a9f3f8900032dc8bf3425a6a7d10e2b','tx_sfeventmgt_domain_model_registration',336,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('6ac2af8c1509416e051b88919e3022ca','tx_sfeventmgt_domain_model_registration',556,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('6b5df4e98b6de335e21c8ecb7f0d4a5a','tx_sfeventmgt_domain_model_registration',595,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('6b6be9159e12046b670d6e4cf87de5fd','tx_sfeventmgt_domain_model_registration',319,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('6b81ada5e754645fda0fbc975abdc98a','tx_sfeventmgt_domain_model_registration',73,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',35,''),('6bae0121e0dea5e1b8e49f3465274bb4','tx_sfeventmgt_domain_model_registration',354,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('6bc641b4ce217eb7fd73431596039c9b','tx_sfeventmgt_domain_model_registration',226,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('6bd72b3bbeb44f9208fe26062694941d','tx_sfeventmgt_domain_model_registration',255,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',253,''),('6c33a2a276e30dd9b5f7f8cb93e9749e','tx_sfeventmgt_domain_model_registration',344,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('6c3a76ca8c4db66cd9b6fbda941bdcfa','tx_sfeventmgt_domain_model_registration',434,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('6c51346c7853cde892ef47e51aabb4fe','tx_sfeventmgt_domain_model_registration',203,'fe_user','','','',0,0,'fe_users',1,''),('6cf230a300a30f301bdf4f8a9965ee39','tx_sfeventmgt_domain_model_registration_field',4,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('6d1364780324787595acbc9f5d6d146b','tx_sfeventmgt_domain_model_registration',337,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',335,''),('6ea12d93ed25ea6b1be9f3c8bdba0bef','tx_sfeventmgt_domain_model_event',12,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_event',4,''),('6efcce095820a39938d960247e655a9f','tx_sfeventmgt_domain_model_registration',637,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('6efeadea8c298a05204e8ca66aef8477','tx_sfeventmgt_domain_model_registration',496,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('6f25e3c27fb511fddfd0952339e39423','sys_category',5,'items','','','',0,0,'tx_sfeventmgt_domain_model_event',1,''),('6fb02f0ece6058e6a52b1d4f13b4ceaa','tx_sfeventmgt_domain_model_registration',581,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',580,''),('6fd6bbbab9d38baf0b32d67b0050cb9d','tx_sfeventmgt_domain_model_registration_fieldvalue',4,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('7049e2a2a4672c0bed75835c683b419a','tx_sfeventmgt_domain_model_registration',624,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('70d1d56fb80ffe31e96c824a58ca3a99','tx_sfeventmgt_domain_model_registration',8,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',2,''),('7148fb1f6cea9e80b1308fab4ee4a509','tx_sfeventmgt_domain_model_registration',581,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('7158a689ba420df82939e83a6a3e61fa','sys_category',1,'items','','','',9,0,'tx_sfeventmgt_domain_model_event',11,''),('71915f1ab05c9eac4b5fe31eea52c5f8','tx_sfeventmgt_domain_model_registration',456,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('71d22fe7129402c87fee3c5a6a4815dd','tx_sfeventmgt_domain_model_registration',316,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('7248b0e7bf4c59c94e9b65f03d0eee3a','tt_content',12,'pi_flexform','sDEF/lDEF/settings.userRegistration.storagePage/vDEF/','','',0,0,'pages',4,''),('72615181d7b8528970771c1459c338ae','tt_content',6,'l18n_parent','','','',0,0,'tt_content',3,''),('72daf6069da552329d8669b0c801a991','tx_sfeventmgt_domain_model_registration',627,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('733fecc472d3d700a7d784bb807edfb5','tx_sfeventmgt_domain_model_registration',480,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',479,''),('7343c8af2251c4dc019a5e31ca09ebc2','tx_sfeventmgt_domain_model_registration',549,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('7387fadbcc69f814775ddf0406911810','tx_sfeventmgt_domain_model_registration',631,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('741c41486d3e1bffea544e31269bf735','tx_sfeventmgt_domain_model_registration',484,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('7468f28323181a5dddb9ea4e76981464','tx_sfeventmgt_domain_model_registration',632,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('74dde81fe13b33aa6e407d035bd81961','pages',9,'sys_language_uid','','','',0,0,'sys_language',1,''),('754fff27764b7f4033fe6fda91065512','tt_content',4,'pi_flexform','additional/lDEF/settings.detailPid/vDEF/','','',0,0,'pages',3,''),('7551eb695f7b28277e2f40203395c674','tx_sfeventmgt_domain_model_registration',351,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('758e3f203d54034da204666bacac83f9','tx_sfeventmgt_domain_model_registration',529,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('75b2cac2d16339c9b559ba8f19088129','tx_sfeventmgt_domain_model_registration',244,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('762092b4ef3d0fab4976a23568731a1d','tx_sfeventmgt_domain_model_registration',228,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',226,''),('765846ad2c75255c0396e128be64b261','tx_sfeventmgt_domain_model_registration',478,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('767cf5ccab013779685b4938cd9f3323','tx_sfeventmgt_domain_model_registration',637,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',37,''),('76a430220ab0f317257870d5a467c231','tx_sfeventmgt_domain_model_registration',272,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('76e254d9fcf98bb27b4d143ff9acc4b2','tx_sfeventmgt_domain_model_registration',556,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',22,''),('77722089f4bfc177b25bcaa5e3adf9fa','tx_sfeventmgt_domain_model_registration',451,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('777481e5bdcb780a05dff767d395dbc2','tx_sfeventmgt_domain_model_registration',382,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('780a402756beac9f969e7ab4696877a3','tx_sfeventmgt_domain_model_registration',320,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-en@sfeventmgt.local'),('78701cd6050c0c81deada07489963abe','tt_content',5,'l18n_parent','','','',0,0,'tt_content',2,''),('78b15e14f15a4ef2f2d75bf57ae8bba7','tx_sfeventmgt_domain_model_registration',392,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-en@sfeventmgt.local'),('78b8d4cfe31ee86436ccc204704dd4cf','tx_sfeventmgt_domain_model_registration',33,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',15,''),('7960fc24c47b280cac678dc4e1973b1f','tx_sfeventmgt_domain_model_registration',526,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('7971255d2f83e6af14d7fb6e75a5f71d','tx_sfeventmgt_domain_model_registration',199,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',90,''),('7984a0dfc17091bfde9c357f8737838d','tx_sfeventmgt_domain_model_registration',57,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',28,''),('7a4cf74cbb9df241de88e6490f7f0f64','tx_sfeventmgt_domain_model_registration',300,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('7a54a5297b3aed96e62f92517e847005','sys_category',5,'items','','','',8,0,'tx_sfeventmgt_domain_model_event',10,''),('7a582303e8867891ec79d3b55c4e5abf','tx_sfeventmgt_domain_model_registration',528,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',15,''),('7b842d56b188957d9849cf2d754dc67a','tt_content',7,'pi_flexform','sDEF/lDEF/settings.storagePage/vDEF/','','',0,0,'pages',4,''),('7b9dc9990e513c3a81e987c5d93c1e08','tx_sfeventmgt_domain_model_registration',133,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',64,''),('7ba6289ff0683cdaf5f9fcb9416340c6','tx_sfeventmgt_domain_model_registration',533,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',18,''),('7bbdf486072776e5acec579d01e791f6','tx_sfeventmgt_domain_model_registration',238,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-de@sfeventmgt.local'),('7be5c3a43c4cbbba458deea67a76a8ba','tx_sfeventmgt_domain_model_registration',401,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-de@sfeventmgt.local'),('7bec58e8c0541ca3df019a0e5184493e','tx_sfeventmgt_domain_model_registration',174,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',79,''),('7bfb6ed1a399386ff99c6e4aa59df6a4','tx_sfeventmgt_domain_model_registration',591,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',590,''),('7c5527396b58c829eea63b464fb1a453','tx_sfeventmgt_domain_model_registration',234,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('7c57c12abb272a4661596422ac2f54a0','tx_sfeventmgt_domain_model_registration',322,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('7c60ae46208ca535e5b274e03bca1a8e','tx_sfeventmgt_domain_model_registration',545,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',543,''),('7c8aaa80ed85e55be5593d3e5a5123af','tx_sfeventmgt_domain_model_registration',260,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('7cb3e6acea95c49a8b932b63efd87d40','tx_sfeventmgt_domain_model_registration',149,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',71,''),('7cb43438f9a2acf8515cdabac70c2b8f','tx_sfeventmgt_domain_model_registration',467,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('7cff6a49d41fb693386cf3d8f64f8693','tx_sfeventmgt_domain_model_registration',430,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('7d404d94d158df0089370c53f07e76de','tx_sfeventmgt_domain_model_registration',610,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('7e1e606dc148f280f2bc0d71a5f44233','tx_sfeventmgt_domain_model_registration',604,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',602,''),('7e20b6ed96d7b48da01fa554a7503fe3','tx_sfeventmgt_domain_model_registration',418,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('7e221ee68dac345bb8a11b640ac5ef73','sys_category',5,'items','','','',7,0,'tx_sfeventmgt_domain_model_event',9,''),('7e266b9ea660dd560fa4789b350f3080','tx_sfeventmgt_domain_model_registration',357,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('7e3fd415f4bbe16183b6a9d7e6ffa412','tx_sfeventmgt_domain_model_registration',350,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('7ea96d6bd99abb9b93ca19fb5ec98ac7','tx_sfeventmgt_domain_model_registration',597,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',596,''),('7f4f11712ff594cc13fa0b097c750897','sys_category',1,'items','','','',4,0,'tx_sfeventmgt_domain_model_event',6,''),('7fad57671d03f76342d4e1a4fd3522b6','tx_sfeventmgt_domain_model_registration',567,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('805718ad1bac82f5da213df3695f1118','tx_sfeventmgt_domain_model_registration',406,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('80a267353d8dc380b50f50469871122d','tx_sfeventmgt_domain_model_registration',600,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',599,''),('8119a1b6c3c90cc982c519973213b3cb','tx_sfeventmgt_domain_model_registration',377,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('81b9a5907b259790fdc8a27653e33e18','tx_sfeventmgt_domain_model_registration',418,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',416,''),('82641eada25257ed6be7101c2bd12cd2','tx_sfeventmgt_domain_model_registration',513,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',10,''),('82759c146cf53b228c5b9eab1cebf74a','tx_sfeventmgt_domain_model_registration',533,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('82b30f93a35ed1b0b373b9f942068c04','tx_sfeventmgt_domain_model_registration',613,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('82bb06c212b73ae71aaa34653d5d0806','tx_sfeventmgt_domain_model_registration',561,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',24,''),('82f8d0acf963c3a07906906a85bb9678','tx_sfeventmgt_domain_model_registration',264,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',262,''),('8317c0737e897c687b736e2560728837','tx_sfeventmgt_domain_model_registration_fieldvalue',40,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('8353bc2044c31578a439de4f73e1d4ee','tx_sfeventmgt_domain_model_registration',668,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('83960e94629cd805baa5d93103e03b9b','tx_sfeventmgt_domain_model_registration',213,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',4,''),('83e3b307fd18e8d338bb38aba2957e48','tx_sfeventmgt_domain_model_registration',390,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',389,''),('8400fff442c58b65482731ddc68b434d','tx_sfeventmgt_domain_model_registration',628,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',35,''),('8496ceb992b59f9c2b9144c68ffad051','tx_sfeventmgt_domain_model_registration',555,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('84e6ded51c8948f7b52d3b61d7b99135','tx_sfeventmgt_domain_model_registration',376,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('85432795e4b1954a2336157002c083f6','tx_sfeventmgt_domain_model_registration',231,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',3,''),('85cb91796db2c23400d89befd7460ea6','tx_sfeventmgt_domain_model_registration',109,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',53,''),('85d88d5db3f4bc300a7beb93c2ff9cef','tx_sfeventmgt_domain_model_registration',421,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',6,''),('860ecccdd73f533debd999e48b732e75','tx_sfeventmgt_domain_model_registration',89,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',44,''),('86352e337d590b4c765033f90c2f464b','tx_sfeventmgt_domain_model_registration',205,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',1,''),('8666af45291b45105c465ef98d1520ca','tx_sfeventmgt_domain_model_registration',246,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',244,''),('86e20ea7233e33e255e33bdd98c13274','tx_sfeventmgt_domain_model_registration',283,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',281,''),('8732b843ee650c1f372287979b613af2','tx_sfeventmgt_domain_model_registration',282,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',281,''),('873a7690307e8b71d469c27604db9f2d','tx_sfeventmgt_domain_model_registration',651,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('8760e3208b91898549a1d4ccfca1f7cb','tx_sfeventmgt_domain_model_registration',652,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',650,''),('8835a70f03f22fabffcaaa114d3ba1bc','tx_sfeventmgt_domain_model_registration',385,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('8841520ebc76f2e40ccf8bb3b1af6933','tx_sfeventmgt_domain_model_registration',306,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('886ebdb9550142e02f6fbf59a899f70e','sys_category',3,'items','','','',1,0,'tx_sfeventmgt_domain_model_event',18,''),('888cdcf191ace19c9fc3701c383b9c08','tx_sfeventmgt_domain_model_registration',321,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('8899e9cf507e5b6f6175aed410ac67e5','tx_sfeventmgt_domain_model_registration',365,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-de@sfeventmgt.local'),('89692b0b24b19ad100698c00439ddbbf','tx_sfeventmgt_domain_model_registration',292,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',290,''),('89805e08e961324df8e68061423300a3','tx_sfeventmgt_domain_model_registration',521,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('8a38133defca68baaab6668d1cabe62c','tx_sfeventmgt_domain_model_registration',405,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('8a7d4f4153db46efc5b5540a27143463','tx_sfeventmgt_domain_model_registration',371,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('8ab603b7bdd169f6b3808c08ee97b523','sys_category',3,'items','','','',0,0,'tx_sfeventmgt_domain_model_event',17,''),('8aed4b802fdcdc732af8204eb820f606','tx_sfeventmgt_domain_model_registration',319,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',317,''),('8b19e8da4dcfd8c46f25f975956d7474','tx_sfeventmgt_domain_model_registration',660,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',659,''),('8b26b109ff759a6368bd81ba3fbfc950','tx_sfeventmgt_domain_model_registration',432,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('8b8b69b28d97fc6716ee6a48c60402b1','tx_sfeventmgt_domain_model_registration',655,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('8c25ab780feef162cc88ce9069cad2fa','tx_sfeventmgt_domain_model_registration',367,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',5,''),('8c3661f71708361c3f64e8e1151d1e9f','tx_sfeventmgt_domain_model_registration',334,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('8c4de955c00fb915fc28c3f53d87ab6e','tx_sfeventmgt_domain_model_registration',618,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('8c5c4bc7bf6b18925a2fbbe6af9ef474','tx_sfeventmgt_domain_model_registration',225,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('8c8da260760136b23e6f568cf83f8b3b','tx_sfeventmgt_domain_model_registration',639,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('8c99efa59eb8b150aa95166161ff01cd','tx_sfeventmgt_domain_model_registration',53,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',25,''),('8c9f0c35f0de3490122d219c7fa7ef19','tx_sfeventmgt_domain_model_registration',641,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('8ca98e3543eabad8e4331e3f2ad4b677','tx_sfeventmgt_domain_model_registration',466,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',15,''),('8cf76f7dec566f10f30ac915ab7f1591','tx_sfeventmgt_domain_model_registration',291,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('8d727baf1d624c61d431f7af9686db8a','tx_sfeventmgt_domain_model_registration',624,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',623,''),('8d8f376fff4f3fe471a0af657c620025','tx_sfeventmgt_domain_model_registration',274,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-de@sfeventmgt.local'),('8e4033865d2906af7685aa00aa6a9678','tx_sfeventmgt_domain_model_registration_fieldvalue',33,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('8e5d5c8eccdb4301724cf074bdeb0d9f','tx_sfeventmgt_domain_model_registration_fieldvalue',23,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('8ebdc0854dfb19389330443b9598032b','tx_sfeventmgt_domain_model_registration',458,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('8f3a40c70e20311f99658a5426e8dd44','tx_sfeventmgt_domain_model_registration',397,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('8f3bc2ad775552a4d8b0b994b5d20f54','tx_sfeventmgt_domain_model_registration',324,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('8f51b57a9855c47fa9353e0cbb375c5b','tx_sfeventmgt_domain_model_registration',312,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('8f89fcead474e313efc742bc2402798d','sys_category',5,'l10n_parent','','','',0,0,'sys_category',1,''),('90018d508aa103a3c6dc0b05d208c9ce','tt_content',8,'pi_flexform','additional/lDEF/settings.registrationPid/vDEF/','','',0,0,'pages',6,''),('906154b444e798a216f366bb91f024c0','tx_sfeventmgt_domain_model_registration',236,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('90b05bdf4aa7a759b457170f12ecb938','tx_sfeventmgt_domain_model_registration',508,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('90d4ffd6ff87c4349d8cc3d0935e2853','tx_sfeventmgt_domain_model_registration',421,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',5,''),('90f31d14942b69187463dc6b26dc4147','tx_sfeventmgt_domain_model_registration',582,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('912c91bebe6201e4ab85d015e3048fa2','tx_sfeventmgt_domain_model_registration',125,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',60,''),('913951077316543148104bc0d8590c9f','tx_sfeventmgt_domain_model_registration',271,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('92126d808cb950c3377e3ebad9a7e552','tx_sfeventmgt_domain_model_registration',410,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-en@sfeventmgt.local'),('9272dbcebf925c19f57242e119be289d','tx_sfeventmgt_domain_model_registration',330,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('929b7d1931a2a3275c611b4b3d5c03b7','tx_sfeventmgt_domain_model_registration',518,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',11,''),('92a738f17e15c14833eae40c1df9396c','tx_sfeventmgt_domain_model_registration_fieldvalue',37,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('92b3ae7e483d4bdbfdcaaae18757f112','tx_sfeventmgt_domain_model_registration',77,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',38,''),('93269b3ebe1b5564facbcaf7548603c8','tx_sfeventmgt_domain_model_registration',527,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('9337f1e381a64c08b1426354c4b13212','tx_sfeventmgt_domain_model_registration',293,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-de@sfeventmgt.local'),('93a9921f36414c6bdbb3096aec9a4b09','tx_sfeventmgt_domain_model_registration',597,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('93dad2a71b0918516a05ab137102eede','tx_sfeventmgt_domain_model_registration',386,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('93e76f011d8a70f5affe223ac7444f4f','tx_sfeventmgt_domain_model_registration',65,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',32,''),('9459dcf4ed5fb8da3a068f6bf09e03b1','tt_content',12,'l18n_parent','','','',0,0,'tt_content',11,''),('94a1fb3c1356b8d88ecf196f614fb66c','tx_sfeventmgt_domain_model_registration',532,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('94e2579b737a6f62d402b20179d54820','tx_sfeventmgt_domain_model_registration',533,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',17,''),('955e93fbbe9f1fc6aabf3a773200f687','tx_sfeventmgt_domain_model_registration',381,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('959ac599acee4530b5b2372915630ab8','tx_sfeventmgt_domain_model_registration',49,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',23,''),('96032f68eb3aa3dc5f2ac185f2ee6b16','tx_sfeventmgt_domain_model_registration',23,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',11,''),('9606bf829a3cdb2a57947cd127764e3a','tx_sfeventmgt_domain_model_registration',523,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('9625001deafec3646969a287964db1aa','tx_sfeventmgt_domain_model_registration',240,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',2,''),('96a8478559f96ce72ef116fe57201572','tx_sfeventmgt_domain_model_registration',257,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('9732bbda8f3403415153fbd62b49d9c1','tx_sfeventmgt_domain_model_registration',557,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('975a8c3f8e6e31157d4db53bc73856ad','tx_sfeventmgt_domain_model_registration',466,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('97f4bb60a5ffcb851a98df5a1fa4e0d7','tx_sfeventmgt_domain_model_registration',85,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',41,''),('984edab58bec7954b1b5539030076d97','tx_sfeventmgt_domain_model_registration',179,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',81,''),('989e365ecec8c3ed253ee951d62af89a','tx_sfeventmgt_domain_model_registration',606,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('9938bf65e6286732a8dfa9a588f80d7b','tx_sfeventmgt_domain_model_registration_fieldvalue',36,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('99840f2051141c9ac6bcc085a6dbcff3','tx_sfeventmgt_domain_model_event',2,'registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',565,''),('998dbd704b2dcaade9fe625b80e1450f','tx_sfeventmgt_domain_model_registration',577,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('9a262661305a8b43f7a786611e293ced','tx_sfeventmgt_domain_model_registration',391,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('9a55b2d3b51f7e3f49e74d77676ec18b','tx_sfeventmgt_domain_model_registration',153,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',74,''),('9ade5b5788e48a64aa5dc32a39b66dea','tx_sfeventmgt_domain_model_registration',222,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('9b7ce1607040a86b4b1cf70fa4376c99','tx_sfeventmgt_domain_model_registration',282,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('9b822881dbcd2366bec161b495dd1500','tx_sfeventmgt_domain_model_registration',61,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',30,''),('9baf03eb5a6a9e187dbe4a652ffbeeab','tx_sfeventmgt_domain_model_registration',582,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',580,''),('9bafd7e823b9f0dba908f14e74130d1b','sys_template',1,'constants','','email','2',-1,0,'_STRING',0,'info@sfeventmgt.local'),('9bd2f7f05f1e99927adb099ab72ba0da','tx_sfeventmgt_domain_model_registration',495,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('9bd3d6f397ddd518423e015cdd44d9c3','sys_category',5,'items','','','',10,0,'tx_sfeventmgt_domain_model_event',13,''),('9bdde4cd4d21a802a7e10796ba88a79f','tx_sfeventmgt_domain_model_registration',430,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',7,''),('9c1420dd78a6497e7553a94d9a059bd9','tx_sfeventmgt_domain_model_registration',462,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',461,''),('9c35fddb4cefeb6cea4034cc9e8ceb70','tx_sfeventmgt_domain_model_registration',578,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('9c370128118ce7cc471378b789d4e08a','tx_sfeventmgt_domain_model_registration',387,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('9c50735b03c8caf31b2c03b43a3a22a9','tx_sfeventmgt_domain_model_registration',520,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('9c8e20c0365468ec0163a003d14b112e','tx_sfeventmgt_domain_model_registration',420,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('9c9b95cb094fd5e32092138d86af0abd','tx_sfeventmgt_domain_model_registration',664,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',43,''),('9d3233ac64c292c4544f56e3010dfd1c','tx_sfeventmgt_domain_model_registration',488,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('9d5643f4d5613b06723f00f16aae7bff','tx_sfeventmgt_domain_model_registration_fieldvalue',11,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('9d836ed0b1d938aced02adb5a4ba3e46','tx_sfeventmgt_domain_model_registration',280,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('9ddfa21c85d09d05656e3ef31fca3051','tx_sfeventmgt_domain_model_registration',355,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',353,''),('9dfcf388d4e0314f5f91bab31ffa6b9b','tx_sfeventmgt_domain_model_registration',665,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('9e953a7164b71ff3ccd72959ae29766c','tx_sfeventmgt_domain_model_registration',620,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('9e9553391eaff0e667abb5430068e74e','tx_sfeventmgt_domain_model_registration',515,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('9eb0c0a8a88f3041c892b6fe7a46961f','tx_sfeventmgt_domain_model_registration',466,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',16,''),('9f40fdc53ef3cf3c67c94b2131018b0a','tx_sfeventmgt_domain_model_registration',459,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('9f67fef8e6054c5db30501f9e31e38b2','tx_sfeventmgt_domain_model_registration',636,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('9f68f2d112ab3098f5e81f38abaea6a7','tx_sfeventmgt_domain_model_registration',614,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('9f88017188e9a7c7248c736773a9ab65','tx_sfeventmgt_domain_model_registration',649,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('9f899ba209424291da6d6261a3513808','tx_sfeventmgt_domain_model_registration',394,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('9fa655728166785663cbec445ce9d4a7','tx_sfeventmgt_domain_model_registration',228,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('9fc676aa4e812c1fb304dc76bc2e2765','tx_sfeventmgt_domain_model_registration',622,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('a0393ba901ea20105d0b94758445335c','tx_sfeventmgt_domain_model_registration',463,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('a0d77b769c34e4d9150f0cd17c90f6b4','pages',10,'sys_language_uid','','','',0,0,'sys_language',1,''),('a0dd58fae06013197c6d23fd45367469','tx_sfeventmgt_domain_model_registration',476,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('a11cf4392b8986f05a8bc090e45b1c6f','tx_sfeventmgt_domain_model_registration_fieldvalue',44,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('a1c2c59669d4a97ca91d9e780705e15b','tx_sfeventmgt_domain_model_registration',461,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('a1c8297a809168b93b57179f1cccd41c','tx_sfeventmgt_domain_model_registration',394,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',3,''),('a274925829727beb92737f060097b085','sys_category',1,'items','','','',5,0,'tx_sfeventmgt_domain_model_event',7,''),('a2b25b7546ae7a79bb4b6f9f16155862','tx_sfeventmgt_domain_model_registration',553,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',552,''),('a32081ef412287f59572ee68b61df4b3','tx_sfeventmgt_domain_model_registration_fieldvalue',31,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('a3242fde2dec48b3ceddd5338f1c1028','tx_sfeventmgt_domain_model_registration',101,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',49,''),('a33c2df0b25c21be01983bb932024101','tx_sfeventmgt_domain_model_registration',523,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',14,''),('a3da9507e8a22bbb254c00930154ff09','tx_sfeventmgt_domain_model_registration_fieldvalue',19,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('a4060a1f0ecfc33d126382ba3f15cafb','tx_sfeventmgt_domain_model_registration',276,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('a44d875024755f24c208ca5424215093','tx_sfeventmgt_domain_model_registration_fieldvalue',30,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('a4d0d9a96fafb47f683aee3ed88d5a60','tx_sfeventmgt_domain_model_registration',157,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',76,''),('a52372f8366adccf7f9a2593604db0fb','tx_sfeventmgt_domain_model_registration_fieldvalue',28,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('a534881c3e20fb6505df8be931ad90cf','tx_sfeventmgt_domain_model_registration',263,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',262,''),('a53e70d49c98504e93a53c111a96cf10','tx_sfeventmgt_domain_model_registration',436,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',434,''),('a53fe4dae73e60d1c2d7b1581d34cffd','sys_template',1,'constants','','email','5',-1,0,'_STRING',0,'admin@sfeventmgt.local'),('a574eccc0ac81090806cef0dc546cd52','tx_sfeventmgt_domain_model_registration',544,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('a5cd54be6b82957464f182891252bb2e','tx_sfeventmgt_domain_model_registration',598,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',596,''),('a6331a9c5ea0b61c1e79ecf764368e59','tx_sfeventmgt_domain_model_registration',662,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-en@sfeventmgt.local'),('a63c1f5db5bae9ffd836e0dbb7f147dd','tx_sfeventmgt_domain_model_registration',457,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',13,''),('a64671d53e7bf95117624dc8d4d4624c','tx_sfeventmgt_domain_model_registration',595,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',593,''),('a65473f9875f5008e18fd91c61dc9d27','tx_sfeventmgt_domain_model_registration',27,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',13,''),('a65b956562dac31e35a5b018caf9ef44','tx_sfeventmgt_domain_model_registration',263,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('a65d17f2b51b499aded0ec1bda085dc2','tx_sfeventmgt_domain_model_registration',500,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('a6794439cc9750004d342482fca7b8fa','tx_sfeventmgt_domain_model_registration',286,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',12,''),('a6af82b978a294e5ba7f40be53257897','tx_sfeventmgt_domain_model_registration',237,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('a783cf97a1f81b8b9431d881e51a4200','tx_sfeventmgt_domain_model_registration',295,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',13,''),('a83582b50735298c529a8a75d0431bad','tx_sfeventmgt_domain_model_registration',541,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('a8e98392edfd60186b0f921ca4eb2233','tx_sfeventmgt_domain_model_registration',591,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('a902172e21e98f1ea85032e44743bc6e','tx_sfeventmgt_domain_model_registration',565,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-de@sfeventmgt.local'),('a918b62a6141be0e1a35e66897808e67','tx_sfeventmgt_domain_model_registration',647,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('a92e0fd78515584f4fde77a1016c3375','tx_sfeventmgt_domain_model_registration',318,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('a9500107fd148ea661b7075f0c5a1ee7','tx_sfeventmgt_domain_model_registration',450,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('a97cc63cb7f9ff7737b4197088fcf072','tt_content',8,'pi_flexform','categoryMenu/lDEF/settings.categoryMenu.categories/vDEF/','','',1,0,'sys_category',2,''),('aa054d2abea2fcdabf313961da0cf58f','tx_sfeventmgt_domain_model_registration',341,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('aa77dfd0c4d08807f1f6c092c336b3d6','tx_sfeventmgt_domain_model_registration',669,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',668,''),('aa97f2994fb5189943f6f5203caad802','tx_sfeventmgt_domain_model_registration',268,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('aaab3b61ccd5cb54d719a87d6ce60eef','tx_sfeventmgt_domain_model_registration',309,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',308,''),('ab54bfa4e022f53ed4a8a9d1471f2db6','tx_sfeventmgt_domain_model_registration',572,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('ab59e283a6fd69ffd883d7663277c5fc','tx_sfeventmgt_domain_model_registration',335,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('ab84bc8b439b12822cffec6864439a4f','tx_sfeventmgt_domain_model_registration',373,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('abea54685f25eab121e1a4fb91a80470','tx_sfeventmgt_domain_model_registration',490,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('abf625ca678b98a064891214659ffee3','tx_sfeventmgt_domain_model_registration',556,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',21,''),('ac71595090934dd451bc00eb324ea4f2','tx_sfeventmgt_domain_model_registration',654,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('ace2993eb2d3bb1404936cab118c99fd','sys_category',5,'items','','','',5,0,'tx_sfeventmgt_domain_model_event',7,''),('ad3b05286a3a09d0bf9e67fcdd6edd71','tx_sfeventmgt_domain_model_registration',317,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('ad728b42d707aa37134fdbc9f82e4e6d','tx_sfeventmgt_domain_model_registration',493,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('ad7a668408014ef3cc93e2e9b99b0724','tx_sfeventmgt_domain_model_registration',489,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',488,''),('adf6c67902e24cdda6161ae86e0580cb','tx_sfeventmgt_domain_model_registration',642,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',641,''),('ae517548aa9500ecf93363b4a6eafadc','tx_sfeventmgt_domain_model_registration',629,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('ae5ec5ffdd0a0a0915db0ec901be74f7','tx_sfeventmgt_domain_model_registration_fieldvalue',27,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('ae8647b47f76e3f69e1812587d8d923c','tx_sfeventmgt_domain_model_registration',183,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',83,''),('aec5fa34b4bc4bca0358c73a93263e18','tx_sfeventmgt_domain_model_registration',599,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('af3fc94a4bf2fe0bb9cb5f0c6000dfc2','tx_sfeventmgt_domain_model_registration',490,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',488,''),('af76be2d6c94981d1b11a100ea04cfbb','tx_sfeventmgt_domain_model_registration',93,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',45,''),('af91debd5f79ca3dfad9d51c2d595f13','tx_sfeventmgt_domain_model_registration',576,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('afbb0f0173764f33eb98fcdbc5263f24','tx_sfeventmgt_domain_model_registration_fieldvalue',8,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('b080c012de55cd60574be3df17c962a1','tx_sfeventmgt_domain_model_registration_fieldvalue',24,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('b09ecc3f6f0150daaee5a1b66df281dd','tx_sfeventmgt_domain_model_registration',447,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('b12e83936c94795c19932f9a8d50706a','tx_sfeventmgt_domain_model_registration',277,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',9,''),('b1892fcc53930b1b679d75b8f3d2e1f4','tx_sfeventmgt_domain_model_event',8,'registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',2,''),('b209c989e28321302685eba32547190b','tx_sfeventmgt_domain_model_registration',366,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('b21d1316149ed6a992d5ea6c34d6bdb8','tx_sfeventmgt_domain_model_registration',390,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('b2386c8dcb0f03a8df4be41589bc7fd6','tx_sfeventmgt_domain_model_registration',536,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('b3e6e93ba0b94e75b0a44707bee8554e','tx_sfeventmgt_domain_model_registration',605,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('b4540e7b25496c6643cf8af23fe8e665','tx_sfeventmgt_domain_model_registration_fieldvalue',29,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('b470ca29ac0a02e19c8e49246bd9a978','tx_sfeventmgt_domain_model_registration',23,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',12,''),('b47e7bf9de73378a158e8f20bd3275b9','tx_sfeventmgt_domain_model_registration',427,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',425,''),('b4dac8ccde595790e8985ab69889cc57','tx_sfeventmgt_domain_model_registration_fieldvalue',43,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('b638295f4ea667852491aa44f8c6914a','tx_sfeventmgt_domain_model_registration',523,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',13,''),('b6490fc7a296985f109e02abecc6f0f8','tx_sfeventmgt_domain_model_registration',561,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('b6660182dc5beaf6f49584a3725daf96','tx_sfeventmgt_domain_model_registration',261,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('b6c19665995ff9c98c32dad68b228024','tx_sfeventmgt_domain_model_registration',340,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',23,''),('b6f3d0b3d1ec9bcd215e204a7b39a7a5','tx_sfeventmgt_domain_model_registration',439,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('b717b60d0b606e3c6c3fcb6ebd123d1a','tx_sfeventmgt_domain_model_registration',265,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-en@sfeventmgt.local'),('b73ae2ebfe26f4c3ae3ca444621b1373','tx_sfeventmgt_domain_model_registration',391,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',389,''),('b753740a8d0ace885ada368d25f3d8bc','tx_sfeventmgt_domain_model_registration',278,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('b7a5362212c0675fc841d5e9f8966a92','tx_sfeventmgt_domain_model_registration',333,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('b80378c1351df3f5d87640beb2560830','tx_sfeventmgt_domain_model_registration',546,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('b81bd56c407ab2e81f55aa0b51384b07','tx_sfeventmgt_domain_model_registration',493,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',2,''),('b87df140218d7bf66152bb1d96303d01','tx_sfeventmgt_domain_model_registration',671,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-de@sfeventmgt.local'),('b8bc9f49d1d349ca606d238f757ddfd4','tx_sfeventmgt_domain_model_registration',395,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('b8d53d26acac210a640fc2cd0f204c95','tt_content',4,'pi_flexform','sDEF/lDEF/settings.storagePage/vDEF/','','',0,0,'pages',4,''),('b9c7c8a1ff132f10ba582135197dfca9','tx_sfeventmgt_domain_model_registration_fieldvalue',25,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('ba2c2bb38b93e8615b156dabf25f963c','tx_sfeventmgt_domain_model_registration',573,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('ba4fde3f8fa83366fa414d4c7cf4777a','tx_sfeventmgt_domain_model_registration',1,'email','','email','2',-1,0,'_STRING',0,'user1@sfeventmgt.local'),('ba5af553711024a13b1b05cb87f8037b','tx_sfeventmgt_domain_model_registration',149,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',72,''),('ba6daf5d5be5a18b6095cf2cfacf166f','tx_sfeventmgt_domain_model_registration',298,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('ba8d98c59dfb3241de6be140e7ec6edd','tt_content',10,'pi_flexform','s_redirect/lDEF/settings.redirectPageLogin/vDEF/','','',0,0,'pages',15,''),('ba9775808480f45bce3049fb96fd3a1c','tx_sfeventmgt_domain_model_registration',237,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',235,''),('babce548207d257ba25e00c1aa16363f','tx_sfeventmgt_domain_model_registration',646,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',40,''),('bac8485ecd5128213059d05c784c6bc8','tx_sfeventmgt_domain_model_registration',37,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',18,''),('bb29b0d12ee9b13d88ac86ce752ebc33','tx_sfeventmgt_domain_model_registration',579,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('bbbecfe89830c98153ec06bd9f88afd3','tx_sfeventmgt_domain_model_registration',569,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('bbe603edf01880ca73053b64ea3bfdc2','tx_sfeventmgt_domain_model_registration',2,'email','','email','2',-1,0,'_STRING',0,'user1@sfeventmgt.local'),('bcb2b6abd82f9190c6c757510c0a2431','tx_sfeventmgt_domain_model_registration',300,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',299,''),('bcf42bd7f1a06a4b370d676250e4d02d','tx_sfeventmgt_domain_model_registration',153,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',73,''),('bd487ceb08dbed0494bf23dea704817c','tx_sfeventmgt_domain_model_registration',479,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('bd56fa2347e7ee4319553ce42d17871d','tx_sfeventmgt_domain_model_registration',422,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('bdea14af6f954c64645dc7b4e21bad16','tx_sfeventmgt_domain_model_registration',454,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('be0416c74bffa472718885ed9ace4e0a','tx_sfeventmgt_domain_model_registration',635,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-de@sfeventmgt.local'),('be8be3366648d76e2a628a1eb9303985','sys_category',5,'items','','','',11,0,'tx_sfeventmgt_domain_model_event',14,''),('beb21c49a77412fbd04ebe5223a8b0bb','tx_sfeventmgt_domain_model_registration',470,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('bebb4416b9ef41994684f35cd75ef3f1','tx_sfeventmgt_domain_model_registration',525,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('bedc00375d3a3e37b1b66de6147c3792','tx_sfeventmgt_domain_model_registration',453,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',452,''),('bee3a09fa299652d479b38fc0018949a','tx_sfeventmgt_domain_model_registration',524,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('bf75a31bbb063e9c08723c6569ac8000','tx_sfeventmgt_domain_model_registration',518,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('bfd2eed2862a823f3d9b4aed0cff6488','tx_sfeventmgt_domain_model_registration',576,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',28,''),('bfe2418c6bc16812104996b3d8ccaa6e','tx_sfeventmgt_domain_model_registration',360,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('c009a18b2f3696b81f60bdbd91d1cefc','tx_sfeventmgt_domain_model_registration',505,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('c06ed9788beb5c974b301ebd13c520ea','tx_sfeventmgt_domain_model_registration',179,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',82,''),('c08b3e49fd93fd997a168fd826adef39','tt_content',2,'pi_flexform','additional/lDEF/settings.registrationPid/vDEF/','','',0,0,'pages',6,''),('c091321cc10e6b973b61952dcc05ae83','tx_sfeventmgt_domain_model_registration',430,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',8,''),('c12ce445596d146bfd12a87c4a0051b3','tx_sfeventmgt_domain_model_registration',400,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',398,''),('c1330026852b1df3caa457ca46859051','tx_sfeventmgt_domain_model_registration',657,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('c38aee3509b6901d126757d93d3cd7b5','tx_sfeventmgt_domain_model_registration',233,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('c401f3ec06c2c834e0ad8893e6a5ec76','tx_sfeventmgt_domain_model_registration',318,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',317,''),('c43f73d0087ffd5889fed88e2e2640d0','tx_sfeventmgt_domain_model_event',24,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_event',23,''),('c4971b97a71576fe4212df124eaedc27','tx_sfeventmgt_domain_model_registration',388,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('c4cdcdea23fba9b3b082fa15212b0b85','tx_sfeventmgt_domain_model_event',20,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_event',19,''),('c4fe9c5e8872c0c6dea8c01bc1e97191','tx_sfeventmgt_domain_model_registration',661,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('c5024e0ce0008fcce8d7f825c26078b0','tx_sfeventmgt_domain_model_event',11,'registration_fields','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',3,''),('c51d8a472c612d513eb27f326a799763','tx_sfeventmgt_domain_model_registration_fieldvalue',5,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('c520b7393793cb764a801681763077c1','tx_sfeventmgt_domain_model_registration',258,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',6,''),('c55f3f8c48f9cdf057bf29a481337abe','tx_sfeventmgt_domain_model_registration',389,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('c5938217ecfebe1bc8f34be7a9b8b4d9','tx_sfeventmgt_domain_model_registration',417,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('c62af09b5ab966bd8915591f5a724a0e','tx_sfeventmgt_domain_model_registration',254,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('c6c8b7b0db593ef0fe09908b78fbf6ab','tx_sfeventmgt_domain_model_location',3,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_location',1,''),('c742d7c92b0c799b492bc3cd301bafec','tx_sfeventmgt_domain_model_registration',412,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('c841ed4a6f57ebf5c22bb16e7e84fbeb','tx_sfeventmgt_domain_model_registration',277,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('c8612b36d594011de06a2848039fd78e','tx_sfeventmgt_domain_model_registration',486,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('c86e39c69389911c03c96e158ce41e89','tx_sfeventmgt_domain_model_registration',249,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',3,''),('c8dbdf624a88e1a3c5a356be0948342b','tx_sfeventmgt_domain_model_registration',457,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',14,''),('c92f352d0a5a4bac01043b80489c713a','tx_sfeventmgt_domain_model_registration',174,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',80,''),('c940af1075fbf5f2a8f764b908e8d88f','tx_sfeventmgt_domain_model_registration',309,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('c98debd9b664b75c07e01409e38e1f3f','tx_sfeventmgt_domain_model_registration',289,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('c99e0c0017adf1214c3ba877367d42b1','tx_sfeventmgt_domain_model_registration',109,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',54,''),('c9e5fd0779ac498c6d34d6dc7a1a2a14','tt_content',10,'l18n_parent','','','',0,0,'tt_content',9,''),('c9fa6799afb86238bbe678fb20d48474','tx_sfeventmgt_domain_model_registration',250,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('ca74bd206a61d29786bfaf9eecedb714','tx_sfeventmgt_domain_model_registration',338,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-en@sfeventmgt.local'),('ca8fa8085b0fa732e0d07a7a19da7fec','tx_sfeventmgt_domain_model_registration',570,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('caab77230afcfc284eff79b12760dcec','tx_sfeventmgt_domain_model_registration',145,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',69,''),('caffd7fe030f0fe4ea6aee130dff46c7','tx_sfeventmgt_domain_model_registration',358,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',4,''),('cb16fe39880a8ad4e5c7031b6f8c9370','tx_sfeventmgt_domain_model_registration',400,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('cb3607e6ddf4a248769d9bb08a5477a1','tx_sfeventmgt_domain_model_registration',313,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',18,''),('cb6061b56541972ed1bb061cb9f4927c','tx_sfeventmgt_domain_model_registration',221,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('cb9bdc06e5437dc14098e11895d9d528','tx_sfeventmgt_domain_model_event',11,'registration_fields','','','',1,0,'tx_sfeventmgt_domain_model_registration_field',4,''),('cbf6813ef23ac8c0a198bb81548fc105','tx_sfeventmgt_domain_model_registration',349,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',2,''),('cc0a5b64cb37c8f63af8fbf2d8479a6f','tx_sfeventmgt_domain_model_registration',475,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',18,''),('cc98d80a22e76c8fd85f6c31087d3b61','sys_category',1,'items','','','',13,0,'tx_sfeventmgt_domain_model_event',16,''),('cd009504f5a1e69ba17a5c211978dfdb','tx_sfeventmgt_domain_model_registration',508,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',7,''),('cd33caf4eadd27972ba268e0bb51d259','tx_sfeventmgt_domain_model_registration',408,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',407,''),('cd558e0c2c3aaedbb99993861546327d','tx_sfeventmgt_domain_model_registration',656,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('cd9a32d99a8e3b96f47b6576a1ffa939','tx_sfeventmgt_domain_model_registration',275,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('cdb7388fe3359c5ecf41cc4333ce5f91','tx_sfeventmgt_domain_model_registration',117,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',58,''),('cdf577dfd7f507bc37c0681e102ffcff','tx_sfeventmgt_domain_model_registration',236,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',235,''),('ce0b38595a858f8a5c2d02bdfc6b6c00','tx_sfeventmgt_domain_model_registration',45,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',22,''),('ce8d86f9c9db340304d4e20123a117f5','sys_category',6,'items','','','',1,0,'tx_sfeventmgt_domain_model_event',12,''),('ced8e6b1202cf9d0399ad0588eb56449','tx_sfeventmgt_domain_model_registration',463,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',461,''),('ceeb4e575ee75e2063a47f0565898b6e','tx_sfeventmgt_domain_model_event',3,'registration_fields','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('cf5c847f9ff528912a5efe9be088e7f6','tx_sfeventmgt_domain_model_registration',264,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('cf7438b44b1377f977c453e2a09d45fe','tx_sfeventmgt_domain_model_registration',540,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('cfcc50efb4ab6717b60bca7363601dce','tx_sfeventmgt_domain_model_registration',497,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('d05502d3a42338f98b11cd6b69923a85','tx_sfeventmgt_domain_model_registration',73,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',36,''),('d08f9b2ebcf62b00ccbeaf6040bf7c93','pages',15,'fe_group','','','',0,0,'fe_groups',1,''),('d0ccfc5317834a2a17733fe022cffd50','tt_content',5,'pi_flexform','additional/lDEF/settings.listPid/vDEF/','','',0,0,'pages',2,''),('d0db9539284592fe25668074141e53cc','tt_content',9,'pi_flexform','s_redirect/lDEF/settings.redirectPageLogin/vDEF/','','',0,0,'pages',15,''),('d16b49b99ebfd858e5a31deba38fd68a','tx_sfeventmgt_domain_model_registration',15,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',7,''),('d1bba48d86df0ccf27124f14ecc0daf6','tx_sfeventmgt_domain_model_registration',284,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-en@sfeventmgt.local'),('d1f7825340d202b18b3ddf9f2c35b465','tx_sfeventmgt_domain_model_registration',383,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-de@sfeventmgt.local'),('d1ff9f71c797f934c3d604db4d327dde','tx_sfeventmgt_domain_model_registration',363,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('d24fd908ce1fc63d2abb1f41e62ece61','tx_sfeventmgt_domain_model_registration',235,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('d2fb9ffcbfd8817ec39f33f6c5deb65c','tx_sfeventmgt_domain_model_registration',358,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('d3533ab0ea75e1f14bb123af510b4510','tx_sfeventmgt_domain_model_registration',491,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-de@sfeventmgt.local'),('d35a9f5012130b5a6bbaea1b4989e9ee','tx_sfeventmgt_domain_model_registration',468,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('d3bc91ff31de1c78fcba0399b54b5a8f','tx_sfeventmgt_domain_model_registration',69,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',34,''),('d4aaef77aea8ea9c2d54472f8db301bd','tx_sfeventmgt_domain_model_registration',329,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-de@sfeventmgt.local'),('d4ddf5cc06d9ea25ad885a2f118bd672','tx_sfeventmgt_domain_model_registration',658,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('d4e6da3c5fa784686fc17e70ed364520','tx_sfeventmgt_domain_model_registration',621,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('d57d5e1cc2a33c2c28d3419cb80ed8e1','tx_sfeventmgt_domain_model_registration',370,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('d58d273991007b876387c4e78b1b65bd','tx_sfeventmgt_domain_model_registration',286,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',11,''),('d5ddf6e629a5734bf68d1b6e56313d55','tx_sfeventmgt_domain_model_registration_fieldvalue',2,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('d61af90cf47a2cbb481b0beefd74cd6f','tx_sfeventmgt_domain_model_registration',399,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('d643e83d0f1fc51f019874f8d70b187c','sys_category',1,'items','','','',6,0,'tx_sfeventmgt_domain_model_event',8,''),('d643f9ddaac5c55c233f01c4fdbefa53','tx_sfeventmgt_domain_model_registration',538,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',19,''),('d6490fe236683024e06cd6dd0ded42e5','tx_sfeventmgt_domain_model_registration',376,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',7,''),('d737fe0bd1b443b76a729094ddaf1ff4','tx_sfeventmgt_domain_model_registration',247,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-en@sfeventmgt.local'),('d78c203a43a29ed676b476bde188b4e1','tx_sfeventmgt_domain_model_registration',412,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',4,''),('d7de42addc2aa4d1d11a91dadbdbeed1','tx_sfeventmgt_domain_model_registration',553,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('d813e2b6b92f4fe64f9f298627bd9937','tx_sfeventmgt_domain_model_registration',542,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-de@sfeventmgt.local'),('d8afdedcb3a93e5f6f28a7591cc722a7','tx_sfeventmgt_domain_model_registration',332,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('d8f028433bfa9b99474a87dd8cc00a01','tx_sfeventmgt_domain_model_registration',37,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',17,''),('d90349a72aa6fb8dfe335c8272795bd1','tx_sfeventmgt_domain_model_registration',516,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('d9a88059df1aa2342d6ad5ba48ad442e','tx_sfeventmgt_domain_model_registration',489,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('d9ad4f79f79566fd4b34f83728bf8eaa','tx_sfeventmgt_domain_model_registration',670,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('d9d15cdf1b5adb650a7fb69c07c6d468','tx_sfeventmgt_domain_model_registration',129,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',61,''),('da7c283b5e8322400d520df4dbc3b9b8','tx_sfeventmgt_domain_model_registration',448,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',11,''),('da847d55a29803bfcad434a7a83ab444','tx_sfeventmgt_domain_model_registration',554,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('dac1b631d27a6274fdb164da8e662308','tx_sfeventmgt_domain_model_registration',239,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('dac9c3ec401510233b2adf3ace5de3cf','tx_sfeventmgt_domain_model_registration',97,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',48,''),('db193397cbaca9e9d99bda7c424ecc45','tx_sfeventmgt_domain_model_registration_fieldvalue',34,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('dbfa7adec9cb3d033686a1e496eb6f22','tx_sfeventmgt_domain_model_registration',593,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('dc022034b83a81c9e15f3121f77fdb3b','tx_sfeventmgt_domain_model_registration',288,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('dc62e8bfda7f5b17f9c5aca28b3c5185','tt_content',11,'pi_flexform','sDEF/lDEF/settings.detailPid/vDEF/','','',0,0,'pages',3,''),('dc6fbd4601876992a997fcea0baee4aa','tx_sfeventmgt_domain_model_registration',625,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',623,''),('dcbb3089944756a2edda2d1e912f1946','tx_sfeventmgt_domain_model_registration',19,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',10,''),('dccb1d0b21eeed667c3770d1940af005','tx_sfeventmgt_domain_model_registration_fieldvalue',10,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('dcefc1365c36b2b671bfb12d2fe22463','tx_sfeventmgt_domain_model_registration',57,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',27,''),('dd09dfa20c4308a74a5432e6171aac02','tx_sfeventmgt_domain_model_registration',231,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('dd526b7e57ea4572d24a23333d8a7596','tx_sfeventmgt_domain_model_registration',485,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('ddc9043dfd29017bfdc18321a0db7ac1','tx_sfeventmgt_domain_model_registration',161,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',78,''),('ddf0ad502da32c13c3d8541e00e088d3','tx_sfeventmgt_domain_model_registration',648,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('de2bc2c371f59ed6aa865379457977dc','tx_sfeventmgt_domain_model_registration',417,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',416,''),('e00426da814c25c4b15c3101d068329a','tx_sfeventmgt_domain_model_registration',586,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('e00c09aadaf82e2fa157bbb4c1cc4719','tx_sfeventmgt_domain_model_registration',501,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('e0139b90f9a743c5d4477b2afe0e493c','tx_sfeventmgt_domain_model_registration',437,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-de@sfeventmgt.local'),('e08e0ec252d3e9dac272a3b0db45e5e2','tx_sfeventmgt_domain_model_registration',483,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('e11517a4e95148d6b0f7a93b881a877e','tx_sfeventmgt_domain_model_registration',227,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('e15ca10f62c3042406d60f96dd98ce13','tx_sfeventmgt_domain_model_registration',349,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',1,''),('e1aa6d3b927d358251a69746c9e2ae6c','tx_sfeventmgt_domain_model_registration_fieldvalue',7,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('e228f1290f77c21dbf9c402db467cf5d','tx_sfeventmgt_domain_model_registration',394,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',4,''),('e236cdc72055d6b34bdd2e4eeab10676','tx_sfeventmgt_domain_model_registration',272,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',271,''),('e23b30acfb6b4de394e33d10bb8159da','tx_sfeventmgt_domain_model_registration',670,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',668,''),('e23db06972cd945a52b39c93a12a7dce','tx_sfeventmgt_domain_model_registration',258,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',5,''),('e2b0fedb82e4cce153a80e92cacfa802','sys_template',1,'constants','','email','8',-1,0,'_STRING',0,'bcc@sfeventmgt.local'),('e353a958ba9ce2fb10254b6ba58a43ee','tx_sfeventmgt_domain_model_registration',101,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',50,''),('e3c8bb2ecd613e0b533459987208232a','tx_sfeventmgt_domain_model_registration',349,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('e41f9a65327a850b96087c11256e249a','tx_sfeventmgt_domain_model_registration_fieldvalue',3,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('e46cb956652a949a6ef09422465018a8','tx_sfeventmgt_domain_model_event',19,'registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',203,''),('e46f81d223fad26a96bb9d6402e7c4a5','tx_sfeventmgt_domain_model_registration',141,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',68,''),('e4e34df0697c34e959071954b2458dc8','tx_sfeventmgt_domain_model_registration',402,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('e54436c2bf927849c278fa4625fbb1a1','tx_sfeventmgt_domain_model_registration',567,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',25,''),('e6067e0b1ab852f6c95aad2f0122fbad','tx_sfeventmgt_domain_model_registration',535,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('e6423986214ec0b6ccf547e35da4bc48','tx_sfeventmgt_domain_model_registration',352,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('e7017d70563d1356df1fa5220da72398','tx_sfeventmgt_domain_model_registration',455,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-de@sfeventmgt.local'),('e75a5092b45859aff158436cc08bfb33','sys_category',5,'items','','','',12,0,'tx_sfeventmgt_domain_model_event',15,''),('e7806951ea1f0a211175b25974c8d475','tx_sfeventmgt_domain_model_registration',337,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('e7f8f73116ea00ae6c311520c529882f','tx_sfeventmgt_domain_model_registration',33,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',16,''),('e8484ba28867ed5d88630ebdcf1adacd','tx_sfeventmgt_domain_model_registration',585,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',584,''),('e8920a9131048835e561df42db3e35b5','pages',8,'l10n_parent','','','',0,0,'pages',3,''),('e8aeff483f1db6cf2fc4756c4351bd9f','tx_sfeventmgt_domain_model_registration',493,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',1,''),('e8d625b63e9a24d2773181db91f643e7','tx_sfeventmgt_domain_model_registration',129,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',62,''),('e917b7046a1377de67b503c69491b144','tx_sfeventmgt_domain_model_registration_fieldvalue',42,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('e958af0f0d2abd67b61f6f4299ad2940','tt_content',11,'pi_flexform','sDEF/lDEF/settings.registrationPid/vDEF/','','',0,0,'pages',6,''),('e97ebbef3b712f7fd8f578dc34778103','tt_content',1,'pi_flexform','additional/lDEF/settings.detailPid/vDEF/','','',0,0,'pages',3,''),('ea729909a18e6c45df39b50f4984b913','tt_content',8,'pi_flexform','categoryMenu/lDEF/settings.categoryMenu.categories/vDEF/','','',0,0,'sys_category',1,''),('ea74dc66f9172c6e92055d1bf08feb29','tx_sfeventmgt_domain_model_registration',537,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('ea9e2ca581607d4704c0639cf9624c2f','tx_sfeventmgt_domain_model_registration',358,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',3,''),('eaa4c5303623f4be5fc889132ebd486a','tx_sfeventmgt_domain_model_registration',53,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',26,''),('eaa985aacda3b92b9994ed49c473497e','tx_sfeventmgt_domain_model_registration',327,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',326,''),('eaf5659c71c1ba69f07230ebe966e68a','tx_sfeventmgt_domain_model_registration',267,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',7,''),('eb1b264f41e41d9298c63e5b4b2e769d','tx_sfeventmgt_domain_model_registration',269,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('eb2de9967888f72163ddb8335a951afb','tx_sfeventmgt_domain_model_registration',630,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('eb811d0495d0c77f4555e2c1507c5cba','tx_sfeventmgt_domain_model_registration',378,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('eb8da5aa9fabb49e9c9f51cf16a9516b','tx_sfeventmgt_domain_model_registration',448,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',12,''),('ebb41af8de3e4c913fcb9239fe4d3b42','tx_sfeventmgt_domain_model_event',13,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_event',5,''),('ebfe3c88fbd3c37f30f395757ca1a3b4','tx_sfeventmgt_domain_model_registration',664,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('ec477240c4e8c57bb3339bb4d3fd5c37','tx_sfeventmgt_domain_model_registration',222,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',1,''),('ec6a536bef8ac24407bfc4b39b18f978','tx_sfeventmgt_domain_model_registration',194,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',88,''),('ec6f51485db5a4b5120a50c9cf75eeb9','tx_sfeventmgt_domain_model_registration',302,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-en@sfeventmgt.local'),('ec7a67af22a6b01acaabada3cb1d5546','tx_sfeventmgt_domain_model_registration_fieldvalue',26,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('ec7efe2b3c5577e94fee7b2d92165e7b','tt_content',3,'pi_flexform','additional/lDEF/settings.listPid/vDEF/','','',0,0,'pages',2,''),('ecbe6b3a6f6e037f3f25baa3fda382eb','tx_sfeventmgt_domain_model_registration',615,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('eccb0a87718e1d9607d157b95798b749','tx_sfeventmgt_domain_model_registration',552,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('ecccddb3832433264e3d61008bc78e21','tx_sfeventmgt_domain_model_registration',510,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('ecfe4f9cd113557a828a7b5ba81e21a9','tx_sfeventmgt_domain_model_registration',213,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',3,''),('ed4ca864b60d65395f5c9aae007fc875','pages',7,'sys_language_uid','','','',0,0,'sys_language',1,''),('ed7e11c1851b9659dd6e29b2e92c0583','tx_sfeventmgt_domain_model_registration',428,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-en@sfeventmgt.local'),('ed93c23cd552e0384e4797e9893a9417','tx_sfeventmgt_domain_model_registration',287,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('ee70292fb0c411bacd91ff07ae46aee4','tx_sfeventmgt_domain_model_registration',141,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',67,''),('ee9e108bb9e566798e3e2b62cd537768','tx_sfeventmgt_domain_model_registration',381,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',380,''),('eeceb1d298c93b1aabb3d5da0a335c7e','tx_sfeventmgt_domain_model_registration',419,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-de@sfeventmgt.local'),('eef28b0be5800e748c59f4d106dba214','tx_sfeventmgt_domain_model_registration',538,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('ef6eebef370221e28c81e935389a2388','tx_sfeventmgt_domain_model_registration',347,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-de@sfeventmgt.local'),('f0592d0d8d65a8e5ba612842a6a30817','tx_sfeventmgt_domain_model_registration',503,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',6,''),('f06066cdd123824075dd70b236c59388','tx_sfeventmgt_domain_model_registration',666,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('f0a9c463a2723bb65312f44446535888','tx_sfeventmgt_domain_model_registration',487,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('f0ada2b472193d27ab7de9bd9180d1c2','tx_sfeventmgt_domain_model_registration',528,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',16,''),('f0aeea7082efddb56348c99ccf7e7f45','tx_sfeventmgt_domain_model_registration',232,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('f0bee51b98c28f0678109c3c981db820','tx_sfeventmgt_domain_model_registration',322,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',19,''),('f0f21be7cffbc5a7349eadc586d62d81','tx_sfeventmgt_domain_model_registration',449,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('f11903768d0305c25057c2b7b54171d6','tx_sfeventmgt_domain_model_event',14,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_event',6,''),('f1fcf3e4c6e31afe5e1c6280d18a0f11','tx_sfeventmgt_domain_model_registration',625,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('f203bcca6dc88a6826a92ef8819f50d0','tx_sfeventmgt_domain_model_registration',444,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('f21b9b7673be490bddf0191141f9ffbc','tx_sfeventmgt_domain_model_registration',444,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',443,''),('f23e3e8c7b1b4bd694123d52df3ab031','tx_sfeventmgt_domain_model_event',24,'location','','','',0,0,'tx_sfeventmgt_domain_model_location',1,''),('f25f0be6b1b5e4d54a6e7a452a415f21','tx_sfeventmgt_domain_model_registration',362,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('f31d08fd5e50f81e7c31de2b662f427a','tx_sfeventmgt_domain_model_registration_fieldvalue',21,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('f33df7714e35d96e5cd97d76d1dea945','tt_content',3,'pi_flexform','additional/lDEF/settings.detailPid/vDEF/','','',0,0,'pages',3,''),('f33fd95dda3db68524bbca094ebcdd38','sys_category',5,'items','','','',9,0,'tx_sfeventmgt_domain_model_event',11,''),('f34aa7b06b6446f4e57f5ca551da17f2','tx_sfeventmgt_domain_model_registration',572,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',571,''),('f3defbcebf592a6f4cbc7cd2c6b50f55','tx_sfeventmgt_domain_model_registration',336,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',335,''),('f483f2009466ccae56dc0a0e08e48175','tx_sfeventmgt_domain_model_registration',445,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('f49f3b7b279aaaf6da8122476405d532','tx_sfeventmgt_domain_model_registration',404,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('f659dcfdf5d160bfb9fb51d18763a883','pages',17,'l10n_parent','','','',0,0,'pages',15,''),('f7096cd34736299a5633c066fe124136','tx_sfeventmgt_domain_model_registration',601,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('f71bbc8871d82beda99f2fa67b2d6a3c','tx_sfeventmgt_domain_model_registration',506,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('f742178156e433e6c720e3e5c2a42d62','tx_sfeventmgt_domain_model_registration',267,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('f7d33f7f023a3f581759967f6932b64e','tx_sfeventmgt_domain_model_registration',534,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('f7d4b2a5fa7493a84801af8fb2688f1f','tx_sfeventmgt_domain_model_registration',183,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',84,''),('f7f1c5960160e66ffca3f3cd2c259a76','sys_category',1,'items','','','',11,0,'tx_sfeventmgt_domain_model_event',14,''),('f811bf597acc2db401ed2bb1066d4ac4','tx_sfeventmgt_domain_model_registration',655,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',41,''),('f819f879969deff610c780276bf4c549','tx_sfeventmgt_domain_model_registration',472,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('f88ed94ac4ccdf1f1d0b917a4f81f8bd','tx_sfeventmgt_domain_model_event',10,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_event',2,''),('f925429de92a45f5e0d0ab4d82d0cbc8','tx_sfeventmgt_domain_model_registration_fieldvalue',16,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('f928aeb08705bc4586c1802e3d387b90','tt_content',9,'pi_flexform','sDEF/lDEF/settings.pages/vDEF/','','',0,0,'pages',13,''),('f97f68f408b0f2c37633bf3a67c49880','tx_sfeventmgt_domain_model_registration',454,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',452,''),('f997a155693229b64646e591035c7740','tx_sfeventmgt_domain_model_registration',608,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('f997bf9d1803fc1e1199d38a2160ce8c','tx_sfeventmgt_domain_model_registration',340,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('f9e55bcb06dbe5680c3f20a64feae4ec','tx_sfeventmgt_domain_model_registration',342,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('fa1a2245acd8aad227f4c163b491f1ab','tx_sfeventmgt_domain_model_registration',472,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',470,''),('fa4ea2ca927179395ff2e82df07fbf46','sys_category',1,'items','','','',0,0,'tx_sfeventmgt_domain_model_event',1,''),('fa69705e73f6e79e2817a97eaba044b2','tt_content',1,'pi_flexform','additional/lDEF/settings.registrationPid/vDEF/','','',0,0,'pages',6,''),('fad01f320a178f3f1b0405de71c43ab0','tx_sfeventmgt_domain_model_registration',311,'email','','email','2',-1,0,'_STRING',0,'event-uid-test-de@sfeventmgt.local'),('fb3b4b9d88e00063250b99c7c99cc361','tx_sfeventmgt_domain_model_registration',448,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('fb9d3079a92e09e2829af89bc17bc882','tx_sfeventmgt_domain_model_registration',367,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('fbbd559fd280eb9696b86bc2b1c4f4e2','tx_sfeventmgt_domain_model_registration',439,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',10,''),('fc309fed90060dc3f6e769bbbe46258e','tx_sfeventmgt_domain_model_registration_fieldvalue',12,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('fcbca801747a77a13100b57926d2451c','tx_sfeventmgt_domain_model_registration',643,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',641,''),('fcceb31119c258b99391b3f4bbfeeceb','tx_sfeventmgt_domain_model_registration',544,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',543,''),('fd48c3f1826a6682e20354be1bf4fd35','tx_sfeventmgt_domain_model_registration_fieldvalue',6,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('fd8940e2aee2ac4b5415d95cd0c0bec2','tx_sfeventmgt_domain_model_registration',562,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('fe76800256be392f6fc367af1419773b','tx_sfeventmgt_domain_model_registration',475,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('fe85fc9a5094f6b2f591cc3004cd03c7','tx_sfeventmgt_domain_model_registration_fieldvalue',13,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('ff46203fc2b75475b72bf12a10736b5a','pages',12,'l10n_parent','','','',0,0,'pages',11,''),('ff4d50cfe8c9544c9910cfd85feb05c0','tx_sfeventmgt_domain_model_registration',607,'email','','email','2',-1,0,'_STRING',0,'johndoe@sfeventmgt.local'),('ffebdc753452c3d023c522de77806619','tx_sfeventmgt_domain_model_registration',322,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',20,'');
/*!40000 ALTER TABLE `sys_refindex` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sys_registry`
--

LOCK TABLES `sys_registry` WRITE;
/*!40000 ALTER TABLE `sys_registry` DISABLE KEYS */;
INSERT INTO `sys_registry` VALUES (1,'installUpdate','TYPO3\\CMS\\Form\\Hooks\\FormFileExtensionUpdate',_binary 'i:1;'),(2,'installUpdate','TYPO3\\CMS\\Install\\Updates\\ExtensionManagerTables',_binary 'i:1;'),(3,'installUpdate','TYPO3\\CMS\\Install\\Updates\\Typo3DbExtractionUpdate',_binary 'i:1;'),(4,'installUpdate','TYPO3\\CMS\\Install\\Updates\\FuncExtractionUpdate',_binary 'i:1;'),(5,'installUpdate','TYPO3\\CMS\\Install\\Updates\\MigrateUrlTypesInPagesUpdate',_binary 'i:1;'),(6,'installUpdate','TYPO3\\CMS\\Install\\Updates\\SeparateSysHistoryFromSysLogUpdate',_binary 'i:1;'),(7,'installUpdate','TYPO3\\CMS\\Install\\Updates\\RedirectExtractionUpdate',_binary 'i:1;'),(8,'installUpdate','TYPO3\\CMS\\Install\\Updates\\BackendUserStartModuleUpdate',_binary 'i:1;'),(9,'installUpdate','TYPO3\\CMS\\Install\\Updates\\MigratePagesLanguageOverlayUpdate',_binary 'i:1;'),(10,'installUpdate','TYPO3\\CMS\\Install\\Updates\\MigratePagesLanguageOverlayBeGroupsAccessRights',_binary 'i:1;'),(11,'installUpdate','TYPO3\\CMS\\Install\\Updates\\BackendLayoutIconUpdateWizard',_binary 'i:1;'),(12,'installUpdate','TYPO3\\CMS\\Install\\Updates\\RedirectsExtensionUpdate',_binary 'i:1;'),(13,'installUpdate','TYPO3\\CMS\\Install\\Updates\\AdminPanelInstall',_binary 'i:1;'),(14,'installUpdate','TYPO3\\CMS\\Install\\Updates\\PopulatePageSlugs',_binary 'i:1;'),(15,'installUpdate','TYPO3\\CMS\\Install\\Updates\\Argon2iPasswordHashes',_binary 'i:1;'),(16,'installUpdate','TYPO3\\CMS\\Install\\Updates\\BackendUserConfigurationUpdate',_binary 'i:1;'),(17,'installUpdate','TYPO3\\CMS\\Install\\Updates\\RsaauthExtractionUpdate',_binary 'i:1;'),(18,'installUpdate','TYPO3\\CMS\\Install\\Updates\\FeeditExtractionUpdate',_binary 'i:1;'),(19,'installUpdate','TYPO3\\CMS\\Install\\Updates\\TaskcenterExtractionUpdate',_binary 'i:1;'),(20,'installUpdate','TYPO3\\CMS\\Install\\Updates\\SysActionExtractionUpdate',_binary 'i:1;'),(21,'installUpdate','TYPO3\\CMS\\Felogin\\Updates\\MigrateFeloginPlugins',_binary 'i:1;'),(22,'installUpdate','TYPO3\\CMS\\FrontendLogin\\Updates\\MigrateFeloginPluginsCtype',_binary 'i:0;'),(24,'extensionDataImport','typo3conf/ext/sf_event_mgt/ext_tables_static+adt.sql',_binary 's:0:\"\";'),(27,'installUpdateRows','rowUpdatersDone',_binary 'a:4:{i:0;s:66:\"TYPO3\\CMS\\Install\\Updates\\RowUpdater\\L18nDiffsourceToJsonMigration\";i:1;s:77:\"TYPO3\\CMS\\Install\\Updates\\RowUpdater\\WorkspaceMovePlaceholderRemovalMigration\";i:2;s:76:\"TYPO3\\CMS\\Install\\Updates\\RowUpdater\\WorkspaceNewPlaceholderRemovalMigration\";i:3;s:69:\"TYPO3\\CMS\\Install\\Updates\\RowUpdater\\SysRedirectRootPageMoveMigration\";}'),(35,'installUpdate','TYPO3\\CMS\\Install\\Updates\\SvgFilesSanitization',_binary 'i:1;'),(37,'installUpdate','TYPO3\\CMS\\Install\\Updates\\BackendUserLanguageMigration',_binary 'i:1;'),(38,'installUpdate','TYPO3\\CMS\\Install\\Updates\\SysLogChannel',_binary 'i:1;'),(39,'core','formProtectionSessionToken:2',_binary 's:64:\"18cf0142baf318af690a98eb75e2db682dc8d07a35844e392f87d8ba8326fcd6\";'),(40,'installUpdate','DERHANSEN\\SfEventMgt\\Updates\\PiEventPluginUpdater',_binary 'i:1;'),(41,'core','formProtectionSessionToken:1',_binary 's:64:\"82c8315e1bc29e11122d778125fe58277e260947b7ad57c25eee1405729c3b35\";'),(42,'installUpdate','TYPO3\\CMS\\Install\\Updates\\ShortcutRecordsMigration',_binary 'i:1;'),(43,'installUpdate','TYPO3\\CMS\\Install\\Updates\\CollectionsExtractionUpdate',_binary 'i:1;'),(44,'installUpdate','TYPO3\\CMS\\Install\\Updates\\FeLoginModeExtractionUpdate',_binary 'i:1;'),(45,'installUpdate','TYPO3\\CMS\\Install\\Updates\\SysLogSerializationUpdate',_binary 'i:1;'),(46,'installUpdate','TYPO3\\CMS\\Install\\Updates\\BackendGroupsExplicitAllowDenyMigration',_binary 'i:1;'),(47,'installUpdate','TYPO3\\CMS\\Install\\Updates\\SysFileMountIdentifierMigration',_binary 'i:1;'),(48,'installUpdate','TYPO3\\CMS\\Install\\Updates\\BackendModulePermissionMigration',_binary 'i:1;'),(49,'installUpdate','TYPO3\\CMS\\Install\\Updates\\SysTemplateNoWorkspaceMigration',_binary 'i:1;'),(50,'installUpdate','TYPO3\\CMS\\Install\\Updates\\MigrateSiteSettingsConfigUpdate',_binary 'i:1;');
/*!40000 ALTER TABLE `sys_registry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sys_template`
--

LOCK TABLES `sys_template` WRITE;
/*!40000 ALTER TABLE `sys_template` DISABLE KEYS */;
INSERT INTO `sys_template` VALUES (1,1,1592715288,1586409884,0,0,0,0,256,NULL,0,'sf_event_mgt - Acceptance Tests',1,3,'EXT:fluid_styled_content/Configuration/TypoScript/,EXT:sf_event_mgt/Configuration/TypoScript','plugin.tx_sfeventmgt {\r\n  settings {\r\n    notification {\r\n      senderEmail = info@sfeventmgt.local\r\n      senderName = TYPO3 sf_event_mgt\r\n      senderSignature = Kind Regards<br/>TYPO3 sf_event_mgt\r\n      adminEmail = admin@sfeventmgt.local\r\n      bccEmail = bcc@sfeventmgt.local\r\n    }\r\n  }\r\n}','page = PAGE\r\npage.10 < styles.content.get\r\npage.includeCSS.events = EXT:sf_event_mgt/Resources/Public/Css/events_default.css','',0,0,0);
/*!40000 ALTER TABLE `sys_template` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tt_content`
--

LOCK TABLES `tt_content` WRITE;
/*!40000 ALTER TABLE `tt_content` DISABLE KEYS */;
INSERT INTO `tt_content` VALUES (1,'',2,1586430914,1586410175,0,0,0,0,'',256,0,0,0,0,NULL,0,_binary '{\"CType\":null,\"colPos\":null,\"header\":null,\"header_layout\":null,\"header_position\":null,\"date\":null,\"header_link\":null,\"subheader\":null,\"list_type\":null,\"pi_flexform\":null,\"frame_class\":null,\"space_before_class\":null,\"space_after_class\":null,\"sectionIndex\":null,\"linkToTop\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"editlock\":null,\"categories\":null,\"rowDescription\":null}',0,0,0,0,'list','','',NULL,0,0,0,0,0,0,0,2,0,0,0,'default',0,'','',NULL,NULL,0,'','',0,'0','sfeventmgt_pieventlist',1,0,NULL,0,'','','',0,0,0,'<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"settings.displayMode\">\n                    <value index=\"vDEF\">all</value>\n                </field>\n                <field index=\"settings.categoryConjunction\">\n                    <value index=\"vDEF\">OR</value>\n                </field>\n                <field index=\"settings.includeSubcategories\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.location\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.organisator\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.speaker\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.recursive\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.storagePage\">\n                    <value index=\"vDEF\">4</value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"additional\">\n            <language index=\"lDEF\">\n                <field index=\"settings.restrictForeignRecordsToStoragePage\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.disableOverrideDemand\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.detailPid\">\n                    <value index=\"vDEF\">3</value>\n                </field>\n                <field index=\"settings.registrationPid\">\n                    <value index=\"vDEF\">6</value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"categoryMenu\">\n            <language index=\"lDEF\">\n                <field index=\"settings.categoryMenu.categories\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.categoryMenu.includeSubcategories\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>','',0,'',NULL,'','',NULL,124,0,0,0,0,0),(2,'',3,1586410862,1586410364,0,0,0,0,'',256,0,0,0,0,NULL,0,_binary '{\"CType\":null,\"colPos\":null,\"header\":null,\"header_layout\":null,\"header_position\":null,\"date\":null,\"header_link\":null,\"subheader\":null,\"list_type\":null,\"pi_flexform\":null,\"frame_class\":null,\"space_before_class\":null,\"space_after_class\":null,\"sectionIndex\":null,\"linkToTop\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"editlock\":null,\"categories\":null,\"rowDescription\":null}',0,0,0,0,'list','','',NULL,0,0,0,0,0,0,0,2,0,0,0,'default',0,'','',NULL,'',0,'','',0,'0','sfeventmgt_pieventdetail',1,0,NULL,0,'','','',0,0,0,'<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"settings.recursive\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.singleEvent\">\n                    <value index=\"vDEF\"></value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"additional\">\n            <language index=\"lDEF\">\n                <field index=\"settings.listPid\">\n                    <value index=\"vDEF\">2</value>\n                </field>\n                <field index=\"settings.registrationPid\">\n                    <value index=\"vDEF\">6</value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>','',0,'',NULL,'','',NULL,124,0,0,0,0,0),(3,'',6,1586421201,1586410799,0,0,0,0,'',256,0,0,0,0,NULL,0,_binary '{\"CType\":null,\"colPos\":null,\"header\":null,\"header_layout\":null,\"header_position\":null,\"date\":null,\"header_link\":null,\"subheader\":null,\"list_type\":null,\"pi_flexform\":null,\"frame_class\":null,\"space_before_class\":null,\"space_after_class\":null,\"sectionIndex\":null,\"linkToTop\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"editlock\":null,\"categories\":null,\"rowDescription\":null}',0,0,0,0,'list','','',NULL,0,0,0,0,0,0,0,2,0,0,0,'default',0,'','',NULL,NULL,0,'','',0,'0','sfeventmgt_pieventregistration',1,0,NULL,0,'','','',0,0,0,'<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"settings.recursive\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.singleEvent\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.registration.requiredFields\">\n                    <value index=\"vDEF\">company</value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"additional\">\n            <language index=\"lDEF\">\n                <field index=\"settings.detailPid\">\n                    <value index=\"vDEF\">3</value>\n                </field>\n                <field index=\"settings.listPid\">\n                    <value index=\"vDEF\">2</value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>','',0,'',NULL,'','',NULL,124,0,0,0,0,0),(4,'',2,1586430903,1586418388,0,0,0,0,'',512,0,1,1,1,NULL,1,_binary '{\"CType\":\"list\",\"colPos\":0,\"header\":\"\",\"header_layout\":\"0\",\"header_position\":\"\",\"date\":0,\"header_link\":\"\",\"subheader\":\"\",\"list_type\":\"sfeventmgt_pievent\",\"pi_flexform\":\"<?xml version=\\\"1.0\\\" encoding=\\\"utf-8\\\" standalone=\\\"yes\\\" ?>\\n<T3FlexForms>\\n    <data>\\n        <sheet index=\\\"sDEF\\\">\\n            <language index=\\\"lDEF\\\">\\n                <field index=\\\"settings.displayMode\\\">\\n                    <value index=\\\"vDEF\\\">all<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.categoryConjunction\\\">\\n                    <value index=\\\"vDEF\\\">OR<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.includeSubcategories\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.location\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.organisator\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.speaker\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.recursive\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.storagePage\\\">\\n                    <value index=\\\"vDEF\\\">4<\\/value>\\n                <\\/field>\\n                <field index=\\\"switchableControllerActions\\\">\\n                    <value index=\\\"vDEF\\\">Event-&gt;list<\\/value>\\n                <\\/field>\\n            <\\/language>\\n        <\\/sheet>\\n        <sheet index=\\\"additional\\\">\\n            <language index=\\\"lDEF\\\">\\n                <field index=\\\"settings.restrictForeignRecordsToStoragePage\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.disableOverrideDemand\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.detailPid\\\">\\n                    <value index=\\\"vDEF\\\">3<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.registrationPid\\\">\\n                    <value index=\\\"vDEF\\\">6<\\/value>\\n                <\\/field>\\n            <\\/language>\\n        <\\/sheet>\\n        <sheet index=\\\"categoryMenu\\\">\\n            <language index=\\\"lDEF\\\">\\n                <field index=\\\"settings.categoryMenu.categories\\\">\\n                    <value index=\\\"vDEF\\\">1,2<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.categoryMenu.includeSubcategories\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n            <\\/language>\\n        <\\/sheet>\\n    <\\/data>\\n<\\/T3FlexForms>\",\"frame_class\":\"default\",\"space_before_class\":\"\",\"space_after_class\":\"\",\"sectionIndex\":1,\"linkToTop\":0,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":0,\"endtime\":0,\"fe_group\":\"\",\"editlock\":0,\"categories\":0,\"rowDescription\":\"\",\"l18n_parent\":0}',0,0,0,0,'list','','',NULL,0,0,0,0,0,0,0,2,0,0,0,'default',0,'','','','',0,'','',0,'0','sfeventmgt_pieventlist',1,0,'',0,'','','',0,0,0,'<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"settings.displayMode\">\n                    <value index=\"vDEF\">all</value>\n                </field>\n                <field index=\"settings.categoryConjunction\">\n                    <value index=\"vDEF\">OR</value>\n                </field>\n                <field index=\"settings.includeSubcategories\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.location\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.organisator\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.speaker\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.recursive\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.storagePage\">\n                    <value index=\"vDEF\">4</value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"additional\">\n            <language index=\"lDEF\">\n                <field index=\"settings.restrictForeignRecordsToStoragePage\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.disableOverrideDemand\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.detailPid\">\n                    <value index=\"vDEF\">3</value>\n                </field>\n                <field index=\"settings.registrationPid\">\n                    <value index=\"vDEF\">6</value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"categoryMenu\">\n            <language index=\"lDEF\">\n                <field index=\"settings.categoryMenu.categories\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.categoryMenu.includeSubcategories\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>','',0,'','','','',NULL,124,0,0,0,0,0),(5,'',3,1586418427,1586418420,0,0,0,0,'',512,0,1,2,2,NULL,2,_binary '{\"CType\":\"list\",\"colPos\":0,\"header\":\"\",\"header_layout\":\"0\",\"header_position\":\"\",\"date\":0,\"header_link\":\"\",\"subheader\":\"\",\"list_type\":\"sfeventmgt_pievent\",\"pi_flexform\":\"<?xml version=\\\"1.0\\\" encoding=\\\"utf-8\\\" standalone=\\\"yes\\\" ?>\\n<T3FlexForms>\\n    <data>\\n        <sheet index=\\\"sDEF\\\">\\n            <language index=\\\"lDEF\\\">\\n                <field index=\\\"settings.displayMode\\\">\\n                    <value index=\\\"vDEF\\\">all<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.categoryConjunction\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.includeSubcategories\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.location\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.organisator\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.speaker\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.recursive\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.singleEvent\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"switchableControllerActions\\\">\\n                    <value index=\\\"vDEF\\\">Event-&gt;detail;Event-&gt;icalDownload<\\/value>\\n                <\\/field>\\n            <\\/language>\\n        <\\/sheet>\\n        <sheet index=\\\"additional\\\">\\n            <language index=\\\"lDEF\\\">\\n                <field index=\\\"settings.restrictForeignRecordsToStoragePage\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.disableOverrideDemand\\\">\\n                    <value index=\\\"vDEF\\\">1<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.listPid\\\">\\n                    <value index=\\\"vDEF\\\">2<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.registrationPid\\\">\\n                    <value index=\\\"vDEF\\\">6<\\/value>\\n                <\\/field>\\n            <\\/language>\\n        <\\/sheet>\\n    <\\/data>\\n<\\/T3FlexForms>\",\"frame_class\":\"default\",\"space_before_class\":\"\",\"space_after_class\":\"\",\"sectionIndex\":1,\"linkToTop\":0,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":0,\"endtime\":0,\"fe_group\":\"\",\"editlock\":0,\"categories\":0,\"rowDescription\":\"\",\"l18n_parent\":0}',0,0,0,0,'list','','',NULL,0,0,0,0,0,0,0,2,0,0,0,'default',0,'','','','',0,'','',0,'0','sfeventmgt_pieventdetail',1,0,'',0,'','','',0,0,0,'<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"settings.recursive\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.singleEvent\">\n                    <value index=\"vDEF\"></value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"additional\">\n            <language index=\"lDEF\">\n                <field index=\"settings.listPid\">\n                    <value index=\"vDEF\">2</value>\n                </field>\n                <field index=\"settings.registrationPid\">\n                    <value index=\"vDEF\">6</value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>','',0,'','','','',NULL,124,0,0,0,0,0),(6,'',6,1586421210,1586418449,0,0,0,0,'',512,0,1,3,3,NULL,3,_binary '{\"CType\":\"list\",\"colPos\":0,\"header\":\"\",\"header_layout\":\"0\",\"header_position\":\"\",\"date\":0,\"header_link\":\"\",\"subheader\":\"\",\"list_type\":\"sfeventmgt_pievent\",\"pi_flexform\":\"<?xml version=\\\"1.0\\\" encoding=\\\"utf-8\\\" standalone=\\\"yes\\\" ?>\\n<T3FlexForms>\\n    <data>\\n        <sheet index=\\\"sDEF\\\">\\n            <language index=\\\"lDEF\\\">\\n                <field index=\\\"settings.displayMode\\\">\\n                    <value index=\\\"vDEF\\\">all<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.categoryConjunction\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.includeSubcategories\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.location\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.organisator\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.speaker\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.recursive\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.singleEvent\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"switchableControllerActions\\\">\\n                    <value index=\\\"vDEF\\\">Event-&gt;registration;Event-&gt;saveRegistration;Event-&gt;saveRegistrationResult;Event-&gt;confirmRegistration;Event-&gt;cancelRegistration<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.registration.requiredFields\\\">\\n                    <value index=\\\"vDEF\\\">company<\\/value>\\n                <\\/field>\\n            <\\/language>\\n        <\\/sheet>\\n        <sheet index=\\\"additional\\\">\\n            <language index=\\\"lDEF\\\">\\n                <field index=\\\"settings.detailPid\\\">\\n                    <value index=\\\"vDEF\\\">3<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.listPid\\\">\\n                    <value index=\\\"vDEF\\\">2<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.restrictForeignRecordsToStoragePage\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.disableOverrideDemand\\\">\\n                    <value index=\\\"vDEF\\\">1<\\/value>\\n                <\\/field>\\n            <\\/language>\\n        <\\/sheet>\\n        <sheet index=\\\"categoryMenu\\\">\\n            <language index=\\\"lDEF\\\">\\n                <field index=\\\"settings.categoryMenu.categories\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.categoryMenu.includeSubcategories\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n            <\\/language>\\n        <\\/sheet>\\n    <\\/data>\\n<\\/T3FlexForms>\",\"frame_class\":\"default\",\"space_before_class\":\"\",\"space_after_class\":\"\",\"sectionIndex\":1,\"linkToTop\":0,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":0,\"endtime\":0,\"fe_group\":\"\",\"editlock\":0,\"categories\":0,\"rowDescription\":\"\",\"l18n_parent\":0}',0,0,0,0,'list','','',NULL,0,0,0,0,0,0,0,2,0,0,0,'default',0,'','','','',0,'','',0,'0','sfeventmgt_pieventregistration',1,0,'',0,'','','',0,0,0,'<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"settings.recursive\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.singleEvent\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.registration.requiredFields\">\n                    <value index=\"vDEF\">company</value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"additional\">\n            <language index=\"lDEF\">\n                <field index=\"settings.detailPid\">\n                    <value index=\"vDEF\">3</value>\n                </field>\n                <field index=\"settings.listPid\">\n                    <value index=\"vDEF\">2</value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>','',0,'','','','',NULL,124,0,0,0,0,0),(7,'',11,1587579063,1586431348,0,0,0,0,'',256,0,0,0,0,NULL,1,_binary '{\"hidden\":null}',0,0,0,0,'list','','',NULL,0,0,0,0,0,0,0,2,0,0,0,'default',0,'','','','',0,'','',0,'0','sfeventmgt_pieventlist',1,0,'',0,'','','',0,0,0,'<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"settings.displayMode\">\n                    <value index=\"vDEF\">all</value>\n                </field>\n                <field index=\"settings.categoryConjunction\">\n                    <value index=\"vDEF\">OR</value>\n                </field>\n                <field index=\"settings.includeSubcategories\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.location\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.organisator\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.speaker\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.recursive\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.storagePage\">\n                    <value index=\"vDEF\">4</value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"additional\">\n            <language index=\"lDEF\">\n                <field index=\"settings.restrictForeignRecordsToStoragePage\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.disableOverrideDemand\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.detailPid\">\n                    <value index=\"vDEF\">3</value>\n                </field>\n                <field index=\"settings.registrationPid\">\n                    <value index=\"vDEF\">6</value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"categoryMenu\">\n            <language index=\"lDEF\">\n                <field index=\"settings.categoryMenu.categories\">\n                    <value index=\"vDEF\">1,2</value>\n                </field>\n                <field index=\"settings.categoryMenu.includeSubcategories\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>','',0,'','','','',NULL,124,0,0,0,0,0),(8,'',11,1587579064,1586431348,0,0,0,0,'',128,0,1,7,7,NULL,4,_binary '{\"CType\":\"list\",\"colPos\":0,\"header\":\"\",\"header_layout\":\"0\",\"header_position\":\"\",\"date\":0,\"header_link\":\"\",\"subheader\":\"\",\"list_type\":\"sfeventmgt_pievent\",\"pi_flexform\":\"<?xml version=\\\"1.0\\\" encoding=\\\"utf-8\\\" standalone=\\\"yes\\\" ?>\\n<T3FlexForms>\\n    <data>\\n        <sheet index=\\\"sDEF\\\">\\n            <language index=\\\"lDEF\\\">\\n                <field index=\\\"settings.displayMode\\\">\\n                    <value index=\\\"vDEF\\\">all<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.categoryConjunction\\\">\\n                    <value index=\\\"vDEF\\\">OR<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.includeSubcategories\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.location\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.organisator\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.speaker\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.recursive\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.storagePage\\\">\\n                    <value index=\\\"vDEF\\\">4<\\/value>\\n                <\\/field>\\n                <field index=\\\"switchableControllerActions\\\">\\n                    <value index=\\\"vDEF\\\">Event-&gt;list<\\/value>\\n                <\\/field>\\n            <\\/language>\\n        <\\/sheet>\\n        <sheet index=\\\"additional\\\">\\n            <language index=\\\"lDEF\\\">\\n                <field index=\\\"settings.restrictForeignRecordsToStoragePage\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.disableOverrideDemand\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.detailPid\\\">\\n                    <value index=\\\"vDEF\\\">3<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.registrationPid\\\">\\n                    <value index=\\\"vDEF\\\">6<\\/value>\\n                <\\/field>\\n            <\\/language>\\n        <\\/sheet>\\n        <sheet index=\\\"categoryMenu\\\">\\n            <language index=\\\"lDEF\\\">\\n                <field index=\\\"settings.categoryMenu.categories\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.categoryMenu.includeSubcategories\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n            <\\/language>\\n        <\\/sheet>\\n    <\\/data>\\n<\\/T3FlexForms>\",\"frame_class\":\"default\",\"space_before_class\":\"\",\"space_after_class\":\"\",\"sectionIndex\":1,\"linkToTop\":0,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":0,\"endtime\":0,\"fe_group\":\"\",\"editlock\":0,\"categories\":0,\"rowDescription\":\"\",\"l18n_parent\":0}',0,0,0,0,'list','','',NULL,0,0,0,0,0,0,0,2,0,0,0,'default',0,'','','','',0,'','',0,'0','sfeventmgt_pieventlist',1,0,'',0,'','','',0,0,0,'<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"settings.displayMode\">\n                    <value index=\"vDEF\">all</value>\n                </field>\n                <field index=\"settings.categoryConjunction\">\n                    <value index=\"vDEF\">OR</value>\n                </field>\n                <field index=\"settings.includeSubcategories\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.location\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.organisator\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.speaker\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.recursive\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.storagePage\">\n                    <value index=\"vDEF\">4</value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"additional\">\n            <language index=\"lDEF\">\n                <field index=\"settings.restrictForeignRecordsToStoragePage\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.disableOverrideDemand\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.detailPid\">\n                    <value index=\"vDEF\">3</value>\n                </field>\n                <field index=\"settings.registrationPid\">\n                    <value index=\"vDEF\">6</value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"categoryMenu\">\n            <language index=\"lDEF\">\n                <field index=\"settings.categoryMenu.categories\">\n                    <value index=\"vDEF\">1,2</value>\n                </field>\n                <field index=\"settings.categoryMenu.includeSubcategories\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>','',0,'','','','',NULL,124,0,0,0,0,0),(9,'',14,1586581516,1586581389,0,0,0,0,'',256,0,0,0,0,NULL,0,_binary '{\"CType\":null,\"colPos\":null,\"header\":null,\"header_layout\":null,\"header_position\":null,\"date\":null,\"header_link\":null,\"subheader\":null,\"pi_flexform\":null,\"layout\":null,\"frame_class\":null,\"space_before_class\":null,\"space_after_class\":null,\"sectionIndex\":null,\"linkToTop\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"editlock\":null,\"categories\":null,\"rowDescription\":null}',0,0,0,0,'felogin_login','','',NULL,0,0,0,0,0,0,0,2,0,0,0,'default',0,'','',NULL,NULL,0,'','',0,'0','',1,0,NULL,0,'','','',0,0,0,'<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"settings.showForgotPassword\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.showPermaLogin\">\n                    <value index=\"vDEF\">1</value>\n                </field>\n                <field index=\"settings.showLogoutFormAfterLogin\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.pages\">\n                    <value index=\"vDEF\">13</value>\n                </field>\n                <field index=\"settings.recursive\">\n                    <value index=\"vDEF\"></value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"s_redirect\">\n            <language index=\"lDEF\">\n                <field index=\"settings.redirectMode\">\n                    <value index=\"vDEF\">login</value>\n                </field>\n                <field index=\"settings.redirectFirstMethod\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.redirectPageLogin\">\n                    <value index=\"vDEF\">15</value>\n                </field>\n                <field index=\"settings.redirectPageLoginError\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.redirectPageLogout\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.redirectDisable\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"s_messages\">\n            <language index=\"lDEF\">\n                <field index=\"settings.welcome_header\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.welcome_message\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.success_header\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.success_message\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.error_header\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.error_message\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.status_header\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.status_message\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.logout_header\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.logout_message\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.forgot_header\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.forgot_reset_message\">\n                    <value index=\"vDEF\"></value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>','',0,'',NULL,'','',NULL,124,0,0,0,0,0),(10,'',14,1586581529,1586581524,0,0,0,0,'',512,0,1,9,9,NULL,9,_binary '{\"CType\":\"felogin_login\",\"colPos\":0,\"header\":\"\",\"header_layout\":\"0\",\"header_position\":\"\",\"date\":0,\"header_link\":\"\",\"subheader\":\"\",\"pi_flexform\":\"<?xml version=\\\"1.0\\\" encoding=\\\"utf-8\\\" standalone=\\\"yes\\\" ?>\\n<T3FlexForms>\\n    <data>\\n        <sheet index=\\\"sDEF\\\">\\n            <language index=\\\"lDEF\\\">\\n                <field index=\\\"settings.showForgotPassword\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.showPermaLogin\\\">\\n                    <value index=\\\"vDEF\\\">1<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.showLogoutFormAfterLogin\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.pages\\\">\\n                    <value index=\\\"vDEF\\\">13<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.recursive\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n            <\\/language>\\n        <\\/sheet>\\n        <sheet index=\\\"s_redirect\\\">\\n            <language index=\\\"lDEF\\\">\\n                <field index=\\\"settings.redirectMode\\\">\\n                    <value index=\\\"vDEF\\\">login<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.redirectFirstMethod\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.redirectPageLogin\\\">\\n                    <value index=\\\"vDEF\\\">15<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.redirectPageLoginError\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.redirectPageLogout\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.redirectDisable\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n            <\\/language>\\n        <\\/sheet>\\n        <sheet index=\\\"s_messages\\\">\\n            <language index=\\\"lDEF\\\">\\n                <field index=\\\"settings.welcome_header\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.welcome_message\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.success_header\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.success_message\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.error_header\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.error_message\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.status_header\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.status_message\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.logout_header\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.logout_message\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.forgot_header\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.forgot_reset_message\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n            <\\/language>\\n        <\\/sheet>\\n    <\\/data>\\n<\\/T3FlexForms>\",\"layout\":0,\"frame_class\":\"default\",\"space_before_class\":\"\",\"space_after_class\":\"\",\"sectionIndex\":1,\"linkToTop\":0,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":0,\"endtime\":0,\"fe_group\":\"\",\"editlock\":0,\"categories\":0,\"rowDescription\":\"\",\"l18n_parent\":0}',0,0,0,0,'felogin_login','','',NULL,0,0,0,0,0,0,0,2,0,0,0,'default',0,'','','','',0,'','',0,'0','',1,0,'',0,'','','',0,0,0,'<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"settings.showForgotPassword\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.showPermaLogin\">\n                    <value index=\"vDEF\">1</value>\n                </field>\n                <field index=\"settings.showLogoutFormAfterLogin\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.pages\">\n                    <value index=\"vDEF\">13</value>\n                </field>\n                <field index=\"settings.recursive\">\n                    <value index=\"vDEF\"></value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"s_redirect\">\n            <language index=\"lDEF\">\n                <field index=\"settings.redirectMode\">\n                    <value index=\"vDEF\">login</value>\n                </field>\n                <field index=\"settings.redirectFirstMethod\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.redirectPageLogin\">\n                    <value index=\"vDEF\">15</value>\n                </field>\n                <field index=\"settings.redirectPageLoginError\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.redirectPageLogout\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.redirectDisable\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"s_messages\">\n            <language index=\"lDEF\">\n                <field index=\"settings.welcome_header\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.welcome_message\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.success_header\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.success_message\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.error_header\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.error_message\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.status_header\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.status_message\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.logout_header\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.logout_message\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.forgot_header\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.forgot_reset_message\">\n                    <value index=\"vDEF\"></value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>','',0,'','','','',NULL,124,0,0,0,0,0),(11,'',15,1586581557,1586581542,0,0,0,0,'',256,0,0,0,0,NULL,0,_binary '{\"CType\":null,\"colPos\":null,\"header\":null,\"header_layout\":null,\"header_position\":null,\"date\":null,\"header_link\":null,\"subheader\":null,\"list_type\":null,\"pi_flexform\":null,\"frame_class\":null,\"space_before_class\":null,\"space_after_class\":null,\"sectionIndex\":null,\"linkToTop\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"editlock\":null,\"categories\":null,\"rowDescription\":null}',0,0,0,0,'list','','',NULL,0,0,0,0,0,0,0,2,0,0,0,'default',0,'','',NULL,'',0,'','',0,'0','sfeventmgt_piuserreg',1,0,NULL,0,'','','',0,0,0,'<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"settings.userRegistration.displayMode\">\n                    <value index=\"vDEF\">all</value>\n                </field>\n                <field index=\"settings.registrationPid\">\n                    <value index=\"vDEF\">6</value>\n                </field>\n                <field index=\"settings.detailPid\">\n                    <value index=\"vDEF\">3</value>\n                </field>\n                <field index=\"settings.userRegistration.storagePage\">\n                    <value index=\"vDEF\">4</value>\n                </field>\n                <field index=\"settings.userRegistration.recursive\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>','',0,'',NULL,'','',NULL,124,0,0,0,0,0),(12,'',15,1586581977,1586581971,0,0,0,0,'',512,0,1,11,11,NULL,11,_binary '{\"CType\":\"list\",\"colPos\":0,\"header\":\"\",\"header_layout\":\"0\",\"header_position\":\"\",\"date\":0,\"header_link\":\"\",\"subheader\":\"\",\"list_type\":\"sfeventmgt_piuserreg\",\"pi_flexform\":\"<?xml version=\\\"1.0\\\" encoding=\\\"utf-8\\\" standalone=\\\"yes\\\" ?>\\n<T3FlexForms>\\n    <data>\\n        <sheet index=\\\"sDEF\\\">\\n            <language index=\\\"lDEF\\\">\\n                <field index=\\\"settings.userRegistration.displayMode\\\">\\n                    <value index=\\\"vDEF\\\">all<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.registrationPid\\\">\\n                    <value index=\\\"vDEF\\\">6<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.detailPid\\\">\\n                    <value index=\\\"vDEF\\\">3<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.userRegistration.storagePage\\\">\\n                    <value index=\\\"vDEF\\\">4<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.userRegistration.recursive\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n            <\\/language>\\n        <\\/sheet>\\n    <\\/data>\\n<\\/T3FlexForms>\",\"frame_class\":\"default\",\"space_before_class\":\"\",\"space_after_class\":\"\",\"sectionIndex\":1,\"linkToTop\":0,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":0,\"endtime\":0,\"fe_group\":\"\",\"editlock\":0,\"categories\":0,\"rowDescription\":\"\",\"l18n_parent\":0}',0,0,0,0,'list','','',NULL,0,0,0,0,0,0,0,2,0,0,0,'default',0,'','','','',0,'','',0,'0','sfeventmgt_piuserreg',1,0,'',0,'','','',0,0,0,'<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"settings.userRegistration.displayMode\">\n                    <value index=\"vDEF\">all</value>\n                </field>\n                <field index=\"settings.registrationPid\">\n                    <value index=\"vDEF\">6</value>\n                </field>\n                <field index=\"settings.detailPid\">\n                    <value index=\"vDEF\">3</value>\n                </field>\n                <field index=\"settings.userRegistration.storagePage\">\n                    <value index=\"vDEF\">4</value>\n                </field>\n                <field index=\"settings.userRegistration.recursive\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>','',0,'','','','',NULL,124,0,0,0,0,0);
/*!40000 ALTER TABLE `tt_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tx_extensionmanager_domain_model_extension`
--

LOCK TABLES `tx_extensionmanager_domain_model_extension` WRITE;
/*!40000 ALTER TABLE `tx_extensionmanager_domain_model_extension` DISABLE KEYS */;
/*!40000 ALTER TABLE `tx_extensionmanager_domain_model_extension` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tx_impexp_presets`
--

LOCK TABLES `tx_impexp_presets` WRITE;
/*!40000 ALTER TABLE `tx_impexp_presets` DISABLE KEYS */;
/*!40000 ALTER TABLE `tx_impexp_presets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tx_sfeventmgt_domain_model_customnotificationlog`
--

LOCK TABLES `tx_sfeventmgt_domain_model_customnotificationlog` WRITE;
/*!40000 ALTER TABLE `tx_sfeventmgt_domain_model_customnotificationlog` DISABLE KEYS */;
/*!40000 ALTER TABLE `tx_sfeventmgt_domain_model_customnotificationlog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tx_sfeventmgt_domain_model_event`
--

LOCK TABLES `tx_sfeventmgt_domain_model_event` WRITE;
/*!40000 ALTER TABLE `tx_sfeventmgt_domain_model_event` DISABLE KEYS */;
INSERT INTO `tx_sfeventmgt_domain_model_event` VALUES (1,4,'',1586418834,1586410578,0,0,0,0,'',0,0,0,0,0,1792,0,0,_binary '{\"title\":null,\"top_event\":null,\"slug\":null,\"startdate\":null,\"enddate\":null,\"teaser\":null,\"description\":null,\"price\":null,\"currency\":null,\"price_options\":null,\"link\":null,\"program\":null,\"location\":null,\"room\":null,\"organisator\":null,\"speaker\":null,\"related\":null,\"image\":null,\"files\":null,\"additional_image\":null,\"category\":null,\"enable_registration\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"rowDescription\":null}',NULL,'Event (no reg, cat1) [DE]','','','',1754035200,1754121600,0,1,0,'',0,0,NULL,1,0,0,0,0,'0',0,0,'0',0,'',0,0,0,'',0,0,0,1,0,0,0,0,0,'event-no-reg-cat1-de',0,0,NULL,NULL,NULL,NULL,0),(2,4,'',1757077861,1586410906,0,0,0,0,'',0,0,0,0,0,1664,0,0,_binary '{\"title\":\"\",\"top_event\":\"\",\"slug\":\"\",\"startdate\":\"\",\"enddate\":\"\",\"teaser\":\"\",\"description\":\"\",\"price\":\"\",\"currency\":\"\",\"price_options\":\"\",\"link\":\"\",\"program\":\"\",\"custom_text\":\"\",\"location\":\"\",\"room\":\"\",\"organisator\":\"\",\"speaker\":\"\",\"related\":\"\",\"image\":\"\",\"files\":\"\",\"additional_image\":\"\",\"category\":\"\",\"enable_registration\":\"\",\"allow_registration_until_enddate\":\"\",\"registration_startdate\":\"\",\"registration_deadline\":\"\",\"max_participants\":\"\",\"max_registrations_per_user\":\"\",\"enable_cancel\":\"\",\"enable_waitlist\":\"\",\"enable_autoconfirm\":\"\",\"unique_email_check\":\"\",\"notify_admin\":\"\",\"notify_organisator\":\"\",\"enable_payment\":\"\",\"registration_fields\":\"\",\"registration\":\"\",\"meta_keywords\":\"\",\"meta_description\":\"\",\"alternative_title\":\"\",\"sys_language_uid\":\"\",\"hidden\":\"\",\"starttime\":\"\",\"endtime\":\"\",\"fe_group\":\"\",\"rowDescription\":\"\"}',NULL,'Event (reg, cat1) [DE]','','','',1911888000,1911981600,0,1,0,'',0,0,NULL,2,1,0,0,0,'0',0,0,'0',0,'',1,0,0,'',0,0,0,1,0,0,0,0,0,'event-reg-cat1-de',0,0,NULL,NULL,NULL,NULL,0),(3,4,'',1757077840,1586417669,0,0,0,0,'',0,0,0,0,0,1600,0,0,_binary '{\"title\":\"\",\"top_event\":\"\",\"slug\":\"\",\"startdate\":\"\",\"enddate\":\"\",\"teaser\":\"\",\"description\":\"\",\"price\":\"\",\"currency\":\"\",\"price_options\":\"\",\"link\":\"\",\"program\":\"\",\"custom_text\":\"\",\"location\":\"\",\"room\":\"\",\"organisator\":\"\",\"speaker\":\"\",\"related\":\"\",\"image\":\"\",\"files\":\"\",\"additional_image\":\"\",\"category\":\"\",\"enable_registration\":\"\",\"allow_registration_until_enddate\":\"\",\"registration_startdate\":\"\",\"registration_deadline\":\"\",\"max_participants\":\"\",\"max_registrations_per_user\":\"\",\"enable_cancel\":\"\",\"enable_waitlist\":\"\",\"enable_autoconfirm\":\"\",\"unique_email_check\":\"\",\"notify_admin\":\"\",\"notify_organisator\":\"\",\"enable_payment\":\"\",\"registration_fields\":\"\",\"registration\":\"\",\"meta_keywords\":\"\",\"meta_description\":\"\",\"alternative_title\":\"\",\"sys_language_uid\":\"\",\"hidden\":\"\",\"starttime\":\"\",\"endtime\":\"\",\"fe_group\":\"\",\"rowDescription\":\"\"}',NULL,'Event (reg, regfields, cat1) [DE]','','','',1912060800,1912075200,0,1,0,'',0,0,NULL,2,0,0,2,0,'0',0,0,'0',0,'',1,0,0,'',0,0,0,1,0,0,0,0,0,'event-reg-regfields-cat1-de',0,0,NULL,NULL,NULL,NULL,0),(4,4,'',1586418686,1586418500,0,0,0,0,'',0,0,0,0,0,1568,0,0,_binary '{\"title\":null,\"top_event\":null,\"slug\":null,\"startdate\":null,\"enddate\":null,\"teaser\":null,\"description\":null,\"price\":null,\"currency\":null,\"price_options\":null,\"link\":null,\"program\":null,\"location\":null,\"room\":null,\"organisator\":null,\"speaker\":null,\"related\":null,\"image\":null,\"files\":null,\"additional_image\":null,\"category\":null,\"enable_registration\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"rowDescription\":null}',NULL,'Event (no reg, cat2) [DE]','','','',1754035200,1754128800,0,1,0,'',0,0,NULL,1,0,0,0,0,'0',0,0,'0',0,'',0,0,0,'',0,0,0,1,0,0,0,0,0,'event-no-reg-cat2-de',0,0,NULL,NULL,NULL,NULL,0),(5,4,'',1586418957,1586418920,0,0,0,0,'',0,0,0,0,0,1552,0,0,_binary '{\"title\":null,\"top_event\":null,\"slug\":null,\"startdate\":null,\"enddate\":null,\"teaser\":null,\"description\":null,\"price\":null,\"currency\":null,\"price_options\":null,\"link\":null,\"program\":null,\"location\":null,\"room\":null,\"organisator\":null,\"speaker\":null,\"related\":null,\"image\":null,\"files\":null,\"additional_image\":null,\"category\":null,\"enable_registration\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"rowDescription\":null}',NULL,'Expired Event (reg, cat1) [DE]','','','',1577869200,1577876400,0,1,0,'',0,0,NULL,1,0,0,0,0,'0',0,0,'0',0,'',1,0,0,'',0,0,0,1,0,0,0,0,0,'expired-event-no-reg-cat1-de',0,0,NULL,NULL,NULL,NULL,0),(6,4,'',1757077807,1586419216,0,0,0,0,'',0,0,0,0,0,1544,0,0,_binary '{\"title\":\"\",\"top_event\":\"\",\"slug\":\"\",\"startdate\":\"\",\"enddate\":\"\",\"teaser\":\"\",\"description\":\"\",\"price\":\"\",\"currency\":\"\",\"price_options\":\"\",\"link\":\"\",\"program\":\"\",\"custom_text\":\"\",\"location\":\"\",\"room\":\"\",\"organisator\":\"\",\"speaker\":\"\",\"related\":\"\",\"image\":\"\",\"files\":\"\",\"additional_image\":\"\",\"category\":\"\",\"enable_registration\":\"\",\"allow_registration_until_enddate\":\"\",\"registration_startdate\":\"\",\"registration_deadline\":\"\",\"max_participants\":\"\",\"max_registrations_per_user\":\"\",\"enable_cancel\":\"\",\"enable_waitlist\":\"\",\"enable_autoconfirm\":\"\",\"unique_email_check\":\"\",\"notify_admin\":\"\",\"notify_organisator\":\"\",\"enable_payment\":\"\",\"registration_fields\":\"\",\"registration\":\"\",\"meta_keywords\":\"\",\"meta_description\":\"\",\"alternative_title\":\"\",\"sys_language_uid\":\"\",\"hidden\":\"\",\"starttime\":\"\",\"endtime\":\"\",\"fe_group\":\"\",\"rowDescription\":\"\"}',NULL,'Event fully booked (reg, cat1) [DE]','','','',1912060800,1912068000,1,1,0,'',0,0,NULL,2,1,0,0,0,'0',0,0,'0',0,'',1,0,0,'',0,0,0,1,0,0,0,0,0,'event-fully-booked-reg-cat1-de',0,0,NULL,NULL,NULL,NULL,0),(7,4,'',1757077782,1586419352,0,0,0,0,'',0,0,0,0,2,1540,0,0,_binary '{\"title\":\"\",\"top_event\":\"\",\"slug\":\"\",\"startdate\":\"\",\"enddate\":\"\",\"teaser\":\"\",\"description\":\"\",\"price\":\"\",\"currency\":\"\",\"price_options\":\"\",\"link\":\"\",\"program\":\"\",\"custom_text\":\"\",\"location\":\"\",\"room\":\"\",\"organisator\":\"\",\"speaker\":\"\",\"related\":\"\",\"image\":\"\",\"files\":\"\",\"additional_image\":\"\",\"category\":\"\",\"enable_registration\":\"\",\"allow_registration_until_enddate\":\"\",\"registration_startdate\":\"\",\"registration_deadline\":\"\",\"max_participants\":\"\",\"max_registrations_per_user\":\"\",\"enable_cancel\":\"\",\"enable_waitlist\":\"\",\"enable_autoconfirm\":\"\",\"unique_email_check\":\"\",\"notify_admin\":\"\",\"notify_organisator\":\"\",\"enable_payment\":\"\",\"registration_fields\":\"\",\"registration\":\"\",\"meta_keywords\":\"\",\"meta_description\":\"\",\"alternative_title\":\"\",\"sys_language_uid\":\"\",\"hidden\":\"\",\"starttime\":\"\",\"endtime\":\"\",\"fe_group\":\"\",\"rowDescription\":\"\"}',NULL,'Event (reg, cat1, autoconfirm) [DE]','','','',1912233600,1912240800,0,1,0,'',0,0,'',2,0,0,0,0,'0',0,0,'0',0,'',1,0,0,'',0,0,0,1,0,0,0,1,0,'event-reg-cat1-autoconfirm-de',0,0,NULL,NULL,NULL,NULL,0),(8,4,'',1757077769,1586419432,0,0,0,0,'',0,0,0,0,6,1538,0,0,_binary '{\"title\":\"\",\"top_event\":\"\",\"slug\":\"\",\"startdate\":\"\",\"enddate\":\"\",\"teaser\":\"\",\"description\":\"\",\"price\":\"\",\"currency\":\"\",\"price_options\":\"\",\"link\":\"\",\"program\":\"\",\"custom_text\":\"\",\"location\":\"\",\"room\":\"\",\"organisator\":\"\",\"speaker\":\"\",\"related\":\"\",\"image\":\"\",\"files\":\"\",\"additional_image\":\"\",\"category\":\"\",\"enable_registration\":\"\",\"allow_registration_until_enddate\":\"\",\"registration_startdate\":\"\",\"registration_deadline\":\"\",\"max_participants\":\"\",\"max_registrations_per_user\":\"\",\"enable_cancel\":\"\",\"enable_waitlist\":\"\",\"enable_autoconfirm\":\"\",\"unique_email_check\":\"\",\"notify_admin\":\"\",\"notify_organisator\":\"\",\"enable_payment\":\"\",\"registration_fields\":\"\",\"registration\":\"\",\"registration_waitlist\":\"\",\"meta_keywords\":\"\",\"meta_description\":\"\",\"alternative_title\":\"\",\"sys_language_uid\":\"\",\"hidden\":\"\",\"starttime\":\"\",\"endtime\":\"\",\"fe_group\":\"\",\"rowDescription\":\"\"}',NULL,'Event fully booked waitlist (reg, cat1) [DE]','','','',1912060800,1912068000,1,1,0,'',0,0,'',2,1,0,0,0,'0',0,0,'0',0,'',1,1,0,'',0,0,0,1,0,0,0,0,0,'event-fully-booked-waitlist-reg-cat1-de',0,0,NULL,NULL,NULL,NULL,0),(9,4,'',1586429566,1586429557,0,0,0,0,'',0,0,0,0,1,2048,1,1,_binary '{\"title\":\"Event (no reg, cat1) [DE]\",\"top_event\":0,\"slug\":\"event-no-reg-cat1-de\",\"startdate\":1754035200,\"enddate\":1754121600,\"teaser\":\"\",\"description\":\"\",\"price\":null,\"currency\":null,\"price_options\":null,\"link\":null,\"program\":\"\",\"location\":null,\"room\":null,\"organisator\":null,\"speaker\":null,\"related\":null,\"image\":null,\"files\":null,\"additional_image\":null,\"category\":null,\"enable_registration\":0,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"rowDescription\":\"\",\"l10n_parent\":0,\"enable_waitlist\":0,\"registration_deadline\":0,\"enable_cancel\":0,\"cancel_deadline\":0,\"enable_autoconfirm\":0,\"max_participants\":0,\"max_registrations_per_user\":1,\"notify_admin\":1,\"notify_organisator\":0,\"unique_email_check\":0}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"fe_group\":\"parent\",\"link\":\"parent\",\"price\":\"parent\",\"currency\":\"parent\",\"enable_payment\":\"parent\",\"restrict_payment_methods\":\"parent\",\"selected_payment_methods\":\"parent\",\"location\":\"parent\",\"room\":\"parent\",\"organisator\":\"parent\",\"speaker\":\"parent\",\"image\":\"parent\",\"files\":\"parent\",\"related\":\"parent\",\"additional_image\":\"parent\",\"registration_fields\":\"parent\",\"price_options\":\"parent\",\"category\":\"parent\"}','Event (no reg, cat1) [EN]','','','',1754035200,1754121600,0,1,0,'',0,0,'',1,0,0,0,0,'',0,0,'',0,'',0,0,0,'',0,0,0,1,0,0,0,0,0,'event-no-reg-cat1-en',0,0,NULL,NULL,NULL,NULL,0),(10,4,'',1757077861,1586429571,0,0,0,0,'',0,0,0,0,2,1728,1,2,_binary '{\"title\":\"Event (reg, cat1) [DE]\",\"top_event\":\"0\",\"slug\":\"event-reg-cat1-de\",\"startdate\":\"1911888000\",\"enddate\":\"1911981600\",\"teaser\":\"\",\"description\":\"\",\"price\":\"0\",\"currency\":\"\",\"price_options\":\"0\",\"link\":\"\",\"program\":\"\",\"location\":\"0\",\"room\":\"\",\"organisator\":\"0\",\"speaker\":\"0\",\"related\":\"0\",\"image\":null,\"files\":null,\"additional_image\":null,\"category\":\"2\",\"enable_registration\":\"1\",\"registration_deadline\":\"0\",\"enable_cancel\":\"0\",\"max_participants\":\"0\",\"max_registrations_per_user\":\"1\",\"enable_autoconfirm\":\"0\",\"enable_waitlist\":\"0\",\"unique_email_check\":\"0\",\"notify_admin\":\"1\",\"notify_organisator\":\"0\",\"registration_fields\":\"0\",\"registration\":0,\"enable_payment\":\"0\",\"sys_language_uid\":0,\"hidden\":0,\"starttime\":\"0\",\"endtime\":\"0\",\"fe_group\":\"\",\"rowDescription\":\"\",\"l10n_parent\":0,\"cancel_deadline\":\"0\",\"restrict_payment_methods\":\"0\",\"selected_payment_methods\":\"\",\"allow_registration_until_enddate\":\"0\",\"enable_waitlist_moveup\":\"0\",\"registration_startdate\":\"0\"}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"fe_group\":\"parent\",\"link\":\"parent\",\"price\":\"parent\",\"currency\":\"parent\",\"enable_payment\":\"parent\",\"restrict_payment_methods\":\"parent\",\"selected_payment_methods\":\"parent\",\"location\":\"parent\",\"room\":\"parent\",\"organisator\":\"parent\",\"speaker\":\"parent\",\"related\":\"parent\",\"registration_fields\":\"parent\",\"price_options\":\"parent\",\"category\":\"parent\"}','Event (reg, cat1) [EN]','','','',1911888000,1911981600,0,1,0,'',0,0,'',2,1,0,0,0,'',0,0,'',0,'',1,0,0,'',0,0,0,1,0,0,0,0,0,'event-reg-cat1-en',0,0,NULL,NULL,NULL,NULL,0),(11,4,'',1757077840,1586429584,0,0,0,0,'',0,0,0,0,3,1632,1,3,_binary '{\"title\":\"Event (reg, regfields, cat1) [DE]\",\"top_event\":\"0\",\"slug\":\"event-reg-regfields-cat1-de\",\"startdate\":\"1912060800\",\"enddate\":\"1912075200\",\"teaser\":\"\",\"description\":\"\",\"price\":\"0\",\"currency\":\"\",\"price_options\":\"0\",\"link\":\"\",\"program\":\"\",\"location\":\"0\",\"room\":\"\",\"organisator\":\"0\",\"speaker\":\"0\",\"related\":\"0\",\"image\":null,\"files\":null,\"additional_image\":null,\"category\":\"2\",\"enable_registration\":\"1\",\"registration_deadline\":\"0\",\"enable_cancel\":\"0\",\"max_participants\":\"0\",\"max_registrations_per_user\":\"1\",\"enable_autoconfirm\":\"0\",\"enable_waitlist\":\"0\",\"unique_email_check\":\"0\",\"notify_admin\":\"1\",\"notify_organisator\":\"0\",\"registration_fields\":\"2\",\"registration\":0,\"enable_payment\":\"0\",\"sys_language_uid\":0,\"hidden\":0,\"starttime\":\"0\",\"endtime\":\"0\",\"fe_group\":\"\",\"rowDescription\":\"\",\"l10n_parent\":0,\"cancel_deadline\":\"0\",\"restrict_payment_methods\":\"0\",\"selected_payment_methods\":\"\",\"allow_registration_until_enddate\":\"0\",\"enable_waitlist_moveup\":\"0\",\"registration_startdate\":\"0\"}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"fe_group\":\"parent\",\"link\":\"parent\",\"price\":\"parent\",\"currency\":\"parent\",\"enable_payment\":\"parent\",\"restrict_payment_methods\":\"parent\",\"selected_payment_methods\":\"parent\",\"location\":\"parent\",\"room\":\"parent\",\"organisator\":\"parent\",\"speaker\":\"parent\",\"related\":\"parent\",\"registration_fields\":\"parent\",\"price_options\":\"parent\",\"category\":\"parent\"}','Event (reg, regfields, cat1) [EN]','','','',1912060800,1912075200,0,1,0,'',0,0,'',2,1,0,2,0,'',0,0,'',0,'',1,0,0,'',0,0,0,1,0,0,0,0,0,'event-reg-regfields-cat1-en',0,0,NULL,NULL,NULL,NULL,0),(12,4,'',1586429630,1586429621,0,0,0,0,'',0,0,0,0,4,1584,1,4,_binary '{\"title\":\"Event (no reg, cat2) [DE]\",\"top_event\":0,\"slug\":\"event-no-reg-cat2-de\",\"startdate\":1754035200,\"enddate\":1754128800,\"teaser\":\"\",\"description\":\"\",\"price\":null,\"currency\":null,\"price_options\":null,\"link\":null,\"program\":\"\",\"location\":null,\"room\":null,\"organisator\":null,\"speaker\":null,\"related\":null,\"image\":null,\"files\":null,\"additional_image\":null,\"category\":null,\"enable_registration\":0,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"rowDescription\":\"\",\"l10n_parent\":0,\"enable_waitlist\":0,\"registration_deadline\":0,\"enable_cancel\":0,\"cancel_deadline\":0,\"enable_autoconfirm\":0,\"max_participants\":0,\"max_registrations_per_user\":1,\"notify_admin\":1,\"notify_organisator\":0,\"unique_email_check\":0}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"fe_group\":\"parent\",\"link\":\"parent\",\"price\":\"parent\",\"currency\":\"parent\",\"enable_payment\":\"parent\",\"restrict_payment_methods\":\"parent\",\"selected_payment_methods\":\"parent\",\"location\":\"parent\",\"room\":\"parent\",\"organisator\":\"parent\",\"speaker\":\"parent\",\"image\":\"parent\",\"files\":\"parent\",\"related\":\"parent\",\"additional_image\":\"parent\",\"registration_fields\":\"parent\",\"price_options\":\"parent\",\"category\":\"parent\"}','Event (no reg, cat2) [EN]','','','',1754035200,1754128800,0,1,0,'',0,0,'',1,0,0,0,0,'',0,0,'',0,'',0,0,0,'',0,0,0,1,0,0,0,0,0,'event-no-reg-cat2-en',0,0,NULL,NULL,NULL,NULL,0),(13,4,'',1586429650,1586429641,0,0,0,0,'',0,0,0,0,5,1560,1,5,_binary '{\"title\":\"Expired Event (reg, cat1) [DE]\",\"top_event\":0,\"slug\":\"expired-event-no-reg-cat1-de\",\"startdate\":1577869200,\"enddate\":1577876400,\"teaser\":\"\",\"description\":\"\",\"price\":null,\"currency\":null,\"price_options\":null,\"link\":null,\"program\":\"\",\"location\":null,\"room\":null,\"organisator\":null,\"speaker\":null,\"related\":null,\"image\":null,\"files\":null,\"additional_image\":null,\"category\":null,\"enable_registration\":1,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"rowDescription\":\"\",\"registration\":0,\"l10n_parent\":0,\"enable_waitlist\":0,\"registration_deadline\":0,\"enable_cancel\":0,\"cancel_deadline\":0,\"enable_autoconfirm\":0,\"max_participants\":0,\"max_registrations_per_user\":1,\"notify_admin\":1,\"notify_organisator\":0,\"unique_email_check\":0}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"fe_group\":\"parent\",\"link\":\"parent\",\"price\":\"parent\",\"currency\":\"parent\",\"enable_payment\":\"parent\",\"restrict_payment_methods\":\"parent\",\"selected_payment_methods\":\"parent\",\"location\":\"parent\",\"room\":\"parent\",\"organisator\":\"parent\",\"speaker\":\"parent\",\"image\":\"parent\",\"files\":\"parent\",\"related\":\"parent\",\"additional_image\":\"parent\",\"registration_fields\":\"parent\",\"price_options\":\"parent\",\"category\":\"parent\"}','Expired Event (reg, cat1) [EN]','','','',1577869200,1577876400,0,1,0,'',0,0,'',1,0,0,0,0,'',0,0,'',0,'',1,0,0,'',0,0,0,1,0,0,0,0,0,'expired-event-reg-cat1-en',0,0,NULL,NULL,NULL,NULL,0),(14,4,'',1757077807,1586429655,0,0,0,0,'',0,0,0,0,6,1548,1,6,_binary '{\"title\":\"Event fully booked (reg, cat1) [DE]\",\"top_event\":\"0\",\"slug\":\"event-fully-booked-reg-cat1-de\",\"startdate\":\"1912060800\",\"enddate\":\"1912068000\",\"teaser\":\"\",\"description\":\"\",\"price\":\"0\",\"currency\":\"\",\"price_options\":\"0\",\"link\":\"\",\"program\":\"\",\"location\":\"0\",\"room\":\"\",\"organisator\":\"0\",\"speaker\":\"0\",\"related\":\"0\",\"image\":null,\"files\":null,\"additional_image\":null,\"category\":\"2\",\"enable_registration\":\"1\",\"registration_deadline\":\"0\",\"enable_cancel\":\"0\",\"max_participants\":\"1\",\"max_registrations_per_user\":\"1\",\"enable_autoconfirm\":\"0\",\"enable_waitlist\":\"0\",\"unique_email_check\":\"0\",\"notify_admin\":\"1\",\"notify_organisator\":\"0\",\"registration_fields\":\"0\",\"registration\":1,\"enable_payment\":\"0\",\"sys_language_uid\":0,\"hidden\":0,\"starttime\":\"0\",\"endtime\":\"0\",\"fe_group\":\"\",\"rowDescription\":\"\",\"l10n_parent\":0,\"cancel_deadline\":\"0\",\"restrict_payment_methods\":\"0\",\"selected_payment_methods\":\"\",\"allow_registration_until_enddate\":\"0\",\"enable_waitlist_moveup\":\"0\",\"registration_startdate\":\"0\"}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"fe_group\":\"parent\",\"link\":\"parent\",\"price\":\"parent\",\"currency\":\"parent\",\"enable_payment\":\"parent\",\"restrict_payment_methods\":\"parent\",\"selected_payment_methods\":\"parent\",\"location\":\"parent\",\"room\":\"parent\",\"organisator\":\"parent\",\"speaker\":\"parent\",\"related\":\"parent\",\"registration_fields\":\"parent\",\"price_options\":\"parent\",\"category\":\"parent\"}','Event fully booked (reg, cat1) [EN]','','','',1912060800,1912068000,1,1,0,'',0,0,'',2,0,0,0,0,'',0,0,'',0,'',1,0,0,'',0,0,0,1,0,0,0,0,0,'event-fully-booked-reg-cat1-en',0,0,NULL,NULL,NULL,NULL,0),(15,4,'',1757077782,1586429730,0,0,0,0,'',0,0,0,0,7,1542,1,7,_binary '{\"title\":\"Event (reg, cat1, autoconfirm) [DE]\",\"top_event\":\"0\",\"slug\":\"event-reg-cat1-autoconfirm-de\",\"startdate\":\"1912233600\",\"enddate\":\"1912240800\",\"teaser\":\"\",\"description\":\"\",\"price\":\"0\",\"currency\":\"\",\"price_options\":\"0\",\"link\":\"\",\"program\":\"\",\"location\":\"0\",\"room\":\"\",\"organisator\":\"0\",\"speaker\":\"0\",\"related\":\"0\",\"image\":null,\"files\":null,\"additional_image\":null,\"category\":\"2\",\"enable_registration\":\"1\",\"registration_deadline\":\"0\",\"enable_cancel\":\"0\",\"max_participants\":\"0\",\"max_registrations_per_user\":\"1\",\"enable_autoconfirm\":\"1\",\"enable_waitlist\":\"0\",\"unique_email_check\":\"0\",\"notify_admin\":\"1\",\"notify_organisator\":\"0\",\"registration_fields\":\"0\",\"registration\":0,\"enable_payment\":\"0\",\"sys_language_uid\":0,\"hidden\":0,\"starttime\":\"0\",\"endtime\":\"0\",\"fe_group\":\"\",\"rowDescription\":\"\",\"l10n_parent\":0,\"cancel_deadline\":\"0\",\"restrict_payment_methods\":\"0\",\"selected_payment_methods\":\"\",\"allow_registration_until_enddate\":\"0\",\"enable_waitlist_moveup\":\"0\",\"registration_startdate\":\"0\"}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"fe_group\":\"parent\",\"link\":\"parent\",\"price\":\"parent\",\"currency\":\"parent\",\"enable_payment\":\"parent\",\"restrict_payment_methods\":\"parent\",\"selected_payment_methods\":\"parent\",\"location\":\"parent\",\"room\":\"parent\",\"organisator\":\"parent\",\"speaker\":\"parent\",\"related\":\"parent\",\"registration_fields\":\"parent\",\"price_options\":\"parent\",\"category\":\"parent\"}','Event (reg, cat1, autoconfirm) [EN]','','','',1912233600,1912240800,0,1,0,'',0,0,'',2,1,0,0,0,'',0,0,'',0,'',1,0,0,'',0,0,0,1,0,0,0,1,0,'event-reg-cat1-autoconfirm-en',0,0,NULL,NULL,NULL,NULL,0),(16,4,'',1757077769,1586429741,0,0,0,0,'',0,0,0,0,8,1539,1,8,_binary '{\"title\":\"Event fully booked waitlist (reg, cat1) [DE]\",\"top_event\":\"0\",\"slug\":\"event-fully-booked-reg-cat1-1\",\"startdate\":\"1912060800\",\"enddate\":\"1912068000\",\"teaser\":\"\",\"description\":\"\",\"price\":\"0\",\"currency\":\"\",\"price_options\":\"0\",\"link\":\"\",\"program\":\"\",\"location\":\"0\",\"room\":\"\",\"organisator\":\"0\",\"speaker\":\"0\",\"related\":\"0\",\"image\":\"0\",\"files\":0,\"additional_image\":\"0\",\"category\":\"2\",\"enable_registration\":\"1\",\"registration_deadline\":\"0\",\"enable_cancel\":\"0\",\"max_participants\":\"1\",\"max_registrations_per_user\":\"1\",\"enable_autoconfirm\":\"0\",\"enable_waitlist\":\"1\",\"unique_email_check\":\"0\",\"notify_admin\":\"1\",\"notify_organisator\":\"0\",\"registration_fields\":\"0\",\"registration\":1,\"registration_waitlist\":0,\"enable_payment\":\"0\",\"sys_language_uid\":0,\"hidden\":0,\"starttime\":\"0\",\"endtime\":\"0\",\"fe_group\":\"\",\"rowDescription\":\"\",\"l10n_parent\":0,\"cancel_deadline\":\"0\",\"restrict_payment_methods\":\"0\",\"selected_payment_methods\":\"\",\"allow_registration_until_enddate\":\"0\",\"enable_waitlist_moveup\":\"0\",\"registration_startdate\":\"0\"}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"fe_group\":\"parent\",\"link\":\"parent\",\"price\":\"parent\",\"currency\":\"parent\",\"enable_payment\":\"parent\",\"restrict_payment_methods\":\"parent\",\"selected_payment_methods\":\"parent\",\"location\":\"parent\",\"room\":\"parent\",\"organisator\":\"parent\",\"speaker\":\"parent\",\"related\":\"parent\",\"registration_fields\":\"parent\",\"price_options\":\"parent\",\"category\":\"parent\"}','Event fully booked waitlist (reg, cat1) [EN]','','','',1912060800,1912068000,1,1,0,'',0,0,'',2,1,1,0,0,'0',0,0,'0',0,'',1,1,0,'',0,0,0,1,0,0,0,0,0,'event-fully-booked-waitlist-reg-cat1-en',0,0,NULL,NULL,NULL,NULL,0),(17,4,'',1757077754,1586455055,0,0,0,0,'',0,0,0,0,0,1025,0,0,_binary '{\"title\":\"\",\"top_event\":\"\",\"slug\":\"\",\"startdate\":\"\",\"enddate\":\"\",\"teaser\":\"\",\"description\":\"\",\"price\":\"\",\"currency\":\"\",\"price_options\":\"\",\"link\":\"\",\"program\":\"\",\"custom_text\":\"\",\"location\":\"\",\"room\":\"\",\"organisator\":\"\",\"speaker\":\"\",\"related\":\"\",\"image\":\"\",\"files\":\"\",\"additional_image\":\"\",\"category\":\"\",\"enable_registration\":\"\",\"allow_registration_until_enddate\":\"\",\"registration_startdate\":\"\",\"registration_deadline\":\"\",\"max_participants\":\"\",\"max_registrations_per_user\":\"\",\"enable_cancel\":\"\",\"enable_waitlist\":\"\",\"enable_autoconfirm\":\"\",\"unique_email_check\":\"\",\"notify_admin\":\"\",\"notify_organisator\":\"\",\"enable_payment\":\"\",\"registration_fields\":\"\",\"registration\":\"\",\"meta_keywords\":\"\",\"meta_description\":\"\",\"alternative_title\":\"\",\"sys_language_uid\":\"\",\"hidden\":\"\",\"starttime\":\"\",\"endtime\":\"\",\"fe_group\":\"\",\"rowDescription\":\"\"}',NULL,'Event (reg, cat3, autoconfirm) [DE]','','','',1912233600,1912233600,0,1,0,'',0,0,NULL,1,0,0,0,0,'0',0,0,'0',0,'',1,0,0,'',0,0,0,1,0,0,0,1,0,'event-reg-cat3-autoconfirm-de',0,0,NULL,NULL,NULL,NULL,0),(18,4,'',1757077754,1586455122,0,0,0,0,'',0,0,0,0,17,1281,1,17,_binary '{\"title\":\"Event (reg, cat3, autoconfirm) [DE]\",\"top_event\":\"0\",\"slug\":\"event-reg-cat3-autoconfirm-de\",\"startdate\":\"1912233600\",\"enddate\":\"1912233600\",\"teaser\":\"\",\"description\":\"\",\"price\":\"0\",\"currency\":\"\",\"price_options\":\"0\",\"link\":\"\",\"program\":\"\",\"location\":\"0\",\"room\":\"\",\"organisator\":\"0\",\"speaker\":\"0\",\"related\":\"0\",\"image\":null,\"files\":null,\"additional_image\":null,\"category\":\"1\",\"enable_registration\":\"1\",\"registration_deadline\":\"0\",\"enable_cancel\":\"0\",\"max_participants\":\"0\",\"max_registrations_per_user\":\"1\",\"enable_autoconfirm\":\"1\",\"enable_waitlist\":\"0\",\"unique_email_check\":\"0\",\"notify_admin\":\"1\",\"notify_organisator\":\"0\",\"registration_fields\":\"0\",\"registration\":0,\"enable_payment\":\"0\",\"sys_language_uid\":0,\"hidden\":0,\"starttime\":\"0\",\"endtime\":\"0\",\"fe_group\":\"\",\"rowDescription\":\"\",\"l10n_parent\":0,\"cancel_deadline\":\"0\",\"restrict_payment_methods\":\"0\",\"selected_payment_methods\":\"\",\"allow_registration_until_enddate\":\"0\",\"enable_waitlist_moveup\":\"0\",\"registration_startdate\":\"0\"}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"fe_group\":\"parent\",\"link\":\"parent\",\"price\":\"parent\",\"currency\":\"parent\",\"enable_payment\":\"parent\",\"restrict_payment_methods\":\"parent\",\"selected_payment_methods\":\"parent\",\"location\":\"parent\",\"room\":\"parent\",\"organisator\":\"parent\",\"speaker\":\"parent\",\"related\":\"parent\",\"registration_fields\":\"parent\",\"price_options\":\"parent\",\"category\":\"parent\"}','Event (reg, cat3, autoconfirm) [EN]','','','',1912233600,1912233600,0,1,0,'',0,0,'',1,1,0,0,0,'',0,0,'',0,'',1,0,0,'',0,0,0,1,0,0,0,1,0,'event-reg-cat3-autoconfirm-en',0,0,NULL,NULL,NULL,NULL,0),(19,4,'',1608446456,1586582042,0,0,0,0,'',0,0,0,0,0,512,0,0,_binary '{\"title\":null,\"top_event\":null,\"slug\":null,\"startdate\":null,\"enddate\":null,\"teaser\":null,\"description\":null,\"price\":null,\"currency\":null,\"price_options\":null,\"link\":null,\"program\":null,\"location\":null,\"room\":null,\"organisator\":null,\"speaker\":null,\"related\":null,\"image\":null,\"files\":null,\"additional_image\":null,\"category\":null,\"enable_registration\":null,\"registration_startdate\":null,\"registration_deadline\":null,\"enable_cancel\":null,\"cancel_deadline\":null,\"max_participants\":null,\"max_registrations_per_user\":null,\"enable_waitlist\":null,\"enable_waitlist_moveup\":null,\"enable_autoconfirm\":null,\"unique_email_check\":null,\"notify_admin\":null,\"notify_organisator\":null,\"registration_fields\":null,\"registration\":null,\"enable_payment\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"rowDescription\":null}',NULL,'Expired Event (cat1, fe_user: user1) [DE]','','','',1577869200,1577872800,0,1,0,'',0,0,NULL,0,1,0,0,0,'0',0,0,'0',0,'',1,0,0,'',0,0,0,1,0,1,0,0,0,'expired-event-cat1-fe-user-user1-de',0,0,NULL,NULL,NULL,NULL,0),(20,4,'',1608446456,1586582466,0,0,0,0,'',0,0,0,0,19,768,1,19,_binary '{\"title\":\"Expired Event (cat1, fe_user: user1) [DE]\",\"top_event\":0,\"slug\":\"expired-event-cat1-fe-user-user1-de\",\"startdate\":1577869200,\"enddate\":1577872800,\"teaser\":\"\",\"description\":\"\",\"price\":0,\"currency\":\"\",\"price_options\":0,\"link\":\"\",\"program\":\"\",\"location\":0,\"room\":\"\",\"organisator\":0,\"speaker\":0,\"related\":0,\"image\":\"0\",\"files\":0,\"additional_image\":\"0\",\"category\":0,\"enable_registration\":1,\"registration_deadline\":0,\"enable_cancel\":1,\"cancel_deadline\":0,\"max_participants\":0,\"max_registrations_per_user\":1,\"enable_autoconfirm\":0,\"enable_waitlist\":0,\"unique_email_check\":0,\"notify_admin\":1,\"notify_organisator\":0,\"registration_fields\":0,\"registration\":1,\"enable_payment\":0,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":0,\"endtime\":0,\"fe_group\":\"\",\"rowDescription\":\"\",\"l10n_parent\":0,\"restrict_payment_methods\":0,\"selected_payment_methods\":null,\"enable_waitlist_moveup\":0,\"registration_startdate\":0}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"fe_group\":\"parent\",\"link\":\"parent\",\"price\":\"parent\",\"currency\":\"parent\",\"enable_payment\":\"parent\",\"restrict_payment_methods\":\"parent\",\"selected_payment_methods\":\"parent\",\"location\":\"parent\",\"room\":\"parent\",\"organisator\":\"parent\",\"speaker\":\"parent\",\"image\":\"parent\",\"files\":\"parent\",\"related\":\"parent\",\"additional_image\":\"parent\",\"registration_fields\":\"parent\",\"price_options\":\"parent\",\"category\":\"parent\"}','Expired Event (cat1, fe_user: user1) [EN]','','','',1577869200,1577872800,0,1,0,'',0,0,'',0,0,0,0,0,'0',0,0,'0',0,'',1,0,0,'',0,0,0,1,0,1,0,0,0,'expired-event-cat1-fe-user-user1-en',0,0,NULL,NULL,NULL,NULL,0),(21,4,'',1757078585,1587531096,0,0,0,0,'',0,0,0,0,0,256,0,0,_binary '{\"title\":\"\",\"top_event\":\"\",\"slug\":\"\",\"startdate\":\"\",\"enddate\":\"\",\"teaser\":\"\",\"description\":\"\",\"price\":\"\",\"currency\":\"\",\"price_options\":\"\",\"link\":\"\",\"program\":\"\",\"custom_text\":\"\",\"location\":\"\",\"room\":\"\",\"organisator\":\"\",\"speaker\":\"\",\"related\":\"\",\"image\":\"\",\"files\":\"\",\"additional_image\":\"\",\"category\":\"\",\"enable_registration\":\"\",\"allow_registration_until_enddate\":\"\",\"registration_startdate\":\"\",\"registration_deadline\":\"\",\"max_participants\":\"\",\"max_registrations_per_user\":\"\",\"enable_cancel\":\"\",\"enable_waitlist\":\"\",\"enable_autoconfirm\":\"\",\"unique_email_check\":\"\",\"notify_admin\":\"\",\"notify_organisator\":\"\",\"enable_payment\":\"\",\"registration_fields\":\"\",\"registration\":\"\",\"meta_keywords\":\"\",\"meta_description\":\"\",\"alternative_title\":\"\",\"sys_language_uid\":\"\",\"hidden\":\"\",\"starttime\":\"\",\"endtime\":\"\",\"fe_group\":\"\",\"rowDescription\":\"\"}',NULL,'Event (reg, cat1, multireg) [DE]','','','',1843459200,1843552800,0,3,0,'',0,0,NULL,0,0,0,0,0,'0',0,0,'0',0,'',1,0,0,'',0,0,0,1,0,0,0,0,0,'event-reg-cat1-multireg-de',0,0,NULL,NULL,NULL,NULL,0),(22,4,'',1757078585,1587531123,0,0,0,0,'',0,0,0,0,21,384,1,21,_binary '{\"title\":\"Event (reg, cat1, multireg) [DE]\",\"top_event\":\"0\",\"slug\":\"event-reg-cat1-multireg-de\",\"startdate\":\"1843459200\",\"enddate\":\"1843552800\",\"teaser\":\"\",\"description\":\"\",\"price\":\"0\",\"currency\":\"\",\"price_options\":\"0\",\"link\":\"\",\"program\":\"\",\"location\":\"0\",\"room\":\"\",\"organisator\":\"0\",\"speaker\":\"0\",\"related\":\"0\",\"image\":\"0\",\"files\":\"0\",\"additional_image\":\"0\",\"category\":\"0\",\"enable_registration\":\"1\",\"registration_deadline\":\"0\",\"enable_cancel\":\"0\",\"max_participants\":\"0\",\"max_registrations_per_user\":\"3\",\"enable_autoconfirm\":\"0\",\"enable_waitlist\":\"0\",\"unique_email_check\":\"0\",\"notify_admin\":\"1\",\"notify_organisator\":\"0\",\"registration_fields\":\"0\",\"registration\":\"0\",\"enable_payment\":\"0\",\"sys_language_uid\":\"0\",\"hidden\":\"0\",\"starttime\":\"0\",\"endtime\":\"0\",\"fe_group\":\"\",\"rowDescription\":\"\",\"l10n_parent\":\"0\",\"cancel_deadline\":\"0\",\"restrict_payment_methods\":\"0\",\"selected_payment_methods\":\"\",\"allow_registration_until_enddate\":\"0\",\"enable_waitlist_moveup\":\"0\",\"registration_startdate\":\"0\",\"custom_text\":\"\",\"meta_keywords\":\"\",\"meta_description\":\"\",\"alternative_title\":\"\"}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"fe_group\":\"parent\",\"link\":\"parent\",\"price\":\"parent\",\"currency\":\"parent\",\"enable_payment\":\"parent\",\"restrict_payment_methods\":\"parent\",\"selected_payment_methods\":\"parent\",\"location\":\"parent\",\"room\":\"parent\",\"organisator\":\"parent\",\"speaker\":\"parent\",\"related\":\"parent\",\"registration_fields\":\"parent\",\"price_options\":\"parent\",\"category\":\"parent\"}','Event (reg, cat1, multireg) [EN]','','','',1843459200,1843552800,0,3,0,'',0,0,'',0,0,0,0,0,'0',0,0,'0',0,'',1,0,0,'',0,0,0,1,0,0,0,0,0,'event-reg-cat1-multireg-en',0,0,NULL,NULL,NULL,NULL,0),(23,4,'',1609137784,1608471394,0,0,0,0,'',0,0,0,0,0,128,0,0,_binary '{\"title\":null,\"top_event\":null,\"slug\":null,\"startdate\":null,\"enddate\":null,\"teaser\":null,\"description\":null,\"price\":null,\"currency\":null,\"price_options\":null,\"link\":null,\"program\":null,\"location\":null,\"room\":null,\"organisator\":null,\"speaker\":null,\"related\":null,\"image\":null,\"files\":null,\"additional_image\":null,\"category\":null,\"enable_registration\":null,\"registration_startdate\":null,\"registration_deadline\":null,\"enable_cancel\":null,\"max_participants\":null,\"max_registrations_per_user\":null,\"enable_waitlist\":null,\"enable_autoconfirm\":null,\"unique_email_check\":null,\"notify_admin\":null,\"notify_organisator\":null,\"registration_fields\":null,\"registration\":null,\"enable_payment\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"rowDescription\":null}',NULL,'Expired Event (location: 1, fe_user: user1) [DE]','','','',1602316800,1602324000,0,1,0,'',0,0,NULL,0,1,0,0,0,'0',0,0,'0',1,'',1,0,0,'',0,0,0,1,0,0,0,0,0,'expired-event-location-1-fe-user-user1-de',0,0,NULL,NULL,NULL,NULL,0),(24,4,'',1609137784,1608471405,0,0,0,0,'',0,0,0,0,23,192,1,23,_binary '{\"title\":\"Expired Event (location: 1, fe_user: user1) [DE]\",\"top_event\":0,\"slug\":\"expired-event-location-1-fe-user-user1-de\",\"startdate\":1602316800,\"enddate\":1602324000,\"teaser\":\"\",\"description\":\"\",\"price\":0,\"currency\":\"\",\"price_options\":0,\"link\":\"\",\"program\":\"\",\"location\":1,\"room\":\"\",\"organisator\":0,\"speaker\":0,\"related\":0,\"image\":\"0\",\"files\":0,\"additional_image\":\"0\",\"category\":0,\"enable_registration\":1,\"registration_startdate\":0,\"registration_deadline\":0,\"enable_cancel\":0,\"max_participants\":0,\"max_registrations_per_user\":1,\"enable_waitlist\":0,\"enable_autoconfirm\":0,\"unique_email_check\":0,\"notify_admin\":1,\"notify_organisator\":0,\"registration_fields\":0,\"registration\":1,\"enable_payment\":0,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":0,\"endtime\":0,\"fe_group\":\"\",\"rowDescription\":\"\",\"l10n_parent\":0,\"enable_waitlist_moveup\":0,\"cancel_deadline\":0,\"restrict_payment_methods\":0,\"selected_payment_methods\":null}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"fe_group\":\"parent\",\"link\":\"parent\",\"price\":\"parent\",\"currency\":\"parent\",\"enable_payment\":\"parent\",\"restrict_payment_methods\":\"parent\",\"selected_payment_methods\":\"parent\",\"location\":\"parent\",\"room\":\"parent\",\"organisator\":\"parent\",\"speaker\":\"parent\",\"image\":\"parent\",\"files\":\"parent\",\"related\":\"parent\",\"additional_image\":\"parent\",\"registration_fields\":\"parent\",\"price_options\":\"parent\",\"category\":\"parent\"}','Expired Event (location: 1, fe_user: user1) [EN]','','','',1602316800,1602324000,0,1,0,'',0,0,'',0,0,0,0,0,'0',0,0,'0',1,'',1,0,0,'',0,0,0,1,0,0,0,0,0,'expired-event-location-1-fe-user-user1-en',0,0,NULL,NULL,NULL,NULL,0);
/*!40000 ALTER TABLE `tx_sfeventmgt_domain_model_event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tx_sfeventmgt_domain_model_event_related_mm`
--

LOCK TABLES `tx_sfeventmgt_domain_model_event_related_mm` WRITE;
/*!40000 ALTER TABLE `tx_sfeventmgt_domain_model_event_related_mm` DISABLE KEYS */;
/*!40000 ALTER TABLE `tx_sfeventmgt_domain_model_event_related_mm` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tx_sfeventmgt_domain_model_location`
--

LOCK TABLES `tx_sfeventmgt_domain_model_location` WRITE;
/*!40000 ALTER TABLE `tx_sfeventmgt_domain_model_location` DISABLE KEYS */;
INSERT INTO `tx_sfeventmgt_domain_model_location` VALUES (1,4,1609137585,1586410453,0,0,0,0,0,0,0,0,0,256,0,0,_binary '{\"title\":null,\"slug\":null,\"address\":null,\"zip\":null,\"city\":null,\"country\":null,\"description\":null,\"link\":null,\"latitude\":null,\"longitude\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null}',NULL,'Event Location 1 [DE]','','','','','','',0.000000,0.000000,'event-location-1-de'),(2,4,1609137597,1586410461,0,0,0,0,0,0,0,0,0,512,0,0,_binary '{\"title\":null,\"slug\":null,\"address\":null,\"zip\":null,\"city\":null,\"country\":null,\"description\":null,\"link\":null,\"latitude\":null,\"longitude\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null}',NULL,'Event Location 2 [DE]','','','','','','',0.000000,0.000000,'event-location-2-de'),(3,4,1609137611,1609137604,0,0,0,0,0,0,0,0,1,384,1,1,_binary '{\"title\":\"Event Location 1 [DE]\",\"slug\":\"event-location-1-de\",\"address\":null,\"zip\":\"\",\"city\":null,\"country\":null,\"description\":null,\"link\":null,\"latitude\":\"0.000000\",\"longitude\":\"0.000000\",\"sys_language_uid\":0,\"hidden\":0,\"starttime\":null,\"endtime\":null,\"l10n_parent\":0}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"address\":\"parent\",\"city\":\"parent\",\"country\":\"parent\",\"description\":\"parent\",\"link\":\"parent\"}','Event Location 1 [EN]','','','','','','',0.000000,0.000000,'event-location-1-en'),(4,4,1609137625,1609137615,0,0,0,0,0,0,0,0,2,448,1,2,_binary '{\"title\":\"Event Location 2 [DE]\",\"slug\":\"event-location-2-de\",\"address\":null,\"zip\":\"\",\"city\":null,\"country\":null,\"description\":null,\"link\":null,\"latitude\":\"0.000000\",\"longitude\":\"0.000000\",\"sys_language_uid\":0,\"hidden\":0,\"starttime\":null,\"endtime\":null,\"l10n_parent\":0}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"address\":\"parent\",\"city\":\"parent\",\"country\":\"parent\",\"description\":\"parent\",\"link\":\"parent\"}','Event Location 2 [EN]','','','','','','',0.000000,0.000000,'event-location-2-en');
/*!40000 ALTER TABLE `tx_sfeventmgt_domain_model_location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tx_sfeventmgt_domain_model_organisator`
--

LOCK TABLES `tx_sfeventmgt_domain_model_organisator` WRITE;
/*!40000 ALTER TABLE `tx_sfeventmgt_domain_model_organisator` DISABLE KEYS */;
INSERT INTO `tx_sfeventmgt_domain_model_organisator` VALUES (1,4,1586410486,1586410486,0,0,0,0,0,0,0,0,0,256,0,0,_binary '{\"name\":null,\"slug\":null,\"email\":null,\"email_signature\":null,\"phone\":null,\"image\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null}',NULL,'Organisator 1','organisator1@sfeventmgt.local','','','','organisator-1',''),(2,4,1586410509,1586410509,0,0,0,0,0,0,0,0,0,128,0,0,_binary '{\"name\":null,\"slug\":null,\"email\":null,\"email_signature\":null,\"phone\":null,\"image\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null}',NULL,'Organisator 2','organisator2@sfeventmgt.local','','','','organisator-2','');
/*!40000 ALTER TABLE `tx_sfeventmgt_domain_model_organisator` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tx_sfeventmgt_domain_model_priceoption`
--

LOCK TABLES `tx_sfeventmgt_domain_model_priceoption` WRITE;
/*!40000 ALTER TABLE `tx_sfeventmgt_domain_model_priceoption` DISABLE KEYS */;
/*!40000 ALTER TABLE `tx_sfeventmgt_domain_model_priceoption` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tx_sfeventmgt_domain_model_registration`
--

LOCK TABLES `tx_sfeventmgt_domain_model_registration` WRITE;
/*!40000 ALTER TABLE `tx_sfeventmgt_domain_model_registration` DISABLE KEYS */;
INSERT INTO `tx_sfeventmgt_domain_model_registration` VALUES (1,4,1757077807,1586419253,0,0,0,1,6,0,'','Firstname','Lastname','','','','','','','','user1@sfeventmgt.local',0,'',0,1,'',0,1586418889,1,'',0,0,'','',0,0,NULL),(2,4,1757077769,1586419432,0,0,1,1,8,0,'','Firstname','Lastname','','','','','','','','user1@sfeventmgt.local',0,'',0,1,'',0,1586418889,1,'',0,0,'','',0,0,NULL),(203,4,1608446456,1586582305,0,0,0,1537,19,0,'','user1','user1','','','','','','','','user1@sfeventmgt.local',0,'',0,1,'',0,1586418889,1,'',1,0,'','',0,0,NULL),(220,4,1609137784,1609137340,0,0,0,1,23,0,'','user1','user1','','','','','','','','user1@sfeventmgt.local',0,'',0,1,'',0,1608964001,1,'',1,0,'','',0,0,0);
/*!40000 ALTER TABLE `tx_sfeventmgt_domain_model_registration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tx_sfeventmgt_domain_model_registration_field`
--

LOCK TABLES `tx_sfeventmgt_domain_model_registration_field` WRITE;
/*!40000 ALTER TABLE `tx_sfeventmgt_domain_model_registration_field` DISABLE KEYS */;
INSERT INTO `tx_sfeventmgt_domain_model_registration_field` VALUES (1,4,1757077840,1586417697,0,0,0,0,'',0,0,0,0,0,1,0,0,_binary '{\"hidden\":\"\"}',NULL,'Input field [DE]','input',0,'','',NULL,NULL,0,3,''),(2,4,1757077840,1586417697,0,0,0,0,'',0,0,0,0,0,2,0,0,_binary '{\"hidden\":\"\"}',NULL,'Input field (req) [DE]','input',1,'','',NULL,NULL,0,3,''),(3,4,1757077840,1586429584,0,0,0,0,'',0,0,0,0,1,1,1,1,_binary '{\"title\":\"Input field [DE]\",\"type\":\"input\",\"required\":\"0\",\"placeholder\":\"\",\"default_value\":\"\",\"sys_language_uid\":0,\"hidden\":0,\"starttime\":\"0\",\"endtime\":\"0\",\"fe_group\":\"\",\"l10n_parent\":0,\"settings\":\"\"}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"fe_group\":\"parent\",\"settings\":\"parent\",\"placeholder\":\"parent\",\"default_value\":\"parent\"}','Input field [EN]','input',0,'','',NULL,NULL,0,11,''),(4,4,1757077840,1586429584,0,0,0,0,'',0,0,0,0,2,2,1,2,_binary '{\"title\":\"Input field (req) [DE]\",\"type\":\"input\",\"required\":\"1\",\"placeholder\":\"\",\"default_value\":\"\",\"sys_language_uid\":0,\"hidden\":0,\"starttime\":\"0\",\"endtime\":\"0\",\"fe_group\":\"\",\"l10n_parent\":0,\"settings\":\"\"}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"fe_group\":\"parent\",\"settings\":\"parent\",\"placeholder\":\"parent\",\"default_value\":\"parent\"}','Input field (req) [EN]','input',1,'','',NULL,NULL,0,11,'');
/*!40000 ALTER TABLE `tx_sfeventmgt_domain_model_registration_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tx_sfeventmgt_domain_model_registration_fieldvalue`
--

LOCK TABLES `tx_sfeventmgt_domain_model_registration_fieldvalue` WRITE;
/*!40000 ALTER TABLE `tx_sfeventmgt_domain_model_registration_fieldvalue` DISABLE KEYS */;
/*!40000 ALTER TABLE `tx_sfeventmgt_domain_model_registration_fieldvalue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tx_sfeventmgt_domain_model_speaker`
--

LOCK TABLES `tx_sfeventmgt_domain_model_speaker` WRITE;
/*!40000 ALTER TABLE `tx_sfeventmgt_domain_model_speaker` DISABLE KEYS */;
INSERT INTO `tx_sfeventmgt_domain_model_speaker` VALUES (1,4,1586410523,1586410523,0,0,0,0,0,0,0,0,0,256,0,0,_binary '{\"name\":null,\"job_title\":null,\"slug\":null,\"description\":null,\"image\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null}',NULL,'Speaker 1','','',0,'speaker-1'),(2,4,1586410530,1586410530,0,0,0,0,0,0,0,0,0,128,0,0,_binary '{\"name\":null,\"job_title\":null,\"slug\":null,\"description\":null,\"image\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null}',NULL,'Speaker 2','','',0,'speaker-2');
/*!40000 ALTER TABLE `tx_sfeventmgt_domain_model_speaker` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tx_sfeventmgt_event_speaker_mm`
--

LOCK TABLES `tx_sfeventmgt_event_speaker_mm` WRITE;
/*!40000 ALTER TABLE `tx_sfeventmgt_event_speaker_mm` DISABLE KEYS */;
/*!40000 ALTER TABLE `tx_sfeventmgt_event_speaker_mm` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-09-05 15:45:55
