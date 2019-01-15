$.postJSON = function(url, data, callback) 
{
	 $.post(url, data, callback, "json"); 
}

var count_goods = 0;
var par_id = 0;
var in_id = '';

function modal_show()
{
	$('.count').on('click', function()
	{
		par = $(this).parent().parent();
		par_id = par.attr('id');
		count_goods = $(this).text();
		
		$('#dialog').dialog(
		{
			minWidth: 900,
			maxHeight: 600,
			open: function( event, ui ) {$('input[name="count"]').val(count_goods); $('.ui-dialog-title').html('<b>'+order_n+' '+par.find('.order_name').text()+'</b>');
			$.postJSON
			(
				'/project/orders/storage.php',
				{order_id:par_id},
				function(data)
				{
					$('.dial_tab').html(data.htm);
				}
			);},
			close: function( event, ui ) {$('#client').prop('selectedIndex', 0); $('input[name="date"]').prop('value', ''); $('input[name="count"]').prop('value', ''); $('table.dial_tab').empty();}
		});
	});
}
$(modal_show);

function send_order()
{
	
  $('#send_ord').click(function()
	{
		client_id = $('#client option:selected').val();
		date = $('input[name="date"]').val();
		
		// if(count_goods_result == '')
		var count_goods_result = 0;
		
		count_goods_result = parseInt($('input[name="count"]').val());
		
		if(count_goods_result <= 0)
		{
			alert(error_count);
			return false;
		}
		
		if(client_id == '')
		{
			alert(error_client);
			return false;
		}
		else if(date == '')
		{
			alert(error_date);
			return false;
		}
		else if(isNaN(count_goods_result) || count_goods_result <= 0 || count_goods_result > count_goods)
		{
			alert(error_count);
			return false;
		}
		
		if(confirm(confirm_send))
		{
			$.postJSON
			(
				'/project/orders/storage.php',
				{client_id:client_id, date:date, count_goods_result:count_goods_result, order_id:par_id},
				function(data)
				{
					if(data == 'error_count')
					{
						alert(error_count);
						return false;
					}
					else
					{
						$('.dial_tab').html(data.htm);
						$('tr#'+par_id).find('a.count').text(data.count);
						$('input[name="count"]').val(data.count);
						
						if(data.count == 0)
							$('tr#'+par_id).remove();
					}
				}
			);
		}
	});
}
$(send_order);

function add_child_specification()
{
	$('body').on('click', 'a.add_child', function(e)
	{
		e.preventDefault();
		
		$(this).addClass('request_wait');
		
		var ths = $(this);
		var par = $(this).parent().parent().parent().parent();
		par_id = par.attr('data-id');
		
		var margin_parent = parseInt(par.children().eq(3).find('img').css('marginLeft'), 10);
		margin_parent = margin_parent + 10;
		
		clearTimeout(in_id);
		
		in_id = setTimeout(function()
		{
			$.postJSON
			(
				'/project/orders/storage.php',
				{parent_specification:par_id, id_zak:id},
				function(data)
				{
					if(data !== 0)
						par.after('<tr data-proj="" data-zak="'+id+'" data-user-id="" data-id="'+data['id']+'"><td class="nbg"><a href="#0" class="sho_hide">+</a><span class="ltpopup"><div class="ltpopup" id="alt_db_zakdet_'+data['id']+'"><a href="#0" title="'+data['alt_array'][6]+'" class="add_child">'+data['alt_array'][6]+'</a><a href="index.php?do=show&formid='+form_id+'&id='+id+'&addnew=db_zakdet&pid='+data['id']+'&lid=1" title="'+data['alt_array'][7]+'">'+data['alt_array'][7]+'</a><div class="hr"></div><a href="#0" class="close_d" title="'+data['alt_array'][8]+'">'+data['alt_array'][8]+'</a></div></span></td><td style="max-width: 30px;" class="rwField ntabg"><input type="text" class="new_input_ord" name="db_zakdet_ORD_edit_'+data['id']+'" value="0" /></td><td class="Field" ><img src="uses/plus.png" alt="'+data['alt_array'][9]+'" style="cursor:pointer;" onclick=window.open("index.php?do=show&formid=208&p0='+data['id']+'");><b id="hash_tp_'+data['id']+'" style="display:none;"></b></td><td onKeyUp="CopyToDSE('+data['id']+');" class="rwField ntabg"><table><tr><td width="1%"><img style="margin-left: '+margin_parent+'px;" src="uses/none.png"></td><td width="1%"><a href="index.php?do=show&formid=52&id='+data['id']+'" title="'+data['alt_array'][0]+'"><img src="project/img/izd.png"></a> </td><td><input type="text" class="new_input_name" name="db_zakdet_NAME_edit_'+data['id']+'" value="" /></td></tr></table></td><td class="Field" style="width: 90pt;"><a href="index.php?do=show&#38;formid=96&#38;id='+data['id']+'" title="'+data['alt_array'][1]+'" target="_blank"><img src="project/img/calc.png"></a><a href="index.php?do=show&#38;formid=226&#38;id='+data['id']+'" title="'+data['alt_array'][1]+'" target="_blank" class="print_MTK_4"><img src="project/img/calc2.png"></a><a href="index.php?do=show&#38;formid=97&#38;id='+data['id']+'" title="'+data['alt_array'][2]+'" target="_blank"><img src="project/img/spec.png"></a><a href="index.php?do=show&#38;formid=98&#38;id='+data['id']+'" title="'+data['alt_array'][3]+'" target="_blank"><img src="project/img/calc.png"></a></td><td style="max-width: 210px;" onKeyUp="CopyToDSE('+data['id']+');" class="rwField ntabg"><input type="text" class="new_input_oboz" name="db_zakdet_OBOZ_edit_'+data['id']+'" value="" onChange="vote(this , "db_edit.php?db=db_zakdet&field=OBOZ&id='+data['id']+'&value="+TXT(this.value));"></td><td style="max-width: 120px;" onKeyUp="CopyToDSE('+data['id']+');" class="rwField ntabg"><SELECT class="new_select" NAME="db_zakdet_TID_edit_'+data['id']+'" style="">'+type_options+'</SELECT></td><td style="max-width: 60px;" class="rwField ntabg"><input type="text" class="new_input_count" name="db_zakdet_COUNT_edit_'+data['id']+'" value="0" onChange="vote(this , "db_edit.php?db=db_zakdet&field=COUNT&id='+data['id']+'&value="+this.value);" onkeydown="KeyDown(this.value, event)" onkeyup="IPFilter(this.form, "db_zakdet_COUNT_edit_'+data['id']+'", event)"></td><td {{field}}"RCOUNT",false{{/field}} class="Field">0</td><td  class="Field">&nbsp;</td><td  class="Field">&nbsp;</td><td class="Field" style="width:30px;"></td><td  class="Field">&nbsp;</td><td class="Field"><a href="#0" class="del" title="'+data['alt_array'][4]+'"><img src="uses/del.png" alt="'+data['alt_array'][4]+'"></a></td></tr>');
					
					par.children().eq(-1).html('<a title="'+data['alt_array'][10]+'"><img src="uses/nodel.png" alt="'+data['alt_array'][10]+'"/>db_zakdet</a>');
				}
			);
			
			ths.removeClass('request_wait');
			
			$(un_bn);
		}, 100);
	});
}
$(add_child_specification);

