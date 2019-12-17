$(function () {

function updatePost (type, element)
{
	$.post( "/project/order_summary_report/ajax.set_value.php", {
																		"order_id": element.closest("table").data("order-id"),
																		"value": element.val(),
																		"type": type
																	});
}

	$("#ovk_fact").change(function() { 
		updatePost("ovk_fact", $(this));
	});
	
	$("#omts_fact").change(function() {
		updatePost("omts_fact", $(this));
	});

	$("#other_fact").change(function() {
		updatePost("other_fact", $(this));
	});

	$("#std_comment").change(function() {
		updatePost("std_comment", $(this));
	});
	
	$("#ovk_comment").change(function() {
		updatePost("ovk_comment", $(this));
	});
	 
	$("#omts_comment").change(function() {
		updatePost("omts_comment", $(this));
	});
	
	$("#prod_comment").change(function() {
		updatePost("prod_comment", $(this));
	});
	
	$("#other_comment").change(function() {
		updatePost("other_comment", $(this));
	});
	
	$("#hour_cost").change(function() {
		$.post( "/project/order_summary_report/ajax.set_hour_cost.php", {
																		"order_id": $(this).closest("table").data("order-id"),
																		"value": $(this).val()
																	});
	});
});
