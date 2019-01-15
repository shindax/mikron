var ids_resurses = '".$ids_resurses."';
var ids_opers_cur_r = '".$ids_opers_cur_r."';
var arr_ids_resrs = ids_resurses.split('|');
var arr_oprs_c_r = ids_opers_cur_r.split('=--=');
var arr_ids_resrs_2 = [];
var arr_oprs_c_r_2 = [];

for (var i_rsr=0; i_rsr<(arr_ids_resrs.length-1); i_rsr++)
{
	arr_ids_resrs_2[arr_ids_resrs[i_rsr]]=arr_ids_resrs[i_rsr];
	arr_oprs_c_r_2[arr_ids_resrs[i_rsr]]=arr_oprs_c_r[i_rsr];

} // for (var i_rsr=0; i_rsr<(arr_ids_resrs.length-1); i_rsr++)

var ids_zak = '".$ids_zaks."';
var ids_dse = '".$ids_dses."';
var names_dse = '".$names_dses."';
var obozs_dse = '".$obozs_dses."';
var child_dse = '".$child_dses."';
var tip_zak = '".$tips_zaks."';
var nam_zak = '".$names_zaks."';
var dsenam_zak = '".$dse_names_zaks."';

var jv_arr_full_tbl_1 = '".$arr_full_tbl_1."';
var jv_arr_full_tbl_2 = '".$arr_full_tbl_2."';
var jv_arr_full_tbl_3 = '".$arr_full_tbl_3."';
var jv_arr_full_tbl_4 = '".$arr_full_tbl_4."';
var jv_arr_full_tbl_5 = '".$arr_full_tbl_5."';
var jv_arr_full_tbl_5_1 = '".$arr_full_tbl_5_1."';
var jv_arr_full_tbl_6 = '".$arr_full_tbl_6."';
var jv_arr_full_tbl_7 = '".$arr_full_tbl_7."';
var jv_arr_full_tbl_8 = '".$arr_full_tbl_8."';
var jv_arr_full_tbl_9 = '".$arr_full_tbl_9."';
var jv_arr_full_tbl_10 = '".$arr_full_tbl_10."';
var jv_arr_full_tbl_11 = '".$arr_full_tbl_11."';
var jv_arr_full_tbl_12 = '".$arr_full_tbl_12."';
var jv_arr_full_tbl_13 = '".$arr_full_tbl_13."';
var jv_arr_full_tbl_14 = '".$arr_full_tbl_14."';
var jv_arr_full_tbl_15 = '".$arr_full_tbl_15."';
var jv_arr_full_tbl_16 = '".$arr_full_tbl_16."';
var jv_arr_full_tbl_17 = '".$arr_full_tbl_17."';
var jv_arr_full_tbl_18 = '".$arr_full_tbl_18."';
var jv_arr_full_tbl_19 = '".$arr_full_tbl_19."';	

var jv_arr_full_tbl_1_spl = jv_arr_full_tbl_1.split('=--=');
var jv_arr_full_tbl_2_spl = jv_arr_full_tbl_2.split('=--=');
var jv_arr_full_tbl_3_spl = jv_arr_full_tbl_3.split('=--=');
var jv_arr_full_tbl_4_spl = jv_arr_full_tbl_4.split('=--=');
var jv_arr_full_tbl_5_spl = jv_arr_full_tbl_5.split('=--=');
var jv_arr_full_tbl_5_1_spl = jv_arr_full_tbl_5_1.split('=--=');
var jv_arr_full_tbl_6_spl = jv_arr_full_tbl_6.split('=--=');
var jv_arr_full_tbl_7_spl = jv_arr_full_tbl_7.split('=--=');
var jv_arr_full_tbl_8_spl = jv_arr_full_tbl_8.split('=--=');
var jv_arr_full_tbl_9_spl = jv_arr_full_tbl_9.split('=--=');
var jv_arr_full_tbl_10_spl = jv_arr_full_tbl_10.split('=--=');
var jv_arr_full_tbl_11_spl = jv_arr_full_tbl_11.split('=--=');
var jv_arr_full_tbl_12_spl = jv_arr_full_tbl_12.split('=--=');
var jv_arr_full_tbl_13_spl = jv_arr_full_tbl_13.split('=--=');
var jv_arr_full_tbl_14_spl = jv_arr_full_tbl_14.split('=--=');
var jv_arr_full_tbl_15_spl = jv_arr_full_tbl_15.split('=--=');
var jv_arr_full_tbl_16_spl = jv_arr_full_tbl_16.split('=--=');
var jv_arr_full_tbl_17_spl = jv_arr_full_tbl_17.split('=--=');
var jv_arr_full_tbl_18_spl = jv_arr_full_tbl_18.split('=--=');	
var jv_arr_full_tbl_19_spl = jv_arr_full_tbl_19.split('=--=');	

var jv2_arr_full_tbl_1_spl = [];
var jv2_arr_full_tbl_2_spl = [];
var jv2_arr_full_tbl_3_spl = [];
var jv2_arr_full_tbl_4_spl = [];
var jv2_arr_full_tbl_5_spl = [];
var jv2_arr_full_tbl_5_1_spl = [];
var jv2_arr_full_tbl_6_spl = [];
var jv2_arr_full_tbl_7_spl = [];
var jv2_arr_full_tbl_8_spl = [];
var jv2_arr_full_tbl_9_spl = [];
var jv2_arr_full_tbl_10_spl = [];
var jv2_arr_full_tbl_11_spl = [];
var jv2_arr_full_tbl_12_spl = [];
var jv2_arr_full_tbl_13_spl = [];
var jv2_arr_full_tbl_14_spl = [];
var jv2_arr_full_tbl_15_spl = [];
var jv2_arr_full_tbl_16_spl = [];
var jv2_arr_full_tbl_17_spl = [];
var jv2_arr_full_tbl_18_spl = [];	
var jv2_arr_full_tbl_19_spl = [];		

for (var ar_f_2=0; ar_f_2<(jv_arr_full_tbl_13_spl.length-1); ar_f_2++)
{
	jv2_arr_full_tbl_1_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_1_spl[ar_f_2];
	jv2_arr_full_tbl_2_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_2_spl[ar_f_2];
	jv2_arr_full_tbl_3_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_3_spl[ar_f_2];
	jv2_arr_full_tbl_4_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_4_spl[ar_f_2];
	jv2_arr_full_tbl_5_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_5_spl[ar_f_2];
	jv2_arr_full_tbl_5_1_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_5_1_spl[ar_f_2];
	jv2_arr_full_tbl_6_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_6_spl[ar_f_2];
	jv2_arr_full_tbl_7_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_7_spl[ar_f_2];
	jv2_arr_full_tbl_8_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_8_spl[ar_f_2];
	jv2_arr_full_tbl_9_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_9_spl[ar_f_2];
	jv2_arr_full_tbl_10_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_10_spl[ar_f_2];
	jv2_arr_full_tbl_11_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_11_spl[ar_f_2];
	jv2_arr_full_tbl_12_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_12_spl[ar_f_2];
	jv2_arr_full_tbl_14_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_14_spl[ar_f_2];
	jv2_arr_full_tbl_15_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_15_spl[ar_f_2];
	jv2_arr_full_tbl_16_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_16_spl[ar_f_2];
	jv2_arr_full_tbl_17_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_17_spl[ar_f_2];
	jv2_arr_full_tbl_18_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_18_spl[ar_f_2];
	jv2_arr_full_tbl_19_spl[jv_arr_full_tbl_13_spl[ar_f_2]] = jv_arr_full_tbl_19_spl[ar_f_2];	
} // for (var ar_f_2=0; ar_f_2<(jv_arr_full_tbl_13_spl.length-1); ar_f_2++)

var arr1_ids_zak = ids_zak.split('|');
var arr1_ids_dse = ids_dse.split('=');
var arr1_names_dse = names_dse.split('=--=');
var arr1_obozs_dse = obozs_dse.split('=--=');
var arr1_child_dse = child_dse.split('=--=');
var arr1_tip_zak = tip_zak.split('|');
var arr1_nam_zak = nam_zak.split('|');
var arr1_dsenam_zak = dsenam_zak.split('|');
var arr2_ids_zak = [];
var arr2_ids_dse = [];
var arr2_ids_dse_ch = [];
var arr2_names_dse = [];
var arr2_obozs_dse = [];
var arr2_child_dse = [];
var arr2_tip_zak = [];
var arr2_nam_zak = [];
var arr2_dsenam_zak = [];

