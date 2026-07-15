/* CUSTOM VARS START */
/* REAL_TABLE_NAME: `wp_termmeta`; */
/* PRE_TABLE_NAME: `1783967501_wp_termmeta`; */
/* CUSTOM VARS END */

CREATE TABLE IF NOT EXISTS `1783967501_wp_termmeta` ( `meta_id` bigint unsigned NOT NULL AUTO_INCREMENT, `term_id` bigint unsigned NOT NULL DEFAULT '0', `meta_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL, `meta_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci, PRIMARY KEY (`meta_id`), KEY `term_id` (`term_id`), KEY `meta_key` (`meta_key`(191))) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
