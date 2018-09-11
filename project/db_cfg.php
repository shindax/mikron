<?php

//////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	� 2012 ���������� �.�.
//
//////////////////////////////////////////////////////

/////////////////////////////////////////////////////////
//
// ���� ������������ ���� ������
//
// ����������������� ������� users|rightgroups|viewgroups|formgroups|forms|formsitem
// ����������������� ���� ������ (ID, PID, LID)
// ���� ������ $db."|TYPE" (line, tree, ltree)
// ���� ����������� ���� $db."/".$field:
//
//	     �����
//
//		integer		- ����� �����
//		pinteger	- ����� ������������� �����
//		real		- ������� �����
//		preal		- ������� ������������� �����
//		money		- ������� �����, ����������� ��� ������
//		pmoney		- ������� ������������� �����, ����������� ��� ������
//
//	     �������
//
//		boolean		- �������
//		state		- ����� ������� �� ����������� ������ (������������ �������������� ��. HOLDBY, HOLDDEL � �.�.)
//		alist		- ���������� ������ ���������
//
//	     ���������
//
//		tinytext	- ����� ������ �� 255 �������� (���� ���� ������)
//		text		- ����� ������� (���� ���� ������)
//		textarea	- ����� ������� (������������������ <teaxtarea>)
//		mediumtext	- ����� ������� �� ��������� � ������ (������������ ��� ���������������� �����)
//
//	     ���� �����
//
//		date		- ����
//		time		- �����
//		dateplan	- ���� ���� (���������� �������)
//
//	     �����
//
//		file		- �������� �����
//
//	     ����� � ������� ���������
//
//		droplist	- ���������� ������ (�������� � ������ ID)
//		list		- ������ � ������� (�������� � ������ ID)
//		multilist	- ���������� ������ (�������� �� ������ ID)
//
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//
//	������ �������� �������
//
//	$table = "db_test";							// ��� ������� � ��
//
//	$db_cfg[$table."|TYPE"] = "line";					// ��� ������� - ��������
//	$db_cfg[$table."|ERP"] = "false";					// �� ��������� � ���� �������
//
//	$db_cfg[$table."|MORE"] = "false";					// ��������� � ������� ������
//	$db_cfg[$table."|DELRIGHT"] = "ID_users";				// ����� �� �������� ������ � users/ID ������������ � ����� ID_users � � DELETE
//	$db_cfg[$table."|CREATEBY"] = "ID_users";				// ���������� ��������� �������� � ��� ����
//	$db_cfg[$table."|CREATEDATE"] = "DATE";					// ���������� ���� �������� �������� � ��� ����
//	$db_cfg[$table."|EDITTIME"] = "ETIME";					// ���������� ���� ����� ���������� ��������� �/� ����������� ���������
//	$db_cfg[$table."|EDITUSER"] = "EUSER";					// ���������� user_id ������� ��������� ��������� �/� ����������� ���������
//	$db_cfg[$table."|HOLDBY"] = "SOGL";					// ���� ���� state �������������� �������������� ����� ��������� ������� (���� ���� LID ��� ltree ��������)
//	$db_cfg[$table."|HOLDDEL"] = "SOGL";					// ���� ���� state �������������� �������� ������ ����� ��������� �������
//	$db_cfg[$table."|DELWITH"] = "";					// $db/$field ������� ������ � ��������� �������� �� $db ��� $field=LIST_ID ����� ��������
//	$db_cfg[$table."|ADDWITH"] = "";					// $db/$field �������� ������ � ��������� ������� � $db � �������� � $field=LIST_ID ����� ��������
//	$db_cfg[$table."|ONCREATE"] = "add_db_test.php";			// ���� php ������� ����� ����������� ����� �������� �������� (��� ������������ ����� ������ ��� ��������� ��������)
//	$db_cfg[$table."|ONDELETE"] = "del_db_test.php";	//$delet_id (�� ���������� ��������)		// ���� php ������� ����� ����������� ����� �������� �������� (��� ������������ ����� ������ ��� ��������� ��������)
//	$db_cfg[$table."|BYPARENT"] = "VAL";					// ���� ������� ��� �������� ����� ����������� � PID ���� ������� ����
//	$db_cfg[$table."|MAXDEEP"] = 2;						// ������������ ������� ��� tree � ltree (���� �� ���������� �� ��� �����������)
//
//	$db_cfg[$table."|LIST_FIELD"] = "ID";					// ���� ��� ����������� ��� ������ ������ (�����) � ������ ������
//	$db_cfg[$table."|LIST_SEARCH"] = "NAME";				// ���� �� ������� ������������ ����� (��� list, multilist � �.�.)
//	$db_cfg[$table."|LIST_PREFIX"] = ", ";
//	$db_cfg[$table."|ADDINDEX"] = "";
//	$db_cfg[$table."|LID_FIELD"] = "";					// ���� ��� ����������� ��� ������ ������ (�����) ��� ltree
//	$db_cfg[$table."|LID_SEARCH"] = "";					// ���� �� ������� ��� ����� ��� ltree
//
//	$db_cfg[$table."|FIELDS"] = "TXT|SOGL|ID_users|VAL";
//
//		$db_cfg[$table."/TXT"] = "textarea";
//		$db_cfg[$table."/TXT|EDITRIGHT"] = "ID_users";			// ����� �� �������������� ������ � users/ID ������������ � ����� ID_users
//		$db_cfg[$table."/ID_users"] = "list";
//		$db_cfg[$table."/ID_users|LIST"] = "users";
//		$db_cfg[$table."/SOGL"] = "state";
//		$db_cfg[$table."/SOGL|LIST"] = "����.|����.";
//		$db_cfg[$table."/SOGL|HOLD"] = "QWEST|ID_users|DATE|UNSW|SOGL";	// ����������� �� �������������� ����� ��������� �������
//		$db_cfg[$table."/SOGL|USER"] = "ID_users";			// �������� ID ������������ ����������� ������
//		$db_cfg[$table."/SOGL|DATE"] = "DATE";				// �������� ���� ��������� �������
//		$db_cfg[$table."/SOGL|ONCHANGE"] = "change_db_test_SOGL.php";	// ���� php ������� ����� ����������� ����� ��������� �������
//		$db_cfg[$table."/VAL"] = "pinteger";
//		$db_cfg[$table."/DATE"] = "date";
//		$db_cfg[$table."/ETIME"] = "time";
//
//
//
//
//
//	!	��������� � ���������������, �������� �������� !!!!
//
/////////////////////////////////////////////////////////

	if (!defined("MAV_ERP")) { die("Access Denied"); }


	$db_cfg["PROJECT"] = "";




   // ������������ PROJECT
   //////////////////////////////////////////////////////////////////////////////////////////////////////


	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."db_specialization";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_clients";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_krz";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_krzdet";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_krzdetitems";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_files_1_cat";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_files_1";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_files_2";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_files_2_cat";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_files_3";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_krz2";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_krz2det";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_krz2detitems";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_arrival_plan";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_urface";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_ktd_cat";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_ktd_izd";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_ktd_files";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_zak";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_zakdet";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_park";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_oper";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_otdel";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_special";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_speclvl";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_shtat";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_resurs";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_tab_sti";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_tab_st";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_tab_pci";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_tab_pc";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_operitems";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_zadan";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_zadanrcp";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_tabel";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_zadanres";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_mat_cat";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_sort_cat";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_mat";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_sort";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_zn_zag";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_zn_pok";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_contacts";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_it_req";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_hr_req";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_ogi_req";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_tmc_req";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_zak_req";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_koop_req";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_prog_req";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_itrzadan";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_zn_instr";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_inv_cat";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_inv";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_inv_places";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_clients_contacts";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_reference_tool";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_inv_cat_tools";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_edo_inout_files";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_edo_inout_files_vidfails";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_edo_vremitr";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_itr_vremitr";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_itrzadan";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_itrzadan_statuses";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_logistic_app";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_inv_storage_areas";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_planzad";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_zapros_all";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_online_chat_curid";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_online_chat_curid_users";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_protocols";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_v_mov_adr";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_v_reference_tool";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_movements_tool";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_movements_tool_destination";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_stocks_doc";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_v_stocks_doc";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_safety_job";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_stocks_doc_inventory";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_inv_s";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_inv_s_subj_addr";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_stock_doctype";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_mtk_perehod";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_mtk_perehod_img";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_movements_tool_transfer";
	$db_cfg["PROJECT"] = $db_cfg["PROJECT"]."|db_edo_inout_files_vrem";

   // ������������� setup
   //////////////////////////////////////////////////////////////////////////////////////////////////////

	$db_cfg["SETUP"] = "db_edo_inout_files_vrem"; // � ������� ������� �������� ������� ������� ������ ������� (���� ���� �����) � �����������













//////////
//	//
//  1	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ���� ������������
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_specialization";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "���� ������������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|ONCREATE"] = "";

	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|MORE";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/MORE"] = "tinytext";



//////////
//	//
//  2	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// �� ����������
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_clients";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "���������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "db_clients_contacts/ID_clients";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|ONCREATE"] = "";

	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|ID_specialization|MORE|TEL|ADR|CONT|REKV|GOROD|PROCH|CODE|OBOZ|PZAK|PPOST";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/ID_specialization"] = "list";
		$db_cfg[$table."/ID_specialization|LIST"] = "db_specialization";
		$db_cfg[$table."/MORE"] = "textarea";			// ����������
		$db_cfg[$table."/TEL"] = "textarea";			// �������
		$db_cfg[$table."/ADR"] = "textarea";			// �����
		$db_cfg[$table."/CONT"] = "textarea";			// ��������
		$db_cfg[$table."/REKV"] = "textarea";			// ���������
		$db_cfg[$table."/GOROD"] = "tinytext";			// �����
		$db_cfg[$table."/PROCH"] = "textarea";			// ������
		$db_cfg[$table."/CODE"] = "tinytext";			// 1c ���
		$db_cfg[$table."/OBOZ"] = "tinytext";			// ������ ������������
		$db_cfg[$table."/PZAK"] = "boolean";			// ������� - ��������
		$db_cfg[$table."/PPOST"] = "boolean";			// ������� - ���������







//////////
//	//
//  3	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ������� ��� (1� ����)
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_krz";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "���";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "ID_users";
	$db_cfg[$table."|CREATEDATE"] = "DATE_START";
	$db_cfg[$table."|HOLDBY"] = "EDIT_STATE";
	$db_cfg[$table."|HOLDDEL"] = "EDIT_STATE";
	$db_cfg[$table."|DELWITH"] = "db_krzdet/ID_krz";
	$db_cfg[$table."|ADDWITH"] = "db_krzdet/ID_krz";
	$db_cfg[$table."|ONCREATE"] = "add_db_krz.php";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|ID_clients|ID_postavshik|ID_users|DATE_START|DOGOVOR|SERIYA|DATE_PLAN|DOCS|MORE|MORE2|NORM_PRICE|EXPERT|MORE_EXPERT|EDIT_STATE";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/ID_clients"] = "list";
		$db_cfg[$table."/ID_clients|LIST"] = "db_clients";
		$db_cfg[$table."/ID_postavshik"] = "alist";
		$db_cfg[$table."/ID_postavshik|LIST"] = "��� ������|��������";
		$db_cfg[$table."/ID_users"] = "list";
		$db_cfg[$table."/ID_users|LIST"] = "users";
		$db_cfg[$table."/ID_users|LIST_WHERE"] = "STATE='0'";
		$db_cfg[$table."/DATE_START"] = "date";			// ���� �������
		$db_cfg[$table."/DOGOVOR"] = "tinytext";		// ����� ��������
		$db_cfg[$table."/SERIYA"] = "tinytext";			// ����������� ����������
		$db_cfg[$table."/DATE_PLAN"] = "tinytext";		// ����������� ����� ��������
		$db_cfg[$table."/DOCS"] = "tinytext";			// ����������� ��� ���������
		$db_cfg[$table."/NORM_PRICE"] = "preal";		// ���� �/� �� ������
		$db_cfg[$table."/EXPERT"] = "list";			// �������
		$db_cfg[$table."/EXPERT|LIST"] = "users";
		$db_cfg[$table."/MORE_EXPERT"] = "textarea";		// ���������� ��������
		$db_cfg[$table."/MORE"] = "textarea";			// ���������� �������
		$db_cfg[$table."/MORE2"] = "tinytext";			// ���������� �������� �� ���.
		$db_cfg[$table."/EDIT_STATE"] = "state";
		$db_cfg[$table."/EDIT_STATE|LIST"] = "���������";
		$db_cfg[$table."/EDIT_STATE|ONCHANGE"] = "krz_edit_state_change.php";
		$db_cfg[$table."/EDIT_STATE|HOLD"] = "NAME|ID_clients|ID_postavshik|ID_users|DATE_START|DOGOVOR|SERIYA|DATE_PLAN|DOCS|MORE|NORM_PRICE|EXPERT|MORE_EXPERT";
		$db_cfg[$table."/EDIT_STATE|USER"] = "EXPERT";







//////////
//	//
//  4	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ������ ��� ������� ���
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_krzdet";

	$db_cfg[$table."|TYPE"] = "tree";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "������������ � ���";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "db_krzdet/PID|db_krzdetitems/ID_krzdet";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "ID_krz";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|ID_krz|OBOZ|COUNT|D1|D2|D3|D4|D5|D6|D7|D8|D9|D10|D11|VES";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/OBOZ"] = "tinytext";
		$db_cfg[$table."/ID_krz"] = "list";
		$db_cfg[$table."/ID_krz|LIST"] = "db_krz";
		$db_cfg[$table."/COUNT"] = "pinteger";

		$db_cfg[$table."/VES"] = "preal";
		$db_cfg[$table."/D1"] = "preal";
		$db_cfg[$table."/D2"] = "preal";
		$db_cfg[$table."/D3"] = "preal";
		$db_cfg[$table."/D4"] = "preal";
		$db_cfg[$table."/D5"] = "preal";
		$db_cfg[$table."/D6"] = "preal";
		$db_cfg[$table."/D7"] = "preal";
		$db_cfg[$table."/D8"] = "preal";
		$db_cfg[$table."/D9"] = "preal";
		$db_cfg[$table."/D10"] = "preal";
		$db_cfg[$table."/D11"] = "preal";






//////////
//	//
//  5	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ������� ��� ������� ���
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_krzdetitems";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "������� � ��������� ������������ ���";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|ID_krzdet|TID|PRICE|COUNT";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/ID_krzdet"] = "list";
		$db_cfg[$table."/ID_krzdet|LIST"] = "db_krzdet";
		$db_cfg[$table."/COUNT"] = "preal";
		$db_cfg[$table."/PRICE"] = "preal";
		$db_cfg[$table."/TID"] = "pinteger";





//////////
//	//
//  6	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ������� ���������� ���������
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_files_1_cat";

	$db_cfg[$table."|TYPE"] = "tree";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "������� ���������� ���������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|STATUS";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/STATUS"] = "boolean";




//////////
//	//
//  7	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ��������� ���������
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_files_1";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "��������� ���������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "EDIT_STATE";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME|KRZ";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME|KRZ";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|KRZ|ID_clients|ID_files_1_cat|FILENAME|MORE|EDIT_STATE";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/KRZ"] = "tinytext";
		$db_cfg[$table."/FILENAME"] = "file";
		$db_cfg[$table."/ID_clients"] = "list";
		$db_cfg[$table."/ID_clients|LIST"] = "db_clients";
		$db_cfg[$table."/ID_files_1_cat"] = "list";
		$db_cfg[$table."/ID_files_1_cat|LIST"] = "db_files_1_cat";
		$db_cfg[$table."/MORE"] = "tinytext";
		$db_cfg[$table."/EDIT_STATE"] = "state";
		$db_cfg[$table."/EDIT_STATE|LIST"] = "����.";
		$db_cfg[$table."/EDIT_STATE|HOLD"] = "NAME|KRZ|ID_clients|ID_files_1_cat|FILENAME|EDIT_STATE";






//////////
//	//
//  8	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ������� ���������� �����������
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_files_2_cat";

	$db_cfg[$table."|MORE"] = "������� ���������� �����������";
	$db_cfg[$table."|TYPE"] = "tree";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|STATUS";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/STATUS"] = "boolean";






//////////
//	//
//  9	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ��������� �����������
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_files_2";

	$db_cfg[$table."|MORE"] = "��������� �����������";
	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "ID_users";
	$db_cfg[$table."|CREATEDATE"] = "DATE";
	$db_cfg[$table."|HOLDBY"] = "EDIT_STATE";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME|TXT";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME|TXT";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|TXT|MORE|ID_users|ID_files_2_cat|FILENAME|DATE|EDIT_STATE";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/TXT"] = "tinytext";
		$db_cfg[$table."/FILENAME"] = "file";
		$db_cfg[$table."/ID_users"] = "list";
		$db_cfg[$table."/ID_users|LIST"] = "users";
		$db_cfg[$table."/ID_users|LIST_WHERE"] = "STATE='0'";
		$db_cfg[$table."/ID_files_2_cat"] = "list";
		$db_cfg[$table."/ID_files_2_cat|LIST"] = "db_files_2_cat";
		$db_cfg[$table."/MORE"] = "tinytext";
		$db_cfg[$table."/DATE"] = "date";
		$db_cfg[$table."/EDIT_STATE"] = "state";
		$db_cfg[$table."/EDIT_STATE|LIST"] = "����.";
		$db_cfg[$table."/EDIT_STATE|HOLD"] = "NAME|TXT|MORE|ID_users|ID_files_2_cat|FILENAME|DATE|EDIT_STATE";






//////////
//	//
//  10	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ��� (2� ����)
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_krz2";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "��� ������ ����";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "ID_users";
	$db_cfg[$table."|CREATEDATE"] = "DATE_START";
	$db_cfg[$table."|HOLDBY"] = "EDIT_STATE|EXPERT_STATE";
	$db_cfg[$table."|DELWITH"] = "db_krz2det/ID_krz2|db_arrival_plan/ID_krz2";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|ONCREATE"] = "add_db_krz2.php";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|ZAKNUM|ID_clients|ID_postavshik|ID_users|DATE_START|SERIYA|DATE_PLAN|DOCS|MORE|MORE2|PRICE|EXPERT|MORE_EXPERT|ID_krz|D1|D2|D3|D4|D5|D6|D7|D8|D9|D10|D11|D12|D13|D15|D16|D17|EXPERT_STATE|NORM_PRICE|S1|S2|EDIT_STATE";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/ZAKNUM"] = "tinytext";
		$db_cfg[$table."/ID_clients"] = "list";
		$db_cfg[$table."/ID_clients|LIST"] = "db_clients";
		$db_cfg[$table."/ID_postavshik"] = "alist";
		$db_cfg[$table."/ID_postavshik|LIST"] = "��� ������|��������";
		$db_cfg[$table."/ID_users"] = "list";
		$db_cfg[$table."/ID_users|LIST"] = "users";
		$db_cfg[$table."/DATE_START"] = "date";			// ���� ������� ���
		$db_cfg[$table."/DATE_PLAN"] = "tinytext";		// ����������� ����� ��������
		$db_cfg[$table."/PRICE"] = "preal";			// ���� ����� �� ������
		$db_cfg[$table."/EXPERT"] = "list";			// �������
		$db_cfg[$table."/EXPERT|LIST"] = "users";
		$db_cfg[$table."/MORE_EXPERT"] = "textarea";		// ���������� ��������
		$db_cfg[$table."/MORE"] = "textarea";			// ����������
		$db_cfg[$table."/MORE2"] = "textarea";			// ����������
		$db_cfg[$table."/ID_krz"] = "list";
		$db_cfg[$table."/ID_krz|LIST"] = "db_krz";
		$db_cfg[$table."/D1"] = "date";
		$db_cfg[$table."/D2"] = "date";
		$db_cfg[$table."/D3"] = "date";
		$db_cfg[$table."/D4"] = "date";
		$db_cfg[$table."/D5"] = "date";
		$db_cfg[$table."/D6"] = "date";
		$db_cfg[$table."/D7"] = "date";
		$db_cfg[$table."/D8"] = "date";
		$db_cfg[$table."/D9"] = "date";
		$db_cfg[$table."/D10"] = "date";
		$db_cfg[$table."/D11"] = "date";
		$db_cfg[$table."/D12"] = "date";
		$db_cfg[$table."/D13"] = "date";
		$db_cfg[$table."/D14"] = "date";
		$db_cfg[$table."/D15"] = "date";
		$db_cfg[$table."/D16"] = "date";
		$db_cfg[$table."/D17"] = "date";
		$db_cfg[$table."/EXPERT_STATE"] = "state";
		$db_cfg[$table."/EXPERT_STATE|LIST"] = "���������";
		$db_cfg[$table."/EXPERT_STATE|HOLD"] = "MORE_EXPERT|MORE";
		$db_cfg[$table."/EXPERT_STATE|USER"] = "EXPERT";
		$db_cfg[$table."/NORM_PRICE"] = "preal";
		$db_cfg[$table."/S1"] = "preal";
		$db_cfg[$table."/S2"] = "preal";
		$db_cfg[$table."/EDIT_STATE"] = "state";
		$db_cfg[$table."/EDIT_STATE|LIST"] = "HOLD";
		$db_cfg[$table."/EDIT_STATE|HOLD"] = $db_cfg[$table."|FIELDS"];







