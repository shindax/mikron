<style>
.dse_head
{
    background-color : #DDD;
}

.brak
{
    background-color :  #FF7F50 ; /*#DCDCDC ;*/
    text-color: white ;
}

.selected
{
    background-color : #FFC0CB; /*#DCDCDC ;*/
}

.total
{
    background-color : yellow; /*#DCDCDC ;*/
	vertical-align:middle !IMPORTANT;	
}

.empty
{
    background-color : #BBB;
	vertical-align:middle !IMPORTANT;	
}

.total
{
vertical-align:middle !IMPORTANT;
}

.Field
{
vertical-align:middle !IMPORTANT;
}


</style>

<script type='text/javascript'>

// Разблокировка кнопки "Отчёт"
    function op_select()
    {
           document.getElementById('calc').disabled = false ;
    }

// Переключение состояния радиокнопок
    function simple_check_radio( id )
    {
        document.getElementById('oboz_id').checked ^= true ;
        document.getElementById('zak_id').checked ^= true ;
    }

// Обработка радиокнопок вида сортировки
    function check_radio( id )
    {
        var loc = window.location.href;
        var arg_pos = loc.indexOf("&sort=");
        var full_filter, partial_filter;

        document.getElementById('oboz_id').checked ^= true ;
        document.getElementById('zak_id').checked ^= true ;


        if( id == 'oboz_id')
        {
            full_filter = '&sort=1';
            partial_filter = '1';
        }

        if( id == 'zak_id')
        {
            full_filter = '&sort=2';
            partial_filter = '2';
        }
      
            if( arg_pos == -1 )
               loc += full_filter;
                else
                {
                    var str = loc.substring( 0 , arg_pos + 6 );
                    str += partial_filter;
                    str += loc.substring( arg_pos + 7 );
                    loc = str ;
                }   

      location.href = loc ;

    }

// Возврат к экрану выбора даты/обработки
function select_job()
{
    location.href = '/index.php?do=show&formid=192';
}

</script>    
<?php
/* Причесанный отчет ДСЕ по обработкам в период */

include "project/dse_execute_functions.php";

$step = 1;
$date1 = '';
$pdate1 = '';
$date2 = '';
$pdate2 = '';
$ops = array();
$sort = 1 ;
$list_count ;

        if( isset( $_GET["sort"] ))
            $sort = $_GET["sort"] ;

        if( isset( $_GET["shid"] ))
            $show_id = 1 ;
        
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
        if( isset( $_GET["p2"] ))
                $ops = $_GET["p2"];

	if ( ( $pdate1 > 0 ) && ( $pdate2 >= $pdate1 ) ) 
               $step = 2;
      
// Печать шапки таблицы
function PrintTableHead( $val , $sort, $date1, $date2, $ops )
{
    
    $str = '';
   
    $print_url = '/index.php?do=show&formid=';
    if( $sort == 1 )
            $print_url .= '193&p0='.$date1.'&p1='.$date2;
    if( $sort == 2 )
            $print_url .= '194&p0='.$date1.'&p1='.$date2;
    
    foreach( $ops AS $op )
        $print_url .= '&p2[]='.$op ;

    $link = "<a id='prnt_dt_sz' class='acl' href='".$print_url."' target='_blank' style='font-size:12pt;text-align:right'>Версия для печати</a>";
    
    $str .= "<table style='width:1200; padding-top:15px; margin-bottom:15px;'><tr><td style='text-align:right' colspan='8'>".$link."</td></tr></table>";
    
    $str .= "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 1200px;' border='1' cellpadding='0' cellspacing='0'>";
    $str .= "<thead>";
    $str .= "<tr class='first'>";
    $str .= "<td rowspan='2' width='8%'><b>".$val."</b></td>";
    $str .= "<td colspan='2'><b>Операция</b></td>";
    $str .= "<td rowspan='2' width='15%'><b>Оборудование</b></td>";
    $str .= "<td rowspan='2' width='5%'><b>Дата<br>/<br>смена</b></td>";        
    $str .= "<td rowspan='2' width='5%'><b>План,<br>шт.</b></td>";
    $str .= "<td rowspan='2' width='5%'><b>Факт,<br>шт.</b></td>";
    $str .= "<td rowspan='2' width='5%'><b>Остаток,<br>шт.</b></td>";
    $str .= "</tr>\n";
    $str .= "<tr class='first'>\n";
    $str .= "<td width='5%'><b>№</b></td>";
    $str .= "<td  width='15%'><b>Наименование</b></td>";
    $str .= "</thead>";
    $str .= "</tr>\n";
        
   return $str ;
 } 
