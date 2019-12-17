<?php

		$file = file_get_contents('/media/server_swap_data/Подготовка производства/2019/19-056 Валок - ВП 913.01.00.00-01/19-056,057 УЧ Упаковка Валков/Упаковка стандартная (19-056, 19-057).jpg');
				header("Content-Description: File Transfer");
				header("Content-Type: image/jpeg");
				header("Content-Disposition: filename=Упаковка стандартная (19-056, 19-057).jpg");
				header("Content-Transfer-Encoding: binary");
				header("Expires: 0");
				header("Cache-Control: must-revalidate");
				header("Pragma: public");
				header("Content-Length: ".strlen($file));
				print $file;
				exit;
				
				
				