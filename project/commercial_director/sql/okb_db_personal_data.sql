DROP TABLE IF EXISTS `okb_db_personal_data`;
CREATE TABLE IF NOT EXISTS `okb_db_personal_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `data` json NOT NULL,
  `note` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


INSERT INTO `okb_db_personal_data` (`id`, `user_id`, `data`, `note`, `timestamp`) VALUES
(1, 145, '{\"base_penalty_rate\": 1}', 0, '2018-01-29 04:24:18');
