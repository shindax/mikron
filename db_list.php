<?php
//////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	© 2012 Мирошников А.В.
//
//
//////////////////////////////////////////////////////

	define("MAV_ERP", TRUE);

// ПОЕХАЛИ

	include "config.php";
	include "locale/".$lang."/lang.php";
	include "includes/database.php";
	dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset);
	//include "includes/cookie.php";
	include "includes/config.php";
	include "db_func.php";
	dbquery('SET NAMES utf8');
	$maxnumrows = 15;

///////////////////////////////////////////////////////////////

	function decodeurl($code) {
		$text = stripslashes($code);
		$search = array("@1@", "@2@", "@3@", "@4@");
		$replace = array("=", "?", "&", ".");
		$text = str_replace($search, $replace, $text);
		return $text;
	}

	function utftxt($str) {
		return iconv($html_charset,"UTF-8",$str);
	}

	function chrtxt($str) {
		return iconv($html_charset,$db_charset,$str);
	}


	$db = $_GET['db'];
	$id = $_GET['id'];
	$field = $_GET['field'];
	$val = iconv('cp1251', 'utf-8', $_GET['value']);
	$url = $_GET['url'];

	$SLURL = decodeurl($url)."&edit_list=".$db."|".$id."|".$field."|";


///////////////////////////////////////////////////////////////