//////////
//	//
//  11	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ������ ��� ��� 2� ����
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_krz2det";

	$db_cfg[$table."|TYPE"] = "tree";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "������������ � ��� ������ ����";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "db_krz2det/PID|db_krz2detitems/ID_krz2det";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "ID_krz2";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|ID_krz2|OBOZ|COUNT|D1|D2|D3|D4|D5|D6|D7|D8|D9|D10|D11|VES";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/OBOZ"] = "tinytext";
		$db_cfg[$table."/ID_krz2"] = "list";
		$db_cfg[$table."/ID_krz2|LIST"] = "db_krz2";
		$db_cfg[$table."/COUNT"] = "pinteger";

		$db_cfg[$table."/VES"] = "preal";
		$db_cfg[$table."/D1"] = "preal";
		$db_cfg[$table."/D2"] = "preal";
		$db_cfg[$table."/D3"] = "preal";
		$db_cfg[$table."/D4"] = "preal";
		$db_cfg[$table."/D5"] = "preal";
		$db_cfg[$table."/D6"] = "preal";
		$db_cfg[$table."/D7"] = "preal";
		$db_cfg[$table."/D8"] = "preal";
		$db_cfg[$table."/D9"] = "preal";
		$db_cfg[$table."/D10"] = "preal";
		$db_cfg[$table."/D11"] = "preal";









//////////
//	//
//  12	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ������� ��� ��� 2� ����
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_krz2detitems";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "������� � ��������� ������������ ��� ������ ����";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|ID_krz2det|TID|PRICE|COUNT";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/ID_krz2det"] = "list";
		$db_cfg[$table."/ID_krz2det|LIST"] = "db_krz2det";
		$db_cfg[$table."/COUNT"] = "preal";
		$db_cfg[$table."/PRICE"] = "preal";
		$db_cfg[$table."/TID"] = "pinteger";









//////////
//	//
//  13	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ���� �������� �� ��� 2� ����
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_arrival_plan";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "���� �������� �� ��� ������ ����";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "PLAN";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "ID_krz2|PLAN|DATE|S1|S2";

		$db_cfg[$table."/ID_krz2"] = "list";
		$db_cfg[$table."/ID_krz2|LIST"] = "db_krz2";
		$db_cfg[$table."/PLAN"] = "preal";
		$db_cfg[$table."/DATE"] = "date";
		$db_cfg[$table."/S1"] = "preal";
		$db_cfg[$table."/S2"] = "preal";






//////////
//	//
//  14	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// �������� � ������������
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_files_3";

	$db_cfg[$table."|TYPE"] = "tree";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "�������� � ������������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "DATE";
	$db_cfg[$table."|HOLDBY"] = "EDIT_STATE";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME|OBOZ|ID_clients";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME|OBOZ";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|OBOZ|ID_clients|FILENAME|MORE|DATE|ARCH|EDIT_STATE";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/OBOZ"] = "tinytext";
		$db_cfg[$table."/FILENAME"] = "file";
		$db_cfg[$table."/ID_clients"] = "list";
		$db_cfg[$table."/ID_clients|LIST"] = "db_clients";
		$db_cfg[$table."/MORE"] = "tinytext";
		$db_cfg[$table."/DATE"] = "date";
		$db_cfg[$table."/ARCH"] = "state";
		$db_cfg[$table."/ARCH|LIST"] = "�����";
		$db_cfg[$table."/EDIT_STATE"] = "state";
		$db_cfg[$table."/EDIT_STATE|LIST"] = "����.";
		$db_cfg[$table."/EDIT_STATE|HOLD"] = "NAME|OBOZ|ID_clients|FILENAME";










//////////
//	//
//  15	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ������� ���
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_ktd_cat";

	$db_cfg[$table."|TYPE"] = "tree";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "������� ���";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME";

		$db_cfg[$table."/NAME"] = "tinytext";







//////////
//	//
//  16	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ������ ��� � ���
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_ktd_izd";

	$db_cfg[$table."|TYPE"] = "ltree";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "������ ��� � ���";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "LID";
	$db_cfg[$table."|DELWITH"] = "db_ktd_izd/PID";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "NAME|OBOZ";
	$db_cfg[$table."|LID_SEARCH"] = "NAME|OBOZ";

	$db_cfg[$table."|FIELDS"] = "NAME|ID_ktd_cat|OBOZ|COUNT";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/OBOZ"] = "tinytext";
		$db_cfg[$table."/ID_ktd_cat"] = "list";
		$db_cfg[$table."/ID_ktd_cat|LIST"] = "db_ktd_cat";
		$db_cfg[$table."/COUNT"] = "pinteger";
		$db_cfg[$table."/LID|HOLD"] = "NAME|OBOZ";







//////////
//	//
//  17	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ��������� (�����) ������������ � ��� � ���
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_ktd_files";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "��������� (�����) ������������ � ���";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME|OBOZ";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME|OBOZ";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "NAME|OBOZ";
	$db_cfg[$table."|LID_SEARCH"] = "NAME|OBOZ";

	$db_cfg[$table."|FIELDS"] = "NAME|OBOZ|DOC|ID_ktd_izd";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/OBOZ"] = "tinytext";
		$db_cfg[$table."/DOC"] = "file";
		$db_cfg[$table."/ID_ktd_izd"] = "list";
		$db_cfg[$table."/ID_ktd_izd|LIST"] = "db_ktd_izd";









//////////
//	//
//  18	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ������
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_zak";

	$db_cfg[$table."|TYPE"] = "tree";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "ID_users";
	$db_cfg[$table."|CREATEDATE"] = "CDATE";
	$db_cfg[$table."|HOLDBY"] = "EDIT_STATE";
	$db_cfg[$table."|HOLDDEL"] = "EDIT_STATE";
	$db_cfg[$table."|DELWITH"] = "db_zakdet/ID_zak";
	$db_cfg[$table."|ADDWITH"] = "db_zakdet/ID_zak";
	$db_cfg[$table."|ONCREATE"] = "add_db_zak.php";



	$db_cfg[$table."|LIST_FIELD"] = "TID|NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = " ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "DSE_NAME|DSE_OBOZ|DSE_COUNT|CDATE|NAME|ORD|TID|VIDRABOT|VIDDOG|DOGDATE|SPECDATE|DATE|IZVESH|MAT|PREDOPL|END_DATE|ED|NORM_PRICE|NORM_PRICE_FACT|IZD_CORR|INSZ|MORE|UPAKOVKA|CONTROL_STATE|PRIOR|INGANT|DATE_PLAN|INSTNUM|PD1|PD2|PD3|PD4|PD5|PD6|PD7|PD8|PD9|PD10|PD11|PD12|PD13|PD14|PD15|PD16|PD17|ID_postavshik|KUR|ID_users|ID_users2|ID_clients|ID_krz2|EDIT_STATE|ID_RASPNUM|ID_SOGL|ID_DOGOVOR|ID_SPECIF|ID_SCHET|ID_INVEST|SUMM_N|SUMM_NV|SUMM_NO|SUMM_V";

		$db_cfg[$table."/CDATE"] = "date";
		$db_cfg[$table."/ORD"] = "pinteger";
		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/TID"] = "alist";
		$db_cfg[$table."/TID|LIST"] = "��|��|��|��|��|��";
		$db_cfg[$table."/VIDRABOT"] = "textarea";
		$db_cfg[$table."/VIDDOG"] = "alist";
		$db_cfg[$table."/VIDDOG|LIST"] = "������� �������|������� ��������|������ ��������";
		$db_cfg[$table."/DOGDATE"] = "tinytext";
		$db_cfg[$table."/SPECDATE"] = "tinytext";
		$db_cfg[$table."/DATE"] = "date";
		$db_cfg[$table."/IZVESH"] = "tinytext";
		$db_cfg[$table."/MAT"] = "boolean";
		$db_cfg[$table."/PREDOPL"] = "boolean";
		$db_cfg[$table."/END_DATE"] = "date";
		$db_cfg[$table."/ED"] = "boolean";
		$db_cfg[$table."/NORM_PRICE"] = "preal";
		$db_cfg[$table."/NORM_PRICE_FACT"] = "preal";
		$db_cfg[$table."/IZD_CORR"] = "boolean";
		$db_cfg[$table."/INSZ"] = "boolean";
		$db_cfg[$table."/MORE"] = "tinytext";
		$db_cfg[$table."/UPAKOVKA"] = "tinytext";
		$db_cfg[$table."/CONTROL_STATE"] = "boolean";
		$db_cfg[$table."/PRIOR"] = "pinteger";
		$db_cfg[$table."/INGANT"] = "boolean";
		$db_cfg[$table."/DATE_PLAN"] = "date";
		$db_cfg[$table."/INSTNUM"] = "tinytext";
		$db_cfg[$table."/PD1"] = "dateplan";
		$db_cfg[$table."/PD2"] = "dateplan";
		$db_cfg[$table."/PD3"] = "dateplan";
		$db_cfg[$table."/PD4"] = "dateplan";
		$db_cfg[$table."/PD5"] = "dateplan";
		$db_cfg[$table."/PD6"] = "dateplan";
		$db_cfg[$table."/PD7"] = "dateplan";
		$db_cfg[$table."/PD8"] = "dateplan";
		$db_cfg[$table."/PD9"] = "dateplan";
		$db_cfg[$table."/PD10"] = "dateplan";
		$db_cfg[$table."/PD11"] = "dateplan";
		$db_cfg[$table."/PD12"] = "dateplan";
		$db_cfg[$table."/PD13"] = "dateplan";
		$db_cfg[$table."/PD14"] = "dateplan";
		$db_cfg[$table."/PD15"] = "date";
		$db_cfg[$table."/PD16"] = "date";
		$db_cfg[$table."/PD17"] = "date";
		$db_cfg[$table."/ID_postavshik"] = "alist";
		$db_cfg[$table."/ID_postavshik|LIST"] = "��� ������|��������";
		$db_cfg[$table."/EDIT_STATE"] = "state";
		$db_cfg[$table."/EDIT_STATE|LIST"] = "��������|�����������|�� ������";
		$db_cfg[$table."/EDIT_STATE|HOLD"] = "CDATE|NAME|ORD|TID|VIDRABOT|VIDDOG|DOGDATE|SPECDATE|DATE|IZVESH|MAT|PREDOPL|END_DATE|ED|NORM_PRICE|NORM_PRICE_FACT|IZD_CORR|INSZ|MORE|UPAKOVKA|CONTROL_STATE|PRIOR|INGANT|DATE_PLAN|INSTNUM|PD1|PD2|PD3|PD4|PD5|PD6|PD7|PD8|PD9|PD10|PD11|PD12|PD13|PD14|ID_postavshik|KUR|ID_users|ID_users2|ID_clients|ID_krz2|ID_RASPNUM|ID_SOGL|ID_DOGOVOR|ID_SPECIF|ID_SCHET|ID_INVEST|SUMM_N|SUMM_NV|SUMM_NO|SUMM_V";

	// �����
		$db_cfg[$table."/DSE_NAME"] = "tinytext";
		$db_cfg[$table."/DSE_OBOZ"] = "tinytext";
		$db_cfg[$table."/DSE_COUNT"] = "pinteger";

	// ����� � ��� ��� ��� ����
		$db_cfg[$table."/KUR"] = "list";
		$db_cfg[$table."/KUR|LIST"] = "users";
		$db_cfg[$table."/ID_users"] = "list";
		$db_cfg[$table."/ID_users|LIST"] = "users";
		$db_cfg[$table."/ID_users2"] = "list";
		$db_cfg[$table."/ID_users2|LIST"] = "users";
		$db_cfg[$table."/ID_clients"] = "list";
		$db_cfg[$table."/ID_clients|LIST"] = "db_clients";

	// ����� � ��� � �����������
		$db_cfg[$table."/ID_krz2"] = "list";
		$db_cfg[$table."/ID_krz2|LIST"] = "db_krz2";
		$db_cfg[$table."/ID_RASPNUM"] = "list";
		$db_cfg[$table."/ID_RASPNUM|LIST"] = "db_files_1";
		$db_cfg[$table."/ID_SOGL"] = "list";
		$db_cfg[$table."/ID_SOGL|LIST"] = "db_files_1";
		$db_cfg[$table."/ID_DOGOVOR"] = "list";
		$db_cfg[$table."/ID_DOGOVOR|LIST"] = "db_files_3";
		$db_cfg[$table."/ID_SPECIF"] = "list";
		$db_cfg[$table."/ID_SPECIF|LIST"] = "db_files_3";
		$db_cfg[$table."/ID_SCHET"] = "list";
		$db_cfg[$table."/ID_SCHET|LIST"] = "db_files_1";
		$db_cfg[$table."/ID_INVEST"] = "list";
		$db_cfg[$table."/ID_INVEST|LIST"] = "db_files_1";

	// �������� �����
		$db_cfg[$table."/SUMM_N"] = "preal";	// ����� �/�
		$db_cfg[$table."/SUMM_NV"] = "preal";	// ��������� �/�
		$db_cfg[$table."/SUMM_NO"] = "preal";	// �������� �/�
		$db_cfg[$table."/SUMM_V"] = "preal";	// ��������� %





//////////
//	//
//  19	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ��� � �������
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_zakdet";

	$db_cfg[$table."|TYPE"] = "ltree";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "��� � �������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "ID_zak";



	$db_cfg[$table."|LIST_FIELD"] = "NAME|OBOZ";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME|OBOZ";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "NAME|OBOZ";
	$db_cfg[$table."|LID_SEARCH"] = "NAME|OBOZ";
	$db_cfg[$table."|LID_MASTER"] = "ID_zak";

	$db_cfg[$table."|FIELDS"] = "NAME|ID_zak|OBOZ|COUNT|RCOUNT|ORD|TID|MTK_OK|NORM_OK|CONTROL_NUM|CLIENT|PRICE_PLAN|PRICE_FACT|WW|HH|LL|MASS|PERCENT|GANT_NP|GANT_NF|GANT_PS|GANT_PP";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/ID_zak"] = "list";
		$db_cfg[$table."/ID_zak|LIST"] = "db_zak";
		$db_cfg[$table."/OBOZ"] = "tinytext";
		$db_cfg[$table."/COUNT"] = "pinteger";
		$db_cfg[$table."/RCOUNT"] = "pinteger";
		$db_cfg[$table."/ORD"] = "pinteger";
		$db_cfg[$table."/TID"] = "alist";
		$db_cfg[$table."/TID|LIST"] = "��������|��������|�������|��. ������.";
		$db_cfg[$table."/MTK_OK"] = "boolean";
		$db_cfg[$table."/NORM_OK"] = "boolean";
		$db_cfg[$table."/CONTROL_NUM"] = "pinteger";
		$db_cfg[$table."/PRICE_PLAN"] = "preal";
		$db_cfg[$table."/PRICE_FACT"] = "preal";
		$db_cfg[$table."/CLIENT"] = "tinytext";
		$db_cfg[$table."/WW"] = "preal";
		$db_cfg[$table."/HH"] = "preal";
		$db_cfg[$table."/LL"] = "preal";
		$db_cfg[$table."/MASS"] = "preal";
		$db_cfg[$table."/PERCENT"] = "tinytext";
		$db_cfg[$table."/GANT_NP"] = "preal";
		$db_cfg[$table."/GANT_NF"] = "preal";
		$db_cfg[$table."/GANT_PS"] = "preal";
		$db_cfg[$table."/GANT_PP"] = "preal";





//////////
//	//
//  20	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// �� ������������ (��������� ����)
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_park";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "�� ������������ - ��������� ����";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "MARK";
	$db_cfg[$table."|LIST_SEARCH"] = "MARK|NAME";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "";
	$db_cfg[$table."|LID_SEARCH"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|SOST|MARK|MORE|TID";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/SOST"] = "alist";
		$db_cfg[$table."/SOST|LIST"] = "�������|������|�����������";
		$db_cfg[$table."/MARK"] = "tinytext";
		$db_cfg[$table."/MORE"] = "tinytext";
		$db_cfg[$table."/TID"] = "alist";
		$db_cfg[$table."/TID|LIST"] = "���������|������-������|���������������|������|��������������|��������|�������";





//////////
//	//
//  21	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ����������� ��������� ��������
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_oper";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "����������� ��������� ��������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "TID|NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "";
	$db_cfg[$table."|LID_SEARCH"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|TID|CODE|VID";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/CODE"] = "tinytext";
		$db_cfg[$table."/TID"] = "alist";
		$db_cfg[$table."/TID|LIST"] = "���������|������-������|���������������|������|��������������|��������|�������|������";
		$db_cfg[$table."/VID"] = "alist";
		$db_cfg[$table."/VID|LIST"] = "������� ��������|���������|��������|������������|��������������|�������|�������|������";





//////////
//	//
//  22	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ������ � �������
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_otdel";

	$db_cfg[$table."|TYPE"] = "tree";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "������ � ��. �����";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "";
	$db_cfg[$table."|LID_SEARCH"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|OBOZ|MORE|INSZ";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/OBOZ"] = "tinytext";
		$db_cfg[$table."/MORE"] = "tinytext";
		$db_cfg[$table."/INSZ"] = "boolean";





//////////
//	//
//  23	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// �������������
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_special";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "�������������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "";
	$db_cfg[$table."|LID_SEARCH"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|MORE";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/MORE"] = "tinytext";





//////////
//	//
//  24	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// �������
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_speclvl";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "������� ��� �������� ����������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "";
	$db_cfg[$table."|LID_SEARCH"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|MORE";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/MORE"] = "tinytext";





//////////
//	//
//  25	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ������� ����������
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_shtat";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "������� ����������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME|ID_special|ID_resurs";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "";
	$db_cfg[$table."|LID_SEARCH"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|ID_otdel|ID_special|ID_speclvl|ID_resurs|MORE|BOSS|NOTTAB";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/ID_otdel"] = "list";
		$db_cfg[$table."/ID_otdel|LIST"] = "db_otdel";
		$db_cfg[$table."/ID_special"] = "list";
		$db_cfg[$table."/ID_special|LIST"] = "db_special";
		$db_cfg[$table."/ID_special|ONCHANGE"] = "change_db_shtat_RESURS.php";
		$db_cfg[$table."/ID_speclvl"] = "list";
		$db_cfg[$table."/ID_speclvl|LIST"] = "db_speclvl";
		$db_cfg[$table."/ID_resurs"] = "list";
		$db_cfg[$table."/ID_resurs|LIST"] = "db_resurs";
		$db_cfg[$table."/ID_resurs|LIST_WHERE"] = "TID='0'";
		$db_cfg[$table."/ID_resurs|ONCHANGE"] = "change_db_shtat_RESURS.php";
		$db_cfg[$table."/MORE"] = "tinytext";
		$db_cfg[$table."/BOSS"] = "boolean";
		$db_cfg[$table."/NOTTAB"] = "boolean";





//////////
//	//
//  26	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// �������
//
///////////////////////////////////////////////////////////////////////////

$table = "db_resurs";

$db_cfg[$table."|TYPE"] = "line";
$db_cfg[$table."|ERP"] = "false";

$db_cfg[$table."|MORE"] = "�������";
$db_cfg[$table."|DELRIGHT"] = "";
$db_cfg[$table."|CREATEBY"] = "";
$db_cfg[$table."|CREATEDATE"] = "";
$db_cfg[$table."|HOLDBY"] = "";
$db_cfg[$table."|DELWITH"] = "";
$db_cfg[$table."|ADDWITH"] = "";
$db_cfg[$table."|BYPARENT"] = "";



$db_cfg[$table."|LIST_FIELD"] = "NAME";
$db_cfg[$table."|LIST_SEARCH"] = "NAME|FF|II|OO";
$db_cfg[$table."|LIST_PREFIX"] = " - ";
$db_cfg[$table."|ADDINDEX"] = "";
$db_cfg[$table."|LID_FIELD"] = "";
$db_cfg[$table."|LID_SEARCH"] = "";

$db_cfg[$table."|FIELDS"] = "DATE_FROM|DATE_TO|ID_JOB_TYPE|ID_special|NAME|TID|FF|II|OO|GENDER|PASPORT|ADR|FOTO|RAZMER|OPER_IDS|PARK_IDS|MORE|TEL|KADR|CHILDS|DATE|ID_tab|ID_users|DATE_LMO|DATE_NMO|ID_tab_st|EMAIL|KVALIF|ID_CARD|TIME_START|TIME_END|TIME_DELTA";

