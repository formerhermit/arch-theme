/* CUSTOM VARS START */
/* REAL_TABLE_NAME: `wp_cmplz_dnsmpd`; */
/* PRE_TABLE_NAME: `1783967501_wp_cmplz_dnsmpd`; */
/* CUSTOM VARS END */

CREATE TABLE IF NOT EXISTS `1783967501_wp_cmplz_dnsmpd` ( `ID` int NOT NULL AUTO_INCREMENT, `name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL, `email` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL, `region` text COLLATE utf8mb4_unicode_520_ci NOT NULL, `global_optout` int NOT NULL, `cross_context` int NOT NULL, `limit_sensitive` int NOT NULL, `request_for_access` int NOT NULL, `right_to_be_forgotten` int NOT NULL, `right_to_data_portability` int NOT NULL, `request_date` int NOT NULL, `resolved` int NOT NULL, PRIMARY KEY (`ID`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
