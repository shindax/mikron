DROP TABLE IF EXISTS `okb_db_operations_with_coop_dep`;
CREATE TABLE IF NOT EXISTS `okb_db_operations_with_coop_dep` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `oper_id` int(10) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `count` tinyint(3) UNSIGNED NOT NULL,
  `norm_hours` float NOT NULL,
  `comment` varchar(30) NOT NULL,
  `timestamp` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
