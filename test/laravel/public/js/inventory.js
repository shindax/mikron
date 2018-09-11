var in_id = '';
var catalog_id = 0;
var id = 0;
var structure_tab = new Array();
var file_conteiner = '';
var clear_time = '';
var attach_user = 0;


$.postJSON = function(url, data, callback, data_type) 
{
	$.ajaxSetup({
		headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
	});
	
	$.post(url, data, callback, data_type); 
}

function structure_table()
{
	$('body').on('click', '.modal_structure', function(){
		
		catalog_id = parseInt($(this).closest('.card-header').attr('data-catalog'));
		catalog_name = $(this).closest('.card-header').children('h5').children('a').text();
		
		// console.log(catalog_name);
		// return false;
		
		$.postJSON
		(
			'inventory/get_structure',
			{catalog_id:catalog_id},
			function(data)
			{
				if(data !== '0')
				{
					// console.log(data['structure_html']);
					$('#table_structure, #structure_select, #type_fields_select, #selection_custom').empty();
					
					table_structure = $('#table_structure');
					
					$.map(data['structure_html'], function(elem, index){
						
						if(elem.parent_id > 0)
						{
							table_structure.find('[data-id="'+elem.parent_id+'"]').children().append(elem.html);
							
							table_structure.find('[data-id="'+elem.id+'"]').children().children('.parent_html').children().eq(1).css('paddingLeft', parseInt(table_structure.find('[data-id="'+elem.parent_id+'"]').children().children('.parent_html').children().eq(1).css('paddingLeft')) + 15+'px');
						}
						else
							table_structure.append(elem.html);
					});
					
					$('#structure_select').html(data['select_structure_html']);
					$('#type_fields_select').html(data['select_type_fields']);
					$('#selection_custom').html(data['selection_custom']);
				}
			},
			"json"
		);
		
		$('#input_group_structure_header').text('Добавить новую ячейку');
		$('#input_group_structure').removeAttr('data-id').children('input[name="name"]').val('').blur();
		$('#input_group_structure').find('input:checkbox').prop('checked', false);
		$('#structure_select option:first').prop('selected', true);
		$('#type_fields_select').removeAttr('disabled').children('option:first').prop('selected', true);
		$('#input_group_structure .input-group-append').html('<button type="button" id="add_structure" class="btn btn-info">Добавить</button>');
		$('#modal_structure_label').text('Структура таблицы "'+catalog_name+'"');
		
		$('#modal_structure').modal('show');
	});
	
	$('#modal_structure').on('click', '.open_structure', function(){
		
		if($(this).closest('.row').siblings().hasClass('d_none'))
		{
			$(this).closest('.row').siblings().removeClass('d_none');
			$(this).children('span').toggleClass('oi-menu oi-minus');
		}
		else
		{
			$(this).closest('.row').siblings(':not(.disable)').addClass('d_none');
			$(this).children('span').toggleClass('oi-menu oi-minus');
		}
	});
	
	$('#modal_structure').on('click', '#add_structure', function(){
		
		par = $(this).closest('#input_group_structure');
		name = $.trim(par.children('input[name="name"]').val());
		parent_id = par.children('#structure_select').val();
		type_field = par.children('#type_fields_select').val();
		selection_id = par.children('#type_fields_select').children('option:selected').attr('data-id');
		log_flag = par.find("input:checkbox:checked").val();
		
		if(log_flag == undefined)
			log_flag = 0;
		
		if(selection_id == undefined)
			selection_id = 0;
		
		if(name == '')
		{
			alert('Поле "Наименование ячейки" не может быть пустым!');
			return false;
		}
		else if(type_field == '')
		{
			alert('Не выбран "Тип поля"!');
			return false;
		}
		
		$.postJSON
		(
			'inventory/set_structure',
			{name:name, parent_id:parent_id, type_field:type_field, selection_id:selection_id, catalog_id:catalog_id, log_flag:log_flag},
			function(data)
			{
				table_structure = $('#table_structure');
					
				table_structure.empty();
				
				$.map(data['html'].structure_html, function(elem, index){
					
					if(elem.parent_id > 0)
						table_structure.find('[data-id="'+elem.parent_id+'"]').children().append(elem.html);
					else
						table_structure.append(elem.html);
				});
				
				$('#structure_select').html(data['html'].select_structure_html);
				
				$('#input_group_structure_header').text('Добавить новую ячейку');
				$('#input_group_structure').removeAttr('data-id').children('input[name="name"]').val('').blur();
				$('#structure_select option:first').prop('selected', true);
				$('#type_fields_select').removeAttr('disabled').children('option:first').prop('selected', true);
				$('#input_group_structure .input-group-append').html('<button type="button" id="add_structure" class="btn btn-info">Добавить</button>');
				
				$('#table_structure [data-id="'+data['id']+'"]').removeClass('d_none').parents('[data-id]').removeClass('d_none').siblings().removeClass('d_none');
				$('#table_structure [data-id="'+data['id']+'"]').parents('[data-id]').find('.oi-menu').removeClass('oi-menu').addClass('oi-minus');
				$('#table_structure [data-id="'+data['id']+'"]').siblings().removeClass('d_none');
				
				cat = $('[data-catalog="'+catalog_id+'"]');
					
				cat.siblings('.collapse').removeClass('show');
				
				cat.find('.collapsed').trigger('click').siblings('img').remove();
			},
			"json"
		);
	});
	
	$('#table_structure').on('click', '.delete', function(){
		
		parent_tr = $(this).closest('[data-id]');
		
		name = $.trim($(this).closest('.row').children().eq(1).text());
		
		
		if(confirm('Удалить ячейку "'+name+'" и все вложенные в неё?'))
		{
			id = parent_tr.attr('data-id');
			
			parent_id = 0;
			if(parent_tr.attr('data-parent') !== undefined)
				parent_id = parent_tr.attr('data-parent');
			
			$.postJSON
			(
				'inventory/set_structure',
				{delete:id, catalog_id:catalog_id},
				function(data)
				{
					table_structure = $('#table_structure');
						
					table_structure.empty();
					
					$.map(data['html'].structure_html, function(elem, index){
						
						if(elem.parent_id > 0)
							table_structure.find('[data-id="'+elem.parent_id+'"]').children().append(elem.html);
						else
							table_structure.append(elem.html);
					});
					
					$('#structure_select').html(data['html'].select_structure_html);
					
					$('#input_group_structure_header').text('Добавить новую ячейку');
					$('#input_group_structure').removeAttr('data-id').children('input[name="name"]').val('').blur();
					$('#structure_select option:first').prop('selected', true);
					$('#type_fields_select').removeAttr('disabled').children('option:first').prop('selected', true);
					$('#input_group_structure .input-group-append').html('<button type="button" id="add_structure" class="btn btn-info">Добавить</button>');
					
					if(parent_id > 0)
					{
						$('#table_structure [data-id="'+parent_id+'"]').removeClass('d_none').parents('[data-id]').removeClass('d_none').siblings().removeClass('d_none');
						$('#table_structure [data-id="'+parent_id+'"]').parents('[data-id]').find('.oi-menu').removeClass('oi-menu').addClass('oi-minus');
						$('#table_structure [data-id="'+parent_id+'"]').siblings().removeClass('d_none');
						$('#table_structure [data-parent="'+parent_id+'"]').removeClass('d_none');
					}
					
					cat = $('div[data-catalog="'+catalog_id+'"]');
					
					cat.siblings('.collapse').removeClass('show');
					
					cat.find('.collapsed').trigger('click').siblings('img').remove();
				},
				"json"
			);
		}
	});
	
	
	$('#table_structure').on('click', '.edit', function(){
		
		parent_tr = $(this).closest('[data-id]');
		
		
		$('#table_structure .row').removeClass('bg-light border-0');
		$(this).closest('.row').addClass('bg-light border-0');
		
		id = parent_tr.attr('data-id');
		name = $.trim($(this).closest('.row').children().eq(1).text());
		type_field = $(this).closest('.row').children().eq(2).attr('data-type');
		
		selection_id = 0;
		selection_id = $(this).closest('.row').children().eq(2).attr('data-id');
		
		log_flag = 0;
		
		if($(this).closest('.row').children().eq(3).attr('data-log') == 1)
			log_flag = 1;
		
		parent_id = 0;
		if(parent_tr.attr('data-parent') !== undefined)
			parent_id = parent_tr.attr('data-parent');
		
		$('#input_group_structure').attr('data-id', id).children('input[name="name"]').val(name).focus();
		
		if(parent_id > 0)
			$('#structure_select option[value="'+parent_id+'"]').prop('selected', true);
		else
			$('#structure_select option:first').prop('selected', true);
		
		$('#structure_select option').prop('disabled', false);
		
		parent_tr.find('[data-id]').filter(function(index){
			
			$('#structure_select option[value="'+$(this).attr('data-id')+'"]').prop('disabled', true);
		});
		
		$('#structure_select option[value="'+id+'"]').prop('disabled', true);
		
		if(type_field == 'selection' || type_field == 'selection_custom')
		{
			if(log_flag == 1)
				$('#input_group_structure').find('input:checkbox').prop({"disabled":false, "checked":true});
			else
				$('#input_group_structure').find('input:checkbox').prop({"disabled":false, "checked":false});
		}
		else
			$('#input_group_structure').find('input:checkbox').prop({"disabled":true, "checked":false});
		
		$('#type_fields_select option').filter(function(index){
			
			if($(this).val() == type_field)
			{
				if(type_field == 'selection' || type_field == 'selection_custom')
				{
					if($(this).attr('data-id') == selection_id)
					{
						$(this).prop('selected', true);
						return false;
					}
				}
				else
				{
					$(this).prop('selected', true);
					return false;
				}
			}
		});
		
		$('#type_fields_select').prop('disabled', true);
		
		$('#input_group_structure .input-group-append').html('<button type="button" class="btn btn-light text-success save" title="Сохранить изменения"><span class="oi oi-check"></span></button><button type="button" class="btn btn-light text-danger cancel" title="Отменить изменения"><span class="oi oi-x"></span></button>');
		
		$('#input_group_structure_header').text('Редактировать ячейку');
		
		// $(un_bn);
		// alert(parent_id);
	});
	
	$('#input_group_structure').on('change', '#type_fields_select', function(){
		
		if($(this).val() == 'selection' || $(this).val() == 'selection_custom')
			$('#input_group_structure').find('input:checkbox').prop("disabled", false);
		else
			$('#input_group_structure').find('input:checkbox').prop({"disabled":true, "checked":false});
	});
	
	$('#input_group_structure').on('click', '.cancel', function(){
		if(confirm('Оменить изменения?'))
		{
			$('#input_group_structure_header').text('Добавить новую ячейку');
			$('#input_group_structure').removeAttr('data-id').children('input[name="name"]').val('').blur();
			$('#structure_select option:first').prop('selected', true);
			$('#structure_select option').prop('disabled', false);
			$('#input_group_structure').find('input:checkbox').prop('checked', false);
			$('#type_fields_select').removeAttr('disabled').children('option:first').prop('selected', true);
			$('#input_group_structure .input-group-append').html('<button type="button" id="add_structure" class="btn btn-info">Добавить</button>');
			$('#table_structure').find('.row').removeClass('bg-light border-0');
		}
	});
	
	$('#input_group_structure').on('click', '.save', function(){
		
		if(confirm('Сохранить изменения?'))
		{
			id = $('#input_group_structure').attr('data-id');
			name = $('#input_group_structure input[name="name"]').val();
			parent_id = $('#structure_select').val();
			log_flag = $('#input_group_structure').find("input:checkbox:checked").val();
			
			if(log_flag == undefined)
				log_flag = 0;
			
			$.postJSON
			(
				'inventory/set_structure',
				{id:id, name:name, parent_id:parent_id, catalog_id:catalog_id, log_flag:log_flag},
				function(data)
				{
					table_structure = $('#table_structure');
						
					table_structure.empty();
					
					$.map(data['html'].structure_html, function(elem, index){
						
						if(elem.parent_id > 0)
							table_structure.find('[data-id="'+elem.parent_id+'"]').children().append(elem.html);
						else
							table_structure.append(elem.html);
					});
					
					$('#structure_select').html(data['html'].select_structure_html);
					
					$('#input_group_structure_header').text('Добавить новую ячейку');
					$('#input_group_structure').removeAttr('data-id').children('input[name="name"]').val('').blur();
					$('#structure_select option:first').prop('selected', true);
					$('#input_group_structure').find('input:checkbox').prop('checked', false);
					$('#type_fields_select').removeAttr('disabled').children('option:first').prop('selected', true);
					$('#input_group_structure .input-group-append').html('<button type="button" id="add_structure" class="btn btn-info">Добавить</button>');
					
					$('#table_structure [data-id="'+id+'"]').removeClass('d_none').parents('[data-id]').removeClass('d_none').siblings().removeClass('d_none');
					$('#table_structure [data-id="'+id+'"]').parents('[data-id]').find('.oi-menu').removeClass('oi-menu').addClass('oi-minus');
					$('#table_structure [data-id="'+id+'"]').siblings().removeClass('d_none');
					
					cat = $('[data-catalog="'+catalog_id+'"]');
					
					cat.siblings('.collapse').removeClass('show');
					
					cat.find('.collapsed').trigger('click').siblings('img').remove();
				},
				"json"
			);
		}
	});
	
	
	
	$('#selection_custom').on('click', 'a', function(){
		
		$('#selection_custom_fields').find('input').removeAttr('data-id').val('').blur();
		
		$('#selection_custom_fields').find('.input-group-append').each(function(index){
			
			$(this).html('<button class="btn btn-success add_'+$(this).siblings('input').attr('name')+'" type="button">Добавить</button>');
		});
	});
	
	
	
	$('#modal_structure').on('click', '.add_selection_custom', function(){
		
		name = $.trim($(this).closest('.input-group').children('input[name="selection_custom"]').val());
		// console.log(name);
		
		if(name == '')
		{
			alert('Поле "Наименование списка" не может быть пустым!');
			return false;
		}
		
		$.postJSON
		(
			'inventory/set_selection_custom',
			{name:name, catalog_id:catalog_id},
			function(data)
			{
				$('#table_structure, #structure_select, #type_fields_select, #selection_custom').empty();
					
				table_structure = $('#table_structure');
				
				$.map(data['structure_html'], function(elem, index){
					
					if(elem.parent_id > 0)
						table_structure.find('[data-id="'+elem.parent_id+'"]').children().append(elem.html);
					else
						table_structure.append(elem.html);
				});
				
				$('#structure_select').html(data['select_structure_html']);
				$('#type_fields_select').html(data['select_type_fields']);
				$('#selection_custom').html(data['selection_custom']);
			},
			"json"
		);
	});
	
	
	$('#modal_structure').on('click', '.add_selection_element', function(){
		
		name = $.trim($(this).closest('.input-group').children('input[name="selection_element"]').val());
		
		selection_id = $(this).closest('#selection_custom_fields').siblings('#selection_custom').find('a.active').attr('data-id');
		// console.log(selection_id);
		// return false;
		
		if(name == '')
		{
			alert('Поле "Элемент списка" не может быть пустым!');
			return false;
		}
		
		$.postJSON
		(
			'inventory/set_selection_custom',
			{name_element:name, catalog_id:catalog_id, selection_id:selection_id},
			function(data)
			{
				// console.log(data);
				// return false;
				$('#table_structure, #structure_select, #type_fields_select, #selection_custom').empty();
				
				table_structure = $('#table_structure');
				
				$.map(data['structure_html'], function(elem, index){
					
					if(elem.parent_id > 0)
						table_structure.find('[data-id="'+elem.parent_id+'"]').children().append(elem.html);
					else
						table_structure.append(elem.html);
				});
				
				$('#structure_select').html(data['select_structure_html']);
				$('#type_fields_select').html(data['select_type_fields']);
				$('#selection_custom').html(data['selection_custom']);
				
				$('#selection_custom').find('a').removeClass('active');
				$('#selection_custom').find('.fade').removeClass('active show');
				$('#selection_custom').find('a[data-id="'+selection_id+'"]').addClass('active');
				$('#selection_custom').find('#list_'+selection_id).addClass('active show');
			},
			"json"
		);
		
		$(this).parent().siblings('input').removeAttr('data-id').val('').blur();
	});
	
	
	$('#modal_structure').on('click', '.edit_selection', function(){
		
		name = $.trim($(this).closest('a').text());
		id = $(this).closest('a').attr('data-id');
		
		$('#selection_custom_fields').find('input[name="selection_custom"]').val(name).attr('data-id', id).focus().siblings('.input-group-append').html('<button type="button" class="btn btn-light text-success save" title="Сохранить изменения"><span class="oi oi-check"></span></button><button type="button" class="btn btn-light text-danger cancel" title="Отменить изменения"><span class="oi oi-x"></span></button>');
	});
	
	
	$('#modal_structure').on('click', '.edit_selection_element', function(){
		
		$(this).closest('ul').children('li').removeClass('bg-light');
		$(this).closest('li').addClass('bg-light');
		
		name = $.trim($(this).closest('li').text());
		id = $(this).closest('li').attr('data-id');
		
		$('#selection_custom_fields').find('input[name="selection_element"]').val(name).attr('data-id', id).focus().siblings('.input-group-append').html('<button type="button" class="btn btn-light text-success save" title="Сохранить изменения"><span class="oi oi-check"></span></button><button type="button" class="btn btn-light text-danger cancel" title="Отменить изменения"><span class="oi oi-x"></span></button>');
	});
	
	
	$('#selection_custom_fields').on('click', '.cancel', function(){
		
		if($(this).parent().siblings('input').attr('name') == 'selection_element')
			$('#selection_custom').find('li').removeClass('bg-light');
		
		$(this).parent().siblings('input').removeAttr('data-id').val('').blur();
		
		$(this).parent().html('<button class="btn btn-success add_'+$(this).parent().siblings('input').attr('name')+'" type="button">Добавить</button>');
	});
	
	
	$('#selection_custom_fields').on('click', '.save', function(){
		
		if(confirm('Сохранить изменения?'))
		{
			id = $(this).parent().siblings('input').attr('data-id');
			input = $(this).parent().siblings('input').attr('name');
			name = $(this).parent().siblings('input').val();
			selection_id = $('#selection_custom').find('.active').attr('data-id');
			
			// console.log(id);
			// console.log(input);
			// console.log(name);
			// console.log(selection_id);
			// return false;
			
			$.postJSON
			(
				'inventory/set_selection_custom',
				{id:id, name:name, catalog_id:catalog_id, input:input},
				function(data)
				{
					$('#table_structure, #structure_select, #type_fields_select, #selection_custom').empty();
					
					table_structure = $('#table_structure');
					
					$.map(data['structure_html'], function(elem, index){
						
						if(elem.parent_id > 0)
							table_structure.find('[data-id="'+elem.parent_id+'"]').children().append(elem.html);
						else
							table_structure.append(elem.html);
					});
					
					$('#structure_select').html(data['select_structure_html']);
					$('#type_fields_select').html(data['select_type_fields']);
					$('#selection_custom').html(data['selection_custom']);
					
					$('#selection_custom').find('a').removeClass('active');
					$('#selection_custom').find('.tab-pane').removeClass('show active');
					$('#selection_custom').find('a[data-id="'+selection_id+'"]').addClass('active');
					$('#selection_custom').find('#list_'+selection_id).addClass('show active');
				},
				"json"
			);
			
			$(this).parent().siblings('input').removeAttr('data-id').val('').blur();

			$(this).parent().html('<button class="btn btn-success add_'+$(this).closest('input').attr('name')+'" type="button">Добавить</button>');
		}
	});
	
	
	$('#selection_custom').on('click', '.delete_selection', function(){
		
		name = $.trim($(this).closest('a').clone().children().remove().end().text());
		
		if(confirm('Удалить список "'+name+'"?'))
		{
			id = $(this).closest('a').attr('data-id');
			
			// console.log(catalog_id);
			// return false;
			$.postJSON
			(
				'inventory/set_selection_custom',
				{delete:id, input:'selection_custom', catalog_id:catalog_id},
				function(data)
				{
					if(data !== 0)
					{
						$('#table_structure, #structure_select, #type_fields_select, #selection_custom').empty();
						
						table_structure = $('#table_structure');
						
						$.map(data['structure_html'], function(elem, index){
							
							if(elem.parent_id > 0)
								table_structure.find('[data-id="'+elem.parent_id+'"]').children().append(elem.html);
							else
								table_structure.append(elem.html);
						});
						
						$('#structure_select').html(data['select_structure_html']);
						$('#type_fields_select').html(data['select_type_fields']);
						$('#selection_custom').html(data['selection_custom']);
						
					}
					else
						alert('Список не может быть удален, так как используется в структуре таблицы!');
				},
				"json"
			);
		}
	});
	
	
	$('#selection_custom').on('click', '.delete_selection_element', function(){
		
		name = $.trim($(this).closest('li').clone().children().remove().end().text());
		// alert(name);
		// return false;
		
		if(confirm('Удалить элемент "'+name+'"?'))
		{
			id = $(this).closest('li').attr('data-id');
			selection_id = $(this).closest('#selection_custom').find('a[id="'+$(this).closest('.tab-pane').attr('aria-labelledby')+'"]').attr('data-id');
			
			$.postJSON
			(
				'inventory/set_selection_custom',
				{delete:id, input:'selection_custom_element', selection_id:selection_id, catalog_id:catalog_id},
				function(data)
				{
					if(data !== 0)
					{
						$('#table_structure, #structure_select, #type_fields_select, #selection_custom').empty();
						
						table_structure = $('#table_structure');
						
						$.map(data['structure_html'], function(elem, index){
							
							if(elem.parent_id > 0)
								table_structure.find('[data-id="'+elem.parent_id+'"]').children().append(elem.html);
							else
								table_structure.append(elem.html);
						});

						$('#structure_select').html(data['select_structure_html']);
						$('#type_fields_select').html(data['select_type_fields']);
						$('#selection_custom').html(data['selection_custom']);
						
						$('#selection_custom').find('a').removeClass('active');
						$('#selection_custom').find('.tab-pane').removeClass('show active');
						$('#selection_custom').find('a[data-id="'+selection_id+'"]').addClass('active');
						$('#selection_custom').find('#list_'+selection_id).addClass('show active');
					}
					else
						alert('Элемент '+name+' не может быть удален, так как используется в структуре таблицы!');
				},
				"json"
			);
		}
	});
}
$(structure_table);




