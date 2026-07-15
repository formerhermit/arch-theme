/* CUSTOM VARS START */
/* REAL_TABLE_NAME: `wp_term_relationships`; */
/* PRE_TABLE_NAME: `1783967501_wp_term_relationships`; */
/* CUSTOM VARS END */

CREATE TABLE IF NOT EXISTS `1783967501_wp_term_relationships` ( `object_id` bigint unsigned NOT NULL DEFAULT '0', `term_taxonomy_id` bigint unsigned NOT NULL DEFAULT '0', `term_order` int NOT NULL DEFAULT '0', PRIMARY KEY (`object_id`,`term_taxonomy_id`), KEY `term_taxonomy_id` (`term_taxonomy_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
INSERT INTO `1783967501_wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (1,1,0),(5,4,0),(9,1,0),(13,1,0),(17,2,0),(46,1,0),(69,3,0),(71,3,0),(98,3,0),(154,3,0),(271,3,0),(276,3,0),(289,3,0),(290,3,0),(291,3,0);
