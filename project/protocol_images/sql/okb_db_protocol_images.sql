DROP TABLE IF EXISTS `okb_db_protocol_images`;
CREATE TABLE IF NOT EXISTS `okb_db_protocol_images` (
  `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `rec_date` date DEFAULT NULL,
  `department_id` tinyint(3) UNSIGNED DEFAULT NULL,
  `project_plan_date_fact` date DEFAULT NULL,
  `project_plan_images` json DEFAULT NULL,
  `plan_date_fact` date DEFAULT NULL,
  `plan_images` json DEFAULT NULL,
  `report_date_fact` date DEFAULT NULL,
  `report_images` json DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `okb_db_protocol_images_ref_dates`;
CREATE TABLE IF NOT EXISTS `okb_db_protocol_images_ref_dates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref_date` date NOT NULL,
  `project_plan_date` date NOT NULL,
  `plan_date` date NOT NULL,
  `report_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `okb_db_protocol_departments`;
CREATE TABLE IF NOT EXISTS `okb_db_protocol_departments` (
  `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `department_name` tinytext,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

INSERT INTO `okb_db_protocol_departments` (`ID`, `department_name`) VALUES
(1, 'Коммерческий отдел'),
(2, 'Отдел внешней кооперации'),
(3, 'Планово-диспетчерский отдел'),
(4, 'Подготовка производства'),
(5, 'Отдел кадров'),
(6, 'Отдел информационных технологий'),
(7, 'Отдел материально-технического снабжения'),
(8, 'Складское хозяйство'),
(9, 'Служба главного инженера');
