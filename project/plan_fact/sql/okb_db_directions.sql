DROP TABLE IF EXISTS `okb_db_plan_fact_directions`;
CREATE TABLE IF NOT EXISTS `okb_db_plan_fact_directions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `direction` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `okb_db_plan_fact_directions` (`id`, `direction`) VALUES
(1, 'Подготовка производства'),
(2, 'Комплектация'),
(3, 'Кооперация'),
(4, 'Производство'),
(5, 'Коммерция'),
