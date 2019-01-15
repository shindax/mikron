<!--
/* 
------------------------------------------

	MAV ERP Solution

	Мирошников А.В.

------------------------------------------
*/

var md_OY = 0;
var md_move = false;

function select_checkbox(name,sel) {
	var x=document.getElementsByName(name);
	for(var i=0; i<x.length; i++) x[i].checked=sel.checked;
}

function submit_btn(url,id) {
	obj = document.getElementById(id);
	obj.action = url;
	obj.submit();
}

function bottom_click() {
	md_OY = window.event.clientY;
	if (!md_move) {
		md_move = true;
	} else {
		md_move = false;
	}
}

function bottom_mousemove() {
	if (md_move) {
		obj = document.getElementById("bvptr");
		dd_OY = window.event.clientY;
		hhh = $(obj).height();
		newhh = hhh-dd_OY+md_OY;
		rhh = newhh;
		if ((newhh>=50) && (newhh<=600)) {
			$(obj).height(newhh);
			md_OY = dd_OY;
		}
		if (newhh<50) {
			$(obj).height(50);
			md_OY = hhh-50+md_OY;
			rhh = 50;
		}
		if (newhh>600) {
			$(obj).height(600);
			md_OY = hhh-600+md_OY;
			rhh = 600
		}
		document.cookie = "bottomheight="+rhh+"; expires=Fri, 31 Dec 3030 23:59:59 GMT;";
	}
}

function bottom_set(hhh) {
	obj = document.getElementById("bvptr");
	$(obj).height(hhh);
}

function numeric_format(val, thSep, dcSep) {
 
    // Проверка указания разделителя разрядов
    if (!thSep) thSep = ' ';
 
    // Проверка указания десятичного разделителя
    if (!dcSep) dcSep = ',';
 
    var res = val.toString();
    var lZero = (val < 0); // Признак отрицательного числа
 
    // Определение длины форматируемой части
    var fLen = res.lastIndexOf('.'); // До десятичной точки
    fLen = (fLen > -1) ? fLen : res.length;
 
    // Выделение временного буфера
    var tmpRes = res.substring(fLen);
    var cnt = -1;
    for (var ind = fLen; ind > 0; ind--) {
        // Формируем временный буфер
        cnt++;
        if (((cnt % 3) === 0) && (ind !== fLen) && (!lZero || (ind > 1))) {
            tmpRes = thSep + tmpRes;
        }
        tmpRes = res.charAt(ind - 1) + tmpRes;
    }
 
    return tmpRes.replace('.', dcSep);
 
}

function vpdiv() {

	obj = document.getElementById("vpdiv");
	obj.style.width = document.body.clientWidth+"px";
	setTimeout("vpdiv();",50);
	}

function bvpdiv() {

	obj = document.getElementById("bvpdiv");
	obj.style.width = document.body.clientWidth+"px";
	setTimeout("bvpdiv();",50);
	}

function scrollvpdiv(oy, ox) {

	obj = document.getElementById("vpdiv");
	obj.scrollTop = oy;
	setTimeout("document.getElementById('vpdiv').scrollLeft = "+ox+";",20);
	}

function scrollbvpdiv(oy, ox) {

	obj = document.getElementById("bvpdiv");
	obj.scrollTop = oy;
	setTimeout("document.getElementById('bvpdiv').scrollLeft = "+ox+";",20);
	}

function Coller(obj_id,cl) {

	obj = document.getElementById(obj_id);
	if (obj.style.background=="white") {
		obj.style.background=cl;
	} else {
		obj.style.background="white";
	}
	setTimeout("Coller(\""+obj_id+"\",\""+cl+"\");",250);
	}

function implode_select(sel) {
		res = "";
		for (j=0;j<sel.length;j=j+1) {  
			if (sel[j].selected==true) res = res + sel[j].value + "|";
		}
		return res;  
} 

function getXmlHttp() {
	var xmlhttp;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");

	} catch (e) {

		try {

			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (E) {
			xmlhttp = false;
		}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
	}

	return xmlhttp;

}