// ПОИСК
	$sdb = $db_cfg[$db."/".$field."|LIST"];

	if ($db_cfg[$sdb."|LIST_SEARCH"].""!=="") {

		$listfield = $db_cfg[$sdb."|LIST_FIELD"]."";
		$listprefix = $db_cfg[$sdb."|LIST_PREFIX"]."";
		$listfield = explode("|",$listfield);

			$add_where = "";
			if ($db_cfg[$db."/".$field."|LIST_EQUAL"].""!=="") {
				$xx = explode("|",$db_cfg[$db."/".$field."|LIST_EQUAL"]);
				if (count($xx)==2) {
					$thisobj = dbquery("SELECT * FROM ".$db_prefix.$db." where (ID='".$id."')");
					if ($thisobj = mysql_fetch_array($thisobj)) {
						$add_where = $xx[0]."='".$thisobj[$xx[1]]."'";
					}
				}
			}

			$list_where = "".$db_cfg[$db."/".$field."|LIST_WHERE"];
			if ($list_where!=="") {
				$list_where = " and (".$list_where.")";
				if ($add_where!=="") $list_where = $list_where." and (".$add_where.")";
			} else {
				if ($add_where!=="") $list_where = " and (".$add_where.")";
			}

		//echo utftxt("[".$list_where."]<br>");

		$find_fields = explode("|",$db_cfg[$sdb."|LIST_SEARCH"]);

		$search = trim(strip_tags(chrtxt($val)));
		$search = substr($search, 0, 64);
		if (strlen($search)<2) $search = "";
	//	$search = preg_replace(" +", " ", $search);

		//echo utftxt($search)."<br>";

		if (($search!=="") && ($search!==" ")) {

			$sql = array();
			foreach($find_fields as $flx){
				$sql[] = "($flx LIKE '%{$search}%')";
			}
			$find_where = "WHERE (".implode(" OR ", $sql).")".$list_where;

			// Сортировка v1
			//$orderby = "length(".$find_fields[0]."), binary(".$find_fields[0].")";

			// Сортировка v2
			//$orderby1 = "length(".implode("), length(",$find_fields).")";
			//$orderby2 = "binary(".implode("), binary(",$find_fields).")";
			//$orderby = $orderby1.", ".$orderby2;

			// Сортировка v3
			$orderby = "";
			$ox = "";
			foreach($find_fields as $flx){
				$orderby .= $ox;
				$orderby .= "length($flx), binary($flx)";
				$ox = ", ";
			}

			//echo $orderby."<br>";

// shindax 12.03.2018
//			$result = dbquery("SELECT * FROM ".$db_prefix.$sdb." ".$find_where." order by ".$orderby." limit 0,".$maxnumrows);
			$result = dbquery("SELECT * FROM ".$db_prefix.$sdb." ".$find_where." order by ".$orderby );

			$numrows = mysql_num_rows($result);
			
			while($row = mysql_fetch_array($result)) {
			
				$val = FVal($row,$sdb,$listfield[0]);
				$listfield_count = count($listfield);
				for ($j=1;$j < $listfield_count;$j++) {
					$val .= $listprefix.FVal($row,$sdb,$listfield[$j]);
				}
	 
				//echo utftxt("<div class='hr'></div><a href='javascript:void(0);' onclick='if (confirm(\"".$loc["dbf21"]." - ".$val." ?\")) parent.location=\"".$SLURL.$row["ID"]."\";'>".$val."</a>");

        $str = "<div class='hr'></div><a href='javascript:void(0);' onclick='parent.location=\"".$SLURL.$row["ID"]."\";'>".$val."</a>";
				
				switch ($_GET['db'])
				{
					case 'db_shtat':
                          echo ($_GET['field'] == 'ID_special' || $_GET['field'] == 'ID_speclvl' || $_GET['field'] =='ID_resurs' ) ? iconv ('utf-8', 'cp1251', $str ) : $str ;
                          break;
						
					case 'db_itr_vremitr':
                          echo ($_GET['field'] == 'ID_users3') ? iconv ('utf-8', 'cp1251', $str ) : $str ;
                          break;
						
					case 'db_krz':
					case 'db_krz2':
                          echo ($_GET['field'] == 'ID_clients' || $_GET['field'] == 'ID_users' || $_GET['field'] == 'ID_users2' || $_GET['field'] == 'ID_DOGOVOR' || $_GET['field'] == 'ID_SOGL' || $_GET['field'] == 'ID_SPECIF' || $_GET['field'] == 'ID_SCHET' || $_GET['field'] == 'ID_INVEST') ? iconv ('utf-8', 'cp1251', $str ) : $str ;
                          break;
						  
					case 'db_tab_st':
                          echo ($_GET['field'] == 'ID_tab_pc') ? iconv ('utf-8', 'cp1251', $str ) : $str ;
                          break;
                            
					case 'db_shtat':
                          echo ($_GET['field'] == 'ID_resurs') ? iconv ('utf-8', 'cp1251', $str ) : $str ;
                          break;
                        
					case 'db_contacts':
					
                          if ($_GET['field'] == 'ID_shtat') {
							  echo iconv ('cp1251', 'utf-8', $str );
						  } else if ($_GET['field'] == 'ID_SPECIAL') {
							  echo iconv ('utf-8', 'cp1251', $str );
						  }  else if ($_GET['field'] == 'ID_shtat') {
							  echo iconv ('utf-8', 'cp1251', $str );
						  } else {
							  echo $str;
						  }
                          break;
                          
					case 'db_zak':
                          echo ($_GET['field'] == 'ID_RASPNUM' || $_GET['field'] == 'ID_users' || $_GET['field'] == 'ID_clients' || $_GET['field'] == 'ID_users2' || $_GET['field'] == 'ID_DOGOVOR' || $_GET['field'] == 'ID_SOGL' || $_GET['field'] == 'ID_SPECIF' || $_GET['field'] == 'ID_SCHET' || $_GET['field'] == 'ID_INVEST') ? iconv ('utf-8', 'cp1251', $str ) : $str ;
                          break;
                          
					case 'db_zak_req':
                          echo $str ;
                          break;
                          
					case 'db_inv':
                          echo ($_GET['field'] == 'ID_resurs') ? iconv ('utf-8', 'cp1251', $str ) : $str ;
                          break;

					case 'db_zn_zag':
                          echo ($_GET['field'] == 'ID_mat' || $_GET['field'] =='ID_sort' ) ? iconv ('utf-8', 'cp1251', $str ) : $str ;
                          break;
						  
					case 'db_hr_req':
                          echo ($_GET['field'] == 'QWEST') ? iconv ('utf-8', 'cp1251', $str ) : $str ;
                          break;
                          		

					case 'db_zn_pok':
                          echo ($_GET['field'] == 'ID_mat' || $_GET['field'] =='ID_sort' ) ? iconv ('utf-8', 'cp1251', $str ) : $str ;
                          break;
						
					case 'db_resurs':
                          echo iconv ('utf-8', 'cp1251', $str );
                          break;
						
					case 'db_files_3':
                          echo ($_GET['field'] == 'ID_clients') ? iconv ('utf-8', 'cp1251', $str ) : $str ;
                          break;
                          						
					case 'db_files_1':
                          echo ($_GET['field'] == 'ID_clients') ? iconv ('utf-8', 'cp1251', $str ) : $str ;
                          break;
                          				
					case 'db_clients':
                          echo ($_GET['field'] == 'ID_specialization') ? iconv ('utf-8', 'cp1251', $str ) : $str ;
                          break;
                          
					case 'db_edo_inout_files':
					case 'db_business_trip_records':
					case 'db_edo_inout_files_vrem':
				
                          if ( $_GET['field'] == 'ID_resurs' || $_GET['field'] == 'ID_clients' || $_GET['field'] == 'ID_clients_contacts' ) 
                            echo iconv ('utf-8', 'cp1251', $str );
                              else 
                                echo ( $_GET['field'] == 'ID_files_3' || $_GET['field'] == 'ID_files_1' ) ? iconv ('utf-8', 'cp1251', $str ) : $str ;

                          break;
					default: 
                          echo $str ;
				}
			}

// shindax 12.03.2018
//			if( $numrows>=$maxnumrows ) 
//          echo ("<div class='hr'></div><center>- - -</center>");
		}
	}

?>