for (var ar_f_1=0; ar_f_1<(arr1_ids_zak.length-1); ar_f_1++)
{
	arr2_ids_dse[arr1_ids_zak[ar_f_1]] = arr1_ids_dse[ar_f_1];
	arr2_ids_zak[arr1_ids_zak[ar_f_1]] = arr1_ids_zak[ar_f_1];
	arr2_names_dse[arr1_ids_zak[ar_f_1]] = arr1_names_dse[ar_f_1];
	arr2_obozs_dse[arr1_ids_zak[ar_f_1]] = arr1_obozs_dse[ar_f_1];
	arr2_child_dse[arr1_ids_zak[ar_f_1]] = arr1_child_dse[ar_f_1];
	arr2_tip_zak[arr1_ids_zak[ar_f_1]] = arr1_tip_zak[ar_f_1];
	arr2_nam_zak[arr1_ids_zak[ar_f_1]] = arr1_nam_zak[ar_f_1];
	arr2_dsenam_zak[arr1_ids_zak[ar_f_1]] = arr1_dsenam_zak[ar_f_1];
	var arr1_ids_dse_ch = arr1_ids_dse[ar_f_1].split('|');
	for (var ar_i_d_c=0;ar_i_d_c<(arr1_ids_dse_ch.length-1);ar_i_d_c++)
		arr2_ids_dse_ch[arr1_ids_dse_ch[ar_i_d_c]]=arr1_ids_zak[ar_f_1];
} // for (var ar_f_1=0; ar_f_1<(arr1_ids_zak.length-1); ar_f_1++)

var jv3_arr_full_tbl_17_spl = '';
var jv3_arr_full_tbl_18_spl = '';
var jv3_arr_full_tbl_19_spl = '';	

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
			if (jv3_arr_full_tbl_14_spl[ch_t_f_o]>0)
				{
						clas_tr_col='ddffdd';
						clas_tr_br='ddffdd';
						clas_tr_pr='ddffdd';
						clas_tr_park='ddffdd';
				}

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
    	let coop_horm_hours = jv3_arr_full_tbl_19_spl[ch_t_f_o] ? Number( jv3_arr_full_tbl_19_spl[ch_t_f_o] ).toFixed(2) : 0 ;

		let loc_cnt_fact = 1 * jv3_arr_full_tbl_7_spl[ch_t_f_o] + coop_items;
		let loc_norm_hours_fact = Number( 1 * jv3_arr_full_tbl_8_spl[ch_t_f_o] + 1 * coop_horm_hours).toFixed(2);

		let coop = coop_count > 0 ? coop_count + '/' + coop_items : '';
		coop = coop_items ? coop_items : '';

		if ((jv3_arr_full_tbl_8_spl[ch_t_f_o]>0)||(jv3_arr_full_tbl_7_spl[ch_t_f_o]>0)){
			js_vp_op = '<a target="_blank" href="index.php?do=show&formid=126&p3='+jv3_arr_full_tbl_1_spl[ch_t_f_o]+'"><span class="count">' + loc_cnt_fact + '</span><br><span class="norm_fact_span">' + loc_norm_hours_fact + '</span></a>';}

			if ((jv3_arr_full_tbl_9_spl[ch_t_f_o]>0)||(jv3_arr_full_tbl_10_spl[ch_t_f_o]>0)) 
				js_ksz_op = '<a target="_blank" href="index.php?do=show&formid=129&p3='+jv3_arr_full_tbl_1_spl[ch_t_f_o]+'"><b>'+jv3_arr_full_tbl_9_spl[ch_t_f_o]+'<br>'+jv3_arr_full_tbl_10_spl[ch_t_f_o]+'</b></a>';

			cur_tree_oper_cur_dse += '<tr class="tr_oper" + data-zak-id="' + id_zak + '" + data-id="' + jv3_arr_full_tbl_1_spl[ ch_t_f_o ] + '" name="dse_par_'+ch_ids_tree_dse[ch_t_f_d]+'" onmouseover=this.setAttribute("style","background:#e2edfd;") onmouseout=this.setAttribute("style","background:#'+clas_tr_col+';") style="background:#'+clas_tr_col+';">'+
			'<td style="width:40px; background:#'+clas_tr_br+';" class="Field">'+jv3_arr_full_tbl_2_spl[ch_t_f_o]+ '</td>'+
			'<td name="pr_cur_r_op" id="pr_cur_r_'+jv3_arr_full_tbl_16_spl[ch_t_f_o]+'" style="width:285px; background:#'+clas_tr_pr+'" class="Field">'+TXT(jv3_arr_full_tbl_4_spl[ch_t_f_o])+'</td>'+
			'<td name="pr_cur_r_park" id="park_cur_r_'+jv3_arr_full_tbl_5_1_spl[ch_t_f_o]+'" style="width:250px; background:#'+clas_tr_park+'" class="Field">'+jv3_arr_full_tbl_5_spl[ch_t_f_o] + '</td>'+

			'<td class="Field coop_td"><div><div><input class="add_count"/><input class="comment"/><button class="coop_send" disabled>Кооп</button></div><div><a href="#" class="coop_a cls1">' + coop + '</a></div></div></td>'+

			'<td class="Field ord_td"><span class="total_count">'+jv3_arr_full_tbl_3_spl[ch_t_f_o]+'</span><br><span class="norm_hours">'+jv3_arr_full_tbl_6_spl[ch_t_f_o]+'</span></td>'+

			'<td class="Field ord_td">'+js_vp_op+'</td>'+
			'<td class="Field ord_td">'+js_ksz_op+'</td>'+
			'<td class="Field ord_td">'+k_o_sht+'<br>'+k_o_nch+'</td>'+
			'<td class="Field ord_td">'+zadel_op+'</td>'+
			'<td style="width:225px;" class="Field"><textarea class="textarea" onchange=vote9(this,'+jv3_arr_full_tbl_1_spl[ch_t_f_o]+',this.value); value="'+jv3_arr_full_tbl_11_spl[ch_t_f_o]+'">'+jv3_arr_full_tbl_11_spl[ch_t_f_o]+
			'</textarea><input type="button" class="ok_but" value="ok" onclick="zapr_pp(this,'+id_zak+','+ch_ids_tree_dse[ch_t_f_d]+','+jv3_arr_full_tbl_1_spl[ch_t_f_o]+');"></td>'+
			'<td style="width:50px; background:#'+clas_tr_br+';" class="Field"><a style="cursor:pointer;" onclick="add_op_in_sz('+jv3_arr_full_tbl_1_spl[ch_t_f_o]+',this)"><b style="'+stl_b_tr_pr+'" name="pr_cur_r_op_b">>>></b></a></td>'+
			'</tr>';
			}
			cur_dse_op_dse = ch_ids_tree_dse[ch_t_f_d];
			cur_id_op_dse = jv3_arr_full_tbl_1_spl[ch_t_f_o];
			cur_vp_op_dse = jv3_arr_full_tbl_7_spl[ch_t_f_o];
		}

	var temp_id = ch_ids_tree_dse[ ch_t_f_d ];

		cur_tree_dse = cur_tree_dse + 
		'<tr class="dse tr_lgray" data-id="dse_par_' + temp_id + '" + data-zak-id="' + id_zak +'" ><td class="Field" + colspan="11"><b>'+arr2_tip_zak[id_zak]+'  '+arr2_nam_zak[id_zak]+'    /'+ch_child_tree_dse[ch_t_f_d]+'    '+ch_obozs_tree_dse[ch_t_f_d]+'</b>    '+ch_names_tree_dse[ch_t_f_d]+'</td></tr>'+cur_tree_oper_cur_dse;
	}

// Отправляем запрос
$.post(
        '/project/zadan/ajax.GetEquipmentList.php',
        {
              id_zak: id_zak
        },
               function( respond, textStaus, jqXHR )
              {
				document.getElementById('tbody_'+id_zak).innerHTML=
				'<tr class="tr_gray" id="tr_' + id_zak + '"><td class="Field" colspan="11"><img onclick="check_cur_zak('+id_zak+',this.parentNode);" src="uses/collapse.png" class="h_img"><img onclick="expand_cur_zak('+id_zak+',this.parentNode);" src="uses/expand.png" class="img">' + 
				'<select data-id="' + id_zak + '" class="park_sel">' + respond + '</select>' + '<b>'+arr2_tip_zak[id_zak]+'  '+arr2_nam_zak[id_zak]+'</b>    '+arr2_dsenam_zak[id_zak]+'   </td></tr>'+cur_tree_dse;

				$('select.park_sel').unbind('change').bind('change', parkSelect );
               },
  		"html"
          );

	afterLoad();
} // function check_cur_zak(id_zak, obj)

function vote9(obj, id_oper, val_oper)
{
	var req = getXmlHttp();
	req.open('GET', 'MSG_INFO_operitems.php?id='+id_oper+'&value='+val_oper);
	req.send(null);
} // function vote9(obj, id_oper, val_oper)

