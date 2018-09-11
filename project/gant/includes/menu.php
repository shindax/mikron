<?php

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  MENU          /////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "<div class=\"hdn\">";


		echo "<form method='get' style='margin: 2px 20px 2px 30px; padding: 0px; display: block; float: left;'>";
		echo "<SELECT NAME='mm'>";
		for ($j=1;$j < 13;$j++) {
			echo "<OPTION VALUE='".$j."' ";
			if ($j==$MM*1) echo "SELECTED";
			echo ">".$DI_MName[$j];
		}
		echo "</SELECT>";
		echo "<SELECT NAME='yy'>";
		for ($j=$YY-2;$j < $YY+3;$j++) {
			echo "<OPTION VALUE='".$j."' ";
			if ($j==$YY*1) echo "SELECTED";
			echo ">".$j;
		}
		echo "</SELECT>";
		echo "<input type='submit' value='ОК'>";
		echo "</form>";
		echo "<div id=\"keys\">";
		echo "<a id='markerkeyon' href=\"javascript:void(0);\" onClick=\"DoUseMarker();\" class='MARKERKEYON'></a>";
		echo "<a id='markerkeyoff' href=\"javascript:void(0);\" onClick=\"DoNotUseMarker();\" class='MARKERKEYOFF'></a>";
		echo "</div>";

		//echo "<a href=\"javascript:void(0);\" onClick=\"all_tree_open();\" title='Открыть всё'><img style='margin-left: 10px;' src='img/collapse.bmp'></a>";
		echo "<a href=\"javascript:void(0);\" onClick=\"all_tree_close();\" title='Закрыть всё'><img style='margin-left: 15px;' src='img/expand.bmp'></a>";

echo "</div>";
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>

