<?php

require_once('/var/www/test.okbmikron/www/db_mysql_pdo.php');
 
function get_val( $ID_krz2 )
  {
    $query = "SELECT * FROM okb_db_krz2det where (PID='0') and (ID_krz2='".$ID_krz2."')";
    $res = 0 ;

    $result = dbquery( $query );

    if ( $arr = mysql_fetch_array($result) ) 
    {
      $count = $arr['COUNT'];

      for( $i = 8 ; $i < 16 ; $i ++ )
        $res += $count * $arr[ $i ];
      
      $res += $arr[16];
    }
  
    return number_format($res, 2);
  }

function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
{
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);
   
    $interval = date_diff($datetime1, $datetime2);
   
    return $interval->format($differenceFormat);
   
}
 
$is_commercial_director = $user['ID'] == 145 || $user['ID'] == 1;
  
$order_id = (int) $_GET['id'];

$krz2_id = $pdo->query("SELECT `ID_krz2` FROM `okb_db_zak` WHERE `ID` = " . $order_id)->fetchColumn();

$std_ktz2_price = $pdo->query("SELECT `price` FROM `okb_db_krz2` WHERE `ZAKNUM` = " . $order_id)->fetchColumn();

$krz_norm_price = round($pdo->query("SELECT `NORM_PRICE` FROM `okb_db_krz2` WHERE `ZAKNUM` = " . $order_id)->fetchColumn(), 2);

$krz_plan = get_val($krz2_id);

