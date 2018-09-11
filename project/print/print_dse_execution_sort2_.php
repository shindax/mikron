<style>
   
#Printed * {
	font : normal 12pt "Times New Roman" Arial Verdana;
}
#Printed span.CODE39 {
	font : normal 36pt CODE39;
}

#Printed H6 {FONT : bold 6pt "Times New Roman" Arial; COLOR : black; TEXT-ALIGN : left;}
#Printed H5 {FONT : bold 8pt "Times New Roman" Arial; COLOR : black; TEXT-ALIGN : left;}
#Printed H4 {FONT : bold 10pt "Times New Roman" Arial; COLOR : black; TEXT-ALIGN : left;}
#Printed H3 {FONT : bold 12pt "Times New Roman" Arial; COLOR : black; TEXT-ALIGN : center;}
#Printed H2 {FONT : bold 16pt "Times New Roman" Arial; COLOR : black; TEXT-ALIGN : center;}
#Printed H1 {FONT : bold 20pt "Times New Roman" Arial; COLOR : black; TEXT-ALIGN : center;}

#Printed b {
	font : bold 12pt "Times New Roman" Arial Verdana;
}

#PageTable {
	BORDER : black 2px solid;
        COLOR : #000;
        BORDER-COLLAPSE : collapse;
        Text-Align : center;
	Vertical-Align : middle;     
}

#PageTable TR TD {
	BORDER : black 1px solid;
	PADDING-RIGHT : 4px;
	PADDING-LEFT : 6px;
	PADDING-BOTTOM : 4px;
	PADDING-TOP : 4px;
	font : normal 12pt "Times New Roman" Arial Verdana;
	height : 19px;
	text-align: center;
	vertical-align: middle;
	background: #fff;
}

#PageTable TR.first TD {
	text-align: center;
}

#PageTable table.itable {
	border: none;
	padding: 0px;
	margin: 0px;
	width: 100%;
	background: none;
}

#PageTable table.itable td {
	border: none;
	padding: 0px;
	margin: 0px;
	background: none;
}

#PageTable table.itable tr {
	border: none;
	padding: 0px;
	margin: 0px;
	background: none;
}

#PageTable TR TD b {
	font : bold 12pt "Times New Roman" Arial Verdana;
	color: black;
}

div.a4p {
	width : 1000px;
	text-align: left;
	background: #fff;
	page-break-after:always;
}

.view div.a4p {
	display: block;
	border: 1px solid #444;
	padding: 20px;
	box-shadow: 3px 4px 20px #555555;
	margin: 40px;
}

table.view {
	width: 100%;
	margin: 0px;
	padding: 0px;
}

.total
{
     background-color:#DDD;
}

</style>
<center>
<?php

        if( isset( $_GET["p0"] ))
        if( isset( $_GET["p0"] ))
            {
                $date1 = $_GET["p0"];
                $pdate1 = DateToInt($date1);
            }
        if( isset( $_GET["p1"] ))
            {
                $date2 = $_GET["p1"];
                $pdate2 = DateToInt($date2);
            }
        
        if( isset( $_GET["op_id"] ))
                $ops = $_GET["op_id"];

$ord_ars ;
$ord_arr;
$orders ;
$dse_cnt = 1 ;

function get_fact( $operitems_id )
{
    $query = "SELECT SUM(NUM_FACT) fact FROM okb_db_zadan where  ID_operitems=".$operitems_id ;
    $res = mysql_query ( $query );
    $row = mysql_fetch_array ( $res );
    return $row['fact'];
}

function get_max_date_str( $operitems_id )
{
    $query = "SELECT DATE_FORMAT( MAX( zadan.DATE ), '%d.%m.%Y' ) maxdate FROM okb_db_zadan zadan where NUM_FACT <> 0 AND ID_operitems=".$operitems_id ;
    $res = mysql_query ( $query );
    $row = mysql_fetch_array ( $res );
    return $row['maxdate'];
}

function get_max_date( $operitems_id )
{
    $query = "SELECT MAX( zadan.DATE ) maxdate FROM okb_db_zadan zadan where NUM_FACT <> 0 AND ID_operitems=".$operitems_id ;
    $res = mysql_query ( $query );
    $row = mysql_fetch_array ( $res );
    return $row['maxdate'];
}