function zapr_pp(obj, id_zak, id_dse, id_op)
{
	if(obj.value=="ok")
	{
		if(confirm("Послать запрос в КТО?"))
		{
			obj.parentNode.parentNode.parentNode.parentNode.parentNode.className='Field';
			obj.parentNode.parentNode.getElementsByTagName('textarea')[0].disabled=true;
			obj.style.display='none';
			vote(obj,'MSG_INFO_operitems.php?id='+id_op+'&value='+obj.parentNode.parentNode.getElementsByTagName('textarea')[0].value);
			vote(obj,'zapros_MTK_PP.php?p1='+id_op+'&p2='+id_dse+'&p3='+id_zak);
		}
	}
} // function zapr_pp(obj, id_zak, id_dse, id_op)

function expand_cur_zak(id_zak, obj)
{
	$('select[data-id=' + id_zak + ']').remove();
	obj.getElementsByTagName('img')[1].style.display='none';
	obj.getElementsByTagName('img')[0].style.display='inline';
	var c_r_b_l = (document.getElementById('tbody_'+id_zak).rows.length-1);
	for(var c_r_b_f=c_r_b_l; c_r_b_f>0; c_r_b_f--){
		document.getElementById('tbody_'+id_zak).rows[c_r_b_f].remove();
	}
} //function expand_cur_zak(id_zak, obj)

function check_cur_hav(obj)
{
	if (obj.value=='Показать сменные задания') 
	{
		if ((document.getElementById('navig_dat').value.length==10)&&(document.getElementById('navig_smen').value>0))
		{
				document.getElementById('cur_smen_sz').src='index.php?do=show&formid=158&p0='+document.getElementById('navig_dat').value.substr(0,4)+document.getElementById('navig_dat').value.substr(5,2)+document.getElementById('navig_dat').value.substr(8,2)+'&p1='+document.getElementById('navig_smen').value + '&current_resource_id=' + current_resource_id;
				document.getElementById('cur_smen_sz').style.display='block';
				obj.value='Скрыть сменные задания';
		}
			else
				alert('Выберите дату и смену в навигации!');
	} //	if (obj.value=='Показать сменные задания') 
	else
		{
			document.getElementById('cur_smen_sz').style.display='none';
			obj.value='Показать сменные задания';
		}
} // function check_cur_hav(obj)

var current_resource_id;

function ch_new_nav()
{
	if ((document.getElementById('navig_dat').value.length==10)&&(document.getElementById('navig_smen').value>0)&&(document.getElementById('sel_res_div').value>0))
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
		}	// for (var td_c_pr=0; td_c_pr<document.getElementsByName('pr_cur_r_op').length; td_c_pr++)
	
	} // 	if ((document.getElementById('navig_dat').value.length==10)&&(document.getElementById('navig_smen').value>0)&&(document.getElementById('sel_res_div').value>0))
	else
		alert('Выберите дату, смену, ресурс!');
}// function ch_new_nav()

function check_sel_park_pr(id_park)
{
	for (var td_c_pr=0; td_c_pr<document.getElementsByName('pr_cur_r_park').length; td_c_pr++){
		if ((document.getElementsByName('pr_cur_r_park')[td_c_pr].id.substr(11)==document.getElementById('park_sel_cur_res').value)&&(document.getElementById('park_sel_cur_res').value!=='0')){
			document.getElementsByName('pr_cur_r_park')[td_c_pr].style.background='#99ddff';
		}
		if (document.getElementById('park_sel_cur_res').value=='0'){
			var split_rgb_obj = document.getElementsByName('pr_cur_r_park')[td_c_pr].parentNode.style.background;
			var split_rgb_obj_repl = split_rgb_obj.replace('rgb(','');
			split_rgb_obj_repl = split_rgb_obj_repl.replace(')','');
			split_rgb_obj_repl = split_rgb_obj_repl.replace(' ','');
			split_rgb_obj_repl = split_rgb_obj_repl.replace(' ','');
			split_rgb_obj_repl = split_rgb_obj_repl.replace(' ','');
			split_rgb_obj_repl = split_rgb_obj_repl.split(',');

			if ((split_rgb_obj_repl[0]=='221')&&(split_rgb_obj_repl[1]=='255')&&(split_rgb_obj_repl[2]=='221')){
				document.getElementsByName('pr_cur_r_park')[td_c_pr].style.background='#ddffdd';
			}else{
				document.getElementsByName('pr_cur_r_park')[td_c_pr].style.background='#fff';
			}
		}
	}
}//function check_sel_park_pr(id_park)
function check_res_sz_cur()
{
	if ((document.getElementById('navig_dat').value.length == 10) && (document.getElementById('navig_smen').value !==0)){
		document.getElementById("val_res_new_nav").value = 'Ждите...';
		vote2('full_plan_sz_ch_res.php?p1='+document.getElementById('navig_dat').value.substr(0,4)+document.getElementById('navig_dat').value.substr(5,2)+document.getElementById('navig_dat').value.substr(8,2)+'&p2='+document.getElementById('navig_smen').value);
	}else{
		alert('Вы не выбрали дату или смену');
	}
}//function check_res_sz_cur()
function vote2(url)
{
	var req = getXmlHttp();
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			if(req.status == 200) {
				document.getElementById('sel_res_div').innerHTML = req.responseText;
				document.getElementById('div_res_div').style.display = 'block';
				document.getElementById('val_res_new_nav').value = 'Выбрать';
			}
		}
	}

	req.open('GET', url, true);
	req.send(null);
}//function vote2(url)
function vote3(url)
{
	var req = getXmlHttp();
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			if(req.status == 200) {
				document.getElementById('park_sel_cur_res').innerHTML = '<option value="0" selected>Список оборудований у ресурса</option>'+req.responseText;
			}
		}
	}

	req.open('GET', url, true);
	req.send(null);
}//function vote3(url)
function add_op_in_sz(id_op_add, obj)
{
	if ((document.getElementById('nav_tekysh_1').innerText!=='')&&(document.getElementById('nav_tekysh_2').innerText!=='')&&(document.getElementById('nav_tekysh_3').innerText!=='')){
	var date_cur_nav = document.getElementById('nav_tekysh_1').innerText.substr(6,4)+document.getElementById('nav_tekysh_1').innerText.substr(3,2)+document.getElementById('nav_tekysh_1').innerText.substr(0,2);

	var par_innerhtml_obj = obj.innerHTML;
	var par_innerhtml_obj_io = par_innerhtml_obj.indexOf('150%');
	if (par_innerhtml_obj_io == -1){
		par_innerhtml_obj = 0;
	}else{
		par_innerhtml_obj = 1;
	}
	obj.innerHTML = '........';

	var req = getXmlHttp();
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			if(req.status == 200) {
				obj.innerHTML = '<img src="uses/ok.png" style="cursor:pointer;">';
				obj.setAttribute('onclick','if (confirm("Вернуть?")){ del_op_in_sz('+id_op_add+',this, "'+par_innerhtml_obj+'");}');
			}
		}
	}
	req.open('GET', 'project/zadan/zadanadd.php?date='+date_cur_nav+'&smen='+document.getElementById('nav_tekysh_2').innerText+'&resurs='+document.getElementById('nav_tekysh_4').innerText+'&idoper='+id_op_add, true);
	req.send(null);

	}else{
		alert('Вы не выбрали ресурс или дату или смену.');
	}
}//function add_op_in_sz(id_op_add, obj)

function del_op_in_sz(id_op_add, obj, parhtml)
{
	if ((document.getElementById('nav_tekysh_1').innerText!=='')&&(document.getElementById('nav_tekysh_2').innerText!=='')&&(document.getElementById('nav_tekysh_3').innerText!=='')){
		var date_cur_nav = document.getElementById('nav_tekysh_1').innerText.substr(6,4)+document.getElementById('nav_tekysh_1').innerText.substr(3,2)+document.getElementById('nav_tekysh_1').innerText.substr(0,2);

		var new_obj_html = '';
		if (parhtml == 0)
			new_obj_html = '<b name="pr_cur_r_op_b">>>></b>';
		if (parhtml == 1)
			new_obj_html = '<b style="font-size:150%; color:#13BD13;" name="pr_cur_r_op_b">>>></b>';

		obj.innerHTML = '........';

		var req = getXmlHttp();
		req.onreadystatechange = function() 
		{
			if (req.readyState == 4) {
				if(req.status == 200) {
					obj.innerHTML = new_obj_html;
					obj.setAttribute('onclick','add_op_in_sz('+id_op_add+',this)');
				}
			}
		}
		req.open('GET', 'project/zadan/zadandel.php?date='+date_cur_nav+'&smen='+document.getElementById('nav_tekysh_2').innerText+'&resurs='+document.getElementById('nav_tekysh_4').innerText+'&idoper='+id_op_add, true);
		req.send(null);

	}
	else
		alert('Вы не выбрали ресурс или дату или смену.');
} // function del_op_in_sz(id_op_add, obj, parhtml)

