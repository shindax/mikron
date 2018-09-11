$(function() {
	$('*[name^="db_logistic_app_TRANSFER_FROM"]').on("keyup", function (e) {
		var t = $(this);

		var id = t.closest("tr").data("id");
		
		$.get("/project/ajax.get_clients.php?text=" + $(this).val(), function (data) {
			console.log($.parseJSON(data));
	 
			t.autocomplete({
				source:  $.parseJSON(data),
				select: function( event, ui ) {
					console.log( "Selected: " + ui.item.value + " aka " + ui.item.telephone );
					 
					$('*[name^="db_logistic_app_CONTRAGENT_CONTACT_edit_' + id + '"]').val(ui.item.telephone);
					$('*[name^="db_logistic_app_CONTRAGENT_CONTACT_edit_' + id + '"]').trigger("change");
				}
			});
		});
	});
})