// Печать футера таблицы
 function PrintTableFoot()
 {
     $str = '</table>';
     return $str ;
 }
 
 // Печать таблицы с сортировкой по чертежу-наименованию ДСЕ
 function PrintSortedByObozDSETable( $arr , $pdate1, $pdate2, $ops )
{

     $str = '';
    $line_num = 1 ;
    $prev_oboz = 0;
    $first_pass = 0 ;
    $total = 0 ;
    
    foreach( $arr AS $item )
    {
        $name = $item['zakdet_name'];
        $oboz = $item['zakdet_oboz'];
        $zak_name = $item['zak_name'];
//        $zak_name .= ' - '.$item['zak_id'];        
        $zakdet_name = $item['zakdet_name'];
        $zakdet_oboz = $item['zakdet_oboz'];
        $mtk_count = count( $item['mtk'] );
        
        if( $zakdet_oboz == 'total')
            $total = 1 ;
            else
                $total = 0 ;
        
            
        if( ( ( $prev_oboz != $zakdet_oboz ) || ! $first_pass ) && ! $total && $mtk_count )
        {
            $head = ( $line_num ++ ).". ".$zakdet_name." ".$zakdet_oboz;
            
//            $head .= ' zakdet_id :'.$item['zakdet_id'].' zak_id :'.$item['zak_id'] ;
            
            $str .= "<tr class='first'><td class='Field AC' colspan='8'><b>".$head."<b></td></tr>";
            $prev_oboz = $zakdet_oboz ;
        }

        if( !$first_pass )
              $first_pass = 1 ;
        
        $subitem = $item['mtk'];
        
        foreach( $subitem AS $op )
        {
        $class = '';
        
        $ord = $op['ord'];
        $oper_name = $op['oper_name'];
        $park_name = $op['park_name'];
        $park_mark = $op['park_mark'];
        $date = $op['date'];
        $smen = $op['smen'];
        $raw_date = $op['raw_date'];
        $oper_id = $op['oper_id'];
        $operitems_id = $op['operitems_id'];
        
        $plan = $item['zakdet_plan'];
        $fact = $op['fact'];
        $brak = $op['brak'];        
        
        if( !strlen( $fact ) || $fact == '0' )
        {
            $fact = 0 ; 
            $link = 0 ;
        }
            else
                {
                    if( $total )
                        $link = $fact ;
                            else
                                $link = "<a href='/index.php?do=show&formid=23&p0=".$operitems_id."' target='_blank'><b>".$fact."</b></a>";
                }
        
        if( !$fact )
            $class = 'empty';

        if( ( $raw_date >= $pdate1 ) && ( $raw_date <= $pdate2 ) && in_array( $oper_id, $ops ) && $fact )
            $class = 'selected';
        
        if( $total )
            $class = 'total';

        if( $brak )
            $class = 'brak';
        
        $str .= "<tr class='".$class."'>";
        $str .= "<td class='Field AC' style='vertical-align:middle'><b>".$zak_name."</b></td>";
        $str .= "<td class='Field AC'><b>".$ord."</b></td>";
        $str .= "<td class='Field AC'>".$oper_name."</td>";        
        
        $str .= "<td class='Field AC'><u>".$park_name."</u><br>".$park_mark."</td>";
        $str .= "<td class='Field AC'><u>".$date."</u><br>".$smen."</td>";
        $str .= "<td class='Field AC'><b>".$plan."</b></td>";
        $str .= "<td class='Field AC'><b>".$link."</b></td>";
        $str .= "<td class='Field AC'><b>".($plan - $fact)."</b></td>";        
        $str .= "</tr>";
        
        $name = '';
        $oboz = '';
        $zak_name = '';
        }
    } 
    
    return $str ;
}

