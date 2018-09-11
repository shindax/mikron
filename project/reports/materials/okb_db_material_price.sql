DROP TABLE IF EXISTS `okb_db_material_price`;
CREATE TABLE IF NOT EXISTS `okb_db_material_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mat_note` varchar(20) NOT NULL,
  `id_mat` int(10) UNSIGNED DEFAULT NULL,
  `sort_note` varchar(30) NOT NULL,
  `id_sort` int(10) UNSIGNED DEFAULT NULL,
  `price` float UNSIGNED DEFAULT NULL,
  `note` varchar(50) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
