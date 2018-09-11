 function debug( str )
  {
    console.log( str );
  }

// var time = performance.now();
 function exec_time( time )
{
	time = performance.now() - time;
	console.log('Exec time = ', time);	
}

var globalpass = 0 ;
var prevstr = '' ;

function find_text_inp(val)
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
			if (zadel_op !== 0) { zadel_op='<a target=\"_blank\" href=\"index.php?do=show&formid=116&&p5='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'&p6='+cur_id_op_dse+'&p3='+jv4_arr_full_tbl_7_spl[ch_t_f_o]+'&p4='+cur_vp_op_dse+'\"><b>'+zadel_op+'</b></a>';}
			if ((jv4_arr_full_tbl_8_spl[ch_t_f_o]>0)||(jv4_arr_full_tbl_7_spl[ch_t_f_o]>0))
			{ js_vp_op = '<a target=\"_blank\" href=\"index.php?do=show&formid=126&p3='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'\"><b><span class=\"count\">'+jv4_arr_full_tbl_7_spl[ch_t_f_o]+'</span><br><span class=\"comp_perc\">'+jv4_arr_full_tbl_8_spl[ch_t_f_o]+'</span></b></a>';}
			if ((jv4_arr_full_tbl_9_spl[ch_t_f_o]>0)||(jv4_arr_full_tbl_10_spl[ch_t_f_o]>0)) { js_ksz_op = '<a target=\"_blank\" href=\"index.php?do=show&formid=129&p3='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'\"><b>'+jv4_arr_full_tbl_9_spl[ch_t_f_o]+'<br>'+jv4_arr_full_tbl_10_spl[ch_t_f_o]+'</b></a>';}

			cur_tree_dse_find = cur_tree_dse_find + '<tr name=\"dse_par_'+str_ids_dse_spl[str_f_s]+'\" onmouseover=this.setAttribute(\"style\",\"background:#e2edfd;\") onmouseout=this.setAttribute(\"style\",\"background:#'+clas_tr_col+';\") style=\"background:#'+clas_tr_col+';\">'+
			'<td style=\"width:40px; background:#'+clas_tr_br+';\" class=\"Field\">'+jv4_arr_full_tbl_2_spl[ch_t_f_o]+'</td>'+
			'<td name=\"pr_cur_r_op\" id=\"pr_cur_r_'+jv4_arr_full_tbl_16_spl[ch_t_f_o]+'\" style=\"width:285px; background:#'+clas_tr_pr+'\" class=\"Field\">'+TXT(jv4_arr_full_tbl_4_spl[ch_t_f_o])+'</td>'+
			'<td name=\"pr_cur_r_park\" id=\"park_cur_r_'+jv4_arr_full_tbl_5_1_spl[ch_t_f_o]+'\" style=\"width:250px; background:#'+clas_tr_park+'\" class=\"Field\">'+jv4_arr_full_tbl_5_spl[ch_t_f_o]+'</td>'+
				'<td style=\"text-align:center;vertical-align:middle; width:210px;padding:0;\" class=\"Field AC\"><input class=\"add_count\"/><input class=\"comment\"/><button class=\"coop_send\" disabled>Кооп</button></td>'+

		'<td style=\"text-align:center; width:70px;\" class=\"Field\">'+jv4_arr_full_tbl_3_spl[ch_t_f_o]+'<br>'+jv4_arr_full_tbl_6_spl[ch_t_f_o]+'</td>'+
			'<td style=\"text-align:center; width:70px;\" class=\"Field\">'+js_vp_op+'</td>'+
			'<td style=\"text-align:center; width:70px;\" class=\"Field\">'+js_ksz_op+'</td>'+
			'<td style=\"text-align:center; width:70px;\" class=\"Field\">'+k_o_sht+'<br>'+k_o_nch+'</td>'+
			'<td style=\"text-align:center; width:70px;\" class=\"Field\">'+zadel_op+'</td>'+
			'<td style=\"width:225px;\" class=\"Field\"><textarea style=\"width:200px; resize:none;\" onchange=vote9(this,'+jv4_arr_full_tbl_1_spl[ch_t_f_o]+',this.value); value=\"'+jv4_arr_full_tbl_11_spl[ch_t_f_o]+'\">'+jv4_arr_full_tbl_11_spl[ch_t_f_o]+
			'</textarea><input type=\"button\" style=\"float:right; margin-right:10px; border:1px solid #444; background:#bbb; height:25px; width:25px; font-size:80%;\" value=\"ok\" onclick=\"zapr_pp(this,'+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+','+str_ids_dse_spl[str_f_s]+','+jv4_arr_full_tbl_1_spl[ch_t_f_o]+');\"></td>'+
			'<td style=\"width:50px; background:#'+clas_tr_br+';\" class=\"Field\"><a style=\"cursor:pointer;\" onclick=\"add_op_in_sz('+jv4_arr_full_tbl_1_spl[ch_t_f_o]+',this)\"><b style=\"'+stl_b_tr_pr+'\" name=\"pr_cur_r_op_b\">>>></b></a></td>'+
			'</tr>';
			}
			cur_dse_op_dse = str_ids_dse_spl[str_f_s];
			//cur_dse_op_dse = ch_ids_tree_dse[ch_t_f_d];
			cur_id_op_dse = jv4_arr_full_tbl_1_spl[ch_t_f_o];
			cur_vp_op_dse = jv4_arr_full_tbl_7_spl[ch_t_f_o];

		}
		cur_zak_nam = arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]];
		cur_tree_dse = cur_tree_dse + '<tr style=\"background:#e2edfd;\"><td class=\"Field\" colspan=\"11\"><b>'+arr2_tip_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'  '+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'    /'+str_ch_dse_spl[str_f_s]+'    '+str_obz_dse_spl[str_f_s]+'</b>    '+str_nam_dse_spl[str_f_s ]+'</td></tr>'+cur_tree_dse_find;
		cur_tree_dse_nam = cur_tree_dse;

		document.getElementById('tbody_'+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]).innerHTML=
		'<tr style=\"background:#cbdef4;\"><td class=\"Field\" colspan=\"11\"><img onclick=\"check_cur_zak('+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+',this.parentNode);\" src=\"uses/collapse.png\" style=\"cursor:pointer; width:12px;\"><img onclick=\"expand_cur_zak('+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+',this.parentNode);\" src=\"uses/expand.png\" style=\"cursor:pointer; width:12px;\"><b>'+arr2_tip_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'  '+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'</b>    '+arr2_dsenam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'   </td></tr>'+cur_tree_dse;
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
			if (zadel_op !== 0) { zadel_op='<a target=\"_blank\" href=\"index.php?do=show&formid=116&&p5='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'&p6='+cur_id_op_dse+'&p3='+jv4_arr_full_tbl_7_spl[ch_t_f_o]+'&p4='+cur_vp_op_dse+'\"><b>'+zadel_op+'</b></a>';}
			if ((jv4_arr_full_tbl_8_spl[ch_t_f_o]>0)||(jv4_arr_full_tbl_7_spl[ch_t_f_o]>0)) { js_vp_op = '<a target=\"_blank\" href=\"index.php?do=show&formid=126&p3='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'\"><b>'+jv4_arr_full_tbl_7_spl[ch_t_f_o]+'<br>'+jv4_arr_full_tbl_8_spl[ch_t_f_o]+'</b></a>';}
			if ((jv4_arr_full_tbl_9_spl[ch_t_f_o]>0)||(jv4_arr_full_tbl_10_spl[ch_t_f_o]>0)) { js_ksz_op = '<a target=\"_blank\" href=\"index.php?do=show&formid=129&p3='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'\"><b>'+jv4_arr_full_tbl_9_spl[ch_t_f_o]+'<br>'+jv4_arr_full_tbl_10_spl[ch_t_f_o]+'</b></a>';}

			cur_tree_dse_find = cur_tree_dse_find + '<tr name=\"dse_par_'+str_ids_dse_spl[str_f_s]+'\" onmouseover=this.setAttribute(\"style\",\"background:#e2edfd;\") onmouseout=this.setAttribute(\"style\",\"background:#'+clas_tr_col+';\") style=\"background:#'+clas_tr_col+';\">'+
			'<td style=\"width:40px; background:#'+clas_tr_br+';\" class=\"Field\">'+jv4_arr_full_tbl_2_spl[ch_t_f_o]+'</td>'+
			'<td name=\"pr_cur_r_op\" id=\"pr_cur_r_'+jv4_arr_full_tbl_16_spl[ch_t_f_o]+'\" style=\"width:285px; background:#'+clas_tr_pr+'\" class=\"Field\">'+TXT(jv4_arr_full_tbl_4_spl[ch_t_f_o])+'</td>'+
			'<td name=\"pr_cur_r_park\" id=\"park_cur_r_'+jv4_arr_full_tbl_5_1_spl[ch_t_f_o]+'\" style=\"width:250px; background:#'+clas_tr_park+'\" class=\"Field\">'+jv4_arr_full_tbl_5_spl[ch_t_f_o]+'</td>'+
					'<td style=\"text-align:center;vertical-align:middle; width:210px;padding:0;\" class=\"Field AC\"><input class=\"add_count\"/><input class=\"comment\"/><button class=\"coop_send\" disabled>Кооп</button></td>'+

			'<td style=\"text-align:center; width:70px;\" class=\"Field\">'+jv4_arr_full_tbl_3_spl[ch_t_f_o]+'<br>'+jv4_arr_full_tbl_6_spl[ch_t_f_o]+'</td>'+
			'<td style=\"text-align:center; width:70px;\" class=\"Field\">'+js_vp_op+'</td>'+
			'<td style=\"text-align:center; width:70px;\" class=\"Field\">'+js_ksz_op+'</td>'+
			'<td style=\"text-align:center; width:70px;\" class=\"Field\">'+k_o_sht+'<br>'+k_o_nch+'</td>'+
			'<td style=\"text-align:center; width:70px;\" class=\"Field\">'+zadel_op+'</td>'+
			'<td style=\"width:225px;\" class=\"Field\"><textarea style=\"width:200px; resize:none;\" onchange=vote9(this,'+jv4_arr_full_tbl_1_spl[ch_t_f_o]+',this.value); value=\"'+jv4_arr_full_tbl_11_spl[ch_t_f_o]+'\">'+jv4_arr_full_tbl_11_spl[ch_t_f_o]+
			'</textarea><input type=\"button\" style=\"float:right; margin-right:10px; border:1px solid #444; background:#bbb; height:25px; width:25px; font-size:80%;\" value=\"ok\" onclick=\"zapr_pp(this,'+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+','+str_ids_dse_spl[str_f_s]+','+jv4_arr_full_tbl_1_spl[ch_t_f_o]+');\"></td>'+
			'<td style=\"width:50px; background:#'+clas_tr_br+';\" class=\"Field\"><a style=\"cursor:pointer;\" onclick=\"add_op_in_sz('+jv4_arr_full_tbl_1_spl[ch_t_f_o]+',this)\"><b style=\"'+stl_b_tr_pr+'\" name=\"pr_cur_r_op_b\">>>></b></a></td>'+
			'</tr>';
			}
			cur_dse_op_dse = str_ids_dse_spl[str_f_s];
			//cur_dse_op_dse = ch_ids_tree_dse[ch_t_f_d];
			cur_id_op_dse = jv4_arr_full_tbl_1_spl[ch_t_f_o];
			cur_vp_op_dse = jv4_arr_full_tbl_7_spl[ch_t_f_o];
		}
		cur_zak_nam = arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]];
		cur_tree_dse = cur_tree_dse + '<tr style=\"background:#e2edfd;\"><td class=\"Field\" colspan=\"11\"><b>'+arr2_tip_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'  '+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'    /'+str_ch_dse_spl[str_f_s]+'    '+str_obz_dse_spl[str_f_s]+'</b>    '+str_nam_dse_spl[str_f_s ]+'</td></tr>'+cur_tree_dse_find;
		cur_tree_dse_nam = cur_tree_dse;

		document.getElementById('tbody_'+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]).innerHTML=
		'<tr style=\"background:#cbdef4;\"><td class=\"Field\" colspan=\"11\"><img onclick=\"check_cur_zak('+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+',this.parentNode);\" src=\"uses/collapse.png\" style=\"cursor:pointer; width:12px;\"><img onclick=\"expand_cur_zak('+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+',this.parentNode);\" src=\"uses/expand.png\" style=\"cursor:pointer; width:12px;\"><b>'+arr2_tip_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'  '+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'</b>    '+arr2_dsenam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'   </td></tr>'+cur_tree_dse;
		if (sel_ids_zaks_nav.indexOf(arr2_ids_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]) == -1) {
			sel_ids_zaks_nav = sel_ids_zaks_nav+'<option value='+arr2_ids_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'>'+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'</option>';
		}
		cur_id_zak_for_sel = arr2_ids_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]];
	}

