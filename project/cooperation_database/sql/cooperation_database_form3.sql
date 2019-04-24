DROP TABLE IF EXISTS `cooperation_database_form3`;
CREATE TABLE IF NOT EXISTS `cooperation_database_form3` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `caption` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `note` varchar(255) NOT NULL,
  `actualization_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `actualizator` smallint(5) UNSIGNED NOT NULL,
  `timestamp` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `cooperation_database_form3` ( `caption`, `price`, `note`, `actualization_date`, `actualizator`, `timestamp`) VALUES
( 'На вертикальном прессе', '20,00-40,00 руб за 1кг.', 'Простые линейные гибы до лист 16 длиной до 3000мм. Цена зависит от сложности гиба. ', '2019-04-10 11:10:31', 1, '2019-04-10 04:10:31'),
( '', '', '', '2019-04-10 11:10:31', 1, '2019-04-10 04:10:31'),
( '', '', '', '2019-04-10 11:10:31', 1, '2019-04-10 04:10:31'),
( '', '', '', '2019-04-10 11:10:31', 1, '2019-04-10 04:10:31'),
( '', '', '', '2019-04-10 11:10:31', 1, '2019-04-10 04:10:31');
