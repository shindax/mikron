<?php
define("MAV_ERP", TRUE);

include "../../config.php";
include "../../includes/database.php";
include "../db_cfg.php";
include "../../db_func.php";
dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

# Загрузка данных в систему контроля доступа

// dbquery("SET  SESSION  character_set_database  =  cp1251");

dbquery("LOAD DATA INFILE 'C:/AppServ/www/project/63gu88s920hb045e/db_files_SCA/destination/fresh_data.csv'
IGNORE INTO TABLE okb_db_system_control_access_source_data
CHARACTER SET cp1251
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\n'
(@Дата,@Время,@Устройство,@Событие,@Код_семейства,@Номер,@ФИО)
SET DT_TIME = STR_TO_DATE(CONCAT(@Дата,';',@Время),'%d.%m.%Y;%T'),
DESTINATION = IF(@Устройство='ВХОД',1,2),
ID_CARD = @Номер,
DT_LOAD = now()");

unlink('../63gu88s920hb045e/db_files_SCA/destination/fresh_data.csv');

dbquery("call set_sca_tabel()");

?>