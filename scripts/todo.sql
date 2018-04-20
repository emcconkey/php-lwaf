
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `menu_order` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `role_menu`;
CREATE TABLE `role_menu` (
  `role_menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`role_menu_id`),
  KEY `role_menu_menu_id_index` (`menu_id`),
  KEY `role_menu_role_id_index` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP VIEW IF EXISTS `menulist`;
CREATE ALGORITHM=UNDEFINED DEFINER=`php-lwaf`@`%` SQL SECURITY DEFINER VIEW `menulist` AS select `user`.`user_id` AS `user_id`,`user`.`email` AS `user_email`,`role`.`role_id` AS `role_id`,`role`.`slug` AS `role_slug`,`menu`.`menu_id` AS `menu_id`,`menu`.`slug` AS `menu_slug`, `menu`.`url` as `menu_url`, `menu`.`title` as `menu_title`, `menu`.`parent` as `menu_parent`, `menu`.`menu_order` as `menu_order` from ((((`user` join `role_user` on(`role_user`.`user_id` = `user`.`user_id`)) join `role` on(`role_user`.`role_id` = `role`.`role_id`)) join `role_menu` on(`role`.`role_id` = `role_menu`.`role_id`)) join `menu` on(`role_menu`.`menu_id` = `menu`.`menu_id`)) order by menu_order ;
