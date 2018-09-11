<?php

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
$ID_krz2 = $_GET["id"];
$show = false;

	$item = dbquery("SELECT * FROM ".$db_prefix."db_krz2 where  (ID='".$ID_krz2."')");
	if ($item = mysql_fetch_array($item)) {
		$show = true;
		$xxx = dbquery("SELECT * FROM ".$db_prefix."db_krz2det where (ID_krz2 = '".$ID_krz2."') and (PID = '0')");
		$dse = mysql_fetch_array($xxx);
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////


echo "
<style>
	b {font-size: 12pt;}
	td.Field {padding-top: 5px; padding-bottom: 5px;}
	tr.first td.Field {padding: 3px;}
</style>
<br>
<H2 style='text-align: center; padding: 0px; margin: 0px;'>Лист согласования ".FVal($item,"db_krz2","NAME")."</H2><br><center>\"___\" __________ 20__ г</center><br><br>
<table style='border: 0px; width: 100%;'>
<tr>
<td width='350px;'></td>
<td style='font-size: 12pt; text-align: left;'>
Заказчик: <b>".FVal($item,"db_krz2","ID_clients")."</b><br>
Наименование изделия: <b>".FVal($dse,"db_krz2det","NAME")."</b><br>
Количество: <b>".FVal($dse,"db_krz2det","COUNT")." шт</b><br>
№ КРЗ: <b>".FVal($item,"db_krz2","NAME")."</b> &nbsp; &nbsp; &nbsp; 
№ Чертежа: <b>".FVal($dse,"db_krz2det","OBOZ")."</b><br>
Примечание: ".FVal($item,"db_krz2","MORE")."<br>".FVal($item,"db_krz2","MORE_EXPERT")."
</td>
</tr>
</table>
<br>

";

echo "<table class='tbl' border='0' cellpadding='0' cellspacing='0' width='100%'>";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr class='first'>\n";
	echo "<td class='Field' width='15%' rowspan='2'>Должность</td>\n";
	echo "<td class='Field' width='14%' rowspan='2'>Ф.И.О.</td>\n";
	echo "<td class='Field' width='13%' rowspan='2'>Подпись</td>\n";
	echo "<td class='Field' colspan='2' rowspan='2'>Даты работы службы<br>над исполнением заказа</td>\n";
	echo "<td class='Field' colspan='2'>Согласование</td>\n";
	echo "<td class='Field' width='11%' rowspan='2'>Примечание</td>\n";
	echo "</tr>\n";

	echo "<tr class='first'>\n";
	echo "<td class='Field' width='12%'>Дата</td>\n";
	echo "<td class='Field' width='11%'>Время</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field'>Директор</td>\n";
	echo "<td class='Field'>Рудых М.Г.</td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field' colspan='2'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field'>Технический<br>директор</td>\n";
	echo "<td class='Field'>Салов Д.А.</td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field' colspan='2'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field' rowspan='4'>Заместитель<br>коммерческого<br>директора</td>\n";
	echo "<td class='Field' rowspan='4'>Куимова О.В.</td>\n";
	echo "<td class='Field' rowspan='4'></td>\n";
	echo "<td class='Field' class='mini' width='12%'>Плановая дата<br>открытия зак.</td>\n";
	echo "<td class='Field' class='mini' width='12%'></td>\n";
	echo "<td class='Field' rowspan='4'></td>\n";
	echo "<td class='Field' rowspan='4'></td>\n";
	echo "<td class='Field' rowspan='4'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field' class='mini'>Предоплата</td>\n";
	echo "<td class='Field' class='mini'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field' class='mini'>Оконч. расчёт</td>\n";
	echo "<td class='Field' class='mini'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field' class='mini'>Поставка</td>\n";
	echo "<td class='Field' class='mini'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field'>Начальник<br>производства</td>\n";
	echo "<td class='Field'>Филоненко С.А.</td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field' class='mini'></td>\n";
	echo "<td class='Field' class='mini'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field' rowspan='2'>Начальник ПДО</td>\n";
	echo "<td class='Field' rowspan='2'>Рыбкина Т.Д.</td>\n";
	echo "<td class='Field' rowspan='2'></td>\n";
	echo "<td class='Field' class='mini'>Начало</td>\n";
	echo "<td class='Field' class='mini'></td>\n";
	echo "<td class='Field' rowspan='2'></td>\n";
	echo "<td class='Field' rowspan='2'></td>\n";
	echo "<td class='Field' rowspan='2'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field' class='mini'>Окончание</td>\n";
	echo "<td class='Field' class='mini'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field' rowspan='2'>Начальник ОВК</td>\n";
	echo "<td class='Field' rowspan='2'>Казаченко А.Л.</td>\n";
	echo "<td class='Field' rowspan='2'></td>\n";
	echo "<td class='Field' class='mini'>Проработка</td>\n";
	echo "<td class='Field' class='mini'></td>\n";
	echo "<td class='Field' rowspan='2'></td>\n";
	echo "<td class='Field' rowspan='2'></td>\n";
	echo "<td class='Field' rowspan='2'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field' class='mini'>Поставка</td>\n";
	echo "<td class='Field' class='mini'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field' rowspan='2'>Начальник ОМТС</td>\n";
	echo "<td class='Field' rowspan='2'>Кумановская А.А.</td>\n";
	echo "<td class='Field' rowspan='2'></td>\n";
	echo "<td class='Field' class='mini'>Проработка</td>\n";
	echo "<td class='Field' class='mini'></td>\n";
	echo "<td class='Field' rowspan='2'></td>\n";
	echo "<td class='Field' rowspan='2'></td>\n";
	echo "<td class='Field' rowspan='2'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field' class='mini'>Поставка</td>\n";
	echo "<td class='Field' class='mini'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field' rowspan='3'>Зам. ТД по подготовке производства</td>\n";
	echo "<td class='Field' rowspan='3'>Бормотов В.А.</td>\n";
	echo "<td class='Field' rowspan='3'></td>\n";
	echo "<td class='Field' class='mini'>КД</td>\n";
	echo "<td class='Field' class='mini'></td>\n";
	echo "<td class='Field' rowspan='3'></td>\n";
	echo "<td class='Field' rowspan='3'></td>\n";
	echo "<td class='Field' rowspan='3'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field' class='mini'>НР</td>\n";
	echo "<td class='Field' class='mini'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field' class='mini'>МТК</td>\n";
	echo "<td class='Field' class='mini'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field'>Инициатор</td>\n";
	echo "<td class='Field'>".FVal($item,"db_krz2","ID_users")."</td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field' colspan='2'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "</tr>\n";


//////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "</table>";

echo "<br><center><b>Дополнительные требования</b></center><br>";

echo "<table class='tbl' border='0' cellpadding='0' cellspacing='0' width='100%'>";

	echo "<tr class='first'>\n";
	echo "<td class='Field' width='200'>Наименование</td>\n";
	echo "<td class='Field' width='120'>Необходимость</td>\n";
	echo "<td class='Field'>Характеристики</td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field'>Сертификат качества</td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field'>Сертификат соответствия</td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field'>Грунтовка</td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field'>Окраска</td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field'>Упаковка</td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'><br></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'><br></td>\n";
	echo "</tr>\n";

	echo "<tr>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'></td>\n";
	echo "<td class='Field'><br></td>\n";
	echo "</tr>\n";



echo "</table>";

?>