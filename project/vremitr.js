if (document.getElementById('d_1_d')) { document.getElementById('d_1_d').style.display='none';}
if (document.getElementById('d_2_d')) { document.getElementById('d_2_d').style.display='none';}
if (document.getElementById('d_3_d')) { document.getElementById('d_3_d').style.display='none';}
-function listen(){
var tota1 = 0, tota2 = 0, tota3 = 0, tota;
var p4 = getUrlVars()["p4"];
	prepTabs = function (t){
		var additr = document.getElementById('additr');
		additr.onclick = clicktab;
		var id = getUrlVars()["id"];
		history.replaceState(0, "New page title", "index.php?do=show&formid=121&id=" + id);
		document.getElementById('vremitr2').className='rwField ntabg';
	}
	var clicktab = function (e) {
		e = window.event;
		var obj = e.target || e.srcElement;
		var id = getUrlVars()["id"];
		var edopoluch = document.getElementById('edopoluch');
		var edopoluch = document.getElementById('vremitr1');
		if (document.getElementById('vremitr4').getElementsByTagName('input')[0]) {
			if (edopoluch.innerText !== '') {
				tota1 = 1;
			}else{
				alert ('Проверьте заполнение полей \"получатель(фамилия)\"');
				this.checked=false;
			}
			if (vremitr1.innerText !== '---') {
				tota2 = 1;
			}else{
				alert ('Проверьте заполнение полей \"дата выполнения\"');
				this.checked=false;
			}
			if (p4 !== '0') {
				tota3 = 1;
			}else{
				alert ('Проверьте заполнение полей \"исполнитель\"');
				this.checked=false;
			}
			tota = tota1 + tota2 + tota3;
			if (tota == 3) {
				if (obj.className == 'additr') {
					document.location.href = "index.php?do=show&formid=121&id=" + id + "&p0=1";				
				}
			}
		}else{
			alert ('у вас нет прав на добавление заданий из документа');
			this.checked=false;
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