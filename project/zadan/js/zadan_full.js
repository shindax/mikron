var prevstr = '' ;
var current_resource_id;

// ******************************************************************************************************
// after page loaded
$(function() 
{
	$(document).on("keyup", ".add_count", count_key_up);
	$(document).on("click", ".coop_send", coop_send_button_click);
	$(document).on("click", ".coop_a", coop_a_click);
	$(document).on("click", ".del_img", del_img_click);

// ********************************* Запрос на выдачу *********************************

	$( '#warehouse_dialog' ).dialog({
		resizable: false,
		modal: true,
     	autoOpen: false,
		closeOnEscape: true,
		height: 250,
		width: 1000,
create : function()
{
},
open : function()
{
},

buttons:
[
{
	id : 'add_to_issue_button',
	text: 'Добавить на выдачу',
	disabled : true, 
	click : function ()
	{
		let that = this
		let arr = [];

		let trs = $( 'tr.items_to_issue' )
	    $.each( trs , function( key, item )
	    {
	    	let pattern = $( item ).data('pattern');
	      	let operation_id = $( item ).data('id');
			let get_from_wh_req_input = parseInt( $( item ).find('.get_from_wh_req_input').val())
			let get_from_wh_req_comment_input = $( item ).find('.get_from_wh_req_comment_input').val()
			let id_zakdet = $( item ).find('.get_from_wh_req_input').data('id_zakdet')

			if( get_from_wh_req_input )
			arr.push( {
					'operation_id' : operation_id,
					'count' : get_from_wh_req_input,
					'comment' : get_from_wh_req_comment_input,
					'id_zakdet' : id_zakdet,
					'pattern' : pattern
				   })
	    });

	    cons( arr )

		$.post(
			'project/zadan/ajax.AddToDSEBasket.php',
			{
				arr,
				user_id : user_id
			},
			function( data )
			{
					OpenBasket()
					$( that ).dialog('close');
					for ( key in arr ) 
					{
						let id_zakdet = parseInt(arr[key]['id_zakdet'])
						let operation_id = parseInt(arr[key]['operation_id'])
						let count = parseInt(arr[key]['count'])

						let a = $('a.in_wh[data-zakdet-id=' + id_zakdet + '][data-operation-id=' + operation_id + ']')
						let old_count = parseInt($( a ).html()) 
						
						$( a ).html( old_count - count )
					}					
			}
			);

	}
},
{
	text : '\u041E\u0442\u043C\u0435\u043D\u0430',
	click : function ()
	{
		$(this).dialog('close');
	}
}
]
});

// ********************************* Корзина *********************************

$( "#basket-dialog" ).dialog({
	  dialogClass: "basket",
      resizable: true,
      height: "auto",
      width: 400,
      minWidth: 200,
      modal: false,
      autoOpen: false,
      position : { my: "right top", at: "right top", of: $('div.bar') },
      open : function()
      {

      },
      buttons: {
        "Запросить все": function() 
        {
        	let that = this
        	$.post(
					'project/zadan/ajax.RequestAllFromBasket.php',
					{
						user_id : user_id
					},
					function( data )
					{
						let arr = JSON.parse( data )
						for ( key in arr ) 
						{
							let id_zakdet = parseInt(arr[key]['id_zakdet'])
							let operation_id = parseInt(arr[key]['operation_id'])
							let count = parseInt(arr[key]['count'])
							// $('a.in_wh[data-zakdet-id=' + id_zakdet + '][data-operation-id=' + operation_id + ']').remove()
						}
						$( that ).dialog( "close" );
					}
					);	
        },
        "Очистить и закрыть": function() 
        {
        	let that = this
        	$.post(
					'project/zadan/ajax.EmptyBasket.php',
					{
						user_id : user_id
					},
					function( data )
					{
						let arr = JSON.parse( data )
						for ( key in arr ) 
						{
							let id_zakdet = parseInt(arr[key]['id_zakdet'])
							let operation_id = parseInt(arr[key]['operation_id'])
							let count = parseInt(arr[key]['count'])
							let a = $('a.in_wh[data-zakdet-id=' + id_zakdet + '][data-operation-id=' + operation_id + ']')
							let old_count = parseInt($( a ).html()) 
							
							$( a ).html( old_count + count )
						}
          				$( that ).dialog( "close" );
					}
					);	
        }
      }
    })

//  *************************** Диалог Отправить ДСЕ на склад ***************************

$( "#move-to-warehouse-dialog" ).dialog({
	  dialogClass: "basket",
      resizable: true,
      height: "auto",
      width: 600,
      minWidth: 200,
      modal: false,
      autoOpen: false,
      position : { my: "right top", at: "right top", of: $('div.bar') },
      open : function()
      {

      },
      buttons: {
        "Отправить все": function() 
        {
        	let arr = []
        	let trs = $( "#move-to-warehouse-dialog table tr" )
        	$.each( trs , function( key, item )
			    {
			      let id = $( item ).data('id');
			      arr.push( id )
			    });

  			url = "index.php?do=show&formid=251&p1=" + arr.join(",");
  			window.open( url, "_blank" );
        },
        "Очистить": function() 
        {
        	let trs = $( "#move-to-warehouse-dialog table tr" )
        	$.each( trs , function( key, item )
			    {
			      let id = $( item ).data('id');
			      $('input.dse_checkbox[data-id=' + id + ']').prop('checked', false)
			      $( item ).remove();
			    });

        	$( this ).dialog('close')
        }
      }
    })
  
// ******************************************************************

	$( "#dialog" ).dialog({
      autoOpen: false,
      height: 440,
      width: 800,
      modal: true,
      closeOnEscape: true,
      buttons: 
      {
        "Закрыть": function()  
        {
          $( this ).dialog( "close" );
        },
      },
      classes:
      {
      	"ui-dialog-titlebar" : "cooperation-dialog"
      }
    });

// ********************************* Удаление записи *********************************
	$( "#delete_row_dialog" ).dialog({
      resizable: false,
      width: 500,
      height: "auto",
      modal: true,
      autoOpen : false,
      buttons: 
      {
      	"\u0423\u0434\u0430\u043b\u0438\u0442\u044c": function() 
        {
        		let id = $( this ).data('id');
        		let el = this ;

    			$.post(
    			         '/project/zadan/ajax.delete_row.php',
    			            {
    			                id  : id,
    			            },
    			            function( data )
    			            {
    			            	let arr = JSON.parse( data )
    			            	let count = 1 * arr[ 0 ];
    			            	let norm_hours = 1 * arr[ 1 ] * count;
    			            	let oper_id = arr[ 2 ];
    			            	let tr = $('tr[data-id=' + oper_id + ']')
    			            	
    			            	let a_count = 1 * $( tr ).find('.coop_a').text() - count;
    			            	a_count = a_count ? a_count : ''
                        		$( tr ).find('.coop_a').text( a_count );

    			            	a_count = 1 * $( tr ).find('.count').text() - count;
                        		$( tr ).find('.count').text( a_count );

                        		let norm_fact = 1 * $( tr ).find('.norm_fact_span').text() - norm_hours;
                        		$( tr ).find('.norm_fact_span').text( Number( norm_fact ).toFixed(2) );

    			            	$( el ).dialog( "close" );
                        
                        		get_cooperation_data ( $( tr ).data('id') )
                        		recalc_arrays( oper_id, $( tr ).attr('name').replace('dse_par_',''), a_count,Number( norm_fact ).toFixed(2) )
    			            }
    			      );
        },
        "\u0417\u0430\u043a\u0440\u044b\u0442\u044c": function() 
        {
          $( this ).dialog( "close" );
        }
      },
      classes:
      {
      	"ui-dialog-titlebar" : "delete_record"	
      }
    });

	if( items_in_basket )
		OpenBasket()
})