function open_catalog()
{
	$('#myTabContent').on('click', '.collapsed', function(){
		
		if(!$(this).siblings().is('img'))
			$(this).parent().append('<img src="/laravel/public/spin.svg"/>');
		
		img = $(this).siblings('img');
		
		var par = $(this).closest('div');
		parent = par.parent();
		
		id = parseInt(par.attr('data-catalog'));
		
		// console.log(attach_user);
		// console.log(id);
		// return false;
		
		if(par.siblings('.collapse').is('.show') == false)
		{
			if($(this).hasClass('just_close'))
			{
				par.siblings('.collapse').addClass('show');
				img.remove();
			}
			else
			{
				$.postJSON
				(
					'/laravel/public/inventory/get_childs_catalog',
					{id:id, attach_user:attach_user},
					function(data)
					{
						// console.log(data);
						// return false;
						
						structure_tab[id] = '';
						file_conteiner = '';
						
						
						if(data['structure_table'])
						{
							html = '<div id="body_table_'+id+'" class="body_table border border-right-0 border-left-0 border-top-0"><div class="d-flex flex-row string"></div></div><div id="file_conteiner_'+id+'" class="d_none"></div>';
							structure_tab[id] = data['structure_table'];
							
							img.remove();
						}
						else
							html = '';
						
						if(data['result_html'])
							html += data['result_html'];
						
						$('#catalog_'+id).html(html).parent().addClass('show');
						
						body_table = $('#body_table_'+id);
						
						if(data['structure_table'])
						{
							// console.log(print_r(data['structure_table']));
							// return false;
							var field_list = new Array();
							file_conteiner = $('#file_conteiner_'+id);
							
							file_conteiner.html('<div class="d-flex flex-row string" data-file=""><div class="flex_conteiner border-0"><div class="d-flex flex-row parent parent_0" data-parent="0" data-type="number"><div class="flex_conteiner inventory_number order"><p>Инвентарный номер</p></div></div><div class="d-flex flex-row d_none child child_0" data-child = "0"></div></div><div class="flex_conteiner border-0"><div class="d-flex flex-row log_flag parent parent_-1" data-parent="-1" data-type="selection"><div class="flex_conteiner flex_field order attach_user"><p>Ответственный</p></div></div><div class="d-flex flex-row d_none child child_-1" data-child = "-1"></div></div></div>');
							
							$.map(data['order_structure'], function(elem, index){
								
								if(data['structure_table'][elem]['type_field'] == 'button_delete')
									field_list[elem] = data['structure_table'][elem]['html'];
								
								// console.log(print_r(field_list));
								log_flag_class = '';
								
								if(data['structure_table'][elem]['log_flag'] == 1)
									log_flag_class = 'log_flag ';
								
								order_class = '';
								data_type = '';
								
								if(data['structure_table'][elem]['order'] == 1)
								{
									order_class = ' order';
									data_type = ' data-type="'+data['structure_table'][elem]['type_field']+'"';
								}
								
								if(elem !== -1)
								{
									style = 'style="';
									
									if(data['structure_table'][elem]['width'])
										style += 'width:'+data['structure_table'][elem]['width']+'px;';
									
									if(data['structure_table'][elem]['height'])
										style += ' height:'+data['structure_table'][elem]['height']+'px;';
									
									style += '"';
									
									if(data['structure_table'][elem]['parent_id'] == 0)
										file_conteiner.children('.string').append('<div class="flex_conteiner border-0"><div class="d-flex flex-row '+log_flag_class+'parent parent_'+data['structure_table'][elem]['id']+'" data-parent = "'+data['structure_table'][elem]['id']+'"'+data_type+' '+style+'><div class="flex_conteiner flex_field'+order_class+'"><p>'+data['structure_table'][elem]['name']+'</p></div></div><div class="d-flex flex-row d_none child child_'+data['structure_table'][elem]['id']+'" data-child = "'+data['structure_table'][elem]['id']+'"></div></div>');
									else
										file_conteiner.find('.child_'+data['structure_table'][elem]['parent_id']).removeClass('d_none').append('<div class="flex_conteiner border-0"><div class="d-flex flex-row '+log_flag_class+'parent parent_'+data['structure_table'][elem]['id']+'" data-parent = "'+data['structure_table'][elem]['id']+'"'+data_type+' '+style+'><div class="flex_conteiner flex_field'+order_class+'"><p>'+data['structure_table'][elem]['name']+'</p></div></div><div class="d-flex flex-row d_none child child_'+data['structure_table'][elem]['id']+'" data-child = "'+data['structure_table'][elem]['id']+'"></div></div>');
								}
							});
							
							file_conteiner.find('.parent_0').width(100);
							file_conteiner.find('.parent_-1').width(105);
							
							// file_conteiner = file_conteiner.children().clone();
							
							// body_table.html(file_conteiner);
							body_table.html(file_conteiner.children().clone());
							
							// body_table.find('.flex_field, .log_flag').removeClass('flex_field log_flag');
							body_table.find('div').removeClass('flex_field log_flag');
						}
						
						
						
						$('#file_conteiner_'+id).find('.flex_field').html('<p data-input-id=""></p>');
						file_conteiner = $('#file_conteiner_'+id).html();
						
						// console.log(print_r(data['table']));
						// return false;
						if(data['table'])
						{
							count_array = 0;
							
							$.map(data['table'], function(elem, index){
								
								if(count_array & 1)
									string = body_table.append(file_conteiner).children(':last').attr('data-file', index).addClass('bg-white');
								else
									string = body_table.append(file_conteiner).children(':last').attr('data-file', index).addClass('bg_string_light');
								
								++count_array;
								
								if($.isEmptyObject(elem) == true)
								{
									string.find('.inventory_number > p').html(data['files'][index]['inventory_number']);
									
									if(data['files'][index]['attach_user'])
										string.find('.attach_user').html('<p>'+data['files'][index]['attach_user']['name']+'</p><span class="d_none select_value">'+data['files'][index]['attach_user_id']+'</span>');
									else
										string.find('.attach_user').html('<p></p><span class="d_none select_value"></span>');
								}
								else
								{
									$.map(elem, function(el, ind){
										
										string.find('.inventory_number > p').html(data['files'][el['file_id']]['inventory_number']);
										
										if(data['files'][el['file_id']]['attach_user'])
											string.find('.attach_user').html('<p>'+data['files'][el['file_id']]['attach_user']['name']+'</p><span class="d_none select_value">'+data['files'][el['file_id']]['attach_user_id']+'</span>');
										else
											string.find('.attach_user').html('<p></p><span class="d_none select_value"></span>');
										
										cur_cell = string.find('.parent_'+ind+' > div');
										// cur_cell = string.find('.parent_'+ind+' .flex_field');
										
										type_field = data['structure_table'][ind].type_field;
										
										if(type_field == 'selection' || type_field == 'selection_custom')
										{
											if(el['select'] !== null && el['select'] > 0 && data['structure_table'][ind]['html']['array'][el['select']])
												cur_cell.html('<p data-input-id="'+el['id']+'">'+data['structure_table'][ind]['html']['array'][el['select']]+'</p><span class="d_none select_value">'+el['select']+'</span>');
											else
												cur_cell.html('<p data-input-id="'+el['id']+'"></p><span class="d_none select_value"></span>');
										}
										else if(type_field == 'file')
										{
											if(el[type_field] !== null)
												cur_cell.html('<p data-input-id="'+el['id']+'">'+el['link']+'</p>');
											else
												cur_cell.html('<p data-input-id="'+el['id']+'"></p>');
										}
										else if(el[type_field] !== null)
											cur_cell.html('<p data-input-id="'+el['id']+'">'+el[type_field]+'</p>');
										else
											cur_cell.html('<p data-input-id="'+el['id']+'"></p>');
									});
								}
							});
							
							if(field_list !== '')
							{
								$.map(field_list, function(field, index){
									
									body_table.children().not(':first').find('.parent_'+index).children().html(field);
									// body_table.find('.string').not(':first').find('.parent_'+index).children().html(field);
								});
							}
						}
						
						// return false;
						
						body_table.children(':first').addClass('new_bg text-white sticky-top').find('.parent').filter(function(index){
							
							body_table.find('.parent_'+this.getAttribute('data-parent')).children().css('width', this.clientWidth).parent().parent().css('width', this.clientWidth);
							
							if(this.nextSibling.classList.contains('d_none') == false && !data['structure_table'][this.getAttribute('data-parent')]['height'])
								body_table.find('.parent_'+this.getAttribute('data-parent')).css('height', '50%').siblings().css('height', '50%');
							
						});
						
						img.remove();
						
						var first_string = body_table.children(':first');
						
						first_string.find('.parent').unbind();
						
						first_string.find('.parent').resizable({
							minWidth: 10,
							minHeight: 10,
							resize: function(event, ui)
							{
								if(ui.size.height !== ui.originalSize.height)
								{
									if(ui.element.siblings().hasClass('d_none'))
										ui.element.parent().height(ui.size.height).siblings().height(ui.size.height);
									else
										ui.element.siblings().height(ui.element.parent().innerHeight() - ui.size.height).parent().height('').siblings().height('').children().height('');
									
									// console.log(1);
									
									if(ui.element.parent().parent().hasClass('child'))
									{
										if(ui.element.parent().innerHeight() > ui.element.parent().parent().innerHeight())
											ui.element.parent().parent().height(ui.element.parent().innerHeight()).siblings().height(ui.element.parent().parent().siblings().innerHeight() - (ui.element.parent().innerHeight() - ui.element.parent().parent().innerHeight()));
										else if(ui.element.parent().innerHeight() < ui.element.parent().parent().innerHeight())
											ui.element.parent().parent().height(ui.element.parent().parent().innerHeight() - (ui.element.parent().parent().innerHeight() - ui.element.parent().innerHeight())).siblings().height(ui.element.parent().parent().siblings().innerHeight() + (ui.element.parent().parent().innerHeight() - ui.element.parent().innerHeight()));
									}
									
									first_string.find('.flex_conteiner').filter(function(){
										
										sum_height = 0;
										
										$(this).children().map(function(){
											sum_height = sum_height + $(this).innerHeight();
										});
										
										if($(this).innerHeight() !== sum_height)
										{
											if(!$(this).children('.child').hasClass('d_none'))
											{
												// $(this).children('.child').height($(this).innerHeight() / 2).find('.flex_conteiner, .parent, .child').height('');
												unit_height = 0;
												unit_height = $(this).innerHeight() / 2;
												
												$(this).children('.parent').height(unit_height).siblings('.child').height(unit_height).find('.flex_conteiner, .parent, .child').height('');
											}
											else
												$(this).children('.parent').height($(this).innerHeight());
										}
									});
								}
								
								if(ui.size.width !== ui.originalSize.width)
								{
									if(ui.element[0].clientWidth >= (ui.element.parent().parent().innerWidth() - (ui.element.parent().nextAll().length * 10)))
										ui.element.css('maxWidth', (ui.element.parent().parent().innerWidth() - (ui.element.parent().nextAll().length * 10))+'px');
									
									ui.element.children('.flex_conteiner').width(ui.element[0].clientWidth);
									
									if(ui.element.parent().parent().hasClass('child'))
										ui.element.parent().width(ui.element[0].clientWidth).siblings().find('.parent, .flex_conteiner').css('width', '100%');
									else
										ui.element.parent().width(ui.element[0].clientWidth);
									
									first_string.find('.parent > .flex_conteiner').css('width', '100%');
									
									ui.element.siblings().filter(function(){
										
										$(this).children('.flex_conteiner').width($(this).width() / ui.element.siblings().children().length).find('.parent, .flex_conteiner').css('width', '100%');
									});
									
									if(!ui.element.parent().parent().hasClass('string'))
									{
										container_width = 0;
										container_width = ui.element.parent().parent().innerWidth();
										
										all_width = 0;
										
										ui.element.parent().siblings().map(function(){
											
											all_width = all_width + this.clientWidth;
										});
										
										all_width = all_width + ui.element[0].clientWidth;
									
										// console.log(container_width);
										// console.log(all_width);
										
										free_width = 0;
										unit_width = 0;
										
										if(container_width > all_width)
										{
											free_width = container_width - all_width;
											
											if(ui.element.parent().nextAll().length > 0)
											{
												// console.log(free_width);
												unit_width = free_width / ui.element.parent().nextAll().length;
												ui.element.parent().nextAll().filter(function(){
													
													$(this).width($(this).innerWidth() + unit_width);
												});
											}
											else
											{
												unit_width = free_width / ui.element.parent().siblings().length;
												
												ui.element.parent().siblings().filter(function(){
													
													$(this).width($(this).innerWidth() + unit_width);
												});
											}
										}
										else if(all_width > container_width)
										{
											free_width = all_width - container_width;
											
											
											if(ui.element.parent().nextAll().length > 0)
											{
												unit_width = free_width / ui.element.parent().nextAll().length;
												
												ui.element.parent().nextAll().filter(function(){
													
													$(this).width($(this).innerWidth() - unit_width);
												});
											}
											else
											{
												unit_width = free_width / ui.element.parent().siblings().length;
												
												ui.element.parent().siblings().filter(function(){
													
													$(this).width($(this).innerWidth() - unit_width);
												});
											}
										}
									}
									
									ui.element.siblings().eq(0).find('.child').map(function(){
										
										if($(this).children().length > 1)
										{
											unit_width = $(this).innerWidth() / $(this).children().length;
											
											$(this).children().filter(function(){
												
												$(this).width(unit_width);
											});
										}
										
									});
									
									ui.element.parent().siblings().eq(0).find('.child').map(function(){
										
										if($(this).children().length > 1)
										{
											unit_width = $(this).innerWidth() / $(this).children().length;
											
											$(this).children().filter(function(){
												
												$(this).width(unit_width);
											});
										}
									});
								}
							},
							stop: function(event, ui){
								
								ui.element.css('maxWidth', '');
								
								
								structure_size_array = new Array();
								
								first_string.find('.parent').map(function(indx){
									
									structure_size_array[indx] = {'id':$(this).attr('data-parent'), 'width':$(this).innerWidth(), 'height':$(this).innerHeight()};
								});
								
								// console.log(print_r(structure_size_array));
								
								
								$.postJSON
								(
									'/laravel/public/inventory/set_structure_size',
									{structure_size_array:structure_size_array},
									function(data)
									{
										if(data == 0)
											alert('Изменение размера не зафиксированно!');
									},
									"json"
								);
								
								first_string.find('.parent').filter(function(index){
									
									body_table.find('.parent_'+this.getAttribute('data-parent')).css({'width':$(this).innerWidth(), 'height':$(this).innerHeight()}).children('.flex_conteiner').css('width', this.clientWidth).parent().parent().css('width', this.clientWidth);
								});
								
								var summ_width = 0;
								
								first_string.children('.flex_conteiner').map(function(index){
									
									// console.log(index);
									if(index == 0)
										summ_width = 0;
									
									summ_width += $(this).innerWidth();
								});
								
								body_table.children().filter(function(index){
									
									$(this).width(summ_width);
								});
							},
							create: function(event, ui){
								
								var summ_width = 0;
							
								first_string.children('.flex_conteiner').filter(function(index){
									
									if(index == 0)
										summ_width = 0;
									
									summ_width += $(this).innerWidth();
								});
								
								body_table.children().width(summ_width);
								
								first_string.find('.parent').map(function(){
									
									if(!$(this).siblings().hasClass('d_none') && !$(this).css('height') && !$(this).siblings().css('height'))
										$(this).height($(this).parent().innerHeight() / 2).siblings().height($(this).parent().innerHeight() / 2);
									
									if($(this).css('width'))
										$(this).parent().width($(this).innerWidth());
									else if($(this).siblings().css('width'))
										$(this).parent().width($(this).siblings().innerWidth());
									
									// if($(this).children('.flex_conteiner').children('p').innerHeight() < $(this).children('.flex_conteiner').innerHeight())
										// $(this).children('.flex_conteiner').css('paddingTop', (($(this).children('.flex_conteiner').innerHeight() - $(this).children('.flex_conteiner').children('p').innerHeight()) / 2)+'px');
								});
							}
						});
					},
					"json"
				);
			}
		}
		else
		{
			par.siblings('.collapse').removeClass('show');
			img.remove();
		}
		
		$(this).unbind();
	});
}
$(open_catalog);



