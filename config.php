<?php
if (!defined("MAV_ERP")) { die("Access Denied"); }
//error_reporting(0);
//ini_set('display_errors', false);

error_reporting(E_ALL);
error_reporting(E_ERROR);
ini_set('display_errors', true);

$db_host = "127.0.0.1";
$db_user = "root";
$db_pass = "";
$db_name = "okbdb";
$db_prefix = "okb_";
$db_charset = "cp1251";
$html_charset = "windows-1251";

$title = "��� ������";
$lang = "ru";
$newpass = "123123";
$use_gzip = false;			// ������������ gzip ������ ������ ��� ���������
$files_path = "63gu88s920hb045e";	// �������� ����� ��� �������� ������ ��� ���� ������ file (���������� ��� ������ �������������)
$auto_backup_time = 0;		// ����� �/� ������������ � �������� (�� ����� 600), 0 - ��������� ���������
$backup_count = 30;			// ���������� ��������� ������� (����� ������ ���������) �� ����� 10
$backup_path = "b2c7k34p0s08r9";	// �������� ����� ��� ������� (���������� ��� ������ �������������)
$use_loginform = false;			// ������������ � �������� ����� ����� project/login.php
$copy_state = false;			// ����� ����� ����� ��� ������������

setlocale(LC_ALL, 'en_US.UTF-8');

date_default_timezone_set("Asia/Krasnoyarsk");
?>