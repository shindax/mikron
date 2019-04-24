DROP TABLE IF EXISTS `cooperation_database_form7`;
CREATE TABLE IF NOT EXISTS `cooperation_database_form7` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `thickness` varchar(255) NOT NULL,
  `material1` varchar(255) NOT NULL,
  `material2` varchar(255) NOT NULL,
  `material3` varchar(255) NOT NULL,
  `material4` varchar(255) NOT NULL,
  `material5` varchar(255) NOT NULL,
  `actualization_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `actualizator` smallint(5) UNSIGNED NOT NULL,
  `timestamp` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `cooperation_database_form7` (`thickness`, `material1`, `material2`, `material3`, `material4`, `material5`, `actualization_date`, `actualizator`, `timestamp`) VALUES ('до 3','175,00','198,00','139,00','121,00','229,00','','1',''),
('4','234,00','270,00','175,00','131,00','','','1',''),
('5','309,00','347,00','216,00','149,00','383,00','','1',''),
('6','383,00','432,00','275,00','175,00','','','1',''),
('7','450,00','522,00','324,00','','','','1',''),
('8','540,00','612,00','373,00','234,00','697,00','','1',''),
('9','612,00','697,00','432,00','','','','1',''),
('10','679,00','792,00','486,00','293,00','913,00','','1',''),
('12','851,00','972,00','607,00','360,00','','','1',''),
('15','1 116,00','1 165,00','769,00','450,00','1 273,00','','1',''),
('16','1 260,00','1 296,00','841,00','','','','1',''),
('20','1 499,00','1 741,00','1 301,00','653,00','2 088,00','','1',''),
('30','2 435,00','2 713,00','1 705,00','1 301,00','4 873,00','','1',''),
('40','3 307,00','3 829,00','2 435,00','1 741,00','','','1',''),
('50','4 199,00','4 873,00','3 047,00','2 957,00','13 199,00','','1',''),
('60','5 305,00','6 089,00','3 695,00','','','','1',''),
('80','7 141,00','8 159,00','6 529,00','5 742,00','20 039,00','','1','');