function get_max_date_smen( $operitems_id )
{
    $query = "SELECT MAX( zadan.DATE ) maxdate, MAX(SMEN) smen   FROM okb_db_zadan zadan where NUM_FACT <> 0 AND ID_operitems=".$operitems_id ;
    $res = mysql_query ( $query );
    $row = mysql_fetch_array ( $res );
	$date = $row['maxdate'];  
	
    $query = "SELECT SMEN smen FROM okb_db_zadan zadan 
	where NUM_FACT <> 0 AND ID_operitems=".$operitems_id." AND DATE=".$date ;

    $res = mysql_query ( $query );
    $row = mysql_fetch_array ( $res );
	$smen = $row['smen'];  
	
	return $smen ;
}

// Печать шапки таблицы
function PrintTableHead( $val )
{
   
    echo "<table ID='PageTable' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width='1000';' border='1' cellpadding='0' cellspacing='0'>";
    echo "<tr class='first'>";
    echo "<td rowspan='2' width='8%'><b>".$val."</b></td>";
    echo "<td colspan='2'><b>Операция</b></td>";
    echo "<td rowspan='2' width='15%'><b>Оборудование</b></td>";
    echo "<td rowspan='2' width='5%'><b>Дата<br>/<br>смена</b></td>";        
    echo "<td rowspan='2' width='5%'><b>План,<br>шт.</b></td>";
    echo "<td rowspan='2' width='5%'><b>Факт,<br>шт.</b></td>";
    echo "<td rowspan='2' width='5%'><b>Остаток,<br>шт.</b></td>";
    echo "</tr>\n";
    echo "<tr class='first'>\n";
    echo "<td width='5%'><b>№</b></td>";
    echo "<td  width='15%'><b>Наименование</b></td>";
    echo "</tr>\n";
        
 } 

 function PrintTableFoot()
 {
     echo '</table>';
 }

// Возвращает список всех ДСЕ с заданным чертежом ДСЕ
function GetItemListByDSE( $dse )
 {
     $items_query = "
                    SELECT 
                    zakdet.ID zakdet_id 
                    FROM okb_db_zadan zadan 
                    LEFT JOIN okb_db_zakdet zakdet ON zakdet.ID = zadan.ID_zakdet 
                    LEFT JOIN okb_db_zak zak ON zak.ID = zadan.ID_zak 
                    WHERE 
                    zakdet.OBOZ = '".$dse."' 
                    AND 
                    zak.EDIT_STATE = 0
                    
                    AND 
                    ORD( zak.PD8 ) <> 49  # Отфильтровать выполненные заказы

                    GROUP BY zakdet_id ORDER BY zak.NAME
                    ";
     
     $result = dbquery( $items_query );     
     
     $list = array();
     while ( $row = mysql_fetch_array( $result ) )
         $list[] = $row['zakdet_id'] ;
     
     return $list ;
  }        
 
// Печать раздела таблицы "Итого" 
function PrintTotal( $values )  
{
    
        reset( $values );
        $cur_rec = current( $values );
        $name = $cur_rec['name'];
        $oboz = $cur_rec['oboz'];
        $title = 'Итого';
        usort( $values , 'ord_sort');
        
        foreach( $values as $key => $val )
        {
        echo "<tr>";                
        echo "<td style='background-color:#DDD'>".$title."</td>";
        echo "<td style='background-color:#DDD'>".$val['ord']."</td>";        
        echo "<td style='background-color:#DDD'>".$val['oper_name']."</td>";
        echo "<td style='background-color:#DDD'>".$val['park_name']."</td>";        
        echo "<td style='background-color:#DDD'>-</td>";
        echo "<td style='background-color:#DDD'>".$val['num']."</td>";        
        echo "<td style='background-color:#DDD'>".$val['fact']."</td>";
        echo "<td style='background-color:#DDD'>".$val['balance']."</td>";        
        echo "</tr>\n";
        $name = '';
        $oboz = '';
        $title = '';
        }// foreach( $values as $key => $val )
 
}// function PrintTotal( $values )

