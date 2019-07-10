<script type="text/javascript" src="/project/zadan/js/zadanres_zadan_full.js?12"></script>

<link rel='stylesheet' href='/project/zadan/css/style.css' type='text/css'>

<script language='javascript'>

// shindax 29.08.2017
$( function()
{

	let url_arr = window.location.href.split('#')
	if( url_arr.length > 1 )
	{
		let tr = $('#row1_' + url_arr[1])
		$( tr ).css('background','yellow').next('tr').css('background','yellow')				
		let top = $( tr ).position().top - 200 

		$('#vpdiv').animate({
		        scrollTop: top
		    }, 250);
	}

	$('.semifin_invoice').unbind('click').bind('click', semifinInvoiceButtonClick );
	$('.warehouse').unbind('click').bind('click', warehouseButtonClick );	

// shindax 13.06.2019
	// $('.noncomplete_execution_causes_select').unbind('change').bind('change', noncomplete_execution_causes_select_change )	

// check_zero_fact_causes()

});

function adjust_ui()
{
	$('.warehouse_count').unbind('keyup').bind('keyup', warehouse_count_keyup );
}

function warehouse_count_keyup()
{
	var val = Number( $( this ).val() );
	if( isNaN( val) )
	{
		$( this ).addClass( "error_number" );
		$('#issue_button').button('disable' );
	}
		else
		{
			$( this ).removeClass( "error_number" );
			$('#issue_button').button('enable' );
		}
}


function delete_res( el, message )
{
 if( confirm( message ) )
	{

	var checks = $('.empty_emp_check:checked');
	var list = '';

	if( checks.length )
	{
		var arr = [];
		$.each( checks , function( key, item )
		    {
		      var id = $( item ).data('id');
		      arr.push( id );
			  console.log 
		      $('tr[data-id=' + id + ']').remove();
		    });
		
		list = arr.join(',');
	}
	else
	{
		var list = $( el ).data('id');
		$('tr[data-id=' + list + ']').remove();		
	}

      	$.post(
          'project/zadan/ajax.DeleteResource.php',
          {
              list : list
          },
          function( data )
          {
//          	console.log( data );
          }
      );
    }
}


function semifinInvoiceButtonClick()
{
  var sel_arr = $("input[name='cur_zad_sel']:checked:enabled");
  var str = '&p0=';

  if( sel_arr.length )
		{
		  $.each( sel_arr , function( key, value )
		  {
		    var id = $( value ).attr('id');
		    if( id.length )
		    	if( id.indexOf( '_item_zad_') == -1 )
		    		str += id.replace('item_zad_','') + ",";
		  });

			 var url = "/index.php?do=show&formid=251" + str.slice(0,-1) ;
			 window.open( url );
		}
}

function warehouseButtonClick()
{
  var id = $( this ).data('id');
  var wh_id = $( this ).data('wh-id');
  var warehouse_name = $( this ).data('warehouse-name');
  var cell_name = $( this ).data('cell-name');
  var tier_name = $( this ).data('tier-name');  
  var pattern = $( this ).data('pattern');  

  if( ! tier_name )
   	tier_name = '���';

    $( '#warehouse_dialog' ).dialog({
    	  resizable: false,
        modal: true,
        closeOnEscape: true,
        height: 250,
        width: 1000,
//        position: { my: 'left top', at: 'left top', of: this },
        create : function()
        	{
				OpenWarehouseDialog( id, pattern )        	
			},
        open : function()
        	{
        		OpenWarehouseDialog( id, pattern );
        	},

        buttons:
        [
            {
             id : 'issue_button',
             text: '������',
             disabled : true, 
            click : function ()
            {
            		var warehouse_inputs = $('.warehouse_count');
            		var ids = [];
					var counts = [];

				    $.each( warehouse_inputs , function( key, item )
				    {
				      var id = $( item ).parent().parent().attr('data-id');
				      var val = Number( $( item ).val() );
				      if( val )
				      {
				      	ids.push( id )
				      	counts.push( val )
				      }
				      
				    });

			      	$.post(
			          'project/zadan/ajax.ReserveItems.php',
			          {
			              ids : ids,
			           	  counts : counts,
				         	  user_id : user_id
			          },
			          function( data )
			          {
			          	//console.log( data );
			          }
			      );


                	$(this).dialog('close');
            }
            },
            {
            text : '\u041E\u0442\u043C\u0435\u043D\u0430',
            click : function ()
            		 {
                        $(this).dialog('close');
                    }
            }
        ]
    });

}


function OpenWarehouseDialog( id, pattern )
{
        		$('div.ui-widget-header').css('background','#AFEEEE'); // ���� ��������� �������
        		$('#copy_dialog_copy_button').addClass('ui-state-disabled');

        		      	$.post(
					          'project/zadan/ajax.FindInWarehouse.php',
					          {
					              pattern : pattern,
					              id : id
					          },
					          function( data )
					          {
					          	$('#warehouse_dialog').html( data );
								adjust_ui();
					          }
					      );
}

// shindax 29.08.2017

function SetNewValue(from_id,to_id,val,url) 
{
	fr_obj = document.getElementById(from_id);
	xxx = fr_obj.value*val;
	yyy = Math.ceil(xxx*100);
	to_obj = document.getElementById(to_id);
	to_obj.value = yyy/100;
	vote(to_obj,url+to_obj.value);

// shindax 13.06.2019
// check_zero_fact_causes_by_id( from_id.split('_')[1] )

}

// shindax 13.06.2019
function check_zero_fact_causes_by_id( id ) 
{
      let num = $( 'input[id=num_'+ id + ']').val()
      let fnum = $( 'input[id=fnum_'+ id + ']').val()

      let norm = $( 'input[id=norm_'+ id + ']').val()
      let fnorm = $( 'input[id=fnorm_'+ id + ']').val()


       if( ( fnorm > norm ) || ( fnum == 0 ) )
       {
      		$( 'a.ready[data-id=' + id + ']' ).hide()
      		$( 'img.dis_img[data-id=' + id + ']' ).show()
       	}
       			else
       			{
       				$( 'a.ready[data-id=' + id + ']' ).show()
      				$( 'img.dis_img[data-id=' + id + ']' ).hide()
       				$( '.noncomplete_execution_causes_select[data-id=' + id + ']').find( 'option[value=0]' ).prop('selected', 'true');
       			}
       
} // shindax 13.06.2019 function check_zero_fact_causes_by_id( id ) 

function check_zero_fact_causes() 
{
	let plans = $('input[ name ^= "db_zadan_NORM_edit_"]' )

	var nonzero_plans = plans.filter( function( item ) 
		{
			let val = parseFloat( $( plans[ item ] ).val() )
			let id = $( plans[ item ] ).attr('id')
			if( isNaN( val ) || val == 0 || id[0]=='_')
  	 			return false;
			return true;
		});

    $.each( nonzero_plans , function( key, item )
    {
       let id = $( item ).attr('id')
       check_zero_fact_causes_by_id( id.split('_')[1] )
    });

} // function check_zero_fact_causes() 


function noncomplete_execution_causes_select_change(argument) 
{
	let id = $( this ).data('id')
	let val = parseFloat( $( this ).find('option:selected').val() )
	
	if( val )
	{
		$( 'a.ready[data-id=' + id + ']' ).show()
		$( 'img.dis_img[data-id=' + id + ']' ).hide()
	}
		else
		{
			$( 'a.ready[data-id=' + id + ']' ).hide()
			$( 'img.dis_img[data-id=' + id + ']' ).show()
			check_zero_fact_causes_by_id( id )
		}
}// function noncomplete_execution_causes_select_change(argument) 

// shindax 13.06.2019

function HLID(x) {
	obj = document.getElementById("row1_"+x);
	obj.className = "htr";
	obj = document.getElementById("row2_"+x);
	obj.className = "htr";
	obj = document.getElementById("row3_"+x);
	obj.className = "htr";
	}


function DHLID(x) {
	obj = document.getElementById("row1_"+x);
	obj.className = "";
	obj = document.getElementById("row2_"+x);
	obj.className = "";
	obj = document.getElementById("row3_"+x);
	obj.className = "";
	}


window.onload=
	document.getElementById('vpdiv').parentNode.parentNode.parentNode.rows[0].style.display='none'; 
	doc_cook = document.cookie;
	get_top_scrl_full = doc_cook.substr((doc_cook.indexOf('scroll')+7), 5);
	get_top_scrl_x = get_top_scrl_full.indexOf('x');
	if (get_top_scrl_x > 0) 
		get_top_scrl_numb = get_top_scrl_full.substr(0, get_top_scrl_x);
      else
        get_top_scrl_numb = get_top_scrl_full;

//	setTimeout("document.getElementById('vpdiv').scrollTop = get_top_scrl_numb",3000);
</script>

<style>
	table.tbl tr.htr {
		background: #ebf3fe;
	}
	table.tbl tr.highlite 
	{
		background: #cbdef4;
	}
	
	
	a.acl {
		font-size: 11pt;
		text-decoration: none;
	}
	
	div.AR
	{
    float : right ;
    padding:0;
    margin:0;
    width:100%;
	}
	.ch_resource_button
	{
    width : 100% ;
	}

    
.change_resource_select_class_div
{
    position:relative;
    overflow: hidden ;     
}

.change_resource_select
{
    display:block;
    position:absolute;
    overflow: auto ; 
}    

#caption
{
    position : fixed ;
    padding: 12px 0 13px 0px !IMPORTANT;
    width : 2000;
    top: 0px;
    left:0px;
    z-index : 1000;
    background-color : #DDD ;
	
}

div.popup
{
    z-index : 1001 !IMPORTANT;
}

#table_caption_div
{
    position : absolute ;
    padding: 9px 0 ;
    top: 38px;
    height: 76px !IMPORTANT;
    z-index : 102;
    overflow : hidden;
}

#main_table_div
{
  position : relative ;
  top: 22px;
}