// *****************************************************************************

	if(str_obz_dse_spl[str_f_s].indexOf( val_lower_case ) !== -1 )
	{
		var temp_index = str_ids_dse_spl[str_f_s];

		var jv4_arr_full_tbl_1_spl = jv2_arr_full_tbl_1_spl[temp_index].split('|');
		var jv4_arr_full_tbl_2_spl = jv2_arr_full_tbl_2_spl[temp_index].split('|');
		var jv4_arr_full_tbl_3_spl = jv2_arr_full_tbl_3_spl[temp_index].split('|');
		var jv4_arr_full_tbl_4_spl = jv2_arr_full_tbl_4_spl[temp_index].split('|');
		var jv4_arr_full_tbl_5_spl = jv2_arr_full_tbl_5_spl[temp_index].split('|');
		var jv4_arr_full_tbl_5_1_spl = jv2_arr_full_tbl_5_1_spl[temp_index].split('|');
		var jv4_arr_full_tbl_6_spl = jv2_arr_full_tbl_6_spl[temp_index].split('|');
		var jv4_arr_full_tbl_7_spl = jv2_arr_full_tbl_7_spl[temp_index].split('|');
		var jv4_arr_full_tbl_8_spl = jv2_arr_full_tbl_8_spl[temp_index].split('|');
		var jv4_arr_full_tbl_9_spl = jv2_arr_full_tbl_9_spl[temp_index].split('|');
		var jv4_arr_full_tbl_10_spl = jv2_arr_full_tbl_10_spl[temp_index].split('|');
		var jv4_arr_full_tbl_11_spl = jv2_arr_full_tbl_11_spl[temp_index].split('|');
		var jv4_arr_full_tbl_12_spl = jv2_arr_full_tbl_12_spl[temp_index].split('|');
		var jv4_arr_full_tbl_14_spl = jv2_arr_full_tbl_14_spl[temp_index].split('|');
		var jv4_arr_full_tbl_15_spl = jv2_arr_full_tbl_15_spl[temp_index].split('|');
		var jv4_arr_full_tbl_16_spl = jv2_arr_full_tbl_16_spl[temp_index].split('|');

		var cur_tree_dse_find = '';
		var cur_dse_op_dse = '';
		var cur_id_op_dse = '';
		var cur_vp_op_dse = '';

		if (arr2_nam_zak[arr2_ids_dse_ch[temp_index]] == cur_zak_nam) 
			var cur_tree_dse = cur_tree_dse_nam;
				else
					var cur_tree_dse = '';


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
				clas_tr_col=clas_tr_br=clas_tr_pr=clas_tr_park='ddffdd';

			if(document.getElementById('nav_tekysh_3').name>0)
			{
				if (spl_op_res_arr[jv4_arr_full_tbl_16_spl[ch_t_f_o]]) 
						clas_tr_pr='99ff99'; stl_b_tr_pr = 'font-size:150%; color:#13BD13;';
				var parks_for_cur_res = document.getElementById('park_sel_cur_res').options.length;
				
				for (var p_f_c_r=0; p_f_c_r<parks_for_cur_res; p_f_c_r++)
					if ((document.getElementById('park_sel_cur_res').options[p_f_c_r].value !== '0')&&(document.getElementById('park_sel_cur_res').options[p_f_c_r].value !== ''))
						if (jv4_arr_full_tbl_5_1_spl[ch_t_f_o]==document.getElementById('park_sel_cur_res').options[p_f_c_r].value) 
							clas_tr_park='99ddff';
			}

			if (jv4_arr_full_tbl_12_spl[ch_t_f_o]==1)
					clas_tr_br='ff9999';

			if (jv4_arr_full_tbl_15_spl[ch_t_f_o]==0)
			{
				if(jv4_arr_full_tbl_3_spl[ch_t_f_o]=='')
						jv4_arr_full_tbl_3_spl[ch_t_f_o]='0';
				if(jv4_arr_full_tbl_6_spl[ch_t_f_o]=='')
					jv4_arr_full_tbl_6_spl[ch_t_f_o]='0.00';
				if(jv4_arr_full_tbl_7_spl[ch_t_f_o]=='')
					jv4_arr_full_tbl_7_spl[ch_t_f_o]='0';
				if(jv4_arr_full_tbl_8_spl[ch_t_f_o]=='')
					jv4_arr_full_tbl_8_spl[ch_t_f_o]='0.00';
				if(jv4_arr_full_tbl_9_spl[ch_t_f_o]=='')
					jv4_arr_full_tbl_9_spl[ch_t_f_o]='0';
				if(jv4_arr_full_tbl_10_spl[ch_t_f_o]=='')
					jv4_arr_full_tbl_10_spl[ch_t_f_o]='0.00';
				
				var k_o_sht = (jv4_arr_full_tbl_3_spl[ch_t_f_o]-jv4_arr_full_tbl_7_spl[ch_t_f_o]-jv4_arr_full_tbl_9_spl[ch_t_f_o]);
				var k_o_nch = (jv4_arr_full_tbl_6_spl[ch_t_f_o]-jv4_arr_full_tbl_8_spl[ch_t_f_o]-jv4_arr_full_tbl_10_spl[ch_t_f_o]).toFixed(2);
				
				if (str_ids_dse_spl[str_f_s]==cur_dse_op_dse)
						zadel_op=(cur_vp_op_dse-jv4_arr_full_tbl_7_spl[ch_t_f_o]-jv4_arr_full_tbl_9_spl[ch_t_f_o]);

				if (zadel_op !== 0) 
					zadel_op='<a target=\"_blank\" href=\"index.php?do=show&formid=116&&p5='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'&p6='+cur_id_op_dse+'&p3='+jv4_arr_full_tbl_7_spl[ch_t_f_o]+'&p4='+cur_vp_op_dse+'\"><b>'+zadel_op+'</b></a>';

				if ((jv4_arr_full_tbl_8_spl[ch_t_f_o]>0)||(jv4_arr_full_tbl_7_spl[ch_t_f_o]>0))
					js_vp_op = '<a target=\"_blank\" href=\"index.php?do=show&formid=126&p3='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'\"><b><span class=\"count\">'+jv4_arr_full_tbl_7_spl[ch_t_f_o]+'</span><br><span class=\"comp_perc\">'+jv4_arr_full_tbl_8_spl[ch_t_f_o]+'</span></b></a>';

				if ((jv4_arr_full_tbl_9_spl[ch_t_f_o]>0)||(jv4_arr_full_tbl_10_spl[ch_t_f_o]>0)) 
					js_ksz_op = '<a target=\"_blank\" href=\"index.php?do=show&formid=129&p3='+jv4_arr_full_tbl_1_spl[ch_t_f_o]+'\"><b>'+jv4_arr_full_tbl_9_spl[ch_t_f_o]+'<br>'+jv4_arr_full_tbl_10_spl[ch_t_f_o]+'</b></a>';

			if( val_lower_case.length > 7 )
			cur_tree_dse_find = cur_tree_dse_find + '<tr name=\"dse_par_'+str_ids_dse_spl[str_f_s]+'\" onmouseover=this.setAttribute(\"style\",\"background:#e2edfd;\") onmouseout=this.setAttribute(\"style\",\"background:#'+clas_tr_col+';\") style=\"background:#'+clas_tr_col+';\">'+
			'<td style=\"width:40px; background:#'+clas_tr_br+';\" class=\"Field\">'+jv4_arr_full_tbl_2_spl[ch_t_f_o]+'</td>'+
			'<td name=\"pr_cur_r_op\" id=\"pr_cur_r_'+jv4_arr_full_tbl_16_spl[ch_t_f_o]+'\" style=\"width:285px; background:#'+clas_tr_pr+'\" class=\"Field\">'+TXT(jv4_arr_full_tbl_4_spl[ch_t_f_o])+'</td>'+
			'<td name=\"pr_cur_r_park\" id=\"park_cur_r_'+jv4_arr_full_tbl_5_1_spl[ch_t_f_o]+'\" style=\"width:250px; background:#'+clas_tr_park+'\" class=\"Field\">'+jv4_arr_full_tbl_5_spl[ch_t_f_o]+'</td>'+
			'<td style=\"text-align:center;vertical-align:middle; width:210px;padding:0;\" class=\"Field AC\"><input class=\"add_count\"/><input class=\"comment\"/><button class=\"coop_send\" disabled>Кооп</button></td>'+
			'<td style=\"text-align:center; width:70px;\" class=\"Field\">'+jv4_arr_full_tbl_3_spl[ch_t_f_o]+'<br>'+jv4_arr_full_tbl_6_spl[ch_t_f_o]+'</td>'+
			'<td style=\"text-align:center; width:70px;\" class=\"Field\">'+js_vp_op+'</td>'+
			'<td style=\"text-align:center; width:70px;\" class=\"Field\">'+js_ksz_op+'</td>'+
			'<td style=\"text-align:center; width:70px;\" class=\"Field\">'+k_o_sht+'<br>'+k_o_nch+'</td>'+
			'<td style=\"text-align:center; width:70px;\" class=\"Field\">'+zadel_op+'</td>'+
			'<td style=\"width:225px;\" class=\"Field\"><textarea style=\"width:200px; resize:none;\" onchange=vote9(this,'+jv4_arr_full_tbl_1_spl[ch_t_f_o]+',this.value); value=\"'+jv4_arr_full_tbl_11_spl[ch_t_f_o]+'\">'+jv4_arr_full_tbl_11_spl[ch_t_f_o]+
			'</textarea><input type=\"button\" style=\"float:right; margin-right:10px; border:1px solid #444; background:#bbb; height:25px; width:25px; font-size:80%;\" value=\"ok\" onclick=\"zapr_pp(this,'+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+','+str_ids_dse_spl[str_f_s]+','+jv4_arr_full_tbl_1_spl[ch_t_f_o]+');\"></td>'+
			'<td style=\"width:50px; background:#'+clas_tr_br+';\" class=\"Field\"><a style=\"cursor:pointer;\" onclick=\"add_op_in_sz('+jv4_arr_full_tbl_1_spl[ch_t_f_o]+',this)\"><b style=\"'+stl_b_tr_pr+'\" name=\"pr_cur_r_op_b\">>>></b></a></td>'+
			'</tr>';
			}
			
			cur_dse_op_dse = str_ids_dse_spl[str_f_s];
			cur_id_op_dse = jv4_arr_full_tbl_1_spl[ch_t_f_o];
			cur_vp_op_dse = jv4_arr_full_tbl_7_spl[ch_t_f_o];
		} // for (var ch_t_f_o=0; ch_t_f_o < (jv4_arr_full_tbl_1_spl.length-1); ch_t_f_o++)

		cur_zak_nam = arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]];
		cur_tree_dse = cur_tree_dse + '<tr style=\"background:#e2edfd;\"><td class=\"Field\" colspan=\"11\"><b>'+arr2_tip_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'  '+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'    /'+str_ch_dse_spl[str_f_s]+'    '+str_obz_dse_spl[str_f_s]+'</b>    '+str_nam_dse_spl[ str_f_s  ]+'</td></tr>'+cur_tree_dse_find;
		cur_tree_dse_nam = cur_tree_dse;

		document.getElementById('tbody_'+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]).innerHTML=
		'<tr style=\"background:#cbdef4;\"><td class=\"Field\" colspan=\"11\"><img onclick=\"check_cur_zak('+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+',this.parentNode);\" src=\"uses/collapse.png\" style=\"cursor:pointer; width:12px;\"><img onclick=\"expand_cur_zak('+arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]+',this.parentNode);\" src=\"uses/expand.png\" style=\"cursor:pointer; width:12px;\"><b>'+arr2_tip_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'  '+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'</b>    '+arr2_dsenam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'   </td></tr>'+cur_tree_dse;

		if (sel_ids_zaks_nav.indexOf(arr2_ids_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]) == -1) 
			sel_ids_zaks_nav = sel_ids_zaks_nav+'<option value='+arr2_ids_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'>'+arr2_nam_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]]+'</option>';

		cur_id_zak_for_sel = arr2_ids_zak[arr2_ids_dse_ch[str_ids_dse_spl[str_f_s]]];
	} // if(str_obz_dse_spl[str_f_s].indexOf( val_lower_case )!==-1)

// *****************************************************************************

} // for ( var str_f_s=0; str_f_s< len ; str_f_s++)
document.getElementById('sel_nav_ids_zaks').innerHTML=sel_ids_zaks_nav;
document.getElementById('sel_nav_ids_zaks').setAttribute('onclick','location.href=\"#tbody_\"+document.getElementById(\"sel_nav_ids_zaks\").value;afterLoad();');

afterLoad();
}