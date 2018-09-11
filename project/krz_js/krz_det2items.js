$.postJSON = function(url, data, callback) 
{
	 $.post(url, data, callback, "json"); 
}

function add_order_link()
{
	var arr_id = $("td.order_link").map(function(indx, element)
	{
		el_txt = $.trim($(element).text());
		return el_txt;
	});
	
	$.postJSON
	(
		'/project/krz_detitems.php',
		{order_id:JSON.stringify(arr_id.get())},
		function(data)
		{
			$("td.order_link").map(function(indx, element)
			{
				el_txt = $.trim($(element).text());
				
				if(typeof data[el_txt] !== 'undefined')
				{
					$(element).html('<a href="/index.php?do=show&formid=39&id='+el_txt+'">'+data[el_txt]['description']+' '+data[el_txt]['name']+'</a>');
				}
			});
		}
	);
	
	$("td.order_state").map(function(indx, element)
	{
		el_txt = $.trim($(element).text());
		
		if(el_txt !== '')
			$(element).html('<img src="/uses/ok.png" alt="'+el_txt+'" title="'+el_txt+'"/>');
	});
}

$(add_order_link);