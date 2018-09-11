<?php

// Удаление последовательных дублей
function ClearDoubleItems( & $dse_arr )
{
    $count = count( $dse_arr );
    $i = 0 ;
    
    for( $i = 0 ; $i < $count ; $i ++ )
    {
        if( 
                $dse_arr[$i]['zak_id'] == $dse_arr[ $i + 1 ]['zak_id'] &&
                $dse_arr[$i]['zakdet_id'] == $dse_arr[ $i + 1 ]['zakdet_id'] &&
                $dse_arr[$i]['zakdet_oboz'] == $dse_arr[ $i + 1 ]['zakdet_oboz'] &&
                $dse_arr[$i]['zakdet_name'] == $dse_arr[ $i + 1 ]['zakdet_name']
          )
            unset( $dse_arr[ $i + 1 ] );
    }
//    return $dse_arr ;
}


// Очистка временного элемента "итого" списка ДСЕ при группировке по типу чертежа
// для секции "итого"        
function ClearItem( & $item )
{
   $item = array();
   $item['zak_name'] = 'Итого';
   $item['zakdet_name'] = 'total';    
   $item['zakdet_oboz'] = 'total';    
   $item['zakdet_id'] = '';  
   $item['total_fact'] = 0;     
   $item['mtk'] = array();  
}        


// Наполнение временного элемента "итого" списка ДСЕ при группировке по типу чертежа
function sortByOrd( $a, $b ) 
{
    if ( $a['ord'] == $b['ord'] ) 
                return 0;
     return ($a['ord'] < $b['ord']) ? -1 : 1 ;
}

// array_column для предыдущих версий PHP
//Signature: array array_column ( array $input , mixed $column_key [, mixed $index_key ] )
if( !function_exists( 'array_column' ) ):
    
    function array_column( array $input, $column_key, $index_key = null ) {
    
        $result = array();
        foreach( $input as $k => $v )
            $result[ $index_key ? $v[ $index_key ] : $k ] = $v[ $column_key ];
        
        return $result;
    }
endif;


// Наполнение временного элемента "итого" списка ДСЕ при группировке по типу чертежа
function SumItem( & $temp_item , $item )
{   
// Общий план суммируется
   $temp_item['zakdet_plan'] += $item['zakdet_plan'];  

   foreach( $item['mtk'] AS $key => $value )
   {
       
//       if( !$item['mtk'][$key]['brak'] )
       {
           $ord = $item['mtk'][$key]['ord'] ;
           $temp_key  = array_search( $ord , array_column( $temp_item['mtk'], 'ord' ) ) ;       

          if( $temp_key === false )
            {
                $tmp_ord = $item['mtk'][$key] ;
                $temp_item['mtk'][] = $tmp_ord ;
            }
                else 
                    $temp_item['mtk'][$temp_key]['fact'] += $item['mtk'][$key]['fact'];
       }
   }
 
   usort( $temp_item['mtk'], 'sortByOrd' );
   
}



// Группировка ДСЕ по типу чертежа со вставкой элемента "итого"
function GroupDSE( $arr )
{
    $out_arr = array();
    $prev_oboz = '';
    $prev_item ;
    
    $total_item ;
    ClearItem( $total_item );

    $pass = 0 ;
    $first_pass = 0 ;
    
    foreach( $arr AS $item )
    {
       
       if( ! $item['total_fact'] ) 
           continue ;
        
       $cur_oboz = $item['zakdet_oboz'];
       
        if( $prev_oboz == $cur_oboz )
            {
                SumItem( $total_item, $prev_item );
                $pass ++ ;
            }
            else
            {
                if( $pass >= 1 )
                {
                    SumItem( $total_item, $prev_item );
                    $out_arr[] = $total_item ;
                    ClearItem( $total_item );
                    $pass = 0 ;
                }
                $prev_oboz = $cur_oboz;
            }

            
    $prev_item = $item ;                
    $out_arr[] = $item ;
    }
   return $out_arr ;
}        

// Получение списка операций        
function get_operations( $ops )
{
    $query = "SELECT NAME FROM okb_db_oper where ID IN (".join( $ops, ',').")" ;
    $res = mysql_query ( $query );
    $str = array();
    
    while( $row = mysql_fetch_array ( $res ))
            $str[]= $row['NAME'];
    
    return join( $str, ', ');
}        