var globalpass = 0 ;
var prevstr = '' ;

function find_text_inp2(val)
{
  if( val == prevstr )
    return ;
  
  prevstr = val ;
  globalpass ++;

  var pass = 1 ;

while(document.getElementsByName('pr_cur_r_op')[0])
{
expand_cur_zak(document.getElementsByName('pr_cur_r_op')[0].parentNode.parentNode.id.substr(6),document.getElementsByName('pr_cur_r_op')[0].parentNode.parentNode.rows[0].cells[0]);
}

var sel_ids_zaks_nav = '';
var cur_id_zak_for_sel = '';

var str_names_dse = names_dse.toLowerCase();
var str_names_dse_rep = str_names_dse.replace(new RegExp('=--=','g'),'');
var str_names_dse_spl = str_names_dse_rep.split('|');

var str_names_zak = nam_zak.toLowerCase();
var str_names_zak_spl = str_names_zak.split('|');
var str_ids_zak = ids_zak.toLowerCase();
var str_ids_zak_spl = str_ids_zak.split('|');

var val_lower_case = val.toLowerCase();

for (var str_f_z=0; str_f_z<str_names_zak_spl.length; str_f_z++)
{

if(str_names_zak_spl[str_f_z].indexOf( val_lower_case )!==-1){
	sel_ids_zaks_nav = sel_ids_zaks_nav+'<option value='+str_ids_zak_spl[str_f_z]+'>'+str_names_zak_spl[str_f_z]+'</option>';
}
}

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
var spl_op_res_arr = [];

if(document.getElementById('nav_tekysh_3').name>0)
{
var spl_op_res = arr_oprs_c_r_2[document.getElementById('nav_tekysh_3').name].split('|');

for (var spl_f_ar=0; spl_f_ar<spl_op_res.length; spl_f_ar++)
	spl_op_res_arr[spl_op_res[spl_f_ar]] = spl_op_res[spl_f_ar];
}

var cur_zak_nam = '';
var pred_nam_zak_f = '';
var cur_tree_dse_nam = '';

for ( var str_f_s=0; str_f_s < str_names_dse_spl.length-1 ; str_f_s++)
{
// var len = str_names_dse_spl.length-1 ;
// debug( str_f_s + ' of ' + len );

if(arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]].indexOf(val)!==-1)
{
 if(val.length>6)
	if(arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]!==pred_nam_zak_f)
		{
			check_cur_zak(arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]], document.getElementById('tbody_'+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]).rows[0].cells[0]);
			pred_nam_zak_f = arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]];
		}
}

if (jv2_arr_full_tbl_5_spl[str_ids_dse_spl[str_f_s]].toLowerCase().indexOf( val_lower_case )!==-1)
{
	// Здесь хранятся id записи в operitems
	var jv4_arr_full_tbl_1_spl = jv2_arr_full_tbl_1_spl[str_ids_dse_spl[str_f_s]].split('|');

	var jv4_arr_full_tbl_2_spl = jv2_arr_full_tbl_2_spl[str_ids_dse_spl[str_f_s]].split('|');
	var jv4_arr_full_tbl_3_spl = jv2_arr_full_tbl_3_spl[str_ids_dse_spl[str_f_s]].split('|');
	var jv4_arr_full_tbl_4_spl = jv2_arr_full_tbl_4_spl[str_ids_dse_spl[str_f_s]].split('|');
	var jv4_arr_full_tbl_5_spl = jv2_arr_full_tbl_5_spl[str_ids_dse_spl[str_f_s]].split('|');
	var jv4_arr_full_tbl_5_1_spl = jv2_arr_full_tbl_5_1_spl[str_ids_dse_spl[str_f_s]].split('|');
	var jv4_arr_full_tbl_6_spl = jv2_arr_full_tbl_6_spl[str_ids_dse_spl[str_f_s]].split('|');
	var jv4_arr_full_tbl_7_spl = jv2_arr_full_tbl_7_spl[str_ids_dse_spl[str_f_s]].split('|');

	// нормочасы факт
	var jv4_arr_full_tbl_8_spl = jv2_arr_full_tbl_8_spl[str_ids_dse_spl[str_f_s]].split('|');
	
	var jv4_arr_full_tbl_9_spl = jv2_arr_full_tbl_9_spl[str_ids_dse_spl[str_f_s]].split('|');
	var jv4_arr_full_tbl_10_spl = jv2_arr_full_tbl_10_spl[str_ids_dse_spl[str_f_s]].split('|');
	var jv4_arr_full_tbl_11_spl = jv2_arr_full_tbl_11_spl[str_ids_dse_spl[str_f_s]].split('|');
	var jv4_arr_full_tbl_12_spl = jv2_arr_full_tbl_12_spl[str_ids_dse_spl[str_f_s]].split('|');
	var jv4_arr_full_tbl_14_spl = jv2_arr_full_tbl_14_spl[str_ids_dse_spl[str_f_s]].split('|');
	var jv4_arr_full_tbl_15_spl = jv2_arr_full_tbl_15_spl[str_ids_dse_spl[str_f_s]].split('|');
	var jv4_arr_full_tbl_16_spl = jv2_arr_full_tbl_16_spl[str_ids_dse_spl[str_f_s]].split('|');

	var cur_tree_dse_find = '';
	var cur_dse_op_dse = '';
	var cur_id_op_dse = '';
	var cur_vp_op_dse = '';

	if (arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]] == cur_zak_nam) 
		var cur_tree_dse = cur_tree_dse_nam;
	else
		var cur_tree_dse = '';
	
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
		if (str_ids_dse_spl[str_f_s]==cur_dse_op_dse){ zadel_op=(cur_vp_op_dse-jv4_arr_full_tbl_7_spl[ch_t_f_o]-jv4_arr_full_tbl_9_spl[ch_t_f_o]);}
		if (zadel_op !== 0) { zadel_op='<a target="_blank" href="index.php?do=show&formid=116&&p5='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'&p6='+cur_id_op_dse+'&p3='+jv4_arr_full_tbl_7_spl[ch_t_f_o]+'&p4='+cur_vp_op_dse+'"><b>'+zadel_op+'</b></a>';}

//	shindax 13.11.2018
    	let coop_count = jv3_arr_full_tbl_18_spl[ch_t_f_o] ? jv3_arr_full_tbl_18_spl[ch_t_f_o] : 0 ;
    	let coop_items = jv3_arr_full_tbl_17_spl[ch_t_f_o] ? 1 * jv3_arr_full_tbl_17_spl[ch_t_f_o] : 0 ;
    	let coop_horm_hours = jv3_arr_full_tbl_19_spl[ch_t_f_o] ? Number( jv3_arr_full_tbl_19_spl[ch_t_f_o] ).toFixed(2) : 0 ;

		let loc_cnt_fact = 1 * jv4_arr_full_tbl_7_spl[ch_t_f_o] + coop_items;
		let loc_norm_hours_fact = Number( 1 * jv4_arr_full_tbl_8_spl[ch_t_f_o] + 1 * coop_horm_hours).toFixed(2);

		let coop = coop_count > 0 ? coop_count + '/' + coop_items : '';
		coop = coop_items ? coop_items : '' ;

		if ((jv4_arr_full_tbl_8_spl[ch_t_f_o]>0)||(jv4_arr_full_tbl_7_spl[ch_t_f_o]>0))
		{ js_vp_op = '<a target="_blank" href="index.php?do=show&formid=126&p3='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'"><b><span class="count">' + loc_cnt_fact + '</span><br><span class="norm_fact_span">' + loc_norm_hours_fact + '</span></b></a>';}

