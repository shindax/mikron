<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

$user_id = $_POST['user_id'];
$can_delete_arr = $_POST['can_delete_arr'];
$can_delete = in_array( $user_id, $can_delete_arr ) ? 1 : 0 ;

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

$str = "<table class='tbl' id='coord_table'>";
$str .= "<col width='2%'>";
$str .= "<col width='5%'>";
$str .= "<col width='10%'>";
$str .= "<col width='20%'>";
$str .= "<col width='20%'>";
$str .= "<col width='2%'>";
$str .= "<col width='1%'>";

$str .= "<tr class='first'>";

$str .= "<td class='field'>".conv("№")."</td>";
$str .= "<td class='field'>".conv("Дата<br>завершения")."</td>";
$str .= "<td class='field'>".conv("КРЗ2")."</td>";
$str .= "<td class='field'>".conv("ДСЕ")."</td>";
$str .= "<td class='field'>".conv("Чертеж")."</td>";
$str .= "<td class='field'>".conv("Количество")."</td>";
$str .= "<td class='field AC'><div class='del_div'><img src='uses/del.png' class='del_page_capt' /></div></td>";
$str .= "</tr>";

$query = "";

        try
            {
                $query = "
                            SELECT
                            coordination_pages.krz2_id,
                            DATE_FORMAT( coordination_pages.coordinated, '%d.%m.%Y') coordinated,
                            okb_db_krz2.`NAME` AS krz2_name,
                            okb_db_krz2det.OBOZ AS krz2_draw,
                            okb_db_krz2det.`NAME` AS krz2_dse_name,
                            coordination_pages.id AS page_num,
                            okb_db_krz2det.COUNT AS count
                            FROM
                            coordination_pages
                            LEFT JOIN okb_db_krz2 ON coordination_pages.krz2_id = okb_db_krz2.ID
                            LEFT JOIN okb_db_krz2det ON okb_db_krz2.ID = okb_db_krz2det.ID_krz2
                            WHERE coordination_pages.coordinated <> '0000-00-00'
                            ";

                            $stmt = $pdo->prepare( $query );
                            $stmt -> execute();
            }

            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Query : $query. Can't update data : " . $e->getMessage() );

            }
       
            $line = 1 ;
            while ( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            {
              $str .= "<tr data-id='".( $row -> page_num )."'>";
              $str .= "<td class='field AC'><a href='index.php?do=show&formid=30&id=".$row -> krz2_id."' target='_blank'>$line</a></td>";
              $str .= "<td class='field'>".conv( $row -> coordinated )."</td>";
              $str .= "<td class='field'>".conv( $row -> krz2_name )."</td>";
              $str .= "<td class='field'>".conv( $row -> krz2_dse_name )."</td>";
              $str .= "<td class='field'>".conv( $row -> krz2_draw )."</td>";     
              $str .= "<td class='field AC'>".conv(  $row -> count )."</td>";
              $str .= "<td class='field AC'><div class='del_div'><img ";
              
              if( $can_delete )
                  $str .= "src='uses/del.png' class='del_page' />";
                      else
                        $str .= "src='uses/del_dis.png' class='del_page_dis' />";
             
              $str .= "</div></td>";
              $str .= "</tr>";
              $line ++;
            }

$str .= "</table>";

if( strlen( $dbpasswd ) )
  echo $str ;
    else
      echo iconv("Windows-1251", "UTF-8", $str );