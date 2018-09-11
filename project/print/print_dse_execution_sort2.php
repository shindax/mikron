<style>
<!--

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
        backgroud-color:#AAA;        
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

-->
.dse_head
{
    background-color : #DDD;
}
.selected
{
    background-color : #FFC0CB; /*#DCDCDC ;*/
}

.total
{
    background-color : yellow; /*#DCDCDC ;*/
}

.empty
{
    background-color : #BBB;
}

</style>
<center>
<div id='Printed' class='a4p'>  
    
<?php
include "project/dse_execute_functions.php";

$date1 = '';
$pdate1 = '';
$date2 = '';
$pdate2 = '';
$ops = array();

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
 
 function PrintTableHead( $val )
{
   
    $str = '';

    $s = 'style = background-color:#DDD';

    $str .= "<table ID='PageTable' border='0' cellpadding='0' cellspacing='0' width='1000'>\n";
    $str .= "<tr class='first'>\n";
    $str .= "<td ".$s." class='first' rowspan='2'><b>".$val."</b></td>";
    $str .= "<td ".$s." colspan='2'><b>Операция</b></td>";
    $str .= "<td ".$s." rowspan='2'><b>Оборудование</b></td>";
    $str .= "<td ".$s." rowspan='2'><b>Дата<br>/<br>смена</b></td>";        
    $str .= "<td ".$s." rowspan='2'><b>План,<br>шт.</b></td>";
    $str .= "<td ".$s." rowspan='2'><b>Факт,<br>шт.</b></td>";
    $str .= "<td ".$s." rowspan='2'><b>Остаток,<br>шт.</b></td>";
    $str .= "</tr>\n";
    $str .= "<tr>\n";
    $str .= "<td ".$s." ><b>№</b></td>";
    $str .= "<td ".$s." ><b>Наименование</b></td>";
    $str .= "</tr>\n";
        
   return $str ;
 } 
function PrintTableFoot()
 {
     $str = '</table>';
     return $str ;
 }
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
            $str .= "<tr class='first'><td style = 'background-color:#EEE' class='Field AC' colspan='8'><b>".$head."<b></td></tr>";
            $prev_zak = $zak_short_name ;
        }
       
        $subitem = $item['mtk'];
        $name = $zakdet_name ;
        $oboz = $zakdet_oboz ;

        $s = 'style = "background-color:#EEE"';
        
        foreach( $subitem AS $op )
        {
        
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
                        $link = $fact ;
            
        $str .= "<tr class='".$class."'>";
        $str .= "<td ".$s." class='Field AC'><b><u>".$name."</u><br>".$oboz."</b></td>";
        $str .= "<td ".$s." class='Field AC'><b>".$ord."</b></td>";
        $str .= "<td ".$s." class='Field AC'>".$oper_name."</td>";        
        
        $str .= "<td ".$s." class='Field AC'><u>".$park_name."</u><br>".$park_mark."</td>";
        $str .= "<td ".$s." class='Field AC'><u>".$date."</u><br>".$smen."</td>";
        $str .= "<td ".$s." class='Field AC'><b>".$plan."</b></td>";
        $str .= "<td ".$s." class='Field AC'><b>".$link."</b></td>";
        $str .= "<td ".$s." class='Field AC'><b>".($plan - $fact)."</b></td>";        
        $str .= "</tr>";
        
        $name = '';
        $oboz = '';
        $zak_name = '';
        $s = '';
        }
    } 
    
    return $str ;
}

        
	echo "
	Отчёт от ".date("d.m.Y H:i",mktime())."<br>
        Сортировка по номеру заказа
	<H3>Выполнение ДСЕ за период с ".$date1." по ".$date2." </H3>";
        
        echo '<h3>';
        if( count( $ops ) > 1 )
            echo "Операции : ";
                else 
                    echo "Операция : ";

       echo get_operations( $ops ).'</h3>';

    $dse_arr = GetDSE( $pdate1, $pdate2, $ops );
    $list_count = count( $dse_arr );
   
       if ( $list_count )
       {
           usort( $dse_arr , 'sortByZakName');               
           echo PrintTableHead( 'ДСЕ' );
           echo PrintSortedByZakDSETable( $dse_arr, $pdate1, $pdate2, $ops );           
           echo PrintTableFoot();
       }
       else
           echo 'Записей не найдено.' ;
        

?>

</div>
</center>