function OpenWarehouseDialog( id_zakdet, operation_id, operitems_id, pattern )
{
        		$('div.ui-widget-header').css('background','#008B8B').css('color','white').css('font-weight','bold'); // Цвет заголовка диалога
        		$('#copy_dialog_copy_button').addClass('ui-state-disabled');

        		$.post(
        			'project/zadan/ajax.FindInWarehouseFromRoot.php',
        			{
        				pattern : pattern, 
        				id_zakdet : id_zakdet,
        				operation_id : operation_id, 
        				operitems_id :operitems_id
        			},
        			function( data )
        			{
        				$('#warehouse_dialog').html( data ).dialog('open');
        				adjust_ui();
        			}
        			);
}

function adjust_ui()
{
	$( 'a.in_wh' ).unbind('click').bind('click', in_wh_click )
	$('.get_from_wh_req_input').unbind('keyup').bind('keyup', get_from_wh_req_input_keyup );
	$('.del_pict').unbind('click').bind('click', del_pic_click );
	$('.dse_checkbox').unbind('click').bind('click', dse_checkbox_click );
	$('.move-to-warehouse-del-img').unbind('click').bind('click', move_to_warehouse_del_img_click );
	
}

function move_to_warehouse_del_img_click()
{
	let tr = $( this ).closest('tr')
	let id = $( tr ).data('id')
	$('input.dse_checkbox[data-id=' + id + ']').prop('checked', false )	
	$( tr ).remove()
	let items = $( "#move-to-warehouse-dialog table tr" ).length
	if( ! items )
		$( "#move-to-warehouse-dialog" ).dialog('close')	
}

