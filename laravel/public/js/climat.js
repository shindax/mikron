var id = '';
var field_id = '';
var new_temperature = [];
var new_humidity = [];

$.postJSON = function(url, data, callback, data_type) 
{
	$.ajaxSetup({
		headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
	});
	
	$.post(url, data, callback, data_type); 
}




function filter()
{
	$('body').on('change', '#today, #week, #month, #date_start, #date_end, input:checkbox[name="workplace"]', function(){
		
		var array_workplaces = new Array();
		
		$('#list_workplaces').find('input[name="workplace"]:checked').map(function(index){
			
			array_workplaces[index] = $(this).attr('value');
		});
		
		date_start = '';
		date_end = '';
		
		ths = $(this);
		
		
		if(ths.attr('id') !== undefined)
			field_id = ths.attr('id');
		else
		{
			field_id = '';
			
			if($('#list_workplaces').find('input[name="workplace"]').not(':checked').length > 0)
				$('#all_places').prop('checked', false);
			else
				$('#all_places').prop('checked', true);
		}
		
		if(field_id == 'date_start' || field_id == 'date_end')
		{
			field_id = 'date';
			
			$('#today, #week, #month').filter(function(){
				
				$(this).prop('checked', false).parent().removeClass('active');
			});
			
			date_start = $('#date_start').val();
			date_end = $('#date_end').val();
			
			if(date_start !== '' && date_end !== '')
			{
				date_start_array = new Array();
				date_end_array = new Array();
				
				date_start_array = date_start.split('-');
				date_end_array = date_end.split('-');
				
				if(new Date().setFullYear(date_start_array[0], (date_start_array[1] - 1), date_start_array[2]) > new Date().setFullYear(date_end_array[0], (date_end_array[1] - 1), date_end_array[2]))
				{
					date_start = '';
					date_end = '';
					
					$('#date_start').val('');
					$('#date_end').val('');
					
					alert('Некорректный диапазон дат! Начальная дата не может быть позднее конечной даты!');
					
					return false;
				}
			}
			else if(date_start == '' && date_end == '')
				return false;
		}
		else if(field_id == 'today' || field_id == 'week' || field_id == 'month')
		{
			date_start = '';
			date_end = '';
			
			$('#date_start').val('');
			$('#date_end').val('');
		}
		else
		{
			date_start = $('#date_start').val();
			date_end = $('#date_end').val();
			
			if($('#today').prop("checked"))
				field_id = 'today';
			else if($('#week').prop("checked"))
				field_id = 'week';
			else if($('#month').prop("checked"))
				field_id = 'month';
			else if(date_start !== '' || date_end !== '')
				field_id = 'date';
		}
		
		
		
		if(array_workplaces.length > 0 && field_id !== '')
		{
			new_temperature = [];
			new_humidity = [];
			// console.log(date_start);
			// console.log(date_end);
			// return false;
			$.postJSON
			(
				'climat/filter',
				{id:field_id, date_start:date_start, date_end:date_end, workplaces:array_workplaces},
				function(data)
				{
					$('#table_result').children('tbody').empty();
					
					$.map(data, function(element, index){
						
						if(element['sum_temperature'] !== 0)
							element['sum_temperature'] = Math.round(element['sum_temperature']/element['count']);
						else
							element['sum_temperature'] = '';
						
						if(element['sum_humidity'] !== 0)
							element['sum_humidity'] = Math.round(element['sum_humidity']/element['count'])
						else
							element['sum_humidity'] = '';
						
						
						$('#table_result').children('tbody').append('<tr><td>'+element['name']+'</td><td>Время: '+element['current_data']['date']+'<br />Температура: '+element['current_data']['temperature']+' ℃<br />Влажность: '+element['current_data']['humidity']+' %</td><td>'+element['min_temperature']+' ℃</td><td>'+element['sum_temperature']+' ℃</td><td>'+element['max_temperature']+' ℃</td><td>'+element['min_humidity']+' %</td><td>'+element['sum_humidity']+' %</td><td>'+element['max_humidity']+' %</td></tr>');
						
						new_temperature[index] = {'name':element['name']};
						new_temperature[index]['data'] = [];
						
						new_humidity[index] = {'name':element['name']};
						new_humidity[index]['data'] = [];
						
						$.map(element['data'], function(el, ind){
						
							new_temperature[index]['data'][ind] = [Date.UTC(el['date'][0], el['date'][1], el['date'][2], el['date'][3], el['date'][4], el['date'][5]), el['temperature']];
							new_humidity[index]['data'][ind] = [Date.UTC(el['date'][0], el['date'][1], el['date'][2], el['date'][3], el['date'][4], el['date'][5]), el['humidity']];
						});
					});
					
					// console.log(new_temperature);
					// return false;
					
					
					// series = new_temperature;
					
					filter_type = field_id;
					widget_chart();
				},
				"json"
			);
		}
	});
}
$(filter);