.Field .res_status
{
	background-color:#fff;
	border:1px solid #222;
	color:#111; 
	padding:0 2px 0 2px;
	font-weight:bold;
	margin:2px 0px 0px 5px;
	}


.zak_link, .dse_link
{
	margin : 0;
	padding : 0;
	font-weight : bold;
}

.zak_link:hover, .dse_link:hover
{
	color: red;
}

</style>

<?php

global $user;
echo "<script>var user_id=".$user['ID']."</script>";

$pass = 0 ;
$offset = -93;
$offset2 = -146;
setlocale(LC_ALL, 'en_US.UTF8');

echo "<script> var offset=$offset; </script>";

$smena = $_GET["p1"];
if (($smena!=="1") && ($smena!=="2") && ($smena!=="3")) $smena = "1";
$pdate = $_GET["p0"]*1;
$date = IntToDate($pdate);

echo "<script>cur_date = $pdate; smena = $smena; </script>";

// shindax 26.12.2017


echo "
<div id='warehouse_dialog' class='hidden' title='������ �� ������'>
</div>";

echo "
<div id='copy_dialog' class='hidden' title='���������� ������� �������'>
<select id='copy_dialog_shift'><option value='0'>...</option><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option></select>&nbsp;
<input id='copy_dialog_date' />&nbsp;
<select id='copy_dialog_resurs'></select>
</div>";

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$pageurl_addzad = "index.php?do=show&formid=65&p0=".$pdate."&p1=".$smena."&p2=";
		$calendar_url = "index.php?do=show&formid=63&p0=".$date;
		$print_url = "index.php?do=show&formid=84&p0=".$pdate."&p1=".$smena;
		$print_resurs_url = "index.php?do=show&formid=83&p0=".$pdate."&p1=";

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	include "project/calc_zak.php";
	include "functions.php";

	$editing = false;
	$modering = false;
	$editingplan = false;

	$today = explode(".",$today_0);
	$today = $today[2]*10000+$today[1]*100+$today[0];

	$real_today = $today;

	if ($pdate>=$today) $editingplan = true;

	if ($pdate<$today) $modering = true;
	if ($pdate==$today) $modering = true;

	$theday = mktime (0,0,0,date("m") ,date("d") ,date("Y"));
	$today_m=date("d.m.Y",$theday-(8*86400));
	$today = explode(".",$today_m);
	$today = $today[2]*10000+$today[1]*100+$today[0];

	if ($pdate>$today) $editing = true;
	if ($pdate==$today) $editing = true;

	if ($pdate<$today) $modering = false;

	if (db_check("db_zadan","MEGA_REDACTOR")) $editing = true;
	if (db_check("db_zadan","MEGA_REDACTOR")) $editingplan = true;
	if (db_check("db_zadan","MEGA_REDACTOR")) $modering = true;

	$redirected = false;


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// �������� ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// addnewres /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// shindax 21.12.2016
  if( isset($_GET['used_ignore']) )
    $used_ignore = 1 ;
      else
        $used_ignore = 0 ;
// shindax 21.12.2016

	if (isset($_POST['addnewres'])) {
		$ids = $_POST['addnewres'];
		if ((db_adcheck("db_zadanres")) && ($editing)) {
			$ids_count = count($ids);
			for ($j=0;$j < $ids_count;$j++) {
				dbquery("INSERT INTO ".$db_prefix."db_zadanres (DATE, SMEN, ID_resurs) VALUES ('".$pdate."', '".$smena."', '".$ids[$j]."')");
			}
		}
		redirect($pageurl."&event","script");
		$redirected = true;
	}
	

// delresid /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if (isset($_GET['delresid'])) {
		$dodelid = $_GET['delresid'];
		$used = true;
		$xxx1 = dbquery("SELECT * FROM ".$db_prefix."db_zadanres where  (ID='".$dodelid."')");
		if ($xxx = mysql_fetch_array($xxx1)) {
			$used = false;
			$result = dbquery("SELECT * FROM ".$db_prefix."db_zadan where  (DATE='".$xxx["DATE"]."') and (SMEN='".$xxx["SMEN"]."') and (ID_resurs='".$xxx["ID_resurs"]."')");
			if (mysql_fetch_array($result)) { 
				$used = true;
			}
		} 
		
		
		
    if( $used_ignore )
    {
       $used_ignore_res = $xxx["ID_resurs"];
       $used_ignore_date = $xxx["DATE"] ;
       $used_ignore_smen = $xxx["SMEN"] ;
       $query = "DELETE FROM ".$db_prefix."db_zadan where  (DATE='$used_ignore_date') and (SMEN='$used_ignore_smen') and (ID_resurs='$used_ignore_res')";
       $result = dbquery( $query );
       $used = 0 ;
    }
	
		// ������� ������� �� �������� ������� �� ��������� ����� ���������� ������� (������)
	
		$current = mysql_fetch_assoc(dbquery("SELECT * FROM okb_db_zadanres WHERE  ID = " . $dodelid), 0);
	
		$prev_name = mysql_result(dbquery("SELECT NAME FROM `okb_db_zadanres`
		
		LEFT JOIN okb_db_resurs ON okb_db_resurs.ID = okb_db_zadanres.ID_resurs
										WHERE SMEN = " . $current['SMEN'] .
										" AND okb_db_zadanres.DATE = '" . $current['DATE']. "'". 
										" AND ID_resurs = " . $current['ID_resurs']), 0);
										
									
		$next_resource = mysql_result(dbquery("SELECT okb_db_zadanres.`ID_resurs` FROM `okb_db_zadanres`
		LEFT JOIN okb_db_resurs ON okb_db_resurs.ID = okb_db_zadanres.ID_resurs
										WHERE SMEN = " . $current['SMEN'] .
										" AND okb_db_zadanres.DATE = '" . $current['DATE'] . "'" . 
										" AND NAME > '" . $prev_name . "' ORDER BY NAME LIMIT 1"), 0);
	
		if ((!$used) && (db_adcheck("db_zadanres")) && ($editing)) {
			dbquery("DELETE from ".$db_prefix."db_zadanres where (ID='".$dodelid."')");
		}
		
		redirect($pageurl."&event&current_resource_id=" . $next_resource,"script");
		$redirected = true;
	}

// ���������� ������� //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if (isset($_POST["add_zadan_to_resurs"])) 
	{

		$idresurs = $_POST["add_zadan_to_resurs"];
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_resurs where (ID = '".$idresurs."')");
		if (($resurs = mysql_fetch_array($xxx)) && ($editing)) {

		   // ���������� ������� � �������
			if (db_adcheck("db_zadan")) {
			$zak_zad = $_POST["zak_zad"];

			$zak_zad_count = count($zak_zad);
			for ($j=0;$j < $zak_zad_count;$j++) {
				$ID_zakdet = "0";
				$ID_zak = "0";
				$ID_park = "0";
				$xxx = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID = '".$zak_zad[$j]."')");
				if ($xxx = mysql_fetch_array($xxx)) {
					$ID_zakdet = $xxx["ID_zakdet"];
					$ID_park = $xxx["ID_park"];
					$yyy = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID = '".$ID_zakdet."')");
					if ($yyy = mysql_fetch_array($yyy)) $ID_zak = $yyy["ID_zak"];
				}
				if ($ID_zakdet!=="0") dbquery("INSERT INTO ".$db_prefix."db_zadan (SMEN, ID_park, ID_zak, ID_zakdet, ID_operitems, ID_resurs, DATE, EDIT_STATE) VALUES ('".$smena."', '".$ID_park."', '".$ID_zak."', '".$ID_zakdet."', '".$zak_zad[$j]."', '".$idresurs."', '".$pdate."', '0')");
			}
			}

		}
		redirect($pageurl."&event","script");
		$redirected = true;
	}

// okoperid /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if (isset($_GET['okoperid'])) 
	{
		$id = $_GET['okoperid'];

		if (db_check("db_operitems","STATE")) {
		$result = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (ID_operitems = '".$id."') and (EDIT_STATE = '0')");
		if (!mysql_fetch_array($result)) {

		   // �������� ��������
			dbquery("Update ".$db_prefix."db_operitems Set STATE:='1' where (ID = '".$id."')");

		   // ����������� �����
			// ??	CalculateOperitem($xxxzad["ID_operitems"]);
		}
		}

		redirect($pageurl."&event","script");
		$redirected = true;
	}

// addbytabel /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if (isset($_GET['addbytabel'])) 
	{

		if ((db_adcheck("db_zadanres")) && ($editing)) 
		{
		   ///////////////////////////////////////////
			$resurs_IDs = Array();
			$query = "SELECT * FROM ".$db_prefix."db_otdel where INSZ = 1";
			$xxx = dbquery( $query );
			while($otdel = mysql_fetch_array($xxx)) 
			{
				$query = "SELECT * FROM ".$db_prefix."db_shtat where ID_otdel = {$otdel["ID"]} AND presense_in_shift_orders=1";
				$xxxs = dbquery( $query );

	          while($shtat = mysql_fetch_array($xxxs)) 
	            if (!in_array($shtat["ID_resurs"],$resurs_IDs)) 
	              $resurs_IDs[] = $shtat["ID_resurs"];

			}

		   ///////////////////////////////////////////

			$ids = array();

			$query = "
					SELECT * FROM okb_db_tabel 
						WHERE 
						SMEN=$smena
						AND
						DATE=$pdate 
						AND
						TID IN (0, 1, 2, 4, 5, 6, 12)";

			$xxx = dbquery( $query );

			while ($res = mysql_fetch_array($xxx)) 
			{
				$tid = $res['TID'];
				$new_smena = $smena;

				 if ( $tid == 1 ) 
				 { 
				 	$res_id = $res["ID_resurs"];
	              	
				 	$query = "	SELECT tab_sti.SMEN AS shift, res.NAME AS res_name
				 				FROM okb_db_resurs AS res
								LEFT JOIN okb_db_tab_st AS tab_st ON tab_st.ID = res.ID_tab_st
				 				LEFT JOIN okb_db_tab_sti AS tab_sti ON tab_sti.ID_tab_st = tab_st.ID
				 				WHERE 
				 				tab_sti.DATE = $pdate
				 				AND
				 				res.ID = $res_id";

				 	$tmp_res = dbquery( $query );
				 	$tmp_res = mysql_fetch_array( $tmp_res );
				 	$new_smena = $tmp_res['shift'];
				 }

				// 21.09.2017 - �� ������� ������� �������� � ���������� � ������� � �������� �� ������ 
				if ( in_array( $res['ID_resurs'], array( 545, 84, 304, 678 )) || $new_smena == 0 ) 
					continue;
				
			   if (in_array($res["ID_resurs"],$resurs_IDs)) 
			   {
            		$xxres = dbquery("SELECT * FROM okb_db_zadanres where SMEN=$new_smena AND DATE=$pdate AND ID_resurs={$res["ID_resurs"]}");
            		
            		if (!mysql_fetch_array($xxres)) 
            		{
            			$query = "INSERT INTO okb_db_zadanres 
            						( ORD, SMEN, DATE, ID_resurs, is_multimachine, multimachine_fact ) 
            				VALUES ( 0, $new_smena, $pdate ,{$res["ID_resurs"]}, 0, 0 )";
              			
              			dbquery( $query );
              			lg( "log.txt", "TID : $tid : ".$query );
            		}
			   }
			}
		}
		redirect($pageurl."&event","script");
		$redirected = true;
	}


