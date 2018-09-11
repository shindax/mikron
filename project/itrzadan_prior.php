<?php
if ($render_row['PRIORZADAN']==1) $opt_sel_1 = "selected";
if ($render_row['PRIORZADAN']==2) $opt_sel_2 = "selected";
if ($render_row['PRIORZADAN']==3) $opt_sel_3 = "selected";
if ($render_row['PRIORZADAN']==4) $opt_sel_4 = "selected";
echo "<select name='itr_prior_c' onchange='chang_all_cels_color(this, this.options[this.selectedIndex].style.color); chang_prior(this, this.selectedIndex); chang_itr_prior(".$render_row['ID'].", this.selectedIndex);' style='font-size:125%; font-weight:bold;'>";
echo "<option style='font-size:100%; color:#000000; font-weight:bold;'>Обычный";
echo "<option ".$opt_sel_1." style='font-size:100%; color:#ff0000; font-weight:bold;'>приор №1";
echo "<option ".$opt_sel_2." style='font-size:100%; color:#44cf44; font-weight:bold;'>приор №2";
echo "<option ".$opt_sel_3." style='font-size:100%; color:#A7A9F5; font-weight:bold;'>приор №3";
echo "<option ".$opt_sel_4." style='font-size:100%; color:#D0CC38; font-weight:bold;'>приор №4";
echo "</select>";

echo "<script type='text/javascript'>
function chang_all_cels_color(ojb, opt_color){
	var cells_c = ojb.parentNode.parentNode.cells.length;
	for (var a_a = 0; a_a < cells_c; a_a++){
		ojb.parentNode.parentNode.cells[a_a].setAttribute('style','color:'+opt_color+'; font-size:125%; font-weight:bold;');
	}
}
function chang_prior(obj_sel, ind_opt){
	obj_sel.style.color=obj_sel.options[ind_opt].style.color;
}
function chang_itr_prior(id_itr, id_opt){
		var req = getXmlHttp();
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if(req.status == 200) {
				}
			}
		}

		req.open('GET', 'project/itrzadan_chang_prior.php?p1='+id_itr+'&p2='+id_opt, true);
		req.send(null);
}
</script>";
?>