function delete_specification()
{
	$('body').on('click', 'a.del', function()
	{
		if(confirm(del_confirm+$(this).parent().parent().find('input[name="db_zakdet_NAME_edit_'+$(this).parent().parent().attr("data-id")+'"]').val()+' - '+$(this).parent().parent().find('input[name="db_zakdet_OBOZ_edit_'+$(this).parent().parent().attr("data-id")+'"]').val()+'?'))
		{	
			parent.location="index.php?do=show&formid="+form_id+"&id="+id+"&db=db_zakdet&delete="+$(this).parent().parent().attr("data-id");
		}
	});
}
$(delete_specification);

function add_specification_data()
{
	$('body').on('change', 'select.new_select', function()
	{
		vote(this, 'db_edit.php?db=db_zakdet&field=TID&id='+$(this).parent().parent().attr('data-id')+'&value='+$(this).val());
	});
	
	$('body').on('change', 'input.new_input_ord', function()
	{
		vote(this, 'db_edit.php?db=db_zakdet&field=ORD&id='+$(this).parent().parent().attr('data-id')+'&value='+$(this).val());
	});
	
	$('body').on('keydown', 'input.new_input_ord', function()
	{
		KeyDown(this.value, event);
	});
	
	$('body').on('keyup', 'input.new_input_ord', function()
	{
		IPFilter(this.form, "db_zakdet_ORD_edit_"+$(this).parent().parent().attr("data-id"), event);
	});
	
	$('body').on('change', 'input.new_input_name', function()
	{
		vote(this, 'db_edit.php?db=db_zakdet&field=NAME&id='+$(this).parent().parent().parent().parent().parent().parent().attr("data-id")+'&value='+TXT($(this).val()));
	});
	
	$('body').on('change', 'input.new_input_oboz', function()
	{
		vote(this, 'db_edit.php?db=db_zakdet&field=OBOZ&id='+$(this).parent().parent().attr("data-id")+'&value='+TXT($(this).val()));
	});
	
	$('body').on('change', 'input.new_input_count', function()
	{
		vote(this, 'db_edit.php?db=db_zakdet&field=COUNT&id='+$(this).parent().parent().attr('data-id')+'&value='+$(this).val());
	});
	
	$('body').on('keydown', 'input.new_input_count', function()
	{
		KeyDown(this.value, event);
	});
	
	$('body').on('keyup', 'input.new_input_count', function()
	{
		IPFilter(this.form, "db_zakdet_COUNT_edit_"+$(this).parent().parent().attr("data-id"), event);
	});
	
	$('body').on('click', 'a.sho_hide', function()
	{
		$(this).parent().find('div.ltpopup').css('display', 'block');
	});
	
	$('body').on('click', 'a.close_d', function()
	{
		$(this).parent().css('display', 'none');
	});
}

function un_bn()
{
	$('select').unbind();
	$('input').unbind();
	$('a.sho_hide').unbind();
	$('a.close_d').unbind();
	$('a').unbind();
	$('body').off('click', 'a.del');
	$(add_specification_data);
	$(add_child_specification);
	$(delete_specification);
}

function print_r(arr, level)
{
	var print_red_text = "";
	if(!level) level = 0;
	var level_padding = "";
	for(var j=0; j<level+1; j++) level_padding += "    ";
	if(typeof(arr) == 'object') {
		for(var item in arr) {
			var value = arr[item];
			if(typeof(value) == 'object') {
				print_red_text += level_padding + "'" + item + "' :\n";
				print_red_text += print_r(value,level+1);
		}
			else
				print_red_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
		}
	}

	else  print_red_text = "===>"+arr+"<===("+typeof(arr)+")";
	return print_red_text;
}