if (!$redirected) 
{

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ����� ������ ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function OpenID( $item, $active = 1 ) 
{
	global $db_prefix, $editing, $pageurl_addzad, $pdate, $smena, $date, $pageurl, $print_resurs_url, $ID_resurs_mults;

	$result = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (DATE = '".$pdate."') and (ID_resurs = '".$item["ID_resurs"]."') and (SMEN = '".$smena."') order by ORD");
	$task_count = mysql_num_rows ($result);

    if( $active )
    {
      $highlight = 'highlite';
      $prefix = '';
    }
        else
        {
          $highlight = 'highlite_inactive';
          $prefix = '_';
        }

	   // ����� ���� � ���� ///////////////////////////////////////////

			$plan_n = 0;
			$fact_n = 0;
			$fact = 0;

	//	$result = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (DATE = '".$pdate."') and (ID_resurs = '".$item["ID_resurs"]."') and (SMEN = '".$smena."') order by ORD");
		while($res = mysql_fetch_array($result))
		{
			$plan_n += (1*$res["NORM"]);
			$fact_n +=  (1*$res["NORM_FACT"]);
			$fact += (1*$res["FACT"]);
		}
		
	   // ������
		echo "<tr data-id='".$item["ID"]."' data-date='" . $item["DATE"] . "' data-resource-id='" . $item["ID_resurs"] . "' class='$highlight' style='height: 25px;'>";

	   // ����������
		Field($item,"db_zadanres","ORD",$editing,"style='width: 30px;'","","rowspan='2'");

	   // ������
		$resource_tid = dbquery("SELECT TID FROM okb_db_tabel where (DATE = '".$item["DATE"]."') and (ID_resurs = '".$item["ID_resurs"]."') GROUP BY DATE");
		$resource_tid = mysql_fetch_assoc($resource_tid);
		// echo $resource_tid['TID'];
		$resource_status_array = array(1=>'��', 2=>'��', 3=>'�', 4=>'�', 8=>'��', 9=>'��', 10=>'�', 11=>'��', 12=>'�', 13=>'��', 14=>'��', 15=>'��', 16=>'��');
		
		if(isset($resource_status_array[$resource_tid['TID']]))
			$resource_status = '<span class="res_status">'.$resource_status_array[$resource_tid['TID']].'</span>';
		else
			$resource_status = '';
		// var_dump($resource_status);
		
 
		echo "<td id='".$prefix."id_res_zadanres_".$item["ID_resurs"]."' class='Field' style='text-align: left;' colspan='5' rowspan='2'>
		
		<input type='checkbox' name='is_multimachine' style='float:right' title='��������������' " . ($item['is_multimachine'] == 1 ? "checked" : "") . "/> 
		<input type='text' name='multimachine_fact' style='float:right;width:18px;' title='���� ���������������' value='" . ($item['multimachine_fact'] != 0 ? $item['multimachine_fact'] : "") . "' />
		<b>".FVal($item,"db_zadanres","ID_resurs")."</b>".$resource_status;
		echo "<a href='".$print_resurs_url.$smena."&p2=".$item["ID_resurs"]."' target='_blank' style='margin-left:15px;'>�����������</a>";
		echo " | <a href='index.php?do=show&formid=211&p0=".$_GET['p0']."&p1=".$_GET['p1']."&p2=".$item['ID_resurs']."' target='_blank'>����������� (�����)</a>";
		echo "</td>";

		echo "<td class='Field' rowspan='2'></td>";
		echo "<td class='Field' rowspan='2'></td>";
		echo "<td class='Field' rowspan='2'><b>".$plan_n."</b></td>";
		echo "<td class='Field' rowspan='2'></td>";
		echo "<td class='Field'></td>";
		echo "<td class='Field'><b>".$fact_n."</b></td>";
		echo "<td class='Field'><b>".$fact."</b></td>";
		echo "<td class='Field' rowspan='2'></td>";
		echo "<td class='Field' colspan='2' rowspan='2'><a href='".$pageurl."&event'><- ��������</a></td>";
		
		if( $task_count )
			echo "<td class='Field' rowspan='2'><input type='checkbox' id='".$prefix."sel_all_".$item["ID_resurs"]."' onchange='sel_all_zad(".$item["ID_resurs"].", this);'></td>";
		  else
			echo "<td class='Field' rowspan='2'><input type='checkbox' class='empty_emp_check' data-id='".$item["ID"]."'></td>";

	   // ��������
		$showdel = "";
		if ((db_adcheck("db_zadanres")) && ($editing)) 
		{
			$used = false;
			$result = dbquery("SELECT 1 FROM ".$db_prefix."db_zadan where  (DATE='".$item["DATE"]."') and (SMEN='".$item["SMEN"]."') and (ID_resurs='".$item["ID_resurs"]."')");
			if (mysql_fetch_assoc($result)) $used = true;
			$ID_resurs_mults = $item['ID_resurs'];
			

		if( $task_count )
		{
			if (!$used) 
			{
				$showdel = "<img onclick='if (confirm(\"�������, ��� ������ ������� ������ �� ������ ?\")) location.href=\"$pageurl&delresid=".$item["ID"]."\";' style='cursor: hand;' alt='�������' src='uses/del.png' title='�������'>";
			}
			else
			{
        		$showdel = "<img id='del_res_img_".$item["ID_resurs"]."' class='hidden' onclick='if (confirm(\"�������, ��� ������ ������� ������ �� ������ ?\")) location.href=\"$pageurl&used_ignore=1&delresid=".$item["ID"]."\";' style='cursor: hand;' alt='�������' src='uses/del.png' title='�������'>";
			}
		 }
		 else
		 {
        		$showdel = "<img data-id='".$item["ID"]."' onclick='delete_res( this, \"�������, ��� ������ ������� ������ �� ������ ?\")' style='cursor: hand;' alt='�������' src='uses/del.png' title='�������'>";
		 }
		}
		echo "<td class='Field' rowspan='2'>".$showdel."</td>";

		echo "</tr>\n";


	   // ���� �������

			$in_tabel = false;
			$result_tabel = dbquery("SELECT * FROM ".$db_prefix."db_tabel where (DATE = '".$pdate."') and (ID_resurs = '".$item["ID_resurs"]."')");
			if ($tabel = mysql_fetch_array($result_tabel)) $in_tabel = true;

		echo "<tr data-id='".$item["ID"]."' class='$highlight'>";
		echo "<td class='Field' colspan='2'><b>����. ���.</b></td>";

			if (!$in_tabel) 
				echo "<td class='Field'></td>";
			
			if ($in_tabel) 
				Field($tabel,"db_tabel","SPEC",$editing,"",""," style='max-width: 50px;' ");

		echo "</tr>\n";

		// ����������� �����������
		
			$used = 0;
			$itognorm = 0;
			$itognormfact = 0;
			$itogfact = 0;
			$result = dbquery("SELECT * FROM ".$db_prefix."db_zadan where  (DATE='".$item["DATE"]."') and (SMEN='".$item["SMEN"]."') and (ID_resurs='".$item["ID_resurs"]."') and (EDIT_STATE='1')");
			while($usres=mysql_fetch_array($result)) {
				/*$result2 = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID='".$usres['ID_operitems']."')");
				$usres2=mysql_fetch_array($result2);*/
				$itognorm+=$usres['NORM'];
				$itognormfact+=$usres['NORM_FACT'];
				$itogfact+=$usres['FACT'];
				$used = 1;
			}
			if($used == 1) {
				if ($itogfact == '0'){
					$itogfact2 = '0';
				}else{
					$itogfact2 = round(($itognormfact/$itogfact),2);
				}
				echo "<tr><td class='Field' colspan='2' style='text-align:right;background:#cbdef4;'><b>�����:</b>
				</td><td class='Field' style='background:#cbdef4;'>����������� ����������� <b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(".$itogfact2.")</b></td></tr>";
			}

	   // ������� �� ������ ///////////////////////////////////////////

		$result = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (DATE = '".$pdate."') and (ID_resurs = '".$item["ID_resurs"]."') and (SMEN = '".$smena."') order by ORD");

	//	mysql_data_seek($result, 0);
		while($res = mysql_fetch_array($result)) 
			OpenZadanID($res, $active);
}

function OpenZadanID( $item , $active = 1 ) 
	{
		global $db_prefix, $editing, $modering, $editingplan, $pageurl, $ID_resurs_mults, $smena, $date;
		
    $item_id = $item["ID"];

    if( $active )
      $prefix = '';
        else
          $prefix = '_';
        
	   // ������
		//echo "<tr id='row1_".$item["ID"]."' onmouseover=\"HLID(".$item["ID"].");\" onmouseout=\"DHLID(".$item["ID"].");\">";

		$result = dbquery("SELECT DSE_NAME,TID,NAME FROM ".$db_prefix."db_zak where (ID = '".$item["ID_zak"]."')");
		$zak = mysql_fetch_array($result);
		$result = dbquery("SELECT ID,NAME,OBOZ FROM ".$db_prefix."db_zakdet where (ID = '".$item["ID_zakdet"]."')");
		$izd = mysql_fetch_array($result);
		$result = dbquery("SELECT ORD,STATE,NUM_ZAK,NUM_ZADEL,BRAK,NORM_ZAK,ID,NORM_FACT,NORM FROM ".$db_prefix."db_operitems where (ID = '".$item["ID_operitems"]."')");
		$oper = mysql_fetch_array($result);
	/*	$result = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID_zak = '".$item["ID_zak"]."') and (PID = '0')");
		$first_dse = mysql_fetch_array($result);*/
		echo "<tr id='".$prefix."row1_".$item["ID"]."' data-id='" . $item['ID'] . "' data-dse-id='" . $izd['ID'] . "'>";

		$tid = FVal($zak,"db_zak","TID");
		$name = $tid." ".$zak["NAME"];
	   // �
		Field($item,"db_zadan","ORD",$editing,"style='width: 30px;'","","rowspan='2' ");

	   // ID
		echo "<td class='Field' style='text-align: center;position:relative' rowspan='2'><b>".$item["ID"]."</b><br/>

		</td>";

		$in_sklad = false;

		$order_name = $name;
		$dse_name = $zak["DSE_NAME"];
		$draw_name = $izd["OBOZ"];
		$dse_pattern = "$order_name ".($izd["NAME"])." $draw_name";

		$query = "SELECT ID, ID_sklades_yarus
				FROM `okb_db_sklades_detitem` 
				WHERE NAME LIKE '$dse_pattern'";

		$result = mysql_query( $query );
		$link = "";

		if ( mysql_num_rows( $result ) ) 
		{
			$in_sklad = true;
			$row = mysql_fetch_assoc($result);
			$wh_id = $row['ID'];
			$tier = $row['ID_sklades_yarus'];

			$query = "SELECT
						okb_db_sklades_yaruses.ORD AS tier_name,
						okb_db_sklades_item.`NAME` AS cell_name,
						okb_db_sklades.`NAME` AS warehouse_name
						FROM
						okb_db_sklades_yaruses
						INNER JOIN okb_db_sklades_item ON okb_db_sklades_yaruses.ID_sklad_item = okb_db_sklades_item.ID
						INNER JOIN okb_db_sklades ON okb_db_sklades_item.ID_sklad = okb_db_sklades.ID
						WHERE okb_db_sklades_yaruses.ID = $tier
						";

			$result = mysql_query( $query );
			$row = mysql_fetch_assoc($result);
			$warehouse_name = $row['warehouse_name'];
			$cell_name = $row['cell_name'];
			$tier_name = $row['tier_name'];						

			$link = "<span style='float:right'><a href='#'><img class='warehouse' data-id='".( $item["ID"] )."' data-wh-id='$wh_id' data-warehouse-name='$warehouse_name' data-cell-name='$cell_name' data-tier-name='$tier_name' data-pattern='$dse_pattern' src='/style/packages.png' style='width:50%'/></a></span>";
		}



	   // ����� / ��� 
		echo "<td class='Field dse' style='text-align: left;" . ($in_sklad ? 'background-color:#e2ffe3' : '') . "'><span style='float:right'><a href='#'><img class='print' src='/style/print.png' style='width:70%'/></a></span>
		<span style='float:right'><img class='semifin_invoice' src='/style/report.png' style='width:70%;height:90%'/></span>
    $link
		<a title='������� � ������' class='zak_link' href='index.php?do=show&formid=39&id={$item['ID_zak']}' target='_blank'>$name {$zak["ID_zak"]}</a><a title='������� � ���������� ���' class='dse_link' href='index.php?do=show&formid=52&id={$izd["ID"]}' target='_blank'>{$zak["DSE_NAME"]}<br>{$izd["OBOZ"]} {$izd["NAME"]}</a>

		</td>";

	   // �
		Field($oper,"db_operitems","ORD",false,"","","");

	   // ��������
		$pic = "";
		if (($item["EDIT_STATE"]=="1") && ($oper["STATE"]=="0")) {
			$result = dbquery("SELECT 1 FROM ".$db_prefix."db_zadan where (ID_operitems = '".$item["ID_operitems"]."') and (EDIT_STATE = '0')");
			if (!mysql_fetch_assoc($result)) {
				$pic = "<img onclick='if (confirm(\"��������� �������� � ������ - ���������?\")) location.href=\"$pageurl&ID_zak=".$item["ID_zak"]."&okoperid=".$item["ID_operitems"]."\";' style='cursor: hand; margin-right: 5px;' alt='��������� �������� � ������ - ���������' src='uses/ok.png' title='��������� �������� � ������ - ���������'>";
			}
		}
		Field($item,"db_zadan","ID_operitems",false,"",$pic,"");

	   // ������������
		echo "<td class='Field'><select onchange='change_park_sz(".$item['ID'].", this.options[this.selectedIndex].id.substr(10));'><option id='".$prefix."mark_p_sz_0'>-----";
		$result3 = dbquery("SELECT ID, MARK FROM ".$db_prefix."db_park order by MARK");
		while ($res_t3 = mysql_fetch_array($result3)) 
		{
			if ($item['ID_park']==$res_t3['ID']) { echo "<option id='".$prefix."mark_p_sz_".$res_t3['ID']."' selected>".$res_t3['MARK'];}else{ echo "<option id='".$prefix."mark_p_sz_".$res_t3['ID']."'>".$res_t3['MARK'];}
		}
		echo "</select></td>";

	   // ���-�� �������� �� �����
		$rcount = $oper["NUM_ZAK"]*1 - $oper["NUM_ZADEL"]*1;
		if ($oper["BRAK"]*1==1) $rcount = $oper["NUM_ZAK"]*1;

	   // ����
	    $nummsumm_expl = explode("|", $item['NUMSUMM_PLAN']);
		if (($editing) && (db_adcheck("db_zadan"))) 
		{
			echo "<td class='Field'><input style='width:45px;' onchange='add_numsumm(".$item['ID_resurs'].",".$item['ID_operitems'].",0,this.value);' value='".$nummsumm_expl[0]."'>/<input style='width:45px;' onchange='add_numsumm(".$item['ID_resurs'].",".$item['ID_operitems'].",1,this.value);' value='".$nummsumm_expl[1]."'></td>";
		}else
		{
			echo "<td class='Field'>".$nummsumm_expl[0]."/".$nummsumm_expl[1]."</td>";
		}
		$nxx = 0;
		if ($rcount>0) 
		$nxx = $oper["NORM_ZAK"]/$rcount;
		$calculator = "";
		$from_id = $prefix."num_".$item["ID"];
		$to_id = $prefix."norm_".$item["ID"];
		$churl = "db_edit.php?db=db_zadan&field=NORM&id=".$prefix."".$item["ID"]."&value=";
		if ($editingplan) $calculator="<a href='javascript:void(0);' onClick=\"SetNewValue('".$from_id."','".$to_id."',".$nxx.",'".$churl."')\" title='����������� �/�'><img src='project/img/calc.png' alt='����������� �/�'></a>";
		Field($item,"db_zadan","NUM",$editingplan," id='".$prefix."".$from_id."' ",$calculator," style='max-width: 60px;' ");
		Field($item,"db_zadan","NORM",$editingplan," id='".$prefix."".$to_id."' ",""," style='max-width: 50px;' ");

	   // �� �����
		$ost = 0;
		if ($oper["NORM_ZAK"]>0) $ost = $rcount*(($oper["NORM_ZAK"]-$oper["NORM_FACT"])/$oper["NORM_ZAK"]);
		$ost = number_format( $ost, 0, '.', ' ');

		$res3 = dbquery("SELECT SUM(NUM_FACT) as NUM_FACT, SUM(NORM) as NORM, SUM(FACT) as FACT FROM ".$db_prefix."db_zadan where ((ID_operitems='".$oper['ID']."'))");

	 
		$res3_1 = mysql_fetch_assoc($res3);
		
		$count_sum = $res3_1['NUM_FACT'];
		$plan_sum = $res3_1['NORM'];
		$fact_sum = $res3_1['FACT'];
	

		$ost = $rcount - $count_sum;

		
		echo "<td class='Field'><center><b>". round((($oper["NORM"])/(60)) * $ost, 2) ." (".$ost.")</b><br>".$oper["NORM_ZAK"]." (".$rcount.")<br>".round(($oper["NORM"])/(60),2)."</center></td>";
		
	   // ����
		$nxx = 0;
		if ($rcount>0) $nxx = $oper["NORM_ZAK"]/$rcount;
		$calculator = "";
		$from_id = "fnum_".$item["ID"];
		$to_id = "fnorm_".$item["ID"];
		$churl = "db_edit.php?db=db_zadan&field=NORM_FACT&id=".$prefix."".$item["ID"]."&value=";
		if ($modering) $calculator="<a href='javascript:void(0);' onClick=\"SetNewValue('".$from_id."','".$to_id."',".$nxx.",'".$churl."')\" title='����������� �/�'><img src='project/img/calc.png' alt='����������� �/�'></a>";
		Field($item,"db_zadan","NUM_FACT",$modering,"id='".$prefix."".$from_id."' ",$calculator," style='max-width: 60px;' ");
		Field($item,"db_zadan","NORM_FACT",$modering," id='".$prefix."".$to_id."' ",""," style='max-width: 50px;' ");
		Field($item,"db_zadan","FACT",$modering,"",""," style='max-width: 50px;' ");
		// Field($item,"db_zadan","ID_zadanrcp",$modering,"style='width: 140px;'","","");

// shindax 13.06.2019
		$nec_select = "";

		if( $item["EDIT_STATE"] == 0 )
			$nec_select = GetNoncompleteExecutionCausesSelect( $item["ID"] );

		echo "<td class='Field nec'>
					$nec_select					
				</td>";

	   // ����
		if (($editing) && (db_adcheck("db_zadan"))) {
			echo "<td class='Field'><select onchange=\"vote(this , 'db_edit.php?db=db_zadan&field=CEH1&id=".$prefix."".$item['ID']."&value='+this.options[this.options.selectedIndex].value);\">";
			echo "<option value=0"; if ($item['CEH1']=="0") { echo " selected";} echo ">---";
			echo "<option value=1"; if ($item['CEH1']=="1") { echo " selected";} echo ">�-1";
			echo "<option value=2"; if ($item['CEH1']=="2") { echo " selected";} echo ">�-2";
			echo "<option value=3"; if ($item['CEH1']=="3") { echo " selected";} echo ">�-3";
			echo "<option value=4"; if ($item['CEH1']=="4") { echo " selected";} echo ">�-4";
			echo "<option value=5"; if ($item['CEH1']=="5") { echo " selected";} echo ">�-5";
			echo "<option value=6"; if ($item['CEH1']=="6") { echo " selected";} echo ">�-6";
			echo "<option value=7"; if ($item['CEH1']=="7") { echo " selected";} echo ">�-1";
			echo "<option value=8"; if ($item['CEH1']=="8") { echo " selected";} echo ">�-2";
			echo "<option value=9"; if ($item['CEH1']=="9") { echo " selected";} echo ">�-3";
			echo "<option value=10"; if ($item['CEH1']=="10") { echo " selected";} echo ">�-4";
			echo "<option value=11"; if ($item['CEH1']=="11") { echo " selected";} echo ">�-1";
			echo "<option value=12"; if ($item['CEH1']=="12") { echo " selected";} echo ">�-2";
			echo "<option value=13"; if ($item['CEH1']=="13") { echo " selected";} echo ">�-3";
			echo "<option value=14"; if ($item['CEH1']=="14") { echo " selected";} echo ">�-4";
			echo "<option value=15"; if ($item['CEH1']=="15") { echo " selected";} echo ">�-1";
			echo "<option value=16"; if ($item['CEH1']=="16") { echo " selected";} echo ">�-2";
			echo "<option value=17"; if ($item['CEH1']=="17") { echo " selected";} echo ">�-3";
			echo "<option value=18"; if ($item['CEH1']=="18") { echo " selected";} echo ">�-4";
			echo "</select></td>";
		}else{
			echo "<td class='Field'></td>";
		}
		if (($editing) && (db_adcheck("db_zadan"))) {
			echo "<td class='Field'><select onchange=\"vote(this , 'db_edit.php?db=db_zadan&field=CEH2&id=".$prefix."".$item['ID']."&value='+this.options[this.options.selectedIndex].value);\">";
			echo "<option value=0"; if ($item['CEH2']=="0") { echo " selected";} echo ">---";
			echo "<option value=1"; if ($item['CEH2']=="1") { echo " selected";} echo ">�-1";
			echo "<option value=2"; if ($item['CEH2']=="2") { echo " selected";} echo ">�-2";
			echo "<option value=3"; if ($item['CEH2']=="3") { echo " selected";} echo ">�-3";
			echo "<option value=4"; if ($item['CEH2']=="4") { echo " selected";} echo ">�-4";
			echo "<option value=5"; if ($item['CEH2']=="5") { echo " selected";} echo ">�-5";
			echo "<option value=6"; if ($item['CEH2']=="6") { echo " selected";} echo ">�-6";
			echo "<option value=7"; if ($item['CEH2']=="7") { echo " selected";} echo ">�-1";
			echo "<option value=8"; if ($item['CEH2']=="8") { echo " selected";} echo ">�-2";
			echo "<option value=9"; if ($item['CEH2']=="9") { echo " selected";} echo ">�-3";
			echo "<option value=10"; if ($item['CEH2']=="10") { echo " selected";} echo ">�-4";
			echo "<option value=11"; if ($item['CEH2']=="11") { echo " selected";} echo ">�-1";
			echo "<option value=12"; if ($item['CEH2']=="12") { echo " selected";} echo ">�-2";
			echo "<option value=13"; if ($item['CEH2']=="13") { echo " selected";} echo ">�-3";
			echo "<option value=14"; if ($item['CEH2']=="14") { echo " selected";} echo ">�-4";
			echo "<option value=15"; if ($item['CEH2']=="15") { echo " selected";} echo ">�-1";
			echo "<option value=16"; if ($item['CEH2']=="16") { echo " selected";} echo ">�-2";
			echo "<option value=17"; if ($item['CEH2']=="17") { echo " selected";} echo ">�-3";
			echo "<option value=18"; if ($item['CEH2']=="18") { echo " selected";} echo ">�-4";
			echo "</select></td>";
		}else{
			echo "<td class='Field'></td>";
		}

	   // ��������
		$showdel = "<img onclick='if (confirm(\"�������, ��� ������ ������� ������� ID: ".$item["ID"]."?\")) vote5(this,".$item["ID"].",".$item["ID_operitems"].", ".$item['ID_resurs'].");' style='cursor: hand;' alt='�������' src='uses/del.png' title='�������'> ";
		echo "<td class='Field'><input type='checkbox' name='cur_zad_sel' name2='parent_res_".$item['ID_resurs']."' name3='".$item["EDIT_STATE"]."' name4='".$item["ID_operitems"]."' id='".$prefix."item_zad_".$item["ID"]."'></td>
		<td class='Field'>";
		if ($item["EDIT_STATE"]=="0") {
			echo "<table><tr><td style='text-align: left; padding-right: 5px;'>";
		if (($editing) && (db_adcheck("db_zadan"))) echo $showdel;
			echo "</td><td style='text-align: right; padding-left: 5px;'>";

		if (($modering) && (db_adcheck("db_zadan"))) 

			// echo " <a style='cursor:pointer;' onclick='reload_page(); vote6(this,".$item["ID"].",".$item["ID_operitems"].", ".$item['ID_resurs'].");'><img alt='������' src='uses/ok.png' title='������'></a>";

			echo "
			<a class='_hidden ready' data-id='{$item["ID"]}' style='cursor:pointer;' onclick='reload_page(); vote6(this,".$item["ID"].",".$item["ID_operitems"].", ".$item['ID_resurs'].");'><img alt='������' src='uses/ok.png'></a>
			<img alt='������' src='uses/ok_dis.png' class='hidden dis_img'>";
			
			echo "</td></tr></table>";
		} else {
			if ((db_adcheck("db_zadan")) && ($oper["STATE"]=="0")) echo " <a style='cursor:pointer;' onclick='reload_page(); vote7(this,".$item["ID"].",".$item["ID_operitems"].", ".$item['ID_resurs'].");'><img alt='�����������' src='uses/restore.png' title='�����������'></a>";
		}
		echo "</td>";

		echo "</tr>\n";
		//echo "<tr id='row3_".$item["ID"]."' onmouseover=\"HLID(".$item["ID"].");\" onmouseout=\"DHLID(".$item["ID"].");\">
		echo "<tr id='".$prefix."row3_".$item["ID"]."'>
		<td style='width:125px;' class='Field'><span style='margin-right: 10px;'>���������:</span>";
		Field($item,"db_zadan","MORE",$editing,"","<span style='margin-right: 10px;'>����������:</span>","colspan='10'");
		

// shindax 26.12.2017		
		if( $active )
		{
		
    echo "
   
   <td class='Field AC'><button class='copy_button' type='button' data-id='".$item["ID"]."'><img src='uses/file_copy.png'>���������� ��</button></td>
    
    <td class='Field' colspan='4'>
		<div style='float:right;display:none;position:absolute;margin-left:-126px;margin-top:25px;z-index:99999' class='change_smen_date_block'>
		<select name='change_resource_select_smen' style='position:absolute;margin-left:-101px;text-align:center;width:100px' size='5'>
			<option value='0' style='color:red'>�����:</option>
			<option value='1'" . ($_GET['p1'] == 1 ? ' selected="selected"' : '') . ">1</option>
			<option value='2'" . ($_GET['p1'] == 2 ? ' selected="selected"' : '') . ">2</option>
			<option value='3'" . ($_GET['p1'] == 3 ? ' selected="selected"' : '') . ">3</option>
		</select>
		<select name='change_resource_select_date' style='text-align:center;width:120px' size='5'></select>
		<select name='change_resource_select_date_resource' style='display:none;text-align:center;width:216px;margin-top:-78px;margin-right:-272px;' size='5'></select>
		
		</div>

		
		<button class='change_smen_date_link' style='width:100%;'>
		<img src='/uses/view.gif'/>
		�������� ������ 
		</button>
		
		
    </td>";
    }
    else
        echo "<td class='Field' colspan='4'></td>";
        
        echo "</tr>\n";
}

   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   // ����� /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//		echo "</form>\n";

		$usedres[] = "0";
		$xxx = dbquery("SELECT ID_resurs FROM ".$db_prefix."db_zadanres where (DATE='".$pdate."') and (SMEN='".$smena."') order by ORD");
		while($res = mysql_fetch_assoc($xxx)) 
			$usedres[] = $res["ID_resurs"];


// shindax
   echo "<div id='caption'>";
		echo "<table style='padding: 0px;' cellpadding='0' cellspacing='0'><tr><td id='links_btn' style='text-align: left;'>\n";
			if (($editing) && (db_adcheck("db_zadanres"))) 
			{
				echo "<div name='add_pers' class='links'>";
				echo "<span class='popup' onClick='chClass(this,\"hpopup\",\"popup\");'>�������� ������";

//					echo "<div class='popup' onClick='window.event.cancelBubble = true;'>";
					echo "<div class='popup' name='tasks_popup_div_$pass'>";					
					$pass ++ ;
					echo "<form  method='post' action='".$pageurl."' style='padding: 0px; margin: 0px;'>";
					echo "<SELECT name='addnewres[]' style='height: 300px;' MULTIPLE>";
						$xxx2 = dbquery("SELECT ID_resurs FROM ".$db_prefix."db_shtat where ((ID_resurs != '0') and ((ID_otdel = '18') or (ID_otdel = '19') or (ID_otdel = '21') or (ID_otdel = '22'))) ");
						$fruits_1 = array();
						while($res2 = mysql_fetch_assoc($xxx2)){
							$xxx = dbquery("SELECT ID,NAME FROM ".$db_prefix."db_resurs where (ID = '".$res2['ID_resurs']."' ) ");
							$res = mysql_fetch_assoc($xxx);
							$fruits_1[$res["ID"]] = $res["NAME"];
						}
						$xxx3 = dbquery("SELECT ID_resurs FROM ".$db_prefix."db_shtat where ((ID_resurs != '0') and (ID_otdel != '18') and (ID_otdel != '19') and (ID_otdel != '21') and (ID_otdel != '22') AND `presense_in_shift_orders`=1 ) ");
						$fruits_2 = array();
						while($res3 = mysql_fetch_assoc($xxx3)){
							$xxx = dbquery("SELECT ID,NAME FROM ".$db_prefix."db_resurs where (ID = '".$res3['ID_resurs']."') ");
							$res = mysql_fetch_assoc($xxx);
							$fruits_2[$res["ID"]] = $res["NAME"];
						}
asort($fruits_1);
asort($fruits_2);
    echo "<option style='color:red; width:150px;' value='0'>--- (������������)";

    foreach ($fruits_1 as $keey_1 => $vaal_1) 
      echo "<option style='width:150px;' value='".$keey_1."'>".$vaal_1;

    echo "<option style='color:red; width:150px;' value='0'>--- (��������� ��������)";
    
    foreach ($fruits_2 as $keey_1 => $vaal_1) 
      echo "<option style='width:150px;' value='".$keey_1."'>".$vaal_1;
	
					echo "</SELECT>";
					echo "<br><br><input type='submit' value='��������'>";
					echo "</form>";
					echo "</div>";
					
				echo "</span>\n";
				echo " | <a class='acl' href='$pageurl&addbytabel'>�� ������</a>";
				echo "</div>";
	}
			
		echo "</td><td style='text-align: right;'>";
			echo "<div class='links'>";
			$smen_1_def = "";
			$smen_2_def = "";
			$smen_3_def = "";
			if ($smena == 1){ $smen_1_def = "selected";}
			if ($smena == 2){ $smen_2_def = "selected";}
			if ($smena == 3){ $smen_3_def = "selected";}
			echo "<select id='cur_sz_smen_158' value='".$smena."' onchange='location.href=\"index.php?do=show&formid=158&p0=".$pdate."&p1=\"+this.value;'><option value='1' ".$smen_1_def.">1 �����</option><option value='2' ".$smen_2_def.">2 �����</option><option value='3' ".$smen_3_def.">3 �����</option></select><input type='date' id='smen_dt_sz' class='acl' min='1970-01-01' max='2099-01-01' value='".substr($pdate,0,4)."-".substr($pdate,4,2)."-".substr($pdate,6,2)."' onchange='location.href=\"index.php?do=show&formid=158&p0=\"+this.value.substr(0,4)+this.value.substr(5,2)+this.value.substr(8,2)+\"&p1=\"+document.getElementById(\"cur_sz_smen_158\").value+\";\";'>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;";
			echo "<a id='prnt_dt_sz2' class='acl' href='index.php?do=show&formid=210&p0=".$_GET['p0']."&p1=".$_GET['p1']."' target='_blank'>������ �������</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
			echo "<a id='prnt_dt_sz3' class='acl' href='index.php?do=show&formid=211&p0=".$_GET['p0']."&p1=".$_GET['p1']."' target='_blank'>������ ��� ������ (�����)</a>"; // &nbsp;&nbsp;|&nbsp;&nbsp;";
//			echo "<a id='prnt_dt_sz' class='acl' href='".$print_url."' target='_blank'>������ ��� ������</a>";
			echo "</div>";
      echo "</td></tr></table></div>";

// *************************************************************************************************************      

	   // ����� ������� ///////////////////////////////////////////////////////////////
		$RsursIDs = Array();
		$xxx = dbquery("SELECT ID_resurs FROM ".$db_prefix."db_zadanres where (DATE='".$pdate."') and (SMEN='".$smena."') order by ORD");
		while($res = mysql_fetch_assoc($xxx)) 
			$RsursIDs[] = $res["ID_resurs"];
		
    echo "<div id='table_caption_div' style='z-index:102 !IMPORTANT;'>";		
		echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 1650px;' border='1' cellpadding='0' cellspacing='0'>\n";

		echo "<thead>";
		echo "<tr class='first'>\n";
		echo "<td colspan=2 width='80'>".substr($pdate,6,2).".".substr($pdate,4,2).".".substr($pdate,0,4)."<br>��. ".$smena."</td>\n";
		echo "<td name='links_btn2'></td>\n";
		echo "<td colspan='2'>��������</td>\n";
		echo "<td rowspan='2' width='160'>������������</td>\n";
		echo "<td rowspan='2' width='55'>������� � ������/������� ����.</td>\n";
		echo "<td colspan='2'>����</td>\n";
		echo "<td rowspan='2' width='80'>�� �����<br><b>��������</b> / �����,<br>�/� (��) / <br>����� �� ��.</td>\n";
		echo "<td colspan='4'>����</td>\n";
		echo "<td rowspan='2' width='50'>������<br>�����</td>\n";
		echo "<td rowspan='2' width='50'>����<br>��������</td>\n";
		echo "<td colspan='2' rowspan='2' width='50'>multiselect</td>\n";
		echo "</tr>\n";


		echo "<tr class='first'>\n";
		echo "<td width='40'>�</td>\n";
		echo "<td width='40'>ID</td>\n";
		echo "<td id='h0' width='300'>����� / ���<br>
		<select id='focus_to_resource'><option value=0 selected>�������� ������</option>";
		$xxx_5 = dbquery("SELECT ID, NAME FROM ".$db_prefix."db_resurs order by binary (NAME)");
		while($resurs_5 = mysql_fetch_assoc($xxx_5)) {
		   if (in_array($resurs_5["ID"],$RsursIDs)) {
			   echo "<option value='".$resurs_5['ID']."'>".$resurs_5['NAME']."</option>";
		   }
		}
		echo "</select></td>\n";
		
		echo "<td width='20'>�</td>\n";
		echo "<td>������������</td>\n";
		echo "<td width='60'>���-��</td>\n";
		echo "<td width='50'>�/�</td>\n";
		echo "<td width='60'>���-��</td>\n";
		echo "<td width='50'>�/�</td>\n";
		echo "<td width='50'>����.<br>�����, �</td>\n";
		echo "<td width='150'>������� ������������</td>\n";
		echo "</tr>\n";
		echo "</thead>";
		
		?>
		
		
	<script>
	$(document).on("change", "#focus_to_resource", function () {

		$("#id_res_zadanres_" + $(this).val()).get(0).parentElement.scrollIntoView(true);
		
		document.getElementById("vpdiv").scrollTop += -($(".rdtbl thead").outerHeight() + $("#caption").outerHeight() + 7);


	});
	
	function getParameterByName(name) {
    var match = RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
    return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
}
	
	$(function () {
		if (window.location.href.indexOf("event") == -1) {
			document.getElementById('vpdiv').scrollTop = get_top_scrl_numb;
		}
		
		var current_resource_id = getParameterByName("current_resource_id");
//alert(current_resource_id);

		var get_id_resource = $("#id_res_zadanres_" + current_resource_id).get(0);
		
		if (get_id_resource != undefined && get_id_resource != null) {
			$("#id_res_zadanres_" + current_resource_id).get(0).parentElement.scrollIntoView(true);
		}
if (current_resource_id > 0) {	
		document.getElementById("vpdiv").scrollTop += -($(".rdtbl thead").outerHeight() + $("#caption").outerHeight() + 7);

}
	})
	</script>
		<?php

// shindax

		echo "<tbody>";
	
	   // ���� ������� ///////////////////////////////////////////////////////////////
		$xxx = dbquery("SELECT ID FROM ".$db_prefix."db_resurs order by binary (NAME)");
		while($resurs = mysql_fetch_assoc($xxx)) 
		  if (in_array($resurs["ID"],$RsursIDs)) 
		   {
          $xxxres = dbquery("SELECT * FROM ".$db_prefix."db_zadanres where (DATE='".$pdate."') and (SMEN='".$smena."') and (ID_resurs='".$resurs["ID"]."')");
          $res = mysql_fetch_array($xxxres);
          OpenID( $res , 0 );
		   }

		echo "</tbody>";

	
		echo "</table>";		
		echo "</div>";		
		
// *****************************************************************************************************
      
		echo "<form id='form1x' method='post' action='".$pageurl."'>";

    echo "<div id='main_table_div'>";
		echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 1650px;' border='1' cellpadding='0' cellspacing='0'>\n";

		echo "<thead>";
		echo "<tr class='first'>\n";
		echo "<td colspan=2 width='80'>".substr($pdate,6,2).".".substr($pdate,4,2).".".substr($pdate,0,4)."<br>��. ".$smena."</td>\n";
		echo "<td name='links_btn2'></td>\n";
		echo "<td colspan='2'>��������</td>\n";
		echo "<td rowspan='2' width='160'>������������</td>\n";
		echo "<td rowspan='2' width='55'>������� � ������/������� ����.</td>\n";
		echo "<td colspan='2'>����</td>\n";
		echo "<td rowspan='2' width='80'>�� �����<br><b>��������</b> / �����,<br>�/� (��) / <br>����� �� ��.</td>\n";
		echo "<td colspan='4'>����</td>\n";
		echo "<td rowspan='2' width='50'>������<br>�����</td>\n";
		echo "<td rowspan='2' width='50'>����<br>��������</td>\n";
		echo "<td colspan='2' rowspan='2' width='50'>multiselect</td>\n";
		echo "</tr>\n";


		echo "<tr class='first'>\n";
		echo "<td width='40'>�</td>\n";
		echo "<td width='40'>ID</td>\n";
		echo "<td id='h0' width='300'>����� / ���</td>";

/*		
		<select onchange='document.getElementById(\"vpdiv\").scrollTop=(document.getElementById(\"id_res_zadanres_\"+this.value).offsetTop+25);'><option value=0 selected>�������� ������</option>";
		$xxx_5 = dbquery("SELECT ID, NAME FROM ".$db_prefix."db_resurs order by binary (NAME)");
		while($resurs_5 = mysql_fetch_array($xxx_5)) {
		   if (in_array($resurs_5["ID"],$RsursIDs)) {
			   echo "<option value='".$resurs_5['ID']."'>".$resurs_5['NAME']."</option>";
		   }
		}
		echo "</select></td>\n";
*/
		echo "<td width='20'>�</td>\n";
		echo "<td>������������</td>\n";
		echo "<td width='60'>���-��</td>\n";
		echo "<td width='50'>�/�</td>\n";
		echo "<td width='60'>���-��</td>\n";
		echo "<td width='50'>�/�</td>\n";
		echo "<td width='50'>����.<br>�����, �</td>\n";
		echo "<td width='150'>������� ������������</td>\n";
		echo "</tr>\n";
		echo "</thead>";


		echo "<tbody>";
				
	   // ���� ������� ///////////////////////////////////////////////////////////////
		$xxx = dbquery("SELECT ID FROM ".$db_prefix."db_resurs order by binary (NAME)");
		while($resurs = mysql_fetch_assoc($xxx)) {
		   if (in_array($resurs["ID"],$RsursIDs)) {
			$xxxres = dbquery("SELECT * FROM ".$db_prefix."db_zadanres where (DATE='".$pdate."') and (SMEN='".$smena."') and (ID_resurs='".$resurs["ID"]."')");
			$res = mysql_fetch_array($xxxres);
			OpenID($res);
		   }
		}
		echo "</tbody>";

		echo "</table></div>\n";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}

