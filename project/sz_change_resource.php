<?php
	define("MAV_ERP", TRUE);
	
	header('Content-type: text/plain; charset=windows-1251');
	
	include '../config.php';
	include '../includes/database.php';
	
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

// global $user;

	$user_id = $_POST['user_id'];
	$mode = $_POST['mode'];
	$sid = (int) $_POST['id'];
	$to_res = (int) $_POST['to_resource'];
	$ids = $_POST['ids'];
	$smena = $_POST['smena'];
	$date = $_POST['date'];

	switch ( $mode )
	{
		case 'single':
			dbquery("UPDATE `okb_db_zadan` SET `id_resurs` = $to_res WHERE `id` = $sid ");
			break;
		case 'multiple':
			foreach( $ids as $id ) 
			{
				dbquery("UPDATE `okb_db_zadan` SET `id_resurs` = $to_res 
						 WHERE `id` = $id" );
			}
			break;
		case 'multiple_smena_date':
			foreach( $ids as $id ) 
			{
				dbquery("UPDATE `okb_db_zadan` SET `id_resurs` = $to_res, `SMEN` = $smena, DATE = $date			 WHERE `id` = $id" );
			}
			break;
	}

dbquery("INSERT INTO `production_shift_actions` ( `op_type`, `user_id` ) VALUES ( 3, $user_id )");