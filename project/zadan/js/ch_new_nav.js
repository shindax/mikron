// ******************************************************************************************************
function ch_new_nav()
{
	
if((document.getElementById('navig_dat').value.length==10)&&(document.getElementById('navig_smen').value>0)&&(document.getElementById('sel_res_div').value>0))
	{
		current_resource_id = document.getElementById('sel_res_div').value;

	document.getElementById('cur_res_pred_op').src='index.php?do=show&formid=159&p0='+document.getElementById('navig_dat').value.substr(0,4)+document.getElementById('navig_dat').value.substr(5,2)+document.getElementById('navig_dat').value.substr(8,2)+'&p1='+document.getElementById('navig_smen').value+'&p2='+document.getElementById('sel_res_div').value + '&current_resource_id='+current_resource_id;
		document.getElementById('cur_res_pred_op').style.display='block';

		document.getElementById('nav_tekysh_1').innerText = document.getElementById('navig_dat').value.substr(8,2)+'.'+document.getElementById('navig_dat').value.substr(5,2)+'.'+document.getElementById('navig_dat').value.substr(0,4);
		document.getElementById('nav_tekysh_2').innerText = document.getElementById('navig_smen').value;
		document.getElementById('nav_tekysh_3').innerText = document.getElementById('val_res_new_nav').value;
		document.getElementById('nav_tekysh_4').innerText = document.getElementById('sel_res_div').value;
		document.getElementById('nav_tekysh_1').name = document.getElementById('navig_dat').value.substr(0,4)+document.getElementById('navig_dat').value.substr(5,2)+document.getElementById('navig_dat').value.substr(8,2);
		document.getElementById('nav_tekysh_2').name = document.getElementById('navig_smen').value;
		document.getElementById('nav_tekysh_3').name = document.getElementById('sel_res_div').value;

		var spl_op_res = arr_oprs_c_r_2[document.getElementById('sel_res_div').value].split('|');
		var spl_op_res_arr = [];

		for (var spl_f_ar=0; spl_f_ar<spl_op_res.length; spl_f_ar++)
			spl_op_res_arr[spl_op_res[spl_f_ar]] = spl_op_res[spl_f_ar];

		document.getElementById('park_sel_cur_res').innerHTML='<option value="0" selected>Получение списка</option>';
		document.getElementById('park_sel_cur_res').setAttribute('onchange', 'check_sel_park_pr(this.value)');
		vote3('full_plan_sz_ch_park.php?p1='+document.getElementById('sel_res_div').value);


		for (var td_c_pr=0; td_c_pr<document.getElementsByName('pr_cur_r_op').length; td_c_pr++)
		{
			var split_rgb_obj = document.getElementsByName('pr_cur_r_park')[td_c_pr].parentNode.style.background;
			var split_rgb_obj_repl = split_rgb_obj.replace('rgb(','');
			split_rgb_obj_repl = split_rgb_obj_repl.replace(')','');
			split_rgb_obj_repl = split_rgb_obj_repl.replace(' ','');
			split_rgb_obj_repl = split_rgb_obj_repl.replace(' ','');
			split_rgb_obj_repl = split_rgb_obj_repl.replace(' ','');
			split_rgb_obj_repl = split_rgb_obj_repl.split(',');

			if ((split_rgb_obj_repl[0]=='221')&&(split_rgb_obj_repl[1]=='255')&&(split_rgb_obj_repl[2]=='221'))
				{
					document.getElementsByName('pr_cur_r_op')[td_c_pr].style.background='#ddffdd';
					document.getElementsByName('pr_cur_r_park')[td_c_pr].style.background='#ddffdd';
				}
					else
						{
							document.getElementsByName('pr_cur_r_op')[td_c_pr].style.background='#fff';
							document.getElementsByName('pr_cur_r_park')[td_c_pr].style.background='#fff';
						}

			if (document.getElementsByName('pr_cur_r_op')[td_c_pr].parentNode.cells[10].innerHTML.indexOf('<img') !== -1)
			{
				var id_op_add_ind_0 = document.getElementsByName('pr_cur_r_op')[td_c_pr].parentNode.cells[10].getElementsByTagName('a')[0].getAttribute('onclick').indexOf('del_op_in_sz');
				var id_op_add_ind = document.getElementsByName('pr_cur_r_op')[td_c_pr].parentNode.cells[10].getElementsByTagName('a')[0].getAttribute('onclick').indexOf('(',id_op_add_ind_0);
				var id_op_add_ind_1 = document.getElementsByName('pr_cur_r_op')[td_c_pr].parentNode.cells[10].getElementsByTagName('a')[0].getAttribute('onclick').indexOf(',',id_op_add_ind);
				var id_op_add_ind_2 = document.getElementsByName('pr_cur_r_op')[td_c_pr].parentNode.cells[10].getElementsByTagName('a')[0].getAttribute('onclick').substr((id_op_add_ind+1),(id_op_add_ind_1-id_op_add_ind-1));
				document.getElementsByName('pr_cur_r_op')[td_c_pr].parentNode.cells[10].getElementsByTagName('a')[0].innerHTML='<b name="pr_cur_r_op_b">>>></b>';
				document.getElementsByName('pr_cur_r_op')[td_c_pr].parentNode.cells[10].getElementsByTagName('a')[0].setAttribute('onclick','add_op_in_sz('+id_op_add_ind_2+',this)');
			}

			document.getElementsByName('pr_cur_r_op_b')[td_c_pr].setAttribute('style','font-size:100%; color:#23609E;');
			
			if (spl_op_res_arr[document.getElementsByName('pr_cur_r_op')[td_c_pr].id.substr(9)])
			{
				document.getElementsByName('pr_cur_r_op')[td_c_pr].style.background='#99ff99';
				document.getElementsByName('pr_cur_r_op_b')[td_c_pr].setAttribute('style','font-size:150%; color:#13BD13;');
			}
		} // for (var td_c_pr=0; td_c_pr<document.getElementsByName('pr_cur_r_op').length; td_c_pr++)
		
	} // if((document.getElementById('navig_dat').value.length==10)&&(document.getElementById('navig_smen').value>0)&&(document.getElementById('sel_res_div').value>0))
	else
		alert('Выберите дату, смену, ресурс!');
} // function ch_new_nav()
