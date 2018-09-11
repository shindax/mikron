<?php

echo '<style type="text/css">
@import url(/project/phonebook/styles.css);
</style><h2>Справочник телефонов</h2><br/>
	<table class="rdtbl tbl report_table">
		<thead>
		<tr class="first">
			<td class="nbg"></td>
			<td>Отделы и ресурсы</td>
			<td>№ Трубки</td>
			<td>Телефон</td>
			<td>E-Mail</td>
			<td>Должность</td>
		</tr>
		</thead>
		<tbody>';
		
	$result = dbquery("SELECT `ID`, `NAME` FROM `okb_db_otdel`
							ORDER BY `PID`");

	while ($row = mysql_fetch_assoc($result)) {
		
		$result_shtat = dbquery("SELECT `okb_db_shtat`.`MORE`, `okb_db_special`.`NAME` as `ResourceSpecialName`,`ID_otdel`, `okb_db_shtat`.`ID_special`, `okb_db_shtat`.`ID_speclvl`, `ID_resurs`, `BOSS`, `okb_db_resurs`.`NAME` as `ResourceName`,  `okb_db_resurs`.`FF`,  `okb_db_resurs`.`II`, `okb_db_resurs`.`EMAIL`,  `okb_db_resurs`.`OO`,`okb_db_resurs`.`TEL` as `ResourcePhone` FROM `okb_db_shtat`
									LEFT JOIN `okb_db_resurs` ON `okb_db_resurs`.`ID` = `okb_db_shtat`.`ID_resurs`
									LEFT JOIN `okb_db_special` ON `okb_db_special`.`ID` = `okb_db_shtat`.`ID_special`
									WHERE `ID_otdel` = " . $row['ID'] . " AND `okb_db_shtat`.`MORE` LIKE '%тел.%'
									ORDER BY `okb_db_shtat`.`BOSS` DESC");
									
		if (mysql_num_rows($result_shtat) > 0) {
	
			echo '<tr>'
			,'<td class="nbg"></td>'
			,'<td  colspan="5"><img src="/project/img/group.png"/>'
			,'<div class="title">' . htmlspecialchars($row['NAME']) . '</div></td>'
			,'</tr>';

			while ($row_shtat = mysql_fetch_assoc($result_shtat)) {
				preg_match('#тел. (.*)#', $row_shtat['MORE'], $out);
				
				if (isset($out[1]) && !empty($row_shtat['FF'])) {
					preg_match('#(^((8|\+7|Мобильный: \+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10})#', $row_shtat['ResourcePhone'], $out_phone);
					
					$emails = explode(';', str_replace(array(',', ';', ' '), ';', $row_shtat['EMAIL']));
					
					$emails_text = '';
					
					foreach ($emails as $email) {
						if (strpos($email, 'okbmikron.ru') == true) {
							$emails_text .= $email . '<br/>';
						}
					} 
					
					echo '<tr>'
					,'<td class="nbg"></td>'
					,'<td class="first_level"><a href="/index.php?do=show&formid=47&id=' . $row_shtat['ID_resurs'] . '"><img src="/project/img/resurs.png"/></a></div>'
					,'<div class="title">'. htmlspecialchars($row_shtat['FF'] . ' ' . $row_shtat['II'] . ' ' . $row_shtat['OO']) . '</div></td>'
					,'<td>' . $out[1] . '</td>'
					,'<td>' . str_replace('Мобильный: ', '', $out_phone[0]) .  '</td>'
					,'<td>' . $emails_text .  '</td>'
					,'<td>' . $row_shtat['ResourceSpecialName'] .  '</td>'
					,'</tr>';
				}
					
			}
		}
	}

?>

	</tbody>
</table>
