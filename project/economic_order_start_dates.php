<style>
.stage_begin_table
{
	width : 100%;
}
input
{
	width : 80px;
}


</style>

<script>
var monthNames = ['\u042F\u043D\u0432\u0430\u0440\u044C','\u0424\u0435\u0432\u0440\u0430\u043B\u044C','\u041C\u0430\u0440\u0442','\u0410\u043F\u0440\u0435\u043B\u044C','\u041C\u0430\u0439','\u0418\u044E\u043D\u044C',
        '\u0418\u044E\u043B\u044C','\u0410\u0432\u0433\u0443\u0441\u0442','\u0421\u0435\u043D\u0442\u044F\u0431\u0440\u044C','\u041E\u043A\u0442\u044F\u0431\u0440\u044C','\u041D\u043E\u044F\u0431\u0440\u044C','\u0414\u0435\u043A\u0430\u0431\u0440\u044C'];
var monthNamesShort = ['\u042F\u043D\u0432','\u0424\u0435\u0432','\u041C\u0430\u0440','\u0410\u043F\u0440','\u041C\u0430\u0439','\u0418\u044E\u043D',
        '\u0418\u044E\u043B','\u0410\u0432\u0433','\u0421\u0435\u043D','\u041E\u043A\u0442','\u041D\u043E\u044F','\u0414\u0435\u043A'];
var dayNames = ['\u0432\u043E\u0441\u043A\u0440\u0435\u0441\u0435\u043D\u044C\u0435','\u043F\u043E\u043D\u0435\u0434\u0435\u043B\u044C\u043D\u0438\u043A','\u0432\u0442\u043E\u0440\u043D\u0438\u043A','\u0441\u0440\u0435\u0434\u0430','\u0447\u0435\u0442\u0432\u0435\u0440\u0433','\u043F\u044F\u0442\u043D\u0438\u0446\u0430','\u0441\u0443\u0431\u0431\u043E\u0442\u0430'];
var dayNamesShort = ['\u0432\u0441\u043A','\u043F\u043D\u0434','\u0432\u0442\u0440','\u0441\u0440\u0434','\u0447\u0442\u0432','\u043F\u0442\u043D','\u0441\u0431\u0442'];
var dayNamesMin = ['\u0412\u0441','\u041F\u043D','\u0412\u0442','\u0421\u0440','\u0427\u0442','\u041F\u0442','\u0421\u0431'];


function adjust_calendars( selector )
{
    $( selector ).datepicker(
    {
        closeText: '\u041F\u0440\u0438\u043D\u044F\u0442\u044C', // Принять
        prevText: '&#x3c;\u041F\u0440\u0435\u0434', //
        nextText: '\u0421\u043B\u0435\u0434&#x3e;',
        currentText: '\u0422\u0435\u043A. \u043C\u0435\u0441\u044F\u0446',// тек. месяц
        showButtonPanel: false,
        monthNames: monthNames,
        monthNamesShort : monthNamesShort,
        dayNames : dayNames,
        dayNamesShort : dayNamesShort,
        dayNamesMin : dayNamesMin,
        dateFormat: 'dd.mm.yy',
        firstDay: 1,
        changeMonth : true,
        changeYear : true,
        isRTL: false,
                    onSelect: function ()
                      { 
                      	var id = $( this ).parent().parent().data('id');
                      	var field = $( this ).data('field');
                      	var date = $( this ).datepicker( "getDate" );
                      	$( this ).prop('disabled', true);
                        getFilteredData( id, field, date ); 
                      }

    });
}

</script>

<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
global $user, $pdo;

$user_id = $user["ID"];

echo "<script>var user_id = $user_id ;</script>";

$zak_id = $_GET['id'];

	try
	{
	    $query ="SELECT TID FROM `okb_db_zak` WHERE ID=$zak_id
				" ;
	    $stmt = $pdo->prepare( $query );
	    $stmt -> execute();
	}
	catch (PDOException $e)
	{
	   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
	}
	$row = $stmt->fetch( PDO::FETCH_OBJ );
	$tid = $row -> TID ;
	if( $tid == 1 )
		return ;

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

function getRow( $row, $field )
{
	global $user_id ;

	$pd = $row -> $field ;
	$pd = explode('#', $pd );
	$pd = explode('|', $pd[0] );
	$pd = $pd[1];
	$pd = explode(' ', $pd );
	$pd = $pd[0];
	
	$class = '';
	$disabled = 'disabled';

	if( strlen($pd) == 0 )
		$class = 'datepicker';

	if(  ( $user_id == 39 || $user_id == 145 || $user_id == 1 || $user_id == 216 || $user_id == 206 ) && $class == 'datepicker')
    		$disabled = '';

	return  "<td class='Field'><input class='$class' value='$pd' data-field='$field' $disabled/></td>";	
}