// Получение дополнительного списка ДСЕ с одинаковыми чертежами из входного списка ДСЕ
function GetDSEBySameOboz( $list )
{
    global $db_prefix ;    
    $same_oboz_list = array();
    $zakdet_list = array();
    
    foreach( $list AS $zakdet_id )
        $zakdet_list[] = $zakdet_id ;
    
    $zakdet_list = join( $zakdet_list, ',');
    
    foreach( $list AS $zakdet_id )
    {
    $query = "SELECT zakdet.OBOZ zakdet_oboz 
              FROM ".$db_prefix."db_zakdet zakdet 
              WHERE zakdet.ID = ".$zakdet_id ;
    $result = dbquery( $query );
    $row = mysql_fetch_array( $result );
    $oboz = $row['zakdet_oboz'];

    $query = "SELECT zakdet.ID zakdet_id
              FROM ".$db_prefix."db_zakdet zakdet 
              LEFT JOIN okb_db_zak zak ON zak.ID = zakdet.ID_zak 
              WHERE 
              zakdet.OBOZ = '".$oboz."' 
              AND zakdet.ID NOT IN (".$zakdet_list.")  
              AND zak.EDIT_STATE = 0 
              GROUP BY zakdet_id" ;

// Тот-же чертеж и исключить исходный zakdet_id    
    
    $result = dbquery( $query );

    while( $row = mysql_fetch_array( $result ))
         $same_oboz_list[] = $row['zakdet_id'];
    }
    
  return $same_oboz_list ;
}

// Получение списка ДСЕ с заданным видоб обработки в заданный период
function GetDSEByDateAndOps( $pdate1, $pdate2, $ops )  
 {
     
     $oplist = join($ops,',');
     $datefrom = $pdate1 ;
     $dateto = $pdate2 ;
     
     $items_query = "
                SELECT zadan.ID_zakdet zakdet_id 

                FROM okb_db_zadan zadan 

                LEFT JOIN okb_db_operitems operitems ON zadan.ID_operitems = operitems.ID 
                LEFT JOIN okb_db_zak zak ON zak.ID = zadan.ID_zak 
                LEFT JOIN okb_db_zakdet zakdet ON zakdet.ID = zadan.ID_zakdet 

                WHERE 
                zadan.DATE >= '".$datefrom."' 
                AND 
                zadan.DATE <= '".$dateto."' 
                AND 
                zadan.EDIT_STATE = 1 
                AND 
                zadan.NUM_FACT > 0  
                AND 
                zak.EDIT_STATE = 0 
                AND
                operitems.ID_oper IN (".$oplist.")
                AND 
                zakdet.OBOZ <> '' 
                GROUP BY zakdet_id";
     
     $list = array();
     
     $result = dbquery( $items_query );
     
     while ( $row = mysql_fetch_array( $result ) )
         $list[]= $row['zakdet_id'] ;
     
     return $list; 
 }
 
// Получить реальную обработку ДСЕ по текущему ORD из МТК
function GetTreatment( $operitems_id )
{
    global $db_prefix ;
    
    $query = "SELECT zadan.SMEN zadan_smen, DATE_FORMAT( zadan.DATE, '%d.%m.%Y' ) zadan_date_str, zadan.DATE raw_date 
              FROM ".$db_prefix."db_zadan zadan 
              WHERE zadan.ID_operitems = ".$operitems_id." AND zadan.NUM_FACT <> 0
              ORDER BY zadan.DATE DESC LIMIT 1" ;

    $result = dbquery( $query );

// Получение последней даты и смены, когда был факт обработки
    $row = mysql_fetch_array( $result );
    $date = $row['zadan_date_str'];
    $smen = $row['zadan_smen'];
    $raw_date = $row['raw_date'];
    
// Получение итогового количества на выходе обработки
    $query = "SELECT SUM(NUM_FACT) fact FROM okb_db_zadan where  ID_operitems=".$operitems_id ;
    $res = mysql_query ( $query );
    $row = mysql_fetch_array ( $res );
    $fact_num = $row['fact'];

    $treatment = array( 'date' => $date , 'smen' => $smen , 'fact' => $fact_num , 'raw_date' => $raw_date );
    
    return $treatment ;
}

