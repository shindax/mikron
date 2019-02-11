DROP TABLE IF EXISTS `okb_db_plan_fact_direction_stages`;
CREATE TABLE IF NOT EXISTS `okb_db_plan_fact_direction_stages` (
  `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `direction_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL,
  `field` varchar(10) NOT NULL,
  `note` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


INSERT INTO `okb_db_plan_fact_direction_stages` (`id`, `direction_id`, `name`, `field`, `note`) VALUES
(1, 1, 'КД', 'pd1', 'Подготовка производства'),
(2, 1, 'Нормы расхода', 'pd2', 'Подготовка производства'),
(3, 1, 'МТК', 'pd3', 'Подготовка производства'),

(4, 2, 'Проработка', 'pd4', 'Комплектация'),
(5, 2, 'Поставка', 'pd7', 'Комплектация'),

(6, 3, 'Проработка', 'pd_coop1', 'Кооперация'),
(7, 3, 'Поставка', 'pd_coop2', 'Кооперация'),

(8, 4, 'Дата начала', 'pd12', 'Производство'),
(9, 4, 'Дата окончания', 'pd8', 'Производство'),
(10, 4, 'Инструмент и оснастка', 'pd13', 'Производство'),


(11, 5, 'Предоплата', 'pd9', 'Коммерция'),
(12, 5, 'Окончательный расчет', 'pd10', 'Коммерция'),
(13, 5, 'Поставка', 'pd11', 'Коммерция');
