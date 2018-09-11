<?php
error_reporting( E_ALL );
error_reporting( 0 );

require_once("project/MyJobs/page_ids.php");

global $EDIT_PROJECT_PAGE_ID;

$cur_form = $_GET['formid'];
$arch_cur = $_GET['arch'];
$result = dbquery("SELECT * FROM okb_db_itrzadan where (ID='".$render_row['ID']."') ");
$name = mysql_fetch_array($result);

$name2 = $name['TIP_FAIL'];
$name3 = $name['ID_edo'];
$proj_id = $name['ID_proj'];


if( $proj_id ) // Если это - проект
{
    $res = dbquery("SELECT * FROM okb_db_projects where ID='$proj_id'");
    $row = mysql_fetch_array($res);
    
    $asd = "<a href='index.php?do=show&formid=$EDIT_PROJECT_PAGE_ID&id=$proj_id'><img src='uses/project.png'></a>";
    $asd .= ' проект';
}
else
{    
    
    $result2 = dbquery("SELECT * FROM okb_db_edo_inout_files where (ID='".$name3."') ");
    $name2_2 = mysql_fetch_array($result2);

    $result3 = dbquery("SELECT * FROM okb_db_protocols where (ID='".$name3."') ");
    $name3_2 = mysql_fetch_array($result3);

    
if ( $name3 !== '0')	
    {
    
    
    
	if ($name2 == 0) 
    {
		$name2 = 110;
		$id_nam = $name3;
		$doc_nam = $name2_2['NAME_IN'];
		$doc_nam2 = $name2_2['NAME_IN'];
	}
	if ($name2 == 1) {
		$name2 = 111;
		$id_nam = $name3;
		$doc_nam = $name2_2['NAME_IN'];
		$doc_nam2 = $name2_2['NAME_IN'];
	}
	if ($name2 == 2) {
		$name2 = 150;
		$id_nam = $name3_2['ID'];
		$doc_nam = $name3_2['NUMBER'];
		$doc_nam2 = $name3_2['NUMBER'];
	}
	if ($arch_cur == 1){
		if ($name2 == 110) {
			$doc_nam3 = "ВХ | ";
		}
		if ($name2 == 111) {
			$doc_nam3 = "ИСХ | ";
		}
		if ($name2 == 150) {
			$doc_nam3 = "ПР | ";
		}
	}else{
		$doc_nam3 = "";
	}
	if ($cur_form==122){
		$doc_nam2 = "";
	}	
	$asd = "<a href='index.php?do=show&formid=".$name2."&id=".$id_nam."'><img src='uses/view.gif'></a> ".$doc_nam3.$doc_nam2;
}

if (($name['ID_zapr']!=='0') and ( $name3 == '0' ))
    {
        $asd = "<a href='index.php?do=show&formid=138&id=".$name['ID_zapr']."'><img src='uses/view.gif'></a>Запрос №".$name['ID_zapr'];
    }

if ($name['ID_zapr']=='0') { $disp = "style='display:none;'"; }
if ($cur_form == 122)
    {
        echo "<table><tr><td width='7px'>".$asd."</td><td>".$doc_nam."</td></tr></table></td></tr><tr ".$disp."><td class='Field' style='background: #d5e7ff; text-align:left; padding-left:6px;'>Запрос №</td><td class='Field' style='text-align:left; padding-left:10px;'><a href='index.php?do=show&formid=138&id=".$name['ID_zapr']."'><img src='uses/view.gif'></a>".$name['ID_zapr'];	
    }
} // if( $zak_id )
    
?>