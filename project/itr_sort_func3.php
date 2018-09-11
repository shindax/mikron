<?php
echo "<script type='text/javascript'>
var sort = getUrlVars()['sort'];
var arch = getUrlVars()['arch'];
var arch_url = '';

if (arch){
	arch_url = '&arch=1';
}
if (!arch){
	arch_url = '';
}
	if (!sort) {
		document.getElementById('sort_itr_1').src = 'project/img5/c1.gif';
		document.getElementById('sort_itr_2').src = 'project/img5/0.gif';
		document.getElementById('sort_itr_3').src = 'project/img5/c1.gif';
		document.getElementById('sort_itr_4').src = 'project/img5/c1.gif';
		document.getElementById('sort_itr_5').src = 'project/img5/c1.gif';
		document.getElementById('sort_itr_1').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=3';};
		document.getElementById('sort_itr_2').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=2';};
		document.getElementById('sort_itr_3').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=5';};
		document.getElementById('sort_itr_4').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=7';};
		document.getElementById('sort_itr_5').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=9';};
	}else{
		if (sort==1){
			document.getElementById('sort_itr_1').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_2').src = 'project/img5/0.gif';
			document.getElementById('sort_itr_3').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_4').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_5').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_1').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=3';};
			document.getElementById('sort_itr_2').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=2';};
			document.getElementById('sort_itr_3').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=5';};
			document.getElementById('sort_itr_4').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=7';};
			document.getElementById('sort_itr_5').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=9';};
		}
		if (sort==2){
			document.getElementById('sort_itr_1').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_2').src = 'project/img5/1.gif';
			document.getElementById('sort_itr_3').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_4').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_5').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_1').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=3';};
			document.getElementById('sort_itr_2').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=1';};
			document.getElementById('sort_itr_3').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=5';};
			document.getElementById('sort_itr_4').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=7';};
			document.getElementById('sort_itr_5').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=9';};
		}
		if (sort==3){
			document.getElementById('sort_itr_1').src = 'project/img5/0.gif';
			document.getElementById('sort_itr_2').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_3').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_4').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_5').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_1').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=4';};
			document.getElementById('sort_itr_2').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=1';};
			document.getElementById('sort_itr_3').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=5';};
			document.getElementById('sort_itr_4').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=7';};
			document.getElementById('sort_itr_5').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=9';};
		}
		if (sort==4){
			document.getElementById('sort_itr_1').src = 'project/img5/1.gif';
			document.getElementById('sort_itr_2').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_3').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_4').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_5').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_1').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=3';};
			document.getElementById('sort_itr_2').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=1';};
			document.getElementById('sort_itr_3').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=5';};
			document.getElementById('sort_itr_4').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=7';};
			document.getElementById('sort_itr_5').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=9';};
		}
		if (sort==5){
			document.getElementById('sort_itr_1').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_2').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_3').src = 'project/img5/0.gif';
			document.getElementById('sort_itr_4').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_5').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_1').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=3';};
			document.getElementById('sort_itr_2').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=1';};
			document.getElementById('sort_itr_3').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=6';};
			document.getElementById('sort_itr_4').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=7';};
			document.getElementById('sort_itr_5').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=9';};
		}
		if (sort==6){
			document.getElementById('sort_itr_1').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_2').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_3').src = 'project/img5/1.gif';
			document.getElementById('sort_itr_4').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_5').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_1').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=3';};
			document.getElementById('sort_itr_2').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=1';};
			document.getElementById('sort_itr_3').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=5';};
			document.getElementById('sort_itr_4').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=7';};
			document.getElementById('sort_itr_5').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=9';};
		}
		if (sort==7){
			document.getElementById('sort_itr_1').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_2').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_3').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_4').src = 'project/img5/0.gif';
			document.getElementById('sort_itr_5').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_1').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=3';};
			document.getElementById('sort_itr_2').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=1';};
			document.getElementById('sort_itr_3').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=5';};
			document.getElementById('sort_itr_4').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=8';};
			document.getElementById('sort_itr_5').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=9';};
		}
		if (sort==8){
			document.getElementById('sort_itr_1').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_2').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_3').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_4').src = 'project/img5/1.gif';
			document.getElementById('sort_itr_5').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_1').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=3';};
			document.getElementById('sort_itr_2').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=1';};
			document.getElementById('sort_itr_3').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=5';};
			document.getElementById('sort_itr_4').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=7';};
			document.getElementById('sort_itr_5').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=9';};
		}
		if (sort==9){
			document.getElementById('sort_itr_1').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_2').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_3').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_4').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_5').src = 'project/img5/0.gif';
			document.getElementById('sort_itr_1').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=3';};
			document.getElementById('sort_itr_2').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=1';};
			document.getElementById('sort_itr_3').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=5';};
			document.getElementById('sort_itr_4').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=7';};
			document.getElementById('sort_itr_5').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=10';};
		}
		if (sort==10){
			document.getElementById('sort_itr_1').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_2').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_3').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_4').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_5').src = 'project/img5/1.gif';
			document.getElementById('sort_itr_1').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=3';};
			document.getElementById('sort_itr_2').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=1';};
			document.getElementById('sort_itr_3').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=5';};
			document.getElementById('sort_itr_4').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=7';};
			document.getElementById('sort_itr_5').onclick = function(){ document.location.href='index.php?do=show&formid=119'+arch_url+'&sort=9';};
		}
	}
	
function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}</script>";
?>