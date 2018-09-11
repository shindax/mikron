<?php
error_reporting( E_ALL );     
require_once("db_config.php");
include("excelwriter.inc.php");

global $mysqli;

   $excel=new ExcelWriter("myXls.xls");
    if($excel==false)    
        echo $excel->error;

        $query = "SELECT NAME FROM okb_db_resurs WHERE FOTO = '' AND TID = 0 ORDER BY NAME" ;

        $result = $mysqli -> query( $query );

        if( ! $result ) 
            exit("Connection error in ".__FILE__." at ".__LINE__." line. <br />Query is : $query <br />".$mysqli->error); 
        
            $prev_cl_name = '';
            while( $row = $result -> fetch_object() )
            {
              $name = $row -> NAME ;
              if( $name == '' )
                continue ;
              
              $myArr=array( $name );
              $excel->writeLine( $myArr );
            }
       

?>