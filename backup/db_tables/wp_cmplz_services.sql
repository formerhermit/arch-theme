/* CUSTOM VARS START */
/* REAL_TABLE_NAME: `wp_cmplz_services`; */
/* PRE_TABLE_NAME: `1783967501_wp_cmplz_services`; */
/* CUSTOM VARS END */

CREATE TABLE IF NOT EXISTS `1783967501_wp_cmplz_services` ( `ID` int NOT NULL AUTO_INCREMENT, `name` varchar(250) COLLATE utf8mb4_unicode_520_ci NOT NULL, `slug` varchar(250) COLLATE utf8mb4_unicode_520_ci NOT NULL, `serviceType` varchar(250) COLLATE utf8mb4_unicode_520_ci NOT NULL, `category` varchar(250) COLLATE utf8mb4_unicode_520_ci NOT NULL, `thirdParty` int NOT NULL, `sharesData` int NOT NULL, `secondParty` int NOT NULL, `privacyStatementURL` varchar(250) COLLATE utf8mb4_unicode_520_ci NOT NULL, `language` varchar(6) COLLATE utf8mb4_unicode_520_ci NOT NULL, `isTranslationFrom` int NOT NULL, `sync` int NOT NULL, `lastUpdatedDate` int NOT NULL, PRIMARY KEY (`ID`)) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
INSERT INTO `1783967501_wp_cmplz_services` (`ID`, `name`, `slug`, `serviceType`, `category`, `thirdParty`, `sharesData`, `secondParty`, `privacyStatementURL`, `language`, `isTranslationFrom`, `sync`, `lastUpdatedDate`) VALUES (1,'YouTube','youtube','video display','utility',1,1,0,'https://policies.google.com/privacy','en',0,1,1783629947);
