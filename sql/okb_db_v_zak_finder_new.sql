-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Мар 12 2019 г., 02:24
-- Версия сервера: 5.7.25-0ubuntu0.18.04.2-log
-- Версия PHP: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8	 */;

--
-- База данных: `okbdb`
--

-- --------------------------------------------------------

--
-- Структура для представления `okb_db_v_zak_finder`
--

CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `okb_db_v_zak_finder`  
	AS  
	select '<option class=\'' 
	AS `S_01`,(case `z`.`EDIT_STATE` when 1 then 'green_color' when 2 then 'red_color' else 'default_color' end) 
	AS `S_02`,'\' value=\'' 
	AS `S_03`,concat('index.php?do=show&formid=39&id=',`z`.`ID`,'\'>') 
	AS `LINK`,(case `z`.`TID` when 1 then 'ОЗ' when 2 then 'КР' when 3 then 'СП' when 4 then 'БЗ' when 5 then 'ХЗ' when 6 then 'ВЗ' end) 
	AS `S_1`,concat(' ',`z`.`NAME`,' / ',`z`.`DSE_NAME`,' / ',`z`.`DSE_OBOZ`,' / ',`c`.`NAME`,' / ',`f`.`NAME`,' ',`f`.`KRZ`,' / ') 
	AS `S_2`,concat(`z`.`DSE_COUNT`,' / ') 
	AS `S_3`,(case `z`.`EDIT_STATE` when 1 then 'ВЫПОЛНЕН' when 2 then 'АННУЛИРОВАН' else '' end) 
	AS `S_4`,'</option>' 
	AS `FINISH` 
		from (
				(
					`okb_db_zak` `z` join `okb_db_clients` `c`
				) 
				join `okb_db_files_1` `f`
			) 
		where (
			(`z`.`ID_clients` = `c`.`ID`) 
			and 
			(
				(
					`z`.`ID_RASPNUM` = `f`.`ID`) 
					OR 
					`z`.`ID_RASPNUM` = 0 
				)
		) 
		group by `LINK`		
		order by `z`.`ORD` desc
		;
--		
--
-- VIEW  `okb_db_v_zak_finder`
-- Данные: Ниодного
--

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
	