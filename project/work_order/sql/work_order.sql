DROP TABLE IF EXISTS `okb_db_oper_class`;
CREATE TABLE IF NOT EXISTS `okb_db_oper_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

INSERT INTO `okb_db_oper_class` (`id`, `description`) VALUES
(1, 'Заготовка'),
(2, 'Сборка-сварка'),
(3, 'Механообработка'),
(4, 'Сборка'),
(5, 'Термообработка'),
(6, 'Упаковка'),
(7, 'Окраска');

DROP TABLE IF EXISTS `okb_db_zak_type`;
CREATE TABLE IF NOT EXISTS `okb_db_zak_type` 
(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

INSERT INTO `okb_db_zak_type` (`id`, `description`) VALUES
(1, 'ОЗ'),
(2, 'КР'),
(3, 'СП'),
(4, 'БЗ'),
(5, 'ХЗ'),
(6, 'ВЗ');