/////////////////////////     ��������� ���������� �� �������� �����

echo "<script language='javascript'>
var per_clik_page;
function add_numsumm(id_res, id_oper, n_arr, val){
	var req = getXmlHttp();
	req.open('GET', 'project/zadan/zadanres_numsumm.php?p1='+id_res+'&p2='+id_oper+'&p3='+n_arr+'&p4='+val);
	req.send(null);
}
function sel_all_zad(id_res, obj){
	if (obj.checked==true){
		var all_checked = document.getElementsByName('cur_zad_sel').length;
		for (var a_a=0; a_a < all_checked; a_a++){
			if (document.getElementsByName('cur_zad_sel')[a_a].getAttribute(\"name2\")=='parent_res_'+id_res){
				document.getElementsByName('cur_zad_sel')[a_a].checked=true;
			}
		}
	}
	if (obj.checked==false){
		var all_checked = document.getElementsByName('cur_zad_sel').length;
		for (var a_a=0; a_a < all_checked; a_a++){
			if (document.getElementsByName('cur_zad_sel')[a_a].getAttribute(\"name2\")=='parent_res_'+id_res){
				document.getElementsByName('cur_zad_sel')[a_a].checked=false;
			}
		}
	}
}
function change_park_sz(id_sz_zadan, mark_park){
		var req = getXmlHttp();
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if(req.status == 200) {
				}
			}
		}

		req.open('GET', 'project/change_park_sz.php?p1='+id_sz_zadan+'&p2='+mark_park, true);
		req.send(null);
}