// Подготовка данных для печати раздела ДСЕ таблицы 
function DSE_out( $dse_arr , $operations )
{     
        global $pdate1, $pdate2, $ops , $debug, $dse_cnt, $ord_arr, $show_id, $orders ;
        
       $prev_ord = 0 ;
       
       foreach( $dse_arr AS $dse ) 
       {
$operitems_query =     "SELECT 
                        operitems.ID,
                        operitems.ID_zak zak_id,
                        
                        zak.NAME zak_name,
                        
                        zakdet.ID zakdet_id,
                        zakdet.NAME zakdet_name,
                        zakdet.OBOZ zakdet_oboz,
                        zakdet.RCOUNT zakdet_rcount,
                        
                        oper.NAME oper_name,

                        park.NAME park_name,
                        park.MARK park_mark,

                        operitems.ORD ord,
                        operitems.ID_oper oper_id,                        

                        zadan.DATE zadan_date, 
                        DATE_FORMAT( zadan.DATE , '%d.%m.%Y' ) zadan_date_str,                        
                        zadan.smen zadan_smen,
                        zadan.id zadan_id,
                        zadan.EDIT_STATE zadan_edit_state,                        
                        zadan.NUM zadan_num,
                        zadan.NUM_FACT zadan_num_fact,

                        operitems.ID operitems_id,
                        operitems.NUM_ZAK operitems_num,
                        operitems.FACT2_NUM operitems_fact 

                        FROM okb_db_operitems operitems 
 
                        LEFT JOIN okb_db_zadan zadan ON operitems.ID = zadan.ID_operitems 
                        LEFT JOIN okb_db_zak zak ON zak.ID = operitems.ID_zak 
                        LEFT JOIN okb_db_zakdet zakdet ON zakdet.ID = operitems.ID_zakdet
                        LEFT JOIN okb_db_oper oper ON oper.ID = operitems.ID_oper
                        LEFT JOIN okb_db_park park ON park.ID = operitems.ID_park

                        WHERE operitems.ID_zakdet=".$dse." 
                        GROUP BY ord ORDER BY  ord, zadan_date 
                        ";

        $result = dbquery( $operitems_query );
      
        $pass = 0 ;
        $ord_arr = array();
        
        while ( $row = mysql_fetch_assoc( $result ) )
        {
            
        $zakdet_name     = $row['zakdet_name'];
        $zakdet_id       = $row['zakdet_id'];
        
        if( $show_id )
            $zakdet_name .= '<br> zakdet_id : '.$zakdet_id.'' ;        
        
        $oboz         = $row['zakdet_oboz'];
        $oboz_str = $oboz ;
        $operitems_id = $row['operitems_id'];
        
        if( $show_id )
            $oboz_str .= '<br> operitems_id : '.$operitems_id.'' ;
        
        $zak_id     = $row['zak_id'];
        $zak_name   = $row['zak_name'];
        
        if( $show_id )
            $zak_name .= '<br> zak_id : '.$zak_id.'' ;        
        
        $oper_id    = $row['oper_id'];        
        $ord = $row['ord']; 
        
        $zadan_id    = $row['zadan_id'];        
        $oper_name  = $row['oper_name'];
            
        if( $show_id )
                $oper_name  .= '<br> operitems_id : '.$operitems_id.'' ;
            
        $park_name  = $row['park_name'].'<br>'.$row['park_mark'];
        $zadan_smen = $row['zadan_smen'];        
        $zakdet_rcount = $row['zakdet_rcount'];
        $num = $zakdet_rcount ;
        $fact = get_fact( $operitems_id );
        $zadan_date = get_max_date( $operitems_id );

        $zadan_date_str = get_max_date_str( $operitems_id );
	$zadan_smen = get_max_date_smen( $operitems_id );		
		
        if( $zadan_date_str != '00.00.0000' )
            $zadan_date_smen = '<u>'.$zadan_date_str.'</u><br><o>'.$zadan_smen.'</o>' ;

        if( $zadan_date_str != '00.00.0000' )
            $zadan_date_smen = '<u>'.$zadan_date_str.'</u><br><o>'.$zadan_smen.'</o>' ;

        $balance = $num - $fact ;

        $selected = 0 ;
        
        if( ( $zadan_date >= $pdate1 && $zadan_date <= $pdate2 ) && ( in_array( $oper_id , $ops ) ) )
            $selected = 1 ;
       
        $ord_arr[ $ord ]['total_fact'] += $fact ;
        $ord_arr[ $ord ]['ord'] = $ord ;
        $ord_arr[ $ord ]['zakdet_id'] = $zakdet_id ;        
        $ord_arr[ $ord ]['zakdet_name'] = $zakdet_name ;
        $ord_arr[ $ord ]['oboz'] = $oboz ; 
        $ord_arr[ $ord ]['zak_name'] = $zak_name ; 
        $ord_arr[ $ord ]['zak_id'] = $zak_id ;         
        $ord_arr[ $ord ]['oper_name'] = $oper_name ; 
        $ord_arr[ $ord ]['park_name'] = $park_name ; 
        $ord_arr[ $ord ]['zadan_date_smen'] = $zadan_date_smen ; 
        $ord_arr[ $ord ]['num'] = $num ; 
        $ord_arr[ $ord ]['fact'] += $fact ;
        $ord_arr[ $ord ]['selected'] = $selected ; 
        $ord_arr[ $ord ]['operitems_id'] = $operitems_id ;
        $ord_arr[ $ord ]['zadan_id'] = $zadan_id ;
        };
  }
 
    foreach( $ord_arr AS $item )
  {
      $ord = $item['ord'];

       $operations[ $ord ]['total_fact'] += $item['fact'] ;
       $operations[ $ord ]['ord']         = $ord ;                
       $operations[ $ord ]['num']        += $item['num'] ;
       $operations[ $ord ]['fact']       += $item['fact'] ;
       $operations[ $ord ]['balance']    +=  ( $item['num'] - $item['fact'] );        
       $operations[ $ord ]['oper_name']   = $item['oper_name'] ;
       $operations[ $ord ]['park_name']   = $item['park_name'] ;        
       $operations[ $ord ]['name']        = $item['name'] ;        
       $operations[ $ord ]['oboz']        = $item['oboz'] ;
  
        array_push( $orders , $item );
       
  }
 
  return $operations ;

}// function DSE_out( $dse_arr )