// Получить список ДСЕ с данными из списка с заданными zakdet_id
function GetDSEFromList( $zakdet_id )
{
    global $db_prefix ;
    
    $mtk = array();
    
// Получить общее описание ДСЕ    

    $query = "SELECT zak.name zak_name, zakdet.NAME zakdet_name, 
              zakdet.OBOZ zakdet_oboz, zakdet.RCOUNT zakdet_rcount, 
              zak.ID zak_id 
              FROM ".$db_prefix."db_zakdet zakdet 
              LEFT JOIN ".$db_prefix."db_zak zak ON  zak.ID = zakdet.ID_zak                   
              WHERE zakdet.ID = ".$zakdet_id ;
            
    $result = dbquery( $query );
    $row = mysql_fetch_array( $result );
    

    $zakdet_name = $row ['zakdet_name'];
    $zakdet_oboz = $row ['zakdet_oboz'];
    $zak_id = $row ['zak_id'];
    $zakdet_rcount = $row ['zakdet_rcount'];
    
    $zak = dbquery("SELECT * FROM ".$db_prefix."db_zak where ID=".$zak_id );
    $zak = mysql_fetch_array($zak);
    $zak_name = FVal($zak,"db_zak","TID")." ".$zak["NAME"];
    $zak_short_name = $zak["NAME"];
    $zak_dse_name = $zak["DSE_NAME"];
    
// END Получить общее описание ДСЕ    
    
// Получить МТК ДСЕ    

    $query = "SELECT 
              operitems.ID operitems_id, operitems.ORD ord, operitems.BRAK brak, 
              park.NAME park_name, park.MARK park_mark, 
              oper.NAME oper_name, oper.ID oper_id  
              
              FROM ".$db_prefix."db_operitems operitems 
              LEFT JOIN ".$db_prefix."db_park park ON park.ID = operitems.ID_park 
              LEFT JOIN ".$db_prefix."db_oper oper ON oper.ID = operitems.ID_oper                    
              WHERE operitems.ID_zakdet = ".$zakdet_id." AND operitems.ID_zak = ".$zak_id." AND operitems.ID_oper <> 0  
              ORDER BY ord";
            
    $result = dbquery( $query );

    $total_fact = 0 ;
    
    while( $row = mysql_fetch_array( $result ))
    {
        $operitems_id = $row['operitems_id'] ;
        $ord = $row['ord'] ;
        $brak = $row['brak'] ;        
        $oper_id = $row['oper_id'] ;        
        $oper_name = $row['oper_name'] ;
        $park_name = $row['park_name'] ;
        $park_mark = $row['park_mark'] ;        
        
        $treatment = GetTreatment( $operitems_id ); // Получить обработки
        $mtk[] = array( 'oper_id' => $oper_id, 'oper_name' => $oper_name, 
                        'park_name' => $park_name , 'park_mark' => $park_mark , 
                        'ord' => $ord , 'brak' => $brak , 'smen' => $treatment['smen'], 
                        'date' => $treatment['date'], 'fact' => $treatment['fact'],
                        'raw_date' => $treatment['raw_date'], 'operitems_id' => $operitems_id 
                      );
        
        if( ! $brak )
            $total_fact += $treatment['fact'] ;
    }

// END Получить МТК ДСЕ     

    $dse = array( 'zak_id' => $zak_id ,'zak_name' => $zak_name , 'zak_short_name' => $zak_short_name, 'zak_dse_name' => $zak_dse_name, 'zakdet_name' => $zakdet_name, 'zakdet_oboz' => $zakdet_oboz, 'zakdet_plan' => $zakdet_rcount, 'total_fact' => $total_fact, 'zakdet_id' => $zakdet_id, 'mtk' => $mtk );
    
    return $dse ;
}

function GetDSE( $pdate1, $pdate2, $ops )
{
       $dse_arr = array();
// Получить список zakdet_id по заданному типу обработки в заданный период
       $dseList = GetDSEByDateAndOps( $pdate1, $pdate2, $ops ) ;
// Если нет записей вернуть пустой список       
       if( count ( $dseList ) == 0 )
           return $dse_arr;
// Получить дополнительный список ДСЕ с таким-же типом чертежа, как у ДСЕ
// во входном списке, выполнить слияние обоих списков в один.       
       $dseList = array_merge( $dseList, GetDSEBySameOboz( $dseList ));
// Получить из полученного выше списка с zakdet_id, список из ДСЕ с полными данными
       foreach( $dseList AS $item )
           $dse_arr[] = GetDSEFromList( $item ); // В $dse_arr - массив всех ДСЕ
// Вернуть список ДСЕ       
       return $dse_arr ;
}

?>
