<?php
error_reporting( 0 );
error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( "functions.php" );

global $pdo;

$add = $_POST['add'];
$can_edit = + $_POST['can_edit'] ? "" : "disabled";
$del_img_src = + $_POST['can_edit'] ? "uses/del.png" : "uses/del_dis.png";
$del_cause_img_class = + $_POST['can_edit'] ? "del_cause_img" : "del_cause_dis_img";
$del_img_class = + $_POST['can_edit'] ? "del_expl_img" : "del_dis_img";


if( $add )
{
    try
    {
        $query = "
                  INSERT
                  INTO `noncomplete_execution_causes` 
                  (`id`, `description`, `responsible_res_id`,`timestamp`)
                  VALUES 
                  (NULL, '', '[]', NOW() );
                  " ;

        $stmt = $pdo->prepare( $query );
        $stmt->execute();

    }
    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
    }
}

$data = GetNoncompleteExecutionCauses();
$expl = GetNoncompleteExecutionCauseExplanation();

$str = "<table class='tbl' id='table'>";

$str .= "<col width='5%'>";
$str .= "<col width='25%'>";
$str .= "<col width='40%'>";
$str .= "<col width='30%'>";

$str .= "<tr class='first'>";
$str .= "<td class='Field'>#</td>";
$str .= "<td class='Field'>".conv("Причина")."</td>";
$str .= "<td class='Field'>".conv("Подтверждения")."</td>";
$str .= "<td class='Field'>".conv("Ответственные")."</td>";
$str .= "</tr>";

$line = 1 ;
foreach ( $data AS $key => $value ) 
{
	$res_id_arr = [];
	$res_name_list = "";
	$persons = $value['persons'];
	foreach ( $persons as $pkey => $pvalue ) 
	{
		$res_id_arr[] = $pkey;
		$res_name_list .= "<span>$pvalue</span>";
	}


	$str .= "<tr>";
	$str .= "<td class='Field AC'><span class='row_number'>$line</span></td>";
	$str .= "<td class='Field AL'>
					<div class='cause_div_wrap'>
					<input data-id='$key' class='cause_description' value='{$value['description']}' $can_edit/>
					<img src='$del_img_src' class='$del_cause_img_class' data-id='$key' />
					</div>
				</td>";

	$str .= "<td class='Field AC'>";
	$str .= "<div class='cause_expl_div_wrap'>";
	$str .= "<div class='cause_expl_div' data-id='$key'>";
		if( isset( $expl[ $key ] ) )
			foreach( $expl[ $key ] AS $ekey => $evalue )
				$str .= "<div class='cause_expl_input' data-id='$ekey'>
							<input class='cause_expl' data-id='$ekey' value='$evalue' $can_edit />
							<img src='$del_img_src' class='$del_img_class' />
						</div>";
	
	$str .= "</div>";
	$str .= "<div class='cause_expl_add_div'><button class='btn btn-small btn-primary add_cause_expl' type='button' $can_edit data-id='$key' >".conv('Добавить')."</button></div>";	
	$str .= "</div>";	
	$str .= "</td>";

	$str .= "<td class='Field AC'>
			 	<div class='res_cell_wrap'>
					<div class='res_list' data-id='$key'>
					$res_name_list
					</div>
					<div class='res_list_ch_div'>
					<button data-id='$key' data-list='".join(",", $res_id_arr )."' class='btn btn-small btn-primary res_change_button' type='button' class='ch_res_list' $can_edit>".conv('Изменить')."</button>
					</div>
				</div>
			</td>";
	$str .= "</tr>";	
	$line ++;
}

$str .= "</table>";

echo  $str; // iconv("Windows-1251", "UTF-8", $str );
