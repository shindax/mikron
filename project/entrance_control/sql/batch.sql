DROP TABLE IF EXISTS `okb_db_entrance_control_items`;
CREATE TABLE IF NOT EXISTS `okb_db_entrance_control_items` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `control_page_id` int(10) UNSIGNED NOT NULL,
  `operation_id` int(10) UNSIGNED NOT NULL,
  `order_item_id` int(10) UNSIGNED NOT NULL,
  `dse_name` varchar(30) NOT NULL,
  `dse_draw` varchar(30) NOT NULL,
  `count` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `inwork_state` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `reject_state` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `rework_state` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `pass_state` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `inwork_state_comment` varchar(50) NOT NULL,
  `reject_state_comment` varchar(50) NOT NULL,
  `rework_state_comment` varchar(50) NOT NULL,
  `pass_state_comment` varchar(50) NOT NULL,
  `timestamp` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `okb_db_entrance_control_pages`;
CREATE TABLE IF NOT EXISTS `okb_db_entrance_control_pages` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `page_num` varchar(20) NOT NULL,
  `image` varchar(50) NOT NULL,
  `proc_type_id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `okb_db_entrance_control_pages_proc_type`;
CREATE TABLE IF NOT EXISTS `okb_db_entrance_control_pages_proc_type` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `description` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `okb_db_entrance_control_pages_proc_type` (`id`, `description`) VALUES
(1, 'Кооперация'),
(2, 'Поставка');
