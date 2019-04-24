DROP TABLE IF EXISTS `cooperation_database_form1`;
CREATE TABLE IF NOT EXISTS `cooperation_database_form1` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `caption` varchar(255) NOT NULL,
  `row_1_type` varchar(255) NOT NULL,
  `row_1_col_1` varchar(255) NOT NULL,
  `row_1_col_2` varchar(255) NOT NULL,
  `row_1_col_3` varchar(255) NOT NULL,
  `row_1_col_4` varchar(255) NOT NULL,
  `row_2_type` varchar(255) NOT NULL,
  `row_2_col_1` varchar(255) NOT NULL,
  `row_2_col_2` varchar(255) NOT NULL,
  `row_2_col_3` varchar(255) NOT NULL,
  `row_2_col_4` varchar(255) NOT NULL,
  `actualization_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `actualizator` smallint(5) UNSIGNED NOT NULL,
  `timestamp` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `cooperation_database_form1` ( `caption`, `row_1_type`, `row_1_col_1`, `row_1_col_2`, `row_1_col_3`, `row_1_col_4`, `row_2_type`, `row_2_col_1`, `row_2_col_2`, `row_2_col_3`, `row_2_col_4`, `actualization_date`, `actualizator`, `timestamp`) VALUES
( 'Ст20-45Л, цена тонны литья, тыс.руб без НДС', 'ПГС, ХТС', '90-100', '100-110', '110-120', '', 'ЛГМ, ЛВМ', '100-110', '110-120', '135-155', '', '2019-04-10 11:22:13', 1, '2019-04-10 04:22:13'),
( 'Чугун 15-20, цена тонны литья, тыс.руб без НДС', 'ПГС, ХТС', '80-90', '90-100', '100-110', '', 'ЛГМ, ЛВМ', '90-100', '100-130', '120-140', '', '2019-04-10 11:22:13', 1, '2019-04-10 04:22:13'),
( '110Г13Л', 'ПГС, ХТС', '', '', '', '', 'ЛГМ, ЛВМ', '', '', '', '', '2019-04-10 11:22:13', 1, '2019-04-10 04:22:13'),
( '', '', '', '', '', '', '', '', '', '', '', '2019-04-10 11:22:13', 1, '2019-04-10 04:22:13'),
( '', '', '', '', '', '', '', '', '', '', '', '2019-04-10 11:22:13', 1, '2019-04-10 04:22:13'),
( 'Пример готового изделия', 'ПГС, ХТС', 'Крышка внешняя КМ10.45.01.004, Корпус буксы КМ10.45.01.003, корпус подшипников, крышки и втулки конвееров', 'Детали к скрейперным лебедкам, бандажи КМ10.45.01.002, ролики, скрейпера, шкивы флотомашин.', 'Колесо КМ12.14.01.002, Колесо М181.04, водило лебедок, диафрагмы, корпуса флотомашин, корпуса редукторов', '', 'ЛГМ, ЛВМ', 'Крышки НКР, шкив НКР, крышки скрейперных лебедок, серьги Вагонеток', 'Корпуса коронок, крюки ВГ2,5, вкладыши', 'Фурма ТВ2019, салазки УПБ', '', '2019-04-10 11:22:13', 1, '2019-04-10 04:22:13');
