<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="/project/sklad/js/bootstrap.min.js"></script>
<script src="/project/sklad/js/warehouse.js"></script>

<link rel='stylesheet' id="bootstrap-css" href='/project/sklad/css/bootstrap.min.css' type='text/css'>
<link rel='stylesheet' href='/project/sklad/css/style.css' type='text/css'>

<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

global $user;
$user_id = $user['ID'];
$semifin_invoices = [];
echo "<script>var user_id = $user_id;</script>";

	try
	{
	    $query ="
	    		 SELECT res.id, res.state,  detitem.NAME dse_name, detitem.KOMM comment, res.count res_count, detitem.COUNT count, inv.inv_num, detitem.ID detitem_id, tier.ORD tier_name, item.NAME item_name, wh.NAME wh_name, users.IO user_name, res.user_id user_id
	    		 FROM `okb_db_warehouse_reserve` res
	    		 LEFT JOIN okb_db_sklades_detitem detitem ON detitem.ID = res.tier_id
	    		 LEFT JOIN okb_db_semifinished_store_invoices inv ON inv.warehouse_item_id = res.tier_id
           LEFT JOIN okb_db_sklades_yaruses tier ON tier.ID = detitem.ID_sklades_yarus
           LEFT JOIN okb_db_sklades_item item ON item.ID = tier.ID_sklad_item
           LEFT JOIN okb_db_sklades wh ON wh.ID = item.ID_sklad 
           LEFT JOIN okb_users users ON users.ID = res.user_id
	    		 WHERE 1
           ORDER BY res.id
           " ;
	    $stmt = $pdo->prepare( $query );
	    $stmt -> execute();
	}
	catch (PDOException $e)
	{
	   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
	}
	while ( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
	{
		$semifin_invoices[] = 
			[
        'rec_id' => $row -> id,
				'inv_num' => $row -> inv_num,
				'dse_name' => conv( $row -> dse_name ),
				'comment' => conv( $row -> comment ),
				'count' => $row -> count,
				'res_count' => $row -> res_count,
				'detitem_id' => $row -> detitem_id,
				'state' => $row -> state,
        'tier' => $row -> tier_name,
        'item' => conv( $row -> item_name ),
        'wh' => conv( $row -> wh_name ),
        'user_name' => conv( $row -> user_name ),
        'user_id' => $row -> user_id
			];		
	}

$str = "<h2>".conv("Заявки на выдачу полуфабрикатов")."</h2>";
$str .= "<div class='container'>";

$str .= "<hr>";


$str .= "
    <div class='row'>
    <div class='col-sm-1 offset-sm-11'>
    <button class='btn btn-big btn-primary float-right' id='create'>".conv("Создать")."</button>
    </div>
    </div>";


$str .= "
<div class='row'>
<table id='semifin_invoices' class='table table-striped'>
<col width='2%'>
<col width='3%'>
<col width='30%'>
<col width='30%'>
<col width='5%'>
<col width='5%'>
<col width='5%'>
<col width='5%'>
<col width='5%'>
<col width='10%'>
<col width='10%'>
<col width='10%'>
<col width='2%'>

  <thead>
    <tr class='table-primary'>
      <th>".conv( "№" )."</th>
      <th>".conv( "№ накл" )."</th>      
      <th>".conv( "ДСЕ" )."</th>
      <th>".conv( "Комментарий" )."</th>
      <th>".conv( "Склад" )."</th>
      <th>".conv( "Ячейка" )."</th>
      <th>".conv( "Ярус" )."</th>            
      <th>".conv( "Кол." )."</th>
      <th>".conv( "Запр. кол." )."</th>      
      <th>".conv( "Заявитель." )."</th>            
      <th>".conv( "Состояние" )."</th>
      <th><span class='glyphicon glyphicon-print'></span></th>      
      <th></th>      
    </tr>
  </thead>
  <tbody>";

 $line = 1 ;
        foreach( $semifin_invoices AS $val )
        {
          $rec_user_id = $val['user_id'];
          $img_class = 'del_img_dis';
          $img_pict = '/uses/del_dis.png';

          if( $rec_user_id == $user_id )
          {
            $img_class = 'del_img';
            $img_pict = '/uses/del.png';
          }

          $print = "<button class='btn btn-success print_button'><span class='glyphicon glyphicon-print'></span></button>";


        	if( $val['state'] )
          {
        		$issue = "<button data-state='1' class='btn issue_button' disabled >".conv("Выдано")."</button>";

            $img_class = 'del_img_dis';
            $img_pict = '/uses/del_dis.png';
          }
        		else
            {
        			$issue = "<button data-state='0' class='btn btn-info issue_button'>".conv("Выдать")."</button>";
            }


            $str .=
                "<tr data-id='".$val['detitem_id']."' data-rec-id='".$val['rec_id']."'>
                  <td class='AC'><span class='line'>$line</span></td>
                  <td class='AC'><span class='inv_num'>".$val['inv_num']."</span></td>
                  <td class='AL'><span>".$val['dse_name']."</span></td>
                  <td class='AL'><span>".$val['comment']."</span></td>
                  <td class='AC'><span>".$val['wh']."</span></td>
                  <td class='AC'><span>".$val['item']."</span></td>
                  <td class='AC'><span>".$val['tier']."</span></td>
                  <td class='AC'><span class='count'>".$val['count']."</span></td>
                  <td class='AC'><span class='res_count'>".$val['res_count']."</span></td>                  
                  <td class='AC'><span>".$val['user_name']."</span></td>
				          <td class='AC'>$issue</td>
                  <td class='AC'>$print</td>                  
                  <td class='AC'><img class='$img_class' src='$img_pict' /></td>                  
                </tr>";
                $line ++ ;
        }



$str .= "</table></div></div>";

echo $str ;
//debug( $semifin_invoices );