$ovk_price = $pdo->query("SELECT SUM( krz2det.COUNT * krz2detitems.COUNT ) AS result
							FROM okb_db_krz2det AS krz2det
							LEFT JOIN okb_db_krz2detitems AS krz2detitems ON krz2detitems.ID_krz2det = krz2det.ID
							WHERE 
							krz2det.ID_krz2 = " . $krz2_id ."
							AND
							krz2detitems.TID = 2")->fetchColumn();


$omts_price = $pdo->query("SELECT SUM(  krz2det.COUNT * krz2detitems.COUNT * krz2detitems.PRICE ) AS result
								FROM okb_db_krz2det AS krz2det
								LEFT JOIN okb_db_krz2detitems AS krz2detitems ON krz2detitems.ID_krz2det = krz2det.ID
								WHERE 
								krz2det.ID_krz2 = " . $krz2_id . "
								AND
								krz2detitems.TID = 0")->fetchColumn();	

$omts_price2 = $pdo->query("SELECT SUM( k2di.COUNT * k2d.COUNT) 
								FROM okb_db_krz2detitems  k2di
								LEFT JOIN okb_db_krz2det k2d ON k2d.ID = k2di.ID_krz2det
								WHERE 
								k2d.ID_krz2 = $krz2_id 
								AND 
								k2di.TID=6")->fetchColumn();	
			 
$omts_price3 = $pdo->query("SELECT SUM( k2di.COUNT * k2d.COUNT) 
								FROM okb_db_krz2detitems  k2di
								LEFT JOIN okb_db_krz2det k2d ON k2d.ID = k2di.ID_krz2det
								WHERE 
								k2d.ID_krz2 = $krz2_id 
								AND 
								k2di.TID=1")->fetchColumn();	
			 
		
$omts_price += $omts_price2 + $omts_price3;

$parent_orders_plan = $pdo->query("SELECT SUM(`SUMM_N`) FROM okb_db_zak WHERE PID = " . $order_id)->fetchColumn();
$parent_orders_fact = $pdo->query("SELECT SUM(`SUMM_NV`) FROM okb_db_zak WHERE PID = " . $order_id)->fetchColumn();

$ovk_fact = $pdo->query("SELECT `ovk_fact` FROM `okb_db_order_summary_report` WHERE `order_id` = " . $order_id)->fetchColumn();
$omts_fact = $pdo->query("SELECT `omts_fact` FROM `okb_db_order_summary_report` WHERE `order_id` = " . $order_id)->fetchColumn();
$other_fact = $pdo->query("SELECT `other_fact` FROM `okb_db_order_summary_report` WHERE `order_id` = " . $order_id)->fetchColumn();
$hour_cost = $pdo->query("SELECT `value` FROM `okb_db_order_summary_report_hour_cost` WHERE `order_id` = " . $order_id)->fetchColumn();
//$hour_cost = ($hour_cost == 0 ? $hour_cost = 1 : $hour_cost);
	 
$other_plan = $pdo->query("SELECT SUM( k2di.COUNT * k2d.COUNT) 
								FROM okb_db_krz2detitems  k2di
								LEFT JOIN okb_db_krz2det k2d ON k2d.ID = k2di.ID_krz2det
								WHERE 
								k2d.ID_krz2 = $krz2_id 
								AND 
								k2di.TID=4")->fetchColumn();	
			 

$carry_over = explode('#', $pdo->query("SELECT `pd8` FROM `okb_db_zak` WHERE `ID` = " . $order_id)->fetchColumn());
 
$carry_over_first = explode('|', $carry_over[2])[0];
$carry_over_last = explode('|', $carry_over[count($carry_over) - 3])[0];

/*
echo $carry_over_first . ' ' . $carry_over_last;
*/
?>
<style type="text/css">
textarea {
	width:100%; 
}
</style>

<h2>Сводно-аналитический отчет</h2>

<script src="/project/order_summary_report/script.js" type="text/javascript"></script>

<table data-order-id="<?php echo $_GET['id']; ?> " class="tbl" style="border-collapse: collapse; border: 0px solid black; text-align: left; color: rgb(0, 0, 0); width: 1240px; padding: 0px;" border="1" cellpadding="0" cellspacing="0">
	<thead>
		<tr class="first">
			<td width="20%" colspan="3"<?php echo ($krz_plan < ($sn + $parent_orders_plan) ? ' style="color:red;font-weight:bold"' : '') ?>>Служба технического директора</td>
			<td width="20%" colspan="2">Отдел внешней кооперации</td>
			<td width="20%" colspan="2">ОМТС</td>
			<td width="20%" colspan="4"<?php echo ($krz_plan < ($sf + $parent_orders_fact) ? ' style="color:red;font-weight:bold"' : '') ?>>Производство</td>
			<td width="20%" colspan="2">Прочее</td>
		</tr>
		<tr class="first">
			<td width="10%">КРЗ2<br/>(н/ч)</td>
			<td width="10%">МТК<br/>(н/ч)</td>
			<td width="10%">ВЗ<br/>(н/ч)</td>
			<td width="10%">КРЗ2<br/>(руб.)</td>
			<td width="10%">Факт<br/>(руб.)</td>
			<td width="10%">КРЗ2<br/>(руб.)</td>
			<td width="10%">Факт<br/>(руб.)</td>
			<td width="5%">МТК<br/>(н/ч)</td>
			<td width="5%">Факт<br/>(ч/ч)</td>
			<td width="5%">МТК ВЗ<br/>(н/ч)</td>
			<td width="5%">Факт ВЗ<br/>(ч/ч)</td>
			<td width="10%">План<br/>(руб.)</td> 
			<td width="10%">Факт<br/>(руб.)</td> 
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="Field AC"><?php echo number_format($krz_plan, 2); ?></td>
			<td class="Field AC"><?php echo number_format($sn, 2); ?></td>
			<td class="Field AC"><?php echo number_format($parent_orders_plan, 2); ?></td>
			<td class="Field AC"><?php echo number_format($ovk_price, 2); ?></td>
			<td class="Field AC"><input type="number" id="ovk_fact" value="<?php echo $ovk_fact?>"/></td>
			<td class="Field AC"><?php echo number_format($omts_price, 2); ?></td>
			<td class="Field AC"><input type="number" id="omts_fact" value="<?php echo $omts_fact; ?>"/></td>
			<td class="Field AC"><?php echo number_format($sn, 2); ?></td>
			<td class="Field AC"><?php echo number_format($sf, 2); ?></td>
			<td class="Field AC"><?php echo number_format($parent_orders_plan, 2); ?></td>
			<td class="Field AC"><?php echo number_format($parent_orders_fact, 2); ?></td>
			<td class="Field AC"><?php echo number_format($other_plan, 2); ?></td>
			<td class="Field AC"><input type="number" id="other_fact" value="<?php echo $other_fact; ?>"/></td>
		</tr>
		<tr>
			<td class="Field AC" colspan="3"><textarea id="std_comment" rows="5"><?php echo iconv('utf-8', 'windows-1251', $pdo->query("SELECT `std_comment` FROM `okb_db_order_summary_report` WHERE `order_id` = " . $order_id)->fetchColumn());?></textarea></td>
			<td class="Field AC" colspan="2"><textarea id="ovk_comment" rows="5"><?php echo iconv('utf-8', 'windows-1251', $pdo->query("SELECT `ovk_comment` FROM `okb_db_order_summary_report` WHERE `order_id` = " . $order_id)->fetchColumn());?></textarea></td>
			<td class="Field AC" colspan="2"><textarea id="omts_comment" rows="5"><?php echo iconv('utf-8', 'windows-1251', $pdo->query("SELECT `omts_comment` FROM `okb_db_order_summary_report` WHERE `order_id` = " . $order_id)->fetchColumn());?></textarea></td>
			<td class="Field AC" colspan="4"><textarea id="prod_comment" rows="5"><?php echo iconv('utf-8', 'windows-1251', $pdo->query("SELECT `prod_comment` FROM `okb_db_order_summary_report` WHERE `order_id` = " . $order_id)->fetchColumn());?></textarea>
			<br/><a target="_blank" href="/index.php?do=show&formid=310&p0%5B%5D=<?php echo $order_id; ?>">Причины по операциям</a>
			</td>
			<td class="Field AC" colspan="2"><textarea id="other_comment" rows="5"><?php echo iconv('utf-8', 'windows-1251', $pdo->query("SELECT `other_comment` FROM `okb_db_order_summary_report` WHERE `order_id` = " . $order_id)->fetchColumn());?></textarea></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td class="Field" colspan="2">Себестоимость Н/Ч:</td>
	<td class="Field AC" colspan="100" style="text-align:left"><input <?php echo (!$is_commercial_director ? ' disabled="disabled"' : ''); ?> type="number" value="<?php echo $hour_cost; ?>" id="hour_cost"</td>
		</tr>
	</tfoot>
</table>
<br/>
<div style="font-weight:bold;font-size:12pt;font-style:open sans;letter-spacing:1px;">
Просрочено: <a style="font-weight:bold;font-size:12pt;font-style:open sans;letter-spacing:1px;" target="_blank" href="/index.php?do=show&formid=269&p0=<?php echo $order_id; ?>">
				<?php echo dateDifference($carry_over_last, $carry_over_first); ?></a> дней.<br/><br/>

Коммерческая стоимость: <?php echo number_format(($krz_plan * $krz_norm_price) + $ovk_price + $omts_price + $other_plan, 2) ?> руб.<br/>
Плановая себестоимость: <?php echo number_format(($sn * $hour_cost) + $ovk_price + $omts_price, 2) ?> руб.<br/>
Фактическая себестоимость: <?php echo number_format((($sf + $parent_orders_fact) * $hour_cost) + $ovk_fact + $omts_fact + $other_fact, 2) ?> руб.<br/><br/>

Коммерческая стоимость:(КРЗ_План * ЦенаНЧ) + ПланОВК + ПланОМТС + ПланПрочее<br/>
Плановая себестоимость: (МТК * СебестоимостьНЧ) + ПланОВК + ПланОМТС + ПланПрочее<br/>
Фактическая себестоимость: ((ФактПроизодства + ФактПроизодстваВЗ) * СебестоимостьНЧ) + ФактОВК + ФактОМТС + ФактПрочее<br/>
</div>









































