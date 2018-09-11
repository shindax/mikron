DROP TABLE IF EXISTS `okb_db_plan_fact_penalty_rates`;
CREATE TABLE IF NOT EXISTS `okb_db_plan_fact_penalty_rates` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `penalty_name` varchar(100) NOT NULL,
  `rate` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `note` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `okb_db_plan_fact_penalty_rates` (`id`, `penalty_name`, `rate`, `note`) VALUES
(1, 'Штраф за просрочку', 0, '');