function widget_chart()
{
	var chart = Highcharts.chart('temperature', {
					chart: {
						type: 'line'
					},
					title: {
						text: 'Температура (℃)'
					},
					subtitle: {
						text: 'Температурный режим на рабочих местах'
					},
					xAxis: {
						type: 'datetime',
						dateTimeLabelFormats: {
							day: '%d.%m.%Y',
							hour: ' '
						},
						title: {
							text: ''
						}
					},
					yAxis: {
						title: {
							text: 'Температура ℃'
						},
						min: 0
					},
					tooltip: {
						headerFormat: '<b>{series.name}</b><br>',
						pointFormat: '{point.x:%d.%m.%Y %H:%M:%S}: <b>{point.y:.1f} ℃</b>'
					},

					plotOptions: {
						spline: {
							marker: {
								enabled: true
							}
						}
					},

					series: new_temperature
				});
	
	Highcharts.chart('humidity', {
		chart: {
			type: 'line'
		},
		title: {
			text: 'Влажность (%)'
		},
		subtitle: {
			text: 'Уровень влажности на рабочих местах'
		},
		xAxis: {
			type: 'datetime',
			dateTimeLabelFormats: {
				day: '%d.%m.%Y',
				hour: ' '
			},
			title: {
				text: ''
			}
		},
		yAxis: {
			title: {
				text: 'Влажность %'
			},
			min: 0
		},
		tooltip: {
			headerFormat: '<b>{series.name}</b><br>',
			pointFormat: '{point.x:%d.%m.%Y %H:%M:%S}: <b>{point.y:.1f} %</b>'
		},

		plotOptions: {
			spline: {
				marker: {
					enabled: true
				}
			}
		},

		series: new_humidity
	});
	
	
	// console.log(chart);
}
$(widget_chart);



