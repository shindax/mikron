<style type="text/css">
	@import url(/project/sklad/styles.css);
	@import url(/project/sklad/css/metal_wh_style.css);

	#autocomplete
	{
		margin-top: 20px;
		height: 300px;
		width: 520px;
		background: #F5F5DC;
	}


	#autocomplete option:hover
	{
		background: navy !IMPORTANT;
		color : white;
		cursor: pointer;
	}


	#box 
	{
		width : 99%;
	}

	.wrapper {
		display: flex;
		flex-flow: row wrap;
		width: 1200px;
	}

	.wrapper > * 
	{
		flex: 1 100%;
	}

	.aside 
	{ 
		flex: 1 auto; 
	}

	.sect-a 
	{ 
		display: flex;
		align-items : flex-start;
		justify-content : center;
		flex-direction: column-reverse ;
		order: 1; 
		width : 100px;
		/*background: red;*/
	}
	.sect-c 
	{ 
		display: flex;
		align-items : flex-end;
		justify-content : center;	
		flex-direction: column;
		order: 3;  
		/*background: yellow;*/
	}

	.sect-b
	{ 	
		display: flex;
		align-items : center;
		justify-content : center;
		/*background: cyan;*/
	}

	.sect-b div, .sect-a div, .sect-c div
	{ 
		display: flex;
		align-items : center;
		justify-content : center;
		flex-direction: column;	

		background: green;
		width : 50px;
		height : 50px;	
		margin: 15px 15px;
		text-align: center;
		border-radius: 2px 2px;
		cursor: pointer;
	}

	sect-a center, sect-b center, sect-c center
	{
		width: 100% !IMPORTANT;
	}

	div.has_items
	{
		background-color:#CEE7F0 !IMPORTANT;
	}

	#box .wrapper .floor
	{
		border: 3px dotted gray;
		display: flex !IMPORTANT;
		align-items : center !IMPORTANT;
		justify-content: center !IMPORTANT;
	}


	#box .wrapper .aside .floor
	{
		margin: 30px 50px;
	}

	#box .wrapper .sect-b .floor
	{
		margin: 0 40px;
	}

	#box .wrapper .sect-c .hidden
	{
		background-color: white;
		border: 1px solid white;
	}

</style>
<?php

include 'sklad_func.php';

$wh_id = isset( $_GET['ID_sklad'] ) ? $_GET['ID_sklad'] : 0 ;
echo "<script>let wh_id = $wh_id</script>";

