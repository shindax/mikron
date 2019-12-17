// ******************************************************************************************************
function find_text_inp(val)
{
	while(document.getElementsByName('pr_cur_r_op')[0])
		expand_cur_zak(document.getElementsByName('pr_cur_r_op')[0].parentNode.parentNode.id.substr(6),document.getElementsByName('pr_cur_r_op')[0].parentNode.parentNode.rows[0].cells[0]);

	var sel_ids_zaks_nav = '';
	var cur_id_zak_for_sel = '';

	var str_names_dse = names_dse.toLowerCase();
	var str_names_dse_rep = str_names_dse.replace(new RegExp('=--=','g'),'');
	var str_names_dse_spl = str_names_dse_rep.split('|');

	var str_names_zak = nam_zak.toLowerCase();
	var str_names_zak_spl = str_names_zak.split('|');
	var str_ids_zak = ids_zak.toLowerCase();
	var str_ids_zak_spl = str_ids_zak.split('|');

	for (var str_f_z=0; str_f_z<str_names_zak_spl.length; str_f_z++)
		if(str_names_zak_spl[str_f_z].indexOf(val.toLowerCase())!==-1)
			sel_ids_zaks_nav = sel_ids_zaks_nav+'<option value='+str_ids_zak_spl[str_f_z]+'>'+str_names_zak_spl[str_f_z]+'</option>';

	var str_ids_dse = ids_dse;
	var str_ids_dse_rep = str_ids_dse.replace(new RegExp('=','g'),'');
	var str_ids_dse_spl = str_ids_dse_rep.split('|');

	var str_ch_dse = child_dse;
	var str_ch_dse_rep = str_ch_dse.replace(new RegExp('=--=','g'),'');
	var str_ch_dse_spl = str_ch_dse_rep.split('|');

	var str_obz_dse = obozs_dse;
	var str_obz_dse_rep = str_obz_dse.replace(new RegExp('=--=','g'),'');
	var str_obz_dse_spl = str_obz_dse_rep.split('|');

	var str_nam_dse = names_dse;

	var str_nam_dse_rep = str_nam_dse.replace(new RegExp('=','g'),'');
	var str_nam_dse_spl = str_nam_dse_rep.split('|');

    if(document.getElementById('nav_tekysh_3').name>0)
    {
		var spl_op_res = arr_oprs_c_r_2[document.getElementById('nav_tekysh_3').name].split('|');
		var spl_op_res_arr = [];
			for (var spl_f_ar=0; spl_f_ar<spl_op_res.length; spl_f_ar++)
				spl_op_res_arr[spl_op_res[spl_f_ar]] = spl_op_res[spl_f_ar];
	}

	var cur_zak_nam = '';
	var pred_nam_zak_f = '';
	var cur_tree_dse_nam = '';
	for (var str_f_s=0; str_f_s<(str_names_dse_spl.length-1); str_f_s++)
	{
		if(arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]].indexOf(val)!==-1)
			if(val.length>4)
				if(arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]!==pred_nam_zak_f)
				{
					check_cur_zak(arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]], document.getElementById('tbody_'+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]).rows[0].cells[0]);
					pred_nam_zak_f = arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]];
				}

		if (jv2_arr_full_tbl_5_spl[str_ids_dse_spl[str_f_s]].toLowerCase().indexOf(val.toLowerCase())!==-1)
		{
			var jv4_arr_full_tbl_1_spl = jv2_arr_full_tbl_1_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_2_spl = jv2_arr_full_tbl_2_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_3_spl = jv2_arr_full_tbl_3_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_4_spl = jv2_arr_full_tbl_4_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_5_spl = jv2_arr_full_tbl_5_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_5_1_spl = jv2_arr_full_tbl_5_1_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_6_spl = jv2_arr_full_tbl_6_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_7_spl = jv2_arr_full_tbl_7_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_8_spl = jv2_arr_full_tbl_8_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_9_spl = jv2_arr_full_tbl_9_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_10_spl = jv2_arr_full_tbl_10_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_11_spl = jv2_arr_full_tbl_11_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_12_spl = jv2_arr_full_tbl_12_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_14_spl = jv2_arr_full_tbl_14_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_15_spl = jv2_arr_full_tbl_15_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_16_spl = jv2_arr_full_tbl_16_spl[str_ids_dse_spl[str_f_s]].split('|');


			var jv4_arr_full_tbl_17_spl = jv2_arr_full_tbl_17_spl[str_ids_dse_spl[str_f_s]].split('|')
			var jv4_arr_full_tbl_18_spl = jv2_arr_full_tbl_18_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_19_spl = jv2_arr_full_tbl_19_spl[str_ids_dse_spl[str_f_s]].split('|');			

			var cur_tree_dse_find = '';
			var cur_dse_op_dse = '';
			var cur_id_op_dse = '';
			var cur_vp_op_dse = '';
			if (arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]] == cur_zak_nam) {
				var cur_tree_dse = cur_tree_dse_nam;
			}else{
				var cur_tree_dse = '';
			}
			for (var ch_t_f_o=0; ch_t_f_o < (jv4_arr_full_tbl_1_spl.length-1); ch_t_f_o++)
			{
				var clas_tr_col = 'fff';
				var clas_tr_br = 'fff';
				var clas_tr_pr = 'fff';
				var clas_tr_park = 'fff';
				var stl_b_tr_pr = '';
				var zadel_op = 0;
				var js_vp_op = '0<br>0.00';
				var js_ksz_op = '0<br>0.00';
				if (jv4_arr_full_tbl_14_spl[ch_t_f_o]>0){ clas_tr_col='ddffdd'; clas_tr_br='ddffdd'; clas_tr_pr='ddffdd'; clas_tr_park='ddffdd';}

				if(document.getElementById('nav_tekysh_3').name>0){
				if (spl_op_res_arr[jv4_arr_full_tbl_16_spl[ch_t_f_o]]) { clas_tr_pr='99ff99'; stl_b_tr_pr = 'font-size:150%; color:#13BD13;';}
				var parks_for_cur_res = document.getElementById('park_sel_cur_res').options.length;
				for (var p_f_c_r=0; p_f_c_r<parks_for_cur_res; p_f_c_r++){
					if ((document.getElementById('park_sel_cur_res').options[p_f_c_r].value !== '0')&&(document.getElementById('park_sel_cur_res').options[p_f_c_r].value !== '')){
					if (jv4_arr_full_tbl_5_1_spl[ch_t_f_o]==document.getElementById('park_sel_cur_res').options[p_f_c_r].value) { clas_tr_park='99ddff';}
					}
				}
				}

				if (jv4_arr_full_tbl_12_spl[ch_t_f_o]==1)
					clas_tr_br='ff9999';
				
				if (jv4_arr_full_tbl_15_spl[ch_t_f_o]==0)
				{

					if(jv4_arr_full_tbl_3_spl[ch_t_f_o]==''){ jv4_arr_full_tbl_3_spl[ch_t_f_o]='0';}
					if(jv4_arr_full_tbl_6_spl[ch_t_f_o]==''){ jv4_arr_full_tbl_6_spl[ch_t_f_o]='0.00';}
					if(jv4_arr_full_tbl_7_spl[ch_t_f_o]==''){ jv4_arr_full_tbl_7_spl[ch_t_f_o]='0';}
					if(jv4_arr_full_tbl_8_spl[ch_t_f_o]==''){ jv4_arr_full_tbl_8_spl[ch_t_f_o]='0.00';}
					if(jv4_arr_full_tbl_9_spl[ch_t_f_o]==''){ jv4_arr_full_tbl_9_spl[ch_t_f_o]='0';}
					if(jv4_arr_full_tbl_10_spl[ch_t_f_o]==''){ jv4_arr_full_tbl_10_spl[ch_t_f_o]='0.00';}
					var k_o_sht = (jv4_arr_full_tbl_3_spl[ch_t_f_o]-jv4_arr_full_tbl_7_spl[ch_t_f_o]-jv4_arr_full_tbl_9_spl[ch_t_f_o]);
					var k_o_nch = (jv4_arr_full_tbl_6_spl[ch_t_f_o]-jv4_arr_full_tbl_8_spl[ch_t_f_o]-jv4_arr_full_tbl_10_spl[ch_t_f_o]).toFixed(2);
					
					if (str_ids_dse_spl[str_f_s]==cur_dse_op_dse)
							zadel_op=(cur_vp_op_dse-jv4_arr_full_tbl_7_spl[ch_t_f_o]-jv4_arr_full_tbl_9_spl[ch_t_f_o]);

					if (zadel_op !== 0) 
							zadel_op='<a target="_blank" href="index.php?do=show&formid=116&&p5='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'&p6='+cur_id_op_dse+'&p3='+jv4_arr_full_tbl_7_spl[ch_t_f_o]+'&p4='+cur_vp_op_dse+'"><b>'+zadel_op+'</b></a>';

		        	let coop_count = jv3_arr_full_tbl_18_spl[ch_t_f_o] ? jv3_arr_full_tbl_18_spl[ch_t_f_o] : 0 ;
		        	let coop_items = jv3_arr_full_tbl_17_spl[ch_t_f_o] ? 1 * jv3_arr_full_tbl_17_spl[ch_t_f_o] : 0 ;
		        	let coop_horm_hours = Number( jv3_arr_full_tbl_19_spl[ch_t_f_o] ).toFixed(2);

					let loc_cnt_fact = 1 * jv4_arr_full_tbl_7_spl[ch_t_f_o] + coop_items;
					let loc_norm_hours_fact = Number( 1 * jv4_arr_full_tbl_8_spl[ch_t_f_o] + 1 * coop_horm_hours).toFixed(2);

					let coop = coop_count > 0 ? coop_count + '/' + coop_items : '';
					coop = coop_items ? coop_items : '';			

					if ((jv4_arr_full_tbl_8_spl[ch_t_f_o]>0)||(jv4_arr_full_tbl_7_spl[ch_t_f_o]>0))
						js_vp_op = '<a target="_blank" href="index.php?do=show&formid=126&p3='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'"><b><span class="count">' + loc_cnt_fact + '</span><br><span class="norm_fact_span">' + loc_norm_hours_fact + '</span></b></a>';

					if ((jv4_arr_full_tbl_9_spl[ch_t_f_o]>0)||(jv4_arr_full_tbl_10_spl[ch_t_f_o]>0)) 
						js_ksz_op = '<a target="_blank" href="index.php?do=show&formid=129&p3='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'"><b>'+jv4_arr_full_tbl_9_spl[ch_t_f_o]+'<br>'+jv4_arr_full_tbl_10_spl[ch_t_f_o]+'</b></a>';

					cur_tree_dse_find = cur_tree_dse_find + '<tr name="dse_par_'+str_ids_dse_spl[str_f_s]+'" onmouseover=this.setAttribute("style","background:#e2edfd;") onmouseout=this.setAttribute("style","background:#'+clas_tr_col+';") style="background:#'+clas_tr_col+';">'+
					'<td style="width:40px; background:#'+clas_tr_br+';" class="Field">'+jv4_arr_full_tbl_2_spl[ch_t_f_o]+'</td>'+
					'<td name="pr_cur_r_op" id="pr_cur_r_'+jv4_arr_full_tbl_16_spl[ch_t_f_o]+'" style="width:285px; background:#'+clas_tr_pr+'" class="Field">'+TXT(jv4_arr_full_tbl_4_spl[ch_t_f_o])+'</td>'+
					'<td name="pr_cur_r_park" id="park_cur_r_'+jv4_arr_full_tbl_5_1_spl[ch_t_f_o]+'" style="width:250px; background:#'+clas_tr_park+'" class="Field">'+jv4_arr_full_tbl_5_spl[ch_t_f_o]+'</td>'+
						
					'<td class="Field coop_td"><div><div><input class="add_count"/><input class="comment"/><button class="coop_send" disabled> ÓÓÔ</button></div><div><a href="#" class="coop_a  cls4">' + coop + '</a></div></div></td>'+
					'<td class="Field ord_td">'+jv4_arr_full_tbl_3_spl[ch_t_f_o]+'<br>'+jv4_arr_full_tbl_6_spl[ch_t_f_o]+'</td>'+
					'<td class="Field ord_td">'+js_vp_op+'</td>'+
					'<td class="Field ord_td">'+js_ksz_op+'</td>'+
					'<td class="Field ord_td">'+k_o_sht+'<br>'+k_o_nch+'</td>'+
					'<td class="Field ord_td">'+zadel_op+'</td>'+
					'<td style="width:225px;" class="Field"><textarea class="textarea"  onchange=vote9(this,'+jv4_arr_full_tbl_1_spl[ch_t_f_o]+',this.value); value="'+jv4_arr_full_tbl_11_spl[ch_t_f_o]+'">'+jv4_arr_full_tbl_11_spl[ch_t_f_o]+
					'</textarea><input type="button" class="ok_but" value="ok" onclick="zapr_pp(this,'+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+','+str_ids_dse_spl[str_f_s]+','+jv4_arr_full_tbl_1_spl[ch_t_f_o]+');"></td>'+
					'<td style="width:50px; background:#'+clas_tr_br+';" class="Field"><a style="cursor:pointer;" onclick="add_op_in_sz('+jv4_arr_full_tbl_1_spl[ch_t_f_o]+',this)"><b style="'+stl_b_tr_pr+'" name="pr_cur_r_op_b">>>></b></a></td>'+'</tr>';
				
				} // if (jv4_arr_full_tbl_15_spl[ch_t_f_o]==0)

				cur_dse_op_dse = str_ids_dse_spl[str_f_s];
				cur_id_op_dse = jv4_arr_full_tbl_1_spl[ch_t_f_o];
				cur_vp_op_dse = jv4_arr_full_tbl_7_spl[ch_t_f_o];

			} // for (var ch_t_f_o=0; ch_t_f_o < (jv4_arr_full_tbl_1_spl.length-1); ch_t_f_o++)

			cur_zak_nam = arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]];
			cur_tree_dse = cur_tree_dse + '<tr class="tr_lgray"><td class="Field" colspan="11"><b>'+arr2_tip_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'††'+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'††††/'+str_ch_dse_spl[str_f_s]+'††††'+str_obz_dse_spl[str_f_s]+'</b>††††'+str_nam_dse_spl[str_f_s ]+'</td></tr>'+cur_tree_dse_find;
			cur_tree_dse_nam = cur_tree_dse;

			document.getElementById('tbody_'+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]).innerHTML=
			'<tr class="tr_gray"><td class="Field" colspan="11"><img onclick="check_cur_zak('+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+',this.parentNode);" src="uses/collapse.png" class="img"><img onclick="expand_cur_zak('+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+',this.parentNode);" src="uses/expand.png" class="img"><b>'+arr2_tip_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'††'+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'</b>††††'+arr2_dsenam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'†††</td></tr>'+cur_tree_dse;

			if (sel_ids_zaks_nav.indexOf(arr2_ids_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]) == -1) 
				sel_ids_zaks_nav = sel_ids_zaks_nav+'<option value='+arr2_ids_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'>'+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'</option>';
			cur_id_zak_for_sel = arr2_ids_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]];
		
		} // if (jv2_arr_full_tbl_5_spl[str_ids_dse_spl[str_f_s]].toLowerCase().indexOf(val.toLowerCase())!==-1)

		if(str_names_dse_spl[str_f_s].indexOf(val.toLowerCase())!==-1)
		{
			var jv4_arr_full_tbl_1_spl = jv2_arr_full_tbl_1_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_2_spl = jv2_arr_full_tbl_2_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_3_spl = jv2_arr_full_tbl_3_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_4_spl = jv2_arr_full_tbl_4_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_5_spl = jv2_arr_full_tbl_5_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_5_1_spl = jv2_arr_full_tbl_5_1_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_6_spl = jv2_arr_full_tbl_6_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_7_spl = jv2_arr_full_tbl_7_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_8_spl = jv2_arr_full_tbl_8_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_9_spl = jv2_arr_full_tbl_9_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_10_spl = jv2_arr_full_tbl_10_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_11_spl = jv2_arr_full_tbl_11_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_12_spl = jv2_arr_full_tbl_12_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_14_spl = jv2_arr_full_tbl_14_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_15_spl = jv2_arr_full_tbl_15_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_16_spl = jv2_arr_full_tbl_16_spl[str_ids_dse_spl[str_f_s]].split('|');

			var jv4_arr_full_tbl_17_spl = jv2_arr_full_tbl_17_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_18_spl = jv2_arr_full_tbl_18_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_19_spl = jv2_arr_full_tbl_19_spl[str_ids_dse_spl[str_f_s]].split('|');

			var cur_tree_dse_find = '';
			var cur_dse_op_dse = '';
			var cur_id_op_dse = '';
			var cur_vp_op_dse = '';
			if (arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]] == cur_zak_nam) {
				var cur_tree_dse = cur_tree_dse_nam;
			}else{
				var cur_tree_dse = '';
			}
			for (var ch_t_f_o=0; ch_t_f_o < (jv4_arr_full_tbl_1_spl.length-1); ch_t_f_o++)
			{
				var clas_tr_col = 'fff';
				var clas_tr_br = 'fff';
				var clas_tr_pr = 'fff';
				var clas_tr_park = 'fff';
				var stl_b_tr_pr = '';
				var zadel_op = 0;
				var js_vp_op = '0<br>0.00';
				var js_ksz_op = '0<br>0.00';
				if (jv4_arr_full_tbl_14_spl[ch_t_f_o]>0){ clas_tr_col='ddffdd'; clas_tr_br='ddffdd'; clas_tr_pr='ddffdd'; clas_tr_park='ddffdd';}

				if(document.getElementById('nav_tekysh_3').name>0){
				if (spl_op_res_arr[jv4_arr_full_tbl_16_spl[ch_t_f_o]]) { clas_tr_pr='99ff99'; stl_b_tr_pr = 'font-size:150%; color:#13BD13;';}
				var parks_for_cur_res = document.getElementById('park_sel_cur_res').options.length;
				for (var p_f_c_r=0; p_f_c_r<parks_for_cur_res; p_f_c_r++){
					if ((document.getElementById('park_sel_cur_res').options[p_f_c_r].value !== '0')&&(document.getElementById('park_sel_cur_res').options[p_f_c_r].value !== '')){
					if (jv4_arr_full_tbl_5_1_spl[ch_t_f_o]==document.getElementById('park_sel_cur_res').options[p_f_c_r].value) { clas_tr_park='99ddff';}
					}
				}
				}

				if (jv4_arr_full_tbl_12_spl[ch_t_f_o]==1){ clas_tr_br='ff9999';}
				if (jv4_arr_full_tbl_15_spl[ch_t_f_o]==0)
				{

					if(jv4_arr_full_tbl_3_spl[ch_t_f_o]==''){ jv4_arr_full_tbl_3_spl[ch_t_f_o]='0';}
					if(jv4_arr_full_tbl_6_spl[ch_t_f_o]==''){ jv4_arr_full_tbl_6_spl[ch_t_f_o]='0.00';}
					if(jv4_arr_full_tbl_7_spl[ch_t_f_o]==''){ jv4_arr_full_tbl_7_spl[ch_t_f_o]='0';}
					if(jv4_arr_full_tbl_8_spl[ch_t_f_o]==''){ jv4_arr_full_tbl_8_spl[ch_t_f_o]='0.00';}
					if(jv4_arr_full_tbl_9_spl[ch_t_f_o]==''){ jv4_arr_full_tbl_9_spl[ch_t_f_o]='0';}
					if(jv4_arr_full_tbl_10_spl[ch_t_f_o]==''){ jv4_arr_full_tbl_10_spl[ch_t_f_o]='0.00';}
					var k_o_sht = (jv4_arr_full_tbl_3_spl[ch_t_f_o]-jv4_arr_full_tbl_7_spl[ch_t_f_o]-jv4_arr_full_tbl_9_spl[ch_t_f_o]);
					var k_o_nch = (jv4_arr_full_tbl_6_spl[ch_t_f_o]-jv4_arr_full_tbl_8_spl[ch_t_f_o]-jv4_arr_full_tbl_10_spl[ch_t_f_o]).toFixed(2);

					if (str_ids_dse_spl[str_f_s]==cur_dse_op_dse)
						zadel_op=(cur_vp_op_dse-jv4_arr_full_tbl_7_spl[ch_t_f_o]-jv4_arr_full_tbl_9_spl[ch_t_f_o]);

					if (zadel_op !== 0) 
							zadel_op='<a target="_blank" href="index.php?do=show&formid=116&&p5='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'&p6='+cur_id_op_dse+'&p3='+jv4_arr_full_tbl_7_spl[ch_t_f_o]+'&p4='+cur_vp_op_dse+'"><b>'+zadel_op+'</b></a>';

		        	let coop_count = jv3_arr_full_tbl_18_spl[ch_t_f_o] ? jv3_arr_full_tbl_18_spl[ch_t_f_o] : 0 ;
		        	let coop_items = jv3_arr_full_tbl_17_spl[ch_t_f_o] ? 1 * jv3_arr_full_tbl_17_spl[ch_t_f_o] : 0 ;
		        	let coop_horm_hours = jv3_arr_full_tbl_19_spl[ch_t_f_o] ? Number( jv3_arr_full_tbl_19_spl[ch_t_f_o] ).toFixed(2) : 0 ;

					let loc_cnt_fact = 1 * jv4_arr_full_tbl_7_spl[ch_t_f_o] + coop_items;
					let loc_norm_hours_fact = Number( 1 * jv4_arr_full_tbl_8_spl[ch_t_f_o] + 1 * coop_horm_hours).toFixed(2);

					let coop = coop_count > 0 ? coop_count + '/' + coop_items : '';
					coop = coop_items ? coop_items : '';

					if ((jv4_arr_full_tbl_8_spl[ch_t_f_o]>0)||(jv4_arr_full_tbl_7_spl[ch_t_f_o]>0))
					{ 
						js_vp_op = '<a target="_blank" href="index.php?do=show&formid=126&p3='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'"><b><span class="count">' + loc_cnt_fact + '</span><br><span class="norm_fact_span">' + loc_norm_hours_fact + '</span></b></a>';}


						if ((jv4_arr_full_tbl_9_spl[ch_t_f_o]>0)||(jv4_arr_full_tbl_10_spl[ch_t_f_o]>0)) { js_ksz_op = '<a target="_blank" href="index.php?do=show&formid=129&p3='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'"><b>'+jv4_arr_full_tbl_9_spl[ch_t_f_o]+'<br>'+jv4_arr_full_tbl_10_spl[ch_t_f_o]+'</b></a>';}

						cur_tree_dse_find = cur_tree_dse_find + '<tr name="dse_par_'+str_ids_dse_spl[str_f_s]+'" onmouseover=this.setAttribute("style","background:#e2edfd;") onmouseout=this.setAttribute("style","background:#'+clas_tr_col+';") style="background:#'+clas_tr_col+';">'+
						'<td style="width:40px; background:#'+clas_tr_br+';" class="Field">'+jv4_arr_full_tbl_2_spl[ch_t_f_o]+'</td>'+
						'<td name="pr_cur_r_op" id="pr_cur_r_'+jv4_arr_full_tbl_16_spl[ch_t_f_o]+'" style="width:285px; background:#'+clas_tr_pr+'" class="Field">'+TXT(jv4_arr_full_tbl_4_spl[ch_t_f_o])+'</td>'+
						'<td name="pr_cur_r_park" id="park_cur_r_'+jv4_arr_full_tbl_5_1_spl[ch_t_f_o]+'" style="width:250px; background:#'+clas_tr_park+'" class="Field">'+jv4_arr_full_tbl_5_spl[ch_t_f_o]+'</td>'+
						
						'<td class="Field coop_td"><div><div><input class="add_count"/><input class="comment"/><button class="coop_send" disabled> ÓÓÔ</button></div><div><a href="#" class="coop_a  cls5">' + coop + '</a></div></div></td>'+

						'<td class="Field ord_td">'+jv4_arr_full_tbl_3_spl[ch_t_f_o]+'<br>'+jv4_arr_full_tbl_6_spl[ch_t_f_o]+'</td>'+
						'<td class="Field ord_td">'+js_vp_op+'</td>'+
						'<td class="Field ord_td">'+js_ksz_op+'</td>'+
						'<td class="Field ord_td">'+k_o_sht+'<br>'+k_o_nch+'</td>'+
						'<td class="Field ord_td">'+zadel_op+'</td>'+
						'<td style="width:225px;" class="Field"><textarea class="textarea"  onchange=vote9(this,'+jv4_arr_full_tbl_1_spl[ch_t_f_o]+',this.value); value="'+jv4_arr_full_tbl_11_spl[ch_t_f_o]+'">'+jv4_arr_full_tbl_11_spl[ch_t_f_o]+
						'</textarea><input type="button" class="ok_but" value="ok" onclick="zapr_pp(this,'+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+','+str_ids_dse_spl[str_f_s]+','+jv4_arr_full_tbl_1_spl[ch_t_f_o]+');"></td>'+
						'<td style="width:50px; background:#'+clas_tr_br+';" class="Field"><a style="cursor:pointer;" onclick="add_op_in_sz('+jv4_arr_full_tbl_1_spl[ch_t_f_o]+',this)"><b style="'+stl_b_tr_pr+'" name="pr_cur_r_op_b">>>></b></a></td>'+
						'</tr>';
						} // if (jv4_arr_full_tbl_15_spl[ch_t_f_o]==0)

						cur_dse_op_dse = str_ids_dse_spl[str_f_s];
						cur_id_op_dse = jv4_arr_full_tbl_1_spl[ch_t_f_o];
						cur_vp_op_dse = jv4_arr_full_tbl_7_spl[ch_t_f_o];
					
					} // for (var ch_t_f_o=0; ch_t_f_o < (jv4_arr_full_tbl_1_spl.length-1); ch_t_f_o++)

					cur_zak_nam = arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]];
					cur_tree_dse = cur_tree_dse + '<tr class="tr_lgray"><td class="Field" colspan="11"><b>'+arr2_tip_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'††'+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'††††/'+str_ch_dse_spl[str_f_s]+'††††'+str_obz_dse_spl[str_f_s]+'</b>††††'+str_nam_dse_spl[str_f_s ]+'</td></tr>'+cur_tree_dse_find;
					cur_tree_dse_nam = cur_tree_dse;

					document.getElementById('tbody_'+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]).innerHTML=
					'<tr class="tr_gray"><td class="Field" colspan="11"><img onclick="check_cur_zak('+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+',this.parentNode);" src="uses/collapse.png" class="img"><img onclick="expand_cur_zak('+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+',this.parentNode);" src="uses/expand.png" class="img"><b>'+arr2_tip_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'††'+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'</b>††††'+arr2_dsenam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'†††</td></tr>'+cur_tree_dse;
					
					if (sel_ids_zaks_nav.indexOf(arr2_ids_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]) == -1) 
						sel_ids_zaks_nav = sel_ids_zaks_nav+'<option value='+arr2_ids_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'>'+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'</option>';

					cur_id_zak_for_sel = arr2_ids_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]];

		} // if(str_names_dse_spl[str_f_s].indexOf(val.toLowerCase())!==-1)

		if(str_obz_dse_spl[str_f_s].indexOf(val.toLowerCase())!==-1)
		{
			var jv4_arr_full_tbl_1_spl = jv2_arr_full_tbl_1_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_2_spl = jv2_arr_full_tbl_2_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_3_spl = jv2_arr_full_tbl_3_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_4_spl = jv2_arr_full_tbl_4_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_5_spl = jv2_arr_full_tbl_5_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_5_1_spl = jv2_arr_full_tbl_5_1_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_6_spl = jv2_arr_full_tbl_6_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_7_spl = jv2_arr_full_tbl_7_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_8_spl = jv2_arr_full_tbl_8_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_9_spl = jv2_arr_full_tbl_9_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_10_spl = jv2_arr_full_tbl_10_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_11_spl = jv2_arr_full_tbl_11_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_12_spl = jv2_arr_full_tbl_12_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_14_spl = jv2_arr_full_tbl_14_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_15_spl = jv2_arr_full_tbl_15_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_16_spl = jv2_arr_full_tbl_16_spl[str_ids_dse_spl[str_f_s]].split('|');

			var jv4_arr_full_tbl_17_spl = jv2_arr_full_tbl_17_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_18_spl = jv2_arr_full_tbl_18_spl[str_ids_dse_spl[str_f_s]].split('|');
			var jv4_arr_full_tbl_19_spl = jv2_arr_full_tbl_19_spl[str_ids_dse_spl[str_f_s]].split('|');

			var cur_tree_dse_find = '';
			var cur_dse_op_dse = '';
			var cur_id_op_dse = '';
			var cur_vp_op_dse = '';
			
			if (arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]] == cur_zak_nam) 
			{
				var cur_tree_dse = cur_tree_dse_nam;
			}
				else
					{
						var cur_tree_dse = '';
					}
			for (var ch_t_f_o=0; ch_t_f_o < (jv4_arr_full_tbl_1_spl.length-1); ch_t_f_o++)
			{
				var clas_tr_col = 'fff';
				var clas_tr_br = 'fff';
				var clas_tr_pr = 'fff';
				var clas_tr_park = 'fff';
				var zadel_op = 0;
				var stl_b_tr_pr = '';
				var js_vp_op = '0<br>0.00';
				var js_ksz_op = '0<br>0.00';
					if (jv4_arr_full_tbl_14_spl[ch_t_f_o]>0)
					{ 
						clas_tr_col='ddffdd'; clas_tr_br='ddffdd'; clas_tr_pr='ddffdd'; clas_tr_park='ddffdd';
					}
					if(document.getElementById('nav_tekysh_3').name>0)
					{
						if (spl_op_res_arr[jv4_arr_full_tbl_16_spl[ch_t_f_o]]) 
						{ 
							clas_tr_pr='99ff99'; stl_b_tr_pr = 'font-size:150%; color:#13BD13;';
						}
						var parks_for_cur_res = document.getElementById('park_sel_cur_res').options.length;
						for (var p_f_c_r=0; p_f_c_r<parks_for_cur_res; p_f_c_r++)
						{
							if ((document.getElementById('park_sel_cur_res').options[p_f_c_r].value !== '0')&&(document.getElementById('park_sel_cur_res').options[p_f_c_r].value !== ''))
							{
								if (jv4_arr_full_tbl_5_1_spl[ch_t_f_o]==document.getElementById('park_sel_cur_res').options[p_f_c_r].value) 
									{ 
										clas_tr_park='99ddff';
									}
							}
						}
					}

				if (jv4_arr_full_tbl_12_spl[ch_t_f_o]==1)
				{ 
					clas_tr_br='ff9999';
				}

				if (jv4_arr_full_tbl_15_spl[ch_t_f_o]==0)
				{

					if(jv4_arr_full_tbl_3_spl[ch_t_f_o]=='')
					{ 
						jv4_arr_full_tbl_3_spl[ch_t_f_o]='0';
					}
					if(jv4_arr_full_tbl_6_spl[ch_t_f_o]=='')
					{ 
						jv4_arr_full_tbl_6_spl[ch_t_f_o]='0.00';
					}
					if(jv4_arr_full_tbl_7_spl[ch_t_f_o]=='')
					{ 
						jv4_arr_full_tbl_7_spl[ch_t_f_o]='0';
					}
					if(jv4_arr_full_tbl_8_spl[ch_t_f_o]=='')
					{ 
						jv4_arr_full_tbl_8_spl[ch_t_f_o]='0.00';
					}
					if(jv4_arr_full_tbl_9_spl[ch_t_f_o]=='')
					{ 
						jv4_arr_full_tbl_9_spl[ch_t_f_o]='0';
					}
					if(jv4_arr_full_tbl_10_spl[ch_t_f_o]=='')
					{ 
						jv4_arr_full_tbl_10_spl[ch_t_f_o]='0.00';
					}


					var k_o_sht = (jv4_arr_full_tbl_3_spl[ch_t_f_o]-jv4_arr_full_tbl_7_spl[ch_t_f_o]-jv4_arr_full_tbl_9_spl[ch_t_f_o]);
					var k_o_nch = (jv4_arr_full_tbl_6_spl[ch_t_f_o]-jv4_arr_full_tbl_8_spl[ch_t_f_o]-jv4_arr_full_tbl_10_spl[ch_t_f_o]).toFixed(2);

					if (str_ids_dse_spl[str_f_s]==cur_dse_op_dse)
						zadel_op=(cur_vp_op_dse-jv4_arr_full_tbl_7_spl[ch_t_f_o]-jv4_arr_full_tbl_9_spl[ch_t_f_o]);

					if (zadel_op !== 0) 
						zadel_op='<a target="_blank" href="index.php?do=show&formid=116&&p5='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'&p6='+cur_id_op_dse+'&p3='+jv4_arr_full_tbl_7_spl[ch_t_f_o]+'&p4='+cur_vp_op_dse+'"><b>'+zadel_op+'</b></a>';

		        	let coop_count = jv4_arr_full_tbl_18_spl[ch_t_f_o] ? jv4_arr_full_tbl_18_spl[ch_t_f_o] : 0 ;

		        	let coop_items = jv4_arr_full_tbl_17_spl[ch_t_f_o] ? 1 * jv4_arr_full_tbl_17_spl[ch_t_f_o] : 0 ;
		        	
		        	let coop_horm_hours = jv4_arr_full_tbl_19_spl[ch_t_f_o] ? Number( jv4_arr_full_tbl_19_spl[ch_t_f_o] ).toFixed(2) : 0 ;

					let loc_cnt_fact = 1 * jv4_arr_full_tbl_7_spl[ch_t_f_o] + coop_items;
					let loc_norm_hours_fact = Number( 1 * jv4_arr_full_tbl_8_spl[ch_t_f_o] + 1 * coop_horm_hours).toFixed(2);

					let coop = coop_count > 0 ? coop_count + '/' + coop_items : '';
					coop = coop_items ? coop_items : '';

					if ((jv4_arr_full_tbl_8_spl[ch_t_f_o]>0)||(jv4_arr_full_tbl_7_spl[ch_t_f_o]>0))
						js_vp_op = '<a target="_blank" href="index.php?do=show&formid=126&p3='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'"><b><span class="count">' + loc_cnt_fact + '</span><br><span class="norm_fact_span">' + loc_norm_hours_fact + '</span></b></a>';
				
				 	js_vp_op = '<a data-mmm target="_blank" href="index.php?do=show&formid=126&p3='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'"><b><span class="count">'+ loc_cnt_fact +'</span><br><span class="norm_fact_span">' + loc_norm_hours_fact + '</span></b></a>';

					// js_ksz_op = '<a target="_blank" href="index.php?do=show&formid=129&p3='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'"><b>'+jv4_arr_full_tbl_9_spl[ch_t_f_o]+'<br>'+jv4_arr_full_tbl_10_spl[ch_t_f_o]+'</b></a>';

					cur_tree_dse_find = cur_tree_dse_find 
					+ '<tr class="tr_oper" data-zak-id="' + arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]] + '" data-id="' + jv4_arr_full_tbl_1_spl[ch_t_f_o] + '" name="dse_par_' + str_ids_dse_spl[str_f_s] + '" onmouseover=this.setAttribute("style","background:#e2edfd;") onmouseout=this.setAttribute("style","background:#'+clas_tr_col+';") style="background:#'+clas_tr_col+';">'
					+
					'<td style="width:40px; background:#' + clas_tr_br + ';" class="Field">'
					+	"<div class='ord_div'>" 
					+   "<div><span>&nbsp;</span></div>"
					+   "<div><span>"
					+	jv4_arr_full_tbl_2_spl[ch_t_f_o]
					+   "</span></div>"
					+   "<div><span>&nbsp;</span></div>"
					+	"</div>" 
					+	'</td>'
					+	'<td name="pr_cur_r_op" id="pr_cur_r_'+jv4_arr_full_tbl_16_spl[ch_t_f_o]+'" style="width:285px; background:#'+clas_tr_pr+'" class="Field">'+TXT(jv4_arr_full_tbl_4_spl[ch_t_f_o])+'</td>'+
					'<td name="pr_cur_r_park" id="park_cur_r_'+jv4_arr_full_tbl_5_1_spl[ch_t_f_o]+'" style="width:250px; background:#'+clas_tr_park+'" class="Field">'+jv4_arr_full_tbl_5_spl[ch_t_f_o]+'</td>'+
					'<td class="Field coop_td"><div><div><input class="add_count"/><input class="comment"/><button class="coop_send" disabled> ÓÓÔ</button></div><div><a href="#" class="coop_a cls6">' + coop + '</a></div></div></td>'+

					'<td class="Field ord_td"><span class="total_count">'+jv4_arr_full_tbl_3_spl[ch_t_f_o]+'</span><br><span class="norm_hours">'+jv4_arr_full_tbl_6_spl[ch_t_f_o]+'</span></td>'+
					'<td class="Field ord_td">'+js_vp_op+'</td>'+
					'<td class="Field ord_td">'+js_ksz_op+'</td>'+
					'<td class="Field ord_td">'+k_o_sht+'<br>'+k_o_nch+'</td>'+
					'<td class="Field ord_td">'+zadel_op+'</td>'+
					'<td style="width:225px;" class="Field"><textarea class="textarea" onchange=vote9(this,'+jv4_arr_full_tbl_1_spl[ch_t_f_o]+',this.value); value="'+jv4_arr_full_tbl_11_spl[ch_t_f_o]+'">'+jv4_arr_full_tbl_11_spl[ch_t_f_o]+
					'</textarea><input type="button" class="ok_but" value="ok" onclick="zapr_pp(this,'+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+','+str_ids_dse_spl[str_f_s]+','+jv4_arr_full_tbl_1_spl[ch_t_f_o]+');"></td>'+
					'<td style="width:50px; background:#'+clas_tr_br+';" class="Field"><a style="cursor:pointer;" onclick="add_op_in_sz('+jv4_arr_full_tbl_1_spl[ch_t_f_o]+',this)"><b style="'+stl_b_tr_pr+'" name="pr_cur_r_op_b">>>></b></a></td>'+
					'</tr>';
				}
				cur_dse_op_dse = str_ids_dse_spl[str_f_s];
				cur_id_op_dse = jv4_arr_full_tbl_1_spl[ch_t_f_o];
				cur_vp_op_dse = jv4_arr_full_tbl_7_spl[ch_t_f_o];
			
			} // for (var ch_t_f_o=0; ch_t_f_o < (jv4_arr_full_tbl_1_spl.length-1); ch_t_f_o++)

			cur_zak_nam = arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]];

			cur_tree_dse = cur_tree_dse + '<tr class="tr_lgray"><td class="Field" colspan="11"><b>'+arr2_tip_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'††'+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'††††/'+str_ch_dse_spl[str_f_s]+'††††'+str_obz_dse_spl[str_f_s]+'</b>††††'+str_nam_dse_spl[ str_f_s  ]+'</td></tr>'+cur_tree_dse_find;
			cur_tree_dse_nam = cur_tree_dse;

			document.getElementById('tbody_'+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]).innerHTML=
			'<tr class="tr_gray"><td class="Field" colspan="11"><img onclick="check_cur_zak('+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+',this.parentNode);" src="uses/collapse.png" img="img"><img onclick="expand_cur_zak('+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+',this.parentNode);" src="uses/expand.png" class="img"><b>'+arr2_tip_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'††'+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'</b>††††'+arr2_dsenam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'†††</td></tr>'+cur_tree_dse;

			if (sel_ids_zaks_nav.indexOf(arr2_ids_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]) == -1) 
				sel_ids_zaks_nav = sel_ids_zaks_nav+'<option value='+arr2_ids_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'>'+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'</option>';

			cur_id_zak_for_sel = arr2_ids_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]];
		}
	}
	document.getElementById('sel_nav_ids_zaks').innerHTML=sel_ids_zaks_nav;
	document.getElementById('sel_nav_ids_zaks').setAttribute('onclick','location.href="#tbody_"+document.getElementById("sel_nav_ids_zaks").value;afterLoad();');

  afterLoad();
} // function find_text_inp(val)