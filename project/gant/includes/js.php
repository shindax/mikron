	<script language="javascript">
	var selected = "none";
	var selectedtp = 0;
	var selectedcolor = 0;
	var usemarker = false;
	var redactor_pos = 600;
	var redactor_show = true;
	var Value = 0;
	var SelStatus = 0;
	var SelNUM = 0;

	function KeyDown(x) {
		if (isFinite(x)) value = x;
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
		if (k == 0) o.value = Value;
		if (o.value<0) o.value = -o.value;
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


	function votesel(obj_id, url) {

		var req = getXmlHttp();
		SelNUM = SelNUM + 1;

		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if(req.status == 200) {
					SelStatus = SelStatus + 1;
					obj = document.getElementById(obj_id);
					obj.innerHTML = req.responseText;
					obj.style.opacity=1;
				}
			}
		}

		document.getElementById(obj_id).style.opacity=0.3;
		req.open('GET', url, true);
		req.send(null);
	}

	function voteoc(obj_id, url) {

		var req = getXmlHttp();
		SelNUM = SelNUM + 1;

		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if(req.status == 200) {
					SelStatus = SelStatus + 1;
					obj = document.getElementById(obj_id);
					obj.innerHTML = req.responseText;
					obj.style.opacity=1;
					barobj = document.getElementById("statbar");
					statdiv = document.getElementById("stat");
					barobj.innerHTML = statdiv.innerHTML;
					barobj = document.getElementById("toolbar");
					statdiv = document.getElementById("svod");
					barobj.innerHTML = statdiv.innerHTML;
					
					setHeight();
				}
			}
		}

		document.getElementById(obj_id).style.opacity=0.3;
		req.open('GET', url, true);
		req.send(null);
	}

	function vote(obj_id, url) {

		var req = getXmlHttp();

		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if(req.status == 200) {
					obj = document.getElementById(obj_id);
					obj.innerHTML = req.responseText;
					barobj = document.getElementById("statbar");
					statdiv = document.getElementById("stat");
					barobj.innerHTML = statdiv.innerHTML;
					barobj = document.getElementById("toolbar");
					statdiv = document.getElementById("svod");
					barobj.innerHTML = statdiv.innerHTML;
				}
			}
		}

		req.open('GET', url, true);
		req.send(null);
	}

	function vredact(obj, url) {

		var req = getXmlHttp();
		obj.style.background = "ffcccc";
		obj.style.border = "1px solid f00";

		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if(req.status == 200) {
					obj.style.background = "ffffff";
					obj.style.border = "1px solid aaa";
				}
			}
		}

		req.open('GET', url, true);
		req.send(null);
	}

	function reload_vote(obj_id, url) {

		var req = getXmlHttp();

		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if(req.status == 200) {
					obj = document.getElementById(obj_id);
					obj.innerHTML = req.responseText;
				}
			}
		}

		req.open('GET', url, true);
		req.send(null);
	}

	function reload_rows_vote() {

		var req = getXmlHttp();

		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if(req.status == 200) {
					eval(req.responseText);
				}
			}
		}
		req.open('GET', "<?php echo $reload_row_url; ?>&sel="+selected, true);
		req.send(null);
	}

	function load_votes() {
		vote("operdiv", "vote_1_tree.php");
		vote("svoddiv", "vote_1_svod.php");
		vote("win", "<?php echo $table_url; ?>");
		vote("hresdiv", "vote_3_hres.php");
		vote("resdiv", "<?php echo $res_url; ?>");
	}

	function tree_open(id) {
	   if (SelNUM==SelStatus) {

		SelStatus = 0;
		SelNUM = 0;

		unselect();
		voteoc("operdiv", "vote_1_tree.php?open="+id);
		voteoc("svoddiv", "vote_1_svod.php?open="+id);
		voteoc("win", "<?php echo $table_url; ?>&open="+id);
		voteoc("hresdiv", "vote_3_hres.php");
		voteoc("resdiv", "<?php echo $res_url; ?>");
		event.cancelBubble = true;
	   }
	}

	function tree_close(id) {
	   if (SelNUM==SelStatus) {

		SelStatus = 0;
		SelNUM = 0;

		unselect();
		voteoc("operdiv", "vote_1_tree.php?close="+id);
		voteoc("svoddiv", "vote_1_svod.php?close="+id);
		voteoc("win", "<?php echo $table_url; ?>&close="+id);
		voteoc("hresdiv", "vote_3_hres.php");
		voteoc("resdiv", "<?php echo $res_url; ?>");
		event.cancelBubble = true;
	   }
	}

	function all_tree_open() {
	   if (SelNUM==SelStatus) {

		SelStatus = 0;
		SelNUM = 0;

		unselect();
		voteoc("operdiv", "vote_1_tree.php?openall");
		voteoc("svoddiv", "vote_1_svod.php?openall");
		voteoc("win", "<?php echo $table_url; ?>&openall");
		voteoc("hresdiv", "vote_3_hres.php");
		voteoc("resdiv", "<?php echo $res_url; ?>");
		event.cancelBubble = true;
	   }
	}

	function all_tree_close() {
	   if (SelNUM==SelStatus) {

		SelStatus = 0;
		SelNUM = 0;

		unselect();
		voteoc("operdiv", "vote_1_tree.php?closeall");
		voteoc("svoddiv", "vote_1_svod.php?closeall");
		voteoc("win", "<?php echo $table_url; ?>&closeall");
		voteoc("hresdiv", "vote_3_hres.php");
		voteoc("resdiv", "<?php echo $res_url; ?>");
		event.cancelBubble = true;
	   }
	}

	function doWinScroll() {
		obj = document.getElementById("win");
		OY = obj.scrollTop;
		OX = obj.scrollLeft;
		obj = document.getElementById("datesdiv");
		obj.scrollLeft = OX;
		obj = document.getElementById("resdiv");
		obj.scrollLeft = OX;
		obj = document.getElementById("svoddiv");
		obj.scrollTop = OY;
		obj = document.getElementById("operdiv");
		obj.scrollTop = OY;
	}

	function doWinScroll2() {
		obj = document.getElementById("operdiv");
		OY = obj.scrollTop;
		obj = document.getElementById("win");
		obj.scrollTop = OY;
		obj = document.getElementById("svoddiv");
		obj.scrollTop = OY;

	}

	function doWinScroll3() {
		obj = document.getElementById("svoddiv");
		OY = obj.scrollTop;
		obj = document.getElementById("win");
		obj.scrollTop = OY;
		obj = document.getElementById("operdiv");
		obj.scrollTop = OY;

	}

	function vpdiv() {

		wdth = (document.body.clientWidth - <?php echo ($L_width+$br_width+$svod_width); ?> - 5) + "px";
		hght = (document.body.clientHeight - 192) + "px";
		obj = document.getElementById("win");
		obj.style.width = wdth;
		obj.style.height = hght;
		obj = document.getElementById("datesdiv");
		obj.style.width = wdth;
		obj = document.getElementById("resdiv");
		obj.style.width = wdth;
		obj = document.getElementById("svoddiv");
		obj.style.height = hght;
		obj = document.getElementById("operdiv");
		obj.style.height = hght;
	}

	function OpenSmen(operid) {
		if (SelNUM==SelStatus) {
			select('o'+operid,1); 
			obj = document.getElementById("smenzad");
			obj.style.display = "block";
			voteoc("smenzad", "vote_smenzad.php?id_oper="+operid);
		}
	}

	function CloseSmen() {
		obj = document.getElementById("smenzad");
		obj.style.display = "none";
		obj.innerHTML = "";
		unselect();
	}

	function CloseResources() {
		document.getElementById("resource_orders").style.display = "none";
		document.getElementById("resource_orders_content").innerHTML = "";
	}

	function CloseResourcesTabel() {
		document.getElementById("resource_tabel").style.display = "none";
		document.getElementById("resource_tabel_content").innerHTML = "";
	}

	function select(x,tp) {
	   if (SelNUM==SelStatus) {
	   if (x!==selected) {

		SelStatus = 0;
		SelNUM = 0;

		if (selected!=="none") {

			votesel("R_"+selected,"<?php echo $row_url; ?>&sel="+selected);
			votesel("S_"+selected,"<?php echo $svodrow_url; ?>&sel="+selected);
			if (selectedtp==1) reload_rows_vote();

			obj = document.getElementById("L_"+selected);
			obj.style.background = selectedcolor;
			if (selectedtp==1) obj.style.height = "<?php echo $oper_row_height; ?>px";
			obj = document.getElementById("R_"+selected);
			obj.style.background = selectedcolor;
			if (selectedtp==1) obj.style.height = "<?php echo $oper_row_height; ?>px";
			obj = document.getElementById("S_"+selected);
			obj.style.background = selectedcolor;
			if (selectedtp==1) obj.style.height = "<?php echo $oper_row_height; ?>px";
		}
		obj = document.getElementById("L_"+x);
		if(obj == null) return;
		selectedcolor = obj.style.background;
		obj.style.background = "ffd048";
		if (tp==1) obj.style.height = "<?php echo $oper_row_height_sel; ?>px";
		obj = document.getElementById("R_"+x);
		
		if(obj == null) return;
		obj.style.background = "ffd048";
		if (tp==1) obj.style.height = "<?php echo $oper_row_height_sel; ?>px";
		obj = document.getElementById("S_"+x);
		
		if(obj == null) return;
		obj.style.background = "ffd048";
		if (tp==1) obj.style.height = "<?php echo $oper_row_height_sel; ?>px";
		selected = x;
		selectedtp = tp;

		votesel("hresdiv", "vote_3_hres.php?sel="+x);
		votesel("resdiv", "<?php echo $res_url; ?>&sel="+x);

		if (tp==1) votesel("R_"+x,"<?php echo $rrow_url; ?>&sel="+x);

		//barobj = document.getElementById("toolbar");
		//barobj.innerHTML = "Select: "+selected;
	   }
	   }
	}

	function ShowResourceOrders(date, smena, resource_id) {
		var req = getXmlHttp();

		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if(req.status == 200) {
					obj = document.getElementById("resource_orders_content");
					
					obj.innerHTML = '';
			
					document.getElementById("resource_orders").style.display = "inline-block";
				
					obj.innerHTML = req.responseText.replace(new RegExp("Table .+ exist",'g'), ""); 
				}
			}
		}

		req.open('GET', '/project/gant/vote_show_resources.php?date=' + date + '&smena=' + smena + "&resource_id=" + resource_id, true);
		req.send(null);
	}
	
	function ShowResourceTabel (id_oper) {
		var req = getXmlHttp();

		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if(req.status == 200) {
					obj = document.getElementById("resource_tabel_content");
					
					obj.innerHTML = '';
			
					document.getElementById("resource_tabel").style.display = "block";
				
					obj.innerHTML = req.responseText.replace(new RegExp("Table .+ exist",'g'),"");

	
				}
			}
		}

		req.open('GET', '/project/gant/vote_edit_tabel.php?id_oper=' + id_oper, true);
		req.send(null);
	}
	
	function ShowResourceTabelSend (form) {
		var firstday = form.firstday.value, secondday = form.secondday.value, variant = document.getElementsByName("variant"), variant_selected, var_smena = form.var_smena.value, var_time = form.var_time.value, id_oper = form.id_oper.value;
		
		for (var i = 0; i < variant.length; ++i) {
			if (variant[i].checked) {
				variant_selected = variant[i].value;
			}
		}

		var req = getXmlHttp();

		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if(req.status == 200) {
					obj = document.getElementById("resource_tabel_content");
					
					obj.innerHTML = '';
			
					document.getElementById("resource_tabel").style.display = "block";
				
					OpenSmen(id_oper);
					
					reload_rows_vote();
				}
			}
		}

		var resursIDS_elements = document.getElementsByName('resursIDS'), resursIDS = [];
		
		for (var i = 0; i < resursIDS_elements.length; ++i) {
			if (resursIDS_elements[i].checked) {
				resursIDS.push(resursIDS_elements[i].value);
			}
		}
		
		req.open('POST', '/project/gant/edit_tabel.php', true);
		req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		req.send('variant=' + variant_selected + "&resursIDS=" + JSON.stringify(resursIDS) + '&firstday=' + firstday + '&secondday=' + secondday + "&var_smena=" + var_smena + "&var_time=" + var_time);
	}
	
	function unselect() {
	   if (SelNUM==SelStatus) {
		SelNUM = 0;
		SelStatus = 0;
		if (selected!=="none") {

			//barobj = document.getElementById("toolbar");
			//barobj.innerHTML = "Unselect: "+selected;

			votesel("R_"+selected,"<?php echo $row_url; ?>&sel="+selected);
			votesel("S_"+selected,"<?php echo $svodrow_url; ?>&sel="+selected);
			if (selectedtp==1) reload_rows_vote();

			obj = document.getElementById("L_"+selected);
			obj.style.background = selectedcolor;
			if (selectedtp==1) obj.style.height = "<?php echo $oper_row_height; ?>px";
			obj = document.getElementById("R_"+selected);
			obj.style.background = selectedcolor;
			if (selectedtp==1) obj.style.height = "<?php echo $oper_row_height; ?>px";
			obj = document.getElementById("S_"+selected);
			obj.style.background = selectedcolor;
			if (selectedtp==1) obj.style.height = "<?php echo $oper_row_height; ?>px";
		}
		selected="none";
		selectedtp=0;
	   }
	}

	function moovemarker() {
		if (usemarker) {
			var e = window.event;

			mrk1 = document.getElementById("marker1");
			mrk2 = document.getElementById("marker2");
			if (e.clientX><?php echo ($L_width+$br_width+$svod_width); ?>) {

				obj = document.getElementById("win");
				scrl = obj.scrollLeft;
				xpos = Math.ceil((e.clientX - <?php echo ($L_width+$br_width+$svod_width+1); ?> + scrl)/35);
				xpos = (xpos * 35) - scrl + <?php echo ($L_width+$br_width+$svod_width+1); ?>;
				ox1 = xpos+1;
				ox2 = xpos-38;
				wdth = document.body.clientWidth-10;

				if (ox1<wdth) {
					mrk1.style.left = ox1;
					mrk1.style.display = "block";
					mrk2.style.left = ox2;
					mrk2.style.display = "block";
				} else {
					mrk1.style.display = "none";
					mrk2.style.display = "none";
				}
			} else {
				mrk1.style.display = "none";
				mrk2.style.display = "none";
			}
		}
	}

	function DoUseMarker() {
		usemarker = true;
		mrkkeyon = document.getElementById("markerkeyon");
		mrkkeyoff = document.getElementById("markerkeyoff");
		mrkkeyon.style.display = "none";
		mrkkeyoff.style.display = "block";
	}

	function DoNotUseMarker() {
		usemarker = false;
		mrkkeyon = document.getElementById("markerkeyon");
		mrkkeyoff = document.getElementById("markerkeyoff");
		mrkkeyoff.style.display = "none";
		mrkkeyon.style.display = "block";
		mrk1 = document.getElementById("marker1");
		mrk2 = document.getElementById("marker2");
		mrk1.style.display = "none";
		mrk2.style.display = "none";
	}

	function SetNewValue(from_id,to_id,val,url) {
		fr_obj = document.getElementById(from_id);
		xxx = fr_obj.value*val;
		yyy = Math.ceil(xxx*100);
		to_obj = document.getElementById(to_id);
		to_obj.value = yyy/100;
		vredact(to_obj,url+to_obj.value);
	}
	
	function scroll()
	{
		var scrollme = document.getElementsByClassName("tr1");
		var scroll_from = document.getElementById("scrollme");
			
		for (var i = 0; i < scrollme.length; ++i) {
			scrollme[i].scrollLeft = scroll_from.scrollLeft;
		}
	}
	
	function setHeight ()
	{
		var trs = document.getElementsByClassName("tr1");
		
		for (var i = 0; i < trs.length; ++i) {
			if (trs[i].getElementsByClassName("inp").length > 0) {
				trs[i].style.height = '94px';
			} else {
				trs[i].style.height = '26px';
			}
			
			var id = trs[i].dataset.id;
			
			var trs_name = document.getElementsByClassName("tr2");
			
			for (var y = 0; y < trs_name.length; ++y) {
				var id_name = trs_name[y].dataset.id;
				
				if (id == id_name) {
					trs_name[y].style.height = trs[i].style.height;
				}
			}
		}
	}
	</script>