function get_operations( $ops )
{
    global $ops ;
    $query = "SELECT NAME FROM okb_db_oper where ID IN (".join( $ops, ',').")" ;
    $res = mysql_query ( $query );
    $str = array();
    
    while( $row = mysql_fetch_array ( $res ))
            $str[]= $row['NAME'];
    
    return join( $str, ', ');
}

// Возвращает $list - список ДСЕ попавщих в период дат и по типу обработки       
 function GetItemsByDateAndOps( $datefrom, $dateto, $oplist )  
 {
     global $debug ;
     
     $items_query = "SELECT zakdet.NAME zakdet_name, zakdet.OBOZ oboz, zak.NAME zak_name, zakdet.ID zakdet_id, zadan.DATE zadan_date, zak.ID zak_id 
                
                FROM okb_db_zadan zadan 

                LEFT JOIN okb_db_operitems operitems ON zadan.ID_operitems = operitems.ID 
                LEFT JOIN okb_db_zak zak ON zak.ID = zadan.ID_zak 
                LEFT JOIN okb_db_zakdet zakdet ON zakdet.ID = zadan.ID_zakdet 

                WHERE 
                zadan.DATE >= '".$datefrom."' 
                AND 
                zakdet.OBOZ <> '' 
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
                
                GROUP BY oboz ORDER BY zakdet_name, oboz, zak_name";
     
     $list = array();
     
     $result = dbquery( $items_query );
     
     while ( $row = mysql_fetch_array( $result ) )
     {
         $list[]= array('zakdet_name' => $row['zakdet_name'], 'oboz' => $row['oboz'], 'zak_name'=> $row['zak_name'], 'zakdet_id' => $row['zakdet_id'] , 'zadan_date' => $row['zadan_date'] );
         
         if( $debug )
                echo join( array( $row['zakdet_id'], $row['zakdet_name'], $row['oboz'], $row['zak_name'],' zak_id : '. $row['zak_id'], $row['zadan_date'] ) ,',').'<br>';
     }
     
     return $list; 
 }