function open_table_catalog()
{
	$('body').on('click', '.table_list', function(){
		
		window.open('inventory/table_list/'+$(this).closest('.card').children('.card-header').attr('data-catalog'), '_blank');
		
	});
	
	
	$('body').on('click', '.list_table', function(){
		
		par = $(this).closest('div');
		
		if(par.siblings('.collapse').is('.show') == false)
		{
			par.siblings('.collapse').removeClass('show');
			
			catalog_id = $(this).closest('.card-header').attr('data-catalog');
			
			$(this).parent().append('<img src="/laravel/public/spin.svg"/>');
			
			img = $(this).siblings('img');
			
			$.postJSON
			(
				'get_list',
				{catalog_id:catalog_id},
				function(data)
				{
					$('#catalog_'+catalog_id).html(data).parent().addClass('show');
				},
				"html"
			);
			
			img.remove();
		}
		else
		{
			par.siblings('.collapse').removeClass('show');
			img.remove();
		}
	});
}
$(open_table_catalog);


function add_edit_cat()
{
	$('body').on('click', '.add_child', function(){
		
		
		par = $(this).closest('div.card-header');
		name = $.trim(par.find('a').text());
		catalog_id = par.attr('data-catalog');
		
		if(catalog_id !== '' && par.siblings('div[data-catalog-body]').hasClass('show'))
		{
			if(!par.siblings('div[data-catalog-body]').children('div.card-body').children().is('div[role="tablist"]'))
				par.siblings('div[data-catalog-body]').children('div.card-body').append('<div role="tablist"></div>');
			
			if(!par.siblings('div[data-catalog-body]').children('div.card-body').children('div[role="tablist"]').children().is('div.card_new'))
			{
				par.siblings('div[data-catalog-body]').children('div.card-body').children('div[role="tablist"]').append('<div class="card alert-primary card_new"><div class="card-header pt-1 pb-1" data-catalog><h5 class="float-left mt-2"><a class="collapsed text-dark d_none" href="#0"></a><div class="input-group input-group-sm"><input type="text" name="catalog_name" value="" class="form-control" placeholder="Новый каталог"><span class="input-group-btn"><button type="button" class="btn btn-outline-success border-0 save_catalog" title="Сохранить изменения"><span class="oi oi-check"></span></button><button type="button" class="btn btn-outline-danger border-0 cancel_catalog" title="Отменить изменения"><span class="oi oi-x"></span></button></span></div></h5><div class="btn-toolbar add_edit_delete invisible"><div class="btn-group btn-group-sm float-right" role="group"><button type="button" class="btn btn-outline-success add_child" title="Добваить дочерний раздел"><span class="oi oi-plus"></span></button><button type="button" class="btn btn-outline-warning edit_catalog" title="Редактировать раздел"><span class="oi oi-pencil"></span></button><button type="button" class="btn btn-outline-danger delete_catalog" title="Удалить раздел"><span class="oi oi-trash"></span></button></div><div class="btn-group btn-group-sm float-right mr-4" role="group"><button type="button" class="btn btn-outline-secondary add_string" title="Добваить строку в таблице"><span class="oi oi-spreadsheet"></span></button><button type="button" class="btn btn-outline-info modal_structure" title="Структура таблицы"><span class="oi oi-fork"></span></button></div><div class="btn-group btn-group-sm float-right mr-4" role="group"><button type="button" class="btn btn-outline-primary table_list" title="Ведомость"><span class="oi oi-document"></span></button></div></div></div><div data-catalog-body class="collapse"><div class="card-body"></div></div></div>');
				par.siblings('div[data-catalog-body]').find('input').focus();
			}
		}
	});
	
	
	$('body').on('click', '.cancel_catalog', function(){
		
		id = $(this).closest('div[data-catalog]').attr('data-catalog');
		
		if(confirm('Отменить изменение?'))
		{
			if(id !== '')
			{
				$('input[name="catalog_name"]').closest('h5').children('a').removeClass('d_none');
				$('input[name="catalog_name"]').closest('h5').children('div.input-group').remove();
			}
			else
			{
				$(this).closest('div.card').remove();
				$('body').find('.add_cat').removeAttr('disabled').css('cursor', '');
			}
		}
	});
	
	
	$('body').on('click', '.delete_catalog', function(){
		
		par = $(this).closest('div.card-header');
		name = $.trim(par.find('a').text());
		id = par.attr('data-catalog');
		
		if(confirm('Удалить каталог "'+name+'"?'))
		{
			if(id !== '')
			{
				$.postJSON
				(
					'inventory/set_catalog',
					{delete:id},
					function(data)
					{
						if(data !== 0)
							par.parent().remove();
						else
							alert('Права на удаление каталога "'+name+'" отсутствуют!');
					},
					"json"
				);
			}
			
		}
	});
	
	
	$('body').on('click', '.save_catalog', function(){
		
		if(confirm('Сохранить изменения?'))
		{
			par = $(this).closest('.card');
			h5 = $(this).closest('h5');
			
			id = $(this).closest('div[data-catalog]').attr('data-catalog');
			value = $.trim($(this).parent().siblings('input').val());
			cur_value = $.trim(h5.children('a').text());
			
			if(value !== cur_value)
			{
				$.postJSON
				(
					'inventory/set_catalog',
					{id:id, parent_id:catalog_id, value:value},
					function(data)
					{
						if(data == 0)
						{
							alert('Допустимый размер строки превышен!');
							return false;
						}
						else
						{
							if(data['id'])
							{
								par.removeClass('card_new').children('.card-header').attr('data-catalog', data['id']).children('.add_edit_delete').removeClass('invisible');
								par.children('.collapse').attr('data-catalog-body', data['id']).children().attr('id', 'catalog_'+data['id']);
							}
							
							h5.children('a').text(value);
							h5.children('.input-group').remove();
							h5.children('a').removeClass('d_none');
							
							$('body').find('.add_cat').removeAttr('disabled').css('cursor', '');
						}
					},
					"json"
				);
				
			}
			else if(value == '')
			{
				alert('Поле не может быть пустым!');
				par.find('input').focus();
			}
			else
			{
				if(h5.closest('div.card').hasClass('card_new'))
					h5.closest('div.card').remove();
				else
				{
					h5.children('div.input-group').remove();
					h5.children('a').removeClass('d_none');
				}
			}
		}
	});
	
	$('body').on('click', '.edit_catalog', function(){
		
		$('body').find('div.card_new').remove();
		$('input[name="catalog_name"]').closest('h5').children('a').removeClass('d_none');
		$('input[name="catalog_name"]').closest('h5').children('div.input-group').remove();
		$(this).closest('div.card-header').find('a').addClass('d_none');
		$(this).closest('div.card-header').children('h5').append('<div class="input-group input-group-sm"><input type="text" name="catalog_name" value="'+$.trim($(this).closest('div.card-header').find('a').text())+'" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-outline-success border-0 save_catalog" title="Сохранить изменения"><span class="oi oi-check"></span></button><button type="button" class="btn btn-outline-danger border-0 cancel_catalog" title="Отменить изменения"><span class="oi oi-x"></span></button></span></div>');
		$(this).closest('div.card-header').find('input[name="catalog_name"]').focus();
	});
	
	$('.add_cat').on('click', function(){
		
		if(!$(this).siblings().is('div[role="tablist"]'))
			$(this).before('<div role="tablist"></div>');
		
		$(this).attr({'disabled':'disabled'}).css({'cursor':'default'});
		
		catalog_id = 0;
		$(this).siblings('div[role="tablist"]').append('<div class="card alert-primary card_new"><div class="card-header pt-1 pb-1" data-catalog><h5 class="float-left mt-2"><a class="collapsed text-dark d_none" href="#0"></a><div class="input-group input-group-sm"><input type="text" name="catalog_name" value="" class="form-control" placeholder="Новый каталог"><span class="input-group-btn"><button type="button" class="btn btn-outline-success border-0 save_catalog" title="Сохранить изменения"><span class="oi oi-check"></span></button><button type="button" class="btn btn-outline-danger border-0 cancel_catalog" title="Отменить изменения"><span class="oi oi-x"></span></button></span></div></h5><div class="btn-toolbar add_edit_delete invisible"><div class="btn-group btn-group-sm float-right" role="group"><button type="button" class="btn btn-outline-success add_child" title="Добваить дочерний раздел"><span class="oi oi-plus"></span></button><button type="button" class="btn btn-outline-warning edit_catalog" title="Редактировать раздел"><span class="oi oi-pencil"></span></button><button type="button" class="btn btn-outline-danger delete_catalog" title="Удалить раздел"><span class="oi oi-trash"></span></button></div><div class="btn-group btn-group-sm float-right mr-4" role="group"><button type="button" class="btn btn-outline-secondary add_string" title="Добваить строку в таблице"><span class="oi oi-spreadsheet"></span></button><button type="button" class="btn btn-outline-info modal_structure" title="Структура таблицы"><span class="oi oi-fork"></span></button></div><div class="btn-group btn-group-sm float-right mr-4" role="group"><button type="button" class="btn btn-outline-primary table_list" title="Ведомость"><span class="oi oi-document"></span></button></div></div></div><div data-catalog-body class="collapse"><div class="card-body"></div></div></div>');
		$(this).siblings('div[role="tablist"]').children('div.card_new').find('input').focus();
	});
	
	/* $('input.cat_name').on('keyup paste', function()
	{
		cat_id = $(this).parents('div.card').attr('id');
		ths = $(this);
		
		if(cat_id == undefined)
			cat_id = 0;
		
		name = $.trim($(this).val());
		
		if(name !== '')
		{
			clearTimeout(in_id);
			
			in_id = setTimeout(function()
			{
				$.postJSON
				(
					'inventory/add_edit_cat',
					{cat_id:cat_id, name:name},
					function(data)
					{
						alert(print_r(data));
						// ths.parent().children('a').text(data.name).removeClass('d_none');
						
						// if(data.id)
							// ths.parents('div.card').attr('id', data.id);
					},
					"json"
				);
			}, 
			300);
		}
		else
			return false;
	}); */
	
	$('input.cat_name').on('blur', function()
	{
		if($.trim($(this).val()) !== '')
		{
			$(this).parent().children('a').removeClass('d_none');
			$(this).addClass('d_none');
		}
	});
}
$(add_edit_cat);