function dse_checkbox_click()
{
	let state = $( this ).prop('checked')
	let id = $( this ).data( 'id' )
	let name = $( this ).data( 'name' )
	let draw = $( this ).data( 'draw' )
	let order = $( this ).data( 'order' )

	if( state )
	{
		let tr = "<tr data-id='" + id + "'><td class='Field AC'>" + order + "</td><td class='Field'>" + name + "</td><td class='Field'>" + draw + "</td><td class='Field AC'><img src='/uses/del.png' class='move-to-warehouse-del-img' /></td></tr>"
		$( "#move-to-warehouse-dialog table" ).append( tr )
		$( "#move-to-warehouse-dialog" ).dialog('open')
	}
	else
	{
		$( "#move-to-warehouse-dialog table" ).find("tr[data-id='" + id + "']").remove()
		let items = $( "#move-to-warehouse-dialog table tr" ).length
		if( ! items )
			$( "#move-to-warehouse-dialog" ).dialog('close')
	}

	adjust_ui();
	// cons( id, name, draw, order, state )
}

function del_pic_click()
{
	let tr = $( this ).closest('tr');
	let id = parseInt( $( tr ).data('id'))
	let id_zakdet = parseInt( $( tr ).data('zakdet_id'))
	let operation_id = parseInt( $( tr ).data('oper_id') )
	let count = parseInt( $( tr ).find('.count').text() )
	$( tr ).remove();
	if( ! $('.basket_table').find('tr').length )
		$( "#basket-dialog" ).dialog('close')

	let a = $('a.in_wh[data-zakdet-id=' + id_zakdet + '][data-operation-id=' + operation_id + ']')
	let old_count = parseInt($( a ).html()) 
	$( a ).html( old_count + count )

	// cons( id, id_zakdet, operation_id, count )

	$.post(
			'project/zadan/ajax.DeleteRowInBasket.php',
			{
				id : id
			},
			function( data )
			{
				// cons( data )
				adjust_ui();
			}
		);
}

function get_from_wh_req_input_keyup()
{
	let val = parseInt( $( this ).val() );
	let max = parseInt( $( this ).data('max') );

    if( val > max || val == 0 || isNaN( val ) )
    {
    // delete last char
      $( this ).val( $.trim($( this ).val()).slice(0, -1));
      disable_button('#add_to_issue_button')
     }
      else
      {
      		enable_button('#add_to_issue_button')
      }
}

function in_wh_click( event )
{
	let pattern = $( this ).data('pattern')
	let id_zakdet = $( this ).data('zakdet-id')
	let operation_id = $( this ).data('operation-id')
	let operitems_id = $( this ).data('operitems-id')
	OpenWarehouseDialog( id_zakdet, operation_id, operitems_id, pattern )
}
// ******************************************************************************************************
function cons( arg1='', arg2='', arg3='', arg4='', arg5='')
{
	let str = arg1 ;
	if( String(arg2).length )
		str += ' : ' + arg2
	if( String(arg3).length )
		str += ' : ' + arg3
	if( String(arg4).length )
		str += ' : ' + arg4
	if( String(arg5).length )
		str += ' : ' + arg5

	console.log( str )
}
// ******************************************************************************************************
function vote9(obj, id_oper, val_oper)
{
	var req = getXmlHttp();
	req.open('GET', 'MSG_INFO_operitems.php?id='+id_oper+'&value='+val_oper);
	req.send(null);
}

