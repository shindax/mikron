$(function () {  
	$("td#ID_clients").each(function () {
		$(this).html("<a href='javascript:void(0)'>" + $(this).text() + "</a>");
	});
	
	$(document).on ("click", "td#ID_clients", function () {
		var search_text = $(this).text();

		$(this).closest("table.rdtbl tbody").find("tr[data-id]").each(function () {
			if ($(this).find("td#ID_clients").text() != search_text) {
				$(this).css("background-color", "#fff");
			} else {
				$(this).css("background-color", "#ccc");
			}
		});
	});
	
	$("td#type").each(function () {
		$(this).html("<a href='javascript:void(0)'>" + $(this).text() + "</a>");
	});
	
	$(document).on ("click", "td#type", function () {
		var search_text = $(this).text();

		$(this).closest("table.rdtbl tbody").find("tr[data-id]").each(function () {
			if ($(this).find("td#type").text() != search_text) {
				$(this).css("background-color", "#fff");
			} else {
				$(this).css("background-color", "#ccc");
			}
		});
	});
	
	$("td#dse_name").each(function () {
		$(this).html("<a href='javascript:void(0)'>" + $(this).text() + "</a>");
	});
	
	$(document).on ("click", "td#dse_name", function () {
		var search_text = $(this).text();

		$(this).closest("table.rdtbl tbody").find("tr[data-id]").each(function () {
			if ($(this).find("td#dse_name").text() != search_text) {
				$(this).css("background-color", "#fff");
			} else {
				$(this).css("background-color", "#ccc");
			}
		});
	});
})

