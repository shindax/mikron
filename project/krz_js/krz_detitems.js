var in_id = '';
var field_name = '';
var field_value = '';
var par_teg = '';
var tid = '';

$.postJSON = function(url, data, callback) 
{
	 $.post(url, data, callback, "json"); 
}

function reload_page()
{
	$.postJSON
	(
		'/project/krz_detitems.php',
		{del:1},
		function(data)
		{
			if(data == 0)
				location.reload();
		}
	);
	
	$('a.reload').on('click', function()
	{
		location.reload();
	});
}
$(reload_page);

function add_string()
{
	$('.add_str').on('click', function()
	{
		var tid = '';
		var unit = '';
		var count = '';
		
		if(u_w == '')
		{
			$(un_bn);
			
			return false;
		}
		else
		{
			tid = $(this).parent().find('input[type="hidden"]').val();
			unit = $(this).parent().find('input[type="hidden"]').attr('name');
			count = $(this).parent().find('input[type="number"]').val();
			
			if(confirm(confirm_add_1+count+confirm_add_2))
			{
				var html_new = '';
				
				for(var j=0; j<count; j++)
				{
					html_new += '<tr class="cl_1 child_'+tid+'"><input type="hidden" value="'+tid+'"/><td class="nbg"></td><td class="rwField ntabg"><input type="text" name="name" value="" class="input_child" /></td><td class="rwField ntabg"><input type="number" name="price" value="" class="input_child" /></td><td class="Field">'+unit+'</td><td class="rwField ntabg"><input type="number" name="count" value="" class="input_child" /></td><td class="Field"><a href="#0" class="del" title="'+del_alt+'"><img src="uses/del.png" alt="'+del_alt+'"/></a></td></tr>';
				}
				
				if($('tr').is('.child_'+tid))
					$('tr.child_'+tid).last().after(html_new);
				else
					$('tr.add_'+tid).after(html_new);
			}
		}
		
		$(un_bn);
	});
}
$(add_string);

function del_string()
{
	$('a.del').on('click', function()
	{
		if(u_w == '')
		{
			$(un_bn);
			
			return false;
		}
		
		id = $(this).parent().parent().attr('id');
		
		if(confirm(confirm_send))
		{
			if(typeof id == 'undefined')
			{
				$(this).parent().parent().remove();
			}
			else
			{
				$.postJSON
				(
					'/project/krz_detitems.php',
					{id:id, del:1},
					function(data)
					{
						if(data == 1)
							$('tr#'+id).remove();
					}
				);
			}
			
			$(un_bn);
		}
	});
}
$(del_string);


function set_string()
{
	$('input.input_child').on('keyup paste', function()
	{
		if(u_w == '')
		{
			$(un_bn);
			
			return false;
		}
		
		ths = $(this);
		field_name = $(this).attr('name');
		par_teg = $(this).parent().parent();
		tid = par_teg.children('input[type="hidden"]').val();
		id = 0;
		
		if(par_teg.hasClass('from_db'))
		{
			from_db = 1;
			id = par_teg.attr('id');
		}
		else
			from_db = 0;
		
		clearTimeout(in_id);
		
		in_id = setTimeout(function()
		{
			field_value = $.trim(ths.val());
			
			/* if(field_name == 'name')
			{
				if(field_value.length == 0)
				{
					alert(error_field);
					return false;
				}
			} */
			
			ths.addClass('load_fild');
			console.log("123");
			$.postJSON
			(
				'/project/krz_detitems.php',
				{field_name:field_name, field_value:field_value, from_db:from_db, ID_krzdet:ID_krzdet, tid:tid, id:id},
				function(data)
				{ 
					if(data.id)
						par_teg.attr('id', data.id);
					
					if(data.from_db)
						par_teg.addClass('from_db');
					
					ths.removeClass('load_fild');
				}
			);
			
			$(un_bn);
			
		}, 300);
	});
}
$(set_string);

function edit_detail()
{
	$('input.detail').on('click', function()
	{
		if($(this).val() <= 0)
			$(this).val('');
		
		$(un_bn);
	});
	
	$('input.detail').on('blur', function()
	{
		if($(this).val() < 0 || $(this).val() == '')
			$(this).val(0);
		
		$(un_bn);
	});
	
	$('input.detail').on('keyup paste', function()
	{
		if(u_w == '')
		{
			$(un_bn);
			
			return false;
		}
		
		ths = $(this);
		field_id = $(this).attr('id');
		
		clearTimeout(in_id);
		
		in_id = setTimeout(function()
		{
			field_value = ths.val();
			
			ths.addClass('load_fild');
			
			$.postJSON
			(
				'/project/krz_detitems.php',
				{field_id:field_id, field_value:field_value, ID_krzdet:ID_krzdet},
				function(data)
				{
					for(var item in data)
					{
						$('td.'+data[item].class).children('b').text(data[item].summ);
					}
						
					ths.removeClass('load_fild');
					
				}
			);
			
			$(un_bn);
			
		}, 300);
	});
}
$(edit_detail);


$(function()
{
	$('input').attr("autocomplete", "off");
	
	$('#curloadingpage1').css('display', 'none');
	
	if(u_w == '')
	{
		$('input').attr('disabled', 'disabled');
		$('a.del, a.reload').css('display', 'none');
	}
	
	$(un_bn);
});

function un_bn()
{
	$('input.input_child').unbind();
	$('input.detail').unbind();
	$('a.del').unbind();
	$('.add_str').unbind();
	$(set_string);
	$(edit_detail);
	$(del_string);
	$(add_string);
	
	// $('input.input_child').unbind('keyup keydown keypress paste');
	// $('input.detail').unbind('keyup blur click paste');
	// $('a.del').unbind('click');
	// $('.add_str').unbind('click');
	// $(set_string);
	// $(del_string);
	// $(edit_detail);
	// $(add_string);
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