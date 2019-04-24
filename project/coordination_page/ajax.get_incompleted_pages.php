<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

//error_reporting( E_ALL );
error_reporting( 0 );

$user_id = $_POST['user_id'];
$can_delete_arr = $_POST['can_delete_arr'];
$can_delete = in_array( $user_id, $can_delete_arr ) ? 1 : 0 ;

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

function process_items( $data )
{
  $subdata = [];

  if( $data[2][ 'data' ][0]['coordinator_id'] )
  {
    unset( $data[1] );
    unset( $data[2] );
  }

  foreach( $data AS $key => $val )
  {
    foreach( $val['data'] AS $subkey => $subval ) 
      $subdata[] = $subval ;
  }

   foreach( $subdata AS $key => $val )

   if( $val['coordinator_id'] == 0 )
        return $val['row_name']." : ".$val['task_name'];

  return conv( "Этап окончательного согласования" );  
}

$str = "<table class='tbl' id='coord_table'>";
$str .= "<col width='2%'>";
$str .= "<col width='10%'>";
$str .= "<col width='10%'>";
$str .= "<col width='20%'>";
$str .= "<col width='20%'>";
$str .= "<col width='10%'>";
$str .= "<col width='2%'>";
$str .= "<col width='1%'>";

$str .= "<tr class='first'>";

$str .= "<td class='field'>".conv("№")."</td>";
$str .= "<td class='field'>".conv("КРЗ")."</td>";
$str .= "<td class='field'>".conv("ДСЕ")."</td>";
$str .= "<td class='field'>".conv("Чертеж")."</td>";
$str .= "<td class='field'>".conv("Этап")."</td>";
$str .= "<td class='field'>".conv("Создатель")."</td>";
$str .= "<td class='field'>".conv("Количество")."</td>";
$str .= "<td class='field AC'><div class='del_div'><img src='uses/del.png' class='del_page_capt' /></div></td>";
$str .= "</tr>";

$incompleted_arr = [];

        try
            {
                $query = "
            SELECT
            coordination_pages.id AS id, 
            coordination_pages.krz2_id,
            coordination_pages.frozen_by,
            okb_db_krz2.`NAME` AS krz2_name,
            okb_db_krz2det.OBOZ AS krz2_draw,
            okb_db_krz2det.`NAME` AS krz2_dse_name,
            okb_db_krz2det.COUNT AS count,
            users.FIO creator
            FROM
            coordination_pages
            LEFT JOIN okb_db_krz2 ON coordination_pages.krz2_id = okb_db_krz2.ID
            LEFT JOIN okb_db_krz2det ON okb_db_krz2.ID = okb_db_krz2det.ID_krz2
            LEFT JOIN okb_users users ON users.ID = coordination_pages.creator
            WHERE
            coordination_pages.coordinated = '0000-00-00' 
            ";

                            $stmt = $pdo->prepare( $query );
                            $stmt -> execute();
            }

            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Query : $query. Can't update data : " . $e->getMessage() );
            }


            while ( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
              $incompleted_arr[ $row -> id ] = 
                    [ 
                      'id' => $row -> id,
                      'krz2_id' => $row -> krz2_id,
                      'krz2_name' => conv( $row -> krz2_name ),
                      'krz2_draw' => conv( $row -> krz2_draw ),
                      'krz2_dse_name' => conv( $row -> krz2_dse_name ),
                      'creator' => conv( $row -> creator ),                      
                      'count' => $row -> count,
                      'frozen_by_id' => $row -> frozen_by
                    ] ;


if( count( $incompleted_arr ) )
{

foreach( $incompleted_arr AS $key => $val )
{
        try
            {
                $query = "
                            SELECT
                            coordination_page_items.id,
                            coordination_page_items.row_id,
                            coordination_page_items.coordinator_id,
                            coordination_pages_task.caption AS task_name,
                            coordination_pages_rows.caption AS row_name,
                            coordination_page_items.ignored,
                            coordination_page_items.date,
                            coordination_page_items.page_id
                            FROM
                            coordination_page_items 
                            LEFT JOIN coordination_pages_task ON coordination_page_items.task_id = coordination_pages_task.id
                            LEFT JOIN coordination_pages_rows ON coordination_page_items.row_id = coordination_pages_rows.id
                            WHERE
                            coordination_page_items.page_id = $key
                            AND
                            coordination_page_items.ignored = 0
                            ORDER BY coordination_page_items.id
                            ";

                            $stmt = $pdo->prepare( $query );
                            $stmt -> execute();

            }

            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Query : $query. Can't update data : " . $e->getMessage() );
            }

            $data = [];

            while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            {
              $data[ $row -> row_id ]['data'][] = 
              [
                'id' => $row -> id,
                'row_id' => $row -> row_id,
                'coordinator_id' => $row -> coordinator_id,
                'task_name' => conv( $row -> task_name ),
                'row_name' => conv( $row -> row_name ),
                'date' => $row -> date
              ];
            }

    
    $incompleted_arr[$key]['stage'] = process_items( $data );
}

        $line = 1 ;
        foreach ( $incompleted_arr as $key => $value ) 
            {
              $frozen = $value['frozen_by_id'];

              $str .= "<tr data-id='".$value['id']."' class='".( $frozen ? 'frozen' : '' )."'>";
              $str .= "<td class='field AC'><a href='index.php?do=show&formid=30&id=".$value['krz2_id']."' target='_blank'>$line</a></td>";
              $str .= "<td class='field'>(".$value['id'].") ".$value['krz2_name']."</td>";
              $str .= "<td class='field'>".$value['krz2_dse_name']."</td>";
              $str .= "<td class='field'>".$value['krz2_draw']."</td>";     
              $str .= "<td class='field'>".$value['stage']."</td>";
              $str .= "<td class='field AC'>".$value['creator']."</td>";
              $str .= "<td class='field AC'>".$value['count']."</td>";
              $str .= "<td class='field AC'><div class='del_div'><img ";
              if( $can_delete )
                  $str .= "src='uses/del.png' class='del_page' />";
                      else
                        $str .= "src='uses/del_dis.png' class='del_page_dis' />";
              
              $str .= "</div></td>";
              $str .= "</tr>";
              $line ++ ;
            }
}

$str .= "</table>";

if( strlen( $dbpasswd ) )
  echo $str ;
    else
      echo iconv("Windows-1251", "UTF-8", $str );