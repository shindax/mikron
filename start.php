<?php
//////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	© 2012 Мирошников А.В.
//
//////////////////////////////////////////////////////

	if (!defined("MAV_ERP")) { die("Access Denied"); }

error_reporting(0);

// variables ////////////////////////////////////////////////////////////////////////

	$user = 0;	// Пользователь
	$start_time = microtime(true);
	$dbquery_index = 0;
	$MESSAGE = "";
	$top_offset = 28;
	$show_bottom_page == false;

// pageurl и do /////////////////////////////////////////////////////////////////////////

	$pageurl="index.php";
	$do = "none";							// Начальная страница
	$notrowurl=$pageurl;
	$id = $_GET["id"];
	$p0 = $_GET["p0"];
	$p1 = $_GET["p1"];
	$p2 = $_GET["p2"];
	$p3 = $_GET["p3"];
	$p4 = $_GET["p4"];
	$p5 = $_GET["p5"];
	$p6 = $_GET["p6"];
	$p7 = $_GET["p7"];
	$p8 = $_GET["p8"];
	$p9 = $_GET["p9"];
	if (isset($_GET["do"])) {
		if ($_GET["do"]=="profile") $do=$_GET["do"];		// Изменение собственного профиля
		if ($_GET["do"]=="users") $do=$_GET["do"];		// Пользователи (для Админа и редактора польз.)
		if ($_GET["do"]=="rights") $do=$_GET["do"];		// Права пользователя (для Админа и редактора польз.)
		if ($_GET["do"]=="rightgroups") $do=$_GET["do"];	// Группы доступа (для Админа)
		if ($_GET["do"]=="formgroups") $do=$_GET["do"];		// Группы форм (для Админа)
		if ($_GET["do"]=="forms") $do=$_GET["do"];		// Формы (для Админа)
		if ($_GET["do"]=="form") $do=$_GET["do"];		// Свойства формы (для Админа)
		if ($_GET["do"]=="formsitems") $do=$_GET["do"];		// Таблицы форм (для Админа)
		if ($_GET["do"]=="formsitem") $do=$_GET["do"];		// Таблица формы (для Админа)
		if ($_GET["do"]=="backup") $do=$_GET["do"];		// Сохранение и восстановление БД (для Админа)
		if ($_GET["do"]=="show") $do=$_GET["do"];		// Просмотр формы
		if ($_GET["do"]=="help") $do=$_GET["do"];		// Помощь (для Админа)
		if ($_GET["do"]=="dbconf") $do=$_GET["do"];		// Конфигурация БД (для Админа)
	}

// includes /////////////////////////////////////////////////////////////////////////

	include "config.php";
	include "locale/".$lang."/lang.php";
	include "includes/database.php";
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);
	include "includes/cookie.php";
	include "includes/config.php";
	include "includes/functions.php";
	if ($use_gzip) include "includes/gzip.php";

// Перепроверка $do и $pageurl //////////////////////////////////////////////////////

	function AddParamToURL($pname,$param) {
		global $pageurl;

		if (!is_array($param)) {
			$pageurl .= "&".$pname."=".$param;
		} else {
			$pageurl .= "&".$pname."%5B%5D=".implode("&".$pname."%5B%5D=",$param);
		}
	}

	if (isset($_GET["do"])) {
		if ($do!=="none") $pageurl .= "?do=".$do;
		if ($do=="users") {
			if (isset($_GET["p0"])) AddParamToURL("p0",$p0);
		}
		if ($do=="rights") $pageurl .= "&id=".$id;
		if ($do=="rightgroups") {
			if (isset($_GET["id"])) $pageurl .= "&id=".$id;
			if (isset($_GET["p0"])) AddParamToURL("p0",$p0);
		}
		if ($do=="show") {
			$xxx = dbquery("SELECT * FROM ".$db_prefix."forms where (ID='".$_GET["formid"]."')");
			if ($showed_form = mysql_fetch_array($xxx)) {
				$pageurl .= "&formid=".$_GET["formid"];
				if (isset($_GET["id"])) $pageurl = $pageurl."&id=".$id;
				if (isset($_GET["p0"])) AddParamToURL("p0",$p0);
				if (isset($_GET["p1"])) AddParamToURL("p1",$p1);
				if (isset($_GET["p2"])) AddParamToURL("p2",$p2);
				if (isset($_GET["p3"])) AddParamToURL("p3",$p3);
				if (isset($_GET["p4"])) AddParamToURL("p4",$p4);
				if (isset($_GET["p5"])) AddParamToURL("p5",$p5);
				if (isset($_GET["p6"])) AddParamToURL("p6",$p6);
				if (isset($_GET["p7"])) AddParamToURL("p7",$p7);
				if (isset($_GET["p8"])) AddParamToURL("p8",$p8);
				if (isset($_GET["p9"])) AddParamToURL("p9",$p9);
				$title = $showed_form["NAME"];
			} else {
				$do = none;
			}
		}
		if ($do=="dbconf") {
			if (isset($_GET["p0"])) $pageurl .= "&p0=".$p0;
		}
		if ($do=="form") $pageurl .= "&id=".$id;
		if ($do=="formsitem") $pageurl .= "&id=".$id;
		$notrowurl=$pageurl;
		if ($do=="show") {
			if (isset($_GET["row"])) $pageurl .= "&row=".$_GET["row"];
		}
	}

// includes /////////////////////////////////////////////////////////////////////////

	include "db_func.php";

// logged ////////////////////////////////////////////////////////////////////////////

	$logged = false;
	if ($user!==0) $logged = true;



// includes /////////////////////////////////////////////////////////////////////////

	include "includes/edit.php";

?>