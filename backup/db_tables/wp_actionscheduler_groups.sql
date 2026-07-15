/* CUSTOM VARS START */
/* REAL_TABLE_NAME: `wp_actionscheduler_groups`; */
/* PRE_TABLE_NAME: `1783967501_wp_actionscheduler_groups`; */
/* CUSTOM VARS END */

CREATE TABLE IF NOT EXISTS `1783967501_wp_actionscheduler_groups` ( `group_id` bigint unsigned NOT NULL AUTO_INCREMENT, `slug` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL, PRIMARY KEY (`group_id`), KEY `slug` (`slug`(191))) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
INSERT INTO `1783967501_wp_actionscheduler_groups` (`group_id`, `slug`) VALUES (1,'ActionScheduler'),(2,'action-scheduler-migration'),(3,'wpforms'),(4,'wp_mail_smtp');
