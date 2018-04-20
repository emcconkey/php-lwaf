--
-- INSTALL: You must change the password of the database user below
-- You should change the database name and the user name. If you do change the username, look below in the file
-- where the views are created and change the user name there as well.
--

SET AUTOCOMMIT = 0;
START TRANSACTION;

CREATE DATABASE `php-lwaf`;
GRANT ALL ON `php-lwaf`.* TO `php-lwaf`@`%` IDENTIFIED BY 'CHANGEME - seriously you need to change this!';
FLUSH PRIVILEGES;

USE `php-lwaf`;

DROP TABLE IF EXISTS `event_trigger`;
CREATE TABLE `event_trigger` (
  `event_trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `event` varchar(255) NOT NULL,
  `event_table` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `code` text NOT NULL,
  `status` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL
  PRIMARY KEY (`event_trigger_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `reset_token`;
CREATE TABLE `reset_token` (
  `reset_token_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token_time` datetime NOT NULL,
  `token` varchar(255) NOT NULL,
  PRIMARY KEY (`reset_token_id`),
  KEY `token` (`token`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `settings_id` int(11) NOT NULL AUTO_INCREMENT,
  `settings_key` varchar(255) NOT NULL,
  `settings_value` longtext NOT NULL,
  PRIMARY KEY (`settings_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `theme` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL,
  `status` varchar(255) NOT NULL,
  `timezone` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `user_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`role_id`),
  KEY `role_slug_index` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `role_user`;
CREATE TABLE `role_user` (
  `role_user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`role_user_id`),
  KEY `role_user_user_id_index` (`user_id`),
  KEY `role_user_role_id_index` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `permission`;
CREATE TABLE `permission` (
  `permission_id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`permission_id`),
  KEY `permission_slug_index` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `permission_role`;
CREATE TABLE `permission_role` (
  `permission_role_id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`permission_role_id`),
  KEY `permission_role_permission_id_index` (`permission_id`),
  KEY `permission_role_role_id_index` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

# select `user`.`user_id` AS `user_id`,`user`.`email` AS `user_email`,`role`.`role_id` AS `role_id`,`role`.`slug` AS `role_slug`,`permission`.`permission_id` AS `permissions_id`,`permission`.`slug` AS `permission_slug` from ((((`user` join `role_user` on(`role_user`.`user_id` = `user`.`user_id`)) join `role` on(`role_user`.`role_id` = `role`.`role_id`)) join `permission_role` on(`role`.`role_id` = `permission_role`.`role_id`)) join `permission` on(`permission_role`.`permission_id` = `permission`.`permission_id`)) where 1 ;

DROP VIEW IF EXISTS `userperms`;
CREATE ALGORITHM=UNDEFINED DEFINER=`php-lwaf`@`%` SQL SECURITY DEFINER VIEW `userperms`  AS  select `user`.`user_id` AS `user_id`,`user`.`email` AS `user_email`,`role`.`role_id` AS `role_id`,`role`.`slug` AS `role_slug`,`permission`.`permission_id` AS `permissions_id`,`permission`.`slug` AS `permission_slug` from ((((`user` join `role_user` on(`role_user`.`user_id` = `user`.`user_id`)) join `role` on(`role_user`.`role_id` = `role`.`role_id`)) join `permission_role` on(`role`.`role_id` = `permission_role`.`role_id`)) join `permission` on(`permission_role`.`permission_id` = `permission`.`permission_id`));

DROP TABLE IF EXISTS `session`;
CREATE TABLE `session` (
  `session_id` int(11) NOT NULL AUTO_INCREMENT,
  `session_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `payload` text COLLATE utf8_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `session_key_index` (`session_key`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

COMMIT;