// shindax 13.11.2018
		// if ((jv4_arr_full_tbl_8_spl[ch_t_f_o]>0)||(jv4_arr_full_tbl_7_spl[ch_t_f_o]>0))
		// { js_vp_op = '<a target="_blank" href="index.php?do=show&formid=126&p3='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'"><b><span class="count">'+jv4_arr_full_tbl_7_spl[ch_t_f_o]+'</span><br><span class="norm_fact_span">'+jv4_arr_full_tbl_8_spl[ch_t_f_o]+'</span></b></a>';}

		if ((jv4_arr_full_tbl_9_spl[ch_t_f_o]>0)||(jv4_arr_full_tbl_10_spl[ch_t_f_o]>0)) { js_ksz_op = '<a target="_blank" href="index.php?do=show&formid=129&p3='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'"><b>'+jv4_arr_full_tbl_9_spl[ch_t_f_o]+'<br>'+jv4_arr_full_tbl_10_spl[ch_t_f_o]+'</b></a>';}


		cur_tree_dse_find = cur_tree_dse_find + '<tr name="dse_par_'+str_ids_dse_spl[str_f_s]+'" onmouseover=this.setAttribute("style","background:#e2edfd;") onmouseout=this.setAttribute("style","background:#'+clas_tr_col+';") style="background:#'+clas_tr_col+';">'+
		'<td style="width:40px; background:#'+clas_tr_br+';" class="Field">'+jv4_arr_full_tbl_2_spl[ch_t_f_o]+'</td>'+
		'<td name="pr_cur_r_op" id="pr_cur_r_'+jv4_arr_full_tbl_16_spl[ch_t_f_o]+'" style="width:285px; background:#'+clas_tr_pr+'" class="Field">'+TXT(jv4_arr_full_tbl_4_spl[ch_t_f_o])+'</td>'+
		'<td name="pr_cur_r_park" id="park_cur_r_'+jv4_arr_full_tbl_5_1_spl[ch_t_f_o]+'" style="width:250px; background:#'+clas_tr_park+'" class="Field">'+jv4_arr_full_tbl_5_spl[ch_t_f_o]+'</td>'+

		'<td class="Field coop_td"><div><div><input class="add_count"/><input class="comment"/><button class="coop_send" disabled>Кооп</button></div><div><a href="#" class="coop_a  cls2">' + coop + '</a></div></div></td>'+

	'<td class="Field ord_td">'+jv4_arr_full_tbl_3_spl[ch_t_f_o]+'<br>'+jv4_arr_full_tbl_6_spl[ch_t_f_o]+'</td>'+
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
		//cur_dse_op_dse = ch_ids_tree_dse[ch_t_f_d];
		cur_id_op_dse = jv4_arr_full_tbl_1_spl[ch_t_f_o];
		cur_vp_op_dse = jv4_arr_full_tbl_7_spl[ch_t_f_o];

	}
	cur_zak_nam = arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]];
	cur_tree_dse = cur_tree_dse + '<tr class="tr_lgray"><td class="Field" colspan="11"><b>'+arr2_tip_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'  '+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'    /'+str_ch_dse_spl[str_f_s]+'    '+str_obz_dse_spl[str_f_s]+'</b>    '+str_nam_dse_spl[str_f_s ]+'</td></tr>'+cur_tree_dse_find;
	cur_tree_dse_nam = cur_tree_dse;

	document.getElementById('tbody_'+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]).innerHTML=
	'<tr class="tr_gray"><td class="Field" colspan="11"><img onclick="check_cur_zak('+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+',this.parentNode);" src="uses/collapse.png" class="img"><img onclick="expand_cur_zak('+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+',this.parentNode);" src="uses/expand.png" class="img"><b>'+arr2_tip_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'  '+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'</b>    '+arr2_dsenam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'   </td></tr>'+cur_tree_dse;
	if (sel_ids_zaks_nav.indexOf(arr2_ids_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]) == -1) {
		sel_ids_zaks_nav = sel_ids_zaks_nav+'<option value='+arr2_ids_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'>'+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'</option>';
	}
	cur_id_zak_for_sel = arr2_ids_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]];
}

if(str_names_dse_spl[str_f_s].indexOf( val_lower_case )!==-1)
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

	var cur_tree_dse_find = '';
	var cur_dse_op_dse = '';
	var cur_id_op_dse = '';
	var cur_vp_op_dse = '';
	if (arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]] == cur_zak_nam) {
		var cur_tree_dse = cur_tree_dse_nam;
	}else{
		var cur_tree_dse = '';
	}
	for (var ch_t_f_o=0; ch_t_f_o < (jv4_arr_full_tbl_1_spl.length-1); ch_t_f_o++){
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
		if (jv4_arr_full_tbl_15_spl[ch_t_f_o]==0){

		if(jv4_arr_full_tbl_3_spl[ch_t_f_o]==''){ jv4_arr_full_tbl_3_spl[ch_t_f_o]='0';}
		if(jv4_arr_full_tbl_6_spl[ch_t_f_o]==''){ jv4_arr_full_tbl_6_spl[ch_t_f_o]='0.00';}
		if(jv4_arr_full_tbl_7_spl[ch_t_f_o]==''){ jv4_arr_full_tbl_7_spl[ch_t_f_o]='0';}
		if(jv4_arr_full_tbl_8_spl[ch_t_f_o]==''){ jv4_arr_full_tbl_8_spl[ch_t_f_o]='0.00';}
		if(jv4_arr_full_tbl_9_spl[ch_t_f_o]==''){ jv4_arr_full_tbl_9_spl[ch_t_f_o]='0';}
		if(jv4_arr_full_tbl_10_spl[ch_t_f_o]==''){ jv4_arr_full_tbl_10_spl[ch_t_f_o]='0.00';}
		var k_o_sht = (jv4_arr_full_tbl_3_spl[ch_t_f_o]-jv4_arr_full_tbl_7_spl[ch_t_f_o]-jv4_arr_full_tbl_9_spl[ch_t_f_o]);
		var k_o_nch = (jv4_arr_full_tbl_6_spl[ch_t_f_o]-jv4_arr_full_tbl_8_spl[ch_t_f_o]-jv4_arr_full_tbl_10_spl[ch_t_f_o]).toFixed(2);
		if (str_ids_dse_spl[str_f_s]==cur_dse_op_dse){ zadel_op=(cur_vp_op_dse-jv4_arr_full_tbl_7_spl[ch_t_f_o]-jv4_arr_full_tbl_9_spl[ch_t_f_o]);}
		if (zadel_op !== 0) { zadel_op='<a target="_blank" href="index.php?do=show&formid=116&&p5='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'&p6='+cur_id_op_dse+'&p3='+jv4_arr_full_tbl_7_spl[ch_t_f_o]+'&p4='+cur_vp_op_dse+'"><b>'+zadel_op+'</b></a>';}

//	shindax 13.11.2018
    	let coop_count = jv3_arr_full_tbl_18_spl[ch_t_f_o] ? jv3_arr_full_tbl_18_spl[ch_t_f_o] : 0 ;
    	let coop_items = jv3_arr_full_tbl_17_spl[ch_t_f_o] ? 1 * jv3_arr_full_tbl_17_spl[ch_t_f_o] : 0 ;
    	let coop_horm_hours = jv3_arr_full_tbl_19_spl[ch_t_f_o] ? Number( jv3_arr_full_tbl_19_spl[ch_t_f_o] ).toFixed(2) : 0 ;

		let loc_cnt_fact = 1 * jv4_arr_full_tbl_7_spl[ch_t_f_o] + coop_items;
		let loc_norm_hours_fact = Number( 1 * jv4_arr_full_tbl_8_spl[ch_t_f_o] + 1 * coop_horm_hours).toFixed(2);

		let coop = coop_count > 0 ? coop_count + '/' + coop_items : '';
		coop = coop_items ? coop_items : '' ;

		if ((jv4_arr_full_tbl_8_spl[ch_t_f_o]>0)||(jv4_arr_full_tbl_7_spl[ch_t_f_o]>0))
		{ js_vp_op = '<a target="_blank" href="index.php?do=show&formid=126&p3='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'"><b><span class="count">' + loc_cnt_fact + '</span><br><span class="norm_fact_span">' + loc_norm_hours_fact + '</span></b></a>';}
// shindax 13.11.2018
		// if ((jv4_arr_full_tbl_8_spl[ch_t_f_o]>0)||(jv4_arr_full_tbl_7_spl[ch_t_f_o]>0)) { js_vp_op = '<a target="_blank" href="index.php?do=show&formid=126&p3='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'"><b>'+jv4_arr_full_tbl_7_spl[ch_t_f_o]+'<br>'+jv4_arr_full_tbl_8_spl[ch_t_f_o]+'</b></a>';}
		
		if ((jv4_arr_full_tbl_9_spl[ch_t_f_o]>0)||(jv4_arr_full_tbl_10_spl[ch_t_f_o]>0)) { js_ksz_op = '<a target="_blank" href="index.php?do=show&formid=129&p3='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'"><b>'+jv4_arr_full_tbl_9_spl[ch_t_f_o]+'<br>'+jv4_arr_full_tbl_10_spl[ch_t_f_o]+'</b></a>';}


		cur_tree_dse_find = cur_tree_dse_find + '<tr name="dse_par_'+str_ids_dse_spl[str_f_s]+'" onmouseover=this.setAttribute("style","background:#e2edfd;") onmouseout=this.setAttribute("style","background:#'+clas_tr_col+';") style="background:#'+clas_tr_col+';">'+
		'<td style="width:40px; background:#'+clas_tr_br+';" class="Field">'+jv4_arr_full_tbl_2_spl[ch_t_f_o]+'</td>'+
		'<td name="pr_cur_r_op" id="pr_cur_r_'+jv4_arr_full_tbl_16_spl[ch_t_f_o]+'" style="width:285px; background:#'+clas_tr_pr+'" class="Field">'+TXT(jv4_arr_full_tbl_4_spl[ch_t_f_o])+'</td>'+
		'<td name="pr_cur_r_park" id="park_cur_r_'+jv4_arr_full_tbl_5_1_spl[ch_t_f_o]+'" style="width:250px; background:#'+clas_tr_park+'" class="Field">'+jv4_arr_full_tbl_5_spl[ch_t_f_o]+'</td>'+
				
		'<td class="Field coop_td"><div><div><input class="add_count"/><input class="comment"/><button class="coop_send" disabled>Кооп</button></div><div><a href="#" class="coop_a  cls3">' + coop + '</a></div></div></td>'+

		'<td class="Field ord_td">'+jv4_arr_full_tbl_3_spl[ch_t_f_o]+'<br>'+jv4_arr_full_tbl_6_spl[ch_t_f_o]+'</td>'+
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
		//cur_dse_op_dse = ch_ids_tree_dse[ch_t_f_d];
		cur_id_op_dse = jv4_arr_full_tbl_1_spl[ch_t_f_o];
		cur_vp_op_dse = jv4_arr_full_tbl_7_spl[ch_t_f_o];
	}
	cur_zak_nam = arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]];
	cur_tree_dse = cur_tree_dse + '<tr class="tr_lgray"><td class="Field" colspan="11"><b>'+arr2_tip_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'  '+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'    /'+str_ch_dse_spl[str_f_s]+'    '+str_obz_dse_spl[str_f_s]+'</b>    '+str_nam_dse_spl[str_f_s ]+'</td></tr>'+cur_tree_dse_find;
	cur_tree_dse_nam = cur_tree_dse;

	document.getElementById('tbody_'+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]).innerHTML=
	'<tr class="tr_gray"><td class="Field" colspan="11"><img onclick="check_cur_zak('+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+',this.parentNode);" src="uses/collapse.png" class="img"><img onclick="expand_cur_zak('+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+',this.parentNode);" src="uses/expand.png" class="img"><b>'+arr2_tip_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'  '+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'</b>    '+arr2_dsenam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'   </td></tr>'+cur_tree_dse;
	if (sel_ids_zaks_nav.indexOf(arr2_ids_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]) == -1) {
		sel_ids_zaks_nav = sel_ids_zaks_nav+'<option value='+arr2_ids_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'>'+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'</option>';
	}
	cur_id_zak_for_sel = arr2_ids_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]];
}

} // for ( var str_f_s=0; str_f_s< len ; str_f_s++)