if (isset($_GET['addsklad'])) {
	if (!$is_admin) return;
	
	dbquery("INSERT INTO okb_db_sklades (NAME, KOMM, ORD)
		SELECT concat('Склад №', IFNULL(MAX(ORD) + 1, 1)), '', IFNULL(MAX(ORD) + 1, 1) FROM okb_db_sklades");
	
	redirect($pageurl);
}

if (isset($_GET['additem'])) {
	if (!$is_admin) return;
	
	dbquery("INSERT INTO okb_db_sklades_item (ID_sklad, NAME, YARUS, KOMM, ORD)
		SELECT " . (int) $_GET['ID_sklad'] . ", '', '', '', IFNULL(MAX(ORD) + 1, 1) FROM okb_db_sklades_item
		WHERE ID_sklad = " . (int) $_GET['ID_sklad']);
	
	redirect($pageurl . '&ID_sklad=' . $_GET['ID_sklad']);
}

if (isset($_POST['edit_item'])) {
	if (!$can_edit_sklad) return;
	
	if ($_POST['floor'] == 'on' && !hasFloor($_POST['ID_item'])) {
		dbquery("INSERT INTO okb_db_sklades_yaruses (ID_sklad_item, ORD)
			VALUES (" . (int) $_POST['ID_item'] . ", 0)");		
	} else {
		if (!isset($_POST['floor'])) {
			$floor_id = mysql_result(dbquery("SELECT ID FROM okb_db_sklades_yaruses WHERE ID_sklad_item = " . (int) $_POST['ID_item'] . " AND ORD = 0"), 0);

			if (mysql_num_rows(dbquery("SELECT ID FROM okb_db_sklades_detitem WHERE ID_sklades_yarus = " . $floor_id)) == 0) {
				dbquery("DELETE FROM okb_db_sklades_yaruses WHERE ORD = 0 AND ID_sklad_item = " . (int) $_POST['ID_item']);
				
				UpdateYarusORD($_POST['ID_item']);
			}
		}
	}
	
	dbquery("UPDATE okb_db_sklades_item SET NAME = '" . mysql_real_escape_string($_POST['NAME']) . "',
		YARUS = " . (int) $_POST['YARUS'] . ",
		KOMM = '" . mysql_real_escape_string($_POST['KOMM']) . "'
		WHERE ID = " . (int) $_POST['ID_item']);
	
	if ($_POST['YARUS'] > 0) {
		dbquery("UPDATE okb_db_sklades_yaruses SET ORD = ORD + " . (int) $_POST['YARUS'] . " WHERE ORD != 0 AND ORD > " . $_POST['YARUS_from'] . " AND ID_sklad_item = " . (int) $_POST['ID_item']);

		for ($i = 0; $i < (int) $_POST['YARUS']; ++$i) {
			$new = (hasFloor($_POST['ID_item']) ? ($_POST['YARUS_from'] + $i)  : ($_POST['YARUS_from'] + $i)) + 1;
			
			dbquery("INSERT INTO okb_db_sklades_yaruses (ID_sklad_item, ORD) VALUES (" . (int) $_POST['ID_item'] . ", " . $new . ")");
		}
		
		UpdateYarusORD($_POST['ID_item']);
	}

	redirect($pageurl . '&ID_sklad=' . $_GET['ID_sklad']);
}

// sklad/sklad_report_by_order.php

if (!isset($_GET['ID_sklad'])) {
	echo '<h2 style="float:left">Выберите склад</h2>'
	. ($is_admin ? '<a class="top_menu" href="' . $pageurl . '&addsklad">Добавить</a>' : '')
	. '<a class="top_menu" target="_blank" href="index.php?do=show&formid=308">Отчет по заказам</a>'
	. '<div style="position:relative">
	<input style="float:right;margin-top:20px;width:300px;" type="text" id="search_sklad"/>
	<select style="float:right;margin-top:40px;width:500px;margin-right:-300px;display:none;position:absolute;right:300px;z-index:9999;" size="12" id="search_sklad_autocomplete"></select>
	</div><br style="clear:left"/>';

	$result = dbquery("SELECT ID,NAME,KOMM FROM okb_db_sklades
		ORDER BY ID ASC");

	while ($row = mysql_fetch_assoc($result)) {
		echo '<a href="/' . $pageurl . '&ID_sklad=' . $row['ID'] . '">'
		,'<div class="box">'
		,'<h3>' . ($row['NAME']) . '</h3>'
		,'</div>'
		,'</a>';
	}
} else {
	$sklad_name = mysql_result(dbquery("SELECT NAME FROM okb_db_sklades WHERE ID = " . (int) $_GET['ID_sklad']), 0);

		// ,'<a href="/print.php?do=show&formid=224&p0=' . $_GET['ID_sklad'] . '" class="top_menu" target="_blank">Печать</a>'


	echo '<h2 style="float:left;">' . ($sklad_name) . '</h2>'
	,'<a href="' . $pageurl . '" class="top_menu">Выбрать другой склад</a>'

	,'<a href="/index.php?do=show&formid=306&p0=' . $_GET['ID_sklad'] . '" class="top_menu" target="_blank">Отчет</a>'
	. ($is_admin ? '<a href="' . $pageurl . '&additem&ID_sklad=' . $_GET['ID_sklad'] . '" class="top_menu">Добавить ячейку</a>' : '') .
	'<div style="position:relative">
	<input style="float:right;margin-top:20px;width:300px;" type="text" id="search_sklad"/>
	<select style="float:right;margin-top:40px;width:500px;margin-right:-300px;display:none;position:absolute;right:300px;z-index:9999;" size="12" id="search_sklad_autocomplete"></select>
	</div><br style="clear:left"/>';


	$query = "	SELECT 	si.ORD AS ord,
	si.NAME,
	YARUS,
	KOMM,
	si.ID,COUNT(sy.ID) as YarusCount 
	FROM okb_db_sklades_item si
	LEFT JOIN okb_db_sklades_yaruses sy ON sy.ID_sklad_item = si.ID
	WHERE si.ID_sklad = " . (int) $_GET['ID_sklad'] . " GROUP BY si.ID ORDER BY si.ORD ASC, si.NAME DESC";

	$result = dbquery( $query );

	if (mysql_num_rows($result) > 0) 
	{
		if ( $_GET['ID_sklad'] == 3 ) 
		{	
			$sect_a_div_arr = [];
			$sect_b_div_arr = [];
			$sect_c_div_arr = [];			

			while ($row = mysql_fetch_assoc($result)) 
			{
				$ord = $row['ord'];
				$name = $row['NAME'];
				$id = $row['ID'];
				$tier_count = $row['YarusCount'];
				$floor = 0 ;

				if( $ord >= 15 && $ord <= 24 ) // D section
				$floor = 1 ;

				$class = "item ";

				$class .= hasItemsInBox( $id ) ? " has_items" : "" ;
				$class .= $floor ? " floor" : "" ;

				$div = "<div class='$class' data-box-id='$id'>";
				$div .= "<center>$name</center>";

				if( $ord >= 21 && $ord <= 24 )
					$div .= "Площадка<br>готовой<br>продукция<br>";

				if( !( $ord >= 15 && $ord <= 21 ) )
					$div .= "Ярусов $tier_count";
				
				$div .= "</div>";

				if( $ord >= 1 && $ord <= 4 ) // A section
				$sect_a_div_arr[] = $div;

				if( $ord >= 5 && $ord <= 10 ) // B section
				$sect_b_div_arr[] = $div;

				if( $ord >= 11 && $ord <= 14 ) // C section
				$sect_c_div_arr[] = $div;

				if( $ord == 15 || $ord == 19 || $ord == 21 || $ord == 23 ) // D1, F1, G1б G3 section
				{
					array_unshift ( $sect_a_div_arr, $div );
				}

				if( $ord == 16 ) // D2 section
				array_unshift ( $sect_b_div_arr, $div );
				
				if( $ord == 17 ) // D3 section
				$sect_b_div_arr[] = $div;

				if( $ord == 18 || $ord == 20 || $ord == 22|| $ord == 24 ) // D4, F2, G2, G4 section
				{
					$sect_c_div_arr[] = $div;
				}

			}


// Пустой блок. Чисто для выравнивания
// 
			$div = "<div class='item floor hidden'>";
			$div .= "</div>";

			// $sect_c_div_arr[] = $div;

			$sect_a = "<sect-a class='aside sect-a'>".join("", $sect_a_div_arr )."</sect-a>";
			$sect_b = "<sect-b class='sect-b'>".join("", $sect_b_div_arr )."</sect-b>";
			$sect_c = "<sect-c class='aside sect-c'>".join("", $sect_c_div_arr )."</sect-c>";

			$main_box = "<div id='box'>
			<div class='wrapper'>
			$sect_b
			$sect_a
			$sect_c
			</div>
			</div>";
			echo $main_box;
		}

		if ( $_GET['ID_sklad'] == 4 ) 
		{
			$data = [];
			while ($row = mysql_fetch_assoc($result))
			{
				$data[ $row['NAME'] ] = $row ;
				$data[ $row['NAME'] ]['has_items'] = + hasItemsInBox( $row['ID'] ) ;
			}

			$main = " 
			<br><br><div id='box'>
			<div class='metal_wh_container'>
			<div class='row1'>
			<div>
			<div class='item empty'>AE</div>
			</div>
			<div>".
			GetCell( $data['A1'] ).
			GetCell( $data['A2'] ).
			GetCell( $data['A3'] ).
			GetCell( $data['A4'] ).
			"</div>
			<div>".
				GetCell( $data['D1'] ).
				GetCell( $data['D2'] ).
				GetCell( $data['D3'] ).
				GetCell( $data['D4'] ).
			"</div>
			</div>
			<div class='row2'>
			<div>
			<div class='item empty'>BE</div>
			</div>
			<div class='wh_b-sect'>
			<div>
			<div>".
				GetCell( $data['B1'] ).
				GetCell( $data['B2'] ).
				GetCell( $data['B3'] ).
			"</div>
			<div>".
				GetCell( $data['B4'] ).
				GetCell( $data['B5'] ).
				GetCell( $data['B6'] ).
			"</div>
			</div>

			<div>".
				GetCell( $data['B7'] ).
				GetCell( $data['B8'] ).
			"</div>
			</div>
			<div>".
				GetCell( $data['D5'] ).
			"<div class='item empty'>DE</div>".
				GetCell( $data['D6'] ).
				GetCell( $data['D7'] ).
			"</div>

			</div>
			<div class='row3'>
			<div class='item empty'>CE</div>".
				GetCell( $data['C1'] ).
				GetCell( $data['C2'] ).
				GetCell( $data['C3'] ).
				GetCell( $data['C4'] ).
				GetCell( $data['C5'] ).
				GetCell( $data['C6'] ).
			"<div class='item empty'>CE</div>".
				GetCell( $data['C7'] ).
			"<div class='item empty'>CE</div>
			</div>
			</div>
			</div>
			";

			echo $main;
		}

		if ( $_GET['ID_sklad'] != 3 && $_GET['ID_sklad'] != 4 )
		{
			echo '<div id="box">';

			$i = 1;
			$str = "";

			while ($row = mysql_fetch_assoc($result)) 
			{
				$has_floor = (bool) mysql_num_rows(dbquery("SELECT ID FROM okb_db_sklades_yaruses WHERE ID_sklad_item = " . $row['ID'] . " AND ORD = 0")) > 0;

				echo '<div class="item"' . (hasItemsInBox($row['ID']) ? ' style="background-color:#CEE7F0;"' : '') . ' data-box-id=' . $row['ID'] . '>'
				,'<center>' . ($row['NAME']) . '</center>'
				,($row['YarusCount'] != 0 ? 'Ярусов: <b>' . (/*temp*/$has_floor ? $row['YarusCount'] - 1 : $row['YarusCount']) . ($has_floor ? '<br/>Пол' : '') . '</b>' : '')
				,'<br/>' . (!empty($row['KOMM']) ? '<div class="tooltip">' . ($row['KOMM']) . '</div>' : '')
				,'</div>';

				if ($_GET['ID_sklad'] == 1) 
				{
					if ($i == 18) 
					{
						echo '<div class="spacer"><div class="entrance"></div></div>';
					} 
					else 
						if (!($i % 18)) 
						{
							echo '<br/>';
						}
					}
					++$i;
		} // while ($row = mysql_fetch_assoc($result))

		echo '</div>';
	 } // else
	} // if (mysql_num_rows($result) > 0) 
	
	
	if (is_admin) {

	}
}

function GetCell( $arr )
{
	$ord = $arr['ord'];
	$name = $arr['NAME'];
	$id = $arr['ID'];
	$tier_count = + $arr['YarusCount'];
	$floor = 0 ;
	$tier = + $arr['YARUS'];

	if(  $tier_count && $tier == 0 )
		$floor = 1 ;

	$class = "item ";

	$class .= hasItemsInBox( $id ) ? " has_items" : "" ;
	$class .= $floor ? " floor" : "" ;

	$div = "<div class='$class' data-box-id='$id'>";
	$div .= "<center>$name</center>";
	
	if( $floor == 0 )
		$div .= "<span>Ярусов $tier_count</span>";

	$div .= "</div>";


	return $div;

	// _debug( $arr );
}

?>
<script type="text/javascript">

	function cons( arg1, arg2 = 0, arg3 = 0, arg4 = 0 )
	{
		let arg = arg1;
		if( arg2 )
			arg += " : " + arg2
		if( arg3 )
			arg += " : " + arg3
		if( arg4 )
			arg += " : " + arg4

		console.log( arg )
	}

	function al( arg1, arg2 = 0, arg3 = 0, arg4 = 0 )
	{
		let arg = arg1;
		if( arg2 )
			arg += " : " + arg2
		if( arg3 )
			arg += " : " + arg3
		if( arg4 )
			arg += " : " + arg4

		alert( arg )
	}


	$("#box .item").mouseover(function(e) {
		$(this).find(".tooltip").css("display", "block");;
	}).mouseleave(function(e) {
		$(this).find(".tooltip").css("display", "none");;
	});

	$("body").click(function () {
		$("#search_sklad_autocomplete").css("display", "none");
	});

	$("#box .item").click(function(e) {
		var current_box_item = $(this);
		HidePopup();

		let prog_click = 0

		if( e.pageX == 0 )
		{
			let pos = $( this ).offset()
			e.pageY = pos.top + 20
			e.pageX = pos.left + 20
		prog_click = 1 //#popup
	}
	
	$.get("/sklad_ajax_show_popup.php?ID_item=" + $(this).data("box-id"), function(data) {
		UnCheck($(".item"));
		
		current_box_item.addClass("box_selected");
		
		$("body").append(data);

		if( prog_click )
		{

			let tier_id = $( '#search_sklad_autocomplete option:selected').data('tier-id')
			$('tr.otk_confirmed[data-id=' + tier_id + ']')[0].click()
		}
		var popup = $("#popup");

		popup.css({ "top" : e.pageY + 20, "left" : e.pageX - 20 }).find("input[type=text]:first").focus();

		// На даем элементы выйти за пределы экрана. Нужно подумать над более лучшей реализацией.
		var off = popup.offset(),t = off.top,l = off.left + 50, h = popup.height(), w = popup.width(), docH = $(window).height(), docW = $(window).width();

		var isVisible = (t > 0 && l > 0 && t + h < docH && l + w < docW);

		if (!isVisible) {
			var popup_img = $("#popup img");
			
			if (t + h < docW) 
			{
				popup_img.css("left", (w - 20) + "px");
				popup_img.css("left", (w - 370) + "px");				

				popup.css("margin-left", "-" + (w - 30) + "px");
				popup.css("margin-left", "-" + (w - 380) + "px");			

			} 
			else 
			{
				if (!popup.find("hr").length) 
				{
					popup_img.css({ "top" : (h + 50) + "px", "transform" : "scale(-1, -1)" });
					popup.css("margin-top", "-" + (h + 60) + "px");
				} 
				else 
				{
					al(1)					
					popup_img.css({ "bottom" : (h + 20) + "px" });
					popup.css("margin-bottom", "-" + (h + 60) + "px");
				}
			}

			if( prog_click )
			{
				let top = 200
				let left = 200
				let first_popup_width = 650
				$('.popup:eq(1)').css('left', left + 'px').css('top', top + 'px')
				$('.popup:eq(2)').css('left',left + first_popup_width + 'px').css('top', top + 'px')
				popup_img.css("display", "none");
			}

		}

	});
});

	$(document).on("submit", "#popup", function() {
		var yarus = $(this).find("input[name=YARUS]");

		if (!yarus.prop("disabled")) {
			var yarus_count = yarus.val();

			if (yarus_count > 50) {
				alert("Слишком много ярусов для одной ячейки.");

				return false;
			}

			if (yarus_count > 10) {
				return confirm("Вы уверены что хотите ввести столько ярусов для этой ячейки?");
			} 
		}
	});

	$(document).on("click", "#popup .yarus_select tbody tr", function(e) {
		var current_yarus = $(this);

		if (current_yarus.hasClass("item_selected")) {
			HidePopupYarus();
			
			return;
		}
		
		HidePopupYarus();

		$.get("/sklad_ajax_show_popup_yarus.php?ID_yarus=" + $(this).data("yarus-id"), function(data) {
			$("#popup .yarus_select tbody tr").removeClass("item_selected");

			$("body").append(data);

			current_yarus.addClass("item_selected");

			var popup = $("#popup_yarus");

			popup.find("#box_yarus_id").text("Ячейка: " + $(".box_selected center").text() + ". Ярус: " + $("#popup .item_selected").find("td").first().text() + ".");

			popup.css("display", "none").fadeIn("fast");

			var popup_child = $("#popup"), popup_child_offset = $("#popup").offset();

			popup.css({"position" : "absolute", "top" : popup_child_offset.top , "left" : popup_child_offset.left + popup_child.width() + 25, "height" : popup_child.height(), "width" : "600" }).find("input[type=text]:first").focus();

			popup.find("table input[type=text],input[type=number],textarea").css("width", "520");

			var off = popup.offset(), l = off.left, w = popup.width(), docW = $(window).width();

			var isVisibleLeft = (docW < (l + w));

			if (!isVisibleLeft) {
				popup.css("margin-right", "-" + (popup_child.width() + w + 50) + "px");
			}

			var isVisibleRight = (docW > (l - 20 + w));

			if (!isVisibleRight) {
				popup.css({ "margin-left" : "-" + (popup_child.width() + 300) + "px", "margin-top" : "50px" });
			}
		});
	});

	$(document).on("click", "#multiselect", function() {
		var multi_select = $(this);

		var popup = $(this).closest(".popup");

		var checkbox = $(this).closest(".popup").find("#yarus_item_select input[type=checkbox]");

		checkbox.each(function() {
			if ($(this).closest("tr").css("display") != "none") {
				$(this).prop("checked", multi_select.prop("checked"));

				if (multi_select.prop("checked")) {
					$(this).closest("tr").addClass("item_selected");
				} else {
					$(this).closest("tr").removeClass("item_selected");
				}
			}
		});

		if (popup.attr("id") == "popup_yarus") $("#otk_confirm_remove, #otk_confirm").css("display", "");
	});

	$(document).on("click", "td:has(input[type=checkbox]), td:has(img)", function(e) {
		e.stopPropagation();
	});

	$(document).on("click", "#popup_yarus #yarus_item_select tr", function(e) {
		if (e.ctrlKey) {
			$(this).find("input[type=checkbox]").trigger("click"); 
			return;
		}

		if( parseInt( $(this).data('ref_id')) )
			return;

		var current_yarus_item = $(this);

		if (current_yarus_item.hasClass("item_selected") || $("#popup_yarus input[type=checkbox]:checked").length > 1) {
			BackToAdd();

			return;
		}

		var checkbox = $("#popup_yarus input[type=checkbox]");

		checkbox.each(function() {
			$(this).prop("checked", false);
		});

		current_yarus_item.find("input[type=checkbox]").prop("checked", true);

		$("#popup_yarus #yarus_item_select tr").removeClass("item_selected");

		current_yarus_item.addClass("item_selected");

		$("#popup_yarus input[type=submit]").val("Изменить");
		$("#popup_yarus input[name=COUNT]").val(current_yarus_item.find("td:nth-child(4)").text());
		$("#popup_yarus input[name=NAME]").val(current_yarus_item.find("td:nth-child(2)").text()).focus();
		$("#popup_yarus").data("yarus-item-id", current_yarus_item.data("yarus-item-id"));
		$("#popup_yarus textarea[name=KOMM]").val(current_yarus_item.find("td:nth-child(3)").text());
		$("#popup_yarus, #remove, #move, #otk_status").css("display", "block");

		var inputs = $("#popup_yarus input[type=submit], #popup_yarus input[name=COUNT], #popup_yarus input[name=NAME], #popup_yarus textarea[name=KOMM]");

		if (current_yarus_item.hasClass("otk_confirmed") || $("#popup input[name=NAME]").prop("disabled")) {
			inputs.prop("disabled", true);
			$("#remove, #move").css("display", "none");
		} else {
			inputs.prop("disabled", false);
			$("#remove, #move").css("display", "block");
		}

		if (current_yarus_item.hasClass("otk_confirmed")) {
			$("#otk_confirm").css("display", "none");
			$("#otk_confirm_remove").css("display", "");
		} else {
			$("#otk_confirm_remove").css("display", "none");
			$("#otk_confirm").css("display", "");
		}
	});

	$(document).on("click", "#popup_yarus input[type=submit]", function(e) {
		let yarus_item_name = $("#popup_yarus input[name=NAME]").val()
		let yarus_item_count = $("#popup_yarus input[name=COUNT]").val();

		if (yarus_item_name == "") {
			alert("Поле \"Название\" обязательно для заполнения.");

			return false;
		}

		if (yarus_item_count == "" || yarus_item_count == 0) {
			alert("Поле \"Количество\" обязательно для заполнения и не может быть равно нулю.");

			return false;
		}

		var yarus_id = $("#popup_yarus").data("yarus-id");
		let ID_yarus_item = $("#popup_yarus").data("yarus-item-id")

		let operation_id =  $("#op_select option:selected").val() 
		let zakdet_id = $("#autocomplete option:selected").val()

		if( zakdet_id == undefined )
			zakdet_id = 0

		$.post("/sklad_ajax_yarus_item_edit.php?mode=add_edit", 
		{ 
			ID_yarus_item : ID_yarus_item,
			ID_yarus : yarus_id, 
			NAME : yarus_item_name, 
			KOMM : $("#popup_yarus textarea[name=KOMM]").val(), 
			COUNT : yarus_item_count,
			operation_id : operation_id,
			zakdet_id : zakdet_id
		}, function (data) 
		{
			$( '#op_select option[value=0]' ).prop('selected', 'true');
			refreshYarusItems(yarus_id, function() {
				var yarus_item_select = $(this).parent();

				if ($("#popup_yarus input[type=submit]").val() != "Изменить") {
					yarus_item_select.scrollTop(yarus_item_select.prop("scrollHeight"));
				} else {
					ScrollToEditedItem();
				}
				BackToAdd();
			});
		});
	});

	$(document).on("click", "#popup_yarus #remove", function(e) {
		var yarus_id = $("#popup_yarus").data("yarus-id");

		if ($("#popup_yarus input[type=checkbox]:checked").length > 0) {
			if (confirm("Вы действительно хотите удалить выбранные записи?")) {
				var yarus_item = $("#yarus_item_select input[type=checkbox]:checked");

				var yarus_items_array = [];

				yarus_item.each(function (key, value) {
					yarus_items_array.push($(this).closest("tr").data("yarus-item-id"));
				});

				$.post("/sklad_ajax_yarus_item_edit.php?mode=remove", { ID_yarus_items : yarus_items_array, ID_yarus : yarus_id }, function () {
					refreshYarusItems(yarus_id, BackToAdd());
				});
			}
		} else {
			if (confirm("Вы действительно хотите удалить выбранную запись?")) {
				$.post("/sklad_ajax_yarus_item_edit.php?mode=remove", { ID_yarus_items : [$("#popup_yarus").data("yarus-item-id")], ID_yarus : yarus_id }, function () {
					refreshYarusItems(yarus_id, BackToAdd());
				});
			}
		}
	});

	$(document).on("click", "#popup_yarus #delete_item", function(e) {
		var yarus_id = $("#popup_yarus").data("yarus-id");
		
		if (confirm("Вы действительно хотите удалить выбранную запись?")) {
			$.post("/sklad_ajax_yarus_item_edit.php?mode=remove", { ID_yarus_items : [$(this).closest("tr").data("yarus-item-id")], ID_yarus : yarus_id }, function () {
				refreshYarusItems(yarus_id, BackToAdd());
			});
		}
	});

	$(document).on("click", "#popup #delete_item", function(e) {	
		if (confirm("Вы действительно хотите удалить выбранный ярус?")) {
			$.post("/sklad_ajax_yarus_edit.php?mode=remove", { ID_yaruses : [$(this).closest("tr").data("yarus-id")], ID_box_item : $("#popup").data("box-id") }, function (data) {
				refreshYarus($("#popup").data("box-id"), BackToAdd());

				HidePopupYarus();
			});
		}
		return false;
	});

	$(document).on("click", "#popup_yarus #move", function(e) {
		var button_text = $(this).text();

		if ($("#move_items_block").css("display") == "block") {
			$(this).text("Изменить ярус");

			$("#move_items_block").css("display", "none");
			$("#remove, #otk_status").css("display", "block");
		} else {
			$(this).text("Отмена");

			$("#move_items_block").css("display", "block");
			$("#remove, #otk_status").css("display", "none");
		}

		$("#move_items_block").empty().load("/sklad_ajax_form_move_yarus_item.php", function () {
			$("#move_items_block #sklad_select").val($("#popup").data("sklad-id")).trigger("change");
		});
	});

	$(document).on("click", "#popup_yarus #otk_confirm", function(e) {
		var yarus_id = $("#popup_yarus").data("yarus-id");
		
		if ($("#popup_yarus input[type=checkbox]:checked").length > 0) {
			if (confirm("Вы действительно хотите подвердить контроль ОТК для выбранных записей?")) {
				var yarus_item = $("#yarus_item_select input[type=checkbox]:checked");

				var yarus_items_array = [];

				yarus_item.each(function (key, value) {
					yarus_items_array.push($(this).closest("tr").data("yarus-item-id"));
				});

				$.post("/sklad_ajax_yarus_item_edit.php?mode=otk_confirm", { ID_yarus_items : yarus_items_array }, function () {
					refreshYarusItems(yarus_id, BackToAdd());
				});
			}
		} else {
			if (confirm("Вы действительно хотите подвердить контроль ОТК для выбранной записи?")) {
				$.post("/sklad_ajax_yarus_item_edit.php?mode=otk_confirm", { ID_yarus_items : [$("#popup_yarus").data("yarus-item-id")] }, function () {
					refreshYarusItems(yarus_id, function () {
						$("#popup_yarus #multiselect").prop("checked", false);

						ScrollToEditedItem();

						BackToAdd();
					});
				});
			}
		}
	});

	$(document).on("click", "#popup_yarus #otk_confirm_remove", function(e) {
		var yarus_id = $("#popup_yarus").data("yarus-id");
		
		if ($("#popup_yarus input[type=checkbox]:checked").length > 0) {
			if (confirm("Вы действительно хотите снять подтверждение контроля ОТК для выбранных записей?")) {
				var yarus_item = $("#yarus_item_select input[type=checkbox]:checked");

				var yarus_items_array = [];

				yarus_item.each(function (key, value) {
					yarus_items_array.push($(this).closest("tr").data("yarus-item-id"));
				});

				$.post("/sklad_ajax_yarus_item_edit.php?mode=otk_confirm_remove", { ID_yarus_items : yarus_items_array }, function (data) {
					refreshYarusItems(yarus_id, BackToAdd());
				});
			}
		} else {
			if (confirm("Вы действительно хотите снять подтверждение контроля ОТК для выбранной записи?")) {
				$.post("/sklad_ajax_yarus_item_edit.php?mode=otk_confirm_remove", { ID_yarus_items : [$("#popup_yarus").data("yarus-item-id")] }, function (data) {
					refreshYarusItems(yarus_id, function () {
						$("#popup_yarus #multiselect").prop("checked", false);

						ScrollToEditedItem();

						BackToAdd();
					});
				});
			}
		}
	});

	$(document).on("change", "#sklad_select", function() {
		var sklad_item_select = $("#sklad_item_select");

		sklad_item_select.empty().load("/sklad_ajax_item_move.php?show=sklad_items&ID_sklad=" + $(this).val(), function () {
			sklad_item_select.val($("#popup").data("box-id")).trigger("change").focus();
		});
	});

	$(document).on("change", "#popup_yarus input[type=checkbox]", function(e) {
		if ($("#popup_yarus input[type=checkbox]:checked").length > 0) {
			if ($(this).prop("checked")) {
				$(this).closest("tr").addClass("item_selected");
			} else {
				$(this).closest("tr").removeClass("item_selected");
			}

			$("#remove, #move, #otk_status").css("display", "block");
			$("#otk_confirm, #otk_confirm_remove").css("display", "");
		} else {
			BackToAdd();
		}
	});

	$(document).on("keyup", "#popup_yarus input[name=NAME]", function(e) {
		var autocomplete = $("#popup_yarus #autocomplete");
		
		if ($(this).val().length > 2) {
			autocomplete.empty().load("/sklad_ajax_yarus_item_edit.php?mode=search_item&text=" + encodeURIComponent($(this).val()), function () 
			{
				if (autocomplete.find("option").length != 0) 
				{
					autocomplete.css("display", "block");
				} 
				else 
				{
					autocomplete.css("display", "none");
				}
			});

		} else {
			autocomplete.css("display", "none");
		}
	});

	$( '#search_sklad_autocomplete').unbind('change').click('change', selectFoundItem )

	function selectFoundItem()
	{
		let cell_id = $( this ).find('option:selected').data('cell-id')
		if( $('div.item[data-box-id=' + cell_id + ']')[0] )
			$('div.item[data-box-id=' + cell_id + ']')[0].click()
	}


	$(document).on("keyup", "#search_sklad", function(e) {
		var autocomplete = $("#search_sklad_autocomplete");
		
		if ($(this).val().length > 2) {
			autocomplete.empty().load("/sklad_ajax_yarus_item_edit.php?wh_id=" + wh_id + "&mode=search_sklad&text=" + encodeURIComponent($(this).val()), function () {

				if (autocomplete.find("option").length != 0) 
				{
					autocomplete.css("display", "block");
				} 
				else 
				{
					autocomplete.css("display", "none");
				}
			});

		} else {
			autocomplete.css("display", "none");
		}
	});

	$(document).on("change", "#popup_yarus #autocomplete", function ()
	{
		$("#popup_yarus input[name=NAME]").val( $(this).find('option:selected').text() );
		$(this).css("display", "none");
		check_can_insert()
	});

	$(document).on("keyup", "#dse_name", check_can_insert );

	$(document).on("change", "#popup_yarus #op_select", function ()
	{
		$( this ).find('option[value=0]').prop('disabled', true )
		check_can_insert()
	});

	function check_can_insert()
	{
		let dse_name = $('#dse_name').val()
	// let op_id = parseInt( $('#op_select option:selected').val() )
	if( dse_name.length ) // && op_id )
		$('#insert').prop('disabled', false )
	else
		$('#insert').prop('disabled', true )
}

$(document).on("change", "#popup input[type=checkbox]:not(input[name=floor])", function(e) {
	if ($(this).prop("checked")) {
		$(this).closest("tr").addClass("item_selected");
		
		$("#remove_yarus").css("display", "block");
	} else {
		$(this).closest("tr").removeClass("item_selected");
		
		$("#remove_yarus").css("display", "");
	}
});

$(document).on("change", "#sklad_item_select", function() {
	$("#sklad_yarus_select").empty().load("/sklad_ajax_item_move.php?show=sklad_item_yaruses&ID_sklad_item=" + $(this).val());
});

$(document).on("change", "#sklad_yarus_select", function() {
	var yarus_id = $("#popup_yarus").data("yarus-id");

	if ($("#popup_yarus input[type=checkbox]:checked").length > 0) {
		if (confirm("Вы действительно хотите перенести выбранные предметы?")) {
			var yarus_item = $("#popup_yarus #yarus_item_select input[type=checkbox]:checked");

			var yarus_items_array = [];

			yarus_item.each(function (key, value) {
				yarus_items_array.push($(this).closest("tr").data("yarus-item-id"));
			});
			
			$.post("/sklad_ajax_yarus_item_edit.php?mode=move", { ID_yarus_items : yarus_items_array, ID_yarus : $(this).val(), ID_yarus_from : yarus_id }, function (data) {
				refreshYarusItems(yarus_id, BackToAdd());
			});
		}
	} else {
		if (confirm("Вы действительно хотите перенести выбранный предмет?")) {
			$.post("/sklad_ajax_yarus_item_edit.php?mode=move", { ID_yarus_items : [$("#popup_yarus").data("yarus-item-id")], ID_yarus : $(this).val(), ID_yarus_from : yarus_id }, function (data) {
				refreshYarusItems(yarus_id, BackToAdd());
			});
		}
	}
});

$(document).on("click", "#popup #remove_yarus", function(e) {
	var yarus_id = $("#popup").data("box-id");

	if ($("#popup input[type=checkbox]:not(input[name=floor]):checked").length > 0) {
		if (confirm("Вы действительно хотите удалить выбранные ярусы?")) {
			var yarus_item = $("#popup #yarus_item_select input[type=checkbox]:checked");

			var yarus_items_array = [];

			yarus_item.each(function (key, value) {
				yarus_items_array.push($(this).closest("tr").data("yarus-id"));
			});
			
			$.post("/sklad_ajax_yarus_edit.php?mode=remove", { ID_yaruses : yarus_items_array, ID_box_item : yarus_id }, function () {
				refreshYarusItems(yarus_id, BackToAdd());
			});
		}
	} else {
		if (confirm("Вы действительно хотите удалить выбранный ярус?")) {
			$.post("/sklad_ajax_yarus_edit.php?mode=remove", { ID_yaruses : [$("#popup_yarus input[name=yarus_item]").val()], ID_box_item : yarus_id }, function () {
				refreshYarusItems(yarus_id, BackToAdd());
			});
		}
	}
});

$(document).on("keyup", "#search_item", function () {
	var value = this.value.toLowerCase().trim();

	var items = $("#popup_yarus #yarus_item_select tr");
	
	items.each(function () {
		$(this).find("td").each(function () {
			var id = $(this).text().toLowerCase().trim();
			
			var not_found = (id.indexOf(value) == -1);
			
			if (!not_found) {
				$(this).closest("tr").css("display", "");
			} else {
				$(this).closest("tr").css("display", "none");
			}
			
			return not_found;
		});
	});
});

function refreshYarusItems(yarus_id, callback)
{
	$("#popup_yarus #yarus_item_select").empty().load("/sklad_ajax_show_popup_yarus.php?ID_yarus=" + yarus_id + " #popup_yarus #yarus_item_select", callback);
	
	refreshYarus($(".box_selected").data("box-id"), null);
}

function refreshYarus(box_id, callback)
{
	$("#popup").empty().load("/sklad_ajax_show_popup.php?ID_item=" + box_id + " #popup #test", callback);
}

function HidePopup()
{
	$("#popup, #popup_yarus").remove();
	UnCheck($(".item"));
}

function HidePopupYarus()
{
	$("#popup_yarus").remove();
	$("#popup .yarus_select tbody tr").removeClass("item_selected");
}

function UnCheck(element)
{
	$(element).removeClass("box_selected");
}

function BackToAdd()
{
	$("#move").text("Изменить ярус");
	$("#move_items_block, #remove, #move, #otk_status").css("display", "none");
	$("#popup_yarus #multiselect").prop("checked", false);
	$("#popup_yarus #yarus_item_select tr").removeClass("item_selected");
	$("#popup_yarus input[name=COUNT]").val("1");
	$("#popup_yarus input[name=NAME], #popup_yarus textarea[name=KOMM]").val("");
	$("#popup_yarus").data("yarus-item-id", "");
	$("#popup_yarus input[type=checkbox]").prop("checked", false);
	$("#popup_yarus input[type=submit]").val("Добавить");
	$("#popup_yarus input[type=submit], #popup_yarus input[name=COUNT], #popup_yarus input[name=NAME], #popup_yarus textarea[name=KOMM]").prop("disabled", $("#popup input[name=NAME]").prop("disabled"));
	$("#search_item").val("");
	$("#popup_yarus input[name=NAME]").focus();
}

function ScrollToEditedItem ()
{	
	var edited_item = $("#popup_yarus #yarus_item_select tr"), edited_element;

	edited_item.each(function () {
		
		if ($(this).data("yarus-item-id") == $("#popup_yarus").data("yarus-item-id")) {
			edited_element = $(this);

			return;
		}
	});

	$("#popup_yarus #yarus_item_select").parent().scrollTop(edited_element.offset().top - 450);
	
	edited_element.addClass("item_edited").fadeIn(1300, function () {
		edited_element.removeClass("item_edited");
	});
}
</script>


