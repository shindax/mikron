var cur_date, smena;

// Действия после загрузки страницы
$( function()
{

$(document).mouseup(function (e)
{
	if($(".change_smen_date_block").is(e.target) || $(".change_smen_date_block").has(e.target).length === 0) {
		$(".change_smen_date_block").hide();
		 $('span.hpopup').click();
	}
});

$(document).on("click", ".change_smen_date_link", function (e) {
	e.preventDefault();

	var block  = $(this).closest("tr").find("div.change_smen_date_block");

	block.show();

	block.find("select[name=change_resource_select_date]").empty().load("/project/sz_get_dates_between.php?date=" + cur_date, function () {
		block.find("select[name=change_resource_select_date]").trigger("change")
	});

	return false;
});

$(document).on("change", "select[name=change_resource_select_date_resource]", function () {
	var block  = $(this).closest("tr").find("div.change_resource_select_date_resource");

	var smena = $( ':selected', $(this).closest("tr").find("select[name=change_resource_select_smen]")).val();
	var date = $( ':selected', $(this).closest("tr").find("select[name=change_resource_select_date]")).val();

	var val = $(this).val();

    if( val == 0 )
        return ;

	var id = $(this).closest("tr").attr('data-id');

	var resurs = $("input[type=checkbox][name=cur_zad_sel]:checked");
	var zadan_array = [];
	var check_count = 0;

	resurs.each(function (key, value) {
		var zadan_id = $(this).attr('id').replace("item_zad_", "").replace("_", "");

		if (zadan_id != null) {
			var zadan_checkbox = $("input[type=checkbox][name=cur_zad_sel]:checked");

					if(zadan_checkbox.is(":checked")) {
						zadan_array.push(zadan_id);

						++check_count;
					}
				}
			});

	if(zadan_array.length == 0) {
		zadan_array = [id];
	}

//	console.log(zadan_array);

    $.post("/project/sz_change_resource.php", { 'user_id' : user_id, 'mode' : 'multiple_smena_date', 'ids' : zadan_array, 'to_resource' : val, 'smena' : smena, 'date' : date}, function () {
		$('#cur_smen_sz').attr('src', $('iframe').attr('src'));

		location.href = "index.php?do=show&formid=158&p0="  + date + "&p1=" + smena;
	});
});

$(document).on("change", "select[name=change_resource_select_date]", function () {
	var block  = $(this).closest("tr").find("div.change_smen_date_block");

	var smena = block.find("select[name=change_resource_select_smen]");

	var resources = block.find("select[name=change_resource_select_date_resource]");

	resources.show();

	resources.empty().load("/project/sz_get_all_resources.php?date=" + $(this).val() + "&smena=" + smena.val());
});

$(document).on("change", "select[name=change_resource_select_smen]", function () {
	var block  = $(this).closest("tr").find("div.change_smen_date_block");

	var smena = block.find("select[name=change_resource_select_smen]");

	var resources = block.find("select[name=change_resource_select_date_resource]");

	resources.empty().load("/project/sz_get_all_resources.php?date=" + block.find("select[name=change_resource_select_date]").val() + "&smena=" + smena.val());
});

$('input[id^="sel_all_"]').unbind('change').bind('change', sel_all_change )
$('input[name^="cur_zad_sel"]').unbind('change').bind('change', cur_zad_sel_change )
$('.multiselect_checkbox').unbind('click').bind('click', multiselect_checkbox_click )

function multiselect_checkbox_click()
{
	$('.empty_emp_check').prop('checked', $('.multiselect_checkbox').prop('checked') )
}

function sel_all_change()
{
  var state = + $( this ).prop('checked');
  var id = $( this ).attr('id').substr( 8 );
  var img = $('img#del_res_img_' + id );

  if( state )
    $( img ).removeClass('hidden');
     else
      $( img ).addClass('hidden');

}

function cur_zad_sel_change()
{
  var name2 = $( this ).attr('name2');
  var id = name2.substr( 11 );
  var check_total = $('input[name2="' + name2 + '"]').length  ;
  var check_total_checked = $('input[name2="' + name2 + '"]:checked').length   ;

  if( check_total != check_total_checked && check_total_checked )
    $('img#del_res_img_' + id ).addClass('hidden');
      else
        $('img#del_res_img_' + id ).removeClass('hidden');
}

});