function add_edit_workplaces()
{
	$('#workplace').on('click', '.new_area', function(){
		
		if($(this).closest('tr').siblings().is('.new_str') == false)
		{
			text_field = '';
			
			$(this).closest('tr').before('<tr class="active new_str"><td width="30"></td><td><input type="text" name="catalog_name" value="" class="form-control form-control-sm" placeholder="Расположение"/></td><td width="110" class="text-right"><div class="btn-group btn-group-sm" role="group"><button type="button" class="btn btn-light text-success save" title="Сохранить расположение"><i class="fas fa-check"></i></button><button type="button" class="btn btn-light text-danger cancel" title="Отменить редактирование"><i class="fas fa-times"></i></button></div></td></tr>');
			
			on_off_expense_buttons('disabled');
			
			$('.new_str input').focus();
		}
	});
	
	
	$('#workplace').on('click', '.add', function(){
		
		if($(this).closest('tr').siblings().is('.new_str') == false && $(this).closest('tr').siblings().is('.edit_str') == false)
		{
			text_field = '';
			str = $(this).closest('tr');
			id = parseInt(str.attr('data-catalog'));
			
			str.children('td').eq(0).html('<button type="button" class="btn btn-link text-secondary p-0 m-0 open" title="Свернуть"><i class="fas fa-minus"></i></button>');
			
			$('#workplace').find('.parent_'+id).removeClass('d_none');
			
			str.after('<tr class="parent_'+id+' new_str" data-parent="'+id+'"><td width="30"></td><td class="pl-3"><input type="text" name="file_name" value="" class="form-control form-control-sm" placeholder="Рабочее место"/></td><td width="110" class="text-right"><div class="btn-group btn-group-sm" role="group"><button type="button" class="btn btn-light text-success save" title="Сохранить"><i class="fas fa-check"></i></button><button type="button" class="btn btn-light text-danger cancel" title="Отменить редактирование"><i class="fas fa-times"></i></button></div></td></tr>');
			
			$('.new_str input').focus();
			
			on_off_expense_buttons('disabled');
		}
	});
	
	
	$('#workplace').on('click', '.edit', function(){
		
		str = $(this).closest('tr');
		text_field = '';
		text_field = str.children('td').eq(1).text();
		
		if(str.hasClass('active'))
			str.children('td').eq(1).html('<input type="text" name="catalog_name" value="'+str.children('td').eq(1).text()+'" class="form-control form-control-sm" placeholder="Наименование категории"/>');
		else
			str.children('td').eq(1).html('<input type="text" name="file_name" value="'+str.children('td').eq(1).text()+'" class="form-control form-control-sm" placeholder="Наименование статьи"/>');
		
		str.find('.btn-group').replaceWith('<div class="btn-group btn-group-sm" role="group"><button type="button" class="btn btn-light text-success save" title="Сохранить"><i class="fas fa-check"></i></button><button type="button" class="btn btn-light text-danger cancel" title="Отменить редактирование"><i class="fas fa-times"></i></button></div>');
		
		str.addClass('edit_str');
		
		str.find('input').focus();
		
		on_off_expense_buttons('disabled');
	});
	
	
	
	
	$('#workplace').on('click', '.cancel', function(){
		
		str = $(this).closest('tr');
		catalog_id = 0;
		
		on_off_expense_buttons(false);
		
		if(str.hasClass('new_str'))
		{
			if(!str.hasClass('active'))
			{
				catalog_id = parseInt(str.attr('data-parent'));
				
				str.remove();
				
				if($('#workplace').find('tr').is('.parent_'+catalog_id) == false)
					$('#workplace').find('[data-catalog="'+catalog_id+'"]').children('td').eq(0).empty();
			}
			else
				str.remove();
			
			
		}
		else if(str.hasClass('active'))
		{
			str.removeClass('edit_str').children('td').eq(1).html('<strong>'+text_field+'</strong>');
			str.find('.btn-group').replaceWith('<div class="btn-group btn-group-sm d_none" role="group"><button type="button" class="btn btn-light text-success add" title="Добавить статью"><i class="fas fa-plus"></i></button><button type="button" class="btn btn-light text-warning edit" title="Редактировать категорию"><span class="oi oi-pencil"></span></button><button type="button" class="btn btn-light text-danger delete" title="Удалить категорию"><span class="oi oi-trash"></span></button></div>');
		}
		else
		{
			str.removeClass('edit_str').children('td').eq(1).html(text_field);
			str.find('.btn-group').replaceWith('<div class="btn-group btn-group-sm d_none" role="group"><button type="button" class="btn btn-light text-warning edit" title="Редактировать статью"><span class="oi oi-pencil"></span></button><button type="button" class="btn btn-light text-danger delete" title="Удалить статью"><span class="oi oi-trash"></span></button></div>');
		}
	});
	
	
	
	
	$('#workplace').on('click', '.open', function(){
		
		str = $(this).closest('tr');
		
		if($('#workplace').find('.parent_'+str.attr('data-catalog')).hasClass('d_none'))
			$('#workplace').find('.parent_'+str.attr('data-catalog')).removeClass('d_none');
		else
			$('#workplace').find('.parent_'+str.attr('data-catalog')).addClass('d_none');
		
		$(this).children('i').toggleClass('fa-bars fa-minus');
	});
}
$(add_edit_workplaces);