// ******************************************************************************************************
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
}

// ******************************************************************************************************
function expand_cur_zak(id_zak, obj)
{
	$('select[data-id=' + id_zak + ']').remove();
	obj.getElementsByTagName('img')[1].style.display='none';
	obj.getElementsByTagName('img')[0].style.display='inline';
	var c_r_b_l = (document.getElementById('tbody_'+id_zak).rows.length-1);
	for(var c_r_b_f=c_r_b_l; c_r_b_f>0; c_r_b_f--)
	{
		document.getElementById('tbody_'+id_zak).rows[c_r_b_f].remove();
	}
}

// ******************************************************************************************************
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
		}
		else
			{
				document.getElementById('cur_smen_sz').style.display='none';
				obj.value='Показать сменные задания';
			}
}

// ******************************************************************************************************
function check_res_sz_cur()
{
	if ((document.getElementById('navig_dat').value.length == 10) && (document.getElementById('navig_smen').value !==0)){
		document.getElementById("val_res_new_nav").value = 'Ждите...';
		vote2('full_plan_sz_ch_res.php?p1='+document.getElementById('navig_dat').value.substr(0,4)+document.getElementById('navig_dat').value.substr(5,2)+document.getElementById('navig_dat').value.substr(8,2)+'&p2='+document.getElementById('navig_smen').value);
	}
	else
		alert('Вы не выбрали дату или смену');
}

// ******************************************************************************************************
function vote2(url)
{
	var req = getXmlHttp();
	req.onreadystatechange = function() 
	{
		if (req.readyState == 4) 
			if(req.status == 200) 
			{
				document.getElementById('sel_res_div').innerHTML = req.responseText;
				document.getElementById('div_res_div').style.display = 'block';
				document.getElementById('val_res_new_nav').value = 'Выбрать';
			}
	}

	req.open('GET', url, true);
	req.send(null);
}

// ******************************************************************************************************
function vote3(url)
{
		var req = getXmlHttp();
		req.onreadystatechange = function() 
		{
			if (req.readyState == 4) 
				if(req.status == 200) 
					document.getElementById('park_sel_cur_res').innerHTML = '<option value="0" selected>Список оборудований у ресурса</option>'+req.responseText;
		}

		req.open('GET', url, true);
		req.send(null);
}

// ******************************************************************************************************
function add_op_in_sz(id_op_add, obj)
{
	if ((document.getElementById('nav_tekysh_1').innerText!=='')&&(document.getElementById('nav_tekysh_2').innerText!=='')&&(document.getElementById('nav_tekysh_3').innerText!==''))
	{
	var date_cur_nav = document.getElementById('nav_tekysh_1').innerText.substr(6,4)+document.getElementById('nav_tekysh_1').innerText.substr(3,2)+document.getElementById('nav_tekysh_1').innerText.substr(0,2);

	var par_innerhtml_obj = obj.innerHTML;
	var par_innerhtml_obj_io = par_innerhtml_obj.indexOf('150%');

	if (par_innerhtml_obj_io == -1)
		par_innerhtml_obj = 0;
	else
		par_innerhtml_obj = 1;

	obj.innerHTML = '........';

	var req = getXmlHttp();
	
	req.onreadystatechange = function() 
	{
		if (req.readyState == 4) 
			if(req.status == 200) 
			{
				obj.innerHTML = '<img src="uses/ok.png" style="cursor:pointer;">';
				obj.setAttribute('onclick','if (confirm("Вернуть?")){ del_op_in_sz('+id_op_add+',this, "' + par_innerhtml_obj + '");}');
			}
	}

	req.open('GET', 'project/zadan/zadanadd.php?date='+date_cur_nav+'&smen='+document.getElementById('nav_tekysh_2').innerText+'&resurs='+document.getElementById('nav_tekysh_4').innerText+'&idoper='+id_op_add, true);
	req.send(null);

	}
		else
			alert('Вы не выбрали ресурс или дату или смену.');
}