function reload_page()
{
	clearTimeout(per_clik_page);
	per_clik_page = setTimeout('reload_page2()',1000);
}

function reload_page2()
{
	location.href='".$pageurl."';
}

function vote5(obj, zadan_id, operit_id, id_res)
{
	var l_zad_res = document.getElementsByName('cur_zad_sel').length;
	var arr_zadan_id = '';
	var arr_operit_id = '';
	var ind_count = 0;
	for (var a_b=0; a_b < l_zad_res; a_b++){
		if (document.getElementsByName('cur_zad_sel')[a_b].getAttribute('name3')=='0')
		{			
			if (document.getElementsByName('cur_zad_sel')[a_b].checked==true)
			{
				arr_zadan_id = arr_zadan_id+document.getElementsByName('cur_zad_sel')[a_b].getAttribute('id').substr(9)+'|';
				arr_operit_id = arr_operit_id+document.getElementsByName('cur_zad_sel')[a_b].getAttribute('name4')+'|';
				
				var cur_tr_id = document.getElementsByName('cur_zad_sel')[a_b].parentNode.parentNode.getAttribute('id').substr(5);
				
				if (document.getElementById('row3_'+cur_tr_id) != null) {
					document.getElementById('row3_'+cur_tr_id).setAttribute('style','display:none;');
				}
								
				if (document.getElementById('row1_'+cur_tr_id) != null) {
					document.getElementById('row1_'+cur_tr_id).setAttribute('style','display:none;');
				}
				
				ind_count = ind_count + 1;
			}
		}
	}
	
	if (ind_count==0) 
	{
		arr_zadan_id = zadan_id;
		arr_operit_id = operit_id;
		
		document.getElementById('row3_'+zadan_id).setAttribute('style','display:none;');
		document.getElementById('row1_'+zadan_id).setAttribute('style','display:none;');
	}
	
	var req = getXmlHttp();
	req.open('GET', 'zadanres_delzad.php?id='+arr_zadan_id+'&operitems='+arr_operit_id);
	req.send(null);
}";

