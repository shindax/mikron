DROP TABLE IF EXISTS `okb_db_plan_fact_notification`;
CREATE TABLE IF NOT EXISTS `okb_db_plan_fact_notification` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `why` tinyint(3) UNSIGNED NOT NULL,
  `to_user` smallint(5) UNSIGNED NOT NULL,
  `zak_id` int(10) UNSIGNED NOT NULL,
  `field` varchar(30) NOT NULL,
  `stage` tinyint(3) UNSIGNED NOT NULL,
  `ack` tinyint(1) DEFAULT '0',
  `description` varchar(128) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=cp1251;