// ******************************************************************************************************
function del_op_in_sz(id_op_add, obj, parhtml)
{
		if ((document.getElementById('nav_tekysh_1').innerText!=='')&&(document.getElementById('nav_tekysh_2').innerText!=='')&&(document.getElementById('nav_tekysh_3').innerText!==''))
		{
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
				if (req.readyState == 4) 
					if(req.status == 200) 
					{
						obj.innerHTML = new_obj_html;
						obj.setAttribute('onclick','add_op_in_sz('+id_op_add+',this)');
					}
			}
			req.open('GET', 'project/zadan/zadandel.php?date='+date_cur_nav+'&smen='+document.getElementById('nav_tekysh_2').innerText+'&resurs='+document.getElementById('nav_tekysh_4').innerText+'&idoper='+id_op_add, true);
			req.send(null);
		}
		else
			alert('Вы не выбрали ресурс или дату или смену.');
}

// ******************************************************************************************************
function check_sel_park_pr(id_park)
{
	for (var td_c_pr=0; td_c_pr<document.getElementsByName('pr_cur_r_park').length; td_c_pr++)
	{
		if ((document.getElementsByName('pr_cur_r_park')[td_c_pr].id.substr(11)==document.getElementById('park_sel_cur_res').value)&&(document.getElementById('park_sel_cur_res').value!=='0'))
				document.getElementsByName('pr_cur_r_park')[td_c_pr].style.background='#99ddff';

		if (document.getElementById('park_sel_cur_res').value=='0')
		{
			var split_rgb_obj = document.getElementsByName('pr_cur_r_park')[td_c_pr].parentNode.style.background;
			var split_rgb_obj_repl = split_rgb_obj.replace('rgb(','');
			split_rgb_obj_repl = split_rgb_obj_repl.replace(')','');
			split_rgb_obj_repl = split_rgb_obj_repl.replace(' ','');
			split_rgb_obj_repl = split_rgb_obj_repl.replace(' ','');
			split_rgb_obj_repl = split_rgb_obj_repl.replace(' ','');
			split_rgb_obj_repl = split_rgb_obj_repl.split(',');

			if ((split_rgb_obj_repl[0]=='221')&&(split_rgb_obj_repl[1]=='255')&&(split_rgb_obj_repl[2]=='221'))
				document.getElementsByName('pr_cur_r_park')[td_c_pr].style.background='#ddffdd';
					else
				document.getElementsByName('pr_cur_r_park')[td_c_pr].style.background='#fff';
		}
	}
}

// ******************************************************************************************************
function get_coop_data( operitems_id )
{
	var count = 0 ;
	$.post(
            '/project/zadan/ajax.GetCooperationsCount.php',
            {
               operitems_id: operitems_id
            },
                   function( respond, textStaus, jqXHR )
                  {
                  	return respond ;
                  },
              );

	return 0;
}

// ******************************************************************************************************
function mul_replace( str, find_str, replace_str )
{
// without regexp
/*
  while( str.indexOf( find_str ) != -1 )
    str = str.replace( find_str, replace_str );

  return str ;
*/
    return str.replace(new RegExp( find_str,'g'), replace_str );
}

// ******************************************************************************************************
function TXT( str ) 
{
	str = mul_replace( str, "@%1@","'");
	str = mul_replace( str, "@%2@","\"");
	str = mul_replace( str, "@%3@","(");
	str = mul_replace( str, "@%4@",")");
	str = mul_replace( str, "@%5@","\n");
	str = mul_replace( str, "@%6@","&");
	str = mul_replace( str, "@%7@","#");
	str = mul_replace( str, "@%8@","\\");
	str = mul_replace( str, "@%9@","+");

	return str;
}