function set_workplace()
{
	$('#workplace').on('click', '.save', function(){
		
		str = $(this).closest('tr');
		text_confirm = 'рабочее место';
		id = 0;
		catalog_id = 0;
		cat_flag = false;
		
		if(str.hasClass('active'))
		{
			cat_flag = true;
			text_confirm = 'расположение';
		}
		
		if($.trim(str.find('input').val()) !== '')
		{
			
			if(!str.hasClass('new_str') && cat_flag == true)
				id = parseInt(str.attr('data-catalog'));
			else if(!str.hasClass('new_str') && cat_flag == false)
				id = parseInt(str.attr('data-file'));
			
			if(cat_flag == false)
				catalog_id = parseInt(str.attr('data-parent'));
			
			
			if(confirm('Сохранить '+text_confirm+' "'+$.trim(str.find('input').val())+'"?'))
			{
				text_field = $.trim(str.find('input').val());
				
				$.postJSON
				(
					'climat/set_workplace',
					{text:text_field, id:id, catalog_id:catalog_id, cat_flag:cat_flag},
					function(data)
					{
						if(cat_flag == true && id == 0)
							str.attr('data-catalog', data).removeClass('new_str').find('input').replaceWith('<strong>'+text_field+'</strong>');
						else if(cat_flag == true)
							str.find('input').replaceWith('<strong>'+text_field+'</strong>');
						else if(cat_flag == false && id == 0)
							str.attr('data-file', data).removeClass('new_str').find('input').replaceWith(text_field);
						else if(cat_flag == false)
							str.find('input').replaceWith(text_field);
						
						if(cat_flag == true)
							str.find('.btn-group').replaceWith('<div class="btn-group btn-group-sm d_none" role="group"><button type="button" class="btn btn-light text-success add" title="Добавить статью"><i class="fas fa-plus"></i></button><button type="button" class="btn btn-light text-warning edit" title="Редактировать категорию"><span class="oi oi-pencil"></span></button><button type="button" class="btn btn-light text-danger delete" title="Удалить категорию"><span class="oi oi-trash"></span></button></div>');
						else
							str.find('.btn-group').replaceWith('<div class="btn-group btn-group-sm d_none" role="group"><button type="button" class="btn btn-light text-warning edit" title="Редактировать статью"><span class="oi oi-pencil"></span></button><button type="button" class="btn btn-light text-danger delete" title="Удалить статью"><span class="oi oi-trash"></span></button></div>');
						
						str.removeClass('edit_str');
						
						on_off_expense_buttons(false);
					},
					"json"
				);
			}
		}
		else
			alert('Поле '+text_confirm+' не может быть пустым!');
	});
	
	
	
	$('#workplace').on('click', '.delete', function(){
		
		str = $(this).closest('tr');
		catalog_id = 0;
		
		if(confirm('Удалить "'+str.children('td').eq(1).text()+'"?'))
		{
			id = 0;
			cat_flag = false;
			
			if(str.hasClass('active'))
				cat_flag = true;
			else
				catalog_id = parseInt(str.attr('data-parent'));
			
			if(!str.hasClass('new_str') && cat_flag == true)
				id = parseInt(str.attr('data-catalog'));
			else if(!str.hasClass('new_str') && cat_flag == false)
				id = parseInt(str.attr('data-file'));
		
			$.postJSON
			(
				'climat/set_workplace',
				{delete:id, cat_flag:cat_flag},
				function(data)
				{
					str.remove();
					
					if(cat_flag == true)
						$('#workplace').find('.parent_'+id).remove();
					else if($('#workplace').find('tr').is('.parent_'+catalog_id) == false)
						$('#workplace').find('[data-catalog="'+catalog_id+'"]').children('td').eq(0).empty();
				},
				"json"
			);
		}
	});
}
$(set_workplace);




function on_off_expense_buttons(on_off)
{
	$('#workplace').find('.new_area, .edit, .add').filter(function(){
		
		$(this).attr('disabled', on_off);
	});
}



function stop_togle()
{
	var ct_id;
	
	$('#button_workplaces').hover(
	function()
	{
		clearTimeout(ct_id);
		if($('#list_workplaces').css('display') == 'none')
			$('#list_workplaces').css('display', 'block');
	},
	function()
	{
		ct_id = setTimeout(function()
		{
			$('#list_workplaces').css('display', 'none');
		}, 200);
	});
	
	$('#list_workplaces').hover(
	function()
	{
		clearTimeout(ct_id);
		if($('#list_workplaces').css('display') == 'none')
			$('#list_workplaces').css('display', 'block');
	},
	function()
	{
		ct_id = setTimeout(function()
		{
			$('#list_workplaces').css('display', 'none');
		}, 200);
	});
}
$(stop_togle);



function checked_all()
{
	$('#list_workplaces').find('input:checkbox').prop('checked', true);
	$('#today').prop('checked', true).parent().addClass('active');
	$('#today').trigger('change');
	
	$('#all_places').on('change', function(){
		
		if($(this).prop("checked") == true)
			$('#list_workplaces').find('input[name="workplace"]').prop('checked', true);
		else
			$('#list_workplaces').find('input[name="workplace"]').prop('checked', false);
		
		// console.log(field_id);
		
		if(field_id == 'date')
			$('#date_start').trigger('change');
		else
			$('#'+field_id).trigger('change');
	});
}
$(checked_all);