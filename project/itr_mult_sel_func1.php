<?php
$result5 = dbquery("SELECT NAME FROM okb_db_resurs where (ID_users='".$user['ID']."') ");
$name5 = mysql_fetch_array($result5);

echo "<script type='text/javascript'>
function check_itr_status(obj, id_itr){
	if (obj.checked == true) {
		var cur_fio_us = '".$name5['NAME']."';
		var txt_cur_stat = obj.parentNode.parentNode.cells[9].innerText;
		var txt_cur_stat2 = '<option>';
		
		if ((obj.parentNode.parentNode.cells[7].innerText == cur_fio_us) && (obj.parentNode.parentNode.cells[6].innerText !== cur_fio_us)){
			if (txt_cur_stat == '�� ���������') { 			var chang_status = '<select onchange=\"check_itr_status_all(this)\" id=\"mult_s_'+id_itr+'\"><option selected>�� ���������<option>���������<option>�������</select>';}
			if (txt_cur_stat == '�������') { 				var chang_status = '<select onchange=\"check_itr_status_all(this)\" id=\"mult_s_'+id_itr+'\"><option selected>�������</select>';}
			if (txt_cur_stat == '�����') { 					var chang_status = '<select onchange=\"check_itr_status_all(this)\" id=\"mult_s_'+id_itr+'\"><option selected>�����<option>������� � ����������<option>���������<option>�������<option>�� ���������</select>';}
			if (txt_cur_stat == '������� � ����������') { 	var chang_status = '<select onchange=\"check_itr_status_all(this)\" id=\"mult_s_'+id_itr+'\"><option selected>������� � ����������<option>���������<option>�������<option>�� ���������</select>';}
			if (txt_cur_stat == '���������') { 				var chang_status = '<select onchange=\"check_itr_status_all(this)\" id=\"mult_s_'+id_itr+'\"><option selected>���������<option>�������<option>�� ���������</select>';}
			if (txt_cur_stat == '�����������') { 			var chang_status = '<select onchange=\"check_itr_status_all(this)\" id=\"mult_s_'+id_itr+'\"><option selected>�����������<option>������� � ����������<option>���������<option>�������<option>�� ���������</select>';}
		}
		if ((obj.parentNode.parentNode.cells[7].innerText == cur_fio_us) && (obj.parentNode.parentNode.cells[6].innerText == cur_fio_us)){
			if (txt_cur_stat == '�� ���������') { 			var chang_status = '<select onchange=\"check_itr_status_all(this)\" id=\"mult_s_'+id_itr+'\"><option selected>�� ���������<option>������������<option>���������</select>';}
			if (txt_cur_stat == '�������') { 				var chang_status = '<select onchange=\"check_itr_status_all(this)\" id=\"mult_s_'+id_itr+'\"><option selected>�������<option>�� ���������<option>������������<option>���������</select>';}
			if (txt_cur_stat == '�����') { 					var chang_status = '<select onchange=\"check_itr_status_all(this)\" id=\"mult_s_'+id_itr+'\"><option selected>�����<option>������� � ����������<option>������������<option>���������</select>';}
			if (txt_cur_stat == '������� � ����������') { 	var chang_status = '<select onchange=\"check_itr_status_all(this)\" id=\"mult_s_'+id_itr+'\"><option selected>������� � ����������<option>������������<option>���������</select>';}
			if (txt_cur_stat == '���������') { 				var chang_status = '<select onchange=\"check_itr_status_all(this)\" id=\"mult_s_'+id_itr+'\"><option selected>���������<option>�� ���������<option>������������<option>���������</select>';}
			if (txt_cur_stat == '�����������') { 			var chang_status = '<select onchange=\"check_itr_status_all(this)\" id=\"mult_s_'+id_itr+'\"><option selected>�����������<option>������� � ����������<option>������������<option>���������</select>';}
		}
		if ((obj.parentNode.parentNode.cells[7].innerText !== cur_fio_us) && (obj.parentNode.parentNode.cells[6].innerText == cur_fio_us)){
			if (txt_cur_stat == '�� ���������') { 			var chang_status = '<select onchange=\"check_itr_status_all(this)\" id=\"mult_s_'+id_itr+'\"><option selected>�� ���������<option>���������<option>������������</select>';}
			if (txt_cur_stat == '�������') { 				var chang_status = '<select onchange=\"check_itr_status_all(this)\" id=\"mult_s_'+id_itr+'\"><option selected>�������<option>�� ���������<option>������������<option>���������</select>';}
			if (txt_cur_stat == '�����') { 					var chang_status = '<select onchange=\"check_itr_status_all(this)\" id=\"mult_s_'+id_itr+'\"><option selected>�����<option>������� � ����������<option>���������<option>������������</select>';}
			if (txt_cur_stat == '������� � ����������') { 	var chang_status = '<select onchange=\"check_itr_status_all(this)\" id=\"mult_s_'+id_itr+'\"><option selected>������� � ����������<option>���������<option>������������</select>';}
			if (txt_cur_stat == '���������') { 				var chang_status = '<select onchange=\"check_itr_status_all(this)\" id=\"mult_s_'+id_itr+'\"><option selected>���������<option>������������</select>';}
			if (txt_cur_stat == '�����������') { 			var chang_status = '<select onchange=\"check_itr_status_all(this)\" id=\"mult_s_'+id_itr+'\"><option selected>�����������<option>������� � ����������<option>���������<option>������������</select>';}
		}
		if ((obj.parentNode.parentNode.cells[7].innerText !== cur_fio_us) && (obj.parentNode.parentNode.cells[6].innerText !== cur_fio_us)){
			if (txt_cur_stat == '�� ���������') { 			var chang_status = '<select onchange=\"check_itr_status_all(this)\" id=\"mult_s_'+id_itr+'\"><option selected>�� ���������<option>���������</select>';}
			if (txt_cur_stat == '�������') { 				var chang_status = '<select onchange=\"check_itr_status_all(this)\" id=\"mult_s_'+id_itr+'\"><option selected>�������</select>';}
			if (txt_cur_stat == '�����') { 					var chang_status = '<select onchange=\"check_itr_status_all(this)\" id=\"mult_s_'+id_itr+'\"><option selected>�����<option>������� � ����������<option>���������</select>';}
			if (txt_cur_stat == '������� � ����������') { 	var chang_status = '<select onchange=\"check_itr_status_all(this)\" id=\"mult_s_'+id_itr+'\"><option selected>������� � ����������<option>���������</select>';}
			if (txt_cur_stat == '���������') { 				var chang_status = '<select onchange=\"check_itr_status_all(this)\" id=\"mult_s_'+id_itr+'\"><option selected>���������</select>';}
			if (txt_cur_stat == '�����������') { 			var chang_status = '<select onchange=\"check_itr_status_all(this)\" id=\"mult_s_'+id_itr+'\"><option selected>�����������<option>������� � ����������<option>���������</select>';}
		}
		
		obj.parentNode.parentNode.cells[9].innerHTML = chang_status;
	}
	if (obj.checked == false){
		var opt_ind = document.getElementById('mult_s_'+id_itr).selectedIndex;
		var opt_txt = document.getElementById('mult_s_'+id_itr).options;
		obj.parentNode.parentNode.cells[9].innerHTML = opt_txt[opt_ind].text;
	}
}
function check_itr_status_all(obj){
	if(confirm('�������������������������������������������������������?')){
		var cur_fio_us = '".$name5['NAME']."';
		var ids_itrs = '';
		var ch_m_s = document.getElementsByName('mult_sel_itr').length;
		var ch_m_s_true = 0;
		for (var a_s_m = 0; a_s_m < ch_m_s; a_s_m++){
			if (document.getElementsByName('mult_sel_itr')[a_s_m].checked == true) {
				ch_m_s_true = ch_m_s_true + 1;
				document.getElementsByName('mult_sel_itr')[a_s_m].setAttribute('disabled', 'true');
				document.getElementById('mult_s_'+document.getElementsByName('mult_sel_itr')[a_s_m].id.substr(8)).setAttribute('disabled', 'true');
				if (obj.options[obj.selectedIndex].text == '������������'){
					if (obj.parentNode.parentNode.cells[6].innerText == cur_fio_us) ids_itrs = ids_itrs + document.getElementsByName('mult_sel_itr')[a_s_m].id.substr(8) + '|';
				}
				if (obj.options[obj.selectedIndex].text == '���������'){
					if ((obj.parentNode.parentNode.cells[6].innerText == cur_fio_us) && (document.getElementById('mult_s_'+document.getElementsByName('mult_sel_itr')[a_s_m].id.substr(8)).options[0].text == '�������')) ids_itrs = ids_itrs + document.getElementsByName('mult_sel_itr')[a_s_m].id.substr(8) + '|';
					if ((obj.parentNode.parentNode.cells[7].innerText == cur_fio_us) && (obj.parentNode.parentNode.cells[6].innerText == cur_fio_us)) ids_itrs = ids_itrs + document.getElementsByName('mult_sel_itr')[a_s_m].id.substr(8) + '|';
				}
				if (obj.options[obj.selectedIndex].text == '���������'){
					if (document.getElementById('mult_s_'+document.getElementsByName('mult_sel_itr')[a_s_m].id.substr(8)).options[0].text == '�����') ids_itrs = ids_itrs + document.getElementsByName('mult_sel_itr')[a_s_m].id.substr(8) + '|';
					if (document.getElementById('mult_s_'+document.getElementsByName('mult_sel_itr')[a_s_m].id.substr(8)).options[0].text == '�����������') ids_itrs = ids_itrs + document.getElementsByName('mult_sel_itr')[a_s_m].id.substr(8) + '|';
					if (document.getElementById('mult_s_'+document.getElementsByName('mult_sel_itr')[a_s_m].id.substr(8)).options[0].text == '������� � ����������') ids_itrs = ids_itrs + document.getElementsByName('mult_sel_itr')[a_s_m].id.substr(8) + '|';
					if (document.getElementById('mult_s_'+document.getElementsByName('mult_sel_itr')[a_s_m].id.substr(8)).options[0].text == '�� ���������') ids_itrs = ids_itrs + document.getElementsByName('mult_sel_itr')[a_s_m].id.substr(8) + '|';
				}
				if (obj.options[obj.selectedIndex].text == '������� � ����������'){
					if (document.getElementById('mult_s_'+document.getElementsByName('mult_sel_itr')[a_s_m].id.substr(8)).options[0].text == '�����') ids_itrs = ids_itrs + document.getElementsByName('mult_sel_itr')[a_s_m].id.substr(8) + '|';
					if (document.getElementById('mult_s_'+document.getElementsByName('mult_sel_itr')[a_s_m].id.substr(8)).options[0].text == '�����������') ids_itrs = ids_itrs + document.getElementsByName('mult_sel_itr')[a_s_m].id.substr(8) + '|';
				}
				if (obj.options[obj.selectedIndex].text == '�������'){
					if ((obj.parentNode.parentNode.cells[7].innerText == cur_fio_us) && (document.getElementById('mult_s_'+document.getElementsByName('mult_sel_itr')[a_s_m].id.substr(8)).options[0].text == '���������')) ids_itrs = ids_itrs + document.getElementsByName('mult_sel_itr')[a_s_m].id.substr(8) + '|';
				}
				if (obj.options[obj.selectedIndex].text == '�� ���������'){
					if ((obj.parentNode.parentNode.cells[7].innerText == cur_fio_us) && (document.getElementById('mult_s_'+document.getElementsByName('mult_sel_itr')[a_s_m].id.substr(8)).options[0].text == '���������')) ids_itrs = ids_itrs + document.getElementsByName('mult_sel_itr')[a_s_m].id.substr(8) + '|';
					if ((obj.parentNode.parentNode.cells[6].innerText == cur_fio_us) && (document.getElementById('mult_s_'+document.getElementsByName('mult_sel_itr')[a_s_m].id.substr(8)).options[0].text == '�������')) ids_itrs = ids_itrs + document.getElementsByName('mult_sel_itr')[a_s_m].id.substr(8) + '|';
				}
			}
		}
		if (ch_m_s_true>1){
			if (confirm('�������� ����� 1 �������, ���� � ��� �� ���������� ���� �� ��������� ������� �� ���, ��� �� ������, �� ������ ��� ������� ��������� �������!\\n\\n����������?\\n')){
//				alert(ids_itrs + ' = ' + obj.options[obj.selectedIndex].text);
				if ((obj.options[obj.selectedIndex].text == '������������') || (obj.options[obj.selectedIndex].text == '���������')){
					var spl_ids = ids_itrs.split('|');
					spl_ids.pop();
					for (var s_f_i = 0; s_f_i < spl_ids.length; s_f_i++){
						document.getElementById('mult_s_'+spl_ids[s_f_i]).parentNode.parentNode.style.display = 'none';
					}
				}
				set_new_stat(ids_itrs, obj.options[obj.selectedIndex].text, cur_fio_us);
			}else{
				location.href = document.location;
			}
		}else{
			if ((obj.options[obj.selectedIndex].text == '������������') || (obj.options[obj.selectedIndex].text == '���������')) obj.parentNode.parentNode.style.display = 'none';
//			alert(ids_itrs + ' = ' + obj.options[obj.selectedIndex].text + ' = ' + ch_m_s_true + ' = (2)');
			set_new_stat(ids_itrs, obj.options[obj.selectedIndex].text, cur_fio_us);
		}
	}else{
		obj.options[0].selected = 'true';
	}
}
function set_new_stat(ids_itrs, n_stat, cur_fio_us){
		var req = getXmlHttp();
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if(req.status == 200) {
					location.href = document.location;
				}
			}
		}

		req.open('GET', 'project/itrzadan_mult_status.php?p1='+ids_itrs+'&p2='+n_stat+'&p3='+cur_fio_us, true);
		req.send(null);
}
</script>";
?>