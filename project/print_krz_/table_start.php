<?php
echo "
	<tr class='top'>
		<td colspan='3'>Коммерческий расчёт заказа  №</td>
		<td colspan='3'>".$zaknum."</td>
		<td>Дата  запуска</td>
		<td>".FVal($krz,"db_krz","DATE_START")."</td>
	</tr>
	<tr>
		<td colspan='3' class='first'>Инициатор</td>
		<td colspan='5'>".FVal($krz,"db_krz","ID_users")."</td>
	</tr>
	<tr>
		<td colspan='3' class='first'>Контрагент</td>
		<td colspan='5'>".FVal($krz,"db_krz","ID_clients")."</td>
	</tr>
	<tr>
		<td colspan='3' class='first' style='background : #ddd;'>Наименование изделия</td>
		<td colspan='4' style='background : #ddd;'>№ чертежа изделия</td>
		<td style='background : #ddd;'>Количество</td>
	</tr>
	<tr>
		<td colspan='3'>".FVal($det,"db_krzdet","NAME")."</td>
		<td colspan='4'>".FVal($det,"db_krzdet","OBOZ")."</td>
		<td><i>";
IVal("count", $count);
	echo "</i></td>
	</tr>
	<tr>
		<td colspan='3' class='first'>Поставщик заготовки</td>
		<td colspan='5'>".FVal($krz,"db_krz","ID_postavshik")."</td>
	</tr>
	<tr>
		<td colspan='3' class='first'>Перспектива серийности</td>
		<td colspan='5'>".FVal($krz,"db_krz","SERIYA")."</td>
	</tr>
	<tr>
		<td colspan='3' class='first'>Необходимые сроки поставки</td>
		<td colspan='5'>".FVal($krz,"db_krz","DATE_PLAN")."</td>
	</tr>
	<tr>
		<td colspan='3' class='first'>Прилагаемые доп. документы</td>
		<td colspan='5'>".FVal($krz,"db_krz","DOCS")."</td>
	</tr>
	<tr>
		<td colspan='3' class='first'>Цена Н/Ч по заказу, руб</td>
		<td colspan='5'>";
RVal("price", $price);
	echo "</td>
	</tr>
	<tr>
		<td colspan='3' class='first'>Эксперт</td>
		<td colspan='5'>".FVal($krz,"db_krz","EXPERT")."</td>
	</tr>
	<tr>
		<td colspan='3' class='first'>Примечание эксперта</td>
		<td colspan='5'>".FVal($krz,"db_krz","MORE_EXPERT")."</td>
	</tr>
	<tr>
		<td colspan='3' class='first'>Примечание</td>
		<td colspan='5'>".FVal($krz,"db_krz","MORE")."</td>
	</tr>
	<tr class='center'>
		<td>№</td>
		<td>Показатель</td>
		<td>Цена ед.<br>изм без НДС, руб</td>
		<td>Ед. изм.</td>
		<td>На ед.</td>
		<td>Всего</td>
		<td>Руб без НДС на ед.</td>
		<td>Руб без НДС ИТОГО</td>
	</tr>
";
?>