function vote(obj, url) {

	var req = getXmlHttp();
	obj.style.background = "ffcccc";
	obj.style.border = "1px solid f00";

	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			if(req.status == 200) {
				obj.style.background = "none";
				obj.style.border = "1px solid fff";
			}
		}
	}

	req.open('GET', url, true);
	req.send(null);
}

function loadurl(obj_id, url) 
{
	var req = getXmlHttp();
	req.onreadystatechange = function() 
	{
		if (req.readyState == 4) 
			if(req.status == 200) 
			{
				obj = document.getElementById(obj_id);
				obj.innerHTML = req.responseText;

			// shindax 12.03.2018
			
				if( req.responseText.length == 0 )
					$('.lid_res').hide();
					else
						$('.lid_res').show();
						
			// shindax 12.03.2018
									
			} // if(req.status == 200) 
	} // req.onreadystatechange = function() 


	if( url != 'db_lid.php?db=db_zakdet&id=572336&url=index@4@php@2@do@1@show@3@formid@1@39@3@id@1@1471&value=1' )
	{

		req.open('GET', url, true);
		req.send(null);

	}


}

function ShowHide( id, el, form_id ) 
{
		x = document.getElementById(id);
		var tr = $( el ).closest('tr.cl_4')
				
		if (x.style.display !== "block") 
		{
			x.style.display = "block";
		
			if( tr.length && form_id == 43 )
			{
				$( 'tr.cl_4' ).removeAttr('data-id')
				$( tr ).attr('data-id', id ).data('id', id )
		
				localStorage.removeItem('db_shtat')
				localStorage.setItem('db_shtat', id )
			}
		}
				else 
					x.style.display = "none";
}

function Show(id) {
		x = document.getElementById(id);
		x.style.display = "block";
}

function Hide(id) {
		x = document.getElementById(id);
		x.style.display = "none";
}

function chClass(obj,class1,class2) {
		if (obj.className !== class1) {
			obj.className = class1;
		} else {
			obj.className = class2;
		}
}

var Value = 0;