$db_cfg[$table."/ID_special"] = "integer";
$db_cfg[$table."/NAME"] = "tinytext";
$db_cfg[$table."/TID"] = "alist";
$db_cfg[$table."/TID|LIST"] = "������";
$db_cfg[$table."/TID|ONCHANGE"] = "resurs_TID.php";
$db_cfg[$table."/FF"] = "tinytext";
$db_cfg[$table."/FF|ONCHANGE"] = "resurs_FIO.php";
$db_cfg[$table."/II"] = "tinytext";
$db_cfg[$table."/II|ONCHANGE"] = "resurs_FIO.php";
$db_cfg[$table."/OO"] = "tinytext";
$db_cfg[$table."/OO|ONCHANGE"] = "resurs_FIO.php";
$db_cfg[$table."/PASPORT"] = "textarea";
$db_cfg[$table."/ADR"] = "textarea";
$db_cfg[$table."/FOTO"] = "text";
$db_cfg[$table."/RAZMER"] = "tinytext";
$db_cfg[$table."/OPER_IDS"] = "multilist";
$db_cfg[$table."/OPER_IDS|LIST"] = "db_oper";
$db_cfg[$table."/PARK_IDS"] = "multilist";
$db_cfg[$table."/PARK_IDS|LIST"] = "db_park";
$db_cfg[$table."/MORE"] = "tinytext";
$db_cfg[$table."/TEL"] = "textarea";
$db_cfg[$table."/KADR"] = "textarea";
$db_cfg[$table."/CHILDS"] = "textarea";
$db_cfg[$table."/DATE"] = "date";
$db_cfg[$table."/ID_tab"] = "list";
$db_cfg[$table."/ID_tab|LIST"] = "users";
$db_cfg[$table."/ID_users"] = "list";
$db_cfg[$table."/ID_users|LIST"] = "users";
$db_cfg[$table."/ID_users|ONCHANGE"] = "change_db_RESURS_check_akk.php";
$db_cfg[$table."/DATE_LMO"] = "date";
$db_cfg[$table."/DATE_NMO"] = "date";
$db_cfg[$table."/ID_tab_st"] = "droplist";
$db_cfg[$table."/ID_tab_st|LIST"] = "db_tab_st";
$db_cfg[$table."/ID_tab_st|ONCHANGE"] = "change_db_tab_st.php";
$db_cfg[$table."/EMAIL"] = "tinytext";
$db_cfg[$table."/KVALIF"] = "preal";
$db_cfg[$table."/ID_JOB_TYPE"] = "state";  // ��������� ��������� ���� ��� ��� ���������-�������� ������������
$db_cfg[$table."/ID_JOB_TYPE|LIST"] = "���������|���������";
$db_cfg[$table."/DATE_FROM"] = "date"; # ������
$db_cfg[$table."/DATE_TO"] = "date"; # ������
$db_cfg[$table."/ID_CARD"] = "integer"; # ����� ����� ���
$db_cfg[$table."/TIME_START"] = "tinytext"; # ����� ������ �������� ���
$db_cfg[$table."/TIME_END"] = "tinytext"; # ����� ��������� �������� ���
$db_cfg[$table."/TIME_DELTA"] = "integer"; # ������ �� ��������� (������ �� ����� �������)
$db_cfg[$table."/GENDER"] = "state";
$db_cfg[$table."/GENDER|LIST"] = "�������|�������";




//////////
//	//
//  27	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ���������������� ���������
//
////////////////////////////////////////////////////////////////////////////

	$table = "db_tab_pc";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "���������������� ���������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "db_tab_pci/ID_tab_pc";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "";
	$db_cfg[$table."|LID_SEARCH"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|MORE";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/MORE"] = "tinytext";





//////////
//	//
//  28	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// �������� ���������������� ����������
//
////////////////////////////////////////////////////////////////////////////

	$table = "db_tab_pci";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "�������� ���������������� ����������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "";
	$db_cfg[$table."|LID_SEARCH"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|HOURS|DATE|TID|ID_tab_pc";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/HOURS"] = "preal";
		$db_cfg[$table."/DATE"] = "date";
		$db_cfg[$table."/TID"] = "alist";
		$db_cfg[$table."/TID|LIST"] = "�|��";
		$db_cfg[$table."/ID_tab_pc"] = "list";
		$db_cfg[$table."/ID_tab_pc|LIST"] = "db_tab_pc";





//////////
//	//
//  29	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ����������� ������� �����
//
////////////////////////////////////////////////////////////////////////////

	$table = "db_tab_st";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "����������� ������� �����";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "";
	$db_cfg[$table."|LID_SEARCH"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|MORE|ID_tab_pc|CICL|FDATE|NSMEN|SCICL|WD1|WD2|WD3|TS1|TS2|TS3|TE1|TE2|TE3|PLAN1|PLAN2|PLAN3|SNCICL";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/MORE"] = "tinytext";
		$db_cfg[$table."/ID_tab_pc"] = "list";
		$db_cfg[$table."/ID_tab_pc|LIST"] = "db_tab_pc";
		$db_cfg[$table."/CICL"] = "integer";
		$db_cfg[$table."/FDATE"] = "date";
		$db_cfg[$table."/NSMEN"] = "alist";
		$db_cfg[$table."/NSMEN|LIST"] = "1 �����|2 ����� |3 �����";
		$db_cfg[$table."/SCICL"] = "alist";
		$db_cfg[$table."/SCICL|LIST"] = "�� ������ 1, 2, 3|������ ����� 1, 2, 3";
		$db_cfg[$table."/SNCICL"] = "alist";
		$db_cfg[$table."/SNCICL|LIST"] = "1, 2, 3|1, 3, 2";
		$db_cfg[$table."/WD1"] = "integer";
		$db_cfg[$table."/WD2"] = "integer";
		$db_cfg[$table."/WD3"] = "integer";
		$db_cfg[$table."/TS1"] = "tinytext";
		$db_cfg[$table."/TS2"] = "tinytext";
		$db_cfg[$table."/TS3"] = "tinytext";
		$db_cfg[$table."/TE1"] = "tinytext";
		$db_cfg[$table."/TE2"] = "tinytext";
		$db_cfg[$table."/TE3"] = "tinytext";
		$db_cfg[$table."/PLAN1"] = "preal";
		$db_cfg[$table."/PLAN2"] = "preal";
		$db_cfg[$table."/PLAN3"] = "preal";





//////////
//	//
//  30	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// �������� ����������� �������� ����� (�����)
//
////////////////////////////////////////////////////////////////////////////

	$table = "db_tab_sti";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "�������� ����������� �������� �����";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "";
	$db_cfg[$table."|LID_SEARCH"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|HOURS|DATE|TID|SMEN|ID_tab_st";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/HOURS"] = "preal";
		$db_cfg[$table."/DATE"] = "date";
		$db_cfg[$table."/TID"] = "alist";
		$db_cfg[$table."/TID|LIST"] = "�|��";
		$db_cfg[$table."/SMEN"] = "alist";
		$db_cfg[$table."/SMEN_LIST"] = "1|2|3";
		$db_cfg[$table."/ID_tab_st"] = "list";
		$db_cfg[$table."/ID_tab_st|LIST"] = "db_tab_st";





//////////
//	//
//  31	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ���
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_operitems";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "��� � ��� � �������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|ONCREATE"] = "add_db_operitems.php";
	$db_cfg[$table."|ONDELETE"] = "del_db_operitems.php";
	$db_cfg[$table."|EDITTIME"] = "ETIME";
	$db_cfg[$table."|EDITUSER"] = "ID_user";



	$db_cfg[$table."|LIST_FIELD"] = "ID_oper";
	$db_cfg[$table."|LIST_SEARCH"] = "ID_oper";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "";
	$db_cfg[$table."|LID_SEARCH"] = "";

	$db_cfg[$table."|FIELDS"] = "FACT2_ID_2|FACT2_NORM_ID|FACT2_NORM|FACT2_ID_1|FACT2_NUM_ID|FACT2_NUM|KSZ_ID|KSZ_ID_NUM|KSZ_NUM|KSZ2_ID|KSZ2_ID_NUM|KSZ2_NUM|MSG_INFO|ID_zak|ID_zakdet|ID_oper|ID_park|ORD|NORM|NORM_2|NORM_ZAK|NORM_FACT|NUM_ZAK|NUM_FACT|NUM_ZADEL|FACT|MORE|STATE|ID_user|ETIME|BRAK|BRAK_MORE|CHANCEL";

		$db_cfg[$table."/ID_zak"] = "list";
		$db_cfg[$table."/ID_zak|LIST"] = "db_zak";
		$db_cfg[$table."/ID_zakdet"] = "list";
		$db_cfg[$table."/ID_zakdet|LIST"] = "db_zakdet";
		$db_cfg[$table."/ID_oper"] = "droplist";
		$db_cfg[$table."/ID_oper|LIST"] = "db_oper";
		$db_cfg[$table."/ID_park"] = "droplist";
		$db_cfg[$table."/ID_park|LIST"] = "db_park";
		$db_cfg[$table."/ORD"] = "pinteger";
		$db_cfg[$table."/NORM"] = "preal";
		$db_cfg[$table."/NORM_2"] = "preal";
		$db_cfg[$table."/NORM_ZAK"] = "preal";
		$db_cfg[$table."/NORM_FACT"] = "preal";
		$db_cfg[$table."/NUM_ZAK"] = "pinteger";
		$db_cfg[$table."/NUM_FACT"] = "pinteger";
		$db_cfg[$table."/NUM_ZADEL"] = "pinteger";
		$db_cfg[$table."/FACT"] = "preal";
		$db_cfg[$table."/MORE"] = "textarea";
		$db_cfg[$table."/STATE"] = "boolean";
		$db_cfg[$table."/ETIME"] = "time";
		$db_cfg[$table."/ID_user"] = "list";
		$db_cfg[$table."/ID_user|LIST"] = "users";
		$db_cfg[$table."/BRAK"] = "state";
		$db_cfg[$table."/BRAK|LIST"] = "����. �����";
		$db_cfg[$table."/BRAK_MORE"] = "tinytext";
		$db_cfg[$table."/CHANCEL"] = "state";
		$db_cfg[$table."/CHANCEL|LIST"] = "������";
		$db_cfg[$table."/CHANCEL|HOLD"] = $db_cfg[$table."|FIELDS"];
		$db_cfg[$table."/MSG_INFO"] = "tinytext";
		$db_cfg[$table."/KSZ_NUM"] = "tinytext";
		$db_cfg[$table."/KSZ_ID"] = "tinytext";
		$db_cfg[$table."/KSZ_ID_NUM"] = "tinytext";
		$db_cfg[$table."/KSZ2_NUM"] = "tinytext";
		$db_cfg[$table."/KSZ2_ID"] = "tinytext";
		$db_cfg[$table."/KSZ2_ID_NUM"] = "tinytext";
		$db_cfg[$table."/FACT2_NUM"] = "tinytext";
		$db_cfg[$table."/FACT2_NUM_ID"] = "tinytext";
		$db_cfg[$table."/FACT2_ID_1"] = "tinytext";
		$db_cfg[$table."/FACT2_NORM"] = "tinytext";
		$db_cfg[$table."/FACT2_NORM_ID"] = "tinytext";
		$db_cfg[$table."/FACT2_ID_2"] = "tinytext";





//////////
//	//
//  32	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// �������� ������� �������
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_zadan";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "�������� ������� �������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "EDIT_STATE";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|ONCREATE"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "ID_oper";
	$db_cfg[$table."|LIST_SEARCH"] = "ID_oper";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "";
	$db_cfg[$table."|LID_SEARCH"] = "";

	$db_cfg[$table."|FIELDS"] = "MULT_SEL|INICIATOR|ORD|SMEN|DATE|ID_zak|ID_zakdet|ID_operitems|ID_resurs|ID_park|NORM|NORM_FACT|FACT|NUM|NUM_FACT|CEH1|CEH2|MORE|OKDATE|SPEC|EDIT_STATE|ID_zadanrcp";

		$db_cfg[$table."/ORD"] = "pinteger";
		$db_cfg[$table."/SMEN"] = "alist";
		$db_cfg[$table."/SMEN|LIST"] = "1|2|3";
		$db_cfg[$table."/DATE"] = "date";
		$db_cfg[$table."/ID_zak"] = "list";
		$db_cfg[$table."/ID_zak|LIST"] = "db_zak";
		$db_cfg[$table."/ID_zakdet"] = "list";
		$db_cfg[$table."/ID_zakdet|LIST"] = "db_zakdet";
		$db_cfg[$table."/ID_operitems"] = "list";
		$db_cfg[$table."/ID_operitems|LIST"] = "db_operitems";
		$db_cfg[$table."/ID_resurs"] = "list";
		$db_cfg[$table."/ID_resurs|LIST"] = "db_resurs";
		$db_cfg[$table."/ID_park"] = "list";
		$db_cfg[$table."/ID_park|LIST"] = "db_park";
		$db_cfg[$table."/NORM"] = "preal";
		$db_cfg[$table."/NORM|ONCHANGE"] = "calc_zak_oper_norm.php";
		$db_cfg[$table."/NORM_FACT"] = "preal";
		$db_cfg[$table."/FACT"] = "preal";
		$db_cfg[$table."/NUM"] = "pinteger";
		$db_cfg[$table."/NUM|ONCHANGE"] = "calc_zak_oper_num.php";
		$db_cfg[$table."/NUM_FACT"] = "pinteger";
		$db_cfg[$table."/CEH1"] = "tinytext";
		$db_cfg[$table."/CEH2"] = "tinytext";
		$db_cfg[$table."/MORE"] = "text";
		$db_cfg[$table."/OKDATE"] = "pinteger";
		$db_cfg[$table."/MULT_SEL"] = "integer";
		$db_cfg[$table."/SPEC"] = "boolean";			// ���� �������
		$db_cfg[$table."/EDIT_STATE"] = "state";
		$db_cfg[$table."/EDIT_STATE|LIST"] = "����.";
		$db_cfg[$table."/EDIT_STATE|HOLD"] = $db_cfg[$table."|FIELDS"];
		$db_cfg[$table."/ID_zadanrcp"] = "droplist";
		$db_cfg[$table."/ID_zadanrcp|LIST"] = "db_zadanrcp";
		$db_cfg[$table."/INICIATOR"] = "list";
		$db_cfg[$table."/INICIATOR|LIST"] = "db_resurs";
		$db_cfg[$table."/INICIATOR|LIST_WHERE"] = "TID='0'";







//////////
//	//
//  33	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ������� ������������ ��
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_zadanrcp";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "������� ������������ ��";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|ONCREATE"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "ID|NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "";
	$db_cfg[$table."|LID_SEARCH"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|MORE";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/MORE"] = "tinytext";








//////////
//	//
//  34	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ������
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_tabel";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|ONCREATE"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "TID|SMEN";
	$db_cfg[$table."|LIST_SEARCH"] = "TID|SMEN";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "";
	$db_cfg[$table."|LID_SEARCH"] = "";

	$db_cfg[$table."|FIELDS"] = "DATE|SMEN|TID|ID_resurs|NFACT|FACT|SPEC|PLAN|OPOZD|NOTTAB";

		$db_cfg[$table."/DATE"] = "date";
		$db_cfg[$table."/SMEN"] = "alist";
		$db_cfg[$table."/SMEN|LIST"] = "1|2|3";
		$db_cfg[$table."/ID_resurs"] = "list";
		$db_cfg[$table."/ID_resurs|LIST"] = "db_resurs";
		$db_cfg[$table."/TID"] = "alist";
		$db_cfg[$table."/TID|LIST"] = "��|��|�|�|��|��|�|��|��|K|��|�|��|��|��|��| ";
		$db_cfg[$table."/PLAN"] = "preal";
		$db_cfg[$table."/FACT"] = "preal";
		$db_cfg[$table."/SPEC"] = "preal";
		$db_cfg[$table."/NFACT"] = "preal";
		$db_cfg[$table."/OPOZD"] = "pinteger";
		$db_cfg[$table."/NOTTAB"] = "pinteger";








//////////
//	//
//  35	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ������� ������� �������
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_zadanres";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "������� ������� ������� �� ����";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|ONCREATE"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";
	$db_cfg[$table."|LID_FIELD"] = "";
	$db_cfg[$table."|LID_SEARCH"] = "";

	$db_cfg[$table."|FIELDS"] = "ORD|SMEN|DATE|ID_resurs";

		$db_cfg[$table."/ORD"] = "pinteger";
		$db_cfg[$table."/SMEN"] = "alist";
		$db_cfg[$table."/SMEN|LIST"] = "1|2|3";
		$db_cfg[$table."/DATE"] = "date";
		$db_cfg[$table."/ID_resurs"] = "list";
		$db_cfg[$table."/ID_resurs|LIST"] = "db_resurs";








//////////
//	//
//  36	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ������� ����������
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_mat_cat";

	$db_cfg[$table."|TYPE"] = "tree";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "������� ����������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|MORE";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/MORE"] = "tinytext";








//////////
//	//
//  37	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ������� ����������
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_sort_cat";

	$db_cfg[$table."|TYPE"] = "tree";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "������� ����������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|MORE";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/MORE"] = "tinytext";








//////////
//	//
//  38	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// �� ����������
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_mat";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "�� ����������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "OBOZ";
	$db_cfg[$table."|LIST_SEARCH"] = "OBOZ|NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|OBOZ|GOST|COEF|ID_mat_cat";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/OBOZ"] = "tinytext";
		$db_cfg[$table."/GOST"] = "tinytext";
		$db_cfg[$table."/COEF"] = "preal";
		$db_cfg[$table."/ID_mat_cat"] = "list";
		$db_cfg[$table."/ID_mat_cat|LIST"] = "db_mat_cat";








//////////
//	//
//  39	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// �� ����������
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_sort";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "�� ����������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "OBOZ|GOST";
	$db_cfg[$table."|LIST_SEARCH"] = "OBOZ|NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|OBOZ|GOST|MASS|MT|ORD|ID_sort_cat";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/OBOZ"] = "tinytext";
		$db_cfg[$table."/GOST"] = "tinytext";
		$db_cfg[$table."/MASS"] = "preal";
		$db_cfg[$table."/MT"] = "alist";
		$db_cfg[$table."/MT|LIST"] = "��/�|��/�2|�3/�|��/�2 - S";
		$db_cfg[$table."/ORD"] = "tinytext";
		$db_cfg[$table."/ID_sort_cat"] = "list";
		$db_cfg[$table."/ID_sort_cat|LIST"] = "db_sort_cat";








//////////
//	//
//  40	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ��������� ��������� � �������
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_zn_zag";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "��������� ��������� � �������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "ID_user";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|EDITTIME"] = "ETIME";
	$db_cfg[$table."|EDITUSER"] = "ID_user";



	$db_cfg[$table."|LIST_FIELD"] = "ID_mat|ID_sort";
	$db_cfg[$table."|LIST_SEARCH"] = "ID_mat|ID_sort";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "ID_zakdet|ID_mat|ID_sort|WW|HH|LL|RCOEF|KDZ|MORE|NORM|NORMZAK|RCOUNT|ID_user|ETIME";

		$db_cfg[$table."/ID_zakdet"] = "list";
		$db_cfg[$table."/ID_zakdet|LIST"] = "db_zakdet";
		$db_cfg[$table."/ID_mat"] = "list";
		$db_cfg[$table."/ID_mat|LIST"] = "db_mat";
		$db_cfg[$table."/ID_sort"] = "list";
		$db_cfg[$table."/ID_sort|LIST"] = "db_sort";
		$db_cfg[$table."/WW"] = "preal";
		$db_cfg[$table."/HH"] = "preal";
		$db_cfg[$table."/LL"] = "preal";
		$db_cfg[$table."/RCOEF"] = "preal";
		$db_cfg[$table."/KDZ"] = "pinteger";
		$db_cfg[$table."/MORE"] = "tinytext";
		$db_cfg[$table."/NORM"] = "preal";
		$db_cfg[$table."/NORMZAK"] = "preal";
		$db_cfg[$table."/RCOUNT"] = "pinteger";
		$db_cfg[$table."/ETIME"] = "time";
		$db_cfg[$table."/ID_user"] = "list";
		$db_cfg[$table."/ID_user|LIST"] = "users";