function add_string()
{
	$('#myTabContent').on('click', '.add_string', function(){
		
		par = $(this).closest('.card');
		
		cat_id = par.children('.card-header').attr('data-catalog');
		
		if(structure_tab[cat_id] && structure_tab[cat_id].length !== 0 && !$('#body_table_'+cat_id).children('div').is('.new_string'))
		{
			$('#body_table_'+cat_id+' > div').eq(0).after($('#file_conteiner_'+cat_id).html());
			// par.find('.body_table .string:first').after($('#file_conteiner_'+cat_id).html());
			
			$('#body_table_'+cat_id+' > div').eq(1).addClass('new_string').find('.inventory_number, .attach_user>p').empty();
			// par.find('.body_table .string').eq(1).addClass('new_string').find('.inventory_number').empty();
			
			if(!$('#body_table_'+cat_id+' > div').eq(2).hasClass('bg-light'))
				$('#body_table_'+cat_id+' > div').eq(1).addClass('bg-light');
			// if(!par.find('.body_table .string').eq(2).hasClass('bg-light'))
				// par.find('.body_table .string').eq(1).addClass('bg-light');
			
			$('#body_table_'+cat_id+' > div').eq(0).find('.parent').filter(function(index){
				
				
				$('#body_table_'+cat_id+' > .new_string').find('.parent_'+this.getAttribute('data-parent')).children().css('width', this.clientWidth).parent().parent().css('width', this.clientWidth);
			});
			/* par.find('.body_table .string:first').find('.parent').filter(function(index){
				
				par.find('.body_table .new_string').find('.parent_'+this.getAttribute('data-parent')).children().css('width', this.clientWidth).parent().parent().css('width', this.clientWidth);
				
				// if(this.nextSibling.classList.contains('d_none') == false)
					// par.find('.body_table .new_string').find('.parent_'+this.getAttribute('data-parent')).css('height', '50%').siblings().css('height', '50%');
			}); */
			
			$('#body_table_'+cat_id+' > .new_string').width($('#body_table_'+cat_id+' > div').eq(0).innerWidth());
			// par.find('.body_table .new_string').width(par.find('.body_table .string:first').innerWidth());
			
			$.map(structure_tab[cat_id], function(field, index){
				
				if(field['type_field'] == 'button_delete')
					$('#body_table_'+cat_id+' > .new_string').find('.parent_'+index+' .flex_field').html(field['html']);
			});
		}
	});
}
$(add_string);

