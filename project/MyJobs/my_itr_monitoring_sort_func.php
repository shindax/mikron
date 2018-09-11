<?php
require_once("page_ids.php");
global $PROJECT_ORDER_MONITORING_PAGE_ID;

echo "<script type='text/javascript'>
if (getUrlVars()['sort']) { var sort = getUrlVars()['sort'];}else{ var sort=1}
if (getUrlVars()['spec_view']) { var spec_view='&spec_view=2';}else{ var spec_view='';}
var arch = getUrlVars()['arch'];
var p1 = getUrlVars()['p1'];
var p2 = getUrlVars()['p2'];
var p3 = getUrlVars()['p3'];

	if(arch) { var arch_1='&arch=1';}else{ var arch_1='';}
	if(p1) { var p1_1='&p1='+p1;}else{ var p1_1='';}
	if(p2) { var p2_1='&p2='+p2;}else{ var p2_1='';}
	if(p3) { var p3_1='&p3='+p3;}else{ var p3_1='';}

	if (!sort) {
		document.getElementById('sort_itr_1').src = 'project/img5/0.gif';
		document.getElementById('sort_itr_2').src = 'project/img5/c1.gif';
		document.getElementById('sort_itr_3').src = 'project/img5/c1.gif';
		document.getElementById('sort_itr_4').src = 'project/img5/c1.gif';
		document.getElementById('sort_itr_5').src = 'project/img5/c1.gif';
		document.getElementById('sort_itr_1').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=2'+p1_1+p2_1+p3_1;};
		document.getElementById('sort_itr_2').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=3'+p1_1+p2_1+p3_1;};
		document.getElementById('sort_itr_3').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=5'+p1_1+p2_1+p3_1;};
		document.getElementById('sort_itr_4').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=7'+p1_1+p2_1+p3_1;};
		document.getElementById('sort_itr_5').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=9'+p1_1+p2_1+p3_1;};
	}else{
		if (sort==2){
			document.getElementById('sort_itr_1').src = 'project/img5/1.gif';
			document.getElementById('sort_itr_2').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_3').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_4').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_5').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_1').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=1'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_2').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=3'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_3').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=5'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_4').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=7'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_5').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=9'+p1_1+p2_1+p3_1;};
		}
		if (sort==1){
			document.getElementById('sort_itr_1').src = 'project/img5/0.gif';
			document.getElementById('sort_itr_2').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_3').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_4').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_5').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_1').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=2'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_2').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=3'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_3').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=5'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_4').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=7'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_5').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=9'+p1_1+p2_1+p3_1;};
		}
		if (sort==3){
			document.getElementById('sort_itr_1').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_2').src = 'project/img5/0.gif';
			document.getElementById('sort_itr_3').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_4').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_5').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_1').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=1'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_2').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=4'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_3').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=5'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_4').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=7'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_5').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=9'+p1_1+p2_1+p3_1;};
		}
		if (sort==4){
			document.getElementById('sort_itr_1').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_2').src = 'project/img5/1.gif';
			document.getElementById('sort_itr_3').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_4').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_5').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_1').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=1'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_2').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=3'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_3').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=5'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_4').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=7'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_5').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=9'+p1_1+p2_1+p3_1;};
		}
		if (sort==5){
			document.getElementById('sort_itr_1').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_2').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_3').src = 'project/img5/0.gif';
			document.getElementById('sort_itr_4').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_5').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_1').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=1'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_2').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=3'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_3').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=6'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_4').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=7'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_5').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=9'+p1_1+p2_1+p3_1;};
		}
		if (sort==6){
			document.getElementById('sort_itr_1').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_2').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_3').src = 'project/img5/1.gif';
			document.getElementById('sort_itr_4').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_5').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_1').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=1'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_2').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=3'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_3').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=5'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_4').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=7'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_5').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=9'+p1_1+p2_1+p3_1;};
		}
		if (sort==7){
			document.getElementById('sort_itr_1').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_2').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_3').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_4').src = 'project/img5/0.gif';
			document.getElementById('sort_itr_5').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_1').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=1'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_2').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=3'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_3').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=5'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_4').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=8'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_5').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=9'+p1_1+p2_1+p3_1;};
		}
		if (sort==8){
			document.getElementById('sort_itr_1').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_2').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_3').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_4').src = 'project/img5/1.gif';
			document.getElementById('sort_itr_5').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_1').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=1'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_2').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=3'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_3').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=5'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_4').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=7'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_5').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=9'+p1_1+p2_1+p3_1;};
		}
		if (sort==9){
			document.getElementById('sort_itr_1').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_2').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_3').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_4').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_5').src = 'project/img5/0.gif';
			document.getElementById('sort_itr_1').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=1'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_2').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=3'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_3').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=5'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_4').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=7'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_5').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=10'+p1_1+p2_1+p3_1;};
		}
		if (sort==10){
			document.getElementById('sort_itr_1').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_2').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_3').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_4').src = 'project/img5/c1.gif';
			document.getElementById('sort_itr_5').src = 'project/img5/1.gif';
			document.getElementById('sort_itr_1').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=1'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_2').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=3'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_3').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=5'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_4').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=7'+p1_1+p2_1+p3_1;};
			document.getElementById('sort_itr_5').onclick = function(){ document.location.href='index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort=9'+p1_1+p2_1+p3_1;};
		}
	}
	
history.replaceState(0, 'New page title', 'index.php?do=show&formid=$PROJECT_ORDER_MONITORING_PAGE_ID'+arch_1+spec_view+'&sort='+sort);
	
function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}</script>";
?>