//////////
//	//
//  41	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ��������� ������� � ������� � �������
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_zn_pok";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "��������� ������� � ������� � �������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "ID_user";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|EDITTIME"] = "ETIME";
	$db_cfg[$table."|EDITUSER"] = "ID_user";



	$db_cfg[$table."|LIST_FIELD"] = "ID_mat";
	$db_cfg[$table."|LIST_SEARCH"] = "ID_mat";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "ID_zakdet|ID_mat|WW|HH|LL|KDZ|MORE|NORM|NORMZAK|RCOUNT|ID_user|ETIME";

		$db_cfg[$table."/ID_zakdet"] = "list";
		$db_cfg[$table."/ID_zakdet|LIST"] = "db_zakdet";
		$db_cfg[$table."/ID_mat"] = "list";
		$db_cfg[$table."/ID_mat|LIST"] = "db_mat";
		$db_cfg[$table."/WW"] = "preal";
		$db_cfg[$table."/HH"] = "preal";
		$db_cfg[$table."/LL"] = "preal";
		$db_cfg[$table."/KDZ"] = "pinteger";
		$db_cfg[$table."/MORE"] = "tinytext";
		$db_cfg[$table."/NORM"] = "preal";
		$db_cfg[$table."/NORMZAK"] = "preal";
		$db_cfg[$table."/RCOUNT"] = "pinteger";
		$db_cfg[$table."/ETIME"] = "time";
		$db_cfg[$table."/ID_user"] = "list";
		$db_cfg[$table."/ID_user|LIST"] = "users";








//////////
//	//
//  42	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ��������� ����������� � �������
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_zn_instr";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "��������� ����������� � �������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "ID_user";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|EDITTIME"] = "ETIME";
	$db_cfg[$table."|EDITUSER"] = "ID_user";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "ID_zakdet|NAME|COUNT|MORE|ID_user|ETIME";

		$db_cfg[$table."/ID_zakdet"] = "list";
		$db_cfg[$table."/ID_zakdet|LIST"] = "db_zakdet";
		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/COUNT"] = "pinteger";
		$db_cfg[$table."/MORE"] = "tinytext";
		$db_cfg[$table."/ETIME"] = "time";
		$db_cfg[$table."/ID_user"] = "list";
		$db_cfg[$table."/ID_user|LIST"] = "users";








//////////
//	//
//  43	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ��. ����
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_urface";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "��. ����";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "OBOZ";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME|OBOZ";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "OBOZ|NAME|MORE|ADDR|ADDRP|INN|KPP|OGRN|REGDATE|OKVED|OKPO";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/OBOZ"] = "tinytext";
		$db_cfg[$table."/MORE"] = "tinytext";
		$db_cfg[$table."/ADDR"] = "textarea";
		$db_cfg[$table."/ADDRP"] = "textarea";
		$db_cfg[$table."/INN"] = "tinytext";
		$db_cfg[$table."/KPP"] = "tinytext";
		$db_cfg[$table."/OGRN"] = "tinytext";
		$db_cfg[$table."/REGDATE"] = "date";
		$db_cfg[$table."/OKVED"] = "tinytext";
		$db_cfg[$table."/OKPO"] = "tinytext";

















//////////
//	//
//  44	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// �������� ��. ���
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_contacts";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "�������� ��. ���";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "SPECIAL";
	$db_cfg[$table."|LIST_FIELD2"] = "FIO";
	$db_cfg[$table."|LIST_SEARCH"] = "SPECIAL|FIO";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "ID_SPECIAL|ID_resurs|ID_urface|ID_shtat|SPECIAL|FIO|TEL|EMAIL";

		$db_cfg[$table."/ID_urface"] = "list";
		$db_cfg[$table."/ID_urface|LIST"] = "db_urface";
		$db_cfg[$table."/ID_resurs"] = "list";
		$db_cfg[$table."/ID_resurs|LIST"] = "db_resurs";
		$db_cfg[$table."/ID_resurs|LIST_EQUAL"] = "ID_special|ID_SPECIAL";
		$db_cfg[$table."/ID_resurs|ONCHANGE"] = "change_db_contacts.php";
		$db_cfg[$table."/ID_SPECIAL"] = "list";
		$db_cfg[$table."/ID_SPECIAL|LIST"] = "db_special";
		$db_cfg[$table."/ID_SPECIAL|ONCHANGE"] = "change_db_contacts.php";
		$db_cfg[$table."/ID_shtat"] = "list";
		$db_cfg[$table."/ID_shtat|LIST"] = "db_shtat";
		$db_cfg[$table."/ID_shtat|LIST_EQUAL"] = "ID_special|ID_SPECIAL";
		$db_cfg[$table."/ID_shtat|ONCHANGE"] = "change_db_contacts.php";
		$db_cfg[$table."/SPECIAL"] = "tinytext";
		$db_cfg[$table."/FIO"] = "tinytext";
		$db_cfg[$table."/TEL"] = "tinytext";
		$db_cfg[$table."/EMAIL"] = "tinytext";










//////////
//	//
//  45	//
//	//
////////////////////////////////////////////////////////////////////////////
//
// ������ � IT �����
//
////////////////////////////////////////////////////////////////////////////

	$table = "db_it_req";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "������ � IT �����";
	$db_cfg[$table."|DELRIGHT"] = "ID_users";
	$db_cfg[$table."|CREATEBY"] = "ID_users";
	$db_cfg[$table."|CREATEDATE"] = "DATE";
	$db_cfg[$table."|HOLDBY"] = "SOGL|EDIT_STATE";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|ONCREATE"] = "";		// ����� = ID

	$db_cfg[$table."|LIST_FIELD"] = "QWEST|ID_users";
	$db_cfg[$table."|LIST_SEARCH"] = "QWEST";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";


	$db_cfg[$table."|FIELDS"] = "DATE_PLAN|NAME|QWEST|SOGL|ID_users|DATE|OTCHET|SOGL_USER|EDIT_STATE";

		$db_cfg[$table."/NAME"] = "tinytext";		// � ������
		$db_cfg[$table."/QWEST"] = "textarea";
		$db_cfg[$table."/ID_users"] = "list";
		$db_cfg[$table."/ID_users|LIST"] = "users";
		$db_cfg[$table."/DATE"] = "date";
		$db_cfg[$table."/SOGL"] = "state";		// ���� / �� ����
		$db_cfg[$table."/SOGL|LIST"] = "����.|����.";
		$db_cfg[$table."/SOGL|HOLD"] = "DATE_PLAN|NAME|QWEST|ID_users|DATE";
		$db_cfg[$table."/SOGL|USER"] = "SOGL_USER";	// �������� ID ������������ ����������� ������
		$db_cfg[$table."/DATE_PLAN"] = "date";
		$db_cfg[$table."/OTCHET"] = "textarea";
		$db_cfg[$table."/SOGL_USER"] = "list";
		$db_cfg[$table."/SOGL_USER|LIST"] = "users";
		$db_cfg[$table."/EDIT_STATE"] = "state";	// ��������� / �� ���������
		$db_cfg[$table."/EDIT_STATE|LIST"] = "���.";
		$db_cfg[$table."/EDIT_STATE|HOLD"] = "DATE_PLAN|NAME|QWEST|SOGL|ID_users|DATE|SOGL_USER|OTCHET";







//////////
//	//
//  45	//
//	//
////////////////////////////////////////////////////////////////////////////
//
// ������ � ����� ������
//
////////////////////////////////////////////////////////////////////////////

	$table = "db_hr_req";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "������ � ����� ������";
	$db_cfg[$table."|DELRIGHT"] = "ID_users";
	$db_cfg[$table."|CREATEBY"] = "ID_users";
	$db_cfg[$table."|CREATEDATE"] = "DATE";
	$db_cfg[$table."|HOLDBY"] = "SOGL|EDIT_STATE";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|ONCREATE"] = "";		// ����� = ID

	$db_cfg[$table."|LIST_FIELD"] = "QWEST|ID_users";
	$db_cfg[$table."|LIST_SEARCH"] = "QWEST";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|ONCREATE"] = "add_db_hr_req.php";

	$db_cfg[$table."|FIELDS"] = "NAME|QWEST|SOGL|ID_users|DATE|OTCHET|COUNT|SOGL_USER|EDIT_STATE|EDUCATION|POSITION|EXPERIENCE|FUNCTION|COMMENT_OK";

		$db_cfg[$table."/NAME"] = "tinytext";		// � ������
		$db_cfg[$table."/QWEST"] = "textarea";
		$db_cfg[$table."/ID_users"] = "list";
		$db_cfg[$table."/ID_users|LIST"] = "users";
		$db_cfg[$table."/DATE"] = "date";
		$db_cfg[$table."/SOGL"] = "state";		// ���� / �� ����
		$db_cfg[$table."/SOGL|LIST"] = "����.|����.";
		$db_cfg[$table."/SOGL|HOLD"] = "DATE_PLAN|NAME|QWEST|ID_users|DATE";
		$db_cfg[$table."/SOGL|USER"] = "SOGL_USER";	// �������� ID ������������ ����������� ������
		$db_cfg[$table."/DATE_PLAN"] = "date";
		$db_cfg[$table."/OTCHET"] = "textarea";
		$db_cfg[$table."/COMMENT_OK"] = "textarea";
		$db_cfg[$table."/SOGL_USER"] = "list";
		
		$db_cfg[$table."/QWEST|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/GENDER"] = "alist";		// ����������
		$db_cfg[$table."/GENDER|LIST"] = "�����|�������|�������";
		$db_cfg[$table."/GENDER|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/COUNT|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/FUNCTION|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/AGE|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/EXPERIENCE|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/POSITION|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/EDUCATION|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/SALARY|EDITRIGHT"] = "ID_users";
		 
		$db_cfg[$table."/QWEST"] = "list";		// �����
		$db_cfg[$table."/QWEST|LIST"] = "db_otdel";
		
		$db_cfg[$table."/SOGL_USER|LIST"] = "users";
		$db_cfg[$table."/COUNT"] = "tinytext";
		$db_cfg[$table."/FUNCTION"] = "tinytext";
		$db_cfg[$table."/AGE"] = "tinytext";
		$db_cfg[$table."/SALARY"] = "tinytext";
		$db_cfg[$table."/EXPERIENCE"] = "tinytext";
		$db_cfg[$table."/POSITION"] = "tinytext";
		$db_cfg[$table."/EDUCATION"] = "tinytext";
		$db_cfg[$table."/EDIT_STATE"] = "state";	// ��������� / �� ���������
		$db_cfg[$table."/EDIT_STATE|LIST"] = "���.";
		$db_cfg[$table."/EDIT_STATE|HOLD"] = "DATE_PLAN|NAME|QWEST|SOGL|ID_users|DATE|SOGL_USER|OTCHET|EDUCATION|POSITION|EXPERIENCE|FUNCTION";







//////////
//	//
//  46	//
//	//
////////////////////////////////////////////////////////////////////////////
//
// ������ � ���
//
////////////////////////////////////////////////////////////////////////////

	$table = "db_ogi_req";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "������ � ���";
	$db_cfg[$table."|DELRIGHT"] = "ID_users";
	$db_cfg[$table."|CREATEBY"] = "ID_users";
	$db_cfg[$table."|CREATEDATE"] = "DATE";
	$db_cfg[$table."|EDITTIME"] = "ETIME";
	$db_cfg[$table."|HOLDBY"] = "SOGL|EDIT_STATE";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|ONCREATE"] = "";		// ����� = ID

	$db_cfg[$table."|LIST_FIELD"] = "QWEST|ID_users";
	$db_cfg[$table."|LIST_SEARCH"] = "QWEST";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";



	$db_cfg[$table."|FIELDS"] = "DATE_PLAN|NAME|QWEST|SOGL|ID_users|DATE|ETIME|OTCHET|SOGL_USER|EDIT_STATE";

		$db_cfg[$table."/NAME"] = "tinytext";		// � ������
		$db_cfg[$table."/QWEST"] = "textarea";		// ������
		$db_cfg[$table."/QWEST|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/ID_users"] = "list";		// ���������
		$db_cfg[$table."/ID_users|LIST"] = "users";
		$db_cfg[$table."/ETIME"] = "time";
		$db_cfg[$table."/DATE"] = "date";
		$db_cfg[$table."/SOGL"] = "state";		// ���� / �� ����
		$db_cfg[$table."/SOGL|LIST"] = "����.|����.";
		$db_cfg[$table."/SOGL|HOLD"] = "DATE_PLAN|NAME|QWEST|ID_users|DATE";
		$db_cfg[$table."/SOGL|USER"] = "SOGL_USER";	// �������� ID ������������ ����������� ������
		$db_cfg[$table."/DATE_PLAN"] = "date";
		$db_cfg[$table."/OTCHET"] = "textarea";
		$db_cfg[$table."/SOGL_USER"] = "list";
		$db_cfg[$table."/SOGL_USER|LIST"] = "users";
		$db_cfg[$table."/EDIT_STATE"] = "state";	// ��������� / �� ���������
		$db_cfg[$table."/EDIT_STATE|LIST"] = "���.";
		$db_cfg[$table."/EDIT_STATE|HOLD"] = "DATE_PLAN|NAME|QWEST|SOGL|ID_users|DATE|OTCHET|SOGL_USER";








//////////
//	//
//  47	//
//	//
////////////////////////////////////////////////////////////////////////////
//
// ������ �� ���
//
////////////////////////////////////////////////////////////////////////////

	$table = "db_tmc_req";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "������ �� ���";
	$db_cfg[$table."|DELRIGHT"] = "ID_users";
	$db_cfg[$table."|CREATEBY"] = "ID_users";
	$db_cfg[$table."|CREATEDATE"] = "CDATE";
	$db_cfg[$table."|HOLDBY"] = "STATE|SOGL1|SOGL2";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|ONCREATE"] = "add_db_tmc_req.php";		// ����������� �����

	$db_cfg[$table."|LIST_FIELD"] = "TXT|ID_users";
	$db_cfg[$table."|LIST_SEARCH"] = "TXT";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";


	$db_cfg[$table."|FIELDS"] = "NAME|CDATE|TXT|COUNT|EDIZM|DATE|DATEPLAN|ID_users|NAZN|ID_zak|SOGL1|SOGLDATE1|SOGL2|SOGLDATE2|MORE|SOGLUSER1|SOGLUSER2|STATE";

		$db_cfg[$table."/NAME"] = "tinytext";		// � ������
		$db_cfg[$table."/CDATE"] = "date";		// ���� ��������
		$db_cfg[$table."/TXT"] = "tinytext";		// ������������
		$db_cfg[$table."/TXT|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/COUNT"] = "pinteger";		// ����������
		$db_cfg[$table."/COUNT|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/EDIZM"] = "tinytext";		// ��. ���������
		$db_cfg[$table."/EDIZM|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/DATE"] = "date";		// ��������� ����
		$db_cfg[$table."/DATE|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/DATEPLAN"] = "dateplan";	// �������� ���� ����������
		$db_cfg[$table."/ID_users"] = "list";		// ���������
		$db_cfg[$table."/ID_users|LIST"] = "users";
		$db_cfg[$table."/NAZN"] = "alist";		// ����������
		$db_cfg[$table."/NAZN|LIST"] = "���. �������|������ ��|����������|������|������������|����������|���|�����|�����������|�������";
		$db_cfg[$table."/NAZN|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/ID_zak"] = "list";		// �����
		$db_cfg[$table."/ID_zak|LIST"] = "db_zak";
		$db_cfg[$table."/SOGL1"] = "state";
		$db_cfg[$table."/SOGL1|LIST"] = "����.|����.";
		$db_cfg[$table."/SOGL1|HOLD"] = "NAME|CDATE|TXT|COUNT|EDIZM|DATE|ID_users|NAZN|ID_zak";
		$db_cfg[$table."/SOGL1|USER"] = "SOGLUSER1";	// �������� ID ������������ ����������� ������
		$db_cfg[$table."/SOGL1|DATE"] = "SOGLDATE1";	// �������� ���� ��������� �������
		$db_cfg[$table."/SOGLUSER1"] = "list";
		$db_cfg[$table."/SOGLUSER1|LIST"] = "users";
		$db_cfg[$table."/SOGLDATE1"] = "date";
		$db_cfg[$table."/SOGL2"] = "state";
		$db_cfg[$table."/SOGL2|LIST"] = "����.|����.";
		$db_cfg[$table."/SOGL2|HOLD"] = "NAME|CDATE|TXT|COUNT|EDIZM|DATE|ID_users|NAZN|ID_zak";
		$db_cfg[$table."/SOGL2|USER"] = "SOGLUSER2";	// �������� ID ������������ ����������� ������
		$db_cfg[$table."/SOGL2|DATE"] = "SOGLDATE2";	// �������� ���� ��������� �������
		$db_cfg[$table."/SOGLUSER2"] = "list";
		$db_cfg[$table."/SOGLUSER2|LIST"] = "users";
		$db_cfg[$table."/SOGLDATE2"] = "date";
		$db_cfg[$table."/MORE"] = "textarea";		// ����������
		$db_cfg[$table."/STATE"] = "state";		// ��������� / �� ���������
		$db_cfg[$table."/STATE|LIST"] = "������.|�����.";
		$db_cfg[$table."/STATE|HOLD"] = "NAME|CDATE|TXT|COUNT|EDIZM|DATE|DATEPLAN|ID_users|NAZN|ID_zak|SOGL1|SOGLDATE1|SOGL2|SOGLDATE2|MORE|SOGLUSER1|SOGLUSER2";








//////////
//	//
//  48	//
//	//
////////////////////////////////////////////////////////////////////////////
//
// ������ �� ������
//
////////////////////////////////////////////////////////////////////////////

	$table = "db_zak_req";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "������ �� ������";
	$db_cfg[$table."|DELRIGHT"] = "ID_users";
	$db_cfg[$table."|CREATEBY"] = "ID_users";
	$db_cfg[$table."|CREATEDATE"] = "CDATE";
	$db_cfg[$table."|HOLDBY"] = "STATE|SOGL1|SOGL2";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|ONCREATE"] = "add_db_zak_req.php";		// ����������� �����

	$db_cfg[$table."|LIST_FIELD"] = "TXT|ID_users";
	$db_cfg[$table."|LIST_SEARCH"] = "TXT";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";


	$db_cfg[$table."|FIELDS"] = "KOMMENT|NAME|CDATE|TXT|COUNT|DATE|STATE|ID_users|NAZN|ID_zak|SOGL1|SOGLDATE1|SOGL2|SOGLDATE2|MORE|SOGLUSER1|SOGLUSER2";

		$db_cfg[$table."/KOMMENT"] = "textarea";			// � ������
		$db_cfg[$table."/NAME"] = "tinytext";			// � ������
		$db_cfg[$table."/CDATE"] = "date";			// ���� ��������
		$db_cfg[$table."/TXT"] = "tinytext";			// ������������
		$db_cfg[$table."/TXT|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/COUNT"] = "pinteger";			// ����������
		$db_cfg[$table."/COUNT|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/DATE"] = "date";			// ��������� ����
		$db_cfg[$table."/DATE|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/STATE"] = "state";			// ��������� / �� ���������
		$db_cfg[$table."/STATE|LIST"] = "������.|�����.";
		
		$db_cfg[$table."/EDIT_STATE|ONCHANGE"] = "zak_req_edit_state_change.php";
		$db_cfg[$table."/STATE|HOLD"] = "NAME|CDATE|TXT|COUNT|DATE|ID_users|NAZN|ID_zak|SOGL1|SOGLDATE1|SOGL2|SOGLDATE2|MORE|SOGLUSER1|SOGLUSER2";
		$db_cfg[$table."/ID_users"] = "list";			// ���������
		$db_cfg[$table."/ID_users|LIST"] = "users";
		$db_cfg[$table."/NAZN"] = "alist";			// ����������
		$db_cfg[$table."/NAZN|LIST"] = "�����|��������|�����|���. ������.|���. �����|������|��������";
		$db_cfg[$table."/NAZN|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/ID_zak"] = "list";			// �����
		$db_cfg[$table."/ID_zak|LIST"] = "db_zak";
		$db_cfg[$table."/ID_zak|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/SOGL1"] = "state";
		$db_cfg[$table."/SOGL1|LIST"] = "����.|����.";
		$db_cfg[$table."/SOGL1|HOLD"] = "NAME|CDATE|TXT|COUNT|DATE|ID_users|NAZN|ID_zak|MORE";
		$db_cfg[$table."/SOGL1|USER"] = "SOGLUSER1";		// �������� ID ������������ ����������� ������
		$db_cfg[$table."/SOGL1|DATE"] = "SOGLDATE1";		// �������� ���� ��������� �������
		$db_cfg[$table."/SOGLUSER1"] = "list";
		$db_cfg[$table."/SOGLUSER1|LIST"] = "users";
		$db_cfg[$table."/SOGLDATE1"] = "date";
		$db_cfg[$table."/SOGL2"] = "state";
		$db_cfg[$table."/SOGL2|LIST"] = "����.|����.";
		$db_cfg[$table."/SOGL2|HOLD"] = "NAME|CDATE|TXT|COUNT|DATE|ID_users|NAZN|ID_zak|MORE";
		$db_cfg[$table."/SOGL2|USER"] = "SOGLUSER2";		// �������� ID ������������ ����������� ������
		$db_cfg[$table."/SOGL2|DATE"] = "SOGLDATE2";		// �������� ���� ��������� �������
		$db_cfg[$table."/SOGLUSER2"] = "list";
		$db_cfg[$table."/SOGLUSER2|LIST"] = "users";
		$db_cfg[$table."/SOGLDATE2"] = "date";
		$db_cfg[$table."/MORE"] = "textarea";			// ����������








//////////
//	//
//  49	//
//	//
////////////////////////////////////////////////////////////////////////////
//
// ������ �� ������ �� ����������
//

	$table = "db_koop_req";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "������ �� ������ �� ����������";
	$db_cfg[$table."|DELRIGHT"] = "ID_users";
	$db_cfg[$table."|CREATEBY"] = "ID_users";
	$db_cfg[$table."|CREATEDATE"] = "CDATE";
	$db_cfg[$table."|HOLDBY"] = "STATE|SOGL1|SOGL2|SOGL3";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|ONCREATE"] = "add_db_koop_req.php";		// ����������� �����

	$db_cfg[$table."|LIST_FIELD"] = "TXT|ID_users";
	$db_cfg[$table."|LIST_SEARCH"] = "TXT";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "SOGL3|REQ_TYPE|EFFECTN_ZERO|EFFECTN|CENA_FACT|STOIM_RAB|CENA_PLAN|PLAN_NCH|NAME|OBOZ|DIRECT|CDATE|TXT|COUNT|DATE|STATE|PLAN_NORM|ID_users|NAZN|ID_zak|SOGL1|SOGLDATE1|SOGL2|SOGLDATE2|MORE|SOGLUSER1|SOGLUSER2|VIDRABOT|ID_resurs|OPTIONS";

		$db_cfg[$table."/NAME"] = "tinytext";			// � ������ NNN.MM.YYYY
		$db_cfg[$table."/OBOZ"] = "tinytext";			// �����
		$db_cfg[$table."/OBOZ|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/DIRECT"] = "tinytext";			// ����� ������������ ��������
		$db_cfg[$table."/DIRECT|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/CDATE"] = "date";			// ���� ��������
		$db_cfg[$table."/TXT"] = "tinytext";			// ������������
		$db_cfg[$table."/TXT|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/COUNT"] = "pinteger";			// ����������
		$db_cfg[$table."/COUNT|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/DATE"] = "date";			// ��������� ����
		$db_cfg[$table."/DATE|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/STATE"] = "state";			// ��������� / �� ���������
		$db_cfg[$table."/STATE|LIST"] = "������.|�����.";
		$db_cfg[$table."/STATE|HOLD"] = "NAME|OBOZ|DIRECT|CDATE|TXT|COUNT|DATE|ID_users|NAZN|ID_zak|SOGL1|SOGLDATE1|SOGL2|SOGLDATE2|MORE|SOGLUSER1|SOGLUSER2|VIDRABOT|ID_resurs";
		$db_cfg[$table."/ID_users"] = "list";			// ���������
		$db_cfg[$table."/ID_users|LIST"] = "users";
		$db_cfg[$table."/NAZN"] = "alist";			// ����������
		$db_cfg[$table."/NAZN|LIST"] = "�����|��������|�����|���. ������.|���. �����|������";
		$db_cfg[$table."/NAZN|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/ID_zak"] = "list";			// �����
		$db_cfg[$table."/ID_zak|LIST"] = "db_zak";
		$db_cfg[$table."/ID_zak|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/SOGL1"] = "state";
		$db_cfg[$table."/SOGL1|LIST"] = "����.|����.";
		$db_cfg[$table."/REQ_TYPE"] = "state";
		$db_cfg[$table."/REQ_TYPE|LIST"] = "����������|� ������";
		$db_cfg[$table."/SOGL1|HOLD"] = "NAME|OBOZ|DIRECT|CDATE|TXT|COUNT|DATE|ID_users|NAZN|ID_zak|VIDRABOT|ID_resurs";
		$db_cfg[$table."/SOGL1|USER"] = "SOGLUSER1";		// �������� ID ������������ ����������� ������
		$db_cfg[$table."/SOGL1|DATE"] = "SOGLDATE1";		// �������� ���� ��������� �������
		$db_cfg[$table."/SOGLUSER1"] = "list";
		$db_cfg[$table."/SOGLUSER1|LIST"] = "users";
		$db_cfg[$table."/SOGLDATE1"] = "date";
		$db_cfg[$table."/SOGL2"] = "state";
		$db_cfg[$table."/SOGL2|LIST"] = "����.|����.";
		$db_cfg[$table."/SOGL2|HOLD"] = "NAME|OBOZ|DIRECT|CDATE|TXT|COUNT|DATE|ID_users|NAZN|ID_zak|VIDRABOT|ID_resurs";
		$db_cfg[$table."/SOGL2|USER"] = "SOGLUSER2";		// �������� ID ������������ ����������� ������
		$db_cfg[$table."/SOGL2|DATE"] = "SOGLDATE2";		// �������� ���� ��������� �������
		$db_cfg[$table."/SOGLUSER2"] = "list";
		$db_cfg[$table."/SOGLUSER2|LIST"] = "users";
		$db_cfg[$table."/SOGLDATE2"] = "date";
		$db_cfg[$table."/VIDRABOT"] = "textarea";		// ��� �����
		$db_cfg[$table."/VIDRABOT|EDITRIGHT"] = "ID_users";
		$db_cfg[$table."/MORE"] = "textarea";			// ���������� ���
		$db_cfg[$table."/ID_resurs"] = "list";			// ������������� �� ��������
		$db_cfg[$table."/ID_resurs|LIST"] = "db_resurs";
		$db_cfg[$table."/PLAN_NCH"] = "real";			// 
		$db_cfg[$table."/PLAN_NCH|EDITRIGHT"] = "ID_users";			// 
		$db_cfg[$table."/CENA_PLAN"] = "money";			// 
		$db_cfg[$table."/STOIM_RAB"] = "money";			//
		$db_cfg[$table."/CENA_FACT"] = "money";			//
		$db_cfg[$table."/EFFECTN"] = "money";			// 
		$db_cfg[$table."/EFFECTN_ZERO"] = "tinytext";	// 
		$db_cfg[$table."/PLAN_NORM"] = "tinytext";	// 
		$db_cfg[$table."/OPTIONS"] = "textarea";	// 
		$db_cfg[$table."/OPTIONS|EDITRIGHT"] = "ID_users";	
		$db_cfg[$table."/REQ_TYPE|EDITRIGHT"] = "ID_users";			// 

		$db_cfg[$table."/PLAN_NORM|EDITRIGHT"] = "ID_users";			// 
		$db_cfg[$table."/SOGL3"] = "state";
		$db_cfg[$table."/SOGL3|LIST"] = "����.";
		$db_cfg[$table."/SOGL3|HOLD"] = "PLAN_NCH|CENA_PLAN|STOIM_RAB|SOGL3";

		$db_cfg[$table."/RESP"] = "state";
		$db_cfg[$table."/RESP|LIST"] = "������������� �.�. &#9742; 1016 &#9993; ovk@okbmikron.ru |��������� �.�. &#9742; 1004 &#9993; kazachenko@okbmikron.ru|�������� �. �. &#9742; 1016 &#9993; bma@okbmikron.ru";



//////////
//	//
//  50	//
//	//
////////////////////////////////////////////////////////////////////////////
//
// ������ ����������� �� �����
//
////////////////////////////////////////////////////////////////////////////

	$table = "db_prog_req";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "������ ����������� �� �����";
	$db_cfg[$table."|DELRIGHT"] = "ID_users";
	$db_cfg[$table."|CREATEBY"] = "ID_users";
	$db_cfg[$table."|CREATEDATE"] = "DATE";
	$db_cfg[$table."|HOLDBY"] = "SOGL|EDIT_STATE";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|ONCREATE"] = "";		// ����� = ID

	$db_cfg[$table."|LIST_FIELD"] = "QWEST|ID_users";
	$db_cfg[$table."|LIST_SEARCH"] = "QWEST";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";


	$db_cfg[$table."|FIELDS"] = "QWEST|SOGL|ID_users|DATE|UNSW|OTCHET|SOGL_USER|EDIT_STATE";

		$db_cfg[$table."/QWEST"] = "textarea";
		$db_cfg[$table."/ID_users"] = "list";
		$db_cfg[$table."/ID_users|LIST"] = "users";
		$db_cfg[$table."/DATE"] = "date";
		$db_cfg[$table."/SOGL"] = "state";		// ���� / �� ����
		$db_cfg[$table."/SOGL|LIST"] = "����.|����.";
		$db_cfg[$table."/SOGL|HOLD"] = "NAME|QWEST|ID_users|DATE";
		$db_cfg[$table."/SOGL|USER"] = "SOGL_USER";	// �������� ID ������������ ����������� ������
		$db_cfg[$table."/UNSW"] = "textarea";		// ���������� ������������
		$db_cfg[$table."/OTCHET"] = "textarea";		// ����� ������������
		$db_cfg[$table."/SOGL_USER"] = "list";
		$db_cfg[$table."/SOGL_USER|LIST"] = "users";
		$db_cfg[$table."/EDIT_STATE"] = "state";	// ��������� / �� ���������
		$db_cfg[$table."/EDIT_STATE|LIST"] = "���.";
		$db_cfg[$table."/EDIT_STATE|HOLD"] = "NAME|QWEST|SOGL|ID_users|DATE|SOGL_USER|OTCHET";











//////////
//	//
//  51	//
//	//
////////////////////////////////////////////////////////////////////////////
//
// ������� ��� (�������� ��������������� ����������)
//
////////////////////////////////////////////////////////////////////////////

	$table = "db_itrzadan";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "������� ��� (�������� ��������������� ����������)";
	$db_cfg[$table."|DELRIGHT"] = "ID_users";
	$db_cfg[$table."|CREATEBY"] = "ID_users";
	$db_cfg[$table."|CREATEDATE"] = "CDATE";
	$db_cfg[$table."|CREATETIME"] = "CTIME";
	$db_cfg[$table."|EDITTIME"] = "ETIME";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";


	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "TXT|NAME|ID_users|ID_users2|ID_users3";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "TIME_PLAN|TIT_HEAD|TIP_JOB|TIP_FAIL|DOCISP|STARTTIME|STARTDATE|KOMM1|KOMM2|KOMM3|ID_zak|ID_users|ID_users2|ID_users3|CDATE|CTIME|TXT|ETIME|EUSER|DATE_PLAN|STATUS|ID_edo";

		$db_cfg[$table."/ID_users"] = "list";		// ���������
		$db_cfg[$table."/ID_users|LIST"] = "db_resurs";	//
		$db_cfg[$table."/ID_users2"] = "list";		// �������������
		$db_cfg[$table."/ID_users2|LIST"] = "db_resurs";	//
		$db_cfg[$table."/ID_users3"] = "list";		// ����������
		$db_cfg[$table."/ID_users3|LIST"] = "db_resurs";	//
		$db_cfg[$table."/ID_users3|LIST_WHERE"] = "TID='0'";	//
		$db_cfg[$table."/CDATE"] = "date";				// ���� �������� �������
		$db_cfg[$table."/CTIME"] = "tinytext";			// ����� �������� �������
		$db_cfg[$table."/STARTDATE"] = "date";			// �������� ������ ���������� �������
		$db_cfg[$table."/STARTTIME"] = "tinytext";		// �������� ������ ���������� �������
		$db_cfg[$table."/TXT"] = "tinytext";			// C��������� �������
		$db_cfg[$table."/DOCISP"] = "tinytext";			// ��������, �������������� ����������
		$db_cfg[$table."/KOMM1"] = "tinytext";			// ����������� ������
		$db_cfg[$table."/KOMM2"] = "tinytext";			// ����������� �����������
		$db_cfg[$table."/KOMM3"] = "tinytext";			// ����������� �����������
		$db_cfg[$table."/ETIME"] = "time";				// ����� ��������� ����-���� � �������
		$db_cfg[$table."/EUSER"] = "list";				// ��� ������ �������
		$db_cfg[$table."/EUSER|LIST"] = "db_resurs";	//
		$db_cfg[$table."/DATE_PLAN"] = "date";			// �������� ����������� ����
		$db_cfg[$table."/TIME_PLAN"] = "tinytext";		// �������� ����������� �����
		$db_cfg[$table."/STATUS"] = "tinytext";			// ������� ������ �������
		$db_cfg[$table."/TIP_FAIL"] = "tinytext";		// ��� ����� ��� �������� � ������ �������
		$db_cfg[$table."/TIT_HEAD"] = "tinytext";		// ��� ����� ��� �������� � ������ �������
		$db_cfg[$table."/TIP_JOB"] = "alist";			// ��� ������ �������
		$db_cfg[$table."/TIP_JOB|LIST"] = "���������|����������|��������� (����� �����)";
		$db_cfg[$table."/ID_edo"] = "list";				// � ���������
		$db_cfg[$table."/ID_edo|LIST"] = "db_edo_inout_files";
		$db_cfg[$table."/ID_zak"] = "list";				// � ������
		$db_cfg[$table."/ID_zak|LIST"] = "db_zak";


////////////////////////////////////////////////////////////////////////////
//
// �������� ��������� �������� ��� ��� �������
//
////////////////////////////////////////////////////////////////////////////


	$table = "db_itrzadan_statuses";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "���������� ��������� �������� �������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|EDITTIME"] = "TIME";
	$db_cfg[$table."|CREATEDATE"] = "DATA";



	$db_cfg[$table."|LIST_FIELD"] = "DATA|TIME|STATUS|USER";
	$db_cfg[$table."|LIST_SEARCH"] = "DATA|TIME|STATUS|USER";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "DATA|TIME|STATUS|USER|ID_edo";

		$db_cfg[$table."/DATA"] = "date";				// ���� ��������� �������
		$db_cfg[$table."/TIME"] = "time2";				// ����� ��������� �������
		$db_cfg[$table."/STATUS"] = "tinytext";			// �� ����� ��� ������ ������
		$db_cfg[$table."/USER"] = "tinytext";			// ��� ��� ������ ������
		$db_cfg[$table."/ID_edo"] = "list";				// � ������ /���������
		$db_cfg[$table."/ID_edo|LIST"] = "db_itrzadan";







//////////
//	//
//  52	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ������� �������������� ������������
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_inv_cat";

	$db_cfg[$table."|TYPE"] = "tree";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "������� ��������������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|ONCREATE"] = "add_db_inv_cat.php";	// ����������� �����
	$db_cfg[$table."|MAXDEEP"] = 2;



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|ORD|PREFIX";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/ORD"] = "tinytext";
		$db_cfg[$table."/PREFIX"] = "tinytext";








//////////
//	//
//  53	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ��������������
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_inv";

	$db_cfg[$table."|TYPE"] = "tree";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "�������� � ��������������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "ID_inv_cat";
	$db_cfg[$table."|ONCREATE"] = "add_db_inv.php";		// ����������� �����
	$db_cfg[$table."|MAXDEEP"] = 2;



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|ID_inv_cat|MODEL|ORD_NUM|YY|INV|ID_resurs|SOST|LASTDATE|POVDATE|MORE|ZAVNUM|KOMPL|DATEVID|USETP|TCLASS|SCALE|PRIOBR|ID_inv_places|OLD_INV";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/ID_inv_cat"] = "list";
		$db_cfg[$table."/ID_inv_cat|LIST"] = "db_inv_cat";
		$db_cfg[$table."/MODEL"] = "tinytext";
		$db_cfg[$table."/ORD_NUM"] = "tinytext";		
		$db_cfg[$table."/YY"] = "pinteger";
		$db_cfg[$table."/INV"] = "tinytext";
		$db_cfg[$table."/ID_resurs"] = "list";
		$db_cfg[$table."/ID_resurs|LIST"] = "db_resurs";
		$db_cfg[$table."/SOST"] = "alist";
		$db_cfg[$table."/SOST|LIST"] = "�������|������|�����������|�������";
		$db_cfg[$table."/LASTDATE"] = "date";
		$db_cfg[$table."/POVDATE"] = "date";
		$db_cfg[$table."/MORE"] = "tinytext";
		$db_cfg[$table."/ZAVNUM"] = "tinytext";
		$db_cfg[$table."/KOMPL"] = "tinytext";
		$db_cfg[$table."/DATEVID"] = "date";
		$db_cfg[$table."/USETP"] = "alist";
		$db_cfg[$table."/USETP|LIST"] = "����������|���������|������";
		$db_cfg[$table."/TCLASS"] = "tinytext";
		$db_cfg[$table."/SCALE"] = "tinytext";
		$db_cfg[$table."/PRIOBR"] = "tinytext";
		$db_cfg[$table."/ID_inv_places"] = "droplist";
		$db_cfg[$table."/ID_inv_places|LIST"] = "db_inv_places";
		$db_cfg[$table."/OLD_INV"] = "tinytext";








//////////
//	//
//  54	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ����� ���������� ��� ��������������
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_inv_places";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "����� ���������� ��� ��������������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "NAME|MORE";

		$db_cfg[$table."/NAME"] = "tinytext";
		$db_cfg[$table."/MORE"] = "tinytext";








//////////
//	//
//  55	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// �������� ������������
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_clients_contacts";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "�������� ������������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "PROF";
	$db_cfg[$table."|LIST_FIELD2"] = "FIO";
	$db_cfg[$table."|LIST_SEARCH"] = "PROF|FIO";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "PROF|FIO|TEL|EMAIL|ID_clients|DATE";

		$db_cfg[$table."/PROF"] = "tinytext";
		$db_cfg[$table."/FIO"] = "tinytext";
		$db_cfg[$table."/TEL"] = "tinytext";
		$db_cfg[$table."/EMAIL"] = "tinytext";
		$db_cfg[$table."/ID_clients"] = "list";
		$db_cfg[$table."/ID_clients|LIST"] = "db_clients";
		$db_cfg[$table."/DATE"] = "date";





////////////
//
// ������������� � ���������� �����������
//
///////////////////////////////////////////////////////////////////////////

$table = "db_inv_cat_tools";

 $db_cfg[$table."|TYPE"] = "tree";
 $db_cfg[$table."|ERP"] = "false";

 $db_cfg[$table."|MORE"] = "������������� � ���������� �����������";
 $db_cfg[$table."|DELRIGHT"] = "";
 $db_cfg[$table."|CREATEBY"] = "";
 $db_cfg[$table."|CREATEDATE"] = "";
 $db_cfg[$table."|HOLDBY"] = "";
 $db_cfg[$table."|DELWITH"] = "";
 $db_cfg[$table."|ADDWITH"] = "";
 $db_cfg[$table."|BYPARENT"] = "";
 $db_cfg[$table."|ONCREATE"] = "add_db_inv_cat_tools.php"; // ����������� �����
 $db_cfg[$table."|MAXDEEP"] = 2;



 $db_cfg[$table."|LIST_FIELD"] = "NAME";
 $db_cfg[$table."|LIST_SEARCH"] = "NAME";
 $db_cfg[$table."|LIST_PREFIX"] = ", ";
 $db_cfg[$table."|ADDINDEX"] = "";

 $db_cfg[$table."|FIELDS"] = "NAME|ORD|PREFIX";

  $db_cfg[$table."/NAME"] = "tinytext";
  $db_cfg[$table."/ORD"] = "tinytext";
  $db_cfg[$table."/PREFIX"] = "tinytext";



///////////////////////////////////////////////////////////////////////////
//
// ���������� �����������
//
///////////////////////////////////////////////////////////////////////////

$table = "db_reference_tool";

$db_cfg[$table."|TYPE"] = "line";
$db_cfg[$table."|ERP"] = "false";

$db_cfg[$table."|MORE"] = "���������� �����������";
$db_cfg[$table."|DELRIGHT"] = "";
$db_cfg[$table."|CREATEBY"] = "";
$db_cfg[$table."|CREATEDATE"] = "";
$db_cfg[$table."|HOLDBY"] = "";
$db_cfg[$table."|DELWITH"] = "";
$db_cfg[$table."|ADDWITH"] = "";

$db_cfg[$table."|LIST_FIELD"] = "S_NAME";
$db_cfg[$table."|LIST_SEARCH"] = "";
$db_cfg[$table."|LIST_PREFIX"] = ", ";
$db_cfg[$table."|ADDINDEX"] = "";

$db_cfg[$table."|FIELDS"] = "N_CODE_IN_GROUP|ID_inv_cat_tools|S_BARCODE|S_NAME|ID_MATERIAL|N_PARAM2|N_PARAM3|N_PARAM4";