$str = "<h2>".conv("Даты начала этапов")."</h2>";
$str .= "<table class='rdtbl tbl stage_begin_table'>
		<col width='10%'>
		<col width='7%'>
		<col width='7%'>		
		<col width='7%'>

		<col width='7%'>
		<col width='7%'>
		<col width='7%'>		
		<col width='7%'>

		<col width='7%'>
		<col width='7%'>
		<col width='7%'>		
		<col width='7%'>

		<col width='7%'>		
		<col width='7%'>

			<tr class='first'>
            <td class='Field' rowspan='2'>".conv("Заказ")."</td>
            <td class='Field' colspan='3'>".conv("Подготовка производства")."</td>
            <td class='Field' colspan='2'>".conv("Комплектация")."</td>
            <td class='Field' colspan='2'>".conv("Кооперация")."</td>
            <td class='Field' colspan='3'>".conv("Производство")."</td>
            <td class='Field' colspan='3'>".conv("Коммерция")."</td>
            </tr>";

        $str .= "<tr class='first'>
            <td class='Field'>".conv("КД")."</td><td class='Field'>".conv("Нормы<br>расхода")."</td><td class='Field'>".conv("МТК")."</td>
            <td class='Field'>".conv("Проработка")."</td><td class='Field'>".conv("Поставка")."</td>
            <td class='Field'>".conv("Проработка")."</td><td class='Field'>".conv("Поставка")."</td>
            <td class='Field'>".conv("Дата<br>нач.")."</td><td class='Field'>".conv("Дата<br>оконч.")."</td><td class='Field'>".conv("Инструмент и<br>оснастка")."</td>

            <td class='Field'>".conv("Предоплата")."</td><td class='Field'>".conv("Оконч.<br>расчет")."</td><td class='Field'>".conv("Поставка")."</td></tr>";

	try
	{
	    $query ="
	    		SELECT 
	    			zak.ID id, 
	    			zak.NAME name, 
	    			zak.PD1 pd1, 
	    			zak.PD2 pd2, 
	    			zak.PD3 pd3, 

	    			zak.PD4 pd4, 
	    			zak.PD7 pd7, 

	    			zak.pd_coop1 pd_coop1, 
	    			zak.pd_coop2 pd_coop1, 

	    			zak.PD12 pd12, 
	    			zak.PD8 pd8, 
	    			zak.PD13 pd13, 
	    			zak.PD9 pd9, 

	    			zak.PD10 pd10, 
	    			zak.PD11 pd11, 
	    			zt.description zak_type

	    		FROM `okb_db_zak` zak
	    		LEFT JOIN `okb_db_zak_type` zt ON zt.id = zak.TID 
	    		WHERE zak.id=$zak_id OR zak.PID = $zak_id
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
		$row_id = $row -> id;
		$zak_name = conv( $row -> zak_type ." ". $row -> name );

		$str .=  "<tr data-id = '$row_id'>";
		$str .=  "<td class='Field'>$zak_name</td>";

		$str .=  getRow( $row, 'pd1' );
		$str .=  getRow( $row, 'pd2' );	
		$str .=  getRow( $row, 'pd3' );


		$str .=  getRow( $row, 'pd4' );	
		$str .=  getRow( $row, 'pd7' );

		$str .=  getRow( $row, 'pd_coop1' );	
		$str .=  getRow( $row, 'pd_coop2' );

		$str .=  getRow( $row, 'pd12' );	
		$str .=  getRow( $row, 'pd8' );
		$str .=  getRow( $row, 'pd13' );

		$str .=  getRow( $row, 'pd9' );	
		$str .=  getRow( $row, 'pd10' );
		$str .=  getRow( $row, 'pd11' );

		$str .=  "</tr>";

	}

$str .=  "</table>";

echo $str ;

?>
<script>
$( function()
{
	adjust_calendars( ".datepicker" )
});

function getFilteredData( id, field, date )
{
	day  = date.getDate();
    month = date.getMonth() + 1;
    year =  date.getFullYear();

    $.post(
    "project/ajax.UpdateStageBegin.php",
    {
        id   : id,
        user_id   : user_id,
        field : field,
        day : day,
        month : month,
        year : year
    },
    function( data )
    {
      //console.log( data );
    }
    );
}
</script>