document.getElementById('sel_nav_ids_zaks').innerHTML=sel_ids_zaks_nav;
document.getElementById('sel_nav_ids_zaks').setAttribute('onclick','location.href="#tbody_"+document.getElementById("sel_nav_ids_zaks").value;afterLoad();');

afterLoad();

}// function find_text_inp2(val)

function find_text_inp(val)
{
while(document.getElementsByName('pr_cur_r_op')[0]){
	expand_cur_zak(document.getElementsByName('pr_cur_r_op')[0].parentNode.parentNode.id.substr(6),document.getElementsByName('pr_cur_r_op')[0].parentNode.parentNode.rows[0].cells[0]);
}

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
{
	if(str_names_zak_spl[str_f_z].indexOf(val.toLowerCase())!==-1)
	{
		sel_ids_zaks_nav = sel_ids_zaks_nav+'<option value='+str_ids_zak_spl[str_f_z]+'>'+str_names_zak_spl[str_f_z]+'</option>';
	}
}

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
	{
		if(val.length>4)
			{
				if(arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]!==pred_nam_zak_f)
				{
					check_cur_zak(arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]], document.getElementById('tbody_'+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]).rows[0].cells[0]);
					pred_nam_zak_f = arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]];
				}
			}
	} // if(arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]].indexOf(val)!==-1)

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

		var cur_tree_dse_find = '';
		var cur_dse_op_dse = '';
		var cur_id_op_dse = '';
		var cur_vp_op_dse = '';
		
		if (arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]] == cur_zak_nam) 
			var cur_tree_dse = cur_tree_dse_nam;
				else
					var cur_tree_dse = '';

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
			if (str_ids_dse_spl[str_f_s]==cur_dse_op_dse){ zadel_op=(cur_vp_op_dse-jv4_arr_full_tbl_7_spl[ch_t_f_o]-jv4_arr_full_tbl_9_spl[ch_t_f_o]);}
			if (zadel_op !== 0) { zadel_op='<a target="_blank" href="index.php?do=show&formid=116&&p5='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'&p6='+cur_id_op_dse+'&p3='+jv4_arr_full_tbl_7_spl[ch_t_f_o]+'&p4='+cur_vp_op_dse+'"><b>'+zadel_op+'</b></a>';}

    	let coop_count = jv3_arr_full_tbl_18_spl[ch_t_f_o] ? jv3_arr_full_tbl_18_spl[ch_t_f_o] : 0 ;
    	let coop_items = jv3_arr_full_tbl_17_spl[ch_t_f_o] ? 1 * jv3_arr_full_tbl_17_spl[ch_t_f_o] : 0 ;
    	let coop_horm_hours = Number( jv3_arr_full_tbl_19_spl[ch_t_f_o] ).toFixed(2);

		let loc_cnt_fact = 1 * jv4_arr_full_tbl_7_spl[ch_t_f_o] + coop_items;
		let loc_norm_hours_fact = Number( 1 * jv4_arr_full_tbl_8_spl[ch_t_f_o] + 1 * coop_horm_hours).toFixed(2);

		let coop = coop_count > 0 ? coop_count + '/' + coop_items : '';
		coop = coop_items ? coop_items : '';			

		if ((jv4_arr_full_tbl_8_spl[ch_t_f_o]>0)||(jv4_arr_full_tbl_7_spl[ch_t_f_o]>0))
		{ js_vp_op = '<a target="_blank" href="index.php?do=show&formid=126&p3='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'"><b><span class="count">' + loc_cnt_fact + '</span><br><span class="norm_fact_span">' + loc_norm_hours_fact + '</span></b></a>';}

			if ((jv4_arr_full_tbl_9_spl[ch_t_f_o]>0)||(jv4_arr_full_tbl_10_spl[ch_t_f_o]>0)) { js_ksz_op = '<a target="_blank" href="index.php?do=show&formid=129&p3='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'"><b>'+jv4_arr_full_tbl_9_spl[ch_t_f_o]+'<br>'+jv4_arr_full_tbl_10_spl[ch_t_f_o]+'</b></a>';}


			cur_tree_dse_find = cur_tree_dse_find + '<tr name="dse_par_'+str_ids_dse_spl[str_f_s]+'" onmouseover=this.setAttribute("style","background:#e2edfd;") onmouseout=this.setAttribute("style","background:#'+clas_tr_col+';") style="background:#'+clas_tr_col+';">'+
			'<td style="width:40px; background:#'+clas_tr_br+';" class="Field">'+jv4_arr_full_tbl_2_spl[ch_t_f_o]+'</td>'+
			'<td name="pr_cur_r_op" id="pr_cur_r_'+jv4_arr_full_tbl_16_spl[ch_t_f_o]+'" style="width:285px; background:#'+clas_tr_pr+'" class="Field">'+TXT(jv4_arr_full_tbl_4_spl[ch_t_f_o])+'</td>'+
			'<td name="pr_cur_r_park" id="park_cur_r_'+jv4_arr_full_tbl_5_1_spl[ch_t_f_o]+'" style="width:250px; background:#'+clas_tr_park+'" class="Field">'+jv4_arr_full_tbl_5_spl[ch_t_f_o]+'</td>'+
				
			'<td class="Field coop_td"><div><div><input class="add_count"/><input class="comment"/><button class="coop_send" disabled>Кооп</button></div><div><a href="#" class="coop_a  cls4">' + coop + '</a></div></div></td>'+

		'<td class="Field ord_td">'+jv4_arr_full_tbl_3_spl[ch_t_f_o]+'<br>'+jv4_arr_full_tbl_6_spl[ch_t_f_o]+'</td>'+
			'<td class="Field ord_td">'+js_vp_op+'</td>'+
			'<td class="Field ord_td">'+js_ksz_op+'</td>'+
			'<td class="Field ord_td">'+k_o_sht+'<br>'+k_o_nch+'</td>'+
			'<td class="Field ord_td">'+zadel_op+'</td>'+
			'<td style="width:225px;" class="Field"><textarea class="textarea"  onchange=vote9(this,'+jv4_arr_full_tbl_1_spl[ch_t_f_o]+',this.value); value="'+jv4_arr_full_tbl_11_spl[ch_t_f_o]+'">'+jv4_arr_full_tbl_11_spl[ch_t_f_o]+
			'</textarea><input type="button" class="ok_but" value="ok" onclick="zapr_pp(this,'+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+','+str_ids_dse_spl[str_f_s]+','+jv4_arr_full_tbl_1_spl[ch_t_f_o]+');"></td>'+
			'<td style="width:50px; background:#'+clas_tr_br+';" class="Field"><a style="cursor:pointer;" onclick="add_op_in_sz('+jv4_arr_full_tbl_1_spl[ch_t_f_o]+',this)"><b style="'+stl_b_tr_pr+'" name="pr_cur_r_op_b">>>></b></a></td>'+
			'</tr>';
			}
			cur_dse_op_dse = str_ids_dse_spl[str_f_s];
			//cur_dse_op_dse = ch_ids_tree_dse[ch_t_f_d];
			cur_id_op_dse = jv4_arr_full_tbl_1_spl[ch_t_f_o];
			cur_vp_op_dse = jv4_arr_full_tbl_7_spl[ch_t_f_o];
		} // for (var ch_t_f_o=0; ch_t_f_o < (jv4_arr_full_tbl_1_spl.length-1); ch_t_f_o++)
		cur_zak_nam = arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]];
		cur_tree_dse = cur_tree_dse + '<tr class="tr_lgray"><td class="Field" colspan="11"><b>'+arr2_tip_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'  '+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'    /'+str_ch_dse_spl[str_f_s]+'    '+str_obz_dse_spl[str_f_s]+'</b>    '+str_nam_dse_spl[str_f_s ]+'</td></tr>'+cur_tree_dse_find;
		cur_tree_dse_nam = cur_tree_dse;

		document.getElementById('tbody_'+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]).innerHTML=
		'<tr class="tr_gray"><td class="Field" colspan="11"><img onclick="check_cur_zak('+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+',this.parentNode);" src="uses/collapse.png" class="img"><img onclick="expand_cur_zak('+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+',this.parentNode);" src="uses/expand.png" class="img"><b>'+arr2_tip_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'  '+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'</b>    '+arr2_dsenam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'   </td></tr>'+cur_tree_dse;
		//alert(str_f_s+' = '+str_ids_dse_spl[str_f_s]+' = '+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]);
		if (sel_ids_zaks_nav.indexOf(arr2_ids_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]) == -1) {
			sel_ids_zaks_nav = sel_ids_zaks_nav+'<option value='+arr2_ids_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'>'+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'</option>';
		}
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

		var cur_tree_dse_find = '';
		var cur_dse_op_dse = '';
		var cur_id_op_dse = '';
		var cur_vp_op_dse = '';
		if (arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]] == cur_zak_nam) 
			var cur_tree_dse = cur_tree_dse_nam;
				else
					var cur_tree_dse = '';

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
			if (jv4_arr_full_tbl_15_spl[ch_t_f_o]==0){

			if(jv4_arr_full_tbl_3_spl[ch_t_f_o]==''){ jv4_arr_full_tbl_3_spl[ch_t_f_o]='0';}
			if(jv4_arr_full_tbl_6_spl[ch_t_f_o]==''){ jv4_arr_full_tbl_6_spl[ch_t_f_o]='0.00';}
			if(jv4_arr_full_tbl_7_spl[ch_t_f_o]==''){ jv4_arr_full_tbl_7_spl[ch_t_f_o]='0';}
			if(jv4_arr_full_tbl_8_spl[ch_t_f_o]==''){ jv4_arr_full_tbl_8_spl[ch_t_f_o]='0.00';}
			if(jv4_arr_full_tbl_9_spl[ch_t_f_o]==''){ jv4_arr_full_tbl_9_spl[ch_t_f_o]='0';}
			if(jv4_arr_full_tbl_10_spl[ch_t_f_o]==''){ jv4_arr_full_tbl_10_spl[ch_t_f_o]='0.00';}
			var k_o_sht = (jv4_arr_full_tbl_3_spl[ch_t_f_o]-jv4_arr_full_tbl_7_spl[ch_t_f_o]-jv4_arr_full_tbl_9_spl[ch_t_f_o]);
			var k_o_nch = (jv4_arr_full_tbl_6_spl[ch_t_f_o]-jv4_arr_full_tbl_8_spl[ch_t_f_o]-jv4_arr_full_tbl_10_spl[ch_t_f_o]).toFixed(2);
			if (str_ids_dse_spl[str_f_s]==cur_dse_op_dse){ zadel_op=(cur_vp_op_dse-jv4_arr_full_tbl_7_spl[ch_t_f_o]-jv4_arr_full_tbl_9_spl[ch_t_f_o]);}
			if (zadel_op !== 0) { zadel_op='<a target="_blank" href="index.php?do=show&formid=116&&p5='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'&p6='+cur_id_op_dse+'&p3='+jv4_arr_full_tbl_7_spl[ch_t_f_o]+'&p4='+cur_vp_op_dse+'"><b>'+zadel_op+'</b></a>';}

	    	let coop_count = jv3_arr_full_tbl_18_spl[ch_t_f_o] ? jv3_arr_full_tbl_18_spl[ch_t_f_o] : 0 ;
	    	let coop_items = jv3_arr_full_tbl_17_spl[ch_t_f_o] ? 1 * jv3_arr_full_tbl_17_spl[ch_t_f_o] : 0 ;
	    	let coop_horm_hours = jv3_arr_full_tbl_19_spl[ch_t_f_o] ? Number( jv3_arr_full_tbl_19_spl[ch_t_f_o] ).toFixed(2) : 0 ;

			let loc_cnt_fact = 1 * jv4_arr_full_tbl_7_spl[ch_t_f_o] + coop_items;
			let loc_norm_hours_fact = Number( 1 * jv4_arr_full_tbl_8_spl[ch_t_f_o] + 1 * coop_horm_hours).toFixed(2);

			let coop = coop_count > 0 ? coop_count + '/' + coop_items : '';
			coop = coop_items ? coop_items : '';

			if ((jv4_arr_full_tbl_8_spl[ch_t_f_o]>0)||(jv4_arr_full_tbl_7_spl[ch_t_f_o]>0))
				{ js_vp_op = '<a target="_blank" href="index.php?do=show&formid=126&p3='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'"><b><span class="count">' + loc_cnt_fact + '</span><br><span class="norm_fact_span">' + loc_norm_hours_fact + '</span></b></a>';
				}

			if ((jv4_arr_full_tbl_9_spl[ch_t_f_o]>0)||(jv4_arr_full_tbl_10_spl[ch_t_f_o]>0)) 
				{ 
					js_ksz_op = '<a target="_blank" href="index.php?do=show&formid=129&p3='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'"><b>'+jv4_arr_full_tbl_9_spl[ch_t_f_o]+'<br>'+jv4_arr_full_tbl_10_spl[ch_t_f_o]+'</b></a>';
				}

			cur_tree_dse_find = cur_tree_dse_find + '<tr name="dse_par_'+str_ids_dse_spl[str_f_s]+'" onmouseover=this.setAttribute("style","background:#e2edfd;") onmouseout=this.setAttribute("style","background:#'+clas_tr_col+';") style="background:#'+clas_tr_col+';">'+
			'<td style="width:40px; background:#'+clas_tr_br+';" class="Field">'+jv4_arr_full_tbl_2_spl[ch_t_f_o]+'</td>'+
			'<td name="pr_cur_r_op" id="pr_cur_r_'+jv4_arr_full_tbl_16_spl[ch_t_f_o]+'" style="width:285px; background:#'+clas_tr_pr+'" class="Field">'+TXT(jv4_arr_full_tbl_4_spl[ch_t_f_o])+'</td>'+
			'<td name="pr_cur_r_park" id="park_cur_r_'+jv4_arr_full_tbl_5_1_spl[ch_t_f_o]+'" style="width:250px; background:#'+clas_tr_park+'" class="Field">'+jv4_arr_full_tbl_5_spl[ch_t_f_o]+'</td>'+
			
			'<td class="Field coop_td"><div><div><input class="add_count"/><input class="comment"/><button class="coop_send" disabled>Кооп</button></div><div><a href="#" class="coop_a  cls5">' + coop + '</a></div></div></td>'+

			'<td class="Field ord_td">'+jv4_arr_full_tbl_3_spl[ch_t_f_o]+'<br>'+jv4_arr_full_tbl_6_spl[ch_t_f_o]+'</td>'+
			'<td class="Field ord_td">'+js_vp_op+'</td>'+
			'<td class="Field ord_td">'+js_ksz_op+'</td>'+
			'<td class="Field ord_td">'+k_o_sht+'<br>'+k_o_nch+'</td>'+
			'<td class="Field ord_td">'+zadel_op+'</td>'+
			'<td style="width:225px;" class="Field"><textarea class="textarea"  onchange=vote9(this,'+jv4_arr_full_tbl_1_spl[ch_t_f_o]+',this.value); value="'+jv4_arr_full_tbl_11_spl[ch_t_f_o]+'">'+jv4_arr_full_tbl_11_spl[ch_t_f_o]+
			'</textarea><input type="button" class="ok_but" value="ok" onclick="zapr_pp(this,'+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+','+str_ids_dse_spl[str_f_s]+','+jv4_arr_full_tbl_1_spl[ch_t_f_o]+');"></td>'+
			'<td style="width:50px; background:#'+clas_tr_br+';" class="Field"><a style="cursor:pointer;" onclick="add_op_in_sz('+jv4_arr_full_tbl_1_spl[ch_t_f_o]+',this)"><b style="'+stl_b_tr_pr+'" name="pr_cur_r_op_b">>>></b></a></td>'+
			'</tr>';
			}
			cur_dse_op_dse = str_ids_dse_spl[str_f_s];
			//cur_dse_op_dse = ch_ids_tree_dse[ch_t_f_d];
			cur_id_op_dse = jv4_arr_full_tbl_1_spl[ch_t_f_o];
			cur_vp_op_dse = jv4_arr_full_tbl_7_spl[ch_t_f_o];
		
		} //for (var ch_t_f_o=0; ch_t_f_o < (jv4_arr_full_tbl_1_spl.length-1); ch_t_f_o++)
		
		cur_zak_nam = arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]];
		cur_tree_dse = cur_tree_dse + '<tr class="tr_lgray"><td class="Field" colspan="11"><b>'+arr2_tip_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'  '+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'    /'+str_ch_dse_spl[str_f_s]+'    '+str_obz_dse_spl[str_f_s]+'</b>    '+str_nam_dse_spl[str_f_s ]+'</td></tr>'+cur_tree_dse_find;
		cur_tree_dse_nam = cur_tree_dse;

		document.getElementById('tbody_'+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]).innerHTML=
		'<tr class="tr_gray"><td class="Field" colspan="11"><img onclick="check_cur_zak('+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+',this.parentNode);" src="uses/collapse.png" class="img"><img onclick="expand_cur_zak('+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+',this.parentNode);" src="uses/expand.png" class="img"><b>'+arr2_tip_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'  '+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'</b>    '+arr2_dsenam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'   </td></tr>'+cur_tree_dse;
		//alert(str_f_s+' = '+str_ids_dse_spl[str_f_s]+' = '+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+' = '+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]);
		//alert(cur_tree_dse);
		if (sel_ids_zaks_nav.indexOf(arr2_ids_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]) == -1) {
			sel_ids_zaks_nav = sel_ids_zaks_nav+'<option value='+arr2_ids_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'>'+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'</option>';
		}
		cur_id_zak_for_sel = arr2_ids_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]];
	
	}// if(str_names_dse_spl[str_f_s].indexOf(val.toLowerCase())!==-1)
	
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
				{ 
					zadel_op=(cur_vp_op_dse-jv4_arr_full_tbl_7_spl[ch_t_f_o]-jv4_arr_full_tbl_9_spl[ch_t_f_o]);
				}
				if (zadel_op !== 0) 
				{ 
					zadel_op='<a target="_blank" href="index.php?do=show&formid=116&&p5='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'&p6='+cur_id_op_dse+'&p3='+jv4_arr_full_tbl_7_spl[ch_t_f_o]+'&p4='+cur_vp_op_dse+'"><b>'+zadel_op+'</b></a>';
				}

    	let coop_count = jv3_arr_full_tbl_18_spl[ch_t_f_o] ? jv3_arr_full_tbl_18_spl[ch_t_f_o] : 0 ;

    	let coop_items = jv3_arr_full_tbl_17_spl[ch_t_f_o] ? 1 * jv3_arr_full_tbl_17_spl[ch_t_f_o] : 0 ;
    	let coop_horm_hours = jv3_arr_full_tbl_19_spl[ch_t_f_o] ? Number( jv3_arr_full_tbl_19_spl[ch_t_f_o] ).toFixed(2) : 0 ;

		let loc_cnt_fact = 1 * jv4_arr_full_tbl_7_spl[ch_t_f_o] + coop_items;
		let loc_norm_hours_fact = Number( 1 * jv4_arr_full_tbl_8_spl[ch_t_f_o] + 1 * coop_horm_hours).toFixed(2);

		let coop = coop_count > 0 ? coop_count + '/' + coop_items : '';
		coop = coop_items ? coop_items : '';

		if ((jv4_arr_full_tbl_8_spl[ch_t_f_o] > 0) || ( jv4_arr_full_tbl_7_spl[ch_t_f_o] > 0 ))
		{ 
			js_vp_op = '<a target="_blank" href="index.php?do=show&formid=126&p3='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'"><b><span class="count">' + loc_cnt_fact + '</span><br><span class="norm_fact_span">' + loc_norm_hours_fact + '</span></b></a>';}

			if ((jv4_arr_full_tbl_9_spl[ch_t_f_o]>0)||(jv4_arr_full_tbl_10_spl[ch_t_f_o]>0)) 
				{ 
					js_ksz_op = '<a target="_blank" href="index.php?do=show&formid=129&p3='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'"><b>'+jv4_arr_full_tbl_9_spl[ch_t_f_o]+'<br>'+jv4_arr_full_tbl_10_spl[ch_t_f_o]+'</b></a>';
				}

			cur_tree_dse_find = cur_tree_dse_find + '<tr class="tr_oper" data-id="' + jv4_arr_full_tbl_1_spl[ch_t_f_o] + '" name="dse_par_'+str_ids_dse_spl[str_f_s]+'" onmouseover=this.setAttribute("style","background:#e2edfd;") onmouseout=this.setAttribute("style","background:#'+clas_tr_col+';") style="background:#'+clas_tr_col+';">'+
			'<td style="width:40px; background:#'+clas_tr_br+';" class="Field">'+jv4_arr_full_tbl_2_spl[ch_t_f_o]+'</td>'+
			'<td name="pr_cur_r_op" id="pr_cur_r_'+jv4_arr_full_tbl_16_spl[ch_t_f_o]+'" style="width:285px; background:#'+clas_tr_pr+'" class="Field">'+TXT(jv4_arr_full_tbl_4_spl[ch_t_f_o])+'</td>'+
			'<td name="pr_cur_r_park" id="park_cur_r_'+jv4_arr_full_tbl_5_1_spl[ch_t_f_o]+'" style="width:250px; background:#'+clas_tr_park+'" class="Field">'+jv4_arr_full_tbl_5_spl[ch_t_f_o]+'</td>'+

			'<td class="Field coop_td"><div><div><input class="add_count"/><input class="comment"/><button class="coop_send" disabled>Кооп</button></div><div><a href="#" class="coop_a cls6">' + coop + '</a></div></div></td>'+

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
			//cur_dse_op_dse = ch_ids_tree_dse[ch_t_f_d];
			cur_id_op_dse = jv4_arr_full_tbl_1_spl[ch_t_f_o];
			cur_vp_op_dse = jv4_arr_full_tbl_7_spl[ch_t_f_o];
		}// for (var ch_t_f_o=0; ch_t_f_o < (jv4_arr_full_tbl_1_spl.length-1); ch_t_f_o++)

		cur_zak_nam = arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]];
		cur_tree_dse = cur_tree_dse + '<tr class="tr_lgray"><td class="Field" colspan="11"><b>'+arr2_tip_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'  '+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'    /'+str_ch_dse_spl[str_f_s]+'    '+str_obz_dse_spl[str_f_s]+'</b>    '+str_nam_dse_spl[ str_f_s  ]+'</td></tr>'+cur_tree_dse_find;
		cur_tree_dse_nam = cur_tree_dse;

		document.getElementById('tbody_'+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]).innerHTML=
		'<tr class="tr_gray"><td class="Field" colspan="11"><img onclick="check_cur_zak('+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+',this.parentNode);" src="uses/collapse.png" img="img"><img onclick="expand_cur_zak('+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+',this.parentNode);" src="uses/expand.png" class="img"><b>'+arr2_tip_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'  '+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'</b>    '+arr2_dsenam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'   </td></tr>'+cur_tree_dse;

		if (sel_ids_zaks_nav.indexOf(arr2_ids_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]) == -1) 
		{
			sel_ids_zaks_nav = sel_ids_zaks_nav+'<option value='+arr2_ids_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'>'+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'</option>';
		}
		cur_id_zak_for_sel = arr2_ids_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]];
	}// if(str_obz_dse_spl[str_f_s].indexOf(val.toLowerCase())!==-1)
}// for (var str_f_s=0; str_f_s<(str_names_dse_spl.length-1); str_f_s++)

document.getElementById('sel_nav_ids_zaks').innerHTML=sel_ids_zaks_nav;
document.getElementById('sel_nav_ids_zaks').setAttribute('onclick','location.href="#tbody_"+document.getElementById("sel_nav_ids_zaks").value;afterLoad();');

afterLoad();