function edit_string()
{
	$('body').on('click', '.flex_field', function(){
		
		// flex_fields = $('.card-body').find('.flex_field').addClass('px-1');
		// flex_fields.children('form, input, textarea, .btn-group').addClass('d_none').removeClass('inline_table');
		// flex_fields.children('p').removeClass('d_none');
		flex_fields = $('#edit_field').addClass('px-1').removeAttr('id');
		flex_fields.children('form, input, textarea, .btn-group').addClass('d_none').removeClass('inline_table');
		flex_fields.children('p').removeClass('d_none');
		
		field_block = '';
		structure_id = '';
		
		field_block = $(this);
		
		cat_id = field_block.closest('.card').children('.card-header').attr('data-catalog');
		
		// console.log(print_r(structure_tab[cat_id][0]['html']));
		// return false;
		
		field_block.attr('id', 'edit_field');
		
		structure_id = field_block.parent().attr('data-parent');
		
		field_block.css('paddingTop', 0).removeClass('px-1');
		
		field_block.children('p').addClass('d_none');
		
		field_text = field_block.children('p').text();
		
		if(structure_id == -1)
		{
			field_block.parent().popover('dispose');
			
			if(!field_block.children().is('.btn-group'))
				field_block.append(structure_tab[cat_id][-1]['html']);
			
			field_block.children('.btn-group').addClass('inline_table').removeClass('d_none');
			
			field_block.find('.btn-group').children('button').focus().html(field_text);
			field_block.find('.btn-group .dropdown-menu').addClass('show').children('button[value="'+field_block.children('span').text()+'"]').addClass('active');
		}
		else if(structure_tab[cat_id][structure_id].type_field == 'selection' || structure_tab[cat_id][structure_id].type_field == 'selection_custom')
		{
			field_block.parent().popover('dispose');
			
			if(!field_block.children().is('.btn-group'))
				field_block.append(structure_tab[cat_id][structure_id]['html'].html);
			
			field_block.children('.btn-group').addClass('inline_table').removeClass('d_none');
		
			field_block.find('.btn-group').children('button').focus().html(field_text);
			field_block.find('.btn-group .dropdown-menu').addClass('show').children('button[value="'+field_block.children('span').text()+'"]').addClass('active');
		}
		else if(structure_tab[cat_id][structure_id].type_field == 'textarea')
		{
			if(!field_block.children().is('textarea'))
				field_block.append(structure_tab[cat_id][structure_id].html);
			
			field_block.children('textarea').val(field_text).removeClass('d_none').css({'minHeight':field_block.css('height'), 'position':'absolute', 'width':field_block.css('width'), 'z-index':1, 'height':Math.ceil(field_text.length / (parseInt(field_block.css('width')) / 10)) * 16+'px'}).focus();
		}
		else if(structure_tab[cat_id][structure_id].type_field == 'file')
		{
			field_block.children('form').removeClass('d_none').children('input').removeClass('d_none');
			
			if(!field_block.children().is('form'))
				field_block.append('<form name="upload_file" enctype="multipart/form-data">'+structure_tab[cat_id][structure_id].html+'</form>');
		}
		else if(structure_tab[cat_id][structure_id].type_field == 'date')
		{
			if(!field_block.children().is('input'))
				field_block.append(structure_tab[cat_id][structure_id].html);
			
			
			if(field_text !== '')
			{
				date_format = field_text.split('.');
				
				field_block.find('input').removeClass('d_none').val(date_format[2]+'-'+date_format[1]+'-'+date_format[0]).focus();
			}
			else
				field_block.find('input').removeClass('d_none').focus();
		}
		else if(structure_tab[cat_id][structure_id].type_field !== 'button_delete')
		{
			if(!field_block.children().is('input'))
				field_block.append(structure_tab[cat_id][structure_id].html);
			
			if(field_text !== '')
				field_block.find('input').removeClass('d_none').val(field_text).focus();
			else
				field_block.find('input').removeClass('d_none').focus();
		}
	});
	
	
	$('body').on('click', '.dropdown-toggle', function(){
		
		button_selection = $(this);
		
		button_selection.closest('.parent').popover('dispose');
		
		$('.flex_field').children('input, textarea, form, span').addClass('d_none');
		$('.flex_field').children('.btn-group').not(button_selection.parent()).addClass('d_none');
		
		dropdown_menu = button_selection.siblings('.dropdown-menu');
		
		if(dropdown_menu.hasClass('show'))
			dropdown_menu.removeClass("show");
		else
			dropdown_menu.addClass("show");
		
		$(un_bn);
		
		return false;
	});
	
	$('.flex_field').on('click', 'a', function(e){
		
		e.preventDefault();
		
		window.location = $(this).attr('href');
		
		return false;
	});
}
$(edit_string);


function set_string()
{
	/* $('body').on('keyup paste cut click change', '.flex_field input, .flex_field form input, .flex_field textarea', function(){
		
		input_text = '';
		
		if($(this).attr('type') == 'date')
		{
			date_format = '';
			
			input_value = $(this).val();

			if(input_value !== '')
			{
				date_format = input_value.split('-');
				input_value = date_format[2]+'.'+date_format[1]+'.'+date_format[0];
				
			}
		}
		else
			input_value = $.trim($(this).val());
		
		input_text = input_value;
		
		// console.log(input_text);
		// return false;
		
		if(input_value != $(this).siblings('p').text())
		{
			file_id = 0;
			input_id = 0;
			
			tag_name = this.tagName.toLowerCase();
			
			if(tag_name == 'input')
				input_type = $(this).attr('type');
			else
				input_type = tag_name;
			
			
			if(!$(this).closest('.string').hasClass('new_string'))
			{
				file_id = parseInt($(this).closest('.string').attr('data-file'));
				
				if(input_type == 'file')
					input_id = parseInt($(this).parent().siblings('p').attr('data-input-id'));
				else
					input_id = parseInt($(this).siblings('p').attr('data-input-id'));
			}
			
			// console.log(input_id);
			// return false;
			
			structure_id = parseInt($(this).closest('div[data-parent]').attr('data-parent'));
			catalog_id = parseInt($(this).closest('div[data-catalog-body]').attr('data-catalog-body'));
			
			$(this).siblings('p').text(input_value);
			
			
			
			ths = $(this);
			
			if(input_type == 'file')
			{
				$.ajaxSetup({
					headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
				});
				
				var file_data = ths.prop('files')[0];
				
				var form_data = new FormData();
				
				form_data.append('file', file_data);
				form_data.append('file_id', file_id);
				form_data.append('catalog_id', catalog_id);
				form_data.append('id', input_id);
				form_data.append('structure_id', structure_id);

				ths.parent('form').addClass('d_none');
				ths.addClass('d_none');
				ths.closest('.flex_field').children('p').html('<div class="progress"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="100" aria-valuemax="100" style="width: 100%"></div></div>');
				ths.closest('.flex_field').children('p').removeClass('d_none');
				
				$.ajax
				(
					{
						url:'/laravel/public/inventory/set_file',
						type:'post',
						data:form_data,
						cache:false,
						data_type:'json',
						processData:false,
						contentType:false,
						success:function(file){
						
							if(file['field_id'])
								ths.closest('.flex_field').children('p').attr('data-input-id', file['field_id']);
							
							if(file['inventory_number'])
								ths.closest('.string').removeClass('new_string').attr('data-file', file['file_id']).find('.inventory_number').html('<p>'+file['inventory_number']+'</p>');
						
							ths.closest('.flex_field').children('p').html(file['link']);
						}
					}
				);
				
				$(un_bn);
			}
			else
			{
				clearTimeout(in_id);
				
				in_id = setTimeout(function()
				{
					if(input_type == 'date')
					{
						if(date_format !== '')
							input_value = date_format[0]+'-'+date_format[1]+'-'+date_format[2];
					}
					// console.log(print_r(input_value));
					$.postJSON
					(
						'/laravel/public/inventory/set_string',
						{file_id:file_id, id:input_id, input_type:input_type, value:input_value, structure_id:structure_id, catalog_id:catalog_id, parent_id:0, text:input_text},
						function(data)
						{
							if(data == 'validation_error')
								alert('Ошибка ввода данных! Превышено максимально допустимое число символов!');
							else if(data['file_id'])
								ths.closest('.new_string').attr('data-file', data['file_id']).removeClass('new_string').addClass('string').find('.inventory_number').html(data['inventory_number']);
							
							if(data['input_id'])
								ths.siblings('p').attr('data-input-id', data['input_id']);
						},
						"json"
					);
					
					$(un_bn);
					
				}, 300);
			}
		}
	}); */
	
	
	
	$('body').on('keyup paste cut click change', '.flex_field>input, .flex_field>form input, .flex_field>textarea', function(){
		
		input_text = '';
		input_value = '';
		tag_name = '';
		
		if($(this).attr('type') == 'date')
		{
			date_format = '';
			
			input_value = $(this).val();

			if(input_value !== '')
			{
				date_format = input_value.split('-');
				input_value = date_format[2]+'.'+date_format[1]+'.'+date_format[0];
				
			}
		}
		else
			input_value = $.trim($(this).val());
		
		input_text = input_value;
		
		// console.log(input_text);
		// return false;
		
		if(input_value != $(this).siblings('p').text())
		{
			input_type = '';
			file_id = 0;
			input_id = 0;
			
			tag_name = this.tagName.toLowerCase();
			
			if(tag_name == 'input')
				input_type = $(this).attr('type');
			else
				input_type = tag_name;
			
			if(!$(this).closest('.string').hasClass('new_string'))
			{
				file_id = parseInt($(this).closest('.string').attr('data-file'));
				
				if(input_type == 'file')
					input_id = parseInt($(this).parent().siblings('p').attr('data-input-id'));
				else
					input_id = parseInt($(this).siblings('p').attr('data-input-id'));
			}
			
			// console.log(input_id);
			// return false;
			structure_id = '';
			catalog_id = '';
			
			structure_id = parseInt($(this).closest('div[data-parent]').attr('data-parent'));
			catalog_id = parseInt($(this).closest('div[data-catalog-body]').attr('data-catalog-body'));
			
			$(this).siblings('p').text(input_value);
			
			
			
			ths = $(this);
			
			if(input_type == 'file')
			{
				$.ajaxSetup({
					headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
				});
				
				var file_data = ths.prop('files')[0];
				
				var form_data = new FormData();
				
				form_data.append('file', file_data);
				form_data.append('file_id', file_id);
				form_data.append('catalog_id', catalog_id);
				form_data.append('id', input_id);
				form_data.append('structure_id', structure_id);

				ths.parent('form').addClass('d_none');
				ths.addClass('d_none');
				ths.closest('.flex_field').children('p').html('<div class="progress"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="100" aria-valuemax="100" style="width: 100%"></div></div>');
				ths.closest('.flex_field').children('p').removeClass('d_none');
				
				$.ajax
				(
					{
						url:'/laravel/public/inventory/set_file',
						type:'post',
						data:form_data,
						cache:false,
						data_type:'json',
						processData:false,
						contentType:false,
						success:function(file){
						
							if(file['field_id'])
								ths.closest('.flex_field').children('p').attr('data-input-id', file['field_id']);
							
							if(file['inventory_number'])
								ths.closest('.string').removeClass('new_string').attr('data-file', file['file_id']).find('.inventory_number').html('<p>'+file['inventory_number']+'</p>');
						
							ths.closest('.flex_field').children('p').html(file['link']);
						}
					}
				);
				
				$(un_bn);
			}
			else
			{
				clearTimeout(in_id);
				
				in_id = setTimeout(function()
				{
					if(input_type == 'date')
					{
						if(date_format !== '')
							input_value = date_format[0]+'-'+date_format[1]+'-'+date_format[2];
					}
							
					$.postJSON
					(
						'/laravel/public/inventory/set_string',
						{file_id:file_id, id:input_id, input_type:input_type, value:input_value, structure_id:structure_id, catalog_id:catalog_id, parent_id:0, text:input_text},
						function(data)
						{
							if(data == 'validation_error')
								alert('Ошибка ввода данных! Превышено максимально допустимое число символов!');
							else if(data['file_id'])
								ths.closest('.new_string').attr('data-file', data['file_id']).removeClass('new_string').addClass('string').find('.inventory_number').html(data['inventory_number']);
							
							if(data['input_id'])
								ths.siblings('p').attr('data-input-id', data['input_id']);
						},
						"json"
					);
					
					$(un_bn);
					
				}, 300);
			}
		}
	});
	
	
	
	$('body').on('click', '.delete_str', function(){
		
		par = $(this).closest('div[data-file]');
		id = par.attr('data-file');
		catalog_id = parseInt($(this).closest('div[data-catalog-body]').attr('data-catalog-body'));
		
		par.addClass('border border-danger');
		// $('div[data-file-parent="'+id+'"]').addClass('border border-danger');
		
		if(id == '')
		{
			clearTimeout(in_id);
			
			in_id = setTimeout(function()
			{
				if(confirm('Удалить строку?'))
				{
					if(par.hasClass('new_string'))
						par.remove();
					else
						return false;
				}
				else
					par.removeClass('border border-danger');
			}
			, 50);
			
			$(un_bn);
		}
		else if(id > 0)
		{
			clearTimeout(in_id);
			
			in_id = setTimeout(function()
			{
				if(confirm('Удалить строку?'))
				{
					$.postJSON
					(
						'/laravel/public/inventory/set_string',
						{delete:id, catalog_id:catalog_id},
						function(data)
						{
							par.remove();
							
							if(data !== 1)
							{
								for(var item in data)
								{
									$('div[data-file="'+data[item]+'"]').remove();
									// alert(print_r(data[item]));
									// return false;
								}
							}
						},
						"json"
					);
					
					$(un_bn);
				}
				else
					par.removeClass('border border-danger');
			}
			, 50);
			
			$(un_bn);
		}
	});
	
	
	
	
	$('body').on('click', 'p .close', function(e){
		
		
		e.preventDefault();
		
		if(confirm('Удалить файл "'+$(this).siblings('a').text()+'"?'))
		{
			par = $(this).parent('p');
			id = $(this).parent('p').attr('data-input-id');
			file_id = parseInt($(this).closest('.string').attr('data-file'));
			structure_id = parseInt($(this).closest('div[data-parent]').attr('data-parent'));
			catalog_id = parseInt($(this).closest('div[data-catalog-body]').attr('data-catalog-body'));
			
			$(this).parent('p').html('<div class="progress"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="100" aria-valuemax="100" style="width: 100%"></div></div>');
			
			$.postJSON
			(
				'inventory/set_file',
				{delete:id, file_id:file_id, structure_id:structure_id, catalog_id:catalog_id},
				function(data)
				{
					if(data == 1)
					{
						par.empty().attr('data-input-id', '');
					}
				},
				"json"
			);
			
		}
		return false;
	});
	
	
	$('body').on('click', '.collapse .dropdown-item', function(){
		
		cur_flex_field = $(this).closest('.flex_field');
		
		cur_btn_value = cur_flex_field.children('span').text();
		
		btn_text = $(this).text();
		
		btn_value = parseInt($(this).val());
		
		// console.log(cur_btn_value);
		// return false;
		
		
		if(btn_value !== cur_btn_value)
		{
			file_id = 0;
			input_id = 0;
			selection_id = 0;
			catalog_id = 0;
			
			structure_id = parseInt($(this).closest('.parent').attr('data-parent'));
			
			if(!$(this).closest('.string').hasClass('new_string'))
			{
				file_id = parseInt($(this).closest('.string').attr('data-file'));
				
				if(structure_id !== -1)
				{
					input_id = $(this).closest('.flex_field').children('p').attr('data-input-id');
				
					if(input_id == '')
						input_id = 0;
					else
						input_id = parseInt(input_id);
				}
			}
			
			input_type = $(this).closest('.btn-group').children('button').attr('data-selection');
			
			if(structure_id !== -1)
				selection_id = parseInt($(this).closest('.btn-group').children('button').attr('data-selection-id'));
			
			catalog_id = parseInt($(this).closest('.collapse').attr('data-catalog-body')); 
			
			// console.log(btn_text);
			// return false;
			ths = $(this);
			
			// console.log(btn_value);
			
			$.postJSON
			(
				'/laravel/public/inventory/set_string',
				{file_id:file_id, id:input_id, input_type:input_type, value:btn_value, selection_id:selection_id, structure_id:structure_id, catalog_id:catalog_id, parent_id:0, text:btn_text},
				function(data)
				{
					if(data['file_id'])
						ths.closest('.new_string').attr('data-file', data['file_id']).removeClass('new_string').addClass('string').find('.inventory_number').html(data['inventory_number']);
					
					if(data['input_id'])
						ths.closest('.btn-group').siblings('p').attr('data-input-id', data['input_id']);
					
					ths.addClass('active').siblings('.dropdown-item').removeClass('active');
					
					cur_flex_field.children('p').html(btn_text).removeClass('d_none').siblings('span').html(btn_value);
					ths.parent().removeClass('show').closest('.btn-group').addClass('d_none');
				},
				"json"
			);
			ths.unbind();
		}
	});
}
$(set_string);


