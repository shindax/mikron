DROP TABLE IF EXISTS `okb_db_logistic_rates`;
CREATE TABLE IF NOT EXISTS `okb_db_logistic_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city` varchar(50) NOT NULL,
  `auto_delivery_eurovan` varchar(30) NOT NULL,
  `auto_delivery_oversize` varchar(30) NOT NULL,
  `railway_delivery_semiwagon` varchar(30) NOT NULL,
  `assembly_cargo` varchar(30) NOT NULL,
  `avia_delivery` varchar(30) NOT NULL,
  `actuality` timestamp NOT NULL,
  `timestamp` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


INSERT INTO `okb_db_logistic_rates` (`id`, `city`) 
VALUES
(NULL, 'Актобе'),
(NULL, 'Апатит'),
(NULL, 'Архангельск'),
(NULL, 'Белгород'),
(NULL, 'Братск'),
(NULL, 'Воронеж'),
(NULL, 'Дзержинск'),
(NULL, 'Заинск'),
(NULL, 'Мурманск'),
(NULL, 'Норильск'),
(NULL, 'Рязань'),
(NULL, 'Саяногорск'),
(NULL, 'Ухта');