// ******************************************************************************************************
function TXT_src(x) 
{
	res = x;
	res = res.replace("@%1@","'");
	res = res.replace("@%2@","\"");
	res = res.replace("@%3@","(");
	res = res.replace("@%4@",")");
	res = res.replace("@%5@","\n");
	res = res.replace("@%6@","&");
	res = res.replace("@%7@","#");
	res = res.replace("@%8@","\\");
	res = res.replace("@%9@","+");
	return res;
}

// ******************************************************************************************************
// Actions after full page loading
function afterLoad()
{
    let trs = $('tr.tr_oper');
    	trs = $('tr.dse');    

    var data = []
	$.each( trs, function( key, value )
    	{
    		let pattern = $( value ).data('draw')
    		if( pattern.length )
    		{
	    		let name = $( value ).attr( 'name' );
	    		let zakdet_id = name.replace('dse_par_', '');
	    		data.push( { zakdet_id : zakdet_id, pattern : pattern });
    		}
	});

	// cons( data )

	$.post(
            '/project/zadan/ajax.FindDataInWarehouseFromRoot.php',
            {
               data: data
            },
                   function( respond, textStaus, jqXHR )
                  {
                  	// cons( respond )
                   	let arr = JSON.parse( respond )
                   	cons( arr )
					for ( key in arr ) 
					{
						let count = arr[ key ]['count']
						let basket_count = arr[ key ]['basket_count']

							let pattern = arr[ key ]['pattern']
							let operation_id = arr[ key ]['operation_id']
							let operitems_id = arr[ key ]['operitems_id']
							let zakdet_id = arr[ key ]['zakdet_id']
							let inv_id = arr[ key ]['inv_id']

							let tr = $( 'tr.dse[name="dse_par_' + zakdet_id + '"]')
							$( tr ).addClass('in_wh')
							let input = $( tr ).find('input')

							if( $( tr ).find('a.in_wh').length == 0 )
								$( input ).before( "<a href='#' class='in_wh' data-zakdet-id='" + zakdet_id + "' data-operitems-id='" + operitems_id + "' data-operation-id='" + operation_id + "' data-pattern='" + pattern + "' title='Имеются на складе'>" + count + '</a>' )
					}

					adjust_ui()
                  },
              )

	// cons( data )
}

// ******************************************************************************************************
function coop_a_click ( event )
{
	event.preventDefault();
	var tr = $( this ).closest('tr');
	get_cooperation_data ( $( tr ).data('id') )
}

function get_cooperation_data ( oper_id )
{
$.post(
            '/project/zadan/ajax.GetCooperationData.php',
            {
               operitems_id: oper_id 
            },
                   function( respond, textStaus, jqXHR )
                  {
                  		var trs = $( respond ).find('tr');
                  		var height = 540 ;

                  		switch( trs.length )
                  		{
                  			case 1 : height = 162 ; break ;
                  			case 2 : height = 182 ; break ;
                  			case 3 : height = 207 ; break ;
                  			case 4 : height = 234 ; break ;

                  			case 5 : height = 258 ; break ;
                  			case 6 : height = 284 ; break ;
                  			case 7 : height = 308 ; break ;
                  			case 8 : height = 333 ; break ;

                  			case 9 : height = 358 ; break ;
                  			case 10 : height = 388 ; break ;
                  			case 11 : height = 409 ; break ;
                  			case 12 : height = 434 ; break ;


                  			case 13 : height = 459 ; break ;
                  			case 14 : height = 485 ; break ;
                  			case 15 : height = 511 ; break ;
                  			case 16 : height = 535 ; break ;

                  			default : break ;
                  		}
                 	  	$( "#dialog" ).dialog( "option", "height", height )
                  		$( "#dialog div" ).html( respond );
                  		$( "#dialog" ).dialog('open');
                  },
              )
}