function edit_user_role()
{
	$('body').on('change', 'input[name="role"]', function(){
		
		$(this).siblings('input').prop('checked', false);
		
		
		cur_checked = $(this).prop('checked');
		siblings_checked = $(this).siblings('input[name="role"]').prop('checked');
		
		par = $(this).closest('li[data-catalog-id]');
		level = par.attr('data-level');
		cur_index = par.index('li[data-catalog-id]');
		
		// alert(level+' '+cur_index);
		
		$('ul.dropdown-menu li[data-catalog-id]').each(function(index, element){
			
			if(index > cur_index)
			{
				if($(this).attr('data-level') <= level)
					return false;
				else
				{
					if(cur_checked == false && siblings_checked == false)
					{
						$(this).removeClass('text-muted');
						$(this).find('input[type="checkbox"]').prop('disabled', false);
					}
					else
					{
						$(this).addClass('text-muted');
						$(this).find('input[type="checkbox"]').attr('disabled', true).prop('checked', false);
					}
				}
			}
		});
	});
	
	$('#profile').on('click', '.add_user, .save', function(){
		
		if($(this).parent().siblings('select[name="user"]').val() !== '')
		{
			if(confirm('Изменить права пользователя '+$(this).parent().siblings('select[name="user"]').children('option:checked').text()+'?'))
			{
				user_id = $(this).parent().siblings('select[name="user"]').val();
				
				$(this).parent().html('<button type="button" class="btn btn-info add_user">Добавить</button>');
				
				if(user_id == '' || user_id == 0)
					return false;
				
				var array_checkbox = new Array();
				
				$('ul.dropdown-menu').find('input:checked').each(function(index){
					
					if($(this).attr('disabled') == undefined)
						array_checkbox[$(this).closest('li').attr('data-catalog-id')] = $(this).attr('value');
				});
				
				$.postJSON
				(
					'inventory/set_user_role',
					{user_id:user_id, array_checkbox:array_checkbox},
					function(data)
					{
						if(data === 0)
							alert('Учетная запись пользователя отключена!');
						else
							$('table.users tbody').html(data);
						
						$('select[name="user"] option:first').prop('selected', true);
						$('input[type="checkbox"][name="role"]').attr('disabled', false).prop('checked', false);
						$('li[data-catalog-id][data-level]').removeClass('text-muted');
					},
					"html"
				);
			}
		}
	});
	
	
	$('body').on('change', 'select[name="user"]', function(){
		
		user_id = $(this).val();
		
		// $(this).siblings('span.input-group-btn').html('<button type="button" class="btn btn-info add_user">Добавить</button>');
		$(this).siblings('.dropdown').find('li[data-catalog-id]').removeClass('text-muted');
		$(this).siblings('.dropdown').find('input[type="checkbox"]').attr('disabled', false).prop('checked', false);
		$('.users').find('tr[data-user-id]').removeClass('bg-light');
		
		if(user_id == '' || user_id == 0)
			return false;
		
		ths = $(this);
		
		$.postJSON
		(
			'inventory/get_user_role',
			{old_user_id:user_id},
			function(data)
			{
				if($('.users tbody tr').is('[data-user-id="'+data['user_id']+'"]'))
				{
					$('.users').find('tr[data-user-id="'+data['user_id']+'"]').addClass('bg-light');
					ths.siblings('.input-group-append').html('<button type="button" class="btn btn-light border_button text-success save" title="Сохранить изменения"><span class="oi oi-check"></span></button><button type="button" class="btn btn-light border_button text-danger cancel" title="Отменить изменения"><span class="oi oi-x"></span></button>');
				}
				else
					ths.siblings('.input-group-append').html('<button type="button" class="btn btn-info add_user">Добавить</button>');
				
				
				if(data['parent_user']['role'] == 1)
				{
					$('.dropdown-menu li[data-catalog-id]').addClass('text-muted');
					$('.dropdown-menu li[data-catalog-id]').find('input[type="checkbox"]').attr('disabled', true);
					
					for(var item in data['roles_parent_user'])
					{
						if(data['roles_parent_user'][item] == 1)
						{
							$('.dropdown-menu li[data-catalog-id="'+item+'"]').removeClass('text-muted');
							$('.dropdown-menu li[data-catalog-id="'+item+'"]').find('input[type="checkbox"]').attr('disabled', false);
							$('.dropdown-menu li[data-catalog-id].'+item).removeClass('text-muted');
							$('.dropdown-menu li[data-catalog-id].'+item).find('input[type="checkbox"]').attr('disabled', false);
						}
					}
				}
				
				if(data['roles_user'])
				{
					for(var item in data['roles_user'])
					{
						$('.dropdown-menu li[data-catalog-id="'+data['roles_user'][item]['catalog_id']+'"]').find('input[value="'+data['roles_user'][item]['role']+'"]').prop('checked', true);
						
						if(data['roles_user'][item]['parent_user'] == data['parent_user']['id'])
						{
							$('.dropdown-menu li[data-catalog-id].'+data['roles_user'][item]['catalog_id']).addClass('text-muted');
							$('.dropdown-menu li[data-catalog-id].'+data['roles_user'][item]['catalog_id']).find('input[type="checkbox"]').attr('disabled', true);
						}
					}
				}
			},
			"json"
		);
	});
	
	
	$('body').on('click', '.edit_user', function(){
		
		user_id = $(this).closest('tr').attr('data-user-id');
		
		$('.dropdown-menu li[data-catalog-id]').removeClass('text-muted');
		$('#profile').find('input[type="checkbox"]').attr('disabled', false).prop('checked', false);
		$('.users').find('tr[data-user-id]').removeClass('bg-light');
		
		$.postJSON
		(
			'inventory/get_user_role',
			{user_id:user_id},
			function(data)
			{
				if($('.users tbody tr').is('[data-user-id="'+data['user_id']+'"]'))
				{
					$('.users').find('tr[data-user-id="'+data['user_id']+'"]').addClass('bg-light');
					$('.users').parent().siblings('.row').find('.input-group-append').html('<button type="button" class="btn btn-light border_button text-success save" title="Сохранить изменения"><span class="oi oi-check"></span></button><button type="button" class="btn btn-light border_button text-danger cancel" title="Отменить изменения"><span class="oi oi-x"></span></button>');
				}
				
				if(data['parent_user']['role'] == 1)
				{
					$('.dropdown-menu li[data-catalog-id]').addClass('text-muted');
					$('.dropdown-menu li[data-catalog-id]').find('input[type="checkbox"]').attr('disabled', true);
					
					for(var item in data['roles_parent_user'])
					{
						if(data['roles_parent_user'][item] == 1)
						{
							$('.dropdown-menu li[data-catalog-id="'+item+'"]').removeClass('text-muted');
							$('.dropdown-menu li[data-catalog-id="'+item+'"]').find('input[type="checkbox"]').attr('disabled', false);
							$('.dropdown-menu li[data-catalog-id].'+item).removeClass('text-muted');
							$('.dropdown-menu li[data-catalog-id].'+item).find('input[type="checkbox"]').attr('disabled', false);
						}
					}
				}
					
				if(data['roles_user'])
				{
					for(var item in data['roles_user'])
					{
						$('.dropdown-menu li[data-catalog-id="'+data['roles_user'][item]['catalog_id']+'"]').find('input[value="'+data['roles_user'][item]['role']+'"]').prop('checked', true);
						
						if(data['roles_user'][item]['parent_user'] == data['parent_user']['id'])
						{
							$('.dropdown-menu li[data-catalog-id].'+data['roles_user'][item]['catalog_id']).addClass('text-muted');
							$('.dropdown-menu li[data-catalog-id].'+data['roles_user'][item]['catalog_id']).find('input[type="checkbox"]').attr('disabled', true);
						}
					}
				}
				
				if(data['okbdb_user_id'])
					$('select[name="user"] option[value="'+data['okbdb_user_id']+'"]').prop('selected', true);
			},
			"json"
		);
	});
	
	
	$('body').on('click', '.delete_user', function(){
		
		user_id = $(this).closest('tr').attr('data-user-id');
		
		if(confirm('Удалить права пользователя '+$(this).closest('tr').children('td:first').text()+'?'))
		{
			$.postJSON
			(
				'inventory/set_user_role',
				{delete:user_id},
				function(data)
				{
					$('table.users tbody').html(data);
					
					$('select[name="user"] option:first').prop('selected', true);
					$('input[type="checkbox"][name="role"]').attr('disabled', false).prop('checked', false);
					$('li[data-catalog-id][data-level]').removeClass('text-muted');
				},
				"html"
			);
		}
	});
	
	
	$('#profile').on('click', 'button.cancel', function(){
		
		user_name = $(this).parent().siblings('select[name="user"]').children('option:checked').text();
		
		if(confirm('Отменить редактирование прав пользователя '+user_name+'?'))
		{
			$('select[name="user"] option:first').prop('selected', true);
			$('input[type="checkbox"][name="role"]').attr('disabled', false).prop('checked', false);
			$('li[data-catalog-id][data-level]').removeClass('text-muted');
			$('table.users').find('tr[data-user-id]').removeClass('bg-light');
			$(this).parent().html('<button type="button" class="btn btn-info add_user">Добавить</button>');
		}
	});
}
$(edit_user_role);