?>


$(function () {
	$(document).on("click", "input[type=checkbox][name=is_multimachine]", function () {
		var tr = $(this).closest("tr");
		var date = tr.data("date");
		var resource_id = tr.data("resource-id");
		
		if ($(this).is(":checked")) {
			$.get("/project/zadan/ajax.set_miltimachine.php?value=1&date=" + date + "&resource_id=" + resource_id);
		} else {
			$.get("/project/zadan/ajax.set_miltimachine.php?value=0&date=" + date + "&resource_id=" + resource_id);
		}
	});
	
	$(document).on("change", "input[type=text][name=multimachine_fact]", function () {
		var tr = $(this).closest("tr");
		var value = $(this).val();
		var date = tr.data("date");
		var resource_id = tr.data("resource-id");
		
		if (value != 0) {
			$.get("/project/zadan/ajax.set_miltimachine.php?value=1&date=" + date + "&resource_id=" + resource_id + "&multimachine_fact=" + value);
		} else {
			$.get("/project/zadan/ajax.set_miltimachine.php?value=0&date=" + date + "&resource_id=" + resource_id + "&multimachine_fact=" + value);
		}
	});
})

<?php

echo "
function vote6(obj, zadan_id, operit_id, id_res)
{
	obj.setAttribute('style','display:none;')

	var l_zad_res = document.getElementsByName('cur_zad_sel').length;
	var arr_zadan_id = '';
	var arr_operit_id = '';
	var ind_count = 0;
	for (var a_b=0; a_b < l_zad_res; a_b++){
		if (document.getElementsByName('cur_zad_sel')[a_b].getAttribute('name3')=='0'){			
			if (document.getElementsByName('cur_zad_sel')[a_b].checked==true){
				arr_zadan_id = arr_zadan_id+document.getElementsByName('cur_zad_sel')[a_b].getAttribute('id').substr(9)+'|';
				arr_operit_id = arr_operit_id+document.getElementsByName('cur_zad_sel')[a_b].getAttribute('name4')+'|';
				
				ind_count = ind_count + 1;
			}
		}
	}
	
	if (ind_count==0) 
	{
		arr_zadan_id = zadan_id;
		arr_operit_id = operit_id;
	}


	let zadan_arr = String( arr_zadan_id ).split('|')
	let cause_arr = []

	  $.each( zadan_arr , function( key, item )
	    {
	      let id = parseInt( item )
	      if( !isNaN( id ) )
	      {
	      	let cause = $('select.noncomplete_execution_causes_select[data-id=' + id + ']').eq(1).find('option:selected').val()
	      	cause_arr.push( cause )
	      }
	    });

	let cause_str = cause_arr.join('|')
		
	var req = getXmlHttp();
	req.open('GET', 'zadanres_okzad.php?id='+arr_zadan_id+'&operitems='+arr_operit_id+'&causes='+cause_str+'&user_id='+user_id);

	req.send(null);
}

