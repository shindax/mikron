<?php


	define("MAV_ERP", TRUE);

require_once( "/var/www/okbmikron/www/includes/phpmailer/PHPMailerAutoload.php" );

	include "/var/www/okbmikron/www/config.php";
	include "/var/www/okbmikron/www/includes/database.php";
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);
	
dbquery('SET NAMES utf8');
require_once( 'functions.php' );


	$result = dbquery("SELECT *,`okb_db_special`.`NAME` as `SpecialName`
						FROM `okb_db_resurs`
						LEFT JOIN `okb_db_special` ON `okb_db_special`.`ID` = `okb_db_resurs`.`ID_special` 
						WHERE `TID` = 0");

	while ($row = mysql_fetch_assoc($result)) {
		$d = substr($row['DATE_FROM'], 6, 2);
		$m = substr($row['DATE_FROM'], 4, 2);
		$y = substr($row['DATE_FROM'], 0, 4);
		
		$plus_30_timestamp = strtotime(date("$y-$m-$d 00:00:00") . ' +3 month');

		$diff = $plus_30_timestamp - time();
		
		$end = secondsToTime($diff);
		
		if ($end['d'] < 1) {
			continue;
		}
		
		$name = htmlspecialchars($row['FF'] . ' ' . $row['II'] . ' ' . $row['OO']);
		
		$email_tab = mysql_result(dbquery("SELECT `email` FROM `okb_db_resurs` WHERE `ID_users` = " . $row['ID_tab']), 0);
		
						
					$emails_text = explode(';', str_replace(array(',', ';', ' '), ';', $email_tab));
					
					$emails = array();
					
					foreach ($emails_text as $email) { 
							$emails[] = $email; 
					} 
		
		
		
		if ($end['d'] < 5) {  
			SendMail($emails, 'Уведомление с сайта КИС ОКБ Микрон — Испытательный срок', 'До окончания испытательного срока сотрудника — ' . $name . ' (' . $row['SpecialName'] . ') остался ' . $end['d'] . ' дней.' .
			'<br/><br/>
			<a href="http://192.168.1.100/index.php?do=show&formid=244">http://192.168.1.100/index.php?do=show&formid=244</a><br/>
			<a href="https://internal.okbmikron.ru/index.php?do=show&formid=244">https://internal.okbmikron.ru/index.php?do=show&formid=244</a>');
			
			sleep(1);
			 
		}
					
	}

?>