// Печать таблицы с сортировкой по номеру заказа-наименованию ДСЕ
 function PrintSortedByZakDSETable( $arr, $pdate1, $pdate2, $ops )
{

    $str = '';
    $line_num = 1 ;
    $prev_zak = 0;

    foreach( $arr AS $item )
    {
        $zak_name = $item['zak_name'];
        $zak_short_name = $item['zak_short_name'];
        $zak_dse_name = $item['zak_dse_name'] ;
        $zakdet_name = $item['zakdet_name'] ;
        $zakdet_oboz = $item['zakdet_oboz'] ;
       
        if( $prev_zak != $zak_short_name )
        {
            $head = ( $line_num ++ ).". Заказ ".$zak_name." ".$zak_dse_name;
            $str .= "<tr class='first'><td class='Field AC' colspan='8'><b>".$head."<b></td></tr>";
            $prev_zak = $zak_short_name ;
        }
       
        $subitem = $item['mtk'];
        
        $name = $zakdet_name ;
        $oboz = $zakdet_oboz ;
        $first_pass = 0 ;
        
        $row_class = 'dse_head';
        
        foreach( $subitem AS $op )
        {
            if( !$first_pass ++ )
                $class = 'dse_head';
                else
                    $class = '';
                
        $ord = $op['ord'];
        $oper_name = $op['oper_name'];
        $park_name = $op['park_name'];
        $park_mark = $op['park_mark'];
        $date = $op['date'];
        $smen = $op['smen'];
        $raw_date = $op['raw_date'];
        $oper_id = $op['oper_id'];
        $operitems_id = $op['operitems_id'];
        
        $plan = $item['zakdet_plan'];
        $fact = $op['fact'];
        
        if( !strlen( $fact ) || $fact == '0' )
        {
            $fact = 0 ; 
            $link = 0 ;
        }
            else
                {
                    if( $total )
                        $link = $fact ;
                            else
                                $link = "<a href='/index.php?do=show&formid=23&p0=".$operitems_id."' target='_blank'><b>".$fact."</b></a>";
                }
        
        if( !$fact )
            $class = 'empty';

        if( ( $raw_date >= $pdate1 ) && ( $raw_date <= $pdate2 ) && in_array( $oper_id, $ops ) && $fact )
            $class = 'selected';
        
        if( $total )
            $class = 'total';
            
        $str .= "<tr class='".$class." ".$row_class."'>";
        $str .= "<td class='Field AC'><u>".$name."</u><br><b>".$oboz."</b></td>";
        $str .= "<td class='Field AC'><b>".$ord."</b></td>";
        $str .= "<td class='Field AC'>".$oper_name."</td>";        
        
        $str .= "<td class='Field AC'><u>".$park_name."</u><br>".$park_mark."</td>";
        $str .= "<td class='Field AC'><u>".$date."</u><br>".$smen."</td>";
        $str .= "<td class='Field AC'><b>".$plan."</b></td>";
        $str .= "<td class='Field AC'><b>".$link."</b></td>";
        $str .= "<td class='Field AC'><b>".($plan - $fact)."</b></td>";        
        $str .= "</tr>";
        
        $name = '';
        $oboz = '';
        $zak_name = '';
        $class = '';
        $row_class = '';
        }
    } 
    
    return $str ;
}

        
if ( $step == 1 ) 
{
// Получение списка операций

	$result = dbquery("SELECT ID, NAME FROM ".$db_prefix."db_oper where 1 order by NAME");    

	echo "</form>\n";
	echo "<form action='".$pageurl."' method='get'>\n";

	echo "<input type='hidden' name='do' value='".$_GET["do"]."'>";
	echo "<input type='hidden' name='formid' value='".$_GET["formid"]."'>";

        echo "<h2>Операции по ДСЕ за период</h2>";
        
	echo "<table class='rdtbl tbl' style='border-collapse: collapse; border: 0px solid black; text-align: left; color: #000; width: 700px;' border='1' cellpadding='0' cellspacing='0'>\n";
	echo "<tr class='first'>\n";
	echo "<td width='250'>...</td>";
	echo "<td>...</td>";
	echo "</tr>\n";

	echo "<tr><td class='Field first'><b>С даты:</b></td><td class='rwField ntabg'>";
	Input("date","p0",TodayDate());
	echo "</td></tr>\n";

	echo "<tr><td class='Field first'><b>По дату:</b></td><td class='rwField ntabg'>";
	Input("date","p1",TodayDate());
	echo "</td></tr>\n";

	echo "<tr><td class='Field first'><b>Операция:</b></td><td class='Field'>";

        echo '<select size="10" multiple name="p2[]" onchange="op_select()">';
        echo '<option disabled selected>Выберите операцию</option>';
        
        while ( $op = mysql_fetch_array( $result ) )
                {
                    echo '<option value="'.$op['ID'].'">'.$op['NAME'].'</option>';
                }
        
        echo '</select>';
        echo "</td></tr>\n";
        
	echo "</table>\n";
	echo "<br><table style='width: 700px;'><tr><td style='text-align: right;'><input disabled id='calc' type='submit' value='&nbsp;&nbsp;&nbsp;Отчёт&nbsp;&nbsp;&nbsp;'></td></tr></table>";
}        

function sortByNameObozZak( $a, $b ) 
{
    if ( $a['zakdet_name'] == $b['zakdet_name'] ) 
    {
        if ( $a['zakdet_oboz'] == $b['zakdet_oboz'] )
            if ( $a['zak_short_name'] == $b['zak_short_name'] )
                return 0;
            return ($a['zak_short_name'] < $b['zak_short_name']) ? -1 : 1 ;
        return ($a['zakdet_oboz'] < $b['zakdet_oboz']) ? -1 : 1 ;
    }

 return ( $a['zakdet_name'] < $b['zakdet_name'] ) ? -1 : 1 ;
}




