<?php
	define("MAV_ERP", TRUE);
	
	header('Content-type: text/plain; charset=windows-1251');
	
	include '../config.php';
	include '../includes/database.php';
	
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);

	switch ($_POST['mode'])
	{
		case 'single':
			dbquery("UPDATE `okb_db_zadan` SET `id_resurs` = " . (int) $_POST['to_resource'] . "
						WHERE `id` = " . (int) $_POST['id']);
			break;
		case 'multiple':
			foreach($_POST['ids'] as $id) {
				dbquery("UPDATE `okb_db_zadan` SET `id_resurs` = " . (int) $_POST['to_resource'] . "
							WHERE `id` = " . (int) $id);
			}
			break;
		case 'multiple_smena_date':
			foreach($_POST['ids'] as $id) {
				dbquery("UPDATE `okb_db_zadan` SET `id_resurs` = " . (int) $_POST['to_resource'] . ", `SMEN` = '" . $_POST['smena'] . "', DATE = '" . $_POST['date'] . "'
							WHERE `id` = " . (int) $id);
			}
			break;
	}
