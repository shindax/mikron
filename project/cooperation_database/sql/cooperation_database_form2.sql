DROP TABLE IF EXISTS `cooperation_database_form2`;
CREATE TABLE IF NOT EXISTS `cooperation_database_form2` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `material` varchar(255) NOT NULL,
  `ts_type` varchar(250) NOT NULL,
  `price` varchar(255) NOT NULL,
  `note` varchar(255) NOT NULL,
  `actualization_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `actualizator` smallint(5) UNSIGNED NOT NULL,
  `timestamp` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `cooperation_database_form2` (`material`, `ts_type`, `price`, `note`, `actualization_date`, `actualizator`, `timestamp`) VALUES
( 'Ст65Г, Ст40Х, Ст40ХН, Ст45, Ст35, 5ХНМ ', 'Объемная закалка', '40', 'Уровень цен действителен при условии розмеров заготовки не больше 1100*460*200. В диапазоне размеров от указаанных до размеров  3500*1000*1000 один цикл работы печи стоит 23 000,00 руб. без НДС. Для примера: объемная закалки заготовки Вала Ф 180 длиной 25', '2019-04-09 14:36:25', 1, '2019-04-09 07:36:25'),
( '', 'Отжиг', '30', 'Уровень цен действителен при условии размеров заготовки 2000*1500*3500мм.', '2019-04-09 14:36:25', 1, '2019-04-09 07:36:25'),
( '', 'Цементация', '150', 'Размеры тыры для закладки деталей 300*400*500. Цена действительна при глубине цементации не более 1,4мм.  Далее каждый 1,0мм плюс 100,00 руб за 1кг.', '2019-04-09 14:36:25', 1, '2019-04-09 07:36:25'),
( '', 'ТВЧ', '', 'Возможность и стоимость проведения ТВЧ зависит от наличия индуктора и вида самой детали. Каждый раз уточняется индивидуально. ', '2019-04-09 14:36:25', 1, '2019-04-09 07:36:25'),
( '', '', '', '', '2019-04-09 14:36:25', 1, '2019-04-09 07:36:25'),
( '', '', '', '', '2019-04-09 14:36:25', 1, '2019-04-09 07:36:25'),
( '', '', '', '', '2019-04-09 14:36:25', 1, '2019-04-09 07:36:25'),
( '', '', '', '', '2019-04-09 14:36:25', 1, '2019-04-09 07:36:25'),
( '', '', '', '', '2019-04-09 14:36:25', 1, '2019-04-09 07:36:25');