function vote7(obj, zadan_id, operit_id, id_res)
{
	obj.setAttribute('style','display:none;')
	
	var l_zad_res = document.getElementsByName('cur_zad_sel').length;
	var arr_zadan_id = '';
	var arr_operit_id = '';
	var ind_count = 0;
	for (var a_b=0; a_b < l_zad_res; a_b++)
	{
		if (document.getElementsByName('cur_zad_sel')[a_b].getAttribute('name3')=='1')
		{			
			if (document.getElementsByName('cur_zad_sel')[a_b].checked==true)
			{
				arr_zadan_id = arr_zadan_id+document.getElementsByName('cur_zad_sel')[a_b].getAttribute('id').substr(9)+'|';
				arr_operit_id = arr_operit_id+document.getElementsByName('cur_zad_sel')[a_b].getAttribute('name4')+'|';
				
				ind_count = ind_count + 1;
			}
		}
	}
	
	if (ind_count==0) 
	{
		arr_zadan_id = zadan_id;
		arr_operit_id = operit_id;
	}
		
	var req = getXmlHttp();
	req.open('GET', 'zadanres_restzad.php?id='+arr_zadan_id+'&operitems='+arr_operit_id);
	req.send(null);
}

if (document.getElementsByClassName('highlite'))
{
	var trall = document.getElementsByClassName('highlite').length;
	for (var trind = 0; trind < (trall/2); trind++)
	{
		if (document.getElementsByClassName('highlite')[(trind*2)].getElementsByTagName('td')[0])
		{
			var tdval = 1, tdsum;
			tdsum=tdval+trind;
			document.getElementsByClassName('highlite')[(trind*2)].getElementsByTagName('td')[0].innerText = tdsum ;
		}
	}
}