$db_cfg[$table."/S_BARCODE"] = "text";
$db_cfg[$table."/S_NAME"] = "tinytext";
$db_cfg[$table."/ID_MATERIAL"] = "droplist";
$db_cfg[$table."/ID_MATERIAL|LIST"] = "db_mat";
$db_cfg[$table."/ID_MATERIAL|LIST_WHERE"] = "ID_MAT_CAT in ('765')";
$db_cfg[$table."/N_PARAM2"] = "tinytext";
$db_cfg[$table."/N_PARAM3"] = "tinytext";
$db_cfg[$table."/N_PARAM4"] = "tinytext";
$db_cfg[$table."/ID_inv_cat_tools"] = "list";
$db_cfg[$table."/ID_inv_cat_tools|LIST"] = "db_inv_cat_tools";
$db_cfg[$table."/N_CODE_IN_GROUP"] = "integer";


//////////////////////////////////////
//
// ������� ���
//
//////////////////////////////////////


	$table = "db_edo_inout_files";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "��������/���������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "ID_users";
	$db_cfg[$table."|CREATEDATE"] = "DATE_START";
	$db_cfg[$table."|EDITTIME"] = "EDITTIME";
	$db_cfg[$table."|EDITUSER"] = "ID_users";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	//$db_cfg[$table."|ONCREATE"] = "add_db_edo_files.php";


	$db_cfg[$table."|LIST_FIELD"] = "NAME_IN";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME_IN|TIP_FAIL";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "KOMM|CONTRAGENT|OTVET_INOUT|TIP_FAIL|ID_clients|ID_clients_contacts|ID_contacts|NAME_OUT|NAME_IN|DATA|DATE_START|VID_FAIL|MORE|ID_krz|ID_krz2|ID_zak|ID_files_3|ID_files_1|FILENAME|ID_resurs|EDITTIME|ID_users";

		$db_cfg[$table."/TIP_FAIL"] = "tinytext";
		$db_cfg[$table."/DATA"] = "date";
		$db_cfg[$table."/DATE_START"] = "date";
		$db_cfg[$table."/ID_clients"] = "list";
		$db_cfg[$table."/ID_clients|LIST"] = "db_clients";
		$db_cfg[$table."/ID_clients|ONCHANGE"] = "edo_change_clients.php";
		$db_cfg[$table."/CONTRAGENT"] = "tinytext";
		$db_cfg[$table."/KOMM"] = "tinytext";
		$db_cfg[$table."/ID_clients_contacts"] = "list2";
		$db_cfg[$table."/ID_clients_contacts|LIST"] = "db_clients_contacts";
		$db_cfg[$table."/ID_clients_contacts|LIST_EQUAL"] = "ID_clients|ID_clients";
		$db_cfg[$table."/ID_contacts"] = "list2";
		$db_cfg[$table."/ID_contacts|LIST"] = "db_contacts";
		$db_cfg[$table."/NAME_IN"] = "tinytext";
		$db_cfg[$table."/NAME_OUT"] = "tinytext";
		$db_cfg[$table."/VID_FAIL"] = "droplist";
		$db_cfg[$table."/VID_FAIL|LIST"] = "db_edo_inout_files_vidfails";
		$db_cfg[$table."/OTVET_INOUT"] = "tinytext";
		$db_cfg[$table."/MORE"] = "textarea";
		$db_cfg[$table."/ID_krz"] = "list";
		$db_cfg[$table."/ID_krz|LIST"] = "db_krz";
		$db_cfg[$table."/ID_krz2"] = "list";
		$db_cfg[$table."/ID_krz2|LIST"] = "db_krz2";
		$db_cfg[$table."/ID_zak"] = "list";
		$db_cfg[$table."/ID_zak|LIST"] = "db_zak";
		$db_cfg[$table."/ID_files_3"] = "list";
		$db_cfg[$table."/ID_files_3|LIST"] = "db_files_3";
		//$db_cfg[$table."/ID_files_3|LIST_EQUAL"] = "ID_clients|ID_clients";
		$db_cfg[$table."/ID_files_1"] = "list";
		$db_cfg[$table."/ID_files_1|LIST"] = "db_files_3";
		//$db_cfg[$table."/ID_files_1|LIST_EQUAL"] = "PID|ID_files_3";
		$db_cfg[$table."/FILENAME"] = "file";
		$db_cfg[$table."/ID_resurs"] = "list";
		$db_cfg[$table."/ID_resurs|LIST"] = "db_resurs";
		$db_cfg[$table."/EDITTIME"] = "time";
		$db_cfg[$table."/ID_users"] = "list";
		$db_cfg[$table."/ID_users|LIST"] = "users";

//////////////////////////////////////
//
// ������� ��� ��� ������������� �����������
//
//////////////////////////////////////


	$table = "db_edo_inout_files_vrem";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "��������/���������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "ID_users";
	$db_cfg[$table."|CREATEDATE"] = "DATE_START";
	$db_cfg[$table."|EDITTIME"] = "EDITTIME";
	$db_cfg[$table."|EDITUSER"] = "ID_users";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|ONCREATE"] = "add_db_edo_files.php";


	$db_cfg[$table."|LIST_FIELD"] = "NAME_IN";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME_IN|TIP_FAIL";
	$db_cfg[$table."|LIST_PREFIX"] = " - ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "KOMM|CONTRAGENT|OTVET_INOUT|TIP_FAIL|ID_clients|ID_clients_contacts|ID_contacts|NAME_OUT|NAME_IN|DATA|DATE_START|VID_FAIL|MORE|ID_krz|ID_krz2|ID_zak|ID_files_3|ID_files_1|FILENAME|ID_resurs|EDITTIME|ID_users";

		$db_cfg[$table."/TIP_FAIL"] = "tinytext";
		$db_cfg[$table."/DATA"] = "date";
		$db_cfg[$table."/DATE_START"] = "date";
		$db_cfg[$table."/ID_clients"] = "list";
		$db_cfg[$table."/ID_clients|LIST"] = "db_clients";
		$db_cfg[$table."/ID_clients|ONCHANGE"] = "edo_change_clients.php";
		$db_cfg[$table."/CONTRAGENT"] = "tinytext";
		$db_cfg[$table."/KOMM"] = "tinytext";
		$db_cfg[$table."/ID_clients_contacts"] = "list2";
		$db_cfg[$table."/ID_clients_contacts|LIST"] = "db_clients_contacts";
		$db_cfg[$table."/ID_clients_contacts|LIST_EQUAL"] = "ID_clients|ID_clients";
		$db_cfg[$table."/ID_contacts"] = "list2";
		$db_cfg[$table."/ID_contacts|LIST"] = "db_contacts";
		$db_cfg[$table."/NAME_IN"] = "tinytext";
		$db_cfg[$table."/NAME_OUT"] = "tinytext";
		$db_cfg[$table."/VID_FAIL"] = "droplist";
		$db_cfg[$table."/VID_FAIL|LIST"] = "db_edo_inout_files_vidfails";
		$db_cfg[$table."/OTVET_INOUT"] = "tinytext";
		$db_cfg[$table."/MORE"] = "textarea";
		$db_cfg[$table."/ID_krz"] = "list";
		$db_cfg[$table."/ID_krz|LIST"] = "db_krz";
		$db_cfg[$table."/ID_krz2"] = "list";
		$db_cfg[$table."/ID_krz2|LIST"] = "db_krz2";
		$db_cfg[$table."/ID_zak"] = "list";
		$db_cfg[$table."/ID_zak|LIST"] = "db_zak";
		$db_cfg[$table."/ID_files_3"] = "list";
		$db_cfg[$table."/ID_files_3|LIST"] = "db_files_3";
		$db_cfg[$table."/ID_files_3|LIST_EQUAL"] = "ID_clients|ID_clients";
		$db_cfg[$table."/ID_files_1"] = "list";
		$db_cfg[$table."/ID_files_1|LIST"] = "db_files_3";
		$db_cfg[$table."/ID_files_1|LIST_EQUAL"] = "PID|ID_files_3";
		$db_cfg[$table."/FILENAME"] = "file";
		$db_cfg[$table."/ID_resurs"] = "list";
		$db_cfg[$table."/ID_resurs|LIST"] = "db_resurs";
		$db_cfg[$table."/EDITTIME"] = "time";
		$db_cfg[$table."/ID_users"] = "list";
		$db_cfg[$table."/ID_users|LIST"] = "users";
		
//////////////////////////////////////
//
// ������� ����� ������ ���
//
//////////////////////////////////////

	$table = "db_edo_inout_files_vidfails";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "���� ���������� ���";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "VID";
	$db_cfg[$table."|LIST_SEARCH"] = "VID";
	$db_cfg[$table."|LIST_PREFIX"] = "";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "VID";

		$db_cfg[$table."/VID"] = "tinytext";

//////////////////////////////////////
//
// ������� ��������� ������� ����������� �� ����� ������ �������� ������� db_edo_inout_files
//
//////////////////////////////////////

	$table = "db_edo_vremitr";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "��������� ������� �� 1 �������� ������� ���";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "VID";
	$db_cfg[$table."|LIST_SEARCH"] = "VID";
	$db_cfg[$table."|LIST_PREFIX"] = "";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "ID_users2|ID_users3|TXT|MORE|DATE_PLAN|ID_contacts";

		$db_cfg[$table."/ID_users2"] = "list";			// �������������
		$db_cfg[$table."/ID_users2|LIST"] = "db_resurs";
		$db_cfg[$table."/ID_users3"] = "list";			// ����������
		$db_cfg[$table."/ID_users3|LIST"] = "db_resurs";
		$db_cfg[$table."/ID_users3|LIST_WHERE"] = "TID='0'";
		$db_cfg[$table."/TXT"] = "tinytext";
		$db_cfg[$table."/MORE"] = "textarea";
		$db_cfg[$table."/DATE_PLAN"] = "date";
		$db_cfg[$table."/ID_contacts"] = "list";			// �������������
		$db_cfg[$table."/ID_contacts|LIST"] = "db_contacts";

//////////////////////////////////////
//
// ������� ��������� ������� ����������� �� ����� ������ �������� ������� db_edo_inout_files
//
//////////////////////////////////////

	$table = "db_itr_vremitr";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "��������� ������� �� 1 �������� ������� ���";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";


	$db_cfg[$table."|LIST_FIELD"] = "VID";
	$db_cfg[$table."|LIST_SEARCH"] = "VID";
	$db_cfg[$table."|LIST_PREFIX"] = "";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "ID_users|ID_users2|TIP_JOB|ID_zak|ID_users3|TXT|DATE_PLAN|TIME_PLAN|STARTDATE|STARTTIME|KOMM1";

		$db_cfg[$table."/ID_users"] = "tinytext";			// �������������
		$db_cfg[$table."/ID_users2"] = "list";			// �������������
		$db_cfg[$table."/ID_users2|LIST"] = "db_resurs";
		$db_cfg[$table."/TIP_JOB"] = "alist";			// ��� ������ �������
		$db_cfg[$table."/TIP_JOB|LIST"] = "���������|����������|������|������ �� �������";
		$db_cfg[$table."/ID_zak"] = "list";				// � ������
		$db_cfg[$table."/ID_zak|LIST"] = "db_zak";
		$db_cfg[$table."/ID_zak|LIST_WHERE"] = "EDIT_STATE='0'";
		$db_cfg[$table."/ID_users3"] = "list";			// ����������
		$db_cfg[$table."/ID_users3|LIST"] = "db_resurs";
		$db_cfg[$table."/ID_users3|LIST_WHERE"] = "TID='0'";
		$db_cfg[$table."/TXT"] = "textarea";
		$db_cfg[$table."/DATE_PLAN"] = "date";
		$db_cfg[$table."/TIME_PLAN"] = "tinytext";
		$db_cfg[$table."/STARTDATE"] = "date";			// �������� ������ ���������� �������
		$db_cfg[$table."/STARTTIME"] = "tinytext";		// �������� ������ ���������� �������
		$db_cfg[$table."/KOMM1"] = "tinytext";			// ����������� ������

////////////////////////////////////////////////////////////////////////////
//
// ������ �� ���������
//
////////////////////////////////////////////////////////////////////////////

$table = "db_logistic_app";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";
	$db_cfg[$table."|MORE"] = "������ �� ���������";
	$db_cfg[$table."|DELRIGHT"] = "ID_USERS";
	$db_cfg[$table."|CREATEBY"] = "ID_USERS";
	$db_cfg[$table."|EDITTIME"] = "FINAL_DATE";
	$db_cfg[$table."|HOLDBY"] = "SOGL|FINISH_STATE";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ONCREATE"] = "zayavk_log_settime.php";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|CREATEDATE"] = "DATE_�REATE";
	
	$db_cfg[$table."|LIST_FIELD"] = "";
	$db_cfg[$table."|LIST_SEARCH"] = "";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "DATE_�REATE|TRANSFER_TIME|N_APPLICATION|ID_USERS|DATE|APPLICATION|QUANTITY|TRANSFER_DATE|TRANSFER_FROM|TRANSFER_TO|COMMENT|CONTRAGENT_CONTACT|SOGL|SOGL_USER|FINISH_STATE|FINAL_DATE";

		$db_cfg[$table."/N_APPLICATION"] = "tinytext";  // � ������
		$db_cfg[$table."/ID_USERS"] = "list";  // ���������
		$db_cfg[$table."/ID_USERS|LIST"] = "users";
		$db_cfg[$table."/DATE"] = "time";           // ����, ����� ������
		$db_cfg[$table."/APPLICATION"] = "textarea";  // ������
		$db_cfg[$table."/APPLICATION|EDITRIGHT"] = "ID_USERS";
		$db_cfg[$table."/QUANTITY"] = "pinteger";  // ���������� ?!
		$db_cfg[$table."/QUANTITY|EDITRIGHT"] = "ID_USERS";
		$db_cfg[$table."/TRANSFER_DATE"] = "date";           // ���� ���������
		$db_cfg[$table."/TRANSFER_DATE|EDITRIGHT"] = "ID_USERS";
		$db_cfg[$table."/TRANSFER_TIME"] = "tinytext";           // ����� ���������
		$db_cfg[$table."/TRANSFER_TIME|EDITRIGHT"] = "ID_USERS";
		$db_cfg[$table."/TRANSFER_FROM"] = "tinytext";  // ������
		$db_cfg[$table."/TRANSFER_FROM|EDITRIGHT"] = "ID_USERS";
		$db_cfg[$table."/TRANSFER_TO"] = "textarea";  // ���� (����� ����������)
		$db_cfg[$table."/TRANSFER_TO|EDITRIGHT"] = "ID_USERS";
		$db_cfg[$table."/COMMENT"] = "textarea";     // �����������
		$db_cfg[$table."/CONTRAGENT_CONTACT"] = "textarea";  // ������� ����������� (+ �������� ����������)
		$db_cfg[$table."/CONTRAGENT_CONTACT|EDITRIGHT"] = "ID_USERS";
		$db_cfg[$table."/SOGL"] = "state";  // ���� / �� ����
		$db_cfg[$table."/SOGL|LIST"] = "�����������|���������";
		$db_cfg[$table."/SOGL|HOLD"] = "APPLICATION|TRANSFER_TIME|N_APPLICATION|ID_USERS|DATE|QWEST|QUANTITY|TRANSFER_DATE|TRANSFER_FROM|TRANSFER_TO|CONTRAGENT_CONTACT";
		$db_cfg[$table."/SOGL|USER"] = "SOGL_USER"; // �������� ID ������������ ����������� ������
		$db_cfg[$table."/SOGL_USER"] = "list";
		$db_cfg[$table."/SOGL_USER|LIST"] = "users";
		$db_cfg[$table."/FINISH_STATE"] = "state"; // ��������� / �� ���������
		$db_cfg[$table."/FINISH_STATE|LIST"] = "���������";
		$db_cfg[$table."/FINISH_STATE|HOLD"] = "APPLICATION|TRANSFER_TIME|N_APPLICATION|ID_USERS|DATE|QWEST|QUANTITY|TRANSFER_DATE|TRANSFER_FROM|TRANSFER_TO|COMMENT|CONTRAGENT_CONTACT";
		$db_cfg[$table."/FINAL_DATE"] = "time";           // ����� ��������� �������� (�������� �� �����)
		$db_cfg[$table."/DATE_�REATE"] = "date"; //��� ������

		$db_cfg[$table."/driver"] = "alist";
		$db_cfg[$table."/driver|LIST"] = "����� ������ ������������|����������� ���������|�������� �������� �������������|������ ���������� ����������";


///////////
//
// ���������� ���� ��������
//
///////////////////////////////////////////////////////////////////////////

$table = "db_inv_storage_areas";

$db_cfg[$table."|TYPE"] = "tree";
$db_cfg[$table."|ERP"] = "false";

$db_cfg[$table."|MORE"] = "���������� ���� ��������";
$db_cfg[$table."|DELRIGHT"] = "";
$db_cfg[$table."|CREATEBY"] = "";
$db_cfg[$table."|CREATEDATE"] = "";
$db_cfg[$table."|HOLDBY"] = "";
$db_cfg[$table."|DELWITH"] = "";
$db_cfg[$table."|ADDWITH"] = "";
$db_cfg[$table."|BYPARENT"] = "";
$db_cfg[$table."|MAXDEEP"] = 4;



$db_cfg[$table."|LIST_FIELD"] = "NAME";
$db_cfg[$table."|LIST_SEARCH"] = "NAME";
$db_cfg[$table."|LIST_PREFIX"] = ", ";
$db_cfg[$table."|ADDINDEX"] = "";

$db_cfg[$table."|FIELDS"] = "NAME|N_CODE_IN_GROUP|PREFIX";

$db_cfg[$table."/NAME"] = "tinytext";
$db_cfg[$table."/PREFIX"] = "tinytext";
$db_cfg[$table."/N_CODE_IN_GROUP"] = "integer";








//////////
//	//
//  56	//
//	//
///////////////////////////////////////////////////////////////////////////
//
// ���� ����
//
///////////////////////////////////////////////////////////////////////////

	$table = "db_planzad";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "���� ����";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEBY"] = "";
	$db_cfg[$table."|CREATEDATE"] = "";
	$db_cfg[$table."|HOLDBY"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";



	$db_cfg[$table."|LIST_FIELD"] = "NAME";
	$db_cfg[$table."|LIST_SEARCH"] = "NAME";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "DATE|ID_zak|ID_zakdet|ID_operitems|NORM";

		$db_cfg[$table."/PROF"] = "tinytext";
		$db_cfg[$table."/FIO"] = "tinytext";
		$db_cfg[$table."/TEL"] = "tinytext";
		$db_cfg[$table."/EMAIL"] = "tinytext";
		$db_cfg[$table."/ID_clients"] = "list";
		$db_cfg[$table."/ID_clients|LIST"] = "db_clients";
		$db_cfg[$table."/DATE"] = "date";


		$db_cfg[$table."/DATE"] = "date";
		$db_cfg[$table."/ID_zak"] = "list";
		$db_cfg[$table."/ID_zak_LIST"] = "db_zak";
		$db_cfg[$table."/ID_zakdet"] = "list";
		$db_cfg[$table."/ID_zakdet|LIST"] = "db_zakdet";
		$db_cfg[$table."/ID_operitems"] = "list";
		$db_cfg[$table."/ID_operitems|LIST"] = "db_operitems";
		$db_cfg[$table."/NORM"] = "preal";



////////////////////////////////
//
//		�������
//
////////////////////////////////

	$table = "db_zapros_all";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "�������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|CREATEDATE"] = "CDATE";
	$db_cfg[$table."|CREATETIME"] = "CTIME";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";


	$db_cfg[$table."|LIST_FIELD"] = "";
	$db_cfg[$table."|LIST_SEARCH"] = "";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "TIP_ZAPR|TIME_FACT|DATE_FACT|SOGL|TIME_PLAN|TIT_HEAD|KOMM|ID_users|ID_users2|ID_users2_plan|ID_users3|CDATE|CTIME|TXT|DATE_PLAN|STATUS|ID_itrzadan";

		$db_cfg[$table."/ID_users"] = "list";		// ���������
		$db_cfg[$table."/ID_users|LIST"] = "db_resurs";	//
		$db_cfg[$table."/ID_users2"] = "list";		// �������������
		$db_cfg[$table."/ID_users2|LIST"] = "db_resurs";	//
		$db_cfg[$table."/ID_users2_plan"] = "list";		// �������������
		$db_cfg[$table."/ID_users2_plan|LIST"] = "db_resurs";	//
		$db_cfg[$table."/ID_users3"] = "list";		// ����������
		$db_cfg[$table."/ID_users3|LIST"] = "db_resurs";	//
		$db_cfg[$table."/CDATE"] = "date";				// ���� �������� �������
		$db_cfg[$table."/CTIME"] = "tinytext";			// ����� �������� �������
		$db_cfg[$table."/DATE_FACT"] = "tinytext";		
		$db_cfg[$table."/TIME_FACT"] = "tinytext";		
		$db_cfg[$table."/TXT"] = "tinytext";			// C��������� �������
		$db_cfg[$table."/KOMM"] = "tinytext";			// ����������� ������
		$db_cfg[$table."/DATE_PLAN"] = "date";			// �������� ����������� ����
		$db_cfg[$table."/TIME_PLAN"] = "tinytext";		// �������� ����������� �����
		$db_cfg[$table."/STATUS"] = "tinytext";			// ������� ������ �������
		$db_cfg[$table."/TIT_HEAD"] = "tinytext";		// ��� ����� ��� �������� � ������ �������
		$db_cfg[$table."/ID_itrzadan"] = "integer";				// � ���������
		$db_cfg[$table."/SOGL"] = "integer";				// � ���������
		$db_cfg[$table."/TIP_ZAPR"] = "integer";				// � ���������


