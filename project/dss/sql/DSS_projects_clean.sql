DROP TABLE IF EXISTS `dss_projects`;
CREATE TABLE IF NOT EXISTS `dss_projects` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `base_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `parent_id` int(10) UNSIGNED DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `creator_id` int(10) UNSIGNED NOT NULL,
  `create_date` date NOT NULL,
  `team` json DEFAULT NULL,
  `pictures` json DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Decision Support System, DSS, Система  принятия решений';