// ******************************************************************************************************
function coop_send_button_click ( event )
{
	event.preventDefault();
	$( this ).prop('disabled', true )
	var tr = $( this ).closest('tr');
	var name = 1 * $( tr ).attr('name').replace("dse_par_","")
	var oper_id = $( tr ).data('id');
	var count = Number( $( tr ).find('input.add_count').val() );

	var comment = $( tr ).find('input.comment').val();
	$( tr ).find('input.add_count').val('');
	$( tr ).find('input.comment').val('');

	var norm_hours_by_batch = Number( $( tr ).find('span.norm_hours').text() ) ;
	var count_in_batch = Number( $( tr ).find('span.total_count').text() );

	var norm_hours = norm_hours_by_batch / count_in_batch ;

    // Отправляем запрос
	$.post(
            '/project/zadan/ajax.PutCooperationData.php',
            {
                oper_id : oper_id,
                count : count,
                comment : comment,
                norm_hours : norm_hours,
                user_id : user_id 
            },
                   function( respond, textStatus, jqXHR )
                  {
                      // if everything is OK
                      if( typeof respond.error === 'undefined' )
                        {
                        		$( tr ).find('a.coop_a').html( respond )
                        		let loc_cnt = 1 * $( tr ).find('.count').text() + 1 * count
                        		let loc_norm_hours = Number( 1 * $( tr ).find('.norm_fact_span').text() + 1 * count * norm_hours).toFixed(2);
                        		$( tr ).find('.count').text( loc_cnt );
                        		$( tr ).find('.norm_fact_span').text( loc_norm_hours );
                            let name = $( tr ).attr('name').replace('dse_par_','');
                            
                            recalc_arrays( oper_id, name, loc_cnt, loc_norm_hours )  
                        }
                   }
              );
}

// ******************************************************************************************************
function count_key_up()
{
	var val = $( this ).val();
	var but = $( this ).closest('tr').find('button.coop_send');

	if( $.isNumeric( val ) && ( user_id == 13 || user_id == 179 || user_id == 1 ) )
		$( but ).prop('disabled', false);
		else
			$( but ).prop('disabled', true);
}

// ******************************************************************************************************
function parkSelect()
{
	var model = $( this ).find( 'option:selected' ).val();
	var id = $( this ).data('id');
	var rows = $('tr[data-zak-id=' + id + ']');
	
	if( model == '' )
	{
		$( rows ).find('td:contains("' + model + '")').removeClass('selected_machine').parent('tr').show();
	}
	else
	{
		$( rows ).hide();

	    $.each( rows , function( key, value )
	    {
		var name = $( value ).find('td:contains("' + model + '")').addClass('selected_machine').parent('tr').show().attr('name');
		$('tr.dse[data-id=\"' + name + '\"]').show();
	      
	    });
	}
	
}

// ******************************************************************************************************
function del_img_click()
{
	let id = $( this ).closest('tr').data('id')
	$( "#delete_row_dialog" ).data('id', id ).dialog('open')
}

function recalc_arrays( oper_id, name, cnt, norm_hours )
{

  let dses = jv_arr_full_tbl_1.split('=--=');
  let op_index = -1 ;

  dses.some(
        		 function( item, loc_index ) 
        		{
        			if( item.indexOf( oper_id ) >= 0 )
        			{
        				{
        					let loc_arr = item.split('|')
        					op_index = loc_arr.indexOf( String( oper_id ) )
            				return true;	
        				}
        			}
				}
			);
	
	let loc_arr = jv2_arr_full_tbl_8_spl[ name ]
	loc_arr = loc_arr.split('|')
	let loc_val = loc_arr[ op_index ] ;
		
	loc_arr = jv2_arr_full_tbl_17_spl[ name ]
	loc_arr = loc_arr.split('|')
	loc_arr[ op_index ] = cnt;
	jv2_arr_full_tbl_17_spl[ name ] = loc_arr.join('|')
	
	loc_arr = jv2_arr_full_tbl_19_spl[ name ]
	loc_arr = loc_arr.split('|')
	loc_arr[ op_index ] = norm_hours - loc_val;
	jv2_arr_full_tbl_19_spl[ name ] = loc_arr.join('|')

}

function enable_button( selector )
{
  $( selector ).button({ disabled: false });
}

function disable_button( selector )
{
  $( selector ).button({ disabled: true });
}

function OpenBasket()
{
	$.post(
	'project/zadan/ajax.GetBasket.php',
	{
		user_id : user_id
	},
	function( data )
	{
		$('#basket-dialog').html( data ).dialog('open')
		adjust_ui()
	}
	);
}