////////////////////////////////
//
//		������ ���    /// CUR_ID
//
////////////////////////////////

	$table = "db_online_chat_curid";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "������ ��� / CUR_ID";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|EDITTIME"] = "CHTIME";


	$db_cfg[$table."|LIST_FIELD"] = "";
	$db_cfg[$table."|LIST_SEARCH"] = "";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "CHTIME|NICK|WORDS";

		$db_cfg[$table."/CHTIME"] = "time";		// 
		$db_cfg[$table."/NICK"] = "tinytext";		// 
		$db_cfg[$table."/WORDS"] = "tinytext";		// 

////////////////////////////////
//
//		������ ���    /// ������������
//
////////////////////////////////

	$table = "db_online_chat_curid_users";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "������ ��� / CUR_ID";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";

	$db_cfg[$table."|LIST_FIELD"] = "";
	$db_cfg[$table."|LIST_SEARCH"] = "";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "IO";

		$db_cfg[$table."/IO"] = "tinytext";		// 


///////////////////////////////
//
//		��������� - ���
//
////////////////////////////////

	$table = "db_protocols";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "���������";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|EDITUSER"] = "EUSER";
	$db_cfg[$table."|EDITTIME"] = "ETIME";
	$db_cfg[$table."|ONCREATE"] = "add_db_protocols.php";

	$db_cfg[$table."|LIST_FIELD"] = "";
	$db_cfg[$table."|LIST_SEARCH"] = "";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "EDIT_STATE|ETIME|EUSER|NUMBER|DATA|DATA_PLAN|ID_zaks|ID_users|ID_users2|ID_users3|ID_users4|NAME|TXT";

		$db_cfg[$table."/EDIT_STATE"] = "integer";			
		$db_cfg[$table."/ETIME"] = "time";			// ����� ��������������
		$db_cfg[$table."/EUSER"] = "list";			// ��� ������������
		$db_cfg[$table."/EUSER|LIST"] = "users";		
		$db_cfg[$table."/NUMBER"] = "tinytext";			// ����� ���������
		$db_cfg[$table."/DATA"] = "date";			// ���� ���������
		$db_cfg[$table."/DATA_PLAN"] = "text";		// �������� ���� �������
		$db_cfg[$table."/ID_zaks"] = "text";		// ������ �� ������ ������������ ���������
		$db_cfg[$table."/ID_users"] = "text";		// ����� ��������� / �������
		$db_cfg[$table."/ID_users2"] = "text";		// ����������� �������
		$db_cfg[$table."/ID_users3"] = "text";		// �������� �������
		$db_cfg[$table."/ID_users4"] = "text";		// ��������� ��������� 
		$db_cfg[$table."/NAME"] = "tinytext";		// �������� ���������
		$db_cfg[$table."/TXT"] = "longtext";		// ���������� �������



//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
//  ������������� - ��������� ���� �����������
//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
$table = "db_v_mov_adr";

$db_cfg[$table."|TYPE"] = "line";
$db_cfg[$table."|ERP"] = "false";

$db_cfg[$table."|MORE"] = "��������� ���� �����������";
$db_cfg[$table."|DELRIGHT"] = "";
$db_cfg[$table."|CREATEBY"] = "";
$db_cfg[$table."|CREATEDATE"] = "";
$db_cfg[$table."|HOLDBY"] = "";
$db_cfg[$table."|DELWITH"] = "";
$db_cfg[$table."|ADDWITH"] = "";
$db_cfg[$table."|BYPARENT"] = "";

$db_cfg[$table."|LIST_FIELD"] = "BARCODE|DESCRIPTION";
$db_cfg[$table."|LIST_SEARCH"] = "BARCODE|DESCRIPTION";
$db_cfg[$table."|LIST_PREFIX"] = ", ";
$db_cfg[$table."|ADDINDEX"] = "";

$db_cfg[$table."|FIELDS"] = "BARCODE|DESCRIPTION|TYPE|STATUS";

$db_cfg[$table."/BARCODE"] = "tinytext";
$db_cfg[$table."/DESCRIPTION"] = "tinytext";
$db_cfg[$table."/TYPE"] = "pinteger";
$db_cfg[$table."/STATUS"] = "pinteger";


//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
//  ����� �������� � �����������
//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
$table = "db_movements_tool_destination";

$db_cfg[$table."|TYPE"] = "line";
$db_cfg[$table."|ERP"] = "false";

$db_cfg[$table."|MORE"] = "����� �������� � ����������";
$db_cfg[$table."|DELRIGHT"] = "";
$db_cfg[$table."|CREATEBY"] = "";
$db_cfg[$table."|CREATEDATE"] = "";
$db_cfg[$table."|EDITTIME"] = "";
$db_cfg[$table."|HOLDBY"] = "EXECUTE";
$db_cfg[$table."|DELWITH"] = "";
$db_cfg[$table."|ADDWITH"] = "";
$db_cfg[$table."|BYPARENT"] = "";

$db_cfg[$table."|LIST_FIELD"] = "";
$db_cfg[$table."|LIST_SEARCH"] = "";
$db_cfg[$table."|LIST_PREFIX"] = ", ";
$db_cfg[$table."|ADDINDEX"] = "";

$db_cfg[$table."|FIELDS"] = "ID_MOV|ID_ADDR|N_QUANTITY|N_INPUT_TOTAL|N_OUTPUT_TOTAL|COUNT|DELETED|EXECUTE|S_EXECUTE";

$db_cfg[$table."/ID_MOV"] = "pinteger"; # ������� �� �������� �������� �����������
$db_cfg[$table."/ID_ADDR"] = "list"; # ����� ��������
$db_cfg[$table."/ID_ADDR|LIST"] = "db_v_mov_adr";
$db_cfg[$table."/ID_ADDR|HOLD"] = "ID_ADDR";
$db_cfg[$table."/N_QUANTITY"] = "integer"; # ���������� ���������� � ������ ("+" ����������, "-" ��������)
$db_cfg[$table."/N_INPUT_TOTAL"] = "integer"; # �������� ������� � ������
$db_cfg[$table."/N_OUTPUT_TOTAL"] = "integer"; # ��������� ������� � ������
$db_cfg[$table."/COUNT"] = "integer"; # ������������ ����� ����������� � �������� ������������ (�������, ��� view - mybad :( )
$db_cfg[$table."/DELETED"] = "boolean"; # ��-�� ������������ ������ (���������� ������� ���� ����� update � �� �� ����� insert), ��� ����� ������� � ������������ �� ���������� ��������
$db_cfg[$table."/EXECUTE"] = "boolean";
#$db_cfg[$table."/EXECUTE|LIST"] = "STOP"; # ������ ��������� ������
$db_cfg[$table."/EXECUTE|HOLD"] = "ID_MOV|ID_ADDR|N_QUANTITY|N_INPUT_TOTAL|N_OUTPUT_TOTAL|COUNT|DELETED|EXECUTE|S_EXECUTE";
$db_cfg[$table."/S_EXECUTE"] = "tinytext";


//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
// ������������� - ����� �����������
//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
$table = "db_v_reference_tool";

$db_cfg[$table."|TYPE"] = "line";
$db_cfg[$table."|ERP"] = "false";

$db_cfg[$table."|MORE"] = "���������� �����������";
$db_cfg[$table."|DELRIGHT"] = "";
$db_cfg[$table."|CREATEBY"] = "";
$db_cfg[$table."|CREATEDATE"] = "";
$db_cfg[$table."|HOLDBY"] = "";
$db_cfg[$table."|DELWITH"] = "";
$db_cfg[$table."|ADDWITH"] = "";

$db_cfg[$table."|LIST_FIELD"] = "S_NAME";
$db_cfg[$table."|LIST_SEARCH"] = "S_NAME";
$db_cfg[$table."|LIST_PREFIX"] = ", ";
$db_cfg[$table."|ADDINDEX"] = "";

$db_cfg[$table."|FIELDS"] = "S_NAME";

$db_cfg[$table."/S_NAME"] = "tinytext";


//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
//  �������� ����������� (��������)
//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
$table = "db_movements_tool";

$db_cfg[$table . "|TYPE"] = "line";
$db_cfg[$table . "|ERP"] = "false";

$db_cfg[$table . "|MORE"] = "�������� �����������";
$db_cfg[$table . "|DELRIGHT"] = "ID_USERS";
$db_cfg[$table . "|CREATEBY"] = "ID_USERS";
$db_cfg[$table . "|CREATEDATE"] = "DATE_CREATE";
$db_cfg[$table . "|EDITTIME"] = "DT_TIME";
$db_cfg[$table . "|HOLDBY"] = "ID_TOOL|EXECUTE";
$db_cfg[$table . "|DELWITH"] = "";
$db_cfg[$table . "|ADDWITH"] = "";

$db_cfg[$table . "|LIST_FIELD"] = "";
$db_cfg[$table . "|LIST_SEARCH"] = "";
$db_cfg[$table . "|LIST_PREFIX"] = ", ";
$db_cfg[$table . "|ADDINDEX"] = "";

$db_cfg[$table . "|FIELDS"] = "N_APPLICATION|ID_TOOL|DATE_CREATE|DT_TIME|ID_SIGN|N_QUANTITY|N_UNIT_PRICE|N_CAME_PRICE|N_INPUT_TOTAL|N_OUTPUT_TOTAL|N_PRICE_AFT_OPER|N_CUR_PRICE|EXECUTE|S_PLACE_AND_QANTITY|ID_STOCK_DOC|ID_USERS|S_EXECUTE";

$db_cfg[$table . "/N_APPLICATION"] = "integer";    # ���������� ����� ���������, ������ ������� ��������
$db_cfg[$table . "/ID_TOOL"] = "list"; # ������� �� ����������
$db_cfg[$table . "/ID_TOOL|LIST"] = "db_v_reference_tool";
$db_cfg[$table . "/ID_TOOL|HOLD"] = "ID_TOOL";
$db_cfg[$table . "/DATE_CREATE"] = "date"; # ��� ������ - ������
$db_cfg[$table . "/DT_TIME"] = "time"; # ���� + ����� ���������
$db_cfg[$table . "/ID_SIGN"] = "state";  // �������� �������� ��������
$db_cfg[$table . "/ID_SIGN|LIST"] = "������|������|C����-->������|�����-->�����|������-->�����";
$db_cfg[$table . "/N_QUANTITY"] = "pinteger"; # ����������
$db_cfg[$table . "/N_UNIT_PRICE"] = "preal"; # ���� �������
$db_cfg[$table . "/N_CAME_PRICE"] = "preal"; # ��������� �������
$db_cfg[$table . "/N_INPUT_TOTAL"] = "integer"; # �������� �������
$db_cfg[$table . "/N_OUTPUT_TOTAL"] = "integer"; # ��������� �������
$db_cfg[$table . "/N_PRICE_AFT_OPER"] = "preal"; # ���� ����� ��������
$db_cfg[$table . "/N_CUR_PRICE"] = "preal"; # ������� ���������
$db_cfg[$table . "/S_PLACE_AND_QANTITY"] = "tinytext"; # ��������� ������������� ���������� � ����������
$db_cfg[$table . "/EXECUTE"] = "boolean";
#$db_cfg[$table . "/EXECUTE|LIST"] = "���������"; # ����������� - �������, ���� ��� �������� ���������, � �������� �������� ���������.
$db_cfg[$table . "/EXECUTE|HOLD"] = "N_APPLICATION|ID_TOOL|DATE_CREATE|DT_TIME|ID_SIGN|N_QUANTITY|N_UNIT_PRICE|N_CAME_PRICE|N_INPUT_TOTAL|N_OUTPUT_TOTAL|N_PRICE_AFT_OPER|N_CUR_PRICE|EXECUTE|S_EXECUTE";
$db_cfg[$table . "/ID_STOCK_DOC"] = "pinteger"; # ������ �� ��������� ������������ ��������
$db_cfg[$table . "/ID_USERS"] = "list";            // ����� ���������
$db_cfg[$table . "/ID_USERS|LIST"] = "users";
$db_cfg[$table . "/S_EXECUTE"] = "tinytext";

//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
//  ����������� �������� ��������� ���������� (��� ��������)
//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
$table = "db_v_stocks_doc";

$db_cfg[$table . "|TYPE"] = "line";
$db_cfg[$table . "|ERP"] = "false";

$db_cfg[$table . "|MORE"] = "����������� �������� ��������� ���������� (��� ��������)";
$db_cfg[$table . "|DELRIGHT"] = "";
$db_cfg[$table . "|CREATEBY"] = "";
$db_cfg[$table . "|CREATEDATE"] = "";
$db_cfg[$table . "|EDITTIME"] = "";
$db_cfg[$table . "|HOLDBY"] = "";
$db_cfg[$table . "|DELWITH"] = "";
$db_cfg[$table . "|ADDWITH"] = "";


$db_cfg[$table . "|LIST_FIELD"] = "";
$db_cfg[$table . "|LIST_SEARCH"] = "";
$db_cfg[$table . "|LIST_PREFIX"] = ", ";
$db_cfg[$table . "|ADDINDEX"] = "";

$db_cfg[$table . "|FIELDS"] = "ID_DOCTYPE|S_DOC_NUMBER|DATE_CREATE";

$db_cfg[$table . "/ID_DOCTYPE"] = "state";  // ��� ���������
$db_cfg[$table . "/ID_DOCTYPE|LIST"] = "��������� ���������|������ �����������|��� ��������|��� ��������������";
$db_cfg[$table . "/ID_DOCTYPE|HOLD"] = "ID_DOCTYPE";
$db_cfg[$table . "/S_DOC_NUMBER"] = "tinytext";    # � ���������
$db_cfg[$table . "/DATE_CREATE"] = "date"; # ���� ��������� � ��� ������
$db_cfg[$table . "/N_SUM_CAME_PRICE"] = "preal"; #

//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
//  ���� ��������� ����������
//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
$table = "db_stock_doctype";

$db_cfg[$table . "|TYPE"] = "line";
$db_cfg[$table . "|ERP"] = "false";

$db_cfg[$table . "|MORE"] = "���� ��������� ����������";
$db_cfg[$table . "|DELRIGHT"] = "";
$db_cfg[$table . "|CREATEBY"] = "";
$db_cfg[$table . "|CREATEDATE"] = "";
$db_cfg[$table . "|EDITTIME"] = "";
$db_cfg[$table . "|HOLDBY"] = "EXECUTE";
$db_cfg[$table . "|DELWITH"] = "";
$db_cfg[$table . "|ADDWITH"] = "";

$db_cfg[$table . "|LIST_FIELD"] = "DESCRIPTION";
$db_cfg[$table . "|LIST_SEARCH"] = "";
$db_cfg[$table . "|LIST_PREFIX"] = ", ";
$db_cfg[$table . "|ADDINDEX"] = "";

$db_cfg[$table . "|FIELDS"] = "DESCRIPTION";

$db_cfg[$table . "/DESCRIPTION"] = "tinytext";

//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
//  ��������� ���������
//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
$table = "db_stocks_doc";

$db_cfg[$table."|TYPE"] = "line";
$db_cfg[$table."|ERP"] = "false";

$db_cfg[$table."|MORE"] = "�������� ��������� ���������";
$db_cfg[$table."|DELRIGHT"] = "ID_USERS";
$db_cfg[$table."|CREATEBY"] = "ID_USERS";
$db_cfg[$table."|CREATEDATE"] = "DATE_CREATE";
$db_cfg[$table."|EDITTIME"] = "DT_TIME";
$db_cfg[$table."|HOLDBY"] = "ID_DOCTYPE|EXECUTE";
$db_cfg[$table."|DELWITH"] = "";
$db_cfg[$table."|ADDWITH"] = "";


$db_cfg[$table."|LIST_FIELD"] = "";
$db_cfg[$table."|LIST_SEARCH"] = "";
$db_cfg[$table."|LIST_PREFIX"] = ", ";
$db_cfg[$table."|ADDINDEX"] = "";

$db_cfg[$table."|FIELDS"] = "ID_DOCTYPE|ID_OPERATION|S_DOC_NUMBER|DATE_CREATE|DT_TIME|ID_USERS|EXECUTE";

$db_cfg[$table."/ID_DOCTYPE"] = "droplist";  // ��� ���������
$db_cfg[$table."/ID_DOCTYPE|LIST"] = "db_stock_doctype";
$db_cfg[$table."/ID_DOCTYPE|LIST_WHERE"] = "ID>'0'";
$db_cfg[$table."/ID_DOCTYPE|HOLD"] = "ID_DOCTYPE";
$db_cfg[$table."/S_DOC_NUMBER"] = "tinytext";	# � ���������
$db_cfg[$table."/DATE_CREATE"] = "date"; # ���� ��������� � ��� ������
$db_cfg[$table."/DT_TIME"] = "time"; # ���� + ����� ��������� ���������
$db_cfg[$table."/ID_USERS"] = "list";			// ����� ���������
$db_cfg[$table."/ID_USERS|LIST"] = "users";
$db_cfg[$table."/EXECUTE"] = "boolean"; # �������� �������� (������, ���� - ��� �������� �������� ���������)
$db_cfg[$table."/EXECUTE|HOLD"] = "ID_DOCTYPE|ID_OPERATION|S_DOC_NUMBER|DATE_CREATE|DT_TIME|ID_USERS|EXECUTE";


//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
//  �������� ����� (������ �����)
//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
$table = "db_safety_job";

$db_cfg[$table . "|TYPE"] = "line";
$db_cfg[$table . "|ERP"] = "false";

$db_cfg[$table . "|MORE"] = "�������� ����� (������ �����)";
$db_cfg[$table . "|DELRIGHT"] = "";
$db_cfg[$table . "|CREATEBY"] = "";
$db_cfg[$table . "|CREATEDATE"] = "";
$db_cfg[$table . "|EDITTIME"] = "";
$db_cfg[$table . "|HOLDBY"] = "";
$db_cfg[$table . "|DELWITH"] = "";
$db_cfg[$table . "|ADDWITH"] = "";


$db_cfg[$table . "|LIST_FIELD"] = "";
$db_cfg[$table . "|LIST_SEARCH"] = "";
$db_cfg[$table . "|LIST_PREFIX"] = ", ";
$db_cfg[$table . "|ADDINDEX"] = "";

$db_cfg[$table . "|FIELDS"] = "ID_RESURS|A0_1|A0_2|A1_1|A1_2|A2_1|A2_2|A2_3|A3_1|A3_2|A3_3|A4_1|A4_2|A4_3|A5_1|A5_2|A5_3|A6_1|A6_2|A6_3|A7_1|A7_2|A7_3|A7_4|A8_1|A8_2|A9_1|A10_1|A10_2|A11_1|A12_1|A12_2|A12_3|A12_4|A13_1|A13_2|A14_1|A15_1|A15_2|B1_1|B1_2|B1_3|B2_1|B2_2|B2_3|B3_1|B3_2|B3_3|B4_1|B4_2|B4_3|B5_1|B5_2|B5_3|B5_4|B6_1|B6_2|B6_3|B6_4|B7_1|B7_2|B7_3|B7_4|C1_1|C1_2|D1_1|D1_2|D2_1|D2_2|D3_1|D3_2|D4_1|D4_2|D5_1|D5_2|E1_1|E1_2|E1_3|E1_4|E2_1|E2_2|E2_3|E2_4|F1_1|F1_2|F1_3|F1_4|F2_1|F2_2|F2_3|F2_4|F3_1|F3_2|F4_1|F4_2|F5_1|F5_2|G1_1|G1_2|G1_3|G1_4|G2_1|G2_2|G2_3|G2_4";

