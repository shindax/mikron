<link rel="stylesheet" href="/project/reports/krz_to_excell_report/css/style.css">
<script type="text/javascript" src="/project/reports/krz_to_excell_report/js/krz_to_excell_report.js"></script>

<?php
error_reporting( E_ALL );     
require_once("db.php");
require_once("excelwriter.inc.php");
require_once("class.krz_collection.php");

$krz = new krz_collection( $dblocation, $dbname, $dbuser, $dbpasswd );

//   $excel=new ExcelWriter("project/reports/krz_to_Excell_report/myXls.xls");
//    if($excel==false)
//        echo $excel->error;
//
//    $str = '';
//
//        $query = "SELECT
//                  krz.NAME krz_NAME,
//                  det.NAME det_NAME,
//                  det.OBOZ det_OBOZ,
//                  cl.NAME cl_NAME,
//                  of.FILENAME of_file,
//                  of.TIP_FAIL of_tf
//                  FROM okb_db_krz krz
//                  INNER JOIN okb_db_clients cl ON cl.ID = krz.ID_clients
//                  INNER JOIN okb_db_krzdet det ON det.ID_krz = krz.ID
//                  INNER JOIN okb_db_edo_inout_files of ON of.ID_krz = krz.ID
//                  WHERE 1
//                  ORDER BY cl.NAME, krz.NAME" ;
//
//
//        $result = $mysqli -> query( $query );
//
//        if( ! $result )
//            exit("Connection error in ".__FILE__." at ".__LINE__." line. <br />Query is : $query <br />".$mysqli->error);
//
//        if( $result -> num_rows )
//        {
//            $str .= "<table class='tbl'>";
//            $prev_cl_name = '';
//            while( $row = $result -> fetch_object() )
//            {
//              $krz_name = $row -> krz_NAME ;
//              $det_name = $row -> det_NAME ;
//              $det_oboz = $row -> det_OBOZ ;
//              $cl_name  = $row -> cl_NAME ;
//              $of_file_name = $row -> of_file ;
//              $of_file_type = $row -> of_tf ;
//
//              if( $of_file_name == '' || $of_file_type != 1 )
//                $of_file  = '';
//                 else
//                  $of_file  = "http://okbmikron/project/63gu88s920hb045e/db_edo_inout_files@FILENAME/".$of_file_name ;
//
//                if( $cl_name == $prev_cl_name )
//                     $outname = '' ;
//                        else
//                        {
//                          $outname = $prev_cl_name = $cl_name;
//
//                          $myArr=array( $outname );
////                          $excel->writeLine( Array() );
//                          $excel->writeLine( $myArr , 'company');
//
//                            $str .= "<tr><td class='Field' colspan='4'>$outname</td></tr>";
//
//                        }
//
//
//
//                $str .= "<tr><td>$krz_name</td><td>$det_name</td><td>$det_oboz</td><td>$of_file</td></tr>";
//
//              $myArr=array( $krz_name,$det_name,$det_oboz,$of_file );
//              $excel->writeLine( $myArr );
//
//              }
//            $str .= '</table>';
//        }
//
//echo $str ;

?>