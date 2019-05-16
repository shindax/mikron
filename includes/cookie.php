<?php
//////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	© 2012 Мирошников А.В.
//
//////////////////////////////////////////////////////

	if (!defined("MAV_ERP")) { die("Access Denied"); }




/////////////////////////////////////////////////////////////////////////////////////
//
// Запрет кеширования
//
/////////////////////////////////////////////////////////////////////////////////////

	Header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); //Дата в прошлом 
	Header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1 
	Header("Pragma: no-cache"); // HTTP/1.1 
	Header("Last-Modified: ".gmdate("D, d M Y H:i:s")."GMT");
	header("Content-type: text/html; charset=".$html_charset);




/////////////////////////////////////////////////////////////////////////////////////
//
// Права пользователя
//
/////////////////////////////////////////////////////////////////////////////////////

	function GetUserRights() {
		global $db_prefix, $user, $user_rights;

		$res = "";
		$ids = explode("|",$user["ID_rightgroups"]);
		$result = dbquery("SELECT * FROM ".$db_prefix."rightgroups");
		while ($group = mysql_fetch_array($result)) {
			if (in_array($group["ID"],$ids)) $res .= "\n".$group["RIGHTS"];
		}
		$user_rights = explode("\n",$res);
	}

	function GetShowRights() {
		global $db_prefix, $user, $user_showrights;

		$res = "";
		$ids = explode("|",$user["ID_forms"]);
		$result = dbquery("SELECT * FROM ".$db_prefix."viewgroups");
		while ($group = mysql_fetch_array($result)) {
			if (in_array($group["ID"],$ids)) $res .= "|".$group["RIGHTS"];
		}
		$user_showrights = explode("|",$res);
	}