function show_log_list()
{
	$('body').on('mouseenter', '.log_flag', function(){
		
		
		th = $(this);
		
		f_id = $(this).closest('.string').attr('data-file');
		str_id = $(this).attr('data-parent');
		catalog_id = $(this).closest('.collapse').attr('data-catalog-body');
		
		// console.log(f_id+' - '+str_id+' - '+catalog_id);
		// return false;
		clearTimeout(clear_time);
		$('.log_flag').popover('dispose');
		
		clear_time = setTimeout(function()
		{
			// console.log(str_id);
			// return false
			
			if(f_id > 0 && structure_tab[catalog_id][str_id] && structure_tab[catalog_id][str_id]['log_flag'] == 1 || str_id == -1)
			{
				$.postJSON
				(
					'/laravel/public/inventory/get_log',
					{file_id:f_id, structure_id:str_id, catalog_id:catalog_id},
					function(data)
					{
						if(data != 0)
						{
							th.popover({
								container:'body',
								content:data,
								html:true,
								placement:'auto',
								trigger:'manual',
								template:'<div class="popover" style="max-width:450px;" role="tooltip"><div class="arrow"></div><button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3 class="popover-header" ></h3><div class="popover-body"></div></div>'
							});
							
							th.popover('show');
							
							th.unbind();
						}
					},
					"html"
				);
			}
			
		}, 200);
	});
	
	$('body').on('mouseleave', '.log_flag', function(){
		
		clearTimeout(clear_time);
		$('.log_flag').popover('dispose');
		
		$(this).unbind();
	});
	
	$('body').on('click', '.popover .close', function(){
		
		clearTimeout(clear_time);
		$('.log_flag').popover('dispose');
		
		$(this).unbind();
	});
}
$(show_log_list);


function stop_togle()
{
	var ct_id;
	
	$('#button_users').on('click',function(){
		
		return false;
	});
	
	$('#button_users').hover(
	function()
	{
		clearTimeout(ct_id);
		if($('#user_role_cat').css('display') == 'none')
			$('#user_role_cat').css('display', 'block');
	},
	function()
	{
		ct_id = setTimeout(function()
		{
			$('#user_role_cat').css('display', 'none');
		}, 200);
	});
	
	$('#user_role_cat').hover(
	function()
	{
		clearTimeout(ct_id);
		if($('#user_role_cat').css('display') == 'none')
			$('#user_role_cat').css('display', 'block');
	},
	function()
	{
		ct_id = setTimeout(function()
		{
			$('#user_role_cat').css('display', 'none');
		}, 200);
	});
}
$(stop_togle);


function order_table()
{
	$('body').on('click', '.sticky-top .order', function(){
		
		ths = $(this);
		
		table_id = ths.closest('.body_table').attr('id');
		
		structure_id = ths.parent().attr('data-parent');
		
		data_type = ths.parent().attr('data-type');
		
		arr = [];
		
		$('#'+table_id+' > :not(.new_string) .parent_'+structure_id+' > .order > p').map(function(indx, element){
			
			if(indx > 0)
				arr[indx] = {id:$(element).closest('.string').attr('data-file'), text:$.trim($(element).text().toLowerCase())};
		});
		
		// console.log(arr);
		// return false;
		
		if(data_type == 'number')
			arr.sort(function(a, b){return parseInt(a.text) - parseInt(b.text);});
		else if(data_type == 'date')
		{
			arr.sort(function(a, b){
				
				a_text = '';
				b_text = '';
				
				if(a.text == '')
					a_text = 0;
				else
				{
					a_text = a.text.split('.');
					a_text = a_text[2]+a_text[1]+a_text[0];
				}
				
				if(b.text == '')
					b_text = 0;
				else
				{
					b_text = b.text.split('.');
					b_text = b_text[2]+b_text[1]+b_text[0];
				}
				
				return parseInt(a_text) - parseInt(b_text);
			});
		}
		else if(data_type == 'text' || data_type == 'selection' || data_type == 'selection_custom')
		{
			arr.sort(function(a, b){
				return a.text.localeCompare(b.text);
			});
		}
		
		ths.closest('.sticky-top').find('.order').removeClass('order_current').children('.oi').remove();
		ths.addClass('order_current');
		
		if(ths.hasClass('reverse') == false)
		{
			ths.closest('.sticky-top').find('.order').removeClass('reverse');
			ths.addClass('reverse').append(' <span class="oi oi-sort-descending"></span>');
			arr.reverse();
		}
		else
			ths.removeClass('reverse').append(' <span class="oi oi-sort-ascending"></span>');
		
		arr.map(function(file, index){
			
			if(index & 1)
				$('#'+table_id).find('.string[data-file="'+file.id+'"]').addClass('bg-light').appendTo('#'+table_id);
			else
				$('#'+table_id).find('.string[data-file="'+file.id+'"]').removeClass('bg-light').appendTo('#'+table_id);
		});
	});
}
$(order_table);




$(function()
{
	$('body').on('click', '#search', function(){
		
		value = $.trim($(this).parent().siblings('.search').val());
		
		if(value !== '' && value.length > 3)
			search(value);
		else
			alert('Длина поискового запроса должна быть не менее 3-х символов.');
	});
	
	$('body').on('keypress', '.search', function(e){
		
		if(e.keyCode==13)
		{
			value = $.trim($(this).val());
			
			if(value !== '' && value.length > 3)
				search(value);
			else
				alert('Длина поискового запроса должна быть не менее 3-х символов.');
		}
	});
	
	$('body').on('click', '#search_reset', function(){
		
		search('');
		
		$(this).parent().siblings('.search').val('');
		
		$(this).addClass('d_none');
	});
});



