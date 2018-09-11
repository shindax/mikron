// Действия после загрузки страницы : 
$( function()
{

});

function waiting()
{
var scroll_top = localStorage.getItem("krz_screen");
    localStorage.removeItem("krz_screen");

    if( scroll_top )
      {
        $( '#vpdiv' ).scrollTop( scroll_top );

		//        alert( $( '#vpdiv' ).length + '-' + scroll_top );
      }
}

function img_click()
{

  var scroll_top = $( '#vpdiv' ).scrollTop();
  localStorage.setItem("krz_screen", scroll_top );
}

$(function () {
if (form_id == 6) {
	var krzs = [];

	$("table.tbl tr[data-id!='']").each(function () {
		if ($(this).attr("data-id") != null) krzs.push($(this).attr("data-id"));
	});

	jQuery.ajax({
    type: 'POST',
    url: "/project/krz_get_attachments.php",
    data : { "krzs" : JSON.stringify(krzs) },
    dataType: "json",
    success: function(data){ 
		$("<td width='46'>&nbsp;</td>").insertAfter($("table.tbl thead tr td:nth-child(6)"));
		$("tr.cltreef td:nth-child(2), tr.cltree td:nth-child(2)").attr("colspan", "2");
		$("<td class='Field'>&nbsp;</td>").insertAfter($("table.tbl tbody tr[data-id!=''] td:nth-child(6)"));

		$('a > img').unbind('click').bind('click', img_click );

		if (id == 0) {
			setTimeout( waiting, 500 );
		}
		
	    $.each(data, function(i, v) {
			$("table.tbl tr[data-id='" + i + "'] td:nth-child(7)").html(v[0] + (v[1] != undefined ? v[1] : ""));
		});
		
	}
	});
	
}
}
);
