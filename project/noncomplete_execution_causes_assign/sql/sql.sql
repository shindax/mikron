DROP TABLE IF EXISTS `noncomplete_execution_cause_explanations`;
CREATE TABLE IF NOT EXISTS `noncomplete_execution_cause_explanations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cause_id` int(10) UNSIGNED NOT NULL,
  `description` varchar(128) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

################################################################################

DROP TABLE IF EXISTS `noncomplete_execution_causes`;
CREATE TABLE IF NOT EXISTS `noncomplete_execution_causes` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `responsible_res_id` json NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

################################################################################

DROP TABLE IF EXISTS `noncomplete_execution_precedents`;
CREATE TABLE IF NOT EXISTS `noncomplete_execution_precedents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zadan_id` int(10) UNSIGNED NOT NULL,
  `shutter_user_id` int(10) UNSIGNED NOT NULL,
  `cause` tinyint(3) UNSIGNED NOT NULL,
  `date` datetime NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
