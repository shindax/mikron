<?php
///////////////////////////////////////////////////////////////////////
// �������������� ������� �������
//
//
///////////////////////////////////////////////////////////////////////

	include "includes.php";




// ���� ���� ������
if (db_adcheck("db_zadan")) {

	if (isset($_POST['edit_tabel'])) {
		
	}


	$resurs = $_GET["resurs"];
	$date = $_GET["date"];
	$smen = $_GET["smen"];
	$ID_operitems = $_GET["operitem"];
	$tp = $_GET["tp"];
	$val = $_GET["val"]*1;

	$result = dbquery("SELECT * FROM ".$db_prefix."db_operitems where (ID='".$ID_operitems."')");
	$operitem = mysql_fetch_array($result);

	$result = dbquery("SELECT * FROM ".$db_prefix."db_zakdet where (ID='".$operitem["ID_zakdet"]."')");
	$operizd = mysql_fetch_array($result);

	$delta = 0;

	$result = dbquery("SELECT * FROM ".$db_prefix."db_zadan where (DATE = '".$date."') and (ID_resurs = '".$resurs."') and (ID_operitems = '".$ID_operitems."')");


	function FormatReal($num,$x) {
		$ret = number_format( $x, $num, '.', ' ');
		if ($x==floor($x)) $ret = number_format($x, 0, '.', ' ');
		return $ret;
	}

// �������������� ��
////////////////////////////////////

	if ($zad = mysql_fetch_array($result)) {
	// ���� ���� ����� ��������

		if ($tp=="num") dbquery("Update ".$db_prefix."db_zadan Set NUM:='".$val."' where (ID='".$zad["ID"]."')");
		if ($tp=="norm") {
			$val_x = $zad["NORM"]*1;
			$delta = $val - $val_x;
			dbquery("Update ".$db_prefix."db_zadan Set NORM:='".$val."' where (ID='".$zad["ID"]."')");
			if ($val==0) {
			// ������� ��� ����������� 0 �/�
				dbquery("DELETE from ".$db_prefix."db_zadan where (ID='".$zad["ID"]."')");
			}
		}

		// ������ zadanres
		// �������� ������� � �� ������� �� �������� ���� � �����

		$result = dbquery("SELECT * FROM ".$db_prefix."db_zadanres where (DATE = '".$date."') and (ID_resurs = '".$resurs."') and (SMEN = '".$smen."')");
		if ($zadres = mysql_fetch_array($result)) {
		// ���� ���� �� ������

		} else {
		// ���� ��� ���������
			dbquery("INSERT INTO ".$db_prefix."db_zadanres (SMEN, ID_resurs, DATE) VALUES ('".$smen."', '".$resurs."', '".$date."')");
		}

	} else {
	// ���� ��� ����� ��������
		if ($val==0) {
		// ��� ����������� 0 �/� ���� 0 �� ������ �� ������

		} else {
		// ��� ����������� �� �������� �������� ���������
			if ($tp=="num") dbquery("INSERT INTO ".$db_prefix."db_zadan (SMEN, ID_park, ID_zak, ID_zakdet, ID_operitems, ID_resurs, DATE, EDIT_STATE, NUM) VALUES ('".$smen."', '".$operitem["ID_park"]."', '".$operizd["ID_zak"]."', '".$operitem["ID_zakdet"]."', '".$ID_operitems."', '".$resurs."', '".$date."', '0', '".$val."')");
			if ($tp=="norm") {
				$delta = $val;
				dbquery("INSERT INTO ".$db_prefix."db_zadan (SMEN, ID_park, ID_zak, ID_zakdet, ID_operitems, ID_resurs, DATE, EDIT_STATE, NORM) VALUES ('".$smen."', '".$operitem["ID_park"]."', '".$operizd["ID_zak"]."', '".$operitem["ID_zakdet"]."', '".$ID_operitems."', '".$resurs."', '".$date."', '0', '".$val."')");
			}

			// ������ zadanres
			// �������� ������� � �� ������� �� �������� ���� � �����

			$result = dbquery("SELECT * FROM ".$db_prefix."db_zadanres where (DATE = '".$date."') and (ID_resurs = '".$resurs."') and (SMEN = '".$smen."')");
			if ($zadres = mysql_fetch_array($result)) {
			// ���� ���� �� ������

			} else {
			// ���� ��� ���������
				dbquery("INSERT INTO ".$db_prefix."db_zadanres (SMEN, ID_resurs, DATE) VALUES ('".$smen."', '".$resurs."', '".$date."')");
			}
		}
	}


// ������������ PLANZAD
////////////////////////////////////

	if ($delta>0) {
	// ���� ������ ��������� � ������� �������
		$result = dbquery("SELECT * FROM ".$db_prefix."db_planzad where (ID_operitems = '".$ID_operitems."') and (DATE='".$date."')");
		if ($planzad = mysql_fetch_array($result)) {
		// ���� ���� ���� �� �������� �� ��� ���� ����� ���������

			$new_norm = $planzad["NORM"]*1 - $delta;
			$new_norm = FormatReal(2,$new_norm);
			if ($new_norm>0) {
			// ���� ��� �� ������� � ����� �� ��������
				dbquery("Update ".$db_prefix."db_planzad Set NORM:='".$new_norm."' where (ID_operitems = '".$ID_operitems."') and (DATE='".$date."')");
			} else {
			// ���� ���� �������� �� �������
				dbquery("DELETE from ".$db_prefix."db_planzad where (ID_operitems = '".$ID_operitems."') and (DATE='".$date."')");
			}

		}
	}
	


// �������� ������
////////////////////////////////////

include "calczak.php";


}
?>