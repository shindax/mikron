DROP TABLE IF EXISTS `department_month_norm_plan`;
CREATE TABLE IF NOT EXISTS `department_month_norm_plan` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `dep_id` int(10) UNSIGNED NOT NULL,
  `year` smallint(5) UNSIGNED NOT NULL,
  `month` tinyint(3) UNSIGNED NOT NULL,
  `plan` decimal(10,2) NOT NULL,
  `score` float UNSIGNED NOT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