window.onload = function()
{
	document.getElementsByName('links_btn2')[1].innerHTML = document.getElementById('links_btn').innerHTML;
	document.getElementsByName('add_pers')[1].style.margin='0px 0px 0px 0px';
	document.getElementsByName('add_pers')[1].getElementsByTagName('span')[0].setAttribute('style', 'font-size:9pt;');
	document.getElementsByName('add_pers')[1].getElementsByTagName('a')[0].setAttribute('style', 'font-size:9pt;');
	document.getElementsByName('links_btn2')[1].innerHTML = document.getElementsByName('links_btn2')[1].innerHTML + '<a href=\"'+document.getElementById(`smen_dt_sz`).href+'\">'+document.getElementById(`smen_dt_sz`).innerText+'</a> | <a target=\"_blank\" href=\"'+document.getElementById(`prnt_dt_sz2`).href+'\">�������</a> | <a target=\"_blank\" href=\"'+document.getElementById(`prnt_dt_sz3`).href+'\">������ (new)</a> | <a target=\"_blank\" href=\"'+document.getElementById(`prnt_dt_sz`).href+'\">������ ��� ������</a>';
	document.getElementById('vpdiv').onscroll = function()
	{
		document.cookie = \"scroll=\"+this.scrollTop+\"x\"+this.scrollLeft+\"; expires=Fri, 31 Dec ".NextYear()." 23:59:59 GMT;\";
		document.getElementsByName('links_btn2')[1].parentNode.parentNode.style.top=\"0px\";
	}
}

$(function () { 
	$(document).on('click', '.dse a img.print', function (e) {
		e.preventDefault();
	 
		var l_zad_res = $('*[name=cur_zad_sel]');
	
		var arr_zadan_id = '';

		l_zad_res.each(function () {
			if ($(this).is(':checked')) {
				arr_zadan_id = arr_zadan_id + $(this).closest('tr').data('dse-id') + ',';

			}
		})

		if (arr_zadan_id == '') {
			arr_zadan_id = $(this).closest('tr').data('dse-id');
		}

		//console.log(arr_zadan_id);

		window.open('/index.php?do=show&formid=226&ids=' + arr_zadan_id, '_blank');
	});
});


// *****************************************************************************************************
// shindax 26.12.2017

var monthNames = ['\u042F\u043D\u0432\u0430\u0440\u044C','\u0424\u0435\u0432\u0440\u0430\u043B\u044C','\u041C\u0430\u0440\u0442','\u0410\u043F\u0440\u0435\u043B\u044C','\u041C\u0430\u0439','\u0418\u044E\u043D\u044C',
        '\u0418\u044E\u043B\u044C','\u0410\u0432\u0433\u0443\u0441\u0442','\u0421\u0435\u043D\u0442\u044F\u0431\u0440\u044C','\u041E\u043A\u0442\u044F\u0431\u0440\u044C','\u041D\u043E\u044F\u0431\u0440\u044C','\u0414\u0435\u043A\u0430\u0431\u0440\u044C'];
var monthNamesShort = ['\u042F\u043D\u0432','\u0424\u0435\u0432','\u041C\u0430\u0440','\u0410\u043F\u0440','\u041C\u0430\u0439','\u0418\u044E\u043D',
        '\u0418\u044E\u043B','\u0410\u0432\u0433','\u0421\u0435\u043D','\u041E\u043A\u0442','\u041D\u043E\u044F','\u0414\u0435\u043A'];
var dayNames = ['\u0432\u043E\u0441\u043A\u0440\u0435\u0441\u0435\u043D\u044C\u0435','\u043F\u043E\u043D\u0435\u0434\u0435\u043B\u044C\u043D\u0438\u043A','\u0432\u0442\u043E\u0440\u043D\u0438\u043A','\u0441\u0440\u0435\u0434\u0430','\u0447\u0435\u0442\u0432\u0435\u0440\u0433','\u043F\u044F\u0442\u043D\u0438\u0446\u0430','\u0441\u0443\u0431\u0431\u043E\u0442\u0430'];
var dayNamesShort = ['\u0432\u0441\u043A','\u043F\u043D\u0434','\u0432\u0442\u0440','\u0441\u0440\u0434','\u0447\u0442\u0432','\u043F\u0442\u043D','\u0441\u0431\u0442'];
var dayNamesMin = ['\u0412\u0441','\u041F\u043D','\u0412\u0442','\u0421\u0440','\u0427\u0442','\u041F\u0442','\u0421\u0431'];


$('.copy_button').bind('click', copy_button_click );
$('#copy_dialog_shift').bind('change', copy_dialog_shift_change );

function copy_dialog_shift_change()
{
	var shift = $( '#copy_dialog_shift option:selected' ).text();
	var date = $( '#copy_dialog_date' ).val() ;

	if( shift && date.length )
              	$.post(
                  'project/zadan/ajax.GetResurs.php',
                  {
                      date : date ,
                      shift : shift
                  },
                  function( data )
                  {
				$( '#copy_dialog_resurs' ).empty().html( data );
                    	$('#new_notify_dialog').dialog('close');
                  }
              );

	get_copy_dialog_values();
}

function copy_button_click()
{
    var id = $( this ).data('id');
    var zadan_id = $( this ).closest('tr[id^=row3_]').attr('id').replace('row3_','');
    $('#copy_dialog').data('id', zadan_id );


    $( '#copy_dialog' ).dialog({
    	  resizable: false,
        modal: true,
        closeOnEscape: true,
        height: 120,
        width: 360,
        position: { my: 'right top', at: 'left top', of: this },
        create : function()
        	{
        		$('div.ui-widget-header').css('background','#AFEEEE'); // ���� ��������� �������
        		$('#copy_dialog_copy_button').addClass('ui-state-disabled');
        	},
        buttons:
        [
            {
             id : 'copy_dialog_copy_button',
            text: '\u0421\u043A\u043E\u043F\u0438\u0440\u043E\u0432\u0430\u0442\u044C',
            click : function ()
            {
			var zadan_id = $('#copy_dialog').data('id');
			var zadan_arr = [];
			var checkboxes = $('input:checkbox[id^=item_zad_]:checked');
			var checkboxes2 = $('input:checkbox[id^=sel_all_]:checked');
 			var res = get_copy_dialog_values()

			if( checkboxes.length )
			{
				$.each( checkboxes, function( key, value )
				{
					zadan_arr.push( $( value ).attr( 'id' ).replace('item_zad_','') );
				});
			}
			else
				zadan_arr.push( zadan_id );

              	$.post(
                  'project/zadan/ajax.PutZadanCopy.php',
                  {
                  	  user_id : user_id,                  	
                      date : res.date ,
                      shift : res.shift ,
                      resurs : res.resurs ,
                      zadan_arr : zadan_arr
                  },
                  function( data )
                  {
//                  		console.log( data );
                  		$( checkboxes ).prop('checked',false);
                  		$( checkboxes2 ).prop('checked',false);
                  }
              );


            	   	clear_copy_dialog();
                	$(this).dialog('close');
            }
            },
            {
            text : '\u041E\u0442\u043C\u0435\u043D\u0430',
            click : function ()
            		 {
            		     clear_copy_dialog()
                        $(this).dialog('close');
                    }
            }
        ]
    });

    $( '#copy_dialog_date' ).datepicker(
        {
            closeText: '\u041F\u0440\u0438\u043D\u044F\u0442\u044C', // �������
            prevText: '&#x3c;\u041F\u0440\u0435\u0434', //
            nextText: '\u0421\u043B\u0435\u0434&#x3e;',
            currentText: '\u0422\u0435\u043A. \u043C\u0435\u0441\u044F\u0446',// ���. �����
            
            showButtonPanel: false,
            showOtherMonths: true,
            selectOtherMonths: true,
            showButtonPanel: true,
            
            monthNames: monthNames,
            monthNamesShort : monthNamesShort,
            dayNames : dayNames,
            dayNamesShort : dayNamesShort,
            dayNamesMin : dayNamesMin,
            dateFormat: 'dd.mm.yy',
            firstDay: 1,
            changeMonth : true,
            changeYear : true,
            closeOnEscape: true,
            isRTL: false,

            beforeShow : function(input, inst) {},

            onSelect: function ()
            {
            		var date = $( this ).val() ;
            		var shift = $( '#copy_dialog_shift option:selected' ).text();

		if( shift && date.length )
	                	$.post(
	                    'project/zadan/ajax.GetResurs.php',
	                    {
	                        date : date ,
	                        shift : shift
	                    },
	                    function( data )
	                    {
					$( '#copy_dialog_resurs' ).empty().html( data );
	                      	get_copy_dialog_values();
	                    }
	                );

            }
        });
}

function clear_copy_dialog()
{
	$( '#copy_dialog_shift option[value=0]' ).prop('selected', true ) ;
	$( '#copy_dialog_date' ).val('') ;
	$( '#copy_dialog_resurs' ).empty() ;
}

function get_copy_dialog_values()
{
      	var shift = $( '#copy_dialog_shift option:selected' ).val();
	var date = $( '#copy_dialog_date' ).val() ;
	var resurs = $( '#copy_dialog_resurs  option:selected' ).val();

	if( shift && date && resurs )
		$('#copy_dialog_copy_button').removeClass('ui-state-disabled');
			else
				$('#copy_dialog_copy_button').addClass('ui-state-disabled');

	return { 'shift' : shift, 'date' : date, 'resurs' : resurs }
}

</script>";


function lg( $file, $arg )
{
	$current = file_get_contents($file);
	$current .= "$arg\n";
	file_put_contents($file, $current);
}

?>
