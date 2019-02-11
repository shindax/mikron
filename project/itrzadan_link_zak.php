<?php
global $user;
$user_id = $user['ID'];

$form_id = $_GET['formid'];

$result = dbquery("SELECT * FROM okb_db_itrzadan where (ID='".$render_row['ID']."') ");
$name = mysql_fetch_array($result);
$name2 = $name['ID_zak'];
$proj_id = $name['ID_proj'];
$creator_id = $name['ID_users'];

$res = dbquery("SELECT ID_users FROM okb_db_resurs where ID=$creator_id");
$row = mysql_fetch_array($res);
$creator_res_id = $row['ID_users'];

$result2 = dbquery("SELECT * FROM okb_db_zak where (ID='".$name2."') ");
$name5 = mysql_fetch_array($result2);
$zak_tip = array(" ","ÎÇ","ÊÐ","ÑÏ","ÁÇ","ÕÇ","ÂÇ");

if( $form_id == 122 )
{
	$asd2 = "<span></span>";
	$div = "<div class='order_prj_div'>";

	if ($name2 !== '0')	
	{
		$asd2 = "<div><a href='index.php?do=show&formid=39&id=".$name2."'  target='_blank'><img src='uses/view.gif'></a><b>".$zak_tip[$name5['TID']]."&nbsp;&nbsp;".$name5['NAME']."</b>&nbsp;&nbsp;&nbsp;".$name5['DSE_NAME']."</div>";
	}

	if( $proj_id )
	{
		$result = dbquery("SELECT * FROM okb_db_projects where ID=$proj_id");
		$row = mysql_fetch_array($result);
		$asd2 = "<div><a href='index.php?do=show&formid=216&id=$proj_id' target='_blank'><img src='/uses/project.png'></a><b>".($row['name'])."</b></div>";
	}

	$div .= $asd2;
	if( $user_id == $creator_res_id )
		$div .= "<img src='uses/svg/link.svg' class='link_img' /></div>";
	$asd2 = $div;
}
else
{
	if ($name2 !== '0')	
	{
		$asd2 = "<a href='index.php?do=show&formid=39&id=".$name2."'  target='_blank'><img src='uses/view.gif'></a><b>".$zak_tip[$name5['TID']]."&nbsp;&nbsp;".$name5['NAME']."</b>&nbsp;&nbsp;&nbsp;".$name5['DSE_NAME']."";
	}

	if( $proj_id )
	{
		$result = dbquery("SELECT * FROM okb_db_projects where ID=$proj_id");
		$row = mysql_fetch_array($result);
		$asd2 = "<a href='index.php?do=show&formid=216&id=$proj_id' target='_blank'><img src='/uses/project.png'></a><b>".($row['name'])."</b>";
	}
}	
