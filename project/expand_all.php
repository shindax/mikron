<?php

	setcookie( 'O_show43', '', 1 );

	$O_show43 = '';
	$result = dbquery("SELECT ID FROM okb_db_otdel WHERE 1");
		while ( $row = mysql_fetch_assoc($result) ) 
			$O_show43 .= "|||db_otdel_43_".$row['ID'];

	setcookie( 'O_show43', $O_show43, time()+86400 );