// Печать таблицы ДСЕ
function PrintZakSortedTable( $zak_array )
{
    global $db_prefix ;
   
    $cnt = 1 ;
    foreach( $zak_array AS $key => $value )
    {

        $zak_id = dbquery("SELECT ID FROM ".$db_prefix."db_zak where NAME='".$key."'");
        $zak_id = mysql_fetch_array($zak_id);
        $zak_id = $zak_id['ID'];
        
        $zak = dbquery("SELECT * FROM ".$db_prefix."db_zak where ID=".$zak_id );
        $zak = mysql_fetch_array($zak);
        $zak_name = FVal($zak,"db_zak","TID")." ".$zak["NAME"];
        
        echo "<tr class='first'><td style='background-color:#DDD' colspan='8'><b>".$cnt++.". Заказ ".$zak_name."</b></td></tr>";
        
        foreach( $value AS $item )
        {
            $first_pass = 0 ;
            foreach( $item AS $subitem )
         {
            if( !$first_pass ++ )
            {
                $oboz = $subitem['oboz'] ;            
                $name = $subitem['zakdet_name'] ;
                $st = "style='background-color:#EEE'";
            }
                else
                {
                    $oboz = '';
                    $name = '';
                    $st = '';
                }    
                    
            $ord  = $subitem['ord'] ;             
            $oper_name  = $subitem['oper_name'] ;
            $park_name  = $subitem['park_name'] ;
            $park_mark  = $subitem['park_mark'] ;
            $zadan_date_smen = $subitem['zadan_date_smen'] ;
            $operitems_id = $subitem['operitems_id'] ;
            $selected = $subitem['selected'] ;
            
            $num  = $subitem['num'] ; 
            $fact  = $subitem['fact'] ; 
            
            if( $fact )
              $link = $fact;
                 else 
                     $link = 0 ;

            $row = "<tr>";
        
            if( $selected )
                $row = "<tr class='selected'>";

            if( !$fact )
                $row = "<tr class='empty'>";
        
            if( !$fact && $selected )
                $row = "<tr class='selected_empty'>";
                 
            echo $row ;
            echo "<td ".$st."><b><u>".$name."</u><br>".$oboz."</b></td>";
            echo "<td ".$st."><b>".$ord."</b></td>";            
            echo "<td ".$st.">".$oper_name."</td>";
            echo "<td ".$st."><u>".$park_name."</u><br>".$park_mark."</td>";            
            echo "<td ".$st.">".$zadan_date_smen."</td>";
            echo "<td ".$st."><b>".$num."</b></td>";            
            echo "<td ".$st."><b>".$link."</b></td>";
            echo "<td ".$st."><b>".($num - $fact)."</b></td>";            
            echo "</tr>";
        }
      }
      
    }
  return $str ;
}


 
 
global $date1, $date2, $pdate1, $pdate2, $ord_arr, $ops, $orders ;
    
	echo "
	<div id='Printed' class='a4p'>
	Отчёт от ".date("d.m.Y H:i",mktime())."<br>
        Сортировка по № заказа
	<H2>Выполнение ДСЕ за период с ".$date1." по ".$date2." </H2>";
        
        echo '<h3>';
        if( count( $ops ) > 1 )
            echo "Операции : ";
                else 
                    echo "Операция : ";

       echo get_operations($ops).'</h3>';

    $str = '';
    
    $orders = array();
    
    if( $profiling )
        StartTimer();
       
// SQL-запрос с перечнем деталей из заказов, где имеется выбранная обработка               

       $list = GetItemsByDateAndOps( $pdate1 , $pdate2 , join($ops,',') ) ;

       $list_count = count( $list );
       
       if ( $list_count )
       {
           PrintTableHead( 'ДСЕ' );
           foreach( $list as $item )
            {

                $temp_list = GetItemListByDSE( $item['oboz'] );
                
                $operations = array();

                $temp_list_cnt = count ( $temp_list ) ;

                    foreach( $temp_list as $temp_item )
                    {
                       $operations = DSE_out( array( $temp_item ) , $operations );
                       $tfact = 0 ;
                     
                       foreach( $ord_arr AS $item )
                        $tfact += $item['total_fact'];
                       
                    }
            }
            
            
            $zak_array = array();
            $cur_temp_zak_name = 0 ;
            $cur_temp_zakdet_id = 0 ;            
            
            foreach( $orders AS $item )
            {
                $temp_zak_name = $item['zak_name'];                
                $temp_zakdet_name = $item['zakdet_id'];                
                
                if( $cur_temp_zak_name != $temp_zak_name )
                {
                    $type = gettype( $zak_array[$temp_zak_name] );
                    if(  $type == 'NULL')
                        $zak_array[$temp_zak_name] = array();
                    $cur_temp_zak_name = $temp_zak_name ;
                
                    if( $cur_temp_zakdet_name != $temp_zakdet_name )
                    {
                        $type = gettype( $zak_array[$temp_zak_name][$temp_zakdet_name] );
                        if(  $type == 'NULL')
                            $zak_array[$temp_zak_name][$temp_zakdet_name] = array();
                        $cur_temp_zakdet_name = $temp_zakdet_name ;
                    }
                }
                
                  $zak_array[$temp_zak_name][$temp_zakdet_name][] = $item ;
            }
            
            ksort( $zak_array );
            
            foreach( $zak_array AS $item )
                usort( $item , 'zakdet_name_sort');

            PrintZakSortedTable( $zak_array );
            PrintTableFoot();
       }
       else
            echo "<h3>Записей не найдено</h3>";         


       echo '</div>'
?>
</center>