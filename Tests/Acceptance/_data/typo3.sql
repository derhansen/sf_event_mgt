-- MySQL dump 10.13  Distrib 5.7.38, for osx10.17 (x86_64)
--
-- Host: localhost    Database: typo3_sfeventmgt_acceptance_v12
-- ------------------------------------------------------
-- Server version	5.7.38

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `backend_layout` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `crdate` int(10) unsigned NOT NULL DEFAULT '0',
  `cruser_id` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hidden` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sorting` int(11) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_state` smallint(6) NOT NULL DEFAULT '0',
  `t3ver_stage` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `config` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` text COLLATE utf8mb4_unicode_ci,
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `be_dashboards` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `crdate` int(10) unsigned NOT NULL DEFAULT '0',
  `cruser_id` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hidden` smallint(5) unsigned NOT NULL DEFAULT '0',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `identifier` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `title` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `widgets` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `be_groups`
--

DROP TABLE IF EXISTS `be_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `be_groups` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `crdate` int(10) unsigned NOT NULL DEFAULT '0',
  `cruser_id` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hidden` smallint(5) unsigned NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `non_exclude_fields` text COLLATE utf8mb4_unicode_ci,
  `explicit_allowdeny` text COLLATE utf8mb4_unicode_ci,
  `allowed_languages` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `custom_options` text COLLATE utf8mb4_unicode_ci,
  `db_mountpoints` text COLLATE utf8mb4_unicode_ci,
  `pagetypes_select` text COLLATE utf8mb4_unicode_ci,
  `tables_select` text COLLATE utf8mb4_unicode_ci,
  `tables_modify` text COLLATE utf8mb4_unicode_ci,
  `groupMods` text COLLATE utf8mb4_unicode_ci,
  `availableWidgets` text COLLATE utf8mb4_unicode_ci,
  `file_mountpoints` text COLLATE utf8mb4_unicode_ci,
  `file_permissions` text COLLATE utf8mb4_unicode_ci,
  `TSconfig` text COLLATE utf8mb4_unicode_ci,
  `subgroup` text COLLATE utf8mb4_unicode_ci,
  `workspace_perms` smallint(6) NOT NULL DEFAULT '1',
  `category_perms` longtext COLLATE utf8mb4_unicode_ci,
  `mfa_providers` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `be_sessions`
--

DROP TABLE IF EXISTS `be_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `be_sessions` (
  `ses_id` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ses_iplock` varchar(39) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ses_userid` int(10) unsigned NOT NULL DEFAULT '0',
  `ses_tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `ses_data` longblob,
  PRIMARY KEY (`ses_id`),
  KEY `ses_tstamp` (`ses_tstamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `be_users`
--

DROP TABLE IF EXISTS `be_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `be_users` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `crdate` int(10) unsigned NOT NULL DEFAULT '0',
  `cruser_id` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` smallint(5) unsigned NOT NULL DEFAULT '0',
  `disable` smallint(5) unsigned NOT NULL DEFAULT '0',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `avatar` int(10) unsigned NOT NULL DEFAULT '0',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `admin` smallint(5) unsigned NOT NULL DEFAULT '0',
  `usergroup` text COLLATE utf8mb4_unicode_ci,
  `lang` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `db_mountpoints` text COLLATE utf8mb4_unicode_ci,
  `options` smallint(5) unsigned NOT NULL DEFAULT '0',
  `realName` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `userMods` text COLLATE utf8mb4_unicode_ci,
  `allowed_languages` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `uc` mediumblob,
  `file_mountpoints` text COLLATE utf8mb4_unicode_ci,
  `file_permissions` text COLLATE utf8mb4_unicode_ci,
  `workspace_perms` smallint(6) NOT NULL DEFAULT '1',
  `TSconfig` text COLLATE utf8mb4_unicode_ci,
  `lastlogin` int(11) NOT NULL DEFAULT '0',
  `workspace_id` int(11) NOT NULL DEFAULT '0',
  `category_perms` longtext COLLATE utf8mb4_unicode_ci,
  `password_reset_token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `mfa` mediumblob,
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_adminpanel_requestcache` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `expires` int(10) unsigned NOT NULL DEFAULT '0',
  `content` longblob,
  PRIMARY KEY (`id`),
  KEY `cache_id` (`identifier`(180),`expires`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache_adminpanel_requestcache_tags`
--

DROP TABLE IF EXISTS `cache_adminpanel_requestcache_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_hash` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `expires` int(10) unsigned NOT NULL DEFAULT '0',
  `content` longblob,
  PRIMARY KEY (`id`),
  KEY `cache_id` (`identifier`(180),`expires`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache_hash_tags`
--

DROP TABLE IF EXISTS `cache_hash_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_imagesizes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `expires` int(10) unsigned NOT NULL DEFAULT '0',
  `content` longblob,
  PRIMARY KEY (`id`),
  KEY `cache_id` (`identifier`(180),`expires`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache_imagesizes_tags`
--

DROP TABLE IF EXISTS `cache_imagesizes_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `expires` int(10) unsigned NOT NULL DEFAULT '0',
  `content` longblob,
  PRIMARY KEY (`id`),
  KEY `cache_id` (`identifier`(180),`expires`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache_pages_tags`
--

DROP TABLE IF EXISTS `cache_pages_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_pages_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `tag` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `cache_id` (`identifier`(191)),
  KEY `cache_tag` (`tag`(191))
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache_rootline`
--

DROP TABLE IF EXISTS `cache_rootline`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_rootline` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `expires` int(10) unsigned NOT NULL DEFAULT '0',
  `content` longblob,
  PRIMARY KEY (`id`),
  KEY `cache_id` (`identifier`(180),`expires`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache_rootline_tags`
--

DROP TABLE IF EXISTS `cache_rootline_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_rootline_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `tag` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `cache_id` (`identifier`(191)),
  KEY `cache_tag` (`tag`(191))
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache_treelist`
--

DROP TABLE IF EXISTS `cache_treelist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_treelist` (
  `md5hash` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `pid` int(11) NOT NULL DEFAULT '0',
  `treelist` mediumtext COLLATE utf8mb4_unicode_ci,
  `tstamp` int(11) NOT NULL DEFAULT '0',
  `expires` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`md5hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fe_groups`
--

DROP TABLE IF EXISTS `fe_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fe_groups` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `crdate` int(10) unsigned NOT NULL DEFAULT '0',
  `cruser_id` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hidden` smallint(5) unsigned NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `tx_extbase_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `subgroup` tinytext COLLATE utf8mb4_unicode_ci,
  `TSconfig` text COLLATE utf8mb4_unicode_ci,
  `felogin_redirectPid` tinytext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fe_sessions`
--

DROP TABLE IF EXISTS `fe_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fe_sessions` (
  `ses_id` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ses_iplock` varchar(39) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ses_userid` int(10) unsigned NOT NULL DEFAULT '0',
  `ses_tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `ses_data` mediumblob,
  `ses_permanent` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ses_id`),
  KEY `ses_tstamp` (`ses_tstamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fe_users`
--

DROP TABLE IF EXISTS `fe_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fe_users` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `crdate` int(10) unsigned NOT NULL DEFAULT '0',
  `cruser_id` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` smallint(5) unsigned NOT NULL DEFAULT '0',
  `disable` smallint(5) unsigned NOT NULL DEFAULT '0',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `tx_extbase_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `usergroup` text COLLATE utf8mb4_unicode_ci,
  `name` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `first_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `middle_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `last_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `telephone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `fax` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `uc` blob,
  `title` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `zip` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `city` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `country` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `www` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `company` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `image` tinytext COLLATE utf8mb4_unicode_ci,
  `TSconfig` text COLLATE utf8mb4_unicode_ci,
  `lastlogin` int(11) NOT NULL DEFAULT '0',
  `is_online` int(10) unsigned NOT NULL DEFAULT '0',
  `felogin_redirectPid` tinytext COLLATE utf8mb4_unicode_ci,
  `felogin_forgotHash` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `mfa` mediumblob,
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `crdate` int(10) unsigned NOT NULL DEFAULT '0',
  `cruser_id` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hidden` smallint(5) unsigned NOT NULL DEFAULT '0',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `fe_group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `sorting` int(11) NOT NULL DEFAULT '0',
  `rowDescription` text COLLATE utf8mb4_unicode_ci,
  `editlock` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sys_language_uid` int(11) NOT NULL DEFAULT '0',
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `l10n_source` int(10) unsigned NOT NULL DEFAULT '0',
  `l10n_state` text COLLATE utf8mb4_unicode_ci,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT '0',
  `l10n_diffsource` mediumblob,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_state` smallint(6) NOT NULL DEFAULT '0',
  `t3ver_stage` int(11) NOT NULL DEFAULT '0',
  `perms_userid` int(10) unsigned NOT NULL DEFAULT '0',
  `perms_groupid` int(10) unsigned NOT NULL DEFAULT '0',
  `perms_user` smallint(5) unsigned NOT NULL DEFAULT '0',
  `perms_group` smallint(5) unsigned NOT NULL DEFAULT '0',
  `perms_everybody` smallint(5) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `slug` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `doktype` int(10) unsigned NOT NULL DEFAULT '0',
  `TSconfig` text COLLATE utf8mb4_unicode_ci,
  `is_siteroot` smallint(6) NOT NULL DEFAULT '0',
  `php_tree_stop` smallint(6) NOT NULL DEFAULT '0',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `shortcut` int(10) unsigned NOT NULL DEFAULT '0',
  `shortcut_mode` int(10) unsigned NOT NULL DEFAULT '0',
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `layout` int(10) unsigned NOT NULL DEFAULT '0',
  `target` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `media` int(10) unsigned NOT NULL DEFAULT '0',
  `lastUpdated` int(11) NOT NULL DEFAULT '0',
  `keywords` text COLLATE utf8mb4_unicode_ci,
  `cache_timeout` int(10) unsigned NOT NULL DEFAULT '0',
  `cache_tags` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `newUntil` int(11) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `no_search` smallint(5) unsigned NOT NULL DEFAULT '0',
  `SYS_LASTCHANGED` int(10) unsigned NOT NULL DEFAULT '0',
  `abstract` text COLLATE utf8mb4_unicode_ci,
  `module` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `extendToSubpages` smallint(5) unsigned NOT NULL DEFAULT '0',
  `author` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `author_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `nav_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `nav_hide` smallint(6) NOT NULL DEFAULT '0',
  `content_from_pid` int(10) unsigned NOT NULL DEFAULT '0',
  `mount_pid` int(10) unsigned NOT NULL DEFAULT '0',
  `mount_pid_ol` smallint(6) NOT NULL DEFAULT '0',
  `l18n_cfg` smallint(6) NOT NULL DEFAULT '0',
  `fe_login_mode` smallint(6) NOT NULL DEFAULT '0',
  `backend_layout` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `backend_layout_next_level` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `tsconfig_includes` text COLLATE utf8mb4_unicode_ci,
  `tx_impexp_origuid` int(11) NOT NULL DEFAULT '0',
  `seo_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `no_index` smallint(6) NOT NULL DEFAULT '0',
  `no_follow` smallint(6) NOT NULL DEFAULT '0',
  `og_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `og_description` text COLLATE utf8mb4_unicode_ci,
  `og_image` int(10) unsigned NOT NULL DEFAULT '0',
  `twitter_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `twitter_description` text COLLATE utf8mb4_unicode_ci,
  `twitter_image` int(10) unsigned NOT NULL DEFAULT '0',
  `twitter_card` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `canonical_link` varchar(2048) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sitemap_priority` decimal(2,1) NOT NULL DEFAULT '0.5',
  `sitemap_changefreq` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `categories` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  KEY `determineSiteRoot` (`is_siteroot`),
  KEY `language_identifier` (`l10n_parent`,`sys_language_uid`),
  KEY `slug` (`slug`(127)),
  KEY `parent` (`pid`,`deleted`,`hidden`),
  KEY `translation_source` (`l10n_source`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_be_shortcuts`
--

DROP TABLE IF EXISTS `sys_be_shortcuts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_be_shortcuts` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sorting` int(11) NOT NULL DEFAULT '0',
  `sc_group` smallint(6) NOT NULL DEFAULT '0',
  `route` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `arguments` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`uid`),
  KEY `event` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_category`
--

DROP TABLE IF EXISTS `sys_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_category` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `crdate` int(10) unsigned NOT NULL DEFAULT '0',
  `cruser_id` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hidden` smallint(5) unsigned NOT NULL DEFAULT '0',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `sorting` int(11) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `sys_language_uid` int(11) NOT NULL DEFAULT '0',
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `l10n_state` text COLLATE utf8mb4_unicode_ci,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT '0',
  `l10n_diffsource` mediumblob,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_state` smallint(6) NOT NULL DEFAULT '0',
  `t3ver_stage` int(11) NOT NULL DEFAULT '0',
  `title` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent` int(10) unsigned NOT NULL DEFAULT '0',
  `items` int(11) NOT NULL DEFAULT '0',
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_category_record_mm` (
  `uid_local` int(10) unsigned NOT NULL DEFAULT '0',
  `uid_foreign` int(10) unsigned NOT NULL DEFAULT '0',
  `tablenames` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `fieldname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sorting` int(10) unsigned NOT NULL DEFAULT '0',
  `sorting_foreign` int(10) unsigned NOT NULL DEFAULT '0',
  KEY `uid_local` (`uid_local`),
  KEY `uid_foreign` (`uid_foreign`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_file`
--

DROP TABLE IF EXISTS `sys_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_file` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `last_indexed` int(11) NOT NULL DEFAULT '0',
  `missing` smallint(6) NOT NULL DEFAULT '0',
  `storage` int(11) NOT NULL DEFAULT '0',
  `type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `metadata` int(11) NOT NULL DEFAULT '0',
  `identifier` text COLLATE utf8mb4_unicode_ci,
  `identifier_hash` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `folder_hash` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `extension` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `name` tinytext COLLATE utf8mb4_unicode_ci,
  `sha1` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `size` bigint(20) unsigned NOT NULL DEFAULT '0',
  `creation_date` int(11) NOT NULL DEFAULT '0',
  `modification_date` int(11) NOT NULL DEFAULT '0',
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_file_collection` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `crdate` int(10) unsigned NOT NULL DEFAULT '0',
  `cruser_id` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hidden` smallint(5) unsigned NOT NULL DEFAULT '0',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `sys_language_uid` int(11) NOT NULL DEFAULT '0',
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `l10n_state` text COLLATE utf8mb4_unicode_ci,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT '0',
  `l10n_diffsource` mediumblob,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_state` smallint(6) NOT NULL DEFAULT '0',
  `t3ver_stage` int(11) NOT NULL DEFAULT '0',
  `title` tinytext COLLATE utf8mb4_unicode_ci,
  `type` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'static',
  `files` int(11) NOT NULL DEFAULT '0',
  `storage` int(11) NOT NULL DEFAULT '0',
  `folder` text COLLATE utf8mb4_unicode_ci,
  `recursive` smallint(6) NOT NULL DEFAULT '0',
  `category` int(10) unsigned NOT NULL DEFAULT '0',
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_file_metadata` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `crdate` int(10) unsigned NOT NULL DEFAULT '0',
  `cruser_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sys_language_uid` int(11) NOT NULL DEFAULT '0',
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `l10n_state` text COLLATE utf8mb4_unicode_ci,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT '0',
  `l10n_diffsource` mediumblob,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_state` smallint(6) NOT NULL DEFAULT '0',
  `t3ver_stage` int(11) NOT NULL DEFAULT '0',
  `file` int(11) NOT NULL DEFAULT '0',
  `title` tinytext COLLATE utf8mb4_unicode_ci,
  `width` int(11) NOT NULL DEFAULT '0',
  `height` int(11) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `alternative` text COLLATE utf8mb4_unicode_ci,
  `categories` int(10) unsigned NOT NULL DEFAULT '0',
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_file_processedfile` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `tstamp` int(11) NOT NULL DEFAULT '0',
  `crdate` int(11) NOT NULL DEFAULT '0',
  `storage` int(11) NOT NULL DEFAULT '0',
  `original` int(11) NOT NULL DEFAULT '0',
  `identifier` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `name` tinytext COLLATE utf8mb4_unicode_ci,
  `configuration` blob,
  `configurationsha1` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `originalfilesha1` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `task_type` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `checksum` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `width` int(11) DEFAULT '0',
  `height` int(11) DEFAULT '0',
  `processing_url` text COLLATE utf8mb4_unicode_ci,
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_file_reference` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `crdate` int(10) unsigned NOT NULL DEFAULT '0',
  `cruser_id` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hidden` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sys_language_uid` int(11) NOT NULL DEFAULT '0',
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `l10n_state` text COLLATE utf8mb4_unicode_ci,
  `l10n_diffsource` mediumblob,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_state` smallint(6) NOT NULL DEFAULT '0',
  `t3ver_stage` int(11) NOT NULL DEFAULT '0',
  `uid_local` int(11) NOT NULL DEFAULT '0',
  `uid_foreign` int(11) NOT NULL DEFAULT '0',
  `tablenames` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `fieldname` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sorting_foreign` int(11) NOT NULL DEFAULT '0',
  `table_local` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `title` tinytext COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `alternative` text COLLATE utf8mb4_unicode_ci,
  `link` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `crop` varchar(4000) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `autoplay` smallint(6) NOT NULL DEFAULT '0',
  `show_in_views` smallint(6) NOT NULL DEFAULT '0',
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_file_storage` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `crdate` int(10) unsigned NOT NULL DEFAULT '0',
  `cruser_id` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` smallint(5) unsigned NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `driver` tinytext COLLATE utf8mb4_unicode_ci,
  `configuration` text COLLATE utf8mb4_unicode_ci,
  `is_default` smallint(6) NOT NULL DEFAULT '0',
  `is_browsable` smallint(6) NOT NULL DEFAULT '0',
  `is_public` smallint(6) NOT NULL DEFAULT '0',
  `is_writable` smallint(6) NOT NULL DEFAULT '0',
  `is_online` smallint(6) NOT NULL DEFAULT '1',
  `auto_extract_metadata` smallint(6) NOT NULL DEFAULT '1',
  `processingfolder` tinytext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_filemounts`
--

DROP TABLE IF EXISTS `sys_filemounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_filemounts` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hidden` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sorting` int(11) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `base` int(10) unsigned NOT NULL DEFAULT '0',
  `read_only` smallint(5) unsigned NOT NULL DEFAULT '0',
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_history` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `actiontype` smallint(6) NOT NULL DEFAULT '0',
  `usertype` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BE',
  `userid` int(10) unsigned DEFAULT NULL,
  `originaluserid` int(10) unsigned DEFAULT NULL,
  `recuid` int(11) NOT NULL DEFAULT '0',
  `tablename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `history_data` mediumtext COLLATE utf8mb4_unicode_ci,
  `workspace` int(11) DEFAULT '0',
  `correlation_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`),
  KEY `recordident_1` (`tablename`(100),`recuid`),
  KEY `recordident_2` (`tablename`(100),`tstamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_lockedrecords`
--

DROP TABLE IF EXISTS `sys_lockedrecords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_lockedrecords` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `record_table` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `record_uid` int(11) NOT NULL DEFAULT '0',
  `record_pid` int(11) NOT NULL DEFAULT '0',
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `feuserid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  KEY `event` (`userid`,`tstamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_log`
--

DROP TABLE IF EXISTS `sys_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_log` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `action` smallint(5) unsigned NOT NULL DEFAULT '0',
  `recuid` int(10) unsigned NOT NULL DEFAULT '0',
  `tablename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `recpid` int(11) NOT NULL DEFAULT '0',
  `error` smallint(5) unsigned NOT NULL DEFAULT '0',
  `details` text COLLATE utf8mb4_unicode_ci,
  `type` smallint(5) unsigned NOT NULL DEFAULT '0',
  `details_nr` smallint(6) NOT NULL DEFAULT '0',
  `IP` varchar(39) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `log_data` text COLLATE utf8mb4_unicode_ci,
  `event_pid` int(11) NOT NULL DEFAULT '-1',
  `workspace` int(11) NOT NULL DEFAULT '0',
  `NEWid` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `request_id` varchar(13) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `time_micro` double NOT NULL DEFAULT '0',
  `component` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `level` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info',
  `message` text COLLATE utf8mb4_unicode_ci,
  `data` text COLLATE utf8mb4_unicode_ci,
  `channel` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  PRIMARY KEY (`uid`),
  KEY `event` (`userid`,`event_pid`),
  KEY `recuidIdx` (`recuid`),
  KEY `user_auth` (`type`,`action`,`tstamp`),
  KEY `request` (`request_id`),
  KEY `combined_1` (`tstamp`,`type`,`userid`),
  KEY `errorcount` (`tstamp`,`error`),
  KEY `parent` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_news`
--

DROP TABLE IF EXISTS `sys_news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_news` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `crdate` int(10) unsigned NOT NULL DEFAULT '0',
  `cruser_id` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hidden` smallint(5) unsigned NOT NULL DEFAULT '0',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `content` mediumtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_note`
--

DROP TABLE IF EXISTS `sys_note`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_note` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `crdate` int(10) unsigned NOT NULL DEFAULT '0',
  `cruser` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sorting` int(11) NOT NULL DEFAULT '0',
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `message` text COLLATE utf8mb4_unicode_ci,
  `personal` smallint(5) unsigned NOT NULL DEFAULT '0',
  `category` smallint(5) unsigned NOT NULL DEFAULT '0',
  `position` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  KEY `parent` (`pid`,`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_redirect`
--

DROP TABLE IF EXISTS `sys_redirect`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_redirect` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `updatedon` int(10) unsigned NOT NULL DEFAULT '0',
  `createdon` int(10) unsigned NOT NULL DEFAULT '0',
  `createdby` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` smallint(5) unsigned NOT NULL DEFAULT '0',
  `disabled` smallint(5) unsigned NOT NULL DEFAULT '0',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `source_host` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `source_path` varchar(2048) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `is_regexp` smallint(5) unsigned NOT NULL DEFAULT '0',
  `force_https` smallint(5) unsigned NOT NULL DEFAULT '0',
  `respect_query_parameters` smallint(5) unsigned NOT NULL DEFAULT '0',
  `keep_query_parameters` smallint(5) unsigned NOT NULL DEFAULT '0',
  `target` varchar(2048) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `target_statuscode` int(11) NOT NULL DEFAULT '307',
  `hitcount` int(11) NOT NULL DEFAULT '0',
  `lasthiton` int(11) NOT NULL DEFAULT '0',
  `disable_hitcount` smallint(5) unsigned NOT NULL DEFAULT '0',
  `protected` smallint(5) unsigned NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `creation_type` int(10) unsigned NOT NULL DEFAULT '0',
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_refindex` (
  `hash` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `tablename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `recuid` int(11) NOT NULL DEFAULT '0',
  `field` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `flexpointer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `softref_key` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `softref_id` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sorting` int(11) NOT NULL DEFAULT '0',
  `workspace` int(11) NOT NULL DEFAULT '0',
  `ref_table` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ref_uid` int(11) NOT NULL DEFAULT '0',
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_registry` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entry_namespace` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `entry_key` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `entry_value` mediumblob,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `entry_identifier` (`entry_namespace`,`entry_key`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_template`
--

DROP TABLE IF EXISTS `sys_template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_template` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `crdate` int(10) unsigned NOT NULL DEFAULT '0',
  `cruser_id` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hidden` smallint(5) unsigned NOT NULL DEFAULT '0',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `sorting` int(11) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_state` smallint(6) NOT NULL DEFAULT '0',
  `t3ver_stage` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `root` smallint(5) unsigned NOT NULL DEFAULT '0',
  `clear` smallint(5) unsigned NOT NULL DEFAULT '0',
  `include_static_file` text COLLATE utf8mb4_unicode_ci,
  `constants` text COLLATE utf8mb4_unicode_ci,
  `config` text COLLATE utf8mb4_unicode_ci,
  `basedOn` tinytext COLLATE utf8mb4_unicode_ci,
  `includeStaticAfterBasedOn` smallint(5) unsigned NOT NULL DEFAULT '0',
  `static_file_mode` smallint(5) unsigned NOT NULL DEFAULT '0',
  `tx_impexp_origuid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  KEY `roottemplate` (`deleted`,`hidden`,`root`),
  KEY `parent` (`pid`,`deleted`,`hidden`),
  KEY `t3ver_oid` (`t3ver_oid`,`t3ver_wsid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tt_content`
--

DROP TABLE IF EXISTS `tt_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tt_content` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rowDescription` text COLLATE utf8mb4_unicode_ci,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `crdate` int(10) unsigned NOT NULL DEFAULT '0',
  `cruser_id` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hidden` smallint(5) unsigned NOT NULL DEFAULT '0',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `fe_group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `sorting` int(11) NOT NULL DEFAULT '0',
  `editlock` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sys_language_uid` int(11) NOT NULL DEFAULT '0',
  `l18n_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `l10n_source` int(10) unsigned NOT NULL DEFAULT '0',
  `l10n_state` text COLLATE utf8mb4_unicode_ci,
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT '0',
  `l18n_diffsource` mediumblob,
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_state` smallint(6) NOT NULL DEFAULT '0',
  `t3ver_stage` int(11) NOT NULL DEFAULT '0',
  `CType` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `header` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `header_position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `bodytext` mediumtext COLLATE utf8mb4_unicode_ci,
  `bullets_type` smallint(5) unsigned NOT NULL DEFAULT '0',
  `uploads_description` smallint(5) unsigned NOT NULL DEFAULT '0',
  `uploads_type` smallint(5) unsigned NOT NULL DEFAULT '0',
  `assets` int(10) unsigned NOT NULL DEFAULT '0',
  `image` int(10) unsigned NOT NULL DEFAULT '0',
  `imagewidth` int(10) unsigned NOT NULL DEFAULT '0',
  `imageorient` smallint(5) unsigned NOT NULL DEFAULT '0',
  `imagecols` smallint(5) unsigned NOT NULL DEFAULT '0',
  `imageborder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `media` int(10) unsigned NOT NULL DEFAULT '0',
  `layout` int(10) unsigned NOT NULL DEFAULT '0',
  `frame_class` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `cols` int(10) unsigned NOT NULL DEFAULT '0',
  `space_before_class` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `space_after_class` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `records` text COLLATE utf8mb4_unicode_ci,
  `pages` text COLLATE utf8mb4_unicode_ci,
  `colPos` int(10) unsigned NOT NULL DEFAULT '0',
  `subheader` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `header_link` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `image_zoom` smallint(5) unsigned NOT NULL DEFAULT '0',
  `header_layout` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `list_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sectionIndex` smallint(5) unsigned NOT NULL DEFAULT '0',
  `linkToTop` smallint(5) unsigned NOT NULL DEFAULT '0',
  `file_collections` text COLLATE utf8mb4_unicode_ci,
  `filelink_size` smallint(5) unsigned NOT NULL DEFAULT '0',
  `filelink_sorting` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `filelink_sorting_direction` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `target` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `date` int(11) NOT NULL DEFAULT '0',
  `recursive` smallint(5) unsigned NOT NULL DEFAULT '0',
  `imageheight` int(10) unsigned NOT NULL DEFAULT '0',
  `pi_flexform` mediumtext COLLATE utf8mb4_unicode_ci,
  `accessibility_title` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `accessibility_bypass` smallint(5) unsigned NOT NULL DEFAULT '0',
  `accessibility_bypass_text` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `selected_categories` longtext COLLATE utf8mb4_unicode_ci,
  `category_field` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `table_class` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `table_caption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `table_delimiter` smallint(5) unsigned NOT NULL DEFAULT '0',
  `table_enclosure` smallint(5) unsigned NOT NULL DEFAULT '0',
  `table_header_position` smallint(5) unsigned NOT NULL DEFAULT '0',
  `table_tfoot` smallint(5) unsigned NOT NULL DEFAULT '0',
  `tx_impexp_origuid` int(11) NOT NULL DEFAULT '0',
  `categories` int(10) unsigned NOT NULL DEFAULT '0',
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tx_extensionmanager_domain_model_extension` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `extension_key` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `repository` int(11) NOT NULL DEFAULT '1',
  `version` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `alldownloadcounter` int(10) unsigned NOT NULL DEFAULT '0',
  `downloadcounter` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `state` int(11) NOT NULL DEFAULT '0',
  `review_state` int(11) NOT NULL DEFAULT '0',
  `category` int(11) NOT NULL DEFAULT '0',
  `last_updated` int(11) NOT NULL DEFAULT '0',
  `serialized_dependencies` mediumtext COLLATE utf8mb4_unicode_ci,
  `author_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `author_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ownerusername` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `md5hash` varchar(35) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `update_comment` mediumtext COLLATE utf8mb4_unicode_ci,
  `authorcompany` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `integer_version` int(11) NOT NULL DEFAULT '0',
  `current_version` int(11) NOT NULL DEFAULT '0',
  `lastreviewedversion` int(11) NOT NULL DEFAULT '0',
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tx_impexp_presets` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_uid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `public` smallint(6) NOT NULL DEFAULT '0',
  `item_uid` int(11) NOT NULL DEFAULT '0',
  `preset_data` blob,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `crdate` int(10) unsigned NOT NULL DEFAULT '0',
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tx_sfeventmgt_domain_model_customnotificationlog` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `crdate` int(10) unsigned NOT NULL DEFAULT '0',
  `cruser_id` int(10) unsigned NOT NULL DEFAULT '0',
  `event` int(10) unsigned NOT NULL DEFAULT '0',
  `details` text COLLATE utf8mb4_unicode_ci,
  `emails_sent` int(11) NOT NULL DEFAULT '0',
  `message` text COLLATE utf8mb4_unicode_ci,
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tx_sfeventmgt_domain_model_event` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0',
  `rowDescription` text COLLATE utf8mb4_unicode_ci,
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `crdate` int(10) unsigned NOT NULL DEFAULT '0',
  `cruser_id` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hidden` smallint(5) unsigned NOT NULL DEFAULT '0',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `fe_group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_state` smallint(6) NOT NULL DEFAULT '0',
  `t3ver_stage` int(11) NOT NULL DEFAULT '0',
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT '0',
  `sorting` int(11) NOT NULL DEFAULT '0',
  `sys_language_uid` int(11) NOT NULL DEFAULT '0',
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `l10n_diffsource` mediumblob,
  `l10n_state` text COLLATE utf8mb4_unicode_ci,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `teaser` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `program` text COLLATE utf8mb4_unicode_ci,
  `startdate` int(11) NOT NULL DEFAULT '0',
  `enddate` int(11) NOT NULL DEFAULT '0',
  `max_participants` int(11) NOT NULL DEFAULT '0',
  `max_registrations_per_user` int(11) NOT NULL DEFAULT '1',
  `price` double NOT NULL DEFAULT '0',
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `enable_payment` smallint(5) unsigned NOT NULL DEFAULT '0',
  `restrict_payment_methods` smallint(5) unsigned NOT NULL DEFAULT '0',
  `selected_payment_methods` text COLLATE utf8mb4_unicode_ci,
  `category` int(10) unsigned NOT NULL DEFAULT '0',
  `registration` int(10) unsigned NOT NULL DEFAULT '0',
  `registration_waitlist` int(10) unsigned NOT NULL DEFAULT '0',
  `registration_fields` int(10) unsigned NOT NULL DEFAULT '0',
  `price_options` int(10) unsigned NOT NULL DEFAULT '0',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `files` int(11) NOT NULL DEFAULT '0',
  `related` int(11) NOT NULL DEFAULT '0',
  `additional_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `location` int(10) unsigned NOT NULL DEFAULT '0',
  `room` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `enable_registration` smallint(5) unsigned NOT NULL DEFAULT '0',
  `enable_waitlist` smallint(5) unsigned NOT NULL DEFAULT '0',
  `registration_deadline` int(11) NOT NULL DEFAULT '0',
  `link` tinytext COLLATE utf8mb4_unicode_ci,
  `top_event` smallint(5) unsigned NOT NULL DEFAULT '0',
  `organisator` int(10) unsigned NOT NULL DEFAULT '0',
  `speaker` int(10) unsigned NOT NULL DEFAULT '0',
  `notify_admin` smallint(5) unsigned NOT NULL DEFAULT '1',
  `notify_organisator` smallint(5) unsigned NOT NULL DEFAULT '0',
  `enable_cancel` smallint(5) unsigned NOT NULL DEFAULT '0',
  `cancel_deadline` int(11) NOT NULL DEFAULT '0',
  `enable_autoconfirm` smallint(5) unsigned NOT NULL DEFAULT '0',
  `unique_email_check` smallint(5) unsigned NOT NULL DEFAULT '0',
  `slug` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enable_waitlist_moveup` smallint(5) unsigned NOT NULL DEFAULT '0',
  `registration_startdate` int(11) NOT NULL DEFAULT '0',
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tx_sfeventmgt_domain_model_event_related_mm` (
  `uid_local` int(11) NOT NULL DEFAULT '0',
  `uid_foreign` int(11) NOT NULL DEFAULT '0',
  `sorting` int(10) unsigned NOT NULL DEFAULT '0',
  `sorting_foreign` int(10) unsigned NOT NULL DEFAULT '0',
  KEY `uid_local` (`uid_local`),
  KEY `uid_foreign` (`uid_foreign`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tx_sfeventmgt_domain_model_location`
--

DROP TABLE IF EXISTS `tx_sfeventmgt_domain_model_location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tx_sfeventmgt_domain_model_location` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `crdate` int(10) unsigned NOT NULL DEFAULT '0',
  `cruser_id` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hidden` smallint(5) unsigned NOT NULL DEFAULT '0',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_state` smallint(6) NOT NULL DEFAULT '0',
  `t3ver_stage` int(11) NOT NULL DEFAULT '0',
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT '0',
  `sorting` int(11) NOT NULL DEFAULT '0',
  `sys_language_uid` int(11) NOT NULL DEFAULT '0',
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `l10n_diffsource` mediumblob,
  `l10n_state` text COLLATE utf8mb4_unicode_ci,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `zip` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` text COLLATE utf8mb4_unicode_ci,
  `link` tinytext COLLATE utf8mb4_unicode_ci,
  `longitude` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `latitude` decimal(9,6) NOT NULL DEFAULT '0.000000',
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tx_sfeventmgt_domain_model_organisator` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `crdate` int(10) unsigned NOT NULL DEFAULT '0',
  `cruser_id` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hidden` smallint(5) unsigned NOT NULL DEFAULT '0',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_state` smallint(6) NOT NULL DEFAULT '0',
  `t3ver_stage` int(11) NOT NULL DEFAULT '0',
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT '0',
  `sorting` int(11) NOT NULL DEFAULT '0',
  `sys_language_uid` int(11) NOT NULL DEFAULT '0',
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `l10n_diffsource` mediumblob,
  `l10n_state` text COLLATE utf8mb4_unicode_ci,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `email_signature` text COLLATE utf8mb4_unicode_ci,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `slug` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tx_sfeventmgt_domain_model_priceoption` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `crdate` int(10) unsigned NOT NULL DEFAULT '0',
  `cruser_id` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hidden` smallint(5) unsigned NOT NULL DEFAULT '0',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `fe_group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_state` smallint(6) NOT NULL DEFAULT '0',
  `t3ver_stage` int(11) NOT NULL DEFAULT '0',
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT '0',
  `sys_language_uid` int(11) NOT NULL DEFAULT '0',
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `l10n_diffsource` mediumblob,
  `l10n_state` text COLLATE utf8mb4_unicode_ci,
  `price` double NOT NULL DEFAULT '0',
  `valid_until` int(11) NOT NULL DEFAULT '0',
  `event` int(10) unsigned NOT NULL DEFAULT '0',
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tx_sfeventmgt_domain_model_registration` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `crdate` int(10) unsigned NOT NULL DEFAULT '0',
  `cruser_id` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hidden` smallint(5) unsigned NOT NULL DEFAULT '0',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT '0',
  `sorting` int(11) NOT NULL DEFAULT '0',
  `event` int(10) unsigned NOT NULL DEFAULT '0',
  `main_registration` int(10) unsigned NOT NULL DEFAULT '0',
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
  `ignore_notifications` smallint(5) unsigned NOT NULL DEFAULT '0',
  `gender` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `accepttc` smallint(5) unsigned NOT NULL DEFAULT '0',
  `confirmed` smallint(5) unsigned NOT NULL DEFAULT '0',
  `notes` mediumtext COLLATE utf8mb4_unicode_ci,
  `date_of_birth` int(11) DEFAULT NULL,
  `confirmation_until` int(10) unsigned NOT NULL DEFAULT '0',
  `amount_of_registrations` int(11) NOT NULL DEFAULT '1',
  `recaptcha` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `fe_user` int(11) NOT NULL DEFAULT '0',
  `paid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `paymentmethod` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `payment_reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `waitlist` smallint(5) unsigned NOT NULL DEFAULT '0',
  `field_values` int(10) unsigned NOT NULL DEFAULT '0',
  `registration_date` int(11) DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `event` (`event`,`waitlist`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB AUTO_INCREMENT=383 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tx_sfeventmgt_domain_model_registration_field`
--

DROP TABLE IF EXISTS `tx_sfeventmgt_domain_model_registration_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tx_sfeventmgt_domain_model_registration_field` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `crdate` int(10) unsigned NOT NULL DEFAULT '0',
  `cruser_id` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hidden` smallint(5) unsigned NOT NULL DEFAULT '0',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `fe_group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_state` smallint(6) NOT NULL DEFAULT '0',
  `t3ver_stage` int(11) NOT NULL DEFAULT '0',
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT '0',
  `sorting` int(11) NOT NULL DEFAULT '0',
  `sys_language_uid` int(11) NOT NULL DEFAULT '0',
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `l10n_diffsource` mediumblob,
  `l10n_state` text COLLATE utf8mb4_unicode_ci,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `required` smallint(5) unsigned NOT NULL DEFAULT '0',
  `placeholder` text COLLATE utf8mb4_unicode_ci,
  `default_value` text COLLATE utf8mb4_unicode_ci,
  `settings` text COLLATE utf8mb4_unicode_ci,
  `text` text COLLATE utf8mb4_unicode_ci,
  `datepickermode` smallint(6) NOT NULL DEFAULT '0',
  `event` int(10) unsigned NOT NULL DEFAULT '0',
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tx_sfeventmgt_domain_model_registration_fieldvalue` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `crdate` int(10) unsigned NOT NULL DEFAULT '0',
  `cruser_id` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hidden` smallint(5) unsigned NOT NULL DEFAULT '0',
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT '0',
  `value` text COLLATE utf8mb4_unicode_ci,
  `value_type` int(10) unsigned NOT NULL DEFAULT '0',
  `field` int(10) unsigned NOT NULL DEFAULT '0',
  `registration` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  KEY `registration` (`registration`),
  KEY `parent` (`pid`,`deleted`,`hidden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tx_sfeventmgt_domain_model_speaker`
--

DROP TABLE IF EXISTS `tx_sfeventmgt_domain_model_speaker`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tx_sfeventmgt_domain_model_speaker` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0',
  `tstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `crdate` int(10) unsigned NOT NULL DEFAULT '0',
  `cruser_id` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hidden` smallint(5) unsigned NOT NULL DEFAULT '0',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_oid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_wsid` int(10) unsigned NOT NULL DEFAULT '0',
  `t3ver_state` smallint(6) NOT NULL DEFAULT '0',
  `t3ver_stage` int(11) NOT NULL DEFAULT '0',
  `t3_origuid` int(10) unsigned NOT NULL DEFAULT '0',
  `sorting` int(11) NOT NULL DEFAULT '0',
  `sys_language_uid` int(11) NOT NULL DEFAULT '0',
  `l10n_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `l10n_diffsource` mediumblob,
  `l10n_state` text COLLATE utf8mb4_unicode_ci,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `job_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` int(10) unsigned NOT NULL DEFAULT '0',
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tx_sfeventmgt_event_speaker_mm` (
  `uid_local` int(11) NOT NULL DEFAULT '0',
  `uid_foreign` int(11) NOT NULL DEFAULT '0',
  `sorting` int(10) unsigned NOT NULL DEFAULT '0',
  `sorting_foreign` int(10) unsigned NOT NULL DEFAULT '0',
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

-- Dump completed on 2022-12-16 17:49:57
-- MySQL dump 10.13  Distrib 5.7.38, for osx10.17 (x86_64)
--
-- Host: localhost    Database: typo3_sfeventmgt_acceptance_v11
-- ------------------------------------------------------
-- Server version	5.7.38

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
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
/*!40000 ALTER TABLE `be_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `be_users`
--

LOCK TABLES `be_users` WRITE;
/*!40000 ALTER TABLE `be_users` DISABLE KEYS */;
INSERT INTO `be_users` VALUES (1,0,1586409640,1586409640,0,0,0,0,0,NULL,'admin',0,'$2y$12$pmFwmlrcCQ2FfrNSohm2pO3zt7oKESw/7KDPWIIx/Ye5BcRgizx0G',1,'','defaul','',NULL,0,'',NULL,'',_binary 'a:12:{s:14:\"interfaceSetup\";s:7:\"backend\";s:10:\"moduleData\";a:5:{s:28:\"dashboard/current_dashboard/\";s:40:\"37046ac03232c39ecfee7f5ac1f6e320c45ce1a2\";s:10:\"web_layout\";a:3:{s:8:\"function\";s:1:\"1\";s:8:\"language\";s:1:\"0\";s:19:\"constant_editor_cat\";N;}s:6:\"web_ts\";a:3:{s:8:\"function\";s:85:\"TYPO3\\CMS\\Tstemplate\\Controller\\TypoScriptTemplateInformationModuleFunctionController\";s:8:\"language\";N;s:19:\"constant_editor_cat\";s:7:\"content\";}s:10:\"FormEngine\";a:2:{i:0;a:2:{s:32:\"d05de5db7a95716a106a7b65a59bfc32\";a:4:{i:0;s:31:\"sf_event_mgt - Acceptance Tests\";i:1;a:5:{s:4:\"edit\";a:1:{s:12:\"sys_template\";a:1:{i:1;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";s:9:\"constants\";s:6:\"noView\";N;}i:2;s:57:\"&edit%5Bsys_template%5D%5B1%5D=edit&columnsOnly=constants\";i:3;a:5:{s:5:\"table\";s:12:\"sys_template\";s:3:\"uid\";i:1;s:3:\"pid\";i:1;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}}s:32:\"ad6c6673a0ce0bd828f9e86c3bc41bf4\";a:4:{i:0;s:31:\"sf_event_mgt - Acceptance Tests\";i:1;a:5:{s:4:\"edit\";a:1:{s:12:\"sys_template\";a:1:{i:1;s:4:\"edit\";}}s:7:\"defVals\";N;s:12:\"overrideVals\";N;s:11:\"columnsOnly\";s:6:\"config\";s:6:\"noView\";N;}i:2;s:54:\"&edit%5Bsys_template%5D%5B1%5D=edit&columnsOnly=config\";i:3;a:5:{s:5:\"table\";s:12:\"sys_template\";s:3:\"uid\";i:1;s:3:\"pid\";i:1;s:3:\"cmd\";s:4:\"edit\";s:12:\"deleteAccess\";b:1;}}}i:1;s:32:\"ad6c6673a0ce0bd828f9e86c3bc41bf4\";}s:57:\"TYPO3\\CMS\\Backend\\Utility\\BackendUtility::getUpdateSignal\";a:0:{}}s:14:\"emailMeAtLogin\";i:0;s:8:\"titleLen\";i:50;s:8:\"edit_RTE\";s:1:\"1\";s:20:\"edit_docModuleUpload\";s:1:\"1\";s:25:\"resizeTextareas_MaxHeight\";i:500;s:4:\"lang\";s:6:\"defaul\";s:19:\"firstLoginTimeStamp\";i:1630086397;s:15:\"moduleSessionID\";a:5:{s:28:\"dashboard/current_dashboard/\";s:40:\"5b8d0afc419a78a3670256e3b3a9f0172ffaed1e\";s:10:\"web_layout\";s:40:\"5b8d0afc419a78a3670256e3b3a9f0172ffaed1e\";s:6:\"web_ts\";s:40:\"63ac208340a3412274b393936b06207c7080d08a\";s:10:\"FormEngine\";s:40:\"63ac208340a3412274b393936b06207c7080d08a\";s:57:\"TYPO3\\CMS\\Backend\\Utility\\BackendUtility::getUpdateSignal\";s:40:\"63ac208340a3412274b393936b06207c7080d08a\";}s:17:\"BackendComponents\";a:1:{s:6:\"States\";a:1:{s:8:\"Pagetree\";a:1:{s:9:\"stateHash\";a:3:{s:3:\"0_1\";s:1:\"1\";s:3:\"0_2\";s:1:\"1\";s:4:\"0_14\";s:1:\"1\";}}}}s:10:\"modulemenu\";s:2:\"{}\";}',NULL,NULL,1,NULL,1667048254,0,NULL,'',NULL);
/*!40000 ALTER TABLE `be_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `cache_adminpanel_requestcache`
--

LOCK TABLES `cache_adminpanel_requestcache` WRITE;
/*!40000 ALTER TABLE `cache_adminpanel_requestcache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_adminpanel_requestcache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `cache_adminpanel_requestcache_tags`
--

LOCK TABLES `cache_adminpanel_requestcache_tags` WRITE;
/*!40000 ALTER TABLE `cache_adminpanel_requestcache_tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_adminpanel_requestcache_tags` ENABLE KEYS */;
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
-- Dumping data for table `cache_treelist`
--

LOCK TABLES `cache_treelist` WRITE;
/*!40000 ALTER TABLE `cache_treelist` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_treelist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `fe_groups`
--

LOCK TABLES `fe_groups` WRITE;
/*!40000 ALTER TABLE `fe_groups` DISABLE KEYS */;
INSERT INTO `fe_groups` VALUES (1,13,1586581341,1586581341,1,0,0,'','0','group1','','','');
/*!40000 ALTER TABLE `fe_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `fe_sessions`
--

LOCK TABLES `fe_sessions` WRITE;
/*!40000 ALTER TABLE `fe_sessions` DISABLE KEYS */;
INSERT INTO `fe_sessions` VALUES ('01d9facbcfe1b3c140858757f9b48b0769b24e66f37a9625bc74d54183df2a57','[DISABLED]',0,1633766610,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('05222169d03fec3d9e0c083197bb0502cbdf7c435e2c6af14fd43952d83308a3','[DISABLED]',0,1633766636,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('05edc8f6d3d3906fc93422f9410a44c6bc00dafd91327af19ed30241e5ac1cd3','[DISABLED]',0,1633766725,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('0601b3f80586b0297b74c0f4779a7f26c9b328be437ae2646c635f7a36b6d86b','[DISABLED]',1,1633784953,'',0),('087bf89f6b929f37b90bffcd111b853c1fd0070eea38cdfcca30d94848fb1bc3','[DISABLED]',0,1638471534,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('09854307ca18f5e3f37c54da641864a5653a11be8decabca8546e41126f43866','[DISABLED]',0,1633666893,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('0acecd49710c8f1018cbc212f47b617e5aeadbd442c49c254d75d574a5db116d','[DISABLED]',0,1631169363,_binary 'a:1:{s:43:\"extbase.flashmessages.tx_sfeventmgt_pievent\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('0b15c3937b88477724e11d45411dce7360d05acd2fe79cabb418a033a54793bd','[DISABLED]',0,1633666872,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('0bd1f4659bc07bb42005cd69a1aa1b7e9566805f264d3acf9642f0a22f4b9289','[DISABLED]',1,1631169327,'',0),('0ca060883e669a49c9d932b414e1edc1c85af00a984cc0ab500e89be512cb729','[DISABLED]',1,1638471438,'',0),('0d3df060b6e3972132ca05e7ae435e83a0bbc396dcd2505ab19abb5254ba9eea','[DISABLED]',1,1638471526,'',0),('0dd404b9edf4861e477126b74851fb7700c1448b44b63a36b4d572aebec81fdd','[DISABLED]',0,1633666885,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('10812532a14d2603a4c70b86d8117b839d6729a8417a73fc0be2362aa5b7ef70','[DISABLED]',0,1633785197,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('10a336f09539774b7999de0016724f29147841691eec42ec1a1534a9d56a5cb5','[DISABLED]',0,1630593590,_binary 'a:1:{s:43:\"extbase.flashmessages.tx_sfeventmgt_pievent\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('118d816d59a1f8a88b855a1d27d7ab0e1f61cd6567ca007db4941857d8a5b17c','[DISABLED]',0,1633784933,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('12495517e80e608e999d70d2af4bde52d79dfe0ca346287e17097ad50fbe61f6','[DISABLED]',0,1633766637,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('12deea93fc91fcf1d65cdffce23ec5e53ccee0b750d652aa00c7821022137287','[DISABLED]',0,1633666882,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('14cd65917bac44fe224029b54a781039beee65e764bbee8f8018cb00ed87bf00','[DISABLED]',0,1630593580,_binary 'a:1:{s:43:\"extbase.flashmessages.tx_sfeventmgt_pievent\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('16c34d139a24429afb864d837a2005893748043b71d9d38507436aff506a3bf5','[DISABLED]',0,1633784935,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('18a071f442c531b0c9782096344c77eefa504ae5382d39eaab925ce17aa8ff26','[DISABLED]',1,1633766615,'',0),('1cb0c22bfeb54b40dd47a81e837e5c9477d0f71fa8e6d263ea21984c374e10e8','[DISABLED]',0,1640177068,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('2265c1facc67d09097cd4b6007cf310e71fb08db5ed2b2a29d8dd8d50178f6f4','[DISABLED]',0,1633666874,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('246b20fc7003e9a13dc2a72efaf7ffc93316fd96a09126d8821667b19d99147f','[DISABLED]',0,1630593605,_binary 'a:1:{s:43:\"extbase.flashmessages.tx_sfeventmgt_pievent\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('2629930c2594b55f04cfd91093468130485cfee0a9fd073ffc898a0dc934d2c6','[DISABLED]',1,1630593587,'',0),('278c0a47ad904d69cf722218de1dd40a1a087dbc10f01c2f701cacad9eaa8f52','[DISABLED]',1,1633666891,'',0),('28ebb5f0ada442e46dd83057b533043637fe1ee421382d0948cfb8c9eb4c9fd4','[DISABLED]',0,1633785194,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('296821318b1854850f1a19eed08b2f7653c059823b2a97904038a92815775516','[DISABLED]',0,1630661295,_binary 'a:1:{s:43:\"extbase.flashmessages.tx_sfeventmgt_pievent\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('2a7eb248a3908d7bcd0a492a5ab49ffca36a202c0dead19032488eff045686fd','[DISABLED]',0,1633784943,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('2ac49443abbb2ae7397af46fdfb1887acfcf18fd6aadb8ad69af2b08797ec6ed','[DISABLED]',0,1640177088,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('2eb3bb7ac18dcb00d2d477c109edd12f2da62e2766d98b9dd30c58e9c044dc7a','[DISABLED]',0,1630568377,_binary 'a:1:{s:43:\"extbase.flashmessages.tx_sfeventmgt_pievent\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('31089df7c1d7cdff8d9d6017c35bcf5eb831e064160292a50db3594a4d054fff','[DISABLED]',0,1633766596,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('323d19d99a50cc09517e7d49ed8441657149d798c4b86cb14353effe576f48fa','[DISABLED]',0,1633784947,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('3556176f33b32f570f4770d8b0fb0e5a7f3be1da393c096d64dea2dec7f0612c','[DISABLED]',0,1630568360,_binary 'a:1:{s:43:\"extbase.flashmessages.tx_sfeventmgt_pievent\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('35e88ddcc1348a9d406424c53b3de60656050ee09a0484dde34eb5232c9a37b8','[DISABLED]',0,1631169349,_binary 'a:1:{s:43:\"extbase.flashmessages.tx_sfeventmgt_pievent\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('39fab7cb336810c2cda7dea32f13debc67170d1c127f49238fc6802128ab43c6','[DISABLED]',0,1631169347,_binary 'a:1:{s:43:\"extbase.flashmessages.tx_sfeventmgt_pievent\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('43fe33cd2881a3b872d76dec955f1147905261cec4e4402408982f730b985157','[DISABLED]',1,1638471419,'',0),('4460e8a4277a94ce6ee92762ec79eac419d66f46b4684bd1d7e8ca9248f128dc','[DISABLED]',1,1630568367,'',0),('4588ddd5e1ccdf0491d104c81b8783d765338033a197ce5fcd8aaf595af1dcd5','[DISABLED]',0,1633785184,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('46329982c03609654d15104de05df36dcec3c7ac90c4510f0a1ce8e07feb0f95','[DISABLED]',0,1640177082,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('4ac547fc999a003da0f69f6d4ad57df04db1b3f98cc4f7392ce76a76ef66853d','[DISABLED]',1,1633766713,'',0),('4f5f93c1449888f4b5a5a6c6f7c4d324aaa58e9b0aa9ac7dd4d7b64612234ff0','[DISABLED]',1,1633766701,'',0),('52c908d9f23b2b706f8be7cf1caac1ccf8394a43c69fd40950679fd9119aca19','[DISABLED]',0,1633766694,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('53f04f00edfcc76d2db6d488ac4572f2d2ff098be412db5e205b52985d2078be','[DISABLED]',1,1633626849,'',0),('55d5f4d8527c61d0c127a1c181314e0dd22a28c2032922bc4968f1e09f0661d5','[DISABLED]',0,1633766708,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('578c89b3d6167762a5313ea8e4914ad37f767fa74e858e9d11dd897e971de3a6','[DISABLED]',0,1633666887,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('5816d884abcd2fa141294a0b7a174c64bbe690263dd4383e53f6af6c64734272','[DISABLED]',1,1633766642,'',0),('58866b029e0190d61d053076f6f70c7a333ce8f12bae6976714e78c038200c0e','[DISABLED]',0,1633784956,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('5d2b714d0608937ba40ddf306730bd32bc2273d735487e0e28d3ad5db90e2393','[DISABLED]',0,1633766723,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('5f633c451b7e699ce84bd121b37b6d2344a0a28f64ec617701565884f62d2911','[DISABLED]',0,1633784948,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('5fcbcd384456af11e9636bcff9a32dfc8ab497e1135e7f2b94d9897c98d9909c','[DISABLED]',0,1640177070,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('60ed912ccaf27d4bb607e534d06a3e74a088dcc893440e3699d313f6736fb0f6','[DISABLED]',0,1633766617,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('61c4501f58b98ee086eea11934918576df1b873f82859fbe1f9a6ee66c0899a7','[DISABLED]',1,1633766742,'',0),('6492efdcc055c5f96ce1707539653c5d261cfe9907ca9ec7566e0dbfa7403672','[DISABLED]',0,1633785186,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('64fd69dd167917ee6c7c43fd445771430230f4978d72dfa801722d4eac4290e4','[DISABLED]',0,1633766732,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('667d68860b99ab7bde5e9ee38e07842285c9af2681f4d9b29a53f715220eec8b','[DISABLED]',1,1633784940,'',0),('67e351b76840d29f823778fb4294fef239621fdc9d99ebdadb359883347d38ea','[DISABLED]',0,1633766737,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('6b83ed6cf9518dfdcab7a9be4a2e7d871faeacc8cb21af2901710cc115cc9157','[DISABLED]',0,1633766744,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('6e7e101bb3f0281d3cd8fac3d2f5ec3e5c252f779e59e98a2f20310f18743449','[DISABLED]',1,1633626861,'',0),('6fc2b5fe8844febaf60718789dfc1e11ef067ec432ecef9b1749230b3f9c23c4','[DISABLED]',0,1633766695,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('73d6495e73049046753f4a0eca2a750b59f402a402b1209aab12499e5e8b7538','[DISABLED]',0,1633766706,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('757c765b88c1fc2e16919e23e52c139ad8a226ab5b188576f5280e59f4cdffd4','[DISABLED]',1,1631169355,'',0),('7a5029851af3da71b239e837ae73543895064912f08283e60a62b4860ca2d45c','[DISABLED]',0,1631169357,_binary 'a:1:{s:43:\"extbase.flashmessages.tx_sfeventmgt_pievent\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('7a951763ce3b58b7e4293010c603dd87eff9f4cc57ccdd65e77345c3015a47d5','[DISABLED]',1,1633666879,'',0),('7ef36dc3ed56564a8225dfca63630a22878a3a9f1ddacb0cfe558ac309f0ec75','[DISABLED]',0,1631169370,_binary 'a:1:{s:43:\"extbase.flashmessages.tx_sfeventmgt_pievent\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('7f0b17a5d4c962efdd8864fdfe8f174f86245d4663571d2c2634af7df69776a2','[DISABLED]',1,1630087049,'',0),('7f3a5f51b5adcb72be14d31d276845a67c1ad52a7873c80dfeaabf9b9cfa7d70','[DISABLED]',0,1633766605,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('861784ad5fa6ac18bbb4b737f37c3b21a8a8da48de964563ef83caa8613416dd','[DISABLED]',0,1638471423,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('861e31e673396b171951f986eee027b8e28f7f29e7566a911360ad90dd23071d','[DISABLED]',0,1633626856,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('8899eb75b84f1a72d13cd5662129b45bb91e13a2d083e20de654ce85cfcc2f2c','[DISABLED]',1,1630087063,'',0),('88e7e692aa6994b42100a49429404bbed0f3c49fa16a2df464bbfe29fbccb405','[DISABLED]',1,1630661301,'',0),('898b372d55f1875b808b30bba2068a4b05bd1453fd68272f82fe04927b4793dd','[DISABLED]',0,1633766648,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('8a22cfac6094dc51c17efb4e3235b2acf348a3d6f2619f0e5dee9fba150bb21e','[DISABLED]',0,1638471410,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('8a28854bf95383a938f46b0f39b6bb32060196010e8f648d218987f0e82e4678','[DISABLED]',0,1630661303,_binary 'a:1:{s:43:\"extbase.flashmessages.tx_sfeventmgt_pievent\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('8dfa156a13a18c44c9750c5e85aa753f77866b59df31ed7608d7cb06f9973c39','[DISABLED]',0,1630593578,_binary 'a:1:{s:43:\"extbase.flashmessages.tx_sfeventmgt_pievent\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('8e16f09cbd46e2c4ea7ca755a094ff69609f9733be0db3975381ebec8b06559e','[DISABLED]',1,1631169367,'',0),('8ecacc0e348a2dc28fd1da047f01daf535483f9dcc5fbe187792930192c6e4bd','[DISABLED]',0,1633626863,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('8ee4e7c4f63dfbd447ced691c6ab9a7fdd8217b8e7e77b505c29b5e5be2e24f2','[DISABLED]',0,1630661308,_binary 'a:1:{s:43:\"extbase.flashmessages.tx_sfeventmgt_pievent\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('90913f7df0ce10a23762097c51186a1ef5e4bdb74b0aa75a99ba408a25c6d923','[DISABLED]',0,1633766609,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('9161cebd25e7c15f1257291ae041b62b69c5e05e76a738843307e06880b8cfe0','[DISABLED]',0,1630568371,_binary 'a:1:{s:43:\"extbase.flashmessages.tx_sfeventmgt_pievent\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('91fa32eefe69e58e24cbd6012302f6b5e7416aba05d7e76767b245acc279b040','[DISABLED]',0,1633785205,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('938a9c872f5a7721142741064f4423e18aa96efddc9bdba23dcff62ee67a811f','[DISABLED]',0,1630568358,_binary 'a:1:{s:43:\"extbase.flashmessages.tx_sfeventmgt_pievent\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('959b268dc909ad6ac7592d8cb5b932177c2814995106105e2dfc6776832ccebd','[DISABLED]',0,1630593595,_binary 'a:1:{s:43:\"extbase.flashmessages.tx_sfeventmgt_pievent\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('9786251bbbde3d70ffd486f3e3dae014a0d2d30d4f75e7ff7b55a444e671706d','[DISABLED]',0,1633766649,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('9a65d75b25a3e3a72636f7793f38449f54a186996d233f97a84b9ae4109e836d','[DISABLED]',0,1633626855,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('9adb45aca614a10a982bbebef86d470f6afafb5437d7485ee593ebf998926e83','[DISABLED]',1,1630661313,'',0),('9c9628adf3c547fe0c1feba930b60c02d26e7a09ea5efa65a1f84d6f9df3bdbe','[DISABLED]',0,1630661316,_binary 'a:1:{s:43:\"extbase.flashmessages.tx_sfeventmgt_pievent\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('9cbb16423024b418f1a477fce5b7dbb35475a92693828f5f3fc68cbab5fb6b1e','[DISABLED]',0,1638471429,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('a13e0b914e3bff299f0b30728c6ec56b3de3aa6954de8dac8fb1e791bea28743','[DISABLED]',0,1633766703,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('a3b644be2b53ee0ce036b3861d594203bda3be727677e0b1fde10d9edd462898','[DISABLED]',1,1640177095,'',0),('a3c1614f1e27deb69f096230d9bc71b86a9075bbf2e7b4ff6d1f723cbe812856','[DISABLED]',0,1633626842,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('a3ff8b1c8ce33178702b1db0f1a62090b881e53206a251ac2a584aa1b9b7eac5','[DISABLED]',0,1633766598,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('a55858f1b765b8f9fb45e36558a5f85a21f0154b509f69c019eff32dbb431017','[DISABLED]',0,1633766715,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('ac25ba0572a21f4d245ca52ab7be33089791213c41da1123d49f37ca3efd6849','[DISABLED]',0,1630661307,_binary 'a:1:{s:43:\"extbase.flashmessages.tx_sfeventmgt_pievent\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('acbce0476f7d99e9d4bb4462c0955e6a5e44ba5afc89c70c1d978119e4065349','[DISABLED]',0,1631169361,_binary 'a:1:{s:43:\"extbase.flashmessages.tx_sfeventmgt_pievent\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('ad3778cfb78cf32f52226d96c25a2599f455209a08a813a78871f9436ed89bd2','[DISABLED]',1,1633785191,'',0),('b0660a990f8d4decb743c359af956d8e8615c38c788c0788522d9b162ccba1d9','[DISABLED]',0,1638471529,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('b182a1f38e89219b0e97f0378e75f31d1713f62c82b2b0eb217ce0262ec9bd49','[DISABLED]',0,1633626851,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('b1934ae8085733c4894ac19ea7485e72d8d2e1329d1f6fa325e13926eb87aec7','[DISABLED]',1,1633785203,'',0),('b76bd765e9d60f4b9c0ebed4d9fcd3e800e38b59c5bca597e0ea9c7846f14503','[DISABLED]',0,1638471431,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('bd0e426562169244541a0e6ebf0cf4c8e7583b415abf15823069c2ecfbdfbc9b','[DISABLED]',0,1638471542,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('bd335e82214e5f1554365a56bce6a797acc889c988ef6d0cca40a0071e4e5d91','[DISABLED]',1,1638471540,'',0),('c0c522d4937393bda2fcc56195fe34afc0962b4b9b9ee64371a05f1cf8fe6144','[DISABLED]',0,1633766645,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('c41a04b0a0a45320f1a7d3cb95c8dedc0b595b790072a5423f73c23c79ace81b','[DISABLED]',0,1630568375,_binary 'a:1:{s:43:\"extbase.flashmessages.tx_sfeventmgt_pievent\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('c68edb7006998b26664c93b492e67b2dad4a8f0272129dd2863f7f6168936708','[DISABLED]',0,1638471520,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('c69d34c328b34a2f19ce3508508bd2b1503d6e7a7b5f7f6a34ddca6b87ec5942','[DISABLED]',0,1633626843,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('cb751737e7a13dd493131cc646849fe4fb533da3bd6262dcd413b936a82a89a3','[DISABLED]',1,1630568383,'',0),('ce63c43dbf68f85e82b537a4130a290b615e40d9022bdec949f829724d653ad7','[DISABLED]',0,1638471519,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('d463838d8fdad520b5eabda17d12fae9b9a4d4b42565d86db50f504ed6aa6cee','[DISABLED]',1,1630593602,'',0),('d81306b81f020b95b828c965f0486dc6043639ab59004814d7b2276467d60bfe','[DISABLED]',1,1633766730,'',0),('d9639c34286324e993c469ba775ab84b245339525ea882ee32e159fa734b47ed','[DISABLED]',0,1630593596,_binary 'a:1:{s:43:\"extbase.flashmessages.tx_sfeventmgt_pievent\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('dd33d06b0590206d1e965b43e9999f0ed33faeb75596ade5e4e2d7bdb0282603','[DISABLED]',0,1638471441,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('dea71f4bef84529bdcf22628deb8902b3e905fb732628e8617a3336f628e5b54','[DISABLED]',0,1640177098,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('df660b8aaffa94cc2f7173a23e4b984b0f706329a18d93e05e73b585f5ac8341','[DISABLED]',1,1633766603,'',0),('e162f3e3fc1a3a3f021b99a1d007712da0dd93e5d61bb5df35300bbac7b96388','[DISABLED]',0,1630568386,_binary 'a:1:{s:43:\"extbase.flashmessages.tx_sfeventmgt_pievent\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('e31da3fb466fe686c3abe2252dd07fbe86e05efcbc9409f49acef16be7009c69','[DISABLED]',0,1633766656,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('e5823f80abce719294bfcd81c69c18fee12489dc5802f6aa2064d0a8b413d171','[DISABLED]',1,1633766654,'',0),('e6243b618356cd1973907fb176551a57b6e0ad7375be1dfee6e0fedc27c30b51','[DISABLED]',1,1640177079,'',0),('e68ac7a696be54815783c255a86d962b5944d5fba21d04171239560d0a217f2b','[DISABLED]',0,1640177087,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('ea32d80da39f80d82c5f4db699b4045bc794cfbf0e677eacb15ef928d160efeb','[DISABLED]',0,1633785198,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:1:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('ea9716616842969e2742527a482beb9bfbf781c0b91e071fda9b4d295883c8e5','[DISABLED]',0,1638471408,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('ebb5d9713abfe3c2ebae778e1c546d95414ed5b31a0fd4eb58408e394aaedac4','[DISABLED]',0,1630661293,_binary 'a:1:{s:43:\"extbase.flashmessages.tx_sfeventmgt_pievent\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('ed4a2b12fa99599e0a61341db1338ad2785550d6c30343c6d2538f072b0d6f98','[DISABLED]',0,1633766736,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0),('f8992e86774d879f0711937665d5f9d39d5dfdabaf0af1a33bc355fd506b0c35','[DISABLED]',0,1638471533,_binary 'a:1:{s:55:\"extbase.flashmessages.tx_sfeventmgt_pieventregistration\";a:2:{i:0;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";i:1;s:175:\"{\"severity\":2,\"title\":\"\",\"message\":\"An error occurred while trying to call DERHANSEN\\\\SfEventMgt\\\\Controller\\\\EventController->saveRegistrationAction()\",\"storeInSession\":true}\";}}',0);
/*!40000 ALTER TABLE `fe_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `fe_users`
--

LOCK TABLES `fe_users` WRITE;
/*!40000 ALTER TABLE `fe_users` DISABLE KEYS */;
INSERT INTO `fe_users` VALUES (1,13,1592717114,1586581354,1,0,0,0,0,'','0','user1','$2y$12$6beZNXcJuXQud6kqT59K/OkU/6S.wLc6ZfUakAr7n62/Flfor0wcS','1','','','','','','','','',NULL,'','','','','','','0','',1640177095,1640177079,'','',NULL);
/*!40000 ALTER TABLE `fe_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,0,1586410645,1586409670,1,0,0,0,0,'',256,NULL,0,0,0,0,NULL,0,_binary '{\"doktype\":null,\"title\":null,\"slug\":null,\"nav_title\":null,\"subtitle\":null,\"seo_title\":null,\"description\":null,\"no_index\":null,\"no_follow\":null,\"canonical_link\":null,\"sitemap_changefreq\":null,\"sitemap_priority\":null,\"og_title\":null,\"og_description\":null,\"og_image\":null,\"twitter_title\":null,\"twitter_description\":null,\"twitter_image\":null,\"twitter_card\":null,\"abstract\":null,\"keywords\":null,\"author\":null,\"author_email\":null,\"lastUpdated\":null,\"layout\":null,\"newUntil\":null,\"backend_layout\":null,\"backend_layout_next_level\":null,\"content_from_pid\":null,\"target\":null,\"cache_timeout\":null,\"cache_tags\":null,\"is_siteroot\":null,\"no_search\":null,\"php_tree_stop\":null,\"module\":null,\"media\":null,\"tsconfig_includes\":null,\"TSconfig\":null,\"l18n_cfg\":null,\"hidden\":null,\"nav_hide\":null,\"starttime\":null,\"endtime\":null,\"extendToSubpages\":null,\"fe_group\":null,\"fe_login_mode\":null,\"editlock\":null,\"categories\":null,\"rowDescription\":null}',0,0,0,0,1,0,31,27,0,'Root Page','/',1,NULL,1,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1586410645,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(2,1,1586581290,1586409695,1,0,0,0,0,'',832,NULL,0,0,0,0,NULL,0,_binary '{\"title\":null}',0,0,0,0,1,0,31,27,0,'Event List (all)','/event-list-all',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1586581290,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(3,2,1586418303,1586409717,1,0,0,0,0,'',256,NULL,0,0,0,0,NULL,0,_binary '{\"slug\":null}',0,0,0,0,1,0,31,27,0,'Event Detail','/event-list-all/event-detail',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1586418303,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(4,1,1586429535,1586410297,1,0,0,0,0,'0',64,NULL,0,0,0,0,NULL,0,_binary '{\"doktype\":null,\"title\":null,\"slug\":null,\"backend_layout\":null,\"backend_layout_next_level\":null,\"module\":null,\"media\":null,\"tsconfig_includes\":null,\"TSconfig\":null,\"hidden\":null,\"editlock\":null,\"categories\":null,\"rowDescription\":null}',0,0,0,0,1,0,31,27,0,'Events [DE]','/events-de',254,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'events',0,'','','',0,0,0,0,0,0,'','',NULL,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(5,1,1586581301,1586410400,1,1,0,0,0,'0',1152,NULL,0,0,0,0,NULL,0,_binary '{\"hidden\":null}',0,0,0,0,1,0,31,27,0,'Default Tests','/default-tests',254,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(6,2,1586410987,1586410764,1,0,0,0,0,'',512,NULL,0,0,0,0,NULL,0,_binary '{\"doktype\":null,\"title\":null,\"slug\":null,\"nav_title\":null,\"subtitle\":null,\"seo_title\":null,\"description\":null,\"no_index\":null,\"no_follow\":null,\"canonical_link\":null,\"sitemap_changefreq\":null,\"sitemap_priority\":null,\"og_title\":null,\"og_description\":null,\"og_image\":null,\"twitter_title\":null,\"twitter_description\":null,\"twitter_image\":null,\"twitter_card\":null,\"abstract\":null,\"keywords\":null,\"author\":null,\"author_email\":null,\"lastUpdated\":null,\"layout\":null,\"newUntil\":null,\"backend_layout\":null,\"backend_layout_next_level\":null,\"content_from_pid\":null,\"target\":null,\"cache_timeout\":null,\"cache_tags\":null,\"is_siteroot\":null,\"no_search\":null,\"php_tree_stop\":null,\"module\":null,\"media\":null,\"tsconfig_includes\":null,\"TSconfig\":null,\"l18n_cfg\":null,\"hidden\":null,\"nav_hide\":null,\"starttime\":null,\"endtime\":null,\"extendToSubpages\":null,\"fe_group\":null,\"fe_login_mode\":null,\"editlock\":null,\"categories\":null,\"rowDescription\":null}',0,0,0,0,1,0,31,27,0,'Registration','/registration',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1586421201,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(7,1,1586581290,1586418359,1,0,0,0,0,'',832,NULL,0,1,2,2,'{\"starttime\":\"parent\",\"endtime\":\"parent\",\"nav_hide\":\"parent\",\"url\":\"parent\",\"lastUpdated\":\"parent\",\"newUntil\":\"parent\",\"no_search\":\"parent\",\"shortcut\":\"parent\",\"shortcut_mode\":\"parent\",\"content_from_pid\":\"parent\",\"author\":\"parent\",\"author_email\":\"parent\",\"media\":\"parent\",\"og_image\":\"parent\",\"twitter_image\":\"parent\"}',0,_binary '{\"doktype\":1,\"title\":\"Event List (all)\",\"slug\":\"\\/event-list-all\",\"nav_title\":\"\",\"subtitle\":\"\",\"seo_title\":\"\",\"description\":null,\"canonical_link\":\"\",\"sitemap_changefreq\":\"\",\"sitemap_priority\":\"0.5\",\"og_title\":\"\",\"og_description\":null,\"twitter_title\":\"\",\"twitter_description\":null,\"twitter_card\":\"summary\",\"abstract\":null,\"keywords\":null,\"hidden\":0,\"categories\":0,\"rowDescription\":null,\"TSconfig\":null,\"php_tree_stop\":0,\"editlock\":0,\"layout\":0,\"fe_group\":\"\",\"extendToSubpages\":0,\"target\":\"\",\"cache_timeout\":0,\"cache_tags\":\"\",\"mount_pid\":0,\"is_siteroot\":0,\"mount_pid_ol\":0,\"module\":\"\",\"fe_login_mode\":0,\"l18n_cfg\":0,\"backend_layout\":\"\",\"backend_layout_next_level\":\"\",\"tsconfig_includes\":null,\"no_index\":0,\"no_follow\":0}',0,0,0,0,1,0,31,27,0,'Event List (all)','/event-list-all',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1586581290,NULL,'',0,'','','',0,0,0,0,0,0,'','','',0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(8,2,1586418412,1586418398,1,0,0,0,0,'',256,NULL,0,1,3,3,'{\"starttime\":\"parent\",\"endtime\":\"parent\",\"nav_hide\":\"parent\",\"url\":\"parent\",\"lastUpdated\":\"parent\",\"newUntil\":\"parent\",\"no_search\":\"parent\",\"shortcut\":\"parent\",\"shortcut_mode\":\"parent\",\"content_from_pid\":\"parent\",\"author\":\"parent\",\"author_email\":\"parent\",\"media\":\"parent\",\"og_image\":\"parent\",\"twitter_image\":\"parent\"}',0,_binary '{\"doktype\":1,\"title\":\"Event Detail\",\"slug\":\"\\/event-list-all\\/event-detail\",\"nav_title\":\"\",\"subtitle\":\"\",\"seo_title\":\"\",\"description\":null,\"canonical_link\":\"\",\"sitemap_changefreq\":\"\",\"sitemap_priority\":\"0.5\",\"og_title\":\"\",\"og_description\":null,\"twitter_title\":\"\",\"twitter_description\":null,\"twitter_card\":\"summary\",\"abstract\":null,\"keywords\":null,\"hidden\":0,\"categories\":0,\"rowDescription\":null,\"TSconfig\":null,\"php_tree_stop\":0,\"editlock\":0,\"layout\":0,\"fe_group\":\"\",\"extendToSubpages\":0,\"target\":\"\",\"cache_timeout\":0,\"cache_tags\":\"\",\"mount_pid\":0,\"is_siteroot\":0,\"mount_pid_ol\":0,\"module\":\"\",\"fe_login_mode\":0,\"l18n_cfg\":0,\"backend_layout\":\"\",\"backend_layout_next_level\":\"\",\"tsconfig_includes\":null,\"no_index\":0,\"no_follow\":0}',0,0,0,0,1,0,31,27,0,'Event Detail','/event-list-all/event-detail',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1586418427,NULL,'',0,'','','',0,0,0,0,0,0,'','','',0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(9,2,1586418437,1586418431,1,0,0,0,0,'',512,NULL,0,1,6,6,'{\"starttime\":\"parent\",\"endtime\":\"parent\",\"nav_hide\":\"parent\",\"url\":\"parent\",\"lastUpdated\":\"parent\",\"newUntil\":\"parent\",\"no_search\":\"parent\",\"shortcut\":\"parent\",\"shortcut_mode\":\"parent\",\"content_from_pid\":\"parent\",\"author\":\"parent\",\"author_email\":\"parent\",\"media\":\"parent\",\"og_image\":\"parent\",\"twitter_image\":\"parent\"}',0,_binary '{\"doktype\":1,\"title\":\"Registration\",\"slug\":\"\\/registration\",\"nav_title\":\"\",\"subtitle\":\"\",\"seo_title\":\"\",\"description\":null,\"canonical_link\":\"\",\"sitemap_changefreq\":\"\",\"sitemap_priority\":\"0.5\",\"og_title\":\"\",\"og_description\":null,\"twitter_title\":\"\",\"twitter_description\":null,\"twitter_card\":\"summary\",\"abstract\":null,\"keywords\":null,\"hidden\":0,\"categories\":0,\"rowDescription\":null,\"TSconfig\":null,\"php_tree_stop\":0,\"editlock\":0,\"layout\":0,\"fe_group\":\"\",\"extendToSubpages\":0,\"target\":\"\",\"cache_timeout\":0,\"cache_tags\":\"\",\"mount_pid\":0,\"is_siteroot\":0,\"mount_pid_ol\":0,\"module\":\"\",\"fe_login_mode\":0,\"l18n_cfg\":0,\"backend_layout\":\"\",\"backend_layout_next_level\":\"\",\"tsconfig_includes\":null,\"no_index\":0,\"no_follow\":0}',0,0,0,0,1,0,31,27,0,'Registration','/event-list-all/registration',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1586421210,NULL,'',0,'','','',0,0,0,0,0,0,'','','',0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(10,1,1586429526,1586429498,1,0,0,0,0,'',64,NULL,0,1,4,4,'{\"starttime\":\"parent\",\"endtime\":\"parent\",\"nav_hide\":\"parent\",\"url\":\"parent\",\"lastUpdated\":\"parent\",\"newUntil\":\"parent\",\"no_search\":\"parent\",\"shortcut\":\"parent\",\"shortcut_mode\":\"parent\",\"content_from_pid\":\"parent\",\"author\":\"parent\",\"author_email\":\"parent\",\"media\":\"parent\",\"og_image\":\"parent\",\"twitter_image\":\"parent\"}',0,_binary '{\"doktype\":254,\"title\":\"Events\",\"slug\":\"\\/events\",\"hidden\":0,\"categories\":0,\"rowDescription\":null,\"TSconfig\":null,\"php_tree_stop\":0,\"editlock\":0,\"layout\":0,\"fe_group\":\"0\",\"extendToSubpages\":0,\"target\":\"\",\"cache_timeout\":0,\"cache_tags\":\"\",\"mount_pid\":0,\"is_siteroot\":0,\"mount_pid_ol\":0,\"module\":\"events\",\"fe_login_mode\":0,\"l18n_cfg\":0,\"backend_layout\":\"\",\"backend_layout_next_level\":\"\",\"tsconfig_includes\":null,\"no_index\":0,\"no_follow\":0,\"starttime\":0,\"endtime\":0,\"nav_hide\":0,\"url\":\"\",\"lastUpdated\":0,\"newUntil\":0,\"no_search\":0,\"shortcut\":0,\"shortcut_mode\":0,\"content_from_pid\":0,\"author\":\"\",\"author_email\":\"\",\"media\":0,\"og_image\":0,\"twitter_image\":0}',0,0,0,0,1,0,31,27,0,'Events [EN]','/events-en',254,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'events',0,'','','',0,0,0,0,0,0,'','','',0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(11,1,1587579033,1586431308,1,0,0,0,0,'',1088,NULL,0,0,0,0,NULL,0,_binary '{\"doktype\":null,\"title\":null,\"slug\":null,\"nav_title\":null,\"subtitle\":null,\"seo_title\":null,\"description\":null,\"no_index\":null,\"no_follow\":null,\"canonical_link\":null,\"sitemap_changefreq\":null,\"sitemap_priority\":null,\"og_title\":null,\"og_description\":null,\"og_image\":null,\"twitter_title\":null,\"twitter_description\":null,\"twitter_image\":null,\"twitter_card\":null,\"abstract\":null,\"keywords\":null,\"author\":null,\"author_email\":null,\"lastUpdated\":null,\"layout\":null,\"newUntil\":null,\"backend_layout\":null,\"backend_layout_next_level\":null,\"content_from_pid\":null,\"target\":null,\"cache_timeout\":null,\"cache_tags\":null,\"is_siteroot\":null,\"no_search\":null,\"php_tree_stop\":null,\"module\":null,\"media\":null,\"tsconfig_includes\":null,\"TSconfig\":null,\"l18n_cfg\":null,\"hidden\":null,\"nav_hide\":null,\"starttime\":null,\"endtime\":null,\"extendToSubpages\":null,\"fe_group\":null,\"fe_login_mode\":null,\"editlock\":null,\"categories\":null,\"rowDescription\":null}',0,0,0,0,1,0,31,27,0,'Event List (category menu)','/event-list-category-menu',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1587579063,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(12,1,1587579033,1586431322,1,0,0,0,0,'',1088,NULL,0,1,11,11,'{\"starttime\":\"parent\",\"endtime\":\"parent\",\"nav_hide\":\"parent\",\"url\":\"parent\",\"lastUpdated\":\"parent\",\"newUntil\":\"parent\",\"no_search\":\"parent\",\"shortcut\":\"parent\",\"shortcut_mode\":\"parent\",\"content_from_pid\":\"parent\",\"author\":\"parent\",\"author_email\":\"parent\",\"media\":\"parent\",\"og_image\":\"parent\",\"twitter_image\":\"parent\"}',0,_binary '{\"doktype\":1,\"title\":\"Event List (category menu)\",\"slug\":\"\\/event-list-category-menu\",\"nav_title\":\"\",\"subtitle\":\"\",\"seo_title\":\"\",\"description\":null,\"canonical_link\":\"\",\"sitemap_changefreq\":\"\",\"sitemap_priority\":\"0.5\",\"og_title\":\"\",\"og_description\":null,\"twitter_title\":\"\",\"twitter_description\":null,\"twitter_card\":\"summary\",\"abstract\":null,\"keywords\":null,\"hidden\":0,\"categories\":0,\"rowDescription\":null,\"TSconfig\":null,\"php_tree_stop\":0,\"editlock\":0,\"layout\":0,\"fe_group\":\"\",\"extendToSubpages\":0,\"target\":\"\",\"cache_timeout\":0,\"cache_tags\":\"\",\"mount_pid\":0,\"is_siteroot\":0,\"mount_pid_ol\":0,\"module\":\"\",\"fe_login_mode\":0,\"l18n_cfg\":0,\"backend_layout\":\"\",\"backend_layout_next_level\":\"\",\"tsconfig_includes\":null,\"no_index\":0,\"no_follow\":0,\"starttime\":0,\"endtime\":0,\"nav_hide\":0,\"url\":\"\",\"lastUpdated\":0,\"newUntil\":0,\"no_search\":0,\"shortcut\":0,\"shortcut_mode\":0,\"content_from_pid\":0,\"author\":\"\",\"author_email\":\"\",\"media\":0,\"og_image\":0,\"twitter_image\":0}',0,0,0,0,1,0,31,27,0,'Event List (category menu)','/event-list-category-menu',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1587579064,NULL,'',0,'','','',0,0,0,0,0,0,'','','',0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(13,1,1586581320,1586581309,1,0,0,0,0,'0',320,NULL,0,0,0,0,NULL,0,_binary '{\"doktype\":null,\"title\":null,\"slug\":null,\"backend_layout\":null,\"backend_layout_next_level\":null,\"module\":null,\"media\":null,\"tsconfig_includes\":null,\"TSconfig\":null,\"hidden\":null,\"editlock\":null,\"categories\":null,\"rowDescription\":null}',0,0,0,0,1,0,31,27,0,'Users','/users',254,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'fe_users',0,'','','',0,0,0,0,0,0,'','',NULL,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(14,1,1586581379,1586581375,1,0,0,0,0,'0',1344,NULL,0,0,0,0,NULL,0,_binary '{\"hidden\":null}',0,0,0,0,1,0,31,27,0,'Login','/login',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1586581516,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(15,14,1586581479,1586581406,1,0,0,0,0,'1',256,NULL,0,0,0,0,NULL,0,_binary '{\"doktype\":null,\"title\":null,\"slug\":null,\"nav_title\":null,\"subtitle\":null,\"seo_title\":null,\"description\":null,\"no_index\":null,\"no_follow\":null,\"canonical_link\":null,\"sitemap_changefreq\":null,\"sitemap_priority\":null,\"og_title\":null,\"og_description\":null,\"og_image\":null,\"twitter_title\":null,\"twitter_description\":null,\"twitter_image\":null,\"twitter_card\":null,\"abstract\":null,\"keywords\":null,\"author\":null,\"author_email\":null,\"lastUpdated\":null,\"layout\":null,\"newUntil\":null,\"backend_layout\":null,\"backend_layout_next_level\":null,\"content_from_pid\":null,\"target\":null,\"cache_timeout\":null,\"cache_tags\":null,\"is_siteroot\":null,\"no_search\":null,\"php_tree_stop\":null,\"module\":null,\"media\":null,\"tsconfig_includes\":null,\"TSconfig\":null,\"l18n_cfg\":null,\"hidden\":null,\"nav_hide\":null,\"starttime\":null,\"endtime\":null,\"extendToSubpages\":null,\"fe_group\":null,\"fe_login_mode\":null,\"editlock\":null,\"categories\":null,\"rowDescription\":null}',0,0,0,0,1,0,31,27,0,'User Events','/login/user-events',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1586581557,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(16,1,1586581490,1586581483,1,0,0,0,0,'',1344,NULL,0,1,14,14,'{\"starttime\":\"parent\",\"endtime\":\"parent\",\"nav_hide\":\"parent\",\"url\":\"parent\",\"lastUpdated\":\"parent\",\"newUntil\":\"parent\",\"no_search\":\"parent\",\"shortcut\":\"parent\",\"shortcut_mode\":\"parent\",\"content_from_pid\":\"parent\",\"author\":\"parent\",\"author_email\":\"parent\",\"media\":\"parent\",\"og_image\":\"parent\",\"twitter_image\":\"parent\"}',0,_binary '{\"doktype\":1,\"title\":\"Login\",\"slug\":\"\\/login\",\"nav_title\":\"\",\"subtitle\":\"\",\"seo_title\":\"\",\"description\":null,\"canonical_link\":\"\",\"sitemap_changefreq\":\"\",\"sitemap_priority\":\"0.5\",\"og_title\":\"\",\"og_description\":null,\"twitter_title\":\"\",\"twitter_description\":null,\"twitter_card\":\"summary\",\"abstract\":null,\"keywords\":null,\"hidden\":0,\"categories\":0,\"rowDescription\":null,\"TSconfig\":null,\"php_tree_stop\":0,\"editlock\":0,\"layout\":0,\"fe_group\":\"0\",\"extendToSubpages\":0,\"target\":\"\",\"cache_timeout\":0,\"cache_tags\":\"\",\"mount_pid\":0,\"is_siteroot\":0,\"mount_pid_ol\":0,\"module\":\"\",\"fe_login_mode\":0,\"l18n_cfg\":0,\"backend_layout\":\"\",\"backend_layout_next_level\":\"\",\"tsconfig_includes\":null,\"no_index\":0,\"no_follow\":0}',0,0,0,0,1,0,31,27,0,'Login','/login',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1586581529,NULL,'',0,'','','',0,0,0,0,0,0,'','','',0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(17,14,1586581960,1586581954,1,0,0,0,0,'1',256,NULL,0,1,15,15,'{\"starttime\":\"parent\",\"endtime\":\"parent\",\"nav_hide\":\"parent\",\"url\":\"parent\",\"lastUpdated\":\"parent\",\"newUntil\":\"parent\",\"no_search\":\"parent\",\"shortcut\":\"parent\",\"shortcut_mode\":\"parent\",\"content_from_pid\":\"parent\",\"author\":\"parent\",\"author_email\":\"parent\",\"media\":\"parent\",\"og_image\":\"parent\",\"twitter_image\":\"parent\"}',0,_binary '{\"doktype\":1,\"title\":\"User Events\",\"slug\":\"\\/login\\/user-events\",\"nav_title\":\"\",\"subtitle\":\"\",\"seo_title\":\"\",\"description\":null,\"canonical_link\":\"\",\"sitemap_changefreq\":\"\",\"sitemap_priority\":\"0.5\",\"og_title\":\"\",\"og_description\":null,\"twitter_title\":\"\",\"twitter_description\":null,\"twitter_card\":\"summary\",\"abstract\":null,\"keywords\":null,\"hidden\":0,\"categories\":0,\"rowDescription\":null,\"TSconfig\":null,\"php_tree_stop\":0,\"editlock\":0,\"layout\":0,\"fe_group\":\"1\",\"extendToSubpages\":0,\"target\":\"\",\"cache_timeout\":0,\"cache_tags\":\"\",\"mount_pid\":0,\"is_siteroot\":0,\"mount_pid_ol\":0,\"module\":\"\",\"fe_login_mode\":0,\"l18n_cfg\":0,\"backend_layout\":\"\",\"backend_layout_next_level\":\"\",\"tsconfig_includes\":null,\"no_index\":0,\"no_follow\":0}',0,0,0,0,1,0,31,27,0,'User Events','/login/user-events',1,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,1586581977,NULL,'',0,'','','',0,0,0,0,0,0,'','','',0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0),(18,4,1586972298,1586972295,1,0,0,0,0,'0',256,NULL,0,0,0,0,NULL,0,_binary '{\"hidden\":null}',0,0,0,0,1,0,31,27,0,'Subfolder','/subfolder',254,NULL,0,0,'',0,0,'',0,'',0,0,NULL,0,'',0,NULL,0,0,NULL,'',0,'','','',0,0,0,0,0,0,'','',NULL,0,'',0,0,'',NULL,0,'',NULL,0,'summary','',0.5,'',0);
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
INSERT INTO `sys_category` VALUES (1,4,1586430237,1586410691,1,0,0,0,0,256,'',0,0,NULL,0,_binary '{\"title\":null,\"parent\":null,\"items\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"description\":null}',0,0,0,0,'Category 1 [DE]',0,14,NULL),(2,4,1586430243,1586410701,1,0,0,0,0,512,'',0,0,NULL,0,_binary '{\"title\":null,\"parent\":null,\"items\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"description\":null}',0,0,0,0,'Category 2 [DE]',0,2,NULL),(3,4,1586430251,1586410708,1,0,0,0,0,768,'',0,0,NULL,0,_binary '{\"title\":null,\"parent\":null,\"items\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"description\":null}',0,0,0,0,'Category 3 [DE]',0,0,NULL),(4,4,1586430272,1586410714,1,0,0,0,0,1024,'',0,0,NULL,0,_binary '{\"title\":null,\"parent\":null,\"items\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"description\":null}',0,0,0,0,'Category 4 [DE]',0,0,NULL),(5,4,1586430356,1586430349,1,0,0,0,0,384,'',1,1,'{\"starttime\":\"parent\",\"endtime\":\"parent\"}',1,_binary '{\"title\":\"Category 1 [DE]\",\"parent\":0,\"items\":14,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":null,\"endtime\":null,\"description\":\"\",\"l10n_parent\":0}',0,0,0,0,'Category 1 [EN]',0,14,NULL),(6,4,1586430364,1586430360,1,0,0,0,0,448,'',1,2,'{\"starttime\":\"parent\",\"endtime\":\"parent\"}',2,_binary '{\"title\":\"Category 2 [DE]\",\"parent\":0,\"items\":2,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":null,\"endtime\":null,\"description\":\"\",\"l10n_parent\":0}',0,0,0,0,'Category 2 [EN]',0,2,NULL),(7,4,1586430371,1586430367,1,0,0,0,0,480,'',1,3,'{\"starttime\":\"parent\",\"endtime\":\"parent\"}',3,_binary '{\"title\":\"Category 3 [DE]\",\"parent\":0,\"items\":0,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":null,\"endtime\":null,\"description\":\"\",\"l10n_parent\":0}',0,0,0,0,'Category 3 [EN]',0,0,NULL),(8,4,1586430378,1586430374,1,0,0,0,0,496,'',1,4,'{\"starttime\":\"parent\",\"endtime\":\"parent\"}',4,_binary '{\"title\":\"Category 4 [DE]\",\"parent\":0,\"items\":0,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":null,\"endtime\":null,\"description\":\"\",\"l10n_parent\":0}',0,0,0,0,'Category 4 [EN]',0,0,NULL);
/*!40000 ALTER TABLE `sys_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sys_category_record_mm`
--

LOCK TABLES `sys_category_record_mm` WRITE;
/*!40000 ALTER TABLE `sys_category_record_mm` DISABLE KEYS */;
INSERT INTO `sys_category_record_mm` VALUES (1,1,'tx_sfeventmgt_domain_model_event','category',1,1),(1,2,'tx_sfeventmgt_domain_model_event','category',2,1),(1,3,'tx_sfeventmgt_domain_model_event','category',3,1),(2,4,'tx_sfeventmgt_domain_model_event','category',1,1),(1,5,'tx_sfeventmgt_domain_model_event','category',4,1),(1,6,'tx_sfeventmgt_domain_model_event','category',5,1),(1,7,'tx_sfeventmgt_domain_model_event','category',6,1),(1,8,'tx_sfeventmgt_domain_model_event','category',7,1),(1,9,'tx_sfeventmgt_domain_model_event','category',8,1),(1,10,'tx_sfeventmgt_domain_model_event','category',9,1),(1,11,'tx_sfeventmgt_domain_model_event','category',10,1),(2,12,'tx_sfeventmgt_domain_model_event','category',2,1),(1,13,'tx_sfeventmgt_domain_model_event','category',11,1),(1,14,'tx_sfeventmgt_domain_model_event','category',12,1),(1,15,'tx_sfeventmgt_domain_model_event','category',13,1),(1,16,'tx_sfeventmgt_domain_model_event','category',14,1),(5,1,'tx_sfeventmgt_domain_model_event','category',1,0),(5,2,'tx_sfeventmgt_domain_model_event','category',2,0),(5,3,'tx_sfeventmgt_domain_model_event','category',3,0),(5,5,'tx_sfeventmgt_domain_model_event','category',4,0),(5,6,'tx_sfeventmgt_domain_model_event','category',5,0),(5,7,'tx_sfeventmgt_domain_model_event','category',6,0),(5,8,'tx_sfeventmgt_domain_model_event','category',7,0),(5,9,'tx_sfeventmgt_domain_model_event','category',8,0),(5,10,'tx_sfeventmgt_domain_model_event','category',9,0),(5,11,'tx_sfeventmgt_domain_model_event','category',10,0),(5,13,'tx_sfeventmgt_domain_model_event','category',11,0),(5,14,'tx_sfeventmgt_domain_model_event','category',12,0),(5,15,'tx_sfeventmgt_domain_model_event','category',13,0),(5,16,'tx_sfeventmgt_domain_model_event','category',14,0),(6,4,'tx_sfeventmgt_domain_model_event','category',1,0),(6,12,'tx_sfeventmgt_domain_model_event','category',2,0),(3,17,'tx_sfeventmgt_domain_model_event','category',0,1),(3,18,'tx_sfeventmgt_domain_model_event','category',0,1);
/*!40000 ALTER TABLE `sys_category_record_mm` ENABLE KEYS */;
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
INSERT INTO `sys_file_storage` VALUES (1,0,1586409675,1586409675,0,0,'This is the local fileadmin/ directory. This storage mount has been created automatically by TYPO3.','fileadmin/ (auto-created)','Local','<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"basePath\">\n                    <value index=\"vDEF\">fileadmin/</value>\n                </field>\n                <field index=\"pathType\">\n                    <value index=\"vDEF\">relative</value>\n                </field>\n                <field index=\"caseSensitive\">\n                    <value index=\"vDEF\"></value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>',1,1,1,1,1,1,NULL);
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
-- Dumping data for table `sys_history`
--

LOCK TABLES `sys_history` WRITE;
/*!40000 ALTER TABLE `sys_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sys_language`
--

LOCK TABLES `sys_language` WRITE;
/*!40000 ALTER TABLE `sys_language` DISABLE KEYS */;
INSERT INTO `sys_language` VALUES (1,0,1586409842,0,256,'English','us','en');
/*!40000 ALTER TABLE `sys_language` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sys_lockedrecords`
--

LOCK TABLES `sys_lockedrecords` WRITE;
/*!40000 ALTER TABLE `sys_lockedrecords` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_lockedrecords` ENABLE KEYS */;
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
INSERT INTO `sys_redirect` VALUES (1,0,1586418321,1586409733,1,1,0,0,0,'sf-event-mgt-acceptance-v10.typo3.local','/autogenerated-2/en/',0,0,0,0,'/autogenerated-2/en/event-list',307,0,0,0,0),(2,0,1586418324,1586409733,1,1,0,0,0,'sf-event-mgt-acceptance-v10.typo3.local','/autogenerated-2/en/event-detasil',0,0,0,0,'/autogenerated-2/en/event-list/event-detasil',307,0,0,0,0),(3,0,1586418326,1586409742,1,1,0,0,0,'sf-event-mgt-acceptance-v10.typo3.local','/autogenerated-2/en/event-list/event-detasil',0,0,0,0,'/autogenerated-2/en/event-list/event-detail',307,0,0,0,0),(4,0,1586418328,1586418303,1,1,0,0,0,'sf-event-mgt-acceptance-v10.typo3.local','/de/event-list',0,0,0,0,'/de/event-list-all',307,0,0,0,0),(5,0,1586418330,1586418303,1,1,0,0,0,'sf-event-mgt-acceptance-v10.typo3.local','/de/event-list/event-detail',0,0,0,0,'/de/event-list-all/event-detail',307,0,0,0,0),(6,0,1586418379,1586418366,1,1,0,0,0,'sf-event-mgt-acceptance-v10.typo3.local','/en/translate-to-english-event-list-all',0,0,0,0,'/en/event-list-all',307,0,0,0,0),(7,0,1586418408,1586418406,1,1,0,0,0,'sf-event-mgt-acceptance-v10.typo3.local','/en/event-list-all/translate-to-english-event-detail',0,0,0,0,'/en/event-list-all/event-detail',307,0,0,0,0),(8,0,1586418438,1586418437,1,1,0,0,0,'sf-event-mgt-acceptance-v10.typo3.local','/en/event-list-all/translate-to-english-registration',0,0,0,0,'/en/event-list-all/registration',307,0,0,0,0),(9,0,1586429511,1586429507,1,1,0,0,0,'sf-event-mgt-acceptance-v10.typo3.local','/en/translate-to-english-events',0,0,0,0,'/en/events-en',307,0,0,0,0),(10,0,1586429531,1586429526,1,1,0,0,0,'sf-event-mgt-acceptance-v10.typo3.local','/de/events',0,0,0,0,'/de/events-de',307,0,0,0,0),(11,0,1586431332,1586431329,1,1,0,0,0,'sf-event-mgt-acceptance-v10.typo3.local','/en/translate-to-english-event-list-category-menu',0,0,0,0,'/en/event-list-category-menu',307,0,0,0,0),(12,0,1586581493,1586581490,1,1,0,0,0,'sf-event-mgt-acceptance-v10.typo3.local','/en/translate-to-english-login',0,0,0,0,'/en/login',307,0,0,0,0),(13,0,1586581963,1586581960,1,1,0,0,0,'sf-event-mgt-acceptance-v10.typo3.local','/en/login/translate-to-english-user-events',0,0,0,0,'/en/login/user-events',307,0,0,0,0);
/*!40000 ALTER TABLE `sys_redirect` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sys_refindex`
--

LOCK TABLES `sys_refindex` WRITE;
/*!40000 ALTER TABLE `sys_refindex` DISABLE KEYS */;
INSERT INTO `sys_refindex` VALUES ('00c14f322855e90f38bb1762bb98e4bd','tx_sfeventmgt_domain_model_registration',294,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',18,''),('00c1d7eca02403f32aa88488dc90bb11','tx_sfeventmgt_domain_model_registration',69,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',33,''),('00d7b2620a43080383be5bc913bf9d9c','tt_content',4,'pi_flexform','additional/lDEF/settings.registrationPid/vDEF/','','',0,0,'pages',6,''),('01d86635755b2751d8ad6121af8a9ceb','tx_sfeventmgt_domain_model_registration',300,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',298,''),('029b3ce6a7e65defaeae33175b90f11b','tx_sfeventmgt_domain_model_registration',30,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_registration',1,''),('02d9dfddf87973ad41f2e2282ca5c96d','tx_sfeventmgt_domain_model_registration',157,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',75,''),('0507bdd9964faf30aa7e59c5a89463b5','tx_sfeventmgt_domain_model_event',8,'registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',2,''),('053dbb2a77f567ac04d943596b9f05bf','tx_sfeventmgt_domain_model_registration',290,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',289,''),('05f67795b4d68a9bac382f6710d217c5','sys_category',8,'l10n_parent','','','',0,0,'sys_category',4,''),('06893fa851dd214846f55f875ef5cb8d','tx_sfeventmgt_domain_model_registration',49,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',24,''),('06a12329788a76995ac795edc982c664','pages',16,'l10n_parent','','','',0,0,'pages',14,''),('074f31ffbf10337433026534e07b1550','tx_sfeventmgt_domain_model_registration',117,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',57,''),('0951b546fc625b1085861c88d2d513f7','tx_sfeventmgt_domain_model_registration',273,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',271,''),('09e5bcf858a17624896397b0c6879759','tx_sfeventmgt_domain_model_registration',145,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',70,''),('0a0d066f1bec88a362c373fe12285ad4','tx_sfeventmgt_domain_model_registration_fieldvalue',1,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('0a946dcf0e1e4fc34b37bcef38b5065c','sys_category',1,'items','','','',12,0,'tx_sfeventmgt_domain_model_event',15,''),('0b10b1094ff025ff32c1de3d7be6da72','tx_sfeventmgt_domain_model_registration',113,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',55,''),('0b1174ae7ab6b732bd8d14b976c167f0','tx_sfeventmgt_domain_model_registration',430,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',10,''),('0b1b2c64e2a2edb50bd1a01b2d77078b','tx_sfeventmgt_domain_model_registration',10,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',3,''),('0b5b85c4440e8a809b5b88c3f3ab0a2c','tx_sfeventmgt_domain_model_registration',398,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',2,''),('0db476a9f8ec221fcda1fb9fae157f14','tx_sfeventmgt_domain_model_registration',227,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',226,''),('0deac8e1bd898ccf4113a57ae25935fd','tt_content',7,'pi_flexform','additional/lDEF/settings.detailPid/vDEF/','','',0,0,'pages',3,''),('0f7dca3022881b4565366291b345b8aa','sys_category',2,'items','','','',0,0,'tx_sfeventmgt_domain_model_event',4,''),('107ae1f16faa4d413d70c61cd3aa19b8','tx_sfeventmgt_domain_model_registration',317,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',316,''),('10d18e146f1060988efe13daca4a7841','sys_category',6,'l10n_parent','','','',0,0,'sys_category',2,''),('123c911465a1dcdd22766c1e4f7eb01d','tx_sfeventmgt_domain_model_event',6,'registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',1,''),('12eee9ebf9c23bb61c80df90fbb04ec6','tx_sfeventmgt_domain_model_registration',438,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',11,''),('13b33b58ee159c41f774841e42deff09','tx_sfeventmgt_domain_model_registration',220,'fe_user','','','',0,0,'fe_users',1,''),('140a196f952d7192191520193463f002','tx_sfeventmgt_domain_model_event',18,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_event',17,''),('147adee42c5426bd72f62e9a749bbfd0','sys_category',5,'items','','','',4,0,'tx_sfeventmgt_domain_model_event',6,''),('14828154c0958b1e587bdc2d30e15038','tx_sfeventmgt_domain_model_registration',125,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',59,''),('153874bec868244f893b2d0c647331c8','pages',17,'fe_group','','','',0,0,'fe_groups',1,''),('1549da6c197dc70c6bc01d6cdc66ce21','tx_sfeventmgt_domain_model_registration',438,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',12,''),('161552879a6cbfe8ea291c2af336442f','pages',9,'l10n_parent','','','',0,0,'pages',6,''),('16227a51efdf0f32a68b2f65c719abbb','tx_sfeventmgt_domain_model_registration_fieldvalue',18,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('16bdb6136bece407745512f764b8018d','tx_sfeventmgt_domain_model_registration',312,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',22,''),('16f4014ab652696d3802f3556e566a02','sys_category',5,'items','','','',13,0,'tx_sfeventmgt_domain_model_event',16,''),('186cfc369d0a4fdbe0eab0ed24c32d20','tt_content',8,'l18n_parent','','','',0,0,'tt_content',7,''),('196d35e9b88f4900b80b0aeef6d7a952','tx_sfeventmgt_domain_model_registration_field',3,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('1a45894ac213142cca7a6303b34d5f15','pages',10,'l10n_parent','','','',0,0,'pages',4,''),('1b39219e3b30270186e9211606a9766f','tx_sfeventmgt_domain_model_registration',339,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',3,''),('1c5abce7fb56f918f57282be90b4edaa','tx_sfeventmgt_domain_model_registration',27,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',14,''),('1dc11899dd3eef72fe9a70fcde3259c1','tx_sfeventmgt_domain_model_registration',285,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',15,''),('1ddf299219f2c502f8f0f7cd01facda4','tx_sfeventmgt_domain_model_registration',97,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',47,''),('1fa33d1166fa6a1f6d30cd006117b792','tx_sfeventmgt_domain_model_registration_fieldvalue',15,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('1fb600490e113efa306786d5aee144e6','tx_sfeventmgt_domain_model_registration',231,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',4,''),('202579ab84fbfda32889bd1026efed2a','tx_sfeventmgt_domain_model_event',14,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_event',6,''),('205196235b91b8be325621f00bc73692','tx_sfeventmgt_domain_model_registration',12,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',5,''),('212ce832739e8cf861c3ac053b593b6d','tx_sfeventmgt_domain_model_event',16,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_event',8,''),('21b34861138f8bf9b3662460cc1432aa','tx_sfeventmgt_domain_model_registration',65,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',31,''),('2316ecd948a3a6f59e3ce6cd0f286b87','tx_sfeventmgt_domain_model_registration',161,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',77,''),('23170313f549b406162f9fcb092f0ec3','tx_sfeventmgt_domain_model_registration',93,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',46,''),('238b7b982340dbc7d23a5562d23f0f7e','pages',8,'sys_language_uid','','','',0,0,'sys_language',1,''),('242104c1a65b22b5154447c209e05d44','tx_sfeventmgt_domain_model_event',3,'registration_fields','','','',1,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('24296fc7182c3cc5cb6b3070ec31ae30','tx_sfeventmgt_domain_model_registration',389,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',8,''),('2436d09ea27bf07a35fc37194febf6a1','tx_sfeventmgt_domain_model_registration',137,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',66,''),('25b2cdb2b556df3824c28df3188ffb4b','tx_sfeventmgt_domain_model_event',9,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_event',1,''),('25b77149e19fc0f73682d73925f272f1','tt_content',10,'pi_flexform','sDEF/lDEF/settings.pages/vDEF/','','',0,0,'pages',13,''),('25baab69d437bba7a8714091e77fc307','tt_content',6,'pi_flexform','additional/lDEF/settings.detailPid/vDEF/','','',0,0,'pages',3,''),('25fe83024ed189025303f31b71e513a3','tx_sfeventmgt_domain_model_registration',372,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',370,''),('267503792209dab7b9abfe1180ba09d2','tx_sfeventmgt_domain_model_registration',375,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',3,''),('27c1f29bf3f365a886e0c24c840f4ca4','tx_sfeventmgt_domain_model_registration',430,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',9,''),('2807c8ac933beb769218f70da97649db','tx_sfeventmgt_domain_model_registration_fieldvalue',20,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('2c02466ab7731322cca9380c8bb757d0','sys_category',1,'items','','','',10,0,'tx_sfeventmgt_domain_model_event',13,''),('2c427144c5e4dc38b7a7d74c70e2a206','tx_sfeventmgt_domain_model_registration',81,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',39,''),('2d9796470ac839e427c2ea9a9fdce7cd','tx_sfeventmgt_domain_model_registration',89,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',43,''),('2e234cf063e500452ca61da5f8b0a9c4','tx_sfeventmgt_domain_model_registration',77,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',37,''),('2e9198b99ab014910ad8dfeb21fc86ed','tx_sfeventmgt_domain_model_registration',348,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',6,''),('2fa1373d4e8a9d6751097faa081cf0d7','sys_category',1,'items','','','',3,0,'tx_sfeventmgt_domain_model_event',5,''),('3167e3677f602c5644971960cd484aaf','tx_sfeventmgt_domain_model_registration',61,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',29,''),('31a7d343d55c2a09c7fd58fb4d930113','tt_content',7,'pi_flexform','categoryMenu/lDEF/settings.categoryMenu.categories/vDEF/','','',0,0,'sys_category',1,''),('31dddaf4eeafc4d58d960e7cde393374','tt_content',5,'pi_flexform','additional/lDEF/settings.registrationPid/vDEF/','','',0,0,'pages',6,''),('31f58072c837852871520b88f4c2e262','tx_sfeventmgt_domain_model_registration',205,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',2,''),('329776ba8e4169bd16fb76f234fabd1d','tx_sfeventmgt_domain_model_registration',330,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',2,''),('340dd20777ac1cd10f3714a50e6ac9f1','tx_sfeventmgt_domain_model_registration',276,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',14,''),('342cdb5db51dad5665363b1a036bd5dd','tx_sfeventmgt_domain_model_event',23,'registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',220,''),('35fe169d608b1a2c85ebb2d6584d2114','tx_sfeventmgt_domain_model_registration',249,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',7,''),('3614a53e9a6ebcbb79a0a784a616eae8','tx_sfeventmgt_domain_model_registration',335,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',334,''),('36b057fe4f69268b85d5510330f3e775','sys_category',5,'items','','','',3,0,'tx_sfeventmgt_domain_model_event',5,''),('3773525e00f15d122a6c6b2ab7416137','tx_sfeventmgt_domain_model_registration_fieldvalue',22,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('37e4a10bb488cf84d544d798ec6a69dc','tx_sfeventmgt_domain_model_registration_fieldvalue',17,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('3857717a67ac76a32d37dcc4e36085ab','tx_sfeventmgt_domain_model_registration',194,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',87,''),('387099e2ea2e8fd6f00b05c10957c3c5','sys_category',1,'items','','','',7,0,'tx_sfeventmgt_domain_model_event',9,''),('38c47092c451a30da8840b81e716c35c','sys_category',5,'items','','','',2,0,'tx_sfeventmgt_domain_model_event',3,''),('3accf3f68c071f37544d2faf136b6cf5','tx_sfeventmgt_domain_model_registration',363,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',361,''),('3c676ffba54c50cf225e7eb57fc1519b','sys_category',1,'items','','','',1,0,'tx_sfeventmgt_domain_model_event',2,''),('3d72ec7426c4c98705a9e13489b5b641','tx_sfeventmgt_domain_model_registration',461,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',17,''),('3e3f475d376ab75c7d8492326acbd4fe','tt_content',7,'pi_flexform','additional/lDEF/settings.registrationPid/vDEF/','','',0,0,'pages',6,''),('3ed04b98efb66363f73eb9026e462c5a','tx_sfeventmgt_domain_model_registration_fieldvalue',9,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('3f47ae0e0b32e2660e1af2b770e87f5f','tx_sfeventmgt_domain_model_registration',446,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',14,''),('3f761457e4565320b98bd1ffca5660f7','tx_sfeventmgt_domain_model_registration',133,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',63,''),('4048ab780f640b6894bc7df456fbd7a5','tx_sfeventmgt_domain_model_registration',422,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',8,''),('404aba354fb04f47ff4f302b036df077','tx_sfeventmgt_domain_model_registration',353,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',352,''),('41afa1171e50da25edd20f0db6727e4f','tx_sfeventmgt_domain_model_registration',309,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',307,''),('41d9b0a7c083fc50dbb2204c37c86b66','tx_sfeventmgt_domain_model_registration',41,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',20,''),('422d2405a075342194c511e0e13a330c','tx_sfeventmgt_domain_model_registration',188,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',86,''),('435f393fdca062402c39582c24128a3a','pages',12,'sys_language_uid','','','',0,0,'sys_language',1,''),('437cbf4fa50935393ee1dc4389f74aba','tx_sfeventmgt_domain_model_event',10,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_event',2,''),('43b38a9624a197a73745573db7183398','tx_sfeventmgt_domain_model_registration',10,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',4,''),('44b13d8bbc0a2d01ef5ccdd34626ac69','tt_content',12,'pi_flexform','sDEF/lDEF/settings.registrationPid/vDEF/','','',0,0,'pages',6,''),('467b16334c95fd5e3d98f12ad67e36ca','tx_sfeventmgt_domain_model_registration',222,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',2,''),('479e8762980f9575162d4cd66e1d1995','tx_sfeventmgt_domain_model_registration',192,'fe_user','','','',0,0,'fe_users',1,''),('49f5f8a67674048d3d0de06a531a2f86','tx_sfeventmgt_domain_model_registration',240,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',5,''),('4ac8260629addfdf9a9b4ad2c5357d49','tt_content',8,'pi_flexform','sDEF/lDEF/settings.storagePage/vDEF/','','',0,0,'pages',4,''),('4c13ab4fd59e77cbc0ebee3850654f4c','sys_category',2,'items','','','',1,0,'tx_sfeventmgt_domain_model_event',12,''),('4c18ff4025f01f6edfe7dbd0a82ed4e2','tx_sfeventmgt_domain_model_registration',137,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',65,''),('4c5d1868b1fc64177f85cc0d0ace8976','tx_sfeventmgt_domain_model_registration',31,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_registration',2,''),('4c61f317fdf6bcd19a079790ae1dc0eb','sys_category',5,'items','','','',6,0,'tx_sfeventmgt_domain_model_event',8,''),('4cb060cf7232baf37018bd2ab3194f37','tx_sfeventmgt_domain_model_registration',85,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',42,''),('4cbdfd7f2654664fd0b055e0ce16114e','sys_category',5,'items','','','',1,0,'tx_sfeventmgt_domain_model_event',2,''),('4d1354ad320a41cc39184286faa1a3da','tx_sfeventmgt_domain_model_registration',81,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',40,''),('4d78ad574307066790506f053561f91a','tx_sfeventmgt_domain_model_registration',414,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',5,''),('4dbe6bef1bb9041a9b3725826477e456','sys_category',1,'items','','','',8,0,'tx_sfeventmgt_domain_model_event',10,''),('4dd357ec86f369470e9da9900d2b0333','tt_content',6,'pi_flexform','additional/lDEF/settings.listPid/vDEF/','','',0,0,'pages',2,''),('500298f57643c4f1d6263b08e72055dc','tx_sfeventmgt_domain_model_registration',41,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',19,''),('50454cb43cd934f8f6f045319058fb89','tt_content',8,'pi_flexform','additional/lDEF/settings.detailPid/vDEF/','','',0,0,'pages',3,''),('50538db074534caf1d5ec1b4be405f4b','pages',7,'l10n_parent','','','',0,0,'pages',2,''),('5321ff42bd2d7739a4359f7be135b83a','pages',17,'sys_language_uid','','','',0,0,'sys_language',1,''),('538e5cbcb518a29f59fb03a45e58a884','tx_sfeventmgt_domain_model_registration',321,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',24,''),('5434d056abe170ba04896a8a334e7758','sys_category',7,'l10n_parent','','','',0,0,'sys_category',3,''),('5443dee0829f7a40fbba0dce12f1caa1','tt_content',2,'pi_flexform','additional/lDEF/settings.listPid/vDEF/','','',0,0,'pages',2,''),('54f697156a9efcc3747fa5938ec2bffe','tx_sfeventmgt_domain_model_registration',453,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',16,''),('57718ba7f6bd8f976945fc2a783f656f','tx_sfeventmgt_domain_model_registration',45,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',21,''),('57758b48f471ff35fb9bfb0ac7f6da9e','pages',16,'sys_language_uid','','','',0,0,'sys_language',1,''),('5980c1e7b94cf1831fdbccff0014932f','tt_content',7,'pi_flexform','categoryMenu/lDEF/settings.categoryMenu.categories/vDEF/','','',1,0,'sys_category',2,''),('5c9d31443ffdf0ee0f1b596dd22961a8','tt_content',12,'pi_flexform','sDEF/lDEF/settings.detailPid/vDEF/','','',0,0,'pages',3,''),('5cca32a0bf522b52f8331a445d4f09a3','tx_sfeventmgt_domain_model_registration',15,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',8,''),('5cf38223f033440bfd1737bb99ae1917','tx_sfeventmgt_domain_model_registration',19,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',9,''),('5d9b233ecca0222b0c7518cee6860d05','tx_sfeventmgt_domain_model_registration',348,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',5,''),('5dca694932873d040f4b870eca4efe76','tx_sfeventmgt_domain_model_registration',105,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',51,''),('5dd9d2dc5c0f450a21514d219877e465','tx_sfeventmgt_domain_model_registration',249,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',8,''),('5e43e3f73c2b2dfc800b0e8ca753ccd6','tt_content',4,'l18n_parent','','','',0,0,'tt_content',1,''),('5ea08959488234cf843b49f97d137867','tx_sfeventmgt_domain_model_registration',321,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',23,''),('5f66a5562fe7decf3de0f1bd88150e2b','tx_sfeventmgt_domain_model_registration',188,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',85,''),('5f87aba0934711e93c3a2d8426155922','tx_sfeventmgt_domain_model_registration',113,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',56,''),('6010b3c91dbacd8eb921c59aa515bb95','tx_sfeventmgt_domain_model_event',23,'location','','','',0,0,'tx_sfeventmgt_domain_model_location',1,''),('6029dd8dcb8f9f880710fc5a01de6e24','tx_sfeventmgt_domain_model_registration_fieldvalue',14,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('605bab91b15c80027719bdc9f8e1c59d','tx_sfeventmgt_domain_model_registration',245,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',244,''),('61c03c43982fffaeab34771fc0a96817','tx_sfeventmgt_domain_model_registration',105,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',52,''),('637610a035e804ed2655aa8b211df14f','tx_sfeventmgt_domain_model_registration',8,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',1,''),('63bcb1ab6073313662873ba486918dd5','sys_category',6,'items','','','',0,0,'tx_sfeventmgt_domain_model_event',4,''),('63cf1f1b06ade99503df3fca06dd4886','tt_content',11,'pi_flexform','sDEF/lDEF/settings.userRegistration.storagePage/vDEF/','','',0,0,'pages',4,''),('63ed1a1bdb37725ed4899c9821c1dd47','sys_category',1,'items','','','',2,0,'tx_sfeventmgt_domain_model_event',3,''),('6428ad20e5f44e05a2d24f7725206005','tx_sfeventmgt_domain_model_location',4,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_location',2,''),('644cc3bc0e5c9d344ccfc475df83f465','tx_sfeventmgt_domain_model_registration',12,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',6,''),('65d96373c38206278790229c8f50d2db','tx_sfeventmgt_domain_model_registration',285,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',16,''),('67f5db50d3cebe5e6bd3b928199a959b','tx_sfeventmgt_domain_model_registration',254,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',253,''),('681684a2bf6ab696e85d15078ddfe4df','tt_content',1,'pi_flexform','sDEF/lDEF/settings.storagePage/vDEF/','','',0,0,'pages',4,''),('68397e245bb292895a35acc2abc5c72f','tx_sfeventmgt_domain_model_registration',199,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',89,''),('6b81ada5e754645fda0fbc975abdc98a','tx_sfeventmgt_domain_model_registration',73,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',35,''),('6bbc1415e855814c074373ea71c4c99a','tx_sfeventmgt_domain_model_registration',366,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',2,''),('6bd72b3bbeb44f9208fe26062694941d','tx_sfeventmgt_domain_model_registration',255,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',253,''),('6c51346c7853cde892ef47e51aabb4fe','tx_sfeventmgt_domain_model_registration',203,'fe_user','','','',0,0,'fe_users',1,''),('6e2f94b754f9c9be0763f5fa7c952fbf','tx_sfeventmgt_domain_model_registration',422,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',7,''),('6ea12d93ed25ea6b1be9f3c8bdba0bef','tx_sfeventmgt_domain_model_event',12,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_event',4,''),('6f25e3c27fb511fddfd0952339e39423','sys_category',5,'items','','','',0,0,'tx_sfeventmgt_domain_model_event',1,''),('6fc765477cb2222fec8fc9779de1ed41','tx_sfeventmgt_domain_model_event',11,'registration_fields','','','',1,0,'tx_sfeventmgt_domain_model_registration_field',4,''),('6fd6bbbab9d38baf0b32d67b0050cb9d','tx_sfeventmgt_domain_model_registration_fieldvalue',4,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('70d1d56fb80ffe31e96c824a58ca3a99','tx_sfeventmgt_domain_model_registration',8,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',2,''),('7158a689ba420df82939e83a6a3e61fa','sys_category',1,'items','','','',9,0,'tx_sfeventmgt_domain_model_event',11,''),('7248b0e7bf4c59c94e9b65f03d0eee3a','tt_content',12,'pi_flexform','sDEF/lDEF/settings.userRegistration.storagePage/vDEF/','','',0,0,'pages',4,''),('72615181d7b8528970771c1459c338ae','tt_content',6,'l18n_parent','','','',0,0,'tt_content',3,''),('74dde81fe13b33aa6e407d035bd81961','pages',9,'sys_language_uid','','','',0,0,'sys_language',1,''),('754fff27764b7f4033fe6fda91065512','tt_content',4,'pi_flexform','additional/lDEF/settings.detailPid/vDEF/','','',0,0,'pages',3,''),('762092b4ef3d0fab4976a23568731a1d','tx_sfeventmgt_domain_model_registration',228,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',226,''),('78499cb723da852aa8f95dd5d1011956','tx_sfeventmgt_domain_model_registration',345,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',343,''),('78701cd6050c0c81deada07489963abe','tt_content',5,'l18n_parent','','','',0,0,'tt_content',2,''),('78b8d4cfe31ee86436ccc204704dd4cf','tx_sfeventmgt_domain_model_registration',33,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',15,''),('7971255d2f83e6af14d7fb6e75a5f71d','tx_sfeventmgt_domain_model_registration',199,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',90,''),('7984a0dfc17091bfde9c357f8737838d','tx_sfeventmgt_domain_model_registration',57,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',28,''),('7a54a5297b3aed96e62f92517e847005','sys_category',5,'items','','','',8,0,'tx_sfeventmgt_domain_model_event',10,''),('7a9dd7560b2f6918aa59ef37986051f7','tx_sfeventmgt_domain_model_event',15,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_event',7,''),('7b842d56b188957d9849cf2d754dc67a','tt_content',7,'pi_flexform','sDEF/lDEF/settings.storagePage/vDEF/','','',0,0,'pages',4,''),('7b9dc9990e513c3a81e987c5d93c1e08','tx_sfeventmgt_domain_model_registration',133,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',64,''),('7bec58e8c0541ca3df019a0e5184493e','tx_sfeventmgt_domain_model_registration',174,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',79,''),('7cb3e6acea95c49a8b932b63efd87d40','tx_sfeventmgt_domain_model_registration',149,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',71,''),('7d9af5557e35b4533ee0ff607698424b','tx_sfeventmgt_domain_model_registration',446,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',13,''),('7e221ee68dac345bb8a11b640ac5ef73','sys_category',5,'items','','','',7,0,'tx_sfeventmgt_domain_model_event',9,''),('7f4f11712ff594cc13fa0b097c750897','sys_category',1,'items','','','',4,0,'tx_sfeventmgt_domain_model_event',6,''),('8090bf86a244d538ada93179728ddae1','tx_sfeventmgt_domain_model_registration',294,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',17,''),('817c56931ab510f0e64e72023b4d52a6','tx_sfeventmgt_domain_model_registration',240,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',6,''),('81a74b73b93335f30de781369aebcd64','tx_sfeventmgt_domain_model_registration',380,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',379,''),('82f8d0acf963c3a07906906a85bb9678','tx_sfeventmgt_domain_model_registration',264,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',262,''),('83960e94629cd805baa5d93103e03b9b','tx_sfeventmgt_domain_model_registration',213,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',4,''),('85432795e4b1954a2336157002c083f6','tx_sfeventmgt_domain_model_registration',231,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',3,''),('856c6ef4bc2541ccc5d06312fd330af1','tx_sfeventmgt_domain_model_registration',326,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',325,''),('85cb91796db2c23400d89befd7460ea6','tx_sfeventmgt_domain_model_registration',109,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',53,''),('860187ea6d11c420ed5d5a149f9e7f69','tx_sfeventmgt_domain_model_registration',276,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',13,''),('860ecccdd73f533debd999e48b732e75','tx_sfeventmgt_domain_model_registration',89,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',44,''),('86352e337d590b4c765033f90c2f464b','tx_sfeventmgt_domain_model_registration',205,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',1,''),('8666af45291b45105c465ef98d1520ca','tx_sfeventmgt_domain_model_registration',246,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',244,''),('87e93a693bbf9bfe900033a846c36933','tx_sfeventmgt_domain_model_event',14,'registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',30,''),('87f9edb06a740266b6023ffbe840f78b','tx_sfeventmgt_domain_model_registration',339,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',4,''),('886ebdb9550142e02f6fbf59a899f70e','sys_category',3,'items','','','',1,0,'tx_sfeventmgt_domain_model_event',18,''),('8ab603b7bdd169f6b3808c08ee97b523','sys_category',3,'items','','','',0,0,'tx_sfeventmgt_domain_model_event',17,''),('8ba01abd3ce16dc75b77e3c3906d7713','fe_users',1,'usergroup','','','',0,0,'fe_groups',1,''),('8c822859f809bec53661c92859262bb3','tx_sfeventmgt_domain_model_registration',327,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',325,''),('8c99efa59eb8b150aa95166161ff01cd','tx_sfeventmgt_domain_model_registration',53,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',25,''),('8e5d5c8eccdb4301724cf074bdeb0d9f','tx_sfeventmgt_domain_model_registration_fieldvalue',23,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('8f7d043f9e867404aefd10da605207fe','tx_sfeventmgt_domain_model_registration',291,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',289,''),('8f89fcead474e313efc742bc2402798d','sys_category',5,'l10n_parent','','','',0,0,'sys_category',1,''),('90018d508aa103a3c6dc0b05d208c9ce','tt_content',8,'pi_flexform','additional/lDEF/settings.registrationPid/vDEF/','','',0,0,'pages',6,''),('9113dba5b95523d0be010815d7b54bd2','tx_sfeventmgt_domain_model_registration',406,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',3,''),('912c91bebe6201e4ab85d015e3048fa2','tx_sfeventmgt_domain_model_registration',125,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',60,''),('92b3ae7e483d4bdbfdcaaae18757f112','tx_sfeventmgt_domain_model_registration',77,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',38,''),('92be0b0006e42c2322f5ee1d54a8a04f','tx_sfeventmgt_domain_model_registration',330,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',1,''),('93e76f011d8a70f5affe223ac7444f4f','tx_sfeventmgt_domain_model_registration',65,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',32,''),('9459dcf4ed5fb8da3a068f6bf09e03b1','tt_content',12,'l18n_parent','','','',0,0,'tt_content',11,''),('959ac599acee4530b5b2372915630ab8','tx_sfeventmgt_domain_model_registration',49,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',23,''),('96032f68eb3aa3dc5f2ac185f2ee6b16','tx_sfeventmgt_domain_model_registration',23,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',11,''),('97f4bb60a5ffcb851a98df5a1fa4e0d7','tx_sfeventmgt_domain_model_registration',85,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',41,''),('984edab58bec7954b1b5539030076d97','tx_sfeventmgt_domain_model_registration',179,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',81,''),('9a0b2bcf1dfc90c61990c47321916da4','tx_sfeventmgt_domain_model_registration',389,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',7,''),('9a55b2d3b51f7e3f49e74d77676ec18b','tx_sfeventmgt_domain_model_registration',153,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',74,''),('9b822881dbcd2366bec161b495dd1500','tx_sfeventmgt_domain_model_registration',61,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',30,''),('9bafd7e823b9f0dba908f14e74130d1b','sys_template',1,'constants','','email','2',-1,0,'_STRING',0,'info@sfeventmgt.local'),('9bd3d6f397ddd518423e015cdd44d9c3','sys_category',5,'items','','','',10,0,'tx_sfeventmgt_domain_model_event',13,''),('9d5643f4d5613b06723f00f16aae7bff','tx_sfeventmgt_domain_model_registration_fieldvalue',11,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('a0d77b769c34e4d9150f0cd17c90f6b4','pages',10,'sys_language_uid','','','',0,0,'sys_language',1,''),('a18762c54f755184ac032dd271582d6e','tx_sfeventmgt_domain_model_event',11,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_event',3,''),('a274925829727beb92737f060097b085','sys_category',1,'items','','','',5,0,'tx_sfeventmgt_domain_model_event',7,''),('a3242fde2dec48b3ceddd5338f1c1028','tx_sfeventmgt_domain_model_registration',101,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',49,''),('a3da9507e8a22bbb254c00930154ff09','tx_sfeventmgt_domain_model_registration_fieldvalue',19,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('a40346dd1aacf11de52c1b69f7999713','tx_sfeventmgt_domain_model_registration',414,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',6,''),('a4d0d9a96fafb47f683aee3ed88d5a60','tx_sfeventmgt_domain_model_registration',157,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',76,''),('a4e3ab8c81fda4d6b78986718e5b937d','tx_sfeventmgt_domain_model_registration',362,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',361,''),('a534881c3e20fb6505df8be931ad90cf','tx_sfeventmgt_domain_model_registration',263,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',262,''),('a53fe4dae73e60d1c2d7b1581d34cffd','sys_template',1,'constants','','email','5',-1,0,'_STRING',0,'admin@sfeventmgt.local'),('a65473f9875f5008e18fd91c61dc9d27','tx_sfeventmgt_domain_model_registration',27,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',13,''),('a769f58914acccd8f4c35fbe8892fd48','tx_sfeventmgt_domain_model_registration',318,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',316,''),('a97cc63cb7f9ff7737b4197088fcf072','tt_content',8,'pi_flexform','categoryMenu/lDEF/settings.categoryMenu.categories/vDEF/','','',1,0,'sys_category',2,''),('aba080f53352a81f49045f949a995e4c','tx_sfeventmgt_domain_model_registration',281,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',280,''),('ace2993eb2d3bb1404936cab118c99fd','sys_category',5,'items','','','',5,0,'tx_sfeventmgt_domain_model_event',7,''),('ae03ec1d79e5d72c195e0fe78a2b39ed','tx_sfeventmgt_domain_model_registration',336,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',334,''),('ae8647b47f76e3f69e1812587d8d923c','tx_sfeventmgt_domain_model_registration',183,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',83,''),('af76be2d6c94981d1b11a100ea04cfbb','tx_sfeventmgt_domain_model_registration',93,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',45,''),('afbb0f0173764f33eb98fcdbc5263f24','tx_sfeventmgt_domain_model_registration_fieldvalue',8,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('b080c012de55cd60574be3df17c962a1','tx_sfeventmgt_domain_model_registration_fieldvalue',24,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('b470ca29ac0a02e19c8e49246bd9a978','tx_sfeventmgt_domain_model_registration',23,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',12,''),('b5011479f52784426028f5323ee9c7fd','tx_sfeventmgt_domain_model_registration',312,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',21,''),('b761481285a2c2fa8295069a8069a249','tx_sfeventmgt_domain_model_registration',371,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',370,''),('b8d53d26acac210a640fc2cd0f204c95','tt_content',4,'pi_flexform','sDEF/lDEF/settings.storagePage/vDEF/','','',0,0,'pages',4,''),('ba5af553711024a13b1b05cb87f8037b','tx_sfeventmgt_domain_model_registration',149,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',72,''),('ba8d98c59dfb3241de6be140e7ec6edd','tt_content',10,'pi_flexform','s_redirect/lDEF/settings.redirectPageLogin/vDEF/','','',0,0,'pages',15,''),('ba9775808480f45bce3049fb96fd3a1c','tx_sfeventmgt_domain_model_registration',237,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',235,''),('bac8485ecd5128213059d05c784c6bc8','tx_sfeventmgt_domain_model_registration',37,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',18,''),('bba9642146fca8851e6fad712433d34d','tx_sfeventmgt_domain_model_registration',357,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',8,''),('bcf42bd7f1a06a4b370d676250e4d02d','tx_sfeventmgt_domain_model_registration',153,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',73,''),('be8be3366648d76e2a628a1eb9303985','sys_category',5,'items','','','',11,0,'tx_sfeventmgt_domain_model_event',14,''),('c06ed9788beb5c974b301ebd13c520ea','tx_sfeventmgt_domain_model_registration',179,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',82,''),('c08b3e49fd93fd997a168fd826adef39','tt_content',2,'pi_flexform','additional/lDEF/settings.registrationPid/vDEF/','','',0,0,'pages',6,''),('c39f46b26da3d2982cf857d5b709d09c','tx_sfeventmgt_domain_model_registration',453,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',15,''),('c43f73d0087ffd5889fed88e2e2640d0','tx_sfeventmgt_domain_model_event',24,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_event',23,''),('c4cdcdea23fba9b3b082fa15212b0b85','tx_sfeventmgt_domain_model_event',20,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_event',19,''),('c51d8a472c612d513eb27f326a799763','tx_sfeventmgt_domain_model_registration_fieldvalue',5,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('c52b8875f6bc9ba891cd7b182caabd15','tx_sfeventmgt_domain_model_registration',375,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',4,''),('c6c8b7b0db593ef0fe09908b78fbf6ab','tx_sfeventmgt_domain_model_location',3,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_location',1,''),('c9276e0aab2269b11d6101513570aa54','tx_sfeventmgt_domain_model_registration',267,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',11,''),('c92f352d0a5a4bac01043b80489c713a','tx_sfeventmgt_domain_model_registration',174,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',80,''),('c99e0c0017adf1214c3ba877367d42b1','tx_sfeventmgt_domain_model_registration',109,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',54,''),('c9e5fd0779ac498c6d34d6dc7a1a2a14','tt_content',10,'l18n_parent','','','',0,0,'tt_content',9,''),('caab77230afcfc284eff79b12760dcec','tx_sfeventmgt_domain_model_registration',145,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',69,''),('cb71bd53b5b9d5dd43d7debf9f9493d2','tx_sfeventmgt_domain_model_registration',357,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',7,''),('cc98d80a22e76c8fd85f6c31087d3b61','sys_category',1,'items','','','',13,0,'tx_sfeventmgt_domain_model_event',16,''),('cd98f4e17e783b96763ecc0f972846ff','tx_sfeventmgt_domain_model_event',22,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_event',21,''),('cdb7388fe3359c5ecf41cc4333ce5f91','tx_sfeventmgt_domain_model_registration',117,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',58,''),('cdf577dfd7f507bc37c0681e102ffcff','tx_sfeventmgt_domain_model_registration',236,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',235,''),('ce0b38595a858f8a5c2d02bdfc6b6c00','tx_sfeventmgt_domain_model_registration',45,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',22,''),('ce6b9369e61b55f27a0fdaa746853244','tx_sfeventmgt_domain_model_registration',354,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',352,''),('ce8d86f9c9db340304d4e20123a117f5','sys_category',6,'items','','','',1,0,'tx_sfeventmgt_domain_model_event',12,''),('d05502d3a42338f98b11cd6b69923a85','tx_sfeventmgt_domain_model_registration',73,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',36,''),('d08f9b2ebcf62b00ccbeaf6040bf7c93','pages',15,'fe_group','','','',0,0,'fe_groups',1,''),('d0ccfc5317834a2a17733fe022cffd50','tt_content',5,'pi_flexform','additional/lDEF/settings.listPid/vDEF/','','',0,0,'pages',2,''),('d0db9539284592fe25668074141e53cc','tt_content',9,'pi_flexform','s_redirect/lDEF/settings.redirectPageLogin/vDEF/','','',0,0,'pages',15,''),('d16b49b99ebfd858e5a31deba38fd68a','tx_sfeventmgt_domain_model_registration',15,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',7,''),('d2ee1787b2cbbdc5ce80396a9eae9af4','tx_sfeventmgt_domain_model_registration',468,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',20,''),('d3bc91ff31de1c78fcba0399b54b5a8f','tx_sfeventmgt_domain_model_registration',69,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',34,''),('d4709e321031b0173897251fd073d2ba','tx_sfeventmgt_domain_model_registration',366,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',1,''),('d5ddf6e629a5734bf68d1b6e56313d55','tx_sfeventmgt_domain_model_registration_fieldvalue',2,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('d643e83d0f1fc51f019874f8d70b187c','sys_category',1,'items','','','',6,0,'tx_sfeventmgt_domain_model_event',8,''),('d8f028433bfa9b99474a87dd8cc00a01','tx_sfeventmgt_domain_model_registration',37,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',17,''),('d98af6af5d544701370577e25864a340','tx_sfeventmgt_domain_model_registration',258,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',10,''),('d9b9d854786cf066b13cf7f9b32b1731','tx_sfeventmgt_domain_model_registration',468,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',19,''),('d9d15cdf1b5adb650a7fb69c07c6d468','tx_sfeventmgt_domain_model_registration',129,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',61,''),('da7b45cdb71f16425de1dff809ac1f98','tx_sfeventmgt_domain_model_registration',303,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',19,''),('dac9c3ec401510233b2adf3ace5de3cf','tx_sfeventmgt_domain_model_registration',97,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',48,''),('db028f685a70f06ff769ae631fd40b31','tx_sfeventmgt_domain_model_registration',282,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',280,''),('dbb923e5b3fbee0053e44f559169234e','tx_sfeventmgt_domain_model_registration',398,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',1,''),('dc62e8bfda7f5b17f9c5aca28b3c5185','tt_content',11,'pi_flexform','sDEF/lDEF/settings.detailPid/vDEF/','','',0,0,'pages',3,''),('dcbb3089944756a2edda2d1e912f1946','tx_sfeventmgt_domain_model_registration',19,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',10,''),('dccb1d0b21eeed667c3770d1940af005','tx_sfeventmgt_domain_model_registration_fieldvalue',10,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('dcefc1365c36b2b671bfb12d2fe22463','tx_sfeventmgt_domain_model_registration',57,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',27,''),('ddc9043dfd29017bfdc18321a0db7ac1','tx_sfeventmgt_domain_model_registration',161,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',78,''),('e1aa6d3b927d358251a69746c9e2ae6c','tx_sfeventmgt_domain_model_registration_fieldvalue',7,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('e1cacac10872b6a933bd7f645ea970ac','tx_sfeventmgt_domain_model_event',3,'registration_fields','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('e236cdc72055d6b34bdd2e4eeab10676','tx_sfeventmgt_domain_model_registration',272,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',271,''),('e2b0fedb82e4cce153a80e92cacfa802','sys_template',1,'constants','','email','8',-1,0,'_STRING',0,'bcc@sfeventmgt.local'),('e353a958ba9ce2fb10254b6ba58a43ee','tx_sfeventmgt_domain_model_registration',101,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',50,''),('e41f9a65327a850b96087c11256e249a','tx_sfeventmgt_domain_model_registration_fieldvalue',3,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('e43d45edaf174b4f9963e7bd153059ed','tx_sfeventmgt_domain_model_registration',381,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',379,''),('e46cb956652a949a6ef09422465018a8','tx_sfeventmgt_domain_model_event',19,'registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',203,''),('e46f81d223fad26a96bb9d6402e7c4a5','tx_sfeventmgt_domain_model_registration',141,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',68,''),('e663c6bb77f5db2b0173861c05c6d9e2','tx_sfeventmgt_domain_model_registration',299,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',298,''),('e75a5092b45859aff158436cc08bfb33','sys_category',5,'items','','','',12,0,'tx_sfeventmgt_domain_model_event',15,''),('e7f8f73116ea00ae6c311520c529882f','tx_sfeventmgt_domain_model_registration',33,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',16,''),('e8920a9131048835e561df42db3e35b5','pages',8,'l10n_parent','','','',0,0,'pages',3,''),('e8d625b63e9a24d2773181db91f643e7','tx_sfeventmgt_domain_model_registration',129,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',62,''),('e8d7c4b413e5fe9d6241bc4be90007ec','tx_sfeventmgt_domain_model_registration',461,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',18,''),('e958af0f0d2abd67b61f6f4299ad2940','tt_content',11,'pi_flexform','sDEF/lDEF/settings.registrationPid/vDEF/','','',0,0,'pages',6,''),('e97ebbef3b712f7fd8f578dc34778103','tt_content',1,'pi_flexform','additional/lDEF/settings.detailPid/vDEF/','','',0,0,'pages',3,''),('ea729909a18e6c45df39b50f4984b913','tt_content',8,'pi_flexform','categoryMenu/lDEF/settings.categoryMenu.categories/vDEF/','','',0,0,'sys_category',1,''),('eaa4c5303623f4be5fc889132ebd486a','tx_sfeventmgt_domain_model_registration',53,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',26,''),('eb9da2ef54cb472afc06ce9d4c50161e','tx_sfeventmgt_domain_model_registration',406,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',4,''),('ebb41af8de3e4c913fcb9239fe4d3b42','tx_sfeventmgt_domain_model_event',13,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_event',5,''),('ec0c4597be6fed5d71b3c5100b073095','tx_sfeventmgt_domain_model_registration_field',4,'l10n_parent','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('ec477240c4e8c57bb3339bb4d3fd5c37','tx_sfeventmgt_domain_model_registration',222,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',1,''),('ec6a536bef8ac24407bfc4b39b18f978','tx_sfeventmgt_domain_model_registration',194,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',88,''),('ec7efe2b3c5577e94fee7b2d92165e7b','tt_content',3,'pi_flexform','additional/lDEF/settings.listPid/vDEF/','','',0,0,'pages',2,''),('ecbb03eb6acf9c1cb035e78dbdddd105','tx_sfeventmgt_domain_model_event',11,'registration_fields','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',3,''),('ecfe4f9cd113557a828a7b5ba81e21a9','tx_sfeventmgt_domain_model_registration',213,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',3,''),('ed4ca864b60d65395f5c9aae007fc875','pages',7,'sys_language_uid','','','',0,0,'sys_language',1,''),('ee70292fb0c411bacd91ff07ae46aee4','tx_sfeventmgt_domain_model_registration',141,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',67,''),('f23e3e8c7b1b4bd694123d52df3ab031','tx_sfeventmgt_domain_model_event',24,'location','','','',0,0,'tx_sfeventmgt_domain_model_location',1,''),('f31d08fd5e50f81e7c31de2b662f427a','tx_sfeventmgt_domain_model_registration_fieldvalue',21,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('f33df7714e35d96e5cd97d76d1dea945','tt_content',3,'pi_flexform','additional/lDEF/settings.detailPid/vDEF/','','',0,0,'pages',3,''),('f33fd95dda3db68524bbca094ebcdd38','sys_category',5,'items','','','',9,0,'tx_sfeventmgt_domain_model_event',11,''),('f56a0be65448dc3b2113326efcf40add','tx_sfeventmgt_domain_model_registration',267,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',12,''),('f659dcfdf5d160bfb9fb51d18763a883','pages',17,'l10n_parent','','','',0,0,'pages',15,''),('f77cd1d81fb70e25b25eb2d1ede02eff','tx_sfeventmgt_domain_model_registration',258,'field_values','','','',0,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',9,''),('f7d4b2a5fa7493a84801af8fb2688f1f','tx_sfeventmgt_domain_model_registration',183,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',84,''),('f7f1c5960160e66ffca3f3cd2c259a76','sys_category',1,'items','','','',11,0,'tx_sfeventmgt_domain_model_event',14,''),('f925429de92a45f5e0d0ab4d82d0cbc8','tx_sfeventmgt_domain_model_registration_fieldvalue',16,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('f928aeb08705bc4586c1802e3d387b90','tt_content',9,'pi_flexform','sDEF/lDEF/settings.pages/vDEF/','','',0,0,'pages',13,''),('fa4ea2ca927179395ff2e82df07fbf46','sys_category',1,'items','','','',0,0,'tx_sfeventmgt_domain_model_event',1,''),('fa69705e73f6e79e2817a97eaba044b2','tt_content',1,'pi_flexform','additional/lDEF/settings.registrationPid/vDEF/','','',0,0,'pages',6,''),('fad58d3b1c4a2111479375f939ed3a1e','tx_sfeventmgt_domain_model_registration',344,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',343,''),('fc309fed90060dc3f6e769bbbe46258e','tx_sfeventmgt_domain_model_registration_fieldvalue',12,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('fd48c3f1826a6682e20354be1bf4fd35','tx_sfeventmgt_domain_model_registration_fieldvalue',6,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',2,''),('fe0055f985c15a3ca38f052d80a9d1c0','tx_sfeventmgt_domain_model_registration',308,'main_registration','','','',0,0,'tx_sfeventmgt_domain_model_registration',307,''),('fe85fc9a5094f6b2f591cc3004cd03c7','tx_sfeventmgt_domain_model_registration_fieldvalue',13,'field','','','',0,0,'tx_sfeventmgt_domain_model_registration_field',1,''),('fef78c4d709de5af517a3bf31c047710','tx_sfeventmgt_domain_model_registration',303,'field_values','','','',1,0,'tx_sfeventmgt_domain_model_registration_fieldvalue',20,''),('ff46203fc2b75475b72bf12a10736b5a','pages',12,'l10n_parent','','','',0,0,'pages',11,'');
/*!40000 ALTER TABLE `sys_refindex` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sys_registry`
--

LOCK TABLES `sys_registry` WRITE;
/*!40000 ALTER TABLE `sys_registry` DISABLE KEYS */;
INSERT INTO `sys_registry` VALUES (1,'installUpdate','TYPO3\\CMS\\Form\\Hooks\\FormFileExtensionUpdate',_binary 'i:1;'),(2,'installUpdate','TYPO3\\CMS\\Install\\Updates\\ExtensionManagerTables',_binary 'i:1;'),(3,'installUpdate','TYPO3\\CMS\\Install\\Updates\\Typo3DbExtractionUpdate',_binary 'i:1;'),(4,'installUpdate','TYPO3\\CMS\\Install\\Updates\\FuncExtractionUpdate',_binary 'i:1;'),(5,'installUpdate','TYPO3\\CMS\\Install\\Updates\\MigrateUrlTypesInPagesUpdate',_binary 'i:1;'),(6,'installUpdate','TYPO3\\CMS\\Install\\Updates\\SeparateSysHistoryFromSysLogUpdate',_binary 'i:1;'),(7,'installUpdate','TYPO3\\CMS\\Install\\Updates\\RedirectExtractionUpdate',_binary 'i:1;'),(8,'installUpdate','TYPO3\\CMS\\Install\\Updates\\BackendUserStartModuleUpdate',_binary 'i:1;'),(9,'installUpdate','TYPO3\\CMS\\Install\\Updates\\MigratePagesLanguageOverlayUpdate',_binary 'i:1;'),(10,'installUpdate','TYPO3\\CMS\\Install\\Updates\\MigratePagesLanguageOverlayBeGroupsAccessRights',_binary 'i:1;'),(11,'installUpdate','TYPO3\\CMS\\Install\\Updates\\BackendLayoutIconUpdateWizard',_binary 'i:1;'),(12,'installUpdate','TYPO3\\CMS\\Install\\Updates\\RedirectsExtensionUpdate',_binary 'i:1;'),(13,'installUpdate','TYPO3\\CMS\\Install\\Updates\\AdminPanelInstall',_binary 'i:1;'),(14,'installUpdate','TYPO3\\CMS\\Install\\Updates\\PopulatePageSlugs',_binary 'i:1;'),(15,'installUpdate','TYPO3\\CMS\\Install\\Updates\\Argon2iPasswordHashes',_binary 'i:1;'),(16,'installUpdate','TYPO3\\CMS\\Install\\Updates\\BackendUserConfigurationUpdate',_binary 'i:1;'),(17,'installUpdate','TYPO3\\CMS\\Install\\Updates\\RsaauthExtractionUpdate',_binary 'i:1;'),(18,'installUpdate','TYPO3\\CMS\\Install\\Updates\\FeeditExtractionUpdate',_binary 'i:1;'),(19,'installUpdate','TYPO3\\CMS\\Install\\Updates\\TaskcenterExtractionUpdate',_binary 'i:1;'),(20,'installUpdate','TYPO3\\CMS\\Install\\Updates\\SysActionExtractionUpdate',_binary 'i:1;'),(21,'installUpdate','TYPO3\\CMS\\Felogin\\Updates\\MigrateFeloginPlugins',_binary 'i:1;'),(22,'installUpdate','TYPO3\\CMS\\FrontendLogin\\Updates\\MigrateFeloginPluginsCtype',_binary 'i:0;'),(24,'extensionDataImport','typo3conf/ext/sf_event_mgt/ext_tables_static+adt.sql',_binary 's:0:\"\";'),(27,'installUpdateRows','rowUpdatersDone',_binary 'a:4:{i:0;s:69:\"TYPO3\\CMS\\Install\\Updates\\RowUpdater\\WorkspaceVersionRecordsMigration\";i:1;s:66:\"TYPO3\\CMS\\Install\\Updates\\RowUpdater\\L18nDiffsourceToJsonMigration\";i:2;s:77:\"TYPO3\\CMS\\Install\\Updates\\RowUpdater\\WorkspaceMovePlaceholderRemovalMigration\";i:3;s:76:\"TYPO3\\CMS\\Install\\Updates\\RowUpdater\\WorkspaceNewPlaceholderRemovalMigration\";}'),(35,'installUpdate','TYPO3\\CMS\\Install\\Updates\\SvgFilesSanitization',_binary 'i:1;'),(37,'installUpdate','TYPO3\\CMS\\Install\\Updates\\BackendUserLanguageMigration',_binary 'i:1;'),(38,'installUpdate','TYPO3\\CMS\\Install\\Updates\\SysLogChannel',_binary 'i:1;'),(39,'core','formProtectionSessionToken:2',_binary 's:64:\"18cf0142baf318af690a98eb75e2db682dc8d07a35844e392f87d8ba8326fcd6\";'),(40,'installUpdate','DERHANSEN\\SfEventMgt\\Updates\\PiEventPluginUpdater',_binary 'i:1;');
/*!40000 ALTER TABLE `sys_registry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sys_template`
--

LOCK TABLES `sys_template` WRITE;
/*!40000 ALTER TABLE `sys_template` DISABLE KEYS */;
INSERT INTO `sys_template` VALUES (1,1,1592715288,1586409884,1,0,0,0,0,256,NULL,0,0,0,0,0,'sf_event_mgt - Acceptance Tests',1,3,'EXT:fluid_styled_content/Configuration/TypoScript/,EXT:sf_event_mgt/Configuration/TypoScript','plugin.tx_sfeventmgt {\r\n  settings {\r\n    notification {\r\n      senderEmail = info@sfeventmgt.local\r\n      senderName = TYPO3 sf_event_mgt\r\n      senderSignature = Kind Regards<br/>TYPO3 sf_event_mgt\r\n      adminEmail = admin@sfeventmgt.local\r\n      bccEmail = bcc@sfeventmgt.local\r\n    }\r\n  }\r\n}','page = PAGE\r\npage.10 < styles.content.get\r\npage.includeCSS.events = EXT:sf_event_mgt/Resources/Public/Css/events_default.css','',0,0,0);
/*!40000 ALTER TABLE `sys_template` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tt_content`
--

LOCK TABLES `tt_content` WRITE;
/*!40000 ALTER TABLE `tt_content` DISABLE KEYS */;
INSERT INTO `tt_content` VALUES (1,'',2,1586430914,1586410175,1,0,0,0,0,'',256,0,0,0,0,NULL,0,_binary '{\"CType\":null,\"colPos\":null,\"header\":null,\"header_layout\":null,\"header_position\":null,\"date\":null,\"header_link\":null,\"subheader\":null,\"list_type\":null,\"pi_flexform\":null,\"frame_class\":null,\"space_before_class\":null,\"space_after_class\":null,\"sectionIndex\":null,\"linkToTop\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"editlock\":null,\"categories\":null,\"rowDescription\":null}',0,0,0,0,'list','','',NULL,0,0,0,0,0,0,0,2,0,0,0,'default',0,'','',NULL,NULL,0,'','',0,'0','sfeventmgt_pieventlist',1,0,NULL,0,'','','',0,0,0,'<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"settings.displayMode\">\n                    <value index=\"vDEF\">all</value>\n                </field>\n                <field index=\"settings.categoryConjunction\">\n                    <value index=\"vDEF\">OR</value>\n                </field>\n                <field index=\"settings.includeSubcategories\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.location\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.organisator\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.speaker\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.recursive\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.storagePage\">\n                    <value index=\"vDEF\">4</value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"additional\">\n            <language index=\"lDEF\">\n                <field index=\"settings.restrictForeignRecordsToStoragePage\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.disableOverrideDemand\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.detailPid\">\n                    <value index=\"vDEF\">3</value>\n                </field>\n                <field index=\"settings.registrationPid\">\n                    <value index=\"vDEF\">6</value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"categoryMenu\">\n            <language index=\"lDEF\">\n                <field index=\"settings.categoryMenu.categories\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.categoryMenu.includeSubcategories\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>','',0,'',NULL,'','',NULL,124,0,0,0,0,0),(2,'',3,1586410862,1586410364,1,0,0,0,0,'',256,0,0,0,0,NULL,0,_binary '{\"CType\":null,\"colPos\":null,\"header\":null,\"header_layout\":null,\"header_position\":null,\"date\":null,\"header_link\":null,\"subheader\":null,\"list_type\":null,\"pi_flexform\":null,\"frame_class\":null,\"space_before_class\":null,\"space_after_class\":null,\"sectionIndex\":null,\"linkToTop\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"editlock\":null,\"categories\":null,\"rowDescription\":null}',0,0,0,0,'list','','',NULL,0,0,0,0,0,0,0,2,0,0,0,'default',0,'','',NULL,'',0,'','',0,'0','sfeventmgt_pieventdetail',1,0,NULL,0,'','','',0,0,0,'<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"settings.recursive\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.singleEvent\">\n                    <value index=\"vDEF\"></value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"additional\">\n            <language index=\"lDEF\">\n                <field index=\"settings.listPid\">\n                    <value index=\"vDEF\">2</value>\n                </field>\n                <field index=\"settings.registrationPid\">\n                    <value index=\"vDEF\">6</value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>','',0,'',NULL,'','',NULL,124,0,0,0,0,0),(3,'',6,1586421201,1586410799,1,0,0,0,0,'',256,0,0,0,0,NULL,0,_binary '{\"CType\":null,\"colPos\":null,\"header\":null,\"header_layout\":null,\"header_position\":null,\"date\":null,\"header_link\":null,\"subheader\":null,\"list_type\":null,\"pi_flexform\":null,\"frame_class\":null,\"space_before_class\":null,\"space_after_class\":null,\"sectionIndex\":null,\"linkToTop\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"editlock\":null,\"categories\":null,\"rowDescription\":null}',0,0,0,0,'list','','',NULL,0,0,0,0,0,0,0,2,0,0,0,'default',0,'','',NULL,NULL,0,'','',0,'0','sfeventmgt_pieventregistration',1,0,NULL,0,'','','',0,0,0,'<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"settings.recursive\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.singleEvent\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.registration.requiredFields\">\n                    <value index=\"vDEF\">company</value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"additional\">\n            <language index=\"lDEF\">\n                <field index=\"settings.detailPid\">\n                    <value index=\"vDEF\">3</value>\n                </field>\n                <field index=\"settings.listPid\">\n                    <value index=\"vDEF\">2</value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>','',0,'',NULL,'','',NULL,124,0,0,0,0,0),(4,'',2,1586430903,1586418388,1,0,0,0,0,'',512,0,1,1,1,NULL,1,_binary '{\"CType\":\"list\",\"colPos\":0,\"header\":\"\",\"header_layout\":\"0\",\"header_position\":\"\",\"date\":0,\"header_link\":\"\",\"subheader\":\"\",\"list_type\":\"sfeventmgt_pievent\",\"pi_flexform\":\"<?xml version=\\\"1.0\\\" encoding=\\\"utf-8\\\" standalone=\\\"yes\\\" ?>\\n<T3FlexForms>\\n    <data>\\n        <sheet index=\\\"sDEF\\\">\\n            <language index=\\\"lDEF\\\">\\n                <field index=\\\"settings.displayMode\\\">\\n                    <value index=\\\"vDEF\\\">all<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.categoryConjunction\\\">\\n                    <value index=\\\"vDEF\\\">OR<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.includeSubcategories\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.location\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.organisator\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.speaker\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.recursive\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.storagePage\\\">\\n                    <value index=\\\"vDEF\\\">4<\\/value>\\n                <\\/field>\\n                <field index=\\\"switchableControllerActions\\\">\\n                    <value index=\\\"vDEF\\\">Event-&gt;list<\\/value>\\n                <\\/field>\\n            <\\/language>\\n        <\\/sheet>\\n        <sheet index=\\\"additional\\\">\\n            <language index=\\\"lDEF\\\">\\n                <field index=\\\"settings.restrictForeignRecordsToStoragePage\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.disableOverrideDemand\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.detailPid\\\">\\n                    <value index=\\\"vDEF\\\">3<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.registrationPid\\\">\\n                    <value index=\\\"vDEF\\\">6<\\/value>\\n                <\\/field>\\n            <\\/language>\\n        <\\/sheet>\\n        <sheet index=\\\"categoryMenu\\\">\\n            <language index=\\\"lDEF\\\">\\n                <field index=\\\"settings.categoryMenu.categories\\\">\\n                    <value index=\\\"vDEF\\\">1,2<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.categoryMenu.includeSubcategories\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n            <\\/language>\\n        <\\/sheet>\\n    <\\/data>\\n<\\/T3FlexForms>\",\"frame_class\":\"default\",\"space_before_class\":\"\",\"space_after_class\":\"\",\"sectionIndex\":1,\"linkToTop\":0,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":0,\"endtime\":0,\"fe_group\":\"\",\"editlock\":0,\"categories\":0,\"rowDescription\":\"\",\"l18n_parent\":0}',0,0,0,0,'list','','',NULL,0,0,0,0,0,0,0,2,0,0,0,'default',0,'','','','',0,'','',0,'0','sfeventmgt_pieventlist',1,0,'',0,'','','',0,0,0,'<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"settings.displayMode\">\n                    <value index=\"vDEF\">all</value>\n                </field>\n                <field index=\"settings.categoryConjunction\">\n                    <value index=\"vDEF\">OR</value>\n                </field>\n                <field index=\"settings.includeSubcategories\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.location\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.organisator\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.speaker\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.recursive\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.storagePage\">\n                    <value index=\"vDEF\">4</value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"additional\">\n            <language index=\"lDEF\">\n                <field index=\"settings.restrictForeignRecordsToStoragePage\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.disableOverrideDemand\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.detailPid\">\n                    <value index=\"vDEF\">3</value>\n                </field>\n                <field index=\"settings.registrationPid\">\n                    <value index=\"vDEF\">6</value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"categoryMenu\">\n            <language index=\"lDEF\">\n                <field index=\"settings.categoryMenu.categories\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.categoryMenu.includeSubcategories\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>','',0,'','','','',NULL,124,0,0,0,0,0),(5,'',3,1586418427,1586418420,1,0,0,0,0,'',512,0,1,2,2,NULL,2,_binary '{\"CType\":\"list\",\"colPos\":0,\"header\":\"\",\"header_layout\":\"0\",\"header_position\":\"\",\"date\":0,\"header_link\":\"\",\"subheader\":\"\",\"list_type\":\"sfeventmgt_pievent\",\"pi_flexform\":\"<?xml version=\\\"1.0\\\" encoding=\\\"utf-8\\\" standalone=\\\"yes\\\" ?>\\n<T3FlexForms>\\n    <data>\\n        <sheet index=\\\"sDEF\\\">\\n            <language index=\\\"lDEF\\\">\\n                <field index=\\\"settings.displayMode\\\">\\n                    <value index=\\\"vDEF\\\">all<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.categoryConjunction\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.includeSubcategories\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.location\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.organisator\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.speaker\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.recursive\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.singleEvent\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"switchableControllerActions\\\">\\n                    <value index=\\\"vDEF\\\">Event-&gt;detail;Event-&gt;icalDownload<\\/value>\\n                <\\/field>\\n            <\\/language>\\n        <\\/sheet>\\n        <sheet index=\\\"additional\\\">\\n            <language index=\\\"lDEF\\\">\\n                <field index=\\\"settings.restrictForeignRecordsToStoragePage\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.disableOverrideDemand\\\">\\n                    <value index=\\\"vDEF\\\">1<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.listPid\\\">\\n                    <value index=\\\"vDEF\\\">2<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.registrationPid\\\">\\n                    <value index=\\\"vDEF\\\">6<\\/value>\\n                <\\/field>\\n            <\\/language>\\n        <\\/sheet>\\n    <\\/data>\\n<\\/T3FlexForms>\",\"frame_class\":\"default\",\"space_before_class\":\"\",\"space_after_class\":\"\",\"sectionIndex\":1,\"linkToTop\":0,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":0,\"endtime\":0,\"fe_group\":\"\",\"editlock\":0,\"categories\":0,\"rowDescription\":\"\",\"l18n_parent\":0}',0,0,0,0,'list','','',NULL,0,0,0,0,0,0,0,2,0,0,0,'default',0,'','','','',0,'','',0,'0','sfeventmgt_pieventdetail',1,0,'',0,'','','',0,0,0,'<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"settings.recursive\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.singleEvent\">\n                    <value index=\"vDEF\"></value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"additional\">\n            <language index=\"lDEF\">\n                <field index=\"settings.listPid\">\n                    <value index=\"vDEF\">2</value>\n                </field>\n                <field index=\"settings.registrationPid\">\n                    <value index=\"vDEF\">6</value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>','',0,'','','','',NULL,124,0,0,0,0,0),(6,'',6,1586421210,1586418449,1,0,0,0,0,'',512,0,1,3,3,NULL,3,_binary '{\"CType\":\"list\",\"colPos\":0,\"header\":\"\",\"header_layout\":\"0\",\"header_position\":\"\",\"date\":0,\"header_link\":\"\",\"subheader\":\"\",\"list_type\":\"sfeventmgt_pievent\",\"pi_flexform\":\"<?xml version=\\\"1.0\\\" encoding=\\\"utf-8\\\" standalone=\\\"yes\\\" ?>\\n<T3FlexForms>\\n    <data>\\n        <sheet index=\\\"sDEF\\\">\\n            <language index=\\\"lDEF\\\">\\n                <field index=\\\"settings.displayMode\\\">\\n                    <value index=\\\"vDEF\\\">all<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.categoryConjunction\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.includeSubcategories\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.location\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.organisator\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.speaker\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.recursive\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.singleEvent\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"switchableControllerActions\\\">\\n                    <value index=\\\"vDEF\\\">Event-&gt;registration;Event-&gt;saveRegistration;Event-&gt;saveRegistrationResult;Event-&gt;confirmRegistration;Event-&gt;cancelRegistration<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.registration.requiredFields\\\">\\n                    <value index=\\\"vDEF\\\">company<\\/value>\\n                <\\/field>\\n            <\\/language>\\n        <\\/sheet>\\n        <sheet index=\\\"additional\\\">\\n            <language index=\\\"lDEF\\\">\\n                <field index=\\\"settings.detailPid\\\">\\n                    <value index=\\\"vDEF\\\">3<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.listPid\\\">\\n                    <value index=\\\"vDEF\\\">2<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.restrictForeignRecordsToStoragePage\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.disableOverrideDemand\\\">\\n                    <value index=\\\"vDEF\\\">1<\\/value>\\n                <\\/field>\\n            <\\/language>\\n        <\\/sheet>\\n        <sheet index=\\\"categoryMenu\\\">\\n            <language index=\\\"lDEF\\\">\\n                <field index=\\\"settings.categoryMenu.categories\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.categoryMenu.includeSubcategories\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n            <\\/language>\\n        <\\/sheet>\\n    <\\/data>\\n<\\/T3FlexForms>\",\"frame_class\":\"default\",\"space_before_class\":\"\",\"space_after_class\":\"\",\"sectionIndex\":1,\"linkToTop\":0,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":0,\"endtime\":0,\"fe_group\":\"\",\"editlock\":0,\"categories\":0,\"rowDescription\":\"\",\"l18n_parent\":0}',0,0,0,0,'list','','',NULL,0,0,0,0,0,0,0,2,0,0,0,'default',0,'','','','',0,'','',0,'0','sfeventmgt_pieventregistration',1,0,'',0,'','','',0,0,0,'<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"settings.recursive\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.singleEvent\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.registration.requiredFields\">\n                    <value index=\"vDEF\">company</value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"additional\">\n            <language index=\"lDEF\">\n                <field index=\"settings.detailPid\">\n                    <value index=\"vDEF\">3</value>\n                </field>\n                <field index=\"settings.listPid\">\n                    <value index=\"vDEF\">2</value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>','',0,'','','','',NULL,124,0,0,0,0,0),(7,'',11,1587579063,1586431348,1,0,0,0,0,'',256,0,0,0,0,NULL,1,_binary '{\"hidden\":null}',0,0,0,0,'list','','',NULL,0,0,0,0,0,0,0,2,0,0,0,'default',0,'','','','',0,'','',0,'0','sfeventmgt_pieventlist',1,0,'',0,'','','',0,0,0,'<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"settings.displayMode\">\n                    <value index=\"vDEF\">all</value>\n                </field>\n                <field index=\"settings.categoryConjunction\">\n                    <value index=\"vDEF\">OR</value>\n                </field>\n                <field index=\"settings.includeSubcategories\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.location\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.organisator\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.speaker\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.recursive\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.storagePage\">\n                    <value index=\"vDEF\">4</value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"additional\">\n            <language index=\"lDEF\">\n                <field index=\"settings.restrictForeignRecordsToStoragePage\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.disableOverrideDemand\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.detailPid\">\n                    <value index=\"vDEF\">3</value>\n                </field>\n                <field index=\"settings.registrationPid\">\n                    <value index=\"vDEF\">6</value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"categoryMenu\">\n            <language index=\"lDEF\">\n                <field index=\"settings.categoryMenu.categories\">\n                    <value index=\"vDEF\">1,2</value>\n                </field>\n                <field index=\"settings.categoryMenu.includeSubcategories\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>','',0,'','','','',NULL,124,0,0,0,0,0),(8,'',11,1587579064,1586431348,1,0,0,0,0,'',128,0,1,7,7,NULL,4,_binary '{\"CType\":\"list\",\"colPos\":0,\"header\":\"\",\"header_layout\":\"0\",\"header_position\":\"\",\"date\":0,\"header_link\":\"\",\"subheader\":\"\",\"list_type\":\"sfeventmgt_pievent\",\"pi_flexform\":\"<?xml version=\\\"1.0\\\" encoding=\\\"utf-8\\\" standalone=\\\"yes\\\" ?>\\n<T3FlexForms>\\n    <data>\\n        <sheet index=\\\"sDEF\\\">\\n            <language index=\\\"lDEF\\\">\\n                <field index=\\\"settings.displayMode\\\">\\n                    <value index=\\\"vDEF\\\">all<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.categoryConjunction\\\">\\n                    <value index=\\\"vDEF\\\">OR<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.includeSubcategories\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.location\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.organisator\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.speaker\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.recursive\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.storagePage\\\">\\n                    <value index=\\\"vDEF\\\">4<\\/value>\\n                <\\/field>\\n                <field index=\\\"switchableControllerActions\\\">\\n                    <value index=\\\"vDEF\\\">Event-&gt;list<\\/value>\\n                <\\/field>\\n            <\\/language>\\n        <\\/sheet>\\n        <sheet index=\\\"additional\\\">\\n            <language index=\\\"lDEF\\\">\\n                <field index=\\\"settings.restrictForeignRecordsToStoragePage\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.disableOverrideDemand\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.detailPid\\\">\\n                    <value index=\\\"vDEF\\\">3<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.registrationPid\\\">\\n                    <value index=\\\"vDEF\\\">6<\\/value>\\n                <\\/field>\\n            <\\/language>\\n        <\\/sheet>\\n        <sheet index=\\\"categoryMenu\\\">\\n            <language index=\\\"lDEF\\\">\\n                <field index=\\\"settings.categoryMenu.categories\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.categoryMenu.includeSubcategories\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n            <\\/language>\\n        <\\/sheet>\\n    <\\/data>\\n<\\/T3FlexForms>\",\"frame_class\":\"default\",\"space_before_class\":\"\",\"space_after_class\":\"\",\"sectionIndex\":1,\"linkToTop\":0,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":0,\"endtime\":0,\"fe_group\":\"\",\"editlock\":0,\"categories\":0,\"rowDescription\":\"\",\"l18n_parent\":0}',0,0,0,0,'list','','',NULL,0,0,0,0,0,0,0,2,0,0,0,'default',0,'','','','',0,'','',0,'0','sfeventmgt_pieventlist',1,0,'',0,'','','',0,0,0,'<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"settings.displayMode\">\n                    <value index=\"vDEF\">all</value>\n                </field>\n                <field index=\"settings.categoryConjunction\">\n                    <value index=\"vDEF\">OR</value>\n                </field>\n                <field index=\"settings.includeSubcategories\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.location\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.organisator\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.speaker\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.recursive\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.storagePage\">\n                    <value index=\"vDEF\">4</value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"additional\">\n            <language index=\"lDEF\">\n                <field index=\"settings.restrictForeignRecordsToStoragePage\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.disableOverrideDemand\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.detailPid\">\n                    <value index=\"vDEF\">3</value>\n                </field>\n                <field index=\"settings.registrationPid\">\n                    <value index=\"vDEF\">6</value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"categoryMenu\">\n            <language index=\"lDEF\">\n                <field index=\"settings.categoryMenu.categories\">\n                    <value index=\"vDEF\">1,2</value>\n                </field>\n                <field index=\"settings.categoryMenu.includeSubcategories\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>','',0,'','','','',NULL,124,0,0,0,0,0),(9,'',14,1586581516,1586581389,1,0,0,0,0,'',256,0,0,0,0,NULL,0,_binary '{\"CType\":null,\"colPos\":null,\"header\":null,\"header_layout\":null,\"header_position\":null,\"date\":null,\"header_link\":null,\"subheader\":null,\"pi_flexform\":null,\"layout\":null,\"frame_class\":null,\"space_before_class\":null,\"space_after_class\":null,\"sectionIndex\":null,\"linkToTop\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"editlock\":null,\"categories\":null,\"rowDescription\":null}',0,0,0,0,'felogin_login','','',NULL,0,0,0,0,0,0,0,2,0,0,0,'default',0,'','',NULL,NULL,0,'','',0,'0','',1,0,NULL,0,'','','',0,0,0,'<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"settings.showForgotPassword\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.showPermaLogin\">\n                    <value index=\"vDEF\">1</value>\n                </field>\n                <field index=\"settings.showLogoutFormAfterLogin\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.pages\">\n                    <value index=\"vDEF\">13</value>\n                </field>\n                <field index=\"settings.recursive\">\n                    <value index=\"vDEF\"></value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"s_redirect\">\n            <language index=\"lDEF\">\n                <field index=\"settings.redirectMode\">\n                    <value index=\"vDEF\">login</value>\n                </field>\n                <field index=\"settings.redirectFirstMethod\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.redirectPageLogin\">\n                    <value index=\"vDEF\">15</value>\n                </field>\n                <field index=\"settings.redirectPageLoginError\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.redirectPageLogout\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.redirectDisable\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"s_messages\">\n            <language index=\"lDEF\">\n                <field index=\"settings.welcome_header\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.welcome_message\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.success_header\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.success_message\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.error_header\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.error_message\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.status_header\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.status_message\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.logout_header\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.logout_message\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.forgot_header\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.forgot_reset_message\">\n                    <value index=\"vDEF\"></value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>','',0,'',NULL,'','',NULL,124,0,0,0,0,0),(10,'',14,1586581529,1586581524,1,0,0,0,0,'',512,0,1,9,9,NULL,9,_binary '{\"CType\":\"felogin_login\",\"colPos\":0,\"header\":\"\",\"header_layout\":\"0\",\"header_position\":\"\",\"date\":0,\"header_link\":\"\",\"subheader\":\"\",\"pi_flexform\":\"<?xml version=\\\"1.0\\\" encoding=\\\"utf-8\\\" standalone=\\\"yes\\\" ?>\\n<T3FlexForms>\\n    <data>\\n        <sheet index=\\\"sDEF\\\">\\n            <language index=\\\"lDEF\\\">\\n                <field index=\\\"settings.showForgotPassword\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.showPermaLogin\\\">\\n                    <value index=\\\"vDEF\\\">1<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.showLogoutFormAfterLogin\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.pages\\\">\\n                    <value index=\\\"vDEF\\\">13<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.recursive\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n            <\\/language>\\n        <\\/sheet>\\n        <sheet index=\\\"s_redirect\\\">\\n            <language index=\\\"lDEF\\\">\\n                <field index=\\\"settings.redirectMode\\\">\\n                    <value index=\\\"vDEF\\\">login<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.redirectFirstMethod\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.redirectPageLogin\\\">\\n                    <value index=\\\"vDEF\\\">15<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.redirectPageLoginError\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.redirectPageLogout\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.redirectDisable\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n            <\\/language>\\n        <\\/sheet>\\n        <sheet index=\\\"s_messages\\\">\\n            <language index=\\\"lDEF\\\">\\n                <field index=\\\"settings.welcome_header\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.welcome_message\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.success_header\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.success_message\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.error_header\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.error_message\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.status_header\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.status_message\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.logout_header\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.logout_message\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.forgot_header\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.forgot_reset_message\\\">\\n                    <value index=\\\"vDEF\\\"><\\/value>\\n                <\\/field>\\n            <\\/language>\\n        <\\/sheet>\\n    <\\/data>\\n<\\/T3FlexForms>\",\"layout\":0,\"frame_class\":\"default\",\"space_before_class\":\"\",\"space_after_class\":\"\",\"sectionIndex\":1,\"linkToTop\":0,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":0,\"endtime\":0,\"fe_group\":\"\",\"editlock\":0,\"categories\":0,\"rowDescription\":\"\",\"l18n_parent\":0}',0,0,0,0,'felogin_login','','',NULL,0,0,0,0,0,0,0,2,0,0,0,'default',0,'','','','',0,'','',0,'0','',1,0,'',0,'','','',0,0,0,'<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"settings.showForgotPassword\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.showPermaLogin\">\n                    <value index=\"vDEF\">1</value>\n                </field>\n                <field index=\"settings.showLogoutFormAfterLogin\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.pages\">\n                    <value index=\"vDEF\">13</value>\n                </field>\n                <field index=\"settings.recursive\">\n                    <value index=\"vDEF\"></value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"s_redirect\">\n            <language index=\"lDEF\">\n                <field index=\"settings.redirectMode\">\n                    <value index=\"vDEF\">login</value>\n                </field>\n                <field index=\"settings.redirectFirstMethod\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n                <field index=\"settings.redirectPageLogin\">\n                    <value index=\"vDEF\">15</value>\n                </field>\n                <field index=\"settings.redirectPageLoginError\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.redirectPageLogout\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.redirectDisable\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n            </language>\n        </sheet>\n        <sheet index=\"s_messages\">\n            <language index=\"lDEF\">\n                <field index=\"settings.welcome_header\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.welcome_message\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.success_header\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.success_message\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.error_header\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.error_message\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.status_header\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.status_message\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.logout_header\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.logout_message\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.forgot_header\">\n                    <value index=\"vDEF\"></value>\n                </field>\n                <field index=\"settings.forgot_reset_message\">\n                    <value index=\"vDEF\"></value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>','',0,'','','','',NULL,124,0,0,0,0,0),(11,'',15,1586581557,1586581542,1,0,0,0,0,'',256,0,0,0,0,NULL,0,_binary '{\"CType\":null,\"colPos\":null,\"header\":null,\"header_layout\":null,\"header_position\":null,\"date\":null,\"header_link\":null,\"subheader\":null,\"list_type\":null,\"pi_flexform\":null,\"frame_class\":null,\"space_before_class\":null,\"space_after_class\":null,\"sectionIndex\":null,\"linkToTop\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"editlock\":null,\"categories\":null,\"rowDescription\":null}',0,0,0,0,'list','','',NULL,0,0,0,0,0,0,0,2,0,0,0,'default',0,'','',NULL,'',0,'','',0,'0','sfeventmgt_piuserreg',1,0,NULL,0,'','','',0,0,0,'<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"settings.userRegistration.displayMode\">\n                    <value index=\"vDEF\">all</value>\n                </field>\n                <field index=\"settings.registrationPid\">\n                    <value index=\"vDEF\">6</value>\n                </field>\n                <field index=\"settings.detailPid\">\n                    <value index=\"vDEF\">3</value>\n                </field>\n                <field index=\"settings.userRegistration.storagePage\">\n                    <value index=\"vDEF\">4</value>\n                </field>\n                <field index=\"settings.userRegistration.recursive\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>','',0,'',NULL,'','',NULL,124,0,0,0,0,0),(12,'',15,1586581977,1586581971,1,0,0,0,0,'',512,0,1,11,11,NULL,11,_binary '{\"CType\":\"list\",\"colPos\":0,\"header\":\"\",\"header_layout\":\"0\",\"header_position\":\"\",\"date\":0,\"header_link\":\"\",\"subheader\":\"\",\"list_type\":\"sfeventmgt_piuserreg\",\"pi_flexform\":\"<?xml version=\\\"1.0\\\" encoding=\\\"utf-8\\\" standalone=\\\"yes\\\" ?>\\n<T3FlexForms>\\n    <data>\\n        <sheet index=\\\"sDEF\\\">\\n            <language index=\\\"lDEF\\\">\\n                <field index=\\\"settings.userRegistration.displayMode\\\">\\n                    <value index=\\\"vDEF\\\">all<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.registrationPid\\\">\\n                    <value index=\\\"vDEF\\\">6<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.detailPid\\\">\\n                    <value index=\\\"vDEF\\\">3<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.userRegistration.storagePage\\\">\\n                    <value index=\\\"vDEF\\\">4<\\/value>\\n                <\\/field>\\n                <field index=\\\"settings.userRegistration.recursive\\\">\\n                    <value index=\\\"vDEF\\\">0<\\/value>\\n                <\\/field>\\n            <\\/language>\\n        <\\/sheet>\\n    <\\/data>\\n<\\/T3FlexForms>\",\"frame_class\":\"default\",\"space_before_class\":\"\",\"space_after_class\":\"\",\"sectionIndex\":1,\"linkToTop\":0,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":0,\"endtime\":0,\"fe_group\":\"\",\"editlock\":0,\"categories\":0,\"rowDescription\":\"\",\"l18n_parent\":0}',0,0,0,0,'list','','',NULL,0,0,0,0,0,0,0,2,0,0,0,'default',0,'','','','',0,'','',0,'0','sfeventmgt_piuserreg',1,0,'',0,'','','',0,0,0,'<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"settings.userRegistration.displayMode\">\n                    <value index=\"vDEF\">all</value>\n                </field>\n                <field index=\"settings.registrationPid\">\n                    <value index=\"vDEF\">6</value>\n                </field>\n                <field index=\"settings.detailPid\">\n                    <value index=\"vDEF\">3</value>\n                </field>\n                <field index=\"settings.userRegistration.storagePage\">\n                    <value index=\"vDEF\">4</value>\n                </field>\n                <field index=\"settings.userRegistration.recursive\">\n                    <value index=\"vDEF\">0</value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>','',0,'','','','',NULL,124,0,0,0,0,0);
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
INSERT INTO `tx_sfeventmgt_domain_model_event` VALUES (1,4,'',1586418834,1586410578,1,0,0,0,0,'',0,0,0,0,0,1792,0,0,_binary '{\"title\":null,\"top_event\":null,\"slug\":null,\"startdate\":null,\"enddate\":null,\"teaser\":null,\"description\":null,\"price\":null,\"currency\":null,\"price_options\":null,\"link\":null,\"program\":null,\"location\":null,\"room\":null,\"organisator\":null,\"speaker\":null,\"related\":null,\"image\":null,\"files\":null,\"additional_image\":null,\"category\":null,\"enable_registration\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"rowDescription\":null}',NULL,'Event (no reg, cat1) [DE]','','','',1754035200,1754121600,0,1,0,'',0,0,NULL,1,0,0,0,0,'0',0,0,'0',0,'',0,0,0,'',0,0,0,1,0,0,0,0,0,'event-no-reg-cat1-de',0,0),(2,4,'',1586418704,1586410906,1,0,0,0,0,'',0,0,0,0,0,1664,0,0,_binary '{\"title\":null,\"top_event\":null,\"slug\":null,\"startdate\":null,\"enddate\":null,\"teaser\":null,\"description\":null,\"price\":null,\"currency\":null,\"price_options\":null,\"link\":null,\"program\":null,\"location\":null,\"room\":null,\"organisator\":null,\"speaker\":null,\"related\":null,\"image\":null,\"files\":null,\"additional_image\":null,\"category\":null,\"enable_registration\":null,\"registration_deadline\":null,\"enable_cancel\":null,\"max_participants\":null,\"max_registrations_per_user\":null,\"enable_autoconfirm\":null,\"enable_waitlist\":null,\"unique_email_check\":null,\"notify_admin\":null,\"notify_organisator\":null,\"registration_fields\":null,\"registration\":null,\"enable_payment\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"rowDescription\":null}',NULL,'Event (reg, cat1) [DE]','','','',1754121600,1754215200,0,1,0,'',0,0,NULL,1,1,0,0,0,'0',0,0,'0',0,'',1,0,0,'',0,0,0,1,0,0,0,0,0,'event-reg-cat1-de',0,0),(3,4,'',1586418695,1586417669,1,0,0,0,0,'',0,0,0,0,0,1600,0,0,_binary '{\"title\":null,\"top_event\":null,\"slug\":null,\"startdate\":null,\"enddate\":null,\"teaser\":null,\"description\":null,\"price\":null,\"currency\":null,\"price_options\":null,\"link\":null,\"program\":null,\"location\":null,\"room\":null,\"organisator\":null,\"speaker\":null,\"related\":null,\"image\":null,\"files\":null,\"additional_image\":null,\"category\":null,\"enable_registration\":null,\"registration_deadline\":null,\"enable_cancel\":null,\"max_participants\":null,\"max_registrations_per_user\":null,\"enable_autoconfirm\":null,\"enable_waitlist\":null,\"unique_email_check\":null,\"notify_admin\":null,\"notify_organisator\":null,\"registration_fields\":null,\"registration\":null,\"enable_payment\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"rowDescription\":null}',NULL,'Event (reg, regfields, cat1) [DE]','','','',1754294400,1754308800,0,1,0,'',0,0,NULL,1,1,0,2,0,'0',0,0,'0',0,'',1,0,0,'',0,0,0,1,0,0,0,0,0,'event-reg-regfields-cat1-de',0,0),(4,4,'',1586418686,1586418500,1,0,0,0,0,'',0,0,0,0,0,1568,0,0,_binary '{\"title\":null,\"top_event\":null,\"slug\":null,\"startdate\":null,\"enddate\":null,\"teaser\":null,\"description\":null,\"price\":null,\"currency\":null,\"price_options\":null,\"link\":null,\"program\":null,\"location\":null,\"room\":null,\"organisator\":null,\"speaker\":null,\"related\":null,\"image\":null,\"files\":null,\"additional_image\":null,\"category\":null,\"enable_registration\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"rowDescription\":null}',NULL,'Event (no reg, cat2) [DE]','','','',1754035200,1754128800,0,1,0,'',0,0,NULL,1,0,0,0,0,'0',0,0,'0',0,'',0,0,0,'',0,0,0,1,0,0,0,0,0,'event-no-reg-cat2-de',0,0),(5,4,'',1586418957,1586418920,1,0,0,0,0,'',0,0,0,0,0,1552,0,0,_binary '{\"title\":null,\"top_event\":null,\"slug\":null,\"startdate\":null,\"enddate\":null,\"teaser\":null,\"description\":null,\"price\":null,\"currency\":null,\"price_options\":null,\"link\":null,\"program\":null,\"location\":null,\"room\":null,\"organisator\":null,\"speaker\":null,\"related\":null,\"image\":null,\"files\":null,\"additional_image\":null,\"category\":null,\"enable_registration\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"rowDescription\":null}',NULL,'Expired Event (reg, cat1) [DE]','','','',1577869200,1577876400,0,1,0,'',0,0,NULL,1,0,0,0,0,'0',0,0,'0',0,'',1,0,0,'',0,0,0,1,0,0,0,0,0,'expired-event-no-reg-cat1-de',0,0),(6,4,'',1586424358,1586419216,1,0,0,0,0,'',0,0,0,0,0,1544,0,0,_binary '{\"title\":null,\"top_event\":null,\"slug\":null,\"startdate\":null,\"enddate\":null,\"teaser\":null,\"description\":null,\"price\":null,\"currency\":null,\"price_options\":null,\"link\":null,\"program\":null,\"location\":null,\"room\":null,\"organisator\":null,\"speaker\":null,\"related\":null,\"image\":null,\"files\":null,\"additional_image\":null,\"category\":null,\"enable_registration\":null,\"registration_deadline\":null,\"enable_cancel\":null,\"max_participants\":null,\"max_registrations_per_user\":null,\"enable_autoconfirm\":null,\"enable_waitlist\":null,\"unique_email_check\":null,\"notify_admin\":null,\"notify_organisator\":null,\"registration_fields\":null,\"registration\":null,\"enable_payment\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"rowDescription\":null}',NULL,'Event fully booked (reg, cat1) [DE]','','','',1754294400,1754301600,1,1,0,'',0,0,NULL,1,1,0,0,0,'0',0,0,'0',0,'',1,0,0,'',0,0,0,1,0,0,0,0,0,'event-fully-booked-reg-cat1-de',0,0),(7,4,'',1586419411,1586419352,1,0,0,0,0,'',0,0,0,0,2,1540,0,0,_binary '{\"title\":null,\"top_event\":null,\"slug\":null,\"startdate\":null,\"enddate\":null,\"teaser\":null,\"description\":null,\"price\":null,\"currency\":null,\"price_options\":null,\"link\":null,\"program\":null,\"location\":null,\"room\":null,\"organisator\":null,\"speaker\":null,\"related\":null,\"image\":null,\"files\":null,\"additional_image\":null,\"category\":null,\"enable_registration\":null,\"registration_deadline\":null,\"enable_cancel\":null,\"max_participants\":null,\"max_registrations_per_user\":null,\"enable_autoconfirm\":null,\"enable_waitlist\":null,\"unique_email_check\":null,\"notify_admin\":null,\"notify_organisator\":null,\"registration_fields\":null,\"registration\":null,\"enable_payment\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"rowDescription\":null}',NULL,'Event (reg, cat1, autoconfirm) [DE]','','','',1754467200,1754474400,0,1,0,'',0,0,'',1,1,0,0,0,'0',0,0,'0',0,'',1,0,0,'',0,0,0,1,0,0,0,1,0,'event-reg-cat1-autoconfirm-de',0,0),(8,4,'',1586429763,1586419432,1,0,0,0,0,'',0,0,0,0,6,1538,0,0,_binary '{\"title\":null,\"top_event\":null,\"slug\":null,\"startdate\":null,\"enddate\":null,\"teaser\":null,\"description\":null,\"price\":null,\"currency\":null,\"price_options\":null,\"link\":null,\"program\":null,\"location\":null,\"room\":null,\"organisator\":null,\"speaker\":null,\"related\":null,\"image\":null,\"files\":null,\"additional_image\":null,\"category\":null,\"enable_registration\":null,\"registration_deadline\":null,\"enable_cancel\":null,\"max_participants\":null,\"max_registrations_per_user\":null,\"enable_autoconfirm\":null,\"enable_waitlist\":null,\"unique_email_check\":null,\"notify_admin\":null,\"notify_organisator\":null,\"registration_fields\":null,\"registration\":null,\"registration_waitlist\":null,\"enable_payment\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"rowDescription\":null}',NULL,'Event fully booked waitlist (reg, cat1) [DE]','','','',1754294400,1754301600,1,1,0,'',0,0,'',1,1,1,0,0,'0',0,0,'0',0,'',1,1,0,'',0,0,0,1,0,0,0,0,0,'event-fully-booked-waitlist-reg-cat1-de',0,0),(9,4,'',1586429566,1586429557,1,0,0,0,0,'',0,0,0,0,1,2048,1,1,_binary '{\"title\":\"Event (no reg, cat1) [DE]\",\"top_event\":0,\"slug\":\"event-no-reg-cat1-de\",\"startdate\":1754035200,\"enddate\":1754121600,\"teaser\":\"\",\"description\":\"\",\"price\":null,\"currency\":null,\"price_options\":null,\"link\":null,\"program\":\"\",\"location\":null,\"room\":null,\"organisator\":null,\"speaker\":null,\"related\":null,\"image\":null,\"files\":null,\"additional_image\":null,\"category\":null,\"enable_registration\":0,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"rowDescription\":\"\",\"l10n_parent\":0,\"enable_waitlist\":0,\"registration_deadline\":0,\"enable_cancel\":0,\"cancel_deadline\":0,\"enable_autoconfirm\":0,\"max_participants\":0,\"max_registrations_per_user\":1,\"notify_admin\":1,\"notify_organisator\":0,\"unique_email_check\":0}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"fe_group\":\"parent\",\"link\":\"parent\",\"price\":\"parent\",\"currency\":\"parent\",\"enable_payment\":\"parent\",\"restrict_payment_methods\":\"parent\",\"selected_payment_methods\":\"parent\",\"location\":\"parent\",\"room\":\"parent\",\"organisator\":\"parent\",\"speaker\":\"parent\",\"image\":\"parent\",\"files\":\"parent\",\"related\":\"parent\",\"additional_image\":\"parent\",\"registration_fields\":\"parent\",\"price_options\":\"parent\",\"category\":\"parent\"}','Event (no reg, cat1) [EN]','','','',1754035200,1754121600,0,1,0,'',0,0,'',1,0,0,0,0,'',0,0,'',0,'',0,0,0,'',0,0,0,1,0,0,0,0,0,'event-no-reg-cat1-en',0,0),(10,4,'',1586429580,1586429571,1,0,0,0,0,'',0,0,0,0,2,1728,1,2,_binary '{\"title\":\"Event (reg, cat1) [DE]\",\"top_event\":0,\"slug\":\"event-reg-cat1-de\",\"startdate\":1754121600,\"enddate\":1754215200,\"teaser\":\"\",\"description\":\"\",\"price\":null,\"currency\":null,\"price_options\":null,\"link\":null,\"program\":\"\",\"location\":null,\"room\":null,\"organisator\":null,\"speaker\":null,\"related\":null,\"image\":null,\"files\":null,\"additional_image\":null,\"category\":null,\"enable_registration\":1,\"registration_deadline\":0,\"enable_cancel\":0,\"max_participants\":0,\"max_registrations_per_user\":1,\"enable_autoconfirm\":0,\"enable_waitlist\":0,\"unique_email_check\":0,\"notify_admin\":1,\"notify_organisator\":0,\"registration_fields\":null,\"registration\":0,\"enable_payment\":null,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"rowDescription\":\"\",\"l10n_parent\":0,\"cancel_deadline\":0}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"fe_group\":\"parent\",\"link\":\"parent\",\"price\":\"parent\",\"currency\":\"parent\",\"enable_payment\":\"parent\",\"restrict_payment_methods\":\"parent\",\"selected_payment_methods\":\"parent\",\"location\":\"parent\",\"room\":\"parent\",\"organisator\":\"parent\",\"speaker\":\"parent\",\"image\":\"parent\",\"files\":\"parent\",\"related\":\"parent\",\"additional_image\":\"parent\",\"registration_fields\":\"parent\",\"price_options\":\"parent\",\"category\":\"parent\"}','Event (reg, cat1) [EN]','','','',1754121600,1754215200,0,1,0,'',0,0,'',1,1,0,0,0,'',0,0,'',0,'',1,0,0,'',0,0,0,1,0,0,0,0,0,'event-reg-cat1-en',0,0),(11,4,'',1586429593,1586429584,1,0,0,0,0,'',0,0,0,0,3,1632,1,3,_binary '{\"title\":\"Event (reg, regfields, cat1) [DE]\",\"top_event\":0,\"slug\":\"event-reg-regfields-cat1-de\",\"startdate\":1754294400,\"enddate\":1754308800,\"teaser\":\"\",\"description\":\"\",\"price\":null,\"currency\":null,\"price_options\":null,\"link\":null,\"program\":\"\",\"location\":null,\"room\":null,\"organisator\":null,\"speaker\":null,\"related\":null,\"image\":null,\"files\":null,\"additional_image\":null,\"category\":null,\"enable_registration\":1,\"registration_deadline\":0,\"enable_cancel\":0,\"max_participants\":0,\"max_registrations_per_user\":1,\"enable_autoconfirm\":0,\"enable_waitlist\":0,\"unique_email_check\":0,\"notify_admin\":1,\"notify_organisator\":0,\"registration_fields\":null,\"registration\":0,\"enable_payment\":null,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"rowDescription\":\"\",\"l10n_parent\":0,\"cancel_deadline\":0}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"fe_group\":\"parent\",\"link\":\"parent\",\"price\":\"parent\",\"currency\":\"parent\",\"enable_payment\":\"parent\",\"restrict_payment_methods\":\"parent\",\"selected_payment_methods\":\"parent\",\"location\":\"parent\",\"room\":\"parent\",\"organisator\":\"parent\",\"speaker\":\"parent\",\"image\":\"parent\",\"files\":\"parent\",\"related\":\"parent\",\"additional_image\":\"parent\",\"registration_fields\":\"parent\",\"price_options\":\"parent\",\"category\":\"parent\"}','Event (reg, regfields, cat1) [EN]','','','',1754294400,1754308800,0,1,0,'',0,0,'',1,1,0,2,0,'',0,0,'',0,'',1,0,0,'',0,0,0,1,0,0,0,0,0,'event-reg-regfields-cat1-en',0,0),(12,4,'',1586429630,1586429621,1,0,0,0,0,'',0,0,0,0,4,1584,1,4,_binary '{\"title\":\"Event (no reg, cat2) [DE]\",\"top_event\":0,\"slug\":\"event-no-reg-cat2-de\",\"startdate\":1754035200,\"enddate\":1754128800,\"teaser\":\"\",\"description\":\"\",\"price\":null,\"currency\":null,\"price_options\":null,\"link\":null,\"program\":\"\",\"location\":null,\"room\":null,\"organisator\":null,\"speaker\":null,\"related\":null,\"image\":null,\"files\":null,\"additional_image\":null,\"category\":null,\"enable_registration\":0,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"rowDescription\":\"\",\"l10n_parent\":0,\"enable_waitlist\":0,\"registration_deadline\":0,\"enable_cancel\":0,\"cancel_deadline\":0,\"enable_autoconfirm\":0,\"max_participants\":0,\"max_registrations_per_user\":1,\"notify_admin\":1,\"notify_organisator\":0,\"unique_email_check\":0}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"fe_group\":\"parent\",\"link\":\"parent\",\"price\":\"parent\",\"currency\":\"parent\",\"enable_payment\":\"parent\",\"restrict_payment_methods\":\"parent\",\"selected_payment_methods\":\"parent\",\"location\":\"parent\",\"room\":\"parent\",\"organisator\":\"parent\",\"speaker\":\"parent\",\"image\":\"parent\",\"files\":\"parent\",\"related\":\"parent\",\"additional_image\":\"parent\",\"registration_fields\":\"parent\",\"price_options\":\"parent\",\"category\":\"parent\"}','Event (no reg, cat2) [EN]','','','',1754035200,1754128800,0,1,0,'',0,0,'',1,0,0,0,0,'',0,0,'',0,'',0,0,0,'',0,0,0,1,0,0,0,0,0,'event-no-reg-cat2-en',0,0),(13,4,'',1586429650,1586429641,1,0,0,0,0,'',0,0,0,0,5,1560,1,5,_binary '{\"title\":\"Expired Event (reg, cat1) [DE]\",\"top_event\":0,\"slug\":\"expired-event-no-reg-cat1-de\",\"startdate\":1577869200,\"enddate\":1577876400,\"teaser\":\"\",\"description\":\"\",\"price\":null,\"currency\":null,\"price_options\":null,\"link\":null,\"program\":\"\",\"location\":null,\"room\":null,\"organisator\":null,\"speaker\":null,\"related\":null,\"image\":null,\"files\":null,\"additional_image\":null,\"category\":null,\"enable_registration\":1,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"rowDescription\":\"\",\"registration\":0,\"l10n_parent\":0,\"enable_waitlist\":0,\"registration_deadline\":0,\"enable_cancel\":0,\"cancel_deadline\":0,\"enable_autoconfirm\":0,\"max_participants\":0,\"max_registrations_per_user\":1,\"notify_admin\":1,\"notify_organisator\":0,\"unique_email_check\":0}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"fe_group\":\"parent\",\"link\":\"parent\",\"price\":\"parent\",\"currency\":\"parent\",\"enable_payment\":\"parent\",\"restrict_payment_methods\":\"parent\",\"selected_payment_methods\":\"parent\",\"location\":\"parent\",\"room\":\"parent\",\"organisator\":\"parent\",\"speaker\":\"parent\",\"image\":\"parent\",\"files\":\"parent\",\"related\":\"parent\",\"additional_image\":\"parent\",\"registration_fields\":\"parent\",\"price_options\":\"parent\",\"category\":\"parent\"}','Expired Event (reg, cat1) [EN]','','','',1577869200,1577876400,0,1,0,'',0,0,'',1,0,0,0,0,'',0,0,'',0,'',1,0,0,'',0,0,0,1,0,0,0,0,0,'expired-event-reg-cat1-en',0,0),(14,4,'',1586429722,1586429655,1,0,0,0,0,'',0,0,0,0,6,1548,1,6,_binary '{\"title\":\"Event fully booked (reg, cat1) [DE]\",\"top_event\":0,\"slug\":\"event-fully-booked-reg-cat1-de\",\"startdate\":1754294400,\"enddate\":1754301600,\"teaser\":\"\",\"description\":\"\",\"price\":null,\"currency\":null,\"price_options\":null,\"link\":null,\"program\":\"\",\"location\":null,\"room\":null,\"organisator\":null,\"speaker\":null,\"related\":null,\"image\":null,\"files\":null,\"additional_image\":null,\"category\":null,\"enable_registration\":1,\"registration_deadline\":0,\"enable_cancel\":0,\"max_participants\":1,\"max_registrations_per_user\":1,\"enable_autoconfirm\":0,\"enable_waitlist\":0,\"unique_email_check\":0,\"notify_admin\":1,\"notify_organisator\":0,\"registration_fields\":null,\"registration\":1,\"enable_payment\":null,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"rowDescription\":\"\",\"l10n_parent\":0,\"cancel_deadline\":0}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"fe_group\":\"parent\",\"link\":\"parent\",\"price\":\"parent\",\"currency\":\"parent\",\"enable_payment\":\"parent\",\"restrict_payment_methods\":\"parent\",\"selected_payment_methods\":\"parent\",\"location\":\"parent\",\"room\":\"parent\",\"organisator\":\"parent\",\"speaker\":\"parent\",\"image\":\"parent\",\"files\":\"parent\",\"related\":\"parent\",\"additional_image\":\"parent\",\"registration_fields\":\"parent\",\"price_options\":\"parent\",\"category\":\"parent\"}','Event fully booked (reg, cat1) [EN]','','','',1754294400,1754301600,1,1,0,'',0,0,'',1,0,0,0,0,'',0,0,'',0,'',1,0,0,'',0,0,0,1,0,0,0,0,0,'event-fully-booked-reg-cat1-en',0,0),(15,4,'',1586429737,1586429730,1,0,0,0,0,'',0,0,0,0,7,1542,1,7,_binary '{\"title\":\"Event (reg, cat1, autoconfirm) [DE]\",\"top_event\":0,\"slug\":\"event-reg-cat1-autoconfirm-de\",\"startdate\":1754467200,\"enddate\":1754474400,\"teaser\":\"\",\"description\":\"\",\"price\":null,\"currency\":null,\"price_options\":null,\"link\":null,\"program\":\"\",\"location\":null,\"room\":null,\"organisator\":null,\"speaker\":null,\"related\":null,\"image\":null,\"files\":null,\"additional_image\":null,\"category\":null,\"enable_registration\":1,\"registration_deadline\":0,\"enable_cancel\":0,\"max_participants\":0,\"max_registrations_per_user\":1,\"enable_autoconfirm\":1,\"enable_waitlist\":0,\"unique_email_check\":0,\"notify_admin\":1,\"notify_organisator\":0,\"registration_fields\":null,\"registration\":0,\"enable_payment\":null,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"rowDescription\":\"\",\"l10n_parent\":0,\"cancel_deadline\":0}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"fe_group\":\"parent\",\"link\":\"parent\",\"price\":\"parent\",\"currency\":\"parent\",\"enable_payment\":\"parent\",\"restrict_payment_methods\":\"parent\",\"selected_payment_methods\":\"parent\",\"location\":\"parent\",\"room\":\"parent\",\"organisator\":\"parent\",\"speaker\":\"parent\",\"image\":\"parent\",\"files\":\"parent\",\"related\":\"parent\",\"additional_image\":\"parent\",\"registration_fields\":\"parent\",\"price_options\":\"parent\",\"category\":\"parent\"}','Event (reg, cat1, autoconfirm) [EN]','','','',1754467200,1754474400,0,1,0,'',0,0,'',1,1,0,0,0,'',0,0,'',0,'',1,0,0,'',0,0,0,1,0,0,0,1,0,'event-reg-cat1-autoconfirm-en',0,0),(16,4,'',1586429763,1586429741,1,0,0,0,0,'',0,0,0,0,8,1539,1,8,_binary '{\"title\":\"Event fully booked waitlist (reg, cat1) [DE]\",\"top_event\":0,\"slug\":\"event-fully-booked-reg-cat1-1\",\"startdate\":1754294400,\"enddate\":1754301600,\"teaser\":\"\",\"description\":\"\",\"price\":0,\"currency\":\"\",\"price_options\":0,\"link\":\"\",\"program\":\"\",\"location\":0,\"room\":\"\",\"organisator\":0,\"speaker\":0,\"related\":0,\"image\":\"0\",\"files\":0,\"additional_image\":\"0\",\"category\":1,\"enable_registration\":1,\"registration_deadline\":0,\"enable_cancel\":0,\"max_participants\":1,\"max_registrations_per_user\":1,\"enable_autoconfirm\":0,\"enable_waitlist\":1,\"unique_email_check\":0,\"notify_admin\":1,\"notify_organisator\":0,\"registration_fields\":0,\"registration\":1,\"registration_waitlist\":0,\"enable_payment\":0,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":0,\"endtime\":0,\"fe_group\":\"\",\"rowDescription\":\"\",\"l10n_parent\":0,\"cancel_deadline\":0,\"restrict_payment_methods\":0,\"selected_payment_methods\":\"\"}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"fe_group\":\"parent\",\"link\":\"parent\",\"price\":\"parent\",\"currency\":\"parent\",\"enable_payment\":\"parent\",\"restrict_payment_methods\":\"parent\",\"selected_payment_methods\":\"parent\",\"location\":\"parent\",\"room\":\"parent\",\"organisator\":\"parent\",\"speaker\":\"parent\",\"image\":\"parent\",\"files\":\"parent\",\"related\":\"parent\",\"additional_image\":\"parent\",\"registration_fields\":\"parent\",\"price_options\":\"parent\",\"category\":\"parent\"}','Event fully booked waitlist (reg, cat1) [EN]','','','',1754294400,1754301600,1,1,0,'',0,0,'',1,1,1,0,0,'0',0,0,'0',0,'',1,1,0,'',0,0,0,1,0,0,0,0,0,'event-fully-booked-waitlist-reg-cat1-en',0,0),(17,4,'',1586455110,1586455055,1,0,0,0,0,'',0,0,0,0,0,1025,0,0,_binary '{\"title\":null,\"top_event\":null,\"slug\":null,\"startdate\":null,\"enddate\":null,\"teaser\":null,\"description\":null,\"price\":null,\"currency\":null,\"price_options\":null,\"link\":null,\"program\":null,\"location\":null,\"room\":null,\"organisator\":null,\"speaker\":null,\"related\":null,\"image\":null,\"files\":null,\"additional_image\":null,\"category\":null,\"enable_registration\":null,\"registration_deadline\":null,\"enable_cancel\":null,\"max_participants\":null,\"max_registrations_per_user\":null,\"enable_autoconfirm\":null,\"enable_waitlist\":null,\"unique_email_check\":null,\"notify_admin\":null,\"notify_organisator\":null,\"registration_fields\":null,\"registration\":null,\"enable_payment\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"rowDescription\":null}',NULL,'Event (reg, cat3, autoconfirm) [DE]','','','',1754467200,1754467200,0,1,0,'',0,0,NULL,1,1,0,0,0,'0',0,0,'0',0,'',1,0,0,'',0,0,0,1,0,0,0,1,0,'event-reg-cat3-autoconfirm-de',0,0),(18,4,'',1586455132,1586455122,1,0,0,0,0,'',0,0,0,0,17,1281,1,17,_binary '{\"title\":\"Event (reg, cat3, autoconfirm) [DE]\",\"top_event\":0,\"slug\":\"event-reg-cat3-autoconfirm-de\",\"startdate\":1754467200,\"enddate\":1754467200,\"teaser\":\"\",\"description\":\"\",\"price\":null,\"currency\":null,\"price_options\":null,\"link\":null,\"program\":\"\",\"location\":null,\"room\":null,\"organisator\":null,\"speaker\":null,\"related\":null,\"image\":null,\"files\":null,\"additional_image\":null,\"category\":null,\"enable_registration\":1,\"registration_deadline\":0,\"enable_cancel\":0,\"max_participants\":0,\"max_registrations_per_user\":1,\"enable_autoconfirm\":1,\"enable_waitlist\":0,\"unique_email_check\":0,\"notify_admin\":1,\"notify_organisator\":0,\"registration_fields\":null,\"registration\":0,\"enable_payment\":null,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"rowDescription\":\"\",\"l10n_parent\":0,\"cancel_deadline\":0}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"fe_group\":\"parent\",\"link\":\"parent\",\"price\":\"parent\",\"currency\":\"parent\",\"enable_payment\":\"parent\",\"restrict_payment_methods\":\"parent\",\"selected_payment_methods\":\"parent\",\"location\":\"parent\",\"room\":\"parent\",\"organisator\":\"parent\",\"speaker\":\"parent\",\"image\":\"parent\",\"files\":\"parent\",\"related\":\"parent\",\"additional_image\":\"parent\",\"registration_fields\":\"parent\",\"price_options\":\"parent\",\"category\":\"parent\"}','Event (reg, cat3, autoconfirm) [EN]','','','',1754467200,1754467200,0,1,0,'',0,0,'',1,1,0,0,0,'',0,0,'',0,'',1,0,0,'',0,0,0,1,0,0,0,1,0,'event-reg-cat3-autoconfirm-en',0,0),(19,4,'',1608446456,1586582042,1,0,0,0,0,'',0,0,0,0,0,512,0,0,_binary '{\"title\":null,\"top_event\":null,\"slug\":null,\"startdate\":null,\"enddate\":null,\"teaser\":null,\"description\":null,\"price\":null,\"currency\":null,\"price_options\":null,\"link\":null,\"program\":null,\"location\":null,\"room\":null,\"organisator\":null,\"speaker\":null,\"related\":null,\"image\":null,\"files\":null,\"additional_image\":null,\"category\":null,\"enable_registration\":null,\"registration_startdate\":null,\"registration_deadline\":null,\"enable_cancel\":null,\"cancel_deadline\":null,\"max_participants\":null,\"max_registrations_per_user\":null,\"enable_waitlist\":null,\"enable_waitlist_moveup\":null,\"enable_autoconfirm\":null,\"unique_email_check\":null,\"notify_admin\":null,\"notify_organisator\":null,\"registration_fields\":null,\"registration\":null,\"enable_payment\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"rowDescription\":null}',NULL,'Expired Event (cat1, fe_user: user1) [DE]','','','',1577869200,1577872800,0,1,0,'',0,0,NULL,0,1,0,0,0,'0',0,0,'0',0,'',1,0,0,'',0,0,0,1,0,1,0,0,0,'expired-event-cat1-fe-user-user1-de',0,0),(20,4,'',1608446456,1586582466,1,0,0,0,0,'',0,0,0,0,19,768,1,19,_binary '{\"title\":\"Expired Event (cat1, fe_user: user1) [DE]\",\"top_event\":0,\"slug\":\"expired-event-cat1-fe-user-user1-de\",\"startdate\":1577869200,\"enddate\":1577872800,\"teaser\":\"\",\"description\":\"\",\"price\":0,\"currency\":\"\",\"price_options\":0,\"link\":\"\",\"program\":\"\",\"location\":0,\"room\":\"\",\"organisator\":0,\"speaker\":0,\"related\":0,\"image\":\"0\",\"files\":0,\"additional_image\":\"0\",\"category\":0,\"enable_registration\":1,\"registration_deadline\":0,\"enable_cancel\":1,\"cancel_deadline\":0,\"max_participants\":0,\"max_registrations_per_user\":1,\"enable_autoconfirm\":0,\"enable_waitlist\":0,\"unique_email_check\":0,\"notify_admin\":1,\"notify_organisator\":0,\"registration_fields\":0,\"registration\":1,\"enable_payment\":0,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":0,\"endtime\":0,\"fe_group\":\"\",\"rowDescription\":\"\",\"l10n_parent\":0,\"restrict_payment_methods\":0,\"selected_payment_methods\":null,\"enable_waitlist_moveup\":0,\"registration_startdate\":0}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"fe_group\":\"parent\",\"link\":\"parent\",\"price\":\"parent\",\"currency\":\"parent\",\"enable_payment\":\"parent\",\"restrict_payment_methods\":\"parent\",\"selected_payment_methods\":\"parent\",\"location\":\"parent\",\"room\":\"parent\",\"organisator\":\"parent\",\"speaker\":\"parent\",\"image\":\"parent\",\"files\":\"parent\",\"related\":\"parent\",\"additional_image\":\"parent\",\"registration_fields\":\"parent\",\"price_options\":\"parent\",\"category\":\"parent\"}','Expired Event (cat1, fe_user: user1) [EN]','','','',1577869200,1577872800,0,1,0,'',0,0,'',0,0,0,0,0,'0',0,0,'0',0,'',1,0,0,'',0,0,0,1,0,1,0,0,0,'expired-event-cat1-fe-user-user1-en',0,0),(21,4,'',1592656217,1587531096,1,0,0,0,0,'',0,0,0,0,0,256,0,0,_binary '{\"title\":null,\"top_event\":null,\"slug\":null,\"startdate\":null,\"enddate\":null,\"teaser\":null,\"description\":null,\"price\":null,\"currency\":null,\"price_options\":null,\"link\":null,\"program\":null,\"location\":null,\"room\":null,\"organisator\":null,\"speaker\":null,\"related\":null,\"image\":null,\"files\":null,\"additional_image\":null,\"category\":null,\"enable_registration\":null,\"registration_deadline\":null,\"enable_cancel\":null,\"max_participants\":null,\"max_registrations_per_user\":null,\"enable_autoconfirm\":null,\"enable_waitlist\":null,\"unique_email_check\":null,\"notify_admin\":null,\"notify_organisator\":null,\"registration_fields\":null,\"registration\":null,\"enable_payment\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"rowDescription\":null}',NULL,'Expired Event (reg, cat1, multireg) [DE]','','','',1748764800,1748858400,0,3,0,'',0,0,NULL,0,1,0,0,0,'0',0,0,'0',0,'',1,0,0,'',0,0,0,1,0,0,0,0,0,'expired-event-reg-cat1-multireg-de',0,0),(22,4,'',1592656217,1587531123,1,0,0,0,0,'',0,0,0,0,21,384,1,21,_binary '{\"title\":\"Expired Event (reg, cat1, multireg) [DE]\",\"top_event\":0,\"slug\":\"expired-event-reg-cat1-multireg-de\",\"startdate\":1748764800,\"enddate\":1748858400,\"teaser\":\"\",\"description\":\"\",\"price\":0,\"currency\":\"\",\"price_options\":0,\"link\":\"\",\"program\":\"\",\"location\":0,\"room\":\"\",\"organisator\":0,\"speaker\":0,\"related\":0,\"image\":\"0\",\"files\":0,\"additional_image\":\"0\",\"category\":0,\"enable_registration\":1,\"registration_deadline\":0,\"enable_cancel\":0,\"max_participants\":0,\"max_registrations_per_user\":3,\"enable_autoconfirm\":0,\"enable_waitlist\":0,\"unique_email_check\":0,\"notify_admin\":1,\"notify_organisator\":0,\"registration_fields\":0,\"registration\":0,\"enable_payment\":0,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":0,\"endtime\":0,\"fe_group\":\"\",\"rowDescription\":\"\",\"l10n_parent\":0,\"cancel_deadline\":0,\"restrict_payment_methods\":0,\"selected_payment_methods\":null}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"fe_group\":\"parent\",\"link\":\"parent\",\"price\":\"parent\",\"currency\":\"parent\",\"enable_payment\":\"parent\",\"restrict_payment_methods\":\"parent\",\"selected_payment_methods\":\"parent\",\"location\":\"parent\",\"room\":\"parent\",\"organisator\":\"parent\",\"speaker\":\"parent\",\"image\":\"parent\",\"files\":\"parent\",\"related\":\"parent\",\"additional_image\":\"parent\",\"registration_fields\":\"parent\",\"price_options\":\"parent\",\"category\":\"parent\"}','Expired Event (reg, cat1, multireg) [EN]','','','',1748764800,1748858400,0,3,0,'',0,0,'',0,1,0,0,0,'0',0,0,'0',0,'',1,0,0,'',0,0,0,1,0,0,0,0,0,'expired-event-reg-cat1-multireg-en',0,0),(23,4,'',1609137784,1608471394,1,0,0,0,0,'',0,0,0,0,0,128,0,0,_binary '{\"title\":null,\"top_event\":null,\"slug\":null,\"startdate\":null,\"enddate\":null,\"teaser\":null,\"description\":null,\"price\":null,\"currency\":null,\"price_options\":null,\"link\":null,\"program\":null,\"location\":null,\"room\":null,\"organisator\":null,\"speaker\":null,\"related\":null,\"image\":null,\"files\":null,\"additional_image\":null,\"category\":null,\"enable_registration\":null,\"registration_startdate\":null,\"registration_deadline\":null,\"enable_cancel\":null,\"max_participants\":null,\"max_registrations_per_user\":null,\"enable_waitlist\":null,\"enable_autoconfirm\":null,\"unique_email_check\":null,\"notify_admin\":null,\"notify_organisator\":null,\"registration_fields\":null,\"registration\":null,\"enable_payment\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"rowDescription\":null}',NULL,'Expired Event (location: 1, fe_user: user1) [DE]','','','',1602316800,1602324000,0,1,0,'',0,0,NULL,0,1,0,0,0,'0',0,0,'0',1,'',1,0,0,'',0,0,0,1,0,0,0,0,0,'expired-event-location-1-fe-user-user1-de',0,0),(24,4,'',1609137784,1608471405,1,0,0,0,0,'',0,0,0,0,23,192,1,23,_binary '{\"title\":\"Expired Event (location: 1, fe_user: user1) [DE]\",\"top_event\":0,\"slug\":\"expired-event-location-1-fe-user-user1-de\",\"startdate\":1602316800,\"enddate\":1602324000,\"teaser\":\"\",\"description\":\"\",\"price\":0,\"currency\":\"\",\"price_options\":0,\"link\":\"\",\"program\":\"\",\"location\":1,\"room\":\"\",\"organisator\":0,\"speaker\":0,\"related\":0,\"image\":\"0\",\"files\":0,\"additional_image\":\"0\",\"category\":0,\"enable_registration\":1,\"registration_startdate\":0,\"registration_deadline\":0,\"enable_cancel\":0,\"max_participants\":0,\"max_registrations_per_user\":1,\"enable_waitlist\":0,\"enable_autoconfirm\":0,\"unique_email_check\":0,\"notify_admin\":1,\"notify_organisator\":0,\"registration_fields\":0,\"registration\":1,\"enable_payment\":0,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":0,\"endtime\":0,\"fe_group\":\"\",\"rowDescription\":\"\",\"l10n_parent\":0,\"enable_waitlist_moveup\":0,\"cancel_deadline\":0,\"restrict_payment_methods\":0,\"selected_payment_methods\":null}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"fe_group\":\"parent\",\"link\":\"parent\",\"price\":\"parent\",\"currency\":\"parent\",\"enable_payment\":\"parent\",\"restrict_payment_methods\":\"parent\",\"selected_payment_methods\":\"parent\",\"location\":\"parent\",\"room\":\"parent\",\"organisator\":\"parent\",\"speaker\":\"parent\",\"image\":\"parent\",\"files\":\"parent\",\"related\":\"parent\",\"additional_image\":\"parent\",\"registration_fields\":\"parent\",\"price_options\":\"parent\",\"category\":\"parent\"}','Expired Event (location: 1, fe_user: user1) [EN]','','','',1602316800,1602324000,0,1,0,'',0,0,'',0,0,0,0,0,'0',0,0,'0',1,'',1,0,0,'',0,0,0,1,0,0,0,0,0,'expired-event-location-1-fe-user-user1-en',0,0);
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
INSERT INTO `tx_sfeventmgt_domain_model_location` VALUES (1,4,1609137585,1586410453,1,0,0,0,0,0,0,0,0,0,256,0,0,_binary '{\"title\":null,\"slug\":null,\"address\":null,\"zip\":null,\"city\":null,\"country\":null,\"description\":null,\"link\":null,\"latitude\":null,\"longitude\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null}',NULL,'Event Location 1 [DE]','','','','','','',0.000000,0.000000,'event-location-1-de'),(2,4,1609137597,1586410461,1,0,0,0,0,0,0,0,0,0,512,0,0,_binary '{\"title\":null,\"slug\":null,\"address\":null,\"zip\":null,\"city\":null,\"country\":null,\"description\":null,\"link\":null,\"latitude\":null,\"longitude\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null}',NULL,'Event Location 2 [DE]','','','','','','',0.000000,0.000000,'event-location-2-de'),(3,4,1609137611,1609137604,1,0,0,0,0,0,0,0,0,1,384,1,1,_binary '{\"title\":\"Event Location 1 [DE]\",\"slug\":\"event-location-1-de\",\"address\":null,\"zip\":\"\",\"city\":null,\"country\":null,\"description\":null,\"link\":null,\"latitude\":\"0.000000\",\"longitude\":\"0.000000\",\"sys_language_uid\":0,\"hidden\":0,\"starttime\":null,\"endtime\":null,\"l10n_parent\":0}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"address\":\"parent\",\"city\":\"parent\",\"country\":\"parent\",\"description\":\"parent\",\"link\":\"parent\"}','Event Location 1 [EN]','','','','','','',0.000000,0.000000,'event-location-1-en'),(4,4,1609137625,1609137615,1,0,0,0,0,0,0,0,0,2,448,1,2,_binary '{\"title\":\"Event Location 2 [DE]\",\"slug\":\"event-location-2-de\",\"address\":null,\"zip\":\"\",\"city\":null,\"country\":null,\"description\":null,\"link\":null,\"latitude\":\"0.000000\",\"longitude\":\"0.000000\",\"sys_language_uid\":0,\"hidden\":0,\"starttime\":null,\"endtime\":null,\"l10n_parent\":0}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"address\":\"parent\",\"city\":\"parent\",\"country\":\"parent\",\"description\":\"parent\",\"link\":\"parent\"}','Event Location 2 [EN]','','','','','','',0.000000,0.000000,'event-location-2-en');
/*!40000 ALTER TABLE `tx_sfeventmgt_domain_model_location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tx_sfeventmgt_domain_model_organisator`
--

LOCK TABLES `tx_sfeventmgt_domain_model_organisator` WRITE;
/*!40000 ALTER TABLE `tx_sfeventmgt_domain_model_organisator` DISABLE KEYS */;
INSERT INTO `tx_sfeventmgt_domain_model_organisator` VALUES (1,4,1586410486,1586410486,1,0,0,0,0,0,0,0,0,0,256,0,0,_binary '{\"name\":null,\"slug\":null,\"email\":null,\"email_signature\":null,\"phone\":null,\"image\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null}',NULL,'Organisator 1','organisator1@sfeventmgt.local','','','','organisator-1'),(2,4,1586410509,1586410509,1,0,0,0,0,0,0,0,0,0,128,0,0,_binary '{\"name\":null,\"slug\":null,\"email\":null,\"email_signature\":null,\"phone\":null,\"image\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null}',NULL,'Organisator 2','organisator2@sfeventmgt.local','','','','organisator-2');
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
INSERT INTO `tx_sfeventmgt_domain_model_registration` VALUES (1,4,1586424358,1586419253,1,0,0,0,0,0,1537,6,0,'','Firstname','Lastname','','','','','','','','user1@sfeventmgt.local',0,'',0,1,'',0,1586418889,1,'',0,0,'','',0,0,NULL),(2,4,1586429763,1586419432,1,0,0,0,0,1,1537,8,0,'','Firstname','Lastname','','','','','','','','user1@sfeventmgt.local',0,'',0,1,'',0,1586418889,1,'',0,0,'','',0,0,NULL),(203,4,1608446456,1586582305,1,0,0,0,0,0,1537,19,0,'','user1','user1','','','','','','','','user1@sfeventmgt.local',0,'',0,1,'',0,1586418889,1,'',1,0,'','',0,0,NULL),(220,4,1609137784,1609137340,1,0,0,0,0,0,1,23,0,'','user1','user1','','','','','','','','user1@sfeventmgt.local',0,'',0,1,'',0,1608964001,1,'',1,0,'','',0,0,0),(382,4,1640177099,1640177099,0,0,0,0,0,0,0,2,0,'de','John','Doe','','TYPO3','','','','','','event-uid-test-de@sfeventmgt.local',0,'',0,0,'',NULL,1640180699,1,'',0,0,'','',0,0,1640177099);
/*!40000 ALTER TABLE `tx_sfeventmgt_domain_model_registration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tx_sfeventmgt_domain_model_registration_field`
--

LOCK TABLES `tx_sfeventmgt_domain_model_registration_field` WRITE;
/*!40000 ALTER TABLE `tx_sfeventmgt_domain_model_registration_field` DISABLE KEYS */;
INSERT INTO `tx_sfeventmgt_domain_model_registration_field` VALUES (1,4,1586418695,1586417697,1,0,0,0,0,'',0,0,0,0,0,1,0,0,_binary '{\"title\":null,\"type\":null,\"required\":null,\"placeholder\":null,\"default_value\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"fe_group\":null}',NULL,'Input field [DE]','input',0,'','',NULL,NULL,0,3),(2,4,1586418695,1586417697,1,0,0,0,0,'',0,0,0,0,0,514,0,0,_binary '{\"title\":null,\"type\":null,\"required\":null,\"placeholder\":null,\"default_value\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null,\"fe_group\":null}',NULL,'Input field (req) [DE]','input',1,'','',NULL,NULL,0,3),(3,4,1586429609,1586429584,1,0,0,0,0,'',0,0,0,0,1,1,1,1,_binary '{\"title\":\"Input field [DE]\",\"type\":\"input\",\"required\":0,\"placeholder\":null,\"default_value\":null,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"l10n_parent\":0}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"fe_group\":\"parent\",\"settings\":\"parent\",\"placeholder\":\"parent\",\"default_value\":\"parent\"}','Input field [EN]','input',0,'','',NULL,NULL,0,11),(4,4,1586429609,1586429584,1,0,0,0,0,'',0,0,0,0,2,2,1,2,_binary '{\"title\":\"Input field (req) [DE]\",\"type\":\"input\",\"required\":1,\"placeholder\":null,\"default_value\":null,\"sys_language_uid\":0,\"hidden\":0,\"starttime\":null,\"endtime\":null,\"fe_group\":null,\"l10n_parent\":0}','{\"starttime\":\"parent\",\"endtime\":\"parent\",\"fe_group\":\"parent\",\"settings\":\"parent\",\"placeholder\":\"parent\",\"default_value\":\"parent\"}','Input field (req) [EN]','input',1,'','',NULL,NULL,0,11);
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
INSERT INTO `tx_sfeventmgt_domain_model_speaker` VALUES (1,4,1586410523,1586410523,1,0,0,0,0,0,0,0,0,0,256,0,0,_binary '{\"name\":null,\"job_title\":null,\"slug\":null,\"description\":null,\"image\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null}',NULL,'Speaker 1','','',0,'speaker-1'),(2,4,1586410530,1586410530,1,0,0,0,0,0,0,0,0,0,128,0,0,_binary '{\"name\":null,\"job_title\":null,\"slug\":null,\"description\":null,\"image\":null,\"sys_language_uid\":null,\"hidden\":null,\"starttime\":null,\"endtime\":null}',NULL,'Speaker 2','','',0,'speaker-2');
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

-- Dump completed on 2022-12-16 17:49:57
