<?php
$s1 = $render_row['KADR'];
if (preg_match("/\d\d\.\d\d\.\d\d\d\d/", $s1, $s2)){
	preg_match("/\d\d\.\d\d\.\d\d\d\d/", $s1, $s2);
}else{
	preg_match("/\d\d\.\d\d\.\d\d/", $s1, $s2);
}
echo $s2[0];
?>