DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `id` int(10) UNSIGNED AUTO_INCREMENT,
  `pid` int(10) UNSIGNED,
  `ord` tinyint(3) UNSIGNED,
  `name` tinytext,
  `short_name` tinytext,
  `user_id` int(10) UNSIGNED,  
  PRIMARY KEY (`id`),
  KEY `PID` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, 0, 1, 'Генеральный директор', 'ГД');

SET @lastID := LAST_INSERT_ID();
SET @line := 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @lastID, @line, 'Служба исполнительного директора', 'СИД');
SET @execID := LAST_INSERT_ID();
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @lastID, @line, 'Служба финансового директора', 'СФД');
SET @finID := LAST_INSERT_ID();
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @lastID, @line, 'Служба безопасности', 'СБ');

# Службы исполнительного директора:
SET @line := 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @execID, @line, 'Служба главного инженера', 'СГИ');
SET @mainIngeneerID := LAST_INSERT_ID();
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @execID, @line, 'Производство', 'ПР');
SET @enterpiseID := LAST_INSERT_ID();
SET @line = @line + 1;

 INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @execID, @line, 'Планово-диспетчерский отдел', 'ПДО');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @execID, @line, 'Служба технического директора', 'СТД');
SET @techDirID := LAST_INSERT_ID();
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @execID, @line, 'Служба коммерческого директора', 'СКД');
SET @commertialDirID := LAST_INSERT_ID();
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @execID, @line, 'Служба управления персоналом', 'СУП');
SET @personalCtrlID := LAST_INSERT_ID();
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @execID, @line, 'Юридический отдел', 'ЮО');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @execID, @line, 'Отдел ИТ', 'ИТ');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @execID, @line, 'Отдел секретариата', 'ОС');

# Служба главного инженера
SET @line = 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @mainIngeneerID, @line, 'Отдел модернизации и капремонта', 'ОМКР');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @mainIngeneerID, @line, 'Отдел главного механика', 'ОГМ');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @mainIngeneerID, @line, 'Отдел охраны труда и техники безопасности', 'ООТиТБ');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @mainIngeneerID, @line, 'Отдел главного энергетика', 'ОГЭ');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @mainIngeneerID, @line, 'Отдел автоматизированных систем управления', 'АСУТП');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @mainIngeneerID, @line, 'Отдел логистики', 'ОЛ');
SET @line = @line + 1;
SET @logisticID := LAST_INSERT_ID();

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @mainIngeneerID, @line, 'Отдел строительства и обеспечения', 'ОСиО');
SET @line = @line + 1;
SET @buildID := LAST_INSERT_ID();

#Отдел логистики
SET @line = 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @logisticID, @line, 'Авторемонтный цех', 'АЦ');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @logisticID, @line, 'Бюро транспортировки', 'БТ');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @logisticID, @line, 'Бюро внешней логистики', 'БВЛ');
SET @line = @line + 1;

# Отдел строительства и обеспечения
SET @line = 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @buildID , @line, 'Административно-хозяйственный отдел', 'АХО');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @buildID , @line, 'Отдел строительства', 'ОС');

#Производство
SET @line = 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @enterpiseID, @line, 'Бригада логистики', 'БЛ');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @enterpiseID, @line, 'Участок заготовки', 'УЗ');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @enterpiseID, @line, 'Участок сборки и сварки', 'УСС');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @enterpiseID, @line, 'Участок механической обработки', 'УМС');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @enterpiseID, @line, 'Участок термообработки', 'УТ');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @enterpiseID, @line, 'Участок доводки, сборки и упаковки', 'УДСиП');
SET @line = @line + 1;

#Служба технического директора
SET @line = 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @techDirID, @line, 'Управление качеством', 'УК');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @techDirID, @line, 'Отдел технического контроля', 'ОТК');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @techDirID, @line, 'Отдел стандартизации и сертификации', 'ОСиС');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @techDirID, @line, 'Отдел метрологии', 'ОМ');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @techDirID, @line, 'Конструкторско-технологический отдел', 'КТО');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @techDirID, @line, 'Технологический отдел', 'ТО');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @techDirID, @line, 'Конструкторский отдел', 'КО');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @techDirID, @line, 'Отдел главного сварщика', 'ОГС');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @techDirID, @line, 'Отдел технической документации', 'ОТД');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @techDirID, @line, 'Отдел технической подготовки производства', 'ОТПП');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @techDirID, @line, 'Бюро эксперементального производства', 'БЭП');
SET @lastID := LAST_INSERT_ID();
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @lastID, 1, 'Участок 3D печати', '3D');

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @techDirID, @line, 'Бюро запуска новых рабочих центров', 'БЗНРЦ');
SET @line = @line + 1;

#Служба коммерческого директора
SET @line = 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @commertialDirID, @line, 'Отдел продаж', 'ОП');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @commertialDirID, @line, 'Отдел маркетинга и рекламы', 'ОМиР');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @commertialDirID, @line, 'Отдел внешней кооперации', 'ОВК');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @commertialDirID, @line, 'Отдел материально-технического снабжения', 'ОМТС');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @commertialDirID, @line, 'Складское хозяйство', 'СХ');
SET @line = @line + 1;

#Служба управления персоналом
SET @line = 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @personalCtrlID, @line, 'Отдел подбора персонала', 'ОПП');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @personalCtrlID, @line, 'Отдел кадров', 'ОК');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @personalCtrlID, @line, 'Отдел труда и заработной платы', 'ОТиЗП');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @personalCtrlID, @line, 'Учебный центр', 'УЦ');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @personalCtrlID, @line, 'Медпункт', 'МП');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @personalCtrlID, @line, 'Отдел питания', 'ОП');
SET @line = @line + 1;

#Служба финансового директора
SET @line = 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @finID, @line, 'Планово-экономический отдел', 'ПЭО');
SET @line = @line + 1;

INSERT INTO `departments` (`id`, `pid`, `ord`, `name`, `short_name`) VALUES
(NULL, @finID, @line, 'Отдел бухгалтерского учета', 'ОБ');
