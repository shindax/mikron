DROP TABLE IF EXISTS `okb_db_coop_request_tasks`;
CREATE TABLE IF NOT EXISTS `okb_db_coop_request_tasks` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `coop_req_id` int(11) UNSIGNED NOT NULL,
  `cagent_id` int(11) UNSIGNED NOT NULL,
  `req_send_date` date NOT NULL,
  `req_response_date` date NOT NULL,
  `state` tinyint(3) UNSIGNED NOT NULL,
  `state_note` varchar(255) NOT NULL,
  `pricing` tinyint(4) NOT NULL,
  `pricing_note` varchar(250) NOT NULL,
  `selected` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `okb_db_coop_request_pricing`;
CREATE TABLE IF NOT EXISTS `okb_db_coop_request_pricing` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `descr` varchar(50) NOT NULL,
  `note` varchar(250) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `okb_db_coop_request_pricing` (`id`, `descr`, `note`, `timestamp`) VALUES
(1, 'Другое', '', '2018-03-02 03:47:01'),
(2, 'Согласно КП', '', '2018-03-02 03:47:01');

DROP TABLE IF EXISTS `okb_db_coop_request_task_state`;
CREATE TABLE IF NOT EXISTS `okb_db_coop_request_task_state` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `descr` varchar(50) NOT NULL,
  `note` varchar(250) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `okb_db_coop_request_task_state` (`id`, `descr`, `note`, `timestamp`) VALUES
(1, 'Другое', '', '2018-03-02 03:47:01'),
(2, 'Нет технической возможности', '', '2018-03-02 03:47:01'),
(3, 'Загружены', '', '2018-03-02 03:47:01'),
(4, 'Продолжаем работать по ранее согласованным ценам', '', '2018-03-02 03:47:36');