function TXT(x) {
	res = x;
	res = res.replace(/\'/g,"@%1@");
	res = res.replace(/\"/g,"@%2@");
	res = res.replace(/\(/g,"@%3@");
	res = res.replace(/\)/g,"@%4@");
	res = res.replace(/\n/g,"@%5@");
	res = res.replace(/\&/g,"@%6@");
	res = res.replace(/\#/g,"@%7@");
	res = res.replace(/\\/g,"@%8@");
	res = res.replace(/\+/g,"@%9@");
	return res;
}

function KeyDown(x) {
	if (isFinite(x)) value = x;
}

function IPFilter(f, wh, e) {
	var o = eval("f."+wh);
	var k = 0;
	if ((e.keyCode == 48) || (e.keyCode == 96)) k = 1;
	if ((e.keyCode == 49) || (e.keyCode == 97)) k = 1;
	if ((e.keyCode == 50) || (e.keyCode == 98)) k = 1;
	if ((e.keyCode == 51) || (e.keyCode == 99)) k = 1;
	if ((e.keyCode == 52) || (e.keyCode == 100)) k = 1;
	if ((e.keyCode == 53) || (e.keyCode == 101)) k = 1;
	if ((e.keyCode == 54) || (e.keyCode == 102)) k = 1;
	if ((e.keyCode == 55) || (e.keyCode == 103)) k = 1;
	if ((e.keyCode == 56) || (e.keyCode == 104)) k = 1;
	if ((e.keyCode == 57) || (e.keyCode == 105)) k = 1;
	if ((e.keyCode == 37) || (e.keyCode == 39)) k = 1;
	if (e.keyCode == 8) k = 1;
	if (e.keyCode == 46) k = 1;
	if (k == 0) o.value = value;
	if (o.value<0) o.value = -o.value;
}

function IPMFilter(f, wh, e) {
	var o = eval("f."+wh);
	var k = 0;
	if ((e.keyCode == 48) || (e.keyCode == 96)) k = 1;
	if ((e.keyCode == 49) || (e.keyCode == 97)) k = 1;
	if ((e.keyCode == 50) || (e.keyCode == 98)) k = 1;
	if ((e.keyCode == 51) || (e.keyCode == 99)) k = 1;
	if ((e.keyCode == 52) || (e.keyCode == 100)) k = 1;
	if ((e.keyCode == 53) || (e.keyCode == 101)) k = 1;
	if ((e.keyCode == 54) || (e.keyCode == 102)) k = 1;
	if ((e.keyCode == 55) || (e.keyCode == 103)) k = 1;
	if ((e.keyCode == 56) || (e.keyCode == 104)) k = 1;
	if ((e.keyCode == 57) || (e.keyCode == 105)) k = 1;
	if ((e.keyCode == 37) || (e.keyCode == 39)) k = 1;
	if (e.keyCode == 8) k = 1;
	if (e.keyCode == 46) k = 1;
	if ((e.keyCode == 189) || (e.keyCode == 109)) {
	   if (isFinite(o.value)) k = 1;
	}
	if (k == 0) o.value = value;
}

function FPFilter(f, wh, e) {
	var o = eval("f."+wh);
	var k = 0;
	var vl = o.value;
	o.value = vl.replace(",",".");
	if ((e.keyCode == 48) || (e.keyCode == 96)) k = 1;
	if ((e.keyCode == 49) || (e.keyCode == 97)) k = 1;
	if ((e.keyCode == 50) || (e.keyCode == 98)) k = 1;
	if ((e.keyCode == 51) || (e.keyCode == 99)) k = 1;
	if ((e.keyCode == 52) || (e.keyCode == 100)) k = 1;
	if ((e.keyCode == 53) || (e.keyCode == 101)) k = 1;
	if ((e.keyCode == 54) || (e.keyCode == 102)) k = 1;
	if ((e.keyCode == 55) || (e.keyCode == 103)) k = 1;
	if ((e.keyCode == 56) || (e.keyCode == 104)) k = 1;
	if ((e.keyCode == 57) || (e.keyCode == 105)) k = 1;
	if ((e.keyCode == 37) || (e.keyCode == 39)) k = 1;
	if (e.keyCode == 8) k = 1;
	if (e.keyCode == 46) k = 1;
	if ((e.keyCode == 188) || (e.keyCode == 190) || (e.keyCode == 191) || (e.keyCode == 110)) {
	   if (isFinite(o.value)) k = 1;
	}
	if (k == 0) o.value = value;
	if (o.value<0) o.value = -o.value;
}

function FPMFilter(f, wh, e) {
	var o = eval("f."+wh);
	var k = 0;
	var vl = o.value;
	o.value = vl.replace(",",".");
	if ((e.keyCode == 48) || (e.keyCode == 96)) k = 1;
	if ((e.keyCode == 49) || (e.keyCode == 97)) k = 1;
	if ((e.keyCode == 50) || (e.keyCode == 98)) k = 1;
	if ((e.keyCode == 51) || (e.keyCode == 99)) k = 1;
	if ((e.keyCode == 52) || (e.keyCode == 100)) k = 1;
	if ((e.keyCode == 53) || (e.keyCode == 101)) k = 1;
	if ((e.keyCode == 54) || (e.keyCode == 102)) k = 1;
	if ((e.keyCode == 55) || (e.keyCode == 103)) k = 1;
	if ((e.keyCode == 56) || (e.keyCode == 104)) k = 1;
	if ((e.keyCode == 57) || (e.keyCode == 105)) k = 1;
	if ((e.keyCode == 37) || (e.keyCode == 39)) k = 1;
	if (e.keyCode == 8) k = 1;
	if (e.keyCode == 46) k = 1;
	if ((e.keyCode == 189) || (e.keyCode == 109)) {
	   if (isFinite(o.value)) k = 1;
	}
	if ((e.keyCode == 188) || (e.keyCode == 190) || (e.keyCode == 191) || (e.keyCode == 110)) {
	   if (isFinite(o.value)) k = 1;
	}
	if (k == 0) o.value = value;
}



var DI_WName = new Array('Пн','Вт','Ср','Чт','Пт','Сб','Вс');
var DI_MName = new Array('Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь');

var DI_SkinColor = "98b8e2";
var DI_SkinBorderColor = "8ba2c2";
var DI_CaptionColor = "444444";
var DI_CaptionMouseOverColor = "ffffff";
var DI_WeekColor = "444444";
var DI_SelectedColor = "ffd048";
var DI_SpanMoseOverBGColor = "ddd";
var DI_SpanBGColor = "fff";
var DI_DayBorderColor = "bbb";


function DI_MNum(Mon, Year) {
	var nn = new Array(31,28,31,30,31,30,31,31,30,31,30,31);
	x = 28;
	y = (Math.round(Year/4))*4;
	if (y==Year) x = 29;
	ret = nn[Mon-1];
	if (Mon==2) ret = x;
	return ret;
}

function DI_FirstDay(Year) {
	x0 = 365;
	Y = Year-1;
	days = Y*x0+Math.round((Y/4)-0.5)+6;
	week = days-(7*Math.round((days/7)-0.5));
	return week;
}

function DI_MW(Mon, Year) {
	day = DI_FirstDay(Year);
	for (j=1; j<Mon; j=j+1) {
		day = day+DI_MNum(j, Year);
	}
	day = day-(7*Math.round((day/7)-0.5));
	return day;
}


function DI_TABLE(DD0,MM0,YY0,DD,MM,YY,NAME,URL) {
	SW = DI_MW(MM, YY);
	MD = DI_MNum(MM, YY);
	Wnum = Math.round(((SW+MD)/7)-0.5);
	Sum = 7*Wnum;
	inHTML = "";
	k = 1;
	n = 0;
	inHTML = inHTML + "<table  style='border: solid 1px #"+DI_SkinColor+"' width='100%' border='0' cellpadding='2' cellspacing='0'>";
	inHTML = inHTML + "<tr style='background: #"+DI_SkinColor+";'>"; 
	for (j=0; j<=6; j=j+1) {
		inHTML = inHTML + "<td align='center' style='background: #"+DI_SkinColor+"; color: #"+DI_WeekColor+";'>"+DI_WName[j]+"</td>";
	}
	inHTML = inHTML + "</tr>";
	for (i=0; i<=Wnum; i=i+1) {
		inHTML = inHTML + "<tr style='border: 0px;'>"; 
		for (j=0; j<=6; j=j+1) {
			inHTML = inHTML + "<td style='border: solid 1px #"+DI_DayBorderColor+";";
			if (n>=SW) { if (k<=MD) {
				inHTML = inHTML + " cursor: hand;";
				bgcol = "none"
				if (k == DD0) { if (MM == MM0) { if (YY == YY0) {
					inHTML = inHTML + " background: #"+DI_SelectedColor+";";
					bgcol = "#"+DI_SelectedColor;
				}}}
				inHTML = inHTML + "' onmouseover='this.style.background=\"#ddd\"'  onmouseout='this.style.background=\""+bgcol+"\"' onclick='DI_Select("+k+","+MM+","+YY+",\""+NAME+"\",\""+URL+"\")";
			}}
			inHTML = inHTML + "'>";
			if (n>=SW) { if (k<=MD) {
				inHTML = inHTML + k;
				k = k+1;
			}}
			inHTML = inHTML + "</td>";
			n = n+1;
		}
		inHTML = inHTML + "</tr>"; 
	}
	inHTML = inHTML + "</table>";
	return inHTML;
}

function DI_Prev(DD0,MM0,YY0,DD,MM,YY,NAME,URL,VAL) {
	MM = MM-1;
	if (MM<1) {
		MM = 12;
		YY = YY-1;
	}
	document.getElementById(NAME+"_Span").innerHTML = VAL;
	DI_Create(DD0,MM0,YY0,DD,MM,YY,NAME,URL);
}

function DI_Next(DD0,MM0,YY0,DD,MM,YY,NAME,URL,VAL) {
	MM = MM+1;
	if (MM>12) {
		MM = 1;
		YY = YY+1;
	}
	document.getElementById(NAME+"_Span").innerHTML = VAL;
	DI_Create(DD0,MM0,YY0,DD,MM,YY,NAME,URL);
}

function DI_Prev_YY(DD0,MM0,YY0,DD,MM,YY,NAME,URL,VAL) {
	YY = YY-1;
	document.getElementById(NAME+"_Span").innerHTML = VAL;
	DI_Create(DD0,MM0,YY0,DD,MM,YY,NAME,URL);
}

function DI_Next_YY(DD0,MM0,YY0,DD,MM,YY,NAME,URL,VAL) {
	YY = YY+1;
	document.getElementById(NAME+"_Span").innerHTML = VAL;
	DI_Create(DD0,MM0,YY0,DD,MM,YY,NAME,URL);
}

function DI_Select(DD,MM,YY,NAME,URL) {
	event.cancelBubble=true;
	if (DD<10) DD = "0"+DD;
	if (MM<10) MM = "0"+MM;
	IOBJ = document.getElementById(NAME+"_Input");
	IOBJ.value = DD+"."+MM+"."+YY;
	OBJ = document.getElementById(NAME+"_Span");
	OBJ.innerHTML = DD+"."+MM+"."+YY;
	vote(OBJ , URL+DD+"."+MM+"."+YY);
}

function DI_Clear(NAME,URL) {
	OBJ = document.getElementById(NAME+"_Span");
	OBJ.innerHTML = "---";
	IOBJ = document.getElementById(NAME+"_Input");
	IOBJ.value = "";
	vote(OBJ , URL);
}

function DI_Create(DD0,MM0,YY0,DD,MM,YY,NAME,URL) {
	event.cancelBubble=true;
	VAL = document.getElementById(NAME+"_Span").innerHTML;
	if (VAL!="---") {
		data = VAL.split('.');
		DD0 = data[0];
		MM0 = data[1];
		YY0 = data[2];
	}
	VALUES = DD0+","+MM0+","+YY0+","+DD+","+MM+","+YY+",\""+NAME+"\",\""+URL+"\",\""+VAL+"\"";
	inHTML = "<span onClick='document.getElementById(\""+NAME+"_Span\").innerHTML = \""+VAL+"\"; event.cancelBubble=true;'>"+VAL+"</span>";
	inHTML = inHTML+"<span class='ltpopup'><div class='ltpopup' style='display: block;'>";
	inHTML = inHTML+"<table class='DateInputTable' id=\""+NAME+"_DateInput\" class='DateInput' style='border: solid 1px #"+DI_SkinBorderColor+"; width: 300px; margin: 0px -300px -300px 0px; position: relative;' width='300' border='0' cellpadding='0' cellspacing='0' onClick='event.cancelBubble=true;'>";
	inHTML = inHTML+"<tr style='background: #"+DI_SkinColor+"; padding: 0px; border: 0px;'>";
		inHTML = inHTML+"<td style='background: #"+DI_SkinColor+"; color: #"+DI_CaptionColor+"; cursor: hand;' align='center' onmouseover='this.style.color=\""+DI_CaptionMouseOverColor+"\"' onmouseout='this.style.color=\""+DI_CaptionColor+"\"' onclick='DI_Prev_YY("+VALUES+"); event.cancelBubble=true;'>&#60;&#60;</td>";
		inHTML = inHTML+"<td id=\""+NAME+"_Caption_YY\" style='background: #"+DI_SkinColor+"; color: #"+DI_CaptionColor+";' align='center'>"+YY+"</td>";
		inHTML = inHTML+"<td style='background: #"+DI_SkinColor+"; color: #"+DI_CaptionColor+"; cursor: hand;' align='center' onmouseover='this.style.color=\""+DI_CaptionMouseOverColor+"\"' onmouseout='this.style.color=\""+DI_CaptionColor+"\"' onclick='DI_Next_YY("+VALUES+"); event.cancelBubble=true;'>&#62;&#62;</td>";
	inHTML = inHTML+"</tr>";
	inHTML = inHTML+"<tr style='background: #"+DI_SkinColor+"; padding: 0px; border: 0px;'>";
		inHTML = inHTML+"<td style='background: #"+DI_SkinColor+"; color: #"+DI_CaptionColor+"; cursor: hand;' align='center' onmouseover='this.style.color=\""+DI_CaptionMouseOverColor+"\"' onmouseout='this.style.color=\""+DI_CaptionColor+"\"' onclick='DI_Prev("+VALUES+"); event.cancelBubble=true;'>&#60;&#60;</td>";
		inHTML = inHTML+"<td id=\""+NAME+"_Caption\" style='background: #"+DI_SkinColor+"; color: #"+DI_CaptionColor+";' align='center'>"+DI_MName[MM-1]+"</td>";
		inHTML = inHTML+"<td style='background: #"+DI_SkinColor+"; color: #"+DI_CaptionColor+"; cursor: hand;' align='center' onmouseover='this.style.color=\""+DI_CaptionMouseOverColor+"\"' onmouseout='this.style.color=\""+DI_CaptionColor+"\"' onclick='DI_Next("+VALUES+"); event.cancelBubble=true;'>&#62;&#62;</td>";
	inHTML = inHTML+"</tr><tr style='border: 0px;'><td colspan='3' id=\""+NAME+"_InputBody\" style='border: 0px; padding: 2px;'>";
	inHTML = inHTML+DI_TABLE(DD0,MM0,YY0,DD,MM,YY,NAME,URL);
	inHTML = inHTML+"</td></tr>";
	inHTML = inHTML+"<tr style='background: #"+DI_SkinColor+";'>";
		inHTML = inHTML+"<td colspan='3' style='background: #"+DI_SkinColor+";'>";
		inHTML = inHTML+"<table align='center' width='290' border='0' cellpadding='0' cellspacing='0' onClick='event.cancelBubble=true;' style='width: 290px;'><tr>";
		inHTML = inHTML+"<td align='left' style='background: #"+DI_SkinColor+"; text-align: left;'><span style='color: #"+DI_CaptionColor+"; cursor: hand;' onClick='DI_Clear(\""+NAME+"\",\""+URL+"\")' onmouseover='this.style.color=\""+DI_CaptionMouseOverColor+"\"' onmouseout='this.style.color=\""+DI_CaptionColor+"\"'>Нет даты</span></td>";
		inHTML = inHTML+"<td  align='right' style='background: #"+DI_SkinColor+"; text-align: right;'><span style='color: #"+DI_CaptionColor+"; cursor: hand;' onClick='document.getElementById(\""+NAME+"_Span\").innerHTML = \""+VAL+"\";' onmouseover='this.style.color=\""+DI_CaptionMouseOverColor+"\"' onmouseout='this.style.color=\""+DI_CaptionColor+"\"'>Закрыть</span></td>";
		inHTML = inHTML+"</tr></table>";
		inHTML = inHTML+"</td>";
	inHTML = inHTML+"</tr></table>";
	inHTML = inHTML+"</div></span>";
	document.getElementById(NAME+"_Span").innerHTML = inHTML;
}
-->


$(document).on("click", ".print_MTK_4", function (e) {
	e.preventDefault();
	
	/*if ($("#MTK4_multiselect").length != 1) {
		$(this).closest("table.rdtbl").find("thead tr:nth-child(1)").find("td:nth-child(4)").append("<input style='text-align:center' type='checkbox' name='MTK4_print' id='MTK4_multiselect' value=''/>");
	}*/	
	
		
	var trs = $(this).closest("table.rdtbl").find("tbody tr");
	
	trs.each(function () {
		
		if ($(this).find("td:nth-child(5)").find("input[name=MTK4_print]").length != 1) {
			var id = $(this).find(".print_MTK_4").attr("href");
			
			if (id === undefined) {
			} else {
				id = id.replace("index.php?do=show&formid=226&id=", "");
				
					console.log(id);
			
			}
			
		
			$(this).find("td:nth-child(5)").append("<input style='float:left' type='checkbox' name='MTK4_print' value=''/>");
			
			$(this).find("td:nth-child(5)").find("input[name=MTK4_print]").val(id);
		} else {
			var checked = $("input[name=MTK4_print]:checked");
			
			var checked_check = [];
			
			checked.each(function () {
				if ($(this).is(":checked")) {
					checked_check.push($(this).val());
				}
			});
			
			//console.log(checked_check);
			
			window.open('/index.php?do=show&formid=226&ids=' + checked_check.join(","), '_blank');
		}
	});
	

	
	
	return false;
});