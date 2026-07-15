/* CUSTOM VARS START */
/* REAL_TABLE_NAME: `wp_wpmailsmtp_debug_events`; */
/* PRE_TABLE_NAME: `1783967501_wp_wpmailsmtp_debug_events`; */
/* CUSTOM VARS END */

CREATE TABLE IF NOT EXISTS `1783967501_wp_wpmailsmtp_debug_events` ( `id` int unsigned NOT NULL AUTO_INCREMENT, `content` text COLLATE utf8mb4_unicode_520_ci, `initiator` text COLLATE utf8mb4_unicode_520_ci, `event_type` tinyint unsigned NOT NULL DEFAULT '0', `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
INSERT INTO `1783967501_wp_wpmailsmtp_debug_events` (`id`, `content`, `initiator`, `event_type`, `created_at`) VALUES (1,'Mailer: Brevo\r\nunauthorized: authentication not found in headers','{\"file\":\"\\/s2-overtakefan\\/wordpress\\/wp-content\\/plugins\\/wpforms-lite\\/src\\/Emails\\/Mailer.php\",\"line\":626}',0,'2026-07-08 18:53:10');