$db_cfg[$table . "/ID_RESURS"] = "list";
$db_cfg[$table . "/ID_RESURS|LIST"] = "db_resurs";
$db_cfg[$table . "/A0_1"] = "tinytext"; # ������ �������� �� ��
$db_cfg[$table . "/A0_2"] = "tinytext"; # ���� ������������
################ ������ �����
$db_cfg[$table . "/A1_1"] = "tinytext"; # ��������� ���������� �����������
$db_cfg[$table . "/A1_2"] = "date"; #
$db_cfg[$table . "/A2_1"] = "date"; # ���������� �������
$db_cfg[$table . "/A2_2"] = "pinteger"; #
$db_cfg[$table . "/A2_3"] = "pinteger"; #
$db_cfg[$table . "/A3_1"] = "date"; # � ���������
$db_cfg[$table . "/A3_2"] = "pinteger"; #
$db_cfg[$table . "/A3_3"] = "pinteger"; #
$db_cfg[$table . "/A4_1"] = "date"; # � ���������
$db_cfg[$table . "/A4_2"] = "pinteger"; #
$db_cfg[$table . "/A4_3"] = "pinteger"; #
$db_cfg[$table . "/A5_1"] = "date"; # � �����������
$db_cfg[$table . "/A5_2"] = "pinteger"; #
$db_cfg[$table . "/A5_3"] = "pinteger"; #
$db_cfg[$table . "/A6_1"] = "date"; # � �������
$db_cfg[$table . "/A6_2"] = "pinteger"; #
$db_cfg[$table . "/A6_3"] = "pinteger"; #
$db_cfg[$table . "/A7_1"] = "date"; # ����������
$db_cfg[$table . "/A7_2"] = "date"; #
$db_cfg[$table . "/A7_3"] = "pinteger"; #
$db_cfg[$table . "/A7_4"] = "pinteger"; #
$db_cfg[$table . "/A8_1"] = "tinytext"; # ��������
$db_cfg[$table . "/A8_2"] = "date"; #
$db_cfg[$table . "/A9_1"] = "date"; # ������ � ������
$db_cfg[$table . "/A10_1"] = "tinytext"; # �������������
$db_cfg[$table . "/A10_2"] = "date"; #
$db_cfg[$table . "/A11_1"] = "tinytext"; # 2 ���������
$db_cfg[$table . "/A12_1"] = "date"; # ����������
$db_cfg[$table . "/A12_2"] = "date"; #
$db_cfg[$table . "/A12_3"] = "pinteger"; #
$db_cfg[$table . "/A12_4"] = "pinteger"; #
$db_cfg[$table . "/A13_1"] = "tinytext"; # ��������
$db_cfg[$table . "/A13_2"] = "date"; #
$db_cfg[$table . "/A14_1"] = "date"; # ������ � ������
$db_cfg[$table . "/A15_1"] = "tinytext"; # �������������
$db_cfg[$table . "/A15_2"] = "date"; #
################ �������� ������������
$db_cfg[$table . "/B1_1"] = "date"; # ���������� �������
$db_cfg[$table . "/B1_2"] = "pinteger"; #
$db_cfg[$table . "/B1_3"] = "pinteger"; #
$db_cfg[$table . "/B2_1"] = "date"; # � ���������
$db_cfg[$table . "/B2_2"] = "pinteger"; #
$db_cfg[$table . "/B2_3"] = "pinteger"; #
$db_cfg[$table . "/B3_1"] = "date"; # � ���������
$db_cfg[$table . "/B3_2"] = "pinteger"; #
$db_cfg[$table . "/B3_3"] = "pinteger"; #
$db_cfg[$table . "/B4_1"] = "date"; # � �����������
$db_cfg[$table . "/B4_2"] = "pinteger"; #
$db_cfg[$table . "/B4_3"] = "pinteger"; #
$db_cfg[$table . "/B5_1"] = "date"; # ����������
$db_cfg[$table . "/B5_2"] = "date"; #
$db_cfg[$table . "/B5_3"] = "pinteger"; #
$db_cfg[$table . "/B5_4"] = "pinteger"; #
$db_cfg[$table . "/B6_1"] = "tinytext"; # �������
$db_cfg[$table . "/B6_2"] = "date"; #
$db_cfg[$table . "/B6_3"] = "pinteger"; #
$db_cfg[$table . "/B6_4"] = "pinteger"; #
$db_cfg[$table . "/B7_1"] = "tinytext"; # ��������
$db_cfg[$table . "/B7_2"] = "date"; #
$db_cfg[$table . "/B7_3"] = "pinteger"; #
$db_cfg[$table . "/B7_4"] = "pinteger"; #
################ ������
$db_cfg[$table . "/C1_1"] = "tinytext"; #
$db_cfg[$table . "/C1_2"] = "date"; #
################ ����� ����. ������ ������� �����
$db_cfg[$table . "/D1_1"] = "tinytext"; # ������������
$db_cfg[$table . "/D1_2"] = "date"; #
$db_cfg[$table . "/D2_1"] = "tinytext"; #
$db_cfg[$table . "/D2_2"] = "date"; #
$db_cfg[$table . "/D3_1"] = "tinytext"; #
$db_cfg[$table . "/D3_2"] = "date"; #
$db_cfg[$table . "/D4_1"] = "tinytext"; #
$db_cfg[$table . "/D4_2"] = "date"; #
$db_cfg[$table . "/D5_1"] = "tinytext"; #
$db_cfg[$table . "/D5_2"] = "date"; #
################ ���������
$db_cfg[$table . "/E1_1"] = "tinytext"; # ���������
$db_cfg[$table . "/E1_2"] = "date"; #
$db_cfg[$table . "/E1_3"] = "pinteger"; #
$db_cfg[$table . "/E1_4"] = "pinteger"; #
$db_cfg[$table . "/E2_1"] = "tinytext"; # ���������
$db_cfg[$table . "/E2_2"] = "date"; #
$db_cfg[$table . "/E2_3"] = "pinteger"; #
$db_cfg[$table . "/E2_4"] = "pinteger"; #
################ �������������������
$db_cfg[$table . "/F1_1"] = "date"; # ����������
$db_cfg[$table . "/F1_2"] = "date"; #
$db_cfg[$table . "/F1_3"] = "pinteger"; #
$db_cfg[$table . "/F1_4"] = "pinteger"; #
$db_cfg[$table . "/F2_1"] = "date"; # ������������
$db_cfg[$table . "/F2_2"] = "date"; #
$db_cfg[$table . "/F2_3"] = "pinteger"; #
$db_cfg[$table . "/F2_4"] = "pinteger"; #
$db_cfg[$table . "/F3_1"] = "tinytext"; # ��������
$db_cfg[$table . "/F3_2"] = "date"; #
$db_cfg[$table . "/F4_1"] = "tinytext"; # �������������
$db_cfg[$table . "/F4_2"] = "date"; #
$db_cfg[$table . "/F5_1"] = "tinytext"; # ������
$db_cfg[$table . "/F5_2"] = "date"; #
################ ���
$db_cfg[$table . "/G1_1"] = "tinytext"; # ������ �����
$db_cfg[$table . "/G1_2"] = "date"; # ���� ������
$db_cfg[$table . "/G1_3"] = "date"; # ���� ������
$db_cfg[$table . "/G1_4"] = "date"; # ���� ��������
$db_cfg[$table . "/G2_1"] = "tinytext"; # ������ ������
$db_cfg[$table . "/G2_2"] = "date"; # ���� ������
$db_cfg[$table . "/G2_3"] = "date"; # ���� ������
$db_cfg[$table . "/G2_4"] = "date"; # ���� ��������
$db_cfg[$table . "/height"] = "pinteger"; # ����

//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
//  ������������������ ���������
//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
$table = "db_stocks_doc_inventory";

$db_cfg[$table . "|TYPE"] = "line";
$db_cfg[$table . "|ERP"] = "false";

$db_cfg[$table . "|MORE"] = "������������������ ���������";
$db_cfg[$table . "|DELRIGHT"] = "";
$db_cfg[$table . "|CREATEBY"] = "ID_USERS";
$db_cfg[$table . "|CREATEDATE"] = "DATE_CREATE";
$db_cfg[$table . "|EDITTIME"] = "DT_TIME";
$db_cfg[$table . "|HOLDBY"] = "EXECUTE";
$db_cfg[$table . "|DELWITH"] = "";
$db_cfg[$table . "|ADDWITH"] = "";

$db_cfg[$table . "|LIST_FIELD"] = "";
$db_cfg[$table . "|LIST_SEARCH"] = "";
$db_cfg[$table . "|LIST_PREFIX"] = ", ";
$db_cfg[$table . "|ADDINDEX"] = "";

$db_cfg[$table . "|FIELDS"] = "ID_INV_CAT_TOOLS|S_DOC_NUMBER|DATE_CREATE|DT_TIME|ID_USERS|EXECUTE";

$db_cfg[$table . "/ID_INV_CAT_TOOLS"] = "droplist";  // ���������� �� �������� ���������� ��������������
$db_cfg[$table . "/ID_INV_CAT_TOOLS|LIST"] = "db_inv_cat_tools";	//
$db_cfg[$table . "/ID_INV_CAT_TOOLS|LIST_WHERE"] = "PID='0'";	//
$db_cfg[$table . "/S_DOC_NUMBER"] = "tinytext";    # � ���������
$db_cfg[$table . "/DATE_CREATE"] = "date"; # ���� �������� ���������
$db_cfg[$table . "/DT_TIME"] = "time"; # ���� + ����� ��������� ���������
$db_cfg[$table . "/ID_USERS"] = "list";            // ����� ���������
$db_cfg[$table . "/ID_USERS|LIST"] = "users";
$db_cfg[$table . "/EXECUTE"] = "boolean"; # �������� �������� (���� ���������� ��� �������������� ��� � ����� ��� ����������� �� ��������� � ��������� ���������)
$db_cfg[$table . "/EXECUTE|HOLD"] = "ID_INV_CAT_TOOLS|S_DOC_NUMBER|DATE_CREATE|DT_TIME|ID_USERS|EXECUTE";


//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
//  �������� ��������������
//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
$table = "db_inv_s";

$db_cfg[$table . "|TYPE"] = "line";
$db_cfg[$table . "|ERP"] = "false";

$db_cfg[$table . "|MORE"] = "�������� ��������������";
$db_cfg[$table . "|DELRIGHT"] = "";
$db_cfg[$table . "|CREATEBY"] = "";
$db_cfg[$table . "|CREATEDATE"] = "";
$db_cfg[$table . "|EDITTIME"] = "";
$db_cfg[$table . "|HOLDBY"] = "EXECUTE";
$db_cfg[$table . "|DELWITH"] = "";
$db_cfg[$table . "|ADDWITH"] = "";

$db_cfg[$table . "|LIST_FIELD"] = "";
$db_cfg[$table . "|LIST_SEARCH"] = "";
$db_cfg[$table . "|LIST_PREFIX"] = ", ";
$db_cfg[$table . "|ADDINDEX"] = "";

$db_cfg[$table . "|FIELDS"] = "N_APPLICATION|ID_DOC_INVENTORY|ID_TOOL|EXECUTE";

$db_cfg[$table . "/N_APPLICATION"] = "integer";    # ���������� ����� � ���������
$db_cfg[$table . "/ID_DOC_INVENTORY"] = "integer";  # ������������������ ���������
$db_cfg[$table . "/ID_TOOL"] = "droplist"; # ������� �� ����������
$db_cfg[$table . "/ID_TOOL|LIST"] = "db_v_reference_tool";
$db_cfg[$table . "/EXECUTE"] = "boolean"; # ��� ������� ��������������, ����� ������
$db_cfg[$table . "/EXECUTE|HOLD"] = "N_APPLICATION|ID_INVENTORY|ID_TOOL|EXECUTE";



//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
//  ������������ ��������� �� ����� �������������� (������ � ������� � �����������)
//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
$table = "db_inv_s_subj_addr";

$db_cfg[$table . "|TYPE"] = "line";
$db_cfg[$table . "|ERP"] = "false";

$db_cfg[$table . "|MORE"] = "������������ ��������� �� ����� ��������������";
$db_cfg[$table . "|DELRIGHT"] = "";
$db_cfg[$table . "|CREATEBY"] = "";
$db_cfg[$table . "|CREATEDATE"] = "";
$db_cfg[$table . "|EDITTIME"] = "";
$db_cfg[$table . "|HOLDBY"] = "EXECUTE";
$db_cfg[$table . "|DELWITH"] = "";
$db_cfg[$table . "|ADDWITH"] = "";

$db_cfg[$table . "|LIST_FIELD"] = "";
$db_cfg[$table . "|LIST_SEARCH"] = "";
$db_cfg[$table . "|LIST_PREFIX"] = ", ";
$db_cfg[$table . "|ADDINDEX"] = "";

$db_cfg[$table . "|FIELDS"] = "ID_INVENTORY_SUBJ|ID_ADDR|N_COUNT|EXECUTE";

$db_cfg[$table."/ID_INVENTORY_SUBJ"] = "integer"; # ������� ��������������
$db_cfg[$table."/ID_ADDR"] = "list"; # ����� ��������
$db_cfg[$table."/ID_ADDR|LIST"] = "db_v_mov_adr";
$db_cfg[$table . "/N_COUNT"] = "pinteger";    # ���������� ������� � ������ (��������������� ���������������)
$db_cfg[$table . "/N_COUNT_REAL"] = "pinteger";    # ���������� ������� � ������ (�� ������ �������� ��������� ��������������)
$db_cfg[$table . "/EXECUTE"] = "boolean"; # ��� ������� ��������������, ����� ������
$db_cfg[$table . "/EXECUTE|HOLD"] = "ID_INVENTORY_SUBJ|ID_ADDR|N_COUNT|EXECUTE";

////////////////////////////////
//
//		�������� - ���
//
////////////////////////////////

	$table = "db_mtk_perehod";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "�������� - ���";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|EDITUSER"] = "EUSER";
	$db_cfg[$table."|EDITTIME"] = "ETIME";

	$db_cfg[$table."|LIST_FIELD"] = "";
	$db_cfg[$table."|LIST_SEARCH"] = "";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "TID|ETIME|EUSER|ID_zak|ID_zakdet|ID_operitems|TXT|INSTR_1|INSTR_2|INSTR_3|DIAM_SHIR|DLINA|R_O_S|R_O_N|R_O_V|R_O_TO|R_O_TP";

		$db_cfg[$table."/TID"] = "list";		// 
		$db_cfg[$table."/ETIME"] = "time";		// 
		$db_cfg[$table."/EUSER"] = "tinytext";		// 
		$db_cfg[$table."/TXT"] = "tinytext";		// 
		$db_cfg[$table."/INSTR_1"] = "tinytext";		// 
		$db_cfg[$table."/INSTR_2"] = "tinytext";		// 
		$db_cfg[$table."/INSTR_3"] = "tinytext";		// 
		$db_cfg[$table."/DIAM_SHIR"] = "tinytext";		// 
		$db_cfg[$table."/DLINA"] = "tinytext";		// 
		$db_cfg[$table."/R_O_S"] = "tinytext";		// 
		$db_cfg[$table."/R_O_N"] = "tinytext";		// 
		$db_cfg[$table."/R_O_V"] = "tinytext";		// 
		$db_cfg[$table."/R_O_TO"] = "tinytext";		// 
		$db_cfg[$table."/R_O_TP"] = "tinytext";		// 
		$db_cfg[$table."/ID_zak"] = "list";		// 
		$db_cfg[$table."/ID_zakdet"] = "list";		// 
		$db_cfg[$table."/ID_operitems"] = "list";		// 

////////////////////////////////
//
//		�������� - ��� _ IMG
//
////////////////////////////////

	$table = "db_mtk_perehod_img";

	$db_cfg[$table."|TYPE"] = "line";
	$db_cfg[$table."|ERP"] = "false";

	$db_cfg[$table."|MORE"] = "�������� - ��� _ IMG";
	$db_cfg[$table."|DELRIGHT"] = "";
	$db_cfg[$table."|DELWITH"] = "";
	$db_cfg[$table."|ADDWITH"] = "";
	$db_cfg[$table."|BYPARENT"] = "";
	$db_cfg[$table."|EDITUSER"] = "EUSER";
	$db_cfg[$table."|EDITTIME"] = "ETIME";

	$db_cfg[$table."|LIST_FIELD"] = "";
	$db_cfg[$table."|LIST_SEARCH"] = "";
	$db_cfg[$table."|LIST_PREFIX"] = ", ";
	$db_cfg[$table."|ADDINDEX"] = "";

	$db_cfg[$table."|FIELDS"] = "ETIME|EUSER|ID_zak|ID_zakdet|ID_operitems|TID|IMG";

		$db_cfg[$table."/ETIME"] = "time";		// 
		$db_cfg[$table."/EUSER"] = "tinytext";		// 
		$db_cfg[$table."/TID"] = "list";		// 
		$db_cfg[$table."/IMG"] = "tinytext";		// 
		$db_cfg[$table."/ID_zak"] = "list";		// 
		$db_cfg[$table."/ID_zakdet"] = "list";		// 
		$db_cfg[$table."/ID_operitems"] = "list";		// 

//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
//  �������� ����������� - ������ ����������� �� ������
//   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /   /
$table = "db_movements_tool_transfer";

$db_cfg[$table."|TYPE"] = "line";
$db_cfg[$table."|ERP"] = "false";

$db_cfg[$table."|MORE"] = "�������� ����������� - ������ ����������� �� ������";
$db_cfg[$table."|DELRIGHT"] = "";
$db_cfg[$table."|CREATEBY"] = "ID_USERS";
$db_cfg[$table."|CREATEDATE"] = "DATE_CREATE";
$db_cfg[$table."|EDITTIME"] = "";
$db_cfg[$table."|HOLDBY"] = "EXECUTE|ID_TOOL|ID_ADDR_FROM";
$db_cfg[$table."|DELWITH"] = "";
$db_cfg[$table."|ADDWITH"] = "";

$db_cfg[$table."|LIST_FIELD"] = "";
$db_cfg[$table."|LIST_SEARCH"] = "";
$db_cfg[$table."|LIST_PREFIX"] = ", ";
$db_cfg[$table."|ADDINDEX"] = "";

$db_cfg[$table."|FIELDS"] = "N_APPLICATION|DATE_CREATE|ID_TOOL|N_QUANTITY|N_UNIT_PRICE|N_CUR_PRICE|ID_ADDR_FROM|ID_ADDR_TO|ID_STOCK_DOC|ID_MOVEMENTS_TOOL|ID_USERS|EXECUTE";

$db_cfg[$table."/N_APPLICATION"] = "integer";    # ���������� ����� ������ � �������� (���)
$db_cfg[$table."/DATE_CREATE"] = "date"; # ���� �������� ��������� (���)
$db_cfg[$table."/ID_TOOL"] = "list"; # ������� �� ����������
$db_cfg[$table."/ID_TOOL|LIST"] = "db_v_reference_tool";
$db_cfg[$table."/ID_TOOL|HOLD"] = "ID_TOOL";
$db_cfg[$table."/N_QUANTITY"] = "pinteger"; # ����������
$db_cfg[$table."/N_UNIT_PRICE"] = "preal"; # ���� ������� (���)
$db_cfg[$table."/N_CUR_PRICE"] = "preal"; # ������� ��������� (���)
$db_cfg[$table."/ID_ADDR_FROM"] = "list"; # ����� ��������
$db_cfg[$table."/ID_ADDR_FROM|LIST"] = "db_v_mov_adr";
$db_cfg[$table."/ID_ADDR_FROM|HOLD"] = "ID_ADDR_FROM";
$db_cfg[$table."/ID_ADDR_FROM|LIST_WHERE"] = "ID!=125";
$db_cfg[$table."/ID_ADDR_TO"] = "list"; # ����� ����������
$db_cfg[$table."/ID_ADDR_TO|LIST"] = "db_v_mov_adr";
$db_cfg[$table."/ID_ADDR_TO|LIST_WHERE"] = "ID!=125";
$db_cfg[$table."/ID_STOCK_DOC"] = "pinteger"; # ������ �� ��������� ������������ ��������
$db_cfg[$table."/ID_MOVEMENTS_TOOL"] = "pinteger"; # ������ �� ��������
$db_cfg[$table."/ID_USERS"] = "list"; # �����
$db_cfg[$table."/ID_USERS|LIST"] = "users";
$db_cfg[$table."/EXECUTE"] = "boolean"; # �������� ��������
$db_cfg[$table."/EXECUTE|HOLD"] = "N_APPLICATION|DATE_CREATE|ID_TOOL|N_QUANTITY|N_CUR_PRICE|N_UNIT_PRICE|ID_ADDR_FROM|ID_ADDR_TO|ID_STOCK_DOC|ID_MOVEMENTS_TOOL|ID_USERS|EXECUTE";


?>