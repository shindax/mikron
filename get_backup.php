<?php
//////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	� 2012 ���������� �.�.
//
//
//////////////////////////////////////////////////////

	define("MAV_ERP", TRUE);



// �������


	include "config.php";
	include "includes/database.php";
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);
	include "includes/cookie.php";
	include "includes/config.php";
	include "includes/functions.php";

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


function file_force_download($file) {
	if (file_exists($file)) {
		// ���������� ����� ������ PHP, ����� �������� ������������ ������ ���������� ��� ������
		// ���� ����� �� ������� ���� ����� �������� � ������ ���������!
		if (ob_get_level()) {
			ob_end_clean();
		}
		// ���������� ������� �������� ���� ���������� �����
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=' . basename($file));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		// ������ ���� � ���������� ��� ������������
		readfile($file);
		exit;
	} else {
		die("File not found");
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


if ($user["ID"]=="1") {
	$filename = "project/".$backup_path."/".$_GET["filename"];
	file_force_download($filename);
} else {
	die("Access Denied");
}



?>