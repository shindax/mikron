	<style>
	body {
		padding: 0px;
		margin: 0px;
		background: #e5effb;
	}
	#operdiv a {
		text-decoration: none;
		color: #00036c;
	}
	td {
		font: normal 11px Verdana;
	}
	span.dt {
		font: normal 9px Verdana;
		color: #555;
		padding: 0px;
		margin: 0px;
	}
	div {
		padding: 0px;
		margin: 0px;
		display: block;
		overflow: hidden;
	}
	table {
		border-collapse: collapse;
		width: 100%;
		height: 100%;
		margin: 0px;
	}
	td.LTD {
		padding: 0px;
		vertical-align: top;
	}
	td.RTD {
		padding: 0px;
		vertical-align: top;
	}
	td.GNT {
		border: 1px solid black;
		padding: 0px;
	}
	td.GNT span {
		display: block;
		width: 34px;
		text-align: center;
		margin: 0px;
		padding: 0px;
	}
	tr.GNT td {
		border-bottom: 1px solid black;
		overflow: hidden;
		white-space: nowrap;
	}
	td.GNT2 {
		border: 1px solid black;
		padding: 0px;
		vertical-align: top;
	}
	td.GNT2 span {
		display: block;
		padding: 0px;
		margin: 0px;
		width: 34px;
		text-align: center;
	}
	span.p {
		padding-left: 0px;
	}
	tr.RTOP {
		height: 50px;
	}
	tr.RTOP td {
		border: 1px solid black;
	}
	tr.RBOTTOM {
		height: 120px;
	}
	tr.RBOTTOM  td.RTD td {
		font: normal 12px Arial;
	}
	tr.RBOTTOM  td.RTD td b {
		font: bold 12px Arial;
	}
	tr.FOOTER {
		background: URL(img/pb.gif) repeat-x;
		height: 20px;
	}
	td.data {
		overflow: hidden;
		white-space: nowrap;
		border: 1px solid black;
		padding: 0px;
		cursor: hand;
	}
	td.data span {
		display: block;
		width: 34px;
		text-align: center;
		margin: 0px;
		padding: 0px;
		font: normal 11px "Arial Narrow";
	}
	td.GNTt {
		overflow: hidden;
		white-space: nowrap;
		border: 1px solid black;
		padding: 0px;
	}
	td.GNTt span {
		display: block;
		width: 34px;
		text-align: center;
		margin: 0px;
		padding: 0px;
		font: normal 11px "Arial Narrow";
	}
	div.hdn {
		overflow: hidden;
		width: <?php echo $L_width; ?>px;
		display: block;
		border: 0px solid black;
	}
	div.smenzad {
		display: none;
		width: 100%;
		height: 100%;
		position: absolute;
		top: 0px;
		left: 0px;
		background: URL(img/hidebg.png);
		text-align: center;
	}
	#smenzad a {
		text-decoration: none;
		color: #fff;
	}
	img {
		border: 0px solid black;
	}
	form {
		padding: 0px;
		margin: 0px;
	}
	input.inp {
		margin: 3px 1px 5px 1px;
		width: 30px;
	}
	img {
		border: 0px solid black;
	}
	a.MARKERKEYON {
		display: block;
		width: 20px;
		height: 20px;
		background: url(img/markeroff.png);
		float: left;
		margin: 5px;
	}
	a.MARKERKEYOFF {
		display: none;
		width: 20px;
		height: 20px;
		background: url(img/markeron.png);
		float: left;
		margin: 5px;
	}
	.ZAK_TR {
		<?php echo $zak_tr_style; ?>
	}
	.IZD_TR {
		<?php echo $izd_tr_style; ?>
	}
	.OPER_TR {
		<?php echo $oper_tr_style; ?>
	}
	td.TODAY {
		background: URL(img/today.png);
	}
	.OPER_TR td.INW span {
		<?php echo $oper_iwork_style; ?>
		font: normal 14px "Arial Narrow";
		cursor: hand;
	}
	.IZD_TR td.INW span {
		<?php echo $izd_iwork_style; ?>
		font: normal 14px "Arial Narrow";
		cursor: hand;
	}
	.ZAK_TR td.INW span {
		<?php echo $zak_iwork_style; ?>
		font: normal 14px "Arial Narrow";
		cursor: hand;
	}
	.OPER_TR td.INP span {
		<?php echo $oper_iplan_style; ?>
		font: normal 14px "Arial Narrow";
		cursor: hand;
	}
	.IZD_TR td.INP span {
		<?php echo $izd_iplan_style; ?>
		font: normal 14px "Arial Narrow";
		cursor: hand;
	}
	.ZAK_TR td.INP span {
		<?php echo $zak_iplan_style; ?>
		font: normal 14px "Arial Narrow";
		cursor: hand;
	}

	.OPER_TR td span.REDACT {
		font: normal 14px "Arial Narrow";
		height: ".$oper_row_height_span."px;
	}

	.OPER_TR td.INW span.REDACT {
		<?php echo $span_redact_style; ?>
		font: normal 11px "Arial Narrow";
	}
	.OPER_TR td.INW span.REDACT b {
		font: normal 14px "Arial Narrow";
	}

	.OPER_TR td.INP span.REDACT {
		<?php echo $span_predact_style; ?>
		font: normal 11px "Arial Narrow";
	}
	.OPER_TR td.INP span.REDACT b {
		font: normal 14px "Arial Narrow";
	}
	</style>
