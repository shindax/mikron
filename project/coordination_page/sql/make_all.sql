DROP TABLE IF EXISTS `coordination_pages_task`;
CREATE TABLE IF NOT EXISTS `coordination_pages_task` (
  `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `caption` varchar(32) NOT NULL,
  `agreed_flag` tinyint(1) NOT NULL DEFAULT '0',
  `can_hide` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

INSERT INTO `coordination_pages_task` (`id`, `caption`, `agreed_flag`, `can_hide`) VALUES
(1, 'Предоплата', 0, 0 ),
(2, 'Оконч. расчет', 0, 0 ),
(3, 'Проработка', 0, 0 ),
(4, 'Поставка', 0, 0 ),
(5, 'Поставка 2', 0, 1 ),
(6, 'Поставка 3', 0, 1 ),
(7, 'Согласовано', 1, 0),
(8, 'НР', 0, 0 ),
(9, 'КД', 0, 0 ),
(10, 'МТК', 0, 0 ),
(11, 'Начало', 0, 0 ),
(12, 'Окончание', 0, 0 ),
(13, 'Окончание 2', 0, 0 ),
(14, 'Окончание 3', 0, 0 );

DROP TABLE IF EXISTS `coordination_pages`;
CREATE TABLE IF NOT EXISTS `coordination_pages` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `krz2_id` int(10) UNSIGNED NOT NULL,
  `coordinated` date NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `coordination_page_items`;
CREATE TABLE IF NOT EXISTS `coordination_page_items` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `page_id` int(10) UNSIGNED NOT NULL,
  `row_id` tinyint(3) UNSIGNED NOT NULL,
  `coordinator_id` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `task_id` tinyint(3) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `ins_time` datetime NOT NULL,
  `ignored` tinyint(1) NOT NULL DEFAULT '0',
  `comment` varchar(128) NOT NULL,
  `timestamp` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `coordination_pages_rows`;
CREATE TABLE IF NOT EXISTS `coordination_pages_rows` (
  `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `caption` varchar(64) NOT NULL,
  `user_arr` json NOT NULL,
  `comment` varchar(64) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

INSERT INTO `coordination_pages_rows` (`id`, `caption`, `user_arr`, `comment`, `timestamp`) VALUES
(1, 'Инициатор заказа', '[145, 173, 206, 216, 222]', 'Трифонов, Гриднева, Михайлова, Антонова, Радыгин', '2018-07-06 03:29:22'),
(2, 'Коммерческий директор', '[145, 39]', 'Трифонов, Куимова', '2018-07-05 01:42:11'),
(3, 'Технический директор', '[43, 31]', 'Бормотов, Роев', '2018-07-05 02:38:11'),
(4, 'Начальник ОМТС', '[15]', 'Кумановская', '2018-07-05 01:43:29'),
(5, 'Начальник ОВК', '[5]', 'Казаченко', '2018-07-05 01:44:00'),
(6, 'Начальник ПДО', '[13]', 'Рыбкина', '2018-07-05 01:44:26'),
(7, 'Начальник производства', '[100]', 'Филоненко', '2018-07-05 01:45:01');