function search(value)
{
	$('.search').addClass('search_spin');
	$.postJSON
	(
		'inventory/search',
		{value:value},
		function(data)
		{
			$('#catalog_conteiner').empty();
			
			if(data !== 0)
			{
				if(value == '')
				{
					$('#catalog_conteiner').append(data['result_html']);
				}
				else
				{
					$.map(data, function(element, index){
						
						$('#catalog_conteiner').append(element['catalog']);
						
						structure_tab[index] = '';
						file_conteiner = '';
						
						html = '<div id="body_table_'+index+'" class="body_table border border-right-0 border-left-0 border-top-0"><div class="d-flex flex-row string"></div></div><div id="file_conteiner_'+index+'" class="d_none"></div>';
						structure_tab[index] = element['structure_table'];
						
						$('#catalog_'+index).html(html);
						
						body_table = $('#body_table_'+index);
						
						var field_list = new Array();
						
						file_conteiner = $('#file_conteiner_'+index);
						
						file_conteiner.html('<div class="d-flex flex-row string" data-file=""><div class="flex_conteiner border-0"><div class="d-flex flex-row parent parent_0" data-parent="0" data-type="number"><div class="flex_conteiner inventory_number order"><p>Инвентарный номер</p></div></div><div class="d-flex flex-row d_none child child_0" data-child = "0"></div></div><div class="flex_conteiner border-0"><div class="d-flex flex-row log_flag parent parent_-1" data-parent="-1" data-type="selection"><div class="flex_conteiner flex_field order attach_user"><p>Ответственный</p></div></div><div class="d-flex flex-row d_none child child_-1" data-child = "-1"></div></div></div>');
						
						$.map(element['structure_table'], function(elem, indx){
							
							if(elem['type_field'] == 'button_delete')
								field_list[indx] = elem['html'];
							
							log_flag_class = '';
							
							if(elem['log_flag'] == 1)
								log_flag_class = 'log_flag ';
							
							order_class = '';
							data_type = '';
							
							if(elem['order'] == 1)
							{
								order_class = ' order';
								data_type = ' data-type="'+elem['type_field']+'"';
							}
							
							if(indx !== -1)
							{
								style = 'style="';
								
								if(elem['width'])
									style += 'width:'+elem['width']+'px;';
								
								if(elem['height'])
									style += ' height:'+elem['height']+'px;';
								
								style += '"';
								
								if(elem['parent_id'] == 0)
									file_conteiner.children('.string').append('<div class="flex_conteiner border-0"><div class="d-flex flex-row '+log_flag_class+'parent parent_'+elem['id']+'" data-parent = "'+elem['id']+'"'+data_type+' '+style+'><div class="flex_conteiner flex_field'+order_class+'"><p>'+elem['name']+'</p></div></div><div class="d-flex flex-row d_none child child_'+elem['id']+'" data-child = "'+elem['id']+'"></div></div>');
								else
									file_conteiner.find('.child_'+elem['parent_id']).removeClass('d_none').append('<div class="flex_conteiner border-0"><div class="d-flex flex-row '+log_flag_class+'parent parent_'+elem['id']+'" data-parent = "'+elem['id']+'"'+data_type+' '+style+'><div class="flex_conteiner flex_field'+order_class+'"><p>'+elem['name']+'</p></div></div><div class="d-flex flex-row d_none child child_'+elem['id']+'" data-child = "'+elem['id']+'"></div></div>');
							}
						});
						
						file_conteiner.find('.parent_0').width(100);
						file_conteiner.find('.parent_-1').width(105);
						
						body_table.html(file_conteiner.children().clone());
						
						body_table.find('div').removeClass('flex_field log_flag');
						
						
						$('#file_conteiner_'+index).find('.flex_field').html('<p data-input-id=""></p>');
						file_conteiner = $('#file_conteiner_'+index).html();
						
						if(element['table'])
						{
							count_array = 0;
							
							$.map(element['table'], function(elem, indx){
								
								if(count_array & 1)
									string = body_table.append(file_conteiner).children(':last').attr('data-file', indx).addClass('bg-white');
								else
									string = body_table.append(file_conteiner).children(':last').attr('data-file', indx).addClass('bg_string_light');
								
								++count_array;
								
								if($.isEmptyObject(elem) == true)
								{
									string.find('.inventory_number > p').html(element['files'][indx]['inventory_number']);
									
									if(element['files'][indx]['attach_user'])
										string.find('.attach_user').html('<p>'+element['files'][indx]['attach_user']['name']+'</p><span class="d_none select_value">'+element['files'][indx]['attach_user_id']+'</span>');
									else
										string.find('.attach_user').html('<p></p><span class="d_none select_value"></span>');
								}
								else
								{
									$.map(elem, function(el, ind){
										
										string.find('.inventory_number > p').html(element['files'][el['file_id']]['inventory_number']);
										
										if(element['files'][el['file_id']]['attach_user'])
											string.find('.attach_user').html('<p>'+element['files'][el['file_id']]['attach_user']['name']+'</p><span class="d_none select_value">'+element['files'][el['file_id']]['attach_user_id']+'</span>');
										else
											string.find('.attach_user').html('<p></p><span class="d_none select_value"></span>');
										
										cur_cell = string.find('.parent_'+ind+' > div');
										
										type_field = element['structure_table'][ind].type_field;
										
										if(type_field == 'selection' || type_field == 'selection_custom')
										{
											if(el['select'] !== null && el['select'] > 0 && element['structure_table'][ind]['html']['array'][el['select']])
												cur_cell.html('<p data-input-id="'+el['id']+'">'+element['structure_table'][ind]['html']['array'][el['select']]+'</p><span class="d_none select_value">'+el['select']+'</span>');
											else
												cur_cell.html('<p data-input-id="'+el['id']+'"></p><span class="d_none select_value"></span>');
										}
										else if(type_field == 'file')
										{
											if(el[type_field] !== null)
												cur_cell.html('<p data-input-id="'+el['id']+'">'+el['link']+'</p>');
											else
												cur_cell.html('<p data-input-id="'+el['id']+'"></p>');
										}
										else if(el[type_field] !== null)
											cur_cell.html('<p data-input-id="'+el['id']+'">'+el[type_field]+'</p>');
										else
											cur_cell.html('<p data-input-id="'+el['id']+'"></p>');
									});
								}
							});
							
							if(field_list !== '')
							{
								$.map(field_list, function(field, indx){
									
									body_table.children().not(':first').find('.parent_'+indx).children().html(field);
								});
							}
						}
						
						// return false;
						
						body_table.children(':first').addClass('new_bg text-white sticky-top').find('.parent').filter(function(indx){
							
							body_table.find('.parent_'+this.getAttribute('data-parent')).children().css('width', this.clientWidth).parent().parent().css('width', this.clientWidth);
							
							if(this.nextSibling.classList.contains('d_none') == false && !element['structure_table'][this.getAttribute('data-parent')]['height'])
								body_table.find('.parent_'+this.getAttribute('data-parent')).css('height', '50%').siblings().css('height', '50%');
							
						});
						
						var first_string = body_table.children(':first');
						
						first_string.find('.parent').unbind();
						
						first_string.find('.parent').resizable({
							minWidth: 10,
							minHeight: 10,
							resize: function(event, ui)
							{
								if(ui.size.height !== ui.originalSize.height)
								{
									if(ui.element.siblings().hasClass('d_none'))
										ui.element.parent().height(ui.size.height).siblings().height(ui.size.height);
									else
										ui.element.siblings().height(ui.element.parent().innerHeight() - ui.size.height).parent().height('').siblings().height('').children().height('');
									
									// console.log(1);
									
									if(ui.element.parent().parent().hasClass('child'))
									{
										if(ui.element.parent().innerHeight() > ui.element.parent().parent().innerHeight())
											ui.element.parent().parent().height(ui.element.parent().innerHeight()).siblings().height(ui.element.parent().parent().siblings().innerHeight() - (ui.element.parent().innerHeight() - ui.element.parent().parent().innerHeight()));
										else if(ui.element.parent().innerHeight() < ui.element.parent().parent().innerHeight())
											ui.element.parent().parent().height(ui.element.parent().parent().innerHeight() - (ui.element.parent().parent().innerHeight() - ui.element.parent().innerHeight())).siblings().height(ui.element.parent().parent().siblings().innerHeight() + (ui.element.parent().parent().innerHeight() - ui.element.parent().innerHeight()));
									}
									
									first_string.find('.flex_conteiner').filter(function(){
										
										sum_height = 0;
										
										$(this).children().map(function(){
											sum_height = sum_height + $(this).innerHeight();
										});
										
										if($(this).innerHeight() !== sum_height)
										{
											if(!$(this).children('.child').hasClass('d_none'))
											{
												unit_height = 0;
												unit_height = $(this).innerHeight() / 2;
												
												$(this).children('.parent').height(unit_height).siblings('.child').height(unit_height).find('.flex_conteiner, .parent, .child').height('');
											}
											else
												$(this).children('.parent').height($(this).innerHeight());
										}
									});
								}
								
								if(ui.size.width !== ui.originalSize.width)
								{
									if(ui.element[0].clientWidth >= (ui.element.parent().parent().innerWidth() - (ui.element.parent().nextAll().length * 10)))
										ui.element.css('maxWidth', (ui.element.parent().parent().innerWidth() - (ui.element.parent().nextAll().length * 10))+'px');
									
									ui.element.children('.flex_conteiner').width(ui.element[0].clientWidth);
									
									if(ui.element.parent().parent().hasClass('child'))
										ui.element.parent().width(ui.element[0].clientWidth).siblings().find('.parent, .flex_conteiner').css('width', '100%');
									else
										ui.element.parent().width(ui.element[0].clientWidth);
									
									first_string.find('.parent > .flex_conteiner').css('width', '100%');
									
									ui.element.siblings().filter(function(){
										
										$(this).children('.flex_conteiner').width($(this).width() / ui.element.siblings().children().length).find('.parent, .flex_conteiner').css('width', '100%');
									});
									
									if(!ui.element.parent().parent().hasClass('string'))
									{
										container_width = 0;
										container_width = ui.element.parent().parent().innerWidth();
										
										all_width = 0;
										
										ui.element.parent().siblings().map(function(){
											
											all_width = all_width + this.clientWidth;
										});
										
										all_width = all_width + ui.element[0].clientWidth;
									
										free_width = 0;
										unit_width = 0;
										
										if(container_width > all_width)
										{
											free_width = container_width - all_width;
											
											if(ui.element.parent().nextAll().length > 0)
											{
												unit_width = free_width / ui.element.parent().nextAll().length;
												ui.element.parent().nextAll().filter(function(){
													
													$(this).width($(this).innerWidth() + unit_width);
												});
											}
											else
											{
												unit_width = free_width / ui.element.parent().siblings().length;
												
												ui.element.parent().siblings().filter(function(){
													
													$(this).width($(this).innerWidth() + unit_width);
												});
											}
										}
										else if(all_width > container_width)
										{
											free_width = all_width - container_width;
											
											
											if(ui.element.parent().nextAll().length > 0)
											{
												unit_width = free_width / ui.element.parent().nextAll().length;
												
												ui.element.parent().nextAll().filter(function(){
													
													$(this).width($(this).innerWidth() - unit_width);
												});
											}
											else
											{
												unit_width = free_width / ui.element.parent().siblings().length;
												
												ui.element.parent().siblings().filter(function(){
													
													$(this).width($(this).innerWidth() - unit_width);
												});
											}
										}
									}
									
									ui.element.siblings().eq(0).find('.child').map(function(){
										
										if($(this).children().length > 1)
										{
											unit_width = $(this).innerWidth() / $(this).children().length;
											
											$(this).children().filter(function(){
												
												$(this).width(unit_width);
											});
										}
										
									});
									
									ui.element.parent().siblings().eq(0).find('.child').map(function(){
										
										if($(this).children().length > 1)
										{
											unit_width = $(this).innerWidth() / $(this).children().length;
											
											$(this).children().filter(function(){
												
												$(this).width(unit_width);
											});
										}
									});
								}
							},
							stop: function(event, ui){
								
								ui.element.css('maxWidth', '');
								
								
								structure_size_array = new Array();
								
								first_string.find('.parent').map(function(indx){
									
									structure_size_array[indx] = {'id':$(this).attr('data-parent'), 'width':$(this).innerWidth(), 'height':$(this).innerHeight()};
								});
								
								
								$.postJSON
								(
									'/laravel/public/inventory/set_structure_size',
									{structure_size_array:structure_size_array},
									function(data)
									{
										if(data == 0)
											alert('Изменение размера не зафиксированно!');
									},
									"json"
								);
								
								first_string.find('.parent').filter(function(index){
									
									first_string.parent().find('.parent_'+this.getAttribute('data-parent')).css({'width':$(this).innerWidth(), 'height':$(this).innerHeight()}).children('.flex_conteiner').css('width', this.clientWidth).parent().parent().css('width', this.clientWidth);
								});
								
								var summ_width = 0;
								
								first_string.children('.flex_conteiner').map(function(index){
									
									if(index == 0)
										summ_width = 0;
									
									summ_width += $(this).innerWidth();
								});
								// console.log(body_table);
								first_string.parent().children().filter(function(index){
									
									$(this).width(summ_width);
								});
							},
							create: function(event, ui){
								
								var summ_width = 0;
							
								first_string.children('.flex_conteiner').filter(function(index){
									
									if(index == 0)
										summ_width = 0;
									
									summ_width += $(this).innerWidth();
								});
								
								body_table.children().width(summ_width);
								
								first_string.find('.parent').map(function(){
									
									if(!$(this).siblings().hasClass('d_none') && !$(this).css('height') && !$(this).siblings().css('height'))
										$(this).height($(this).parent().innerHeight() / 2).siblings().height($(this).parent().innerHeight() / 2);
									
									if($(this).css('width'))
										$(this).parent().width($(this).innerWidth());
									else if($(this).siblings().css('width'))
										$(this).parent().width($(this).siblings().innerWidth());
								});
							}
						});
					});
					
					$('#search_reset').removeClass('d_none');
				}
			}
			else
			{
				$('#catalog_conteiner').html('<div class="alert alert-primary" role="alert">Результат: 0 товарных позиций.</div>');
			
				$('#search_reset').removeClass('d_none');
			}
			
			$('.search').removeClass('search_spin');
		},
		"json"
	);
}




function sortable_structure()
{
	old_parent_id = 0;
	new_parent_id = 0;
	
	old_parent = '';
	new_parent = '';
	
	$('#table_structure').sortable({axis: "y", items: "div[data-id]", placeholder: "sortable-placeholder"},
	{
		start: function(event, ui){
			
			if(ui.item.parent().hasClass('sortable') == true)
				old_parent_id = 0;
			else
			{
				old_parent = ui.item.parent().parent();
				
				old_parent_id = parseInt(old_parent.attr('data-id'));
			}
			// console.log(old_parent_id);
		},
		update: function(event, ui){
			
			if(ui.item.parent().hasClass('sortable') == true)
			{
				new_parent_id = 0;
				
				if(old_parent_id > 0)
					ui.item.children().children('.parent_html').children().eq(1).css('paddingLeft', '15px');
			}
			else
			{
				new_parent = ui.item.parent().parent();
				
				new_parent_id = parseInt(new_parent.attr('data-id'));
				
				// new_parent.height(new_parent.innerHeight() + 33);
				
				new_parent.children().children('.parent_html').children(':first').html('<button type="button" class="btn btn-link btn-sm text-info open_structure p-0" title="Свернуть"><span class="oi oi-minus"></span></button>');
				
				ui.item.siblings().removeClass('d_none');
				
				ui.item.children().children('.parent_html').children().eq(1).css('paddingLeft', parseInt(new_parent.children().children('.parent_html').children().eq(1).css('paddingLeft')) + 15+'px');
				
				new_parent.children().children('.parent_html').after(new_parent.children().children('.disable'));
			}
			
			new_list_id = new Array();
			
			ui.item.parent().children('.row').map(function(index, element){
				
				if(parseInt(element.getAttribute('data-id')) > 0)
					new_list_id[index] = parseInt(element.getAttribute('data-id'));
			});
			
			
			if(old_parent_id > 0)
			{
				// old_parent.height(old_parent.innerHeight() - 33);
				
				if(old_parent.children().children().length == 2)
					old_parent.children().children('.parent_html').children(':first').empty();
			}
			
			id = parseInt(ui.item.attr('data-id'));
			// console.log(id);
			
			$.postJSON
			(
				'/laravel/public/inventory/sortable',
				{id:id, catalog_id:catalog_id, parent_id:new_parent_id, sortable_list:new_list_id},
				function(data)
				{
					if(data == 1)
					{
						$('#catalog_conteiner').find('[data-catalog="'+catalog_id+'"]').siblings('.collapse').removeClass('show');
						$('#catalog_conteiner').find('[data-catalog="'+catalog_id+'"]').find('.collapsed').trigger('click').siblings('img').remove();
					}
				},
				"json"
			);
			
		}
	});
	// $( ".sortable .row[data-id]" ).draggable();
	// $('.sortable .row[data-id]').droppable();
}
$(sortable_structure);

function un_bn()
{
	$('span').unbind();
	$('.dropdown-menu').unbind();
	$('input').unbind();
	$('textarea').unbind();
	$('.log_flag').unbind();
}


$(function()
{
	// $('body').tooltip({selector:'a[data-toggle="tooltip"], b[data-toggle="tooltip"], div[data-toggle="tooltip"]', 'title':$(this).attr('title'), 'placement':'top'});
});

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