function sortByZakName( $a, $b ) 
{
    if ( $a['zak_short_name'] == $b['zak_short_name'] ) 
    {
        if ( $a['zakdet_name'] == $b['zakdet_name'] )
           return 0;
        return ($a['zakdet_name'] < $b['zakdet_name']) ? -1 : 1 ;
    }

 return ($a['zak_short_name'] < $b['zak_short_name']) ? -1 : 1 ;
}

// Общие обработки для обоих типов сортировки
if ( $step == 2 ) 
{
       echo "<div>";
       echo "<h3>Операции по ДСЕ за период <span style='text-decoration:underline;'>".$date1."</span> по <span style='text-decoration:underline;'>".$date2."</span>
             </h3>";          

// Вывод заголовка отчета       
       if( count( $ops ) > 1 )
            echo "<h5>Операции : ";
                else 
                    echo "<h5>Операция : ";

       echo get_operations( $ops )."</h5>";

// Установить чекбоксы в соответствии с типом сортировки       
       if( $sort == 1 )
        {
          $cheked1 = 'checked';
          $cheked2 = '';
       }
         else 
                {
                    $cheked2 = 'checked';
                    $cheked1 = '';
                }
       
       echo "</div>";
       echo "<div style='float:left; padding:15px 30px 0 20px'>";       
/*	   
       echo "<table class='rdtbl tbl'>";
       echo "<tr class='first AC'><td class='first AC'><b>Легенда</b></td></tr>";
       echo "<tr><td class='Field AC'>Выполненные операции</td></td></tr>";
       echo "<tr class='empty'><td class='Field AC'>Невыполненные операции</td></tr>";
       echo "<tr class='selected'><td class='Field AC'>Заданные операции попавшие в период</td></tr>";
       echo "<tr class='brak'><td class='Field AC' >Восстановление брака</td></tr>";       
	   echo "<tr class='total' style='vertical-aligment:middle !IMPORTANT;'><td class='Field AC' >Итого</td></tr>";       	   
       echo "</table><br>";       
*/

       echo "<table><tbody>";
       echo "<tr><td style='background:#FFF;width:20px;border:solid 1px black'></td><td>&nbsp;- Выполненные операции</td></tr>";
       echo "<tr><td style='background:#BBB;width:20px;border:solid 1px black'></td><td>&nbsp;- Невыполненные операции</td></tr>";
       echo "<tr><td style='background:#FFC0CB;width:20px;border:solid 1px black'></td><td>&nbsp;- Выбранные операции попавшие в задданый период</td></tr>";
       echo "<tr><td style='background:#FF7F50;width:20px;border:solid 1px black'></td><td>&nbsp;- Восстановление брака</td></tr>";
       echo "<tr><td style='background:yellow;width:20px;border:solid 1px black'></td><td>&nbsp;- Итого</td></tr>";
       echo "</tbody></table>";
       
	   echo "</div>";
       
       echo "<div style='padding-top:5px; float:left'>";
       echo    '<form>
                <p><b>Сортировка</b></p>
                <p><input type="radio" id="oboz_id" '.$cheked1.' onclick="check_radio(id)"> По наименованию ДСЕ</p>
                <p><input type="radio" id="zak_id" '.$cheked2.' onclick="check_radio(id)"> По номеру заказа</p>
                <br><input id="calc" type="button" value="Выбрать обработку и диапазон" onclick="select_job()">
                </form> ';
       echo "</div>";
       
       $dse_arr = GetDSE( $pdate1, $pdate2, $ops );
       $list_count = count( $dse_arr );
}


// Сортировка по первому
if ( $step == 2 && $sort == 1 ) 
{

       if ( $list_count )
       {
//            echo "<h3>До сортировки: ".( count ( $dse_arr ) )."</h3>";                    
            usort( $dse_arr , 'sortByNameObozZak');
//            echo "<h3>После сортировки: ".( count ( $dse_arr ) )."</h3>";                                
           $dse_arr = GroupDSE( $dse_arr );
		   ClearDoubleItems( $dse_arr );
           echo PrintTableHead( 'Заказ' , 1, $date1, $date2, $ops );
           echo PrintSortedByObozDSETable( $dse_arr, $pdate1, $pdate2, $ops );
           echo PrintTableFoot();           
       }
       else
            echo "<h3>Записей не найдено</h3>";         
       
}

if ( $step == 2 && $sort == 2 ) 
{
    
       if ( $list_count )
       {
           usort( $dse_arr , 'sortByZakName');
           echo PrintTableHead( 'ДСЕ' , 2 , $date1, $date2, $ops );
           echo PrintSortedByZakDSETable( $dse_arr, $pdate1, $pdate2, $ops );
           echo PrintTableFoot();           
       }
       else
            echo "<h3>Записей не найдено</h3>";         
   
}

?>