/////////////////////////////////////////////////////////////////////////////////////
//
// LOGIN COOKIE
//
/////////////////////////////////////////////////////////////////////////////////////



	function GetUserWay() {
		global $db_prefix, $user, $user_lastform;

			$url = "index.php";
			if (isset($_GET["do"])) $url = $url."?do=".$_GET["do"];
			if (isset($_GET["formid"])) $url = $url."&formid=".$_GET["formid"];
			if (isset($_GET["id"])) $url = $url."&id=".$_GET["id"];
			if (isset($_GET["p0"])) $url = $url."&p0=".$_GET["p0"];
			if (isset($_GET["p1"])) $url = $url."&p1=".$_GET["p1"];
			if (isset($_GET["p2"])) $url = $url."&p2=".$_GET["p2"];
			if (isset($_GET["p3"])) $url = $url."&p3=".$_GET["p3"];
			if (isset($_GET["p4"])) $url = $url."&p4=".$_GET["p4"];
			if (isset($_GET["p5"])) $url = $url."&p5=".$_GET["p5"];
			if (isset($_GET["p6"])) $url = $url."&p6=".$_GET["p6"];
			if (isset($_GET["p7"])) $url = $url."&p7=".$_GET["p7"];
			if (isset($_GET["p8"])) $url = $url."&p8=".$_GET["p8"];
			if (isset($_GET["p9"])) $url = $url."&p9=".$_GET["p9"];

		$res = $url;
		if (isset($_COOKIE["user_way"])) $res = $_COOKIE["user_way"];

		$urls = explode("|",$res);
		$newurls = array();

		$user_lastform = $urls[0];
		if (($url==$urls[0]) && (count($urls)>1)) $user_lastform = $urls[1];

		if ($url!==$urls[0]) {
			if (isset($_GET["back"])) {
				if (count($urls)>1) {
					$urls_count = count($urls);
					for ($j=1;$j < $urls_count;$j++) $newurls[] = $urls[$j];
					$user_lastform = $newurls[0];
					if (($url==$newurls[0]) && (count($newurls)>1)) $user_lastform = $newurls[1];
				}
			} else {
				if (count($urls)<10) {
					$newurls[] = $url;
					$urls_count = count($urls);
					for ($j=0;$j < $urls_count;$j++) $newurls[] = $urls[$j];
				} else {
					$newurls[] = $url;
					for ($j=0;$j < 10;$j++) $newurls[] = $urls[$j];
				}
			}
			$res = implode("|",$newurls);
		}

		if ($user_lastform!=="index.php") {
			$user_lastform = $user_lastform."&back";
		} else {
			$user_lastform = $user_lastform."?back";
		}
		setcookie("user_way", $res, time()+(60*60*24), '/');
	}


	$user = 0;
	$user_rights = 0;
	$user_showrights = 0;
	$user_lastform = "index.php";
	
	$userpass = "0";
	
	// include_once('/../classes/ClassSESS.inc.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/classes/ClassSESS.inc.php');
	
	if(isset($_COOKIE["user_pass"]))
		$userpass = $_COOKIE["user_pass"];
	
	if(isset($_POST["user_login"]))
	{
		$userpass = trim($_POST["user_login"])."=".md5(trim($_POST["user_pass"]));
		
		// shindax добавляем куку раскрытого дерева "Штатное расписание"
		$O_show43 = '';
		$result = dbquery("SELECT ID FROM okb_db_otdel WHERE 1");
			while ( $row = mysql_fetch_assoc($result) ) 
				$O_show43 .= "|||db_otdel_43_".$row['ID'];

		setcookie( 'O_show43', $O_show43, time()+86400 );
	}
	
	if(isset($_GET["unlogin"]))
	{
		$userpass = "0";
		
		// shindax убиваем куку дерева "Штатное расписание"
		setcookie ("O_show43","",time()-3600,"/");
		
		SESS::uset('user');
		setcookie("user_id", "", time()-3600, '/');
	}
	
	if($userpass!=="0")
	{
		$login = explode("=",$userpass);
		
		$result = dbquery("SELECT * FROM ".$db_prefix."users where (LOGIN='".mysql_real_escape_string($login[0])."')");
		
		if($user = mysql_fetch_array($result))
		{
			GetUserWay();
			GetUserRights();
			GetShowRights();
			
			if($userpass !== $user["LOGIN"]."=".$user["PASS"])
			{
				$userpass = "0";
				$user = 0;
				$user_rights = 0;
			}
			else
			{
				if($user["STATE"]!=="0")
				{
					$userpass = "0";
					$user = 0;
					$user_rights = 0;
					$MESSAGE = $loc["18"];
				}
				else
				{
					$usr = $user;
					$usr['print_mode'] = $GLOBALS['print_mode'];
					
					SESS::set('user', $usr);
					setcookie("user_id", (int)$usr['ID'], time()+(60*60*24), '/');
					
					dbquery("UPDATE ".$db_prefix."users SET last_ip='".$_SERVER['REMOTE_ADDR']."' where(ID='".(int)$usr['ID']."')");
				}
				
			}
		}
		else
		{
			$userpass = "0";
			$user = 0;
		}
	}

	setcookie("user_pass", $userpass, time()+(60*60*24), '/');



/////////////////////////////////////////////////////////////////////////////////////
//
// OPENED COOKIE
//
/////////////////////////////////////////////////////////////////////////////////////

	$cname = "O_".$_GET["do"].$_GET["formid"];

	$opened = "||";
	if (isset($_COOKIE[$cname])) $opened = $_COOKIE[$cname];
	if (isset($_GET['setopened'])) {
		$opened=$_GET['setopened'];
	}
	if (isset($_GET['addopened'])) {
		$opened .= $_GET['addopened'];
	}
	if (isset($_GET['open'])) {
		$opened=str_replace("|".$_GET['open']."|","",$opened);
		$opened .="|".$_GET['open']."|";
	}
	if (isset($_GET['close'])) $opened=str_replace("|".$_GET['close']."|","",$opened);
	if (isset($_GET['closeall'])) $opened="||";
	if (isset($_GET['openall'])) $opened="|all|";
	setcookie($cname, $opened, time()+(60*60*24*30), '/');




	if (isset($_GET["loginunder"])) {
		if ($user["ID"]=="1") {
			$result = dbquery("SELECT * FROM ".$db_prefix."users where (ID='".$_GET["loginunder"]."')");
			if ($xxxuser = mysql_fetch_array($result)) {
				setcookie("user_pass", $xxxuser["LOGIN"]."=".$xxxuser["PASS"], time()+(60*60*24));
				header("Location: index.php");
			}
		}
	}
?>