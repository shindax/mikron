// ******************************************************************************************************
function check_cur_zak(id_zak, obj)
{
	obj.getElementsByTagName('img')[0].style.display='none';
	obj.getElementsByTagName('img')[1].style.display='block';
	var ch_ids_tree_dse = arr2_ids_dse[id_zak].split('|');
	var ch_names_tree_dse = arr2_names_dse[id_zak].split('|');
	var ch_obozs_tree_dse = arr2_obozs_dse[id_zak].split('|');
	var ch_child_tree_dse = arr2_child_dse[id_zak].split('|');
	var cur_tree_dse = '';

	if(document.getElementById('nav_tekysh_3').name>0)
	{
		var spl_op_res = arr_oprs_c_r_2[document.getElementById('nav_tekysh_3').name].split('|');
		var spl_op_res_arr = [];

		for (var spl_f_ar=0; spl_f_ar<spl_op_res.length; spl_f_ar++)
			spl_op_res_arr[spl_op_res[spl_f_ar]] = spl_op_res[spl_f_ar];
	}

	for (var ch_t_f_d=0; ch_t_f_d < (ch_ids_tree_dse.length-1); ch_t_f_d++)
	{
		var cur_tree_oper_cur_dse = '';
		var jv3_arr_full_tbl_1_spl = jv2_arr_full_tbl_1_spl[ch_ids_tree_dse[ch_t_f_d]].split('|');
		var jv3_arr_full_tbl_2_spl = jv2_arr_full_tbl_2_spl[ch_ids_tree_dse[ch_t_f_d]].split('|');
		var jv3_arr_full_tbl_3_spl = jv2_arr_full_tbl_3_spl[ch_ids_tree_dse[ch_t_f_d]].split('|');
		var jv3_arr_full_tbl_4_spl = jv2_arr_full_tbl_4_spl[ch_ids_tree_dse[ch_t_f_d]].split('|');
		var jv3_arr_full_tbl_5_spl = jv2_arr_full_tbl_5_spl[ch_ids_tree_dse[ch_t_f_d]].split('|');
		var jv3_arr_full_tbl_5_1_spl = jv2_arr_full_tbl_5_1_spl[ch_ids_tree_dse[ch_t_f_d]].split('|');
		var jv3_arr_full_tbl_6_spl = jv2_arr_full_tbl_6_spl[ch_ids_tree_dse[ch_t_f_d]].split('|');
		var jv3_arr_full_tbl_7_spl = jv2_arr_full_tbl_7_spl[ch_ids_tree_dse[ch_t_f_d]].split('|');
		var jv3_arr_full_tbl_8_spl = jv2_arr_full_tbl_8_spl[ch_ids_tree_dse[ch_t_f_d]].split('|');
		var jv3_arr_full_tbl_9_spl = jv2_arr_full_tbl_9_spl[ch_ids_tree_dse[ch_t_f_d]].split('|');
		var jv3_arr_full_tbl_10_spl = jv2_arr_full_tbl_10_spl[ch_ids_tree_dse[ch_t_f_d]].split('|');
		var jv3_arr_full_tbl_11_spl = jv2_arr_full_tbl_11_spl[ch_ids_tree_dse[ch_t_f_d]].split('|');
		var jv3_arr_full_tbl_12_spl = jv2_arr_full_tbl_12_spl[ch_ids_tree_dse[ch_t_f_d]].split('|');
		var jv3_arr_full_tbl_14_spl = jv2_arr_full_tbl_14_spl[ch_ids_tree_dse[ch_t_f_d]].split('|');
		var jv3_arr_full_tbl_15_spl = jv2_arr_full_tbl_15_spl[ch_ids_tree_dse[ch_t_f_d]].split('|');
		var jv3_arr_full_tbl_16_spl = jv2_arr_full_tbl_16_spl[ch_ids_tree_dse[ch_t_f_d]].split('|');

		jv3_arr_full_tbl_17_spl = jv2_arr_full_tbl_17_spl[ch_ids_tree_dse[ch_t_f_d]].split('|');
		jv3_arr_full_tbl_18_spl = jv2_arr_full_tbl_18_spl[ch_ids_tree_dse[ch_t_f_d]].split('|');
		jv3_arr_full_tbl_19_spl = jv2_arr_full_tbl_19_spl[ch_ids_tree_dse[ch_t_f_d]].split('|');

		var cur_dse_op_dse = '';
		var cur_id_op_dse = '';
		var cur_vp_op_dse = '';
		
		for (var ch_t_f_o=0; ch_t_f_o < (jv3_arr_full_tbl_1_spl.length-1); ch_t_f_o++)
		{
			var clas_tr_col = 'fff';
			var clas_tr_br = 'fff';
			var clas_tr_pr = 'fff';
			var clas_tr_park = 'fff';
			var zadel_op = 0;
			var stl_b_tr_pr = '';
			var js_vp_op = '0<br>0.00';
			var js_ksz_op = '0<br>0.00';
			// if (jv3_arr_full_tbl_14_spl[ch_t_f_o]>0)
			// 	{
			// 			clas_tr_col='ddffdd';
			// 			clas_tr_br='ddffdd';
			// 			clas_tr_pr='ddffdd';
			// 			clas_tr_park='ddffdd';
			// 	}

			if(document.getElementById('nav_tekysh_3').name>0)
			{
				if (spl_op_res_arr[jv3_arr_full_tbl_16_spl[ch_t_f_o]])
					{
						clas_tr_pr='99ff99';
						stl_b_tr_pr = 'font-size:150%; color:#13BD13;padding-left:5px';
					}
			var parks_for_cur_res = document.getElementById('park_sel_cur_res').options.length;
				for (var p_f_c_r=0; p_f_c_r<parks_for_cur_res; p_f_c_r++)
				{
					if ((document.getElementById('park_sel_cur_res').options[p_f_c_r].value !== '0')&&(document.getElementById('park_sel_cur_res').options[p_f_c_r].value !== ''))
					{
						if (jv3_arr_full_tbl_5_1_spl[ch_t_f_o]==document.getElementById('park_sel_cur_res').options[p_f_c_r].value)
									clas_tr_park='99ddff';
					}
				}
			}

			if (jv3_arr_full_tbl_12_spl[ch_t_f_o]==1)
					clas_tr_br='ff9999';

			if (jv3_arr_full_tbl_15_spl[ch_t_f_o]==0)
			{

				if(jv3_arr_full_tbl_3_spl[ch_t_f_o]=='')
					jv3_arr_full_tbl_3_spl[ch_t_f_o]='0';

				if(jv3_arr_full_tbl_6_spl[ch_t_f_o]=='')
					jv3_arr_full_tbl_6_spl[ch_t_f_o]='0.00';

				if(jv3_arr_full_tbl_7_spl[ch_t_f_o]=='')
					jv3_arr_full_tbl_7_spl[ch_t_f_o]='0';

				if(jv3_arr_full_tbl_8_spl[ch_t_f_o]=='')
					jv3_arr_full_tbl_8_spl[ch_t_f_o]='0.00';

				if(jv3_arr_full_tbl_9_spl[ch_t_f_o]=='')
					jv3_arr_full_tbl_9_spl[ch_t_f_o]='0';

				if(jv3_arr_full_tbl_10_spl[ch_t_f_o]=='')
					jv3_arr_full_tbl_10_spl[ch_t_f_o]='0.00';
			
				var k_o_sht = (jv3_arr_full_tbl_3_spl[ch_t_f_o]-jv3_arr_full_tbl_7_spl[ch_t_f_o]-jv3_arr_full_tbl_9_spl[ch_t_f_o]);
				var k_o_nch = (jv3_arr_full_tbl_6_spl[ch_t_f_o]-jv3_arr_full_tbl_8_spl[ch_t_f_o]-jv3_arr_full_tbl_10_spl[ch_t_f_o]).toFixed(2);

				if (ch_ids_tree_dse[ch_t_f_d]==cur_dse_op_dse)
					zadel_op=(cur_vp_op_dse-jv3_arr_full_tbl_7_spl[ch_t_f_o]-jv3_arr_full_tbl_9_spl[ch_t_f_o]);

				if (zadel_op !== 0) 
					zadel_op='<a target="_blank" href="index.php?do=show&formid=116&&p5='+jv3_arr_full_tbl_1_spl[ch_t_f_o]+'&p6='+cur_id_op_dse+'&p3='+jv3_arr_full_tbl_7_spl[ch_t_f_o]+'&p4='+cur_vp_op_dse+'"><b>'+zadel_op+'</b></a>';

		    	let coop_count = jv3_arr_full_tbl_18_spl[ch_t_f_o] ? jv3_arr_full_tbl_18_spl[ch_t_f_o] : 0 ;
		    	let coop_items = jv3_arr_full_tbl_17_spl[ch_t_f_o] ? 1 * jv3_arr_full_tbl_17_spl[ch_t_f_o] : 0;
		    	let coop_horm_hours = jv3_arr_full_tbl_19_spl[ch_t_f_o] ? Number( jv3_arr_full_tbl_19_spl[ch_t_f_o].replaceAll(',','') ).toFixed(2) : 0 ;

				let loc_cnt_fact = 1 * jv3_arr_full_tbl_7_spl[ch_t_f_o].replaceAll(',','') + coop_items;
				let loc_norm_hours_fact = Number( 1 * jv3_arr_full_tbl_8_spl[ch_t_f_o].replaceAll(',','') + 1 * coop_horm_hours).toFixed(2);
				let coop = coop_count > 0 ? coop_count + '/' + coop_items : '';
				coop = coop_items ? coop_items : '';

				js_vp_op = '<a target="_blank" href="index.php?do=show&formid=126&p3='+jv3_arr_full_tbl_1_spl[ch_t_f_o]+'"><span class="count">' + loc_cnt_fact + '</span><br><span class="norm_fact_span">' + loc_norm_hours_fact + '</span></a>';

				if ((jv3_arr_full_tbl_9_spl[ch_t_f_o]>0)||(jv3_arr_full_tbl_10_spl[ch_t_f_o]>0)) 
					js_ksz_op = '<a target="_blank" href="index.php?do=show&formid=129&p3='+jv3_arr_full_tbl_1_spl[ch_t_f_o]+'"><b>'+jv3_arr_full_tbl_9_spl[ch_t_f_o]+'<br>'+jv3_arr_full_tbl_10_spl[ch_t_f_o]+'</b></a>';



				cur_tree_oper_cur_dse += '<tr class="tr_oper" data-zak-id="' + id_zak + '" + data-id="' + jv3_arr_full_tbl_1_spl[ ch_t_f_o ] + '" name="dse_par_'+ch_ids_tree_dse[ch_t_f_d]+'" onmouseover=this.setAttribute("style","background:#e2edfd;") onmouseout=this.setAttribute("style","background:#'+clas_tr_col+';") style="background:#'+clas_tr_col+';">'+
				'<td style="width:40px; background:#'+clas_tr_br+';"class="Field AC">'
					+	"<div class='ord_div'>" 
					+   "<div><span>&nbsp;</span></div>"
					+   "<div><span>"
					+		jv3_arr_full_tbl_2_spl[ch_t_f_o]
					+   "</span></div>"
					+   "<div><span>&nbsp;</span></div>"
					+	"</div>" 
					+ 
				'</td>'+
				'<td name="pr_cur_r_op" id="pr_cur_r_'+jv3_arr_full_tbl_16_spl[ch_t_f_o]+'" style="width:285px; background:#'+clas_tr_pr+'" class="Field">'+TXT(jv3_arr_full_tbl_4_spl[ch_t_f_o])+'</td>'+
				'<td name="pr_cur_r_park" id="park_cur_r_'+jv3_arr_full_tbl_5_1_spl[ch_t_f_o]+'" style="width:250px; background:#'+clas_tr_park+'" class="Field">'+jv3_arr_full_tbl_5_spl[ch_t_f_o] + '</td>'+

				'<td class="Field coop_td"><div><div><input class="add_count"/><input class="comment"/><button class="coop_send" disabled> ÓÓÔ</button></div><div><a href="#" class="coop_a cls1">' + coop + '</a></div></div></td>'+

				'<td class="Field ord_td check_cur_zak1"><span class="total_count">'+jv3_arr_full_tbl_3_spl[ch_t_f_o]+'</span><br><span class="norm_hours">'+jv3_arr_full_tbl_6_spl[ch_t_f_o]+'</span></td>'+

				'<td class="Field ord_td check_cur_zak2">'+js_vp_op+'</td>'+
				'<td class="Field ord_td check_cur_zak3">'+js_ksz_op+'</td>'+
				'<td class="Field ord_td check_cur_zak4">'+k_o_sht+'<br>'+k_o_nch+'</td>'+
				'<td class="Field ord_td check_cur_zak5">'+zadel_op+'</td>'+
				'<td style="width:250px;" class="Field"><textarea class="textarea" onchange=vote9(this,'+jv3_arr_full_tbl_1_spl[ch_t_f_o]+',this.value); value="'+jv3_arr_full_tbl_11_spl[ch_t_f_o]+'">'+jv3_arr_full_tbl_11_spl[ch_t_f_o]+
				'</textarea><input type="button" class="ok_but" value="ok" onclick="zapr_pp(this,'+id_zak+','+ch_ids_tree_dse[ch_t_f_d]+','+jv3_arr_full_tbl_1_spl[ch_t_f_o]+');"></td>'+
				'<td style="width:50px; background:#'+clas_tr_br+';" class="Field"><a style="cursor:pointer;" onclick="add_op_in_sz('+jv3_arr_full_tbl_1_spl[ch_t_f_o]+',this)"><b style="'+stl_b_tr_pr+'" name="pr_cur_r_op_b">>>></b></a></td>'+
				'</tr>';
			} //if (jv3_arr_full_tbl_15_spl[ch_t_f_o]==0)
			cur_dse_op_dse = ch_ids_tree_dse[ch_t_f_d];
			cur_id_op_dse = jv3_arr_full_tbl_1_spl[ch_t_f_o];
			cur_vp_op_dse = jv3_arr_full_tbl_7_spl[ch_t_f_o];

		} //for (var ch_t_f_o=0; ch_t_f_o < (jv3_arr_full_tbl_1_spl.length-1); ch_t_f_o++)

	var temp_id = ch_ids_tree_dse[ ch_t_f_d ];

		cur_tree_dse = cur_tree_dse + 
		'<tr class="dse tr_lgray" name="dse_par_'+ temp_id + '" data-id="dse_par_' + temp_id + '" data-zak-id="' + id_zak +'" data-draw="' + ch_obozs_tree_dse[ch_t_f_d] + '"><td class="Field" + colspan="11"><span></span><input title="ŒÚÔ‡‚ËÚ¸ Ì‡ ÒÍÎ‡‰" class="dse_checkbox" type="checkbox" data-id="' + temp_id + '" data-name="' + ch_names_tree_dse[ch_t_f_d] + '" data-draw="' + ch_obozs_tree_dse[ch_t_f_d] + '" data-order="' + arr2_tip_zak[id_zak] + ' ' + arr2_nam_zak[id_zak] + '" /><b>'+arr2_tip_zak[id_zak]+'† '+arr2_nam_zak[id_zak]+'††††/'+ch_child_tree_dse[ch_t_f_d]+'††††'+ch_obozs_tree_dse[ch_t_f_d]+'</b>††††'+ch_names_tree_dse[ch_t_f_d]+'</td></tr>'+cur_tree_oper_cur_dse;
	} //for (var ch_t_f_d=0; ch_t_f_d < (ch_ids_tree_dse.length-1); ch_t_f_d++)

// ŒÚÔ‡‚ÎˇÂÏ Á‡ÔÓÒ
$.post(
        '/project/zadan/ajax.GetEquipmentList.php',
        {
              id_zak: id_zak
        },
               function( respond, textStaus, jqXHR )
              {
				document.getElementById('tbody_'+id_zak).innerHTML=
				'<tr class="tr_gray" id="tr_' + id_zak + '"><td class="Field" colspan="11"><img onclick="check_cur_zak('+id_zak+',this.parentNode);" src="uses/collapse.png" class="h_img"><img onclick="expand_cur_zak('+id_zak+',this.parentNode);" src="uses/expand.png" class="img">' + 
				'<select data-id="' + id_zak + '" class="park_sel">' + respond + '</select>' + '<b>'+arr2_tip_zak[id_zak]+'††'+arr2_nam_zak[id_zak]+'</b>††††'+arr2_dsenam_zak[id_zak]+'†††</td></tr>'+cur_tree_dse;

				$('select.park_sel').unbind('change').bind('change', parkSelect );
				
				afterLoad();
               },
  "html"
          );

}// function check_cur_zak(id_zak, obj)

String.prototype.replaceAll = function(search, replace){
  return this.split(search).join(replace);
}