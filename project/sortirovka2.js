-function listen(){
	
	prepTabs = function (t){
		//var imgtbl0 = document.getElementById('sort_0');
		var sort = getUrlVars()["sort"];
		var d_1_d = document.getElementById('d_1_d');
		var d_2_d = document.getElementById('d_2_d');
		var d_3_d = document.getElementById('d_3_d');
		if(sort == 1){
			d_1_d.src = 'project/img5/d1.png';
			d_2_d.src = 'project/img5/c1.png';
			d_3_d.src = 'project/img5/c1.png';
		}
		if(sort == 2){
			d_1_d.src = 'project/img5/u1.png';
			d_2_d.src = 'project/img5/c1.png';
			d_3_d.src = 'project/img5/c1.png';
		}
		if(sort == 3){
			d_1_d.src = 'project/img5/c1.png';
			d_2_d.src = 'project/img5/u1.png';
			d_3_d.src = 'project/img5/c1.png';
		}
		if(sort == 4){
			d_1_d.src = 'project/img5/c1.png';
			d_2_d.src = 'project/img5/d1.png';
			d_3_d.src = 'project/img5/c1.png';
		}
		if(sort == 5){
			d_1_d.src = 'project/img5/c1.png';
			d_2_d.src = 'project/img5/c1.png';
			d_3_d.src = 'project/img5/u1.png';
		}
		if(sort == 6){
			d_1_d.src = 'project/img5/c1.png';
			d_2_d.src = 'project/img5/c1.png';
			d_3_d.src = 'project/img5/d1.png';
		}
		for (var aaa=0; aaa < 15; aaa++){
			var imgtbl = document.getElementById('sort_' + aaa);
			if (imgtbl !== undefined){
				if(imgtbl !== null) {
					imgtbl.onclick = clicktab;
				}
			}
		}
	}
	
	var clicktab = function (e) {
		e = window.event
		var obj = e.target || e.srcElement;
		while (!obj.tagName.match(/^(th|td)$/i)) obj = obj.parentNode
		var formid = getUrlVars()["formid"];
		var sort = getUrlVars()["sort"];
		if (obj.className == 'sort1'){
			if (!sort){
				location.href = 'index.php?do=show&formid='+formid+'&sort=2';				
			}else{
			if (sort > 0 && sort < 3){
				if (sort == 1){
					location.href = 'index.php?do=show&formid='+formid+'&sort=2';				
				}
				if (sort == 2){
					location.href = 'index.php?do=show&formid='+formid+'&sort=1';				
				}
			}else{
				location.href = 'index.php?do=show&formid='+formid+'&sort=2';				
			}}
		}
		if (obj.className == 'sort2'){
			if (!sort){
				location.href = 'index.php?do=show&formid='+formid+'&sort=3';				
			}else{
			if (sort > 2 && sort < 5){
				if (sort == 3){
					location.href = 'index.php?do=show&formid='+formid+'&sort=4';				
				}
				if (sort == 4){
					location.href = 'index.php?do=show&formid='+formid+'&sort=3';				
				}
			}else{
				location.href = 'index.php?do=show&formid='+formid+'&sort=3';				
			}}
		}
		if (obj.className == 'sort3'){
			if (!sort){
				location.href = 'index.php?do=show&formid='+formid+'&sort=3';				
			}else{
			if (sort > 4 && sort < 7){
				if (sort == 5){
					location.href = 'index.php?do=show&formid='+formid+'&sort=6';				
				}
				if (sort == 6){
					location.href = 'index.php?do=show&formid='+formid+'&sort=5';				
				}
			}else{
				location.href = 'index.php?do=show&formid='+formid+'&sort=5';				
			}}
		}
		//alert(obj.className)
		if (e.shiftKey) {
		}else{
		}
	}
	window.onload = prepTabs
}()

function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}