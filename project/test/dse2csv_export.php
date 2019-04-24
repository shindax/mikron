<?php
error_reporting( E_ALL );
error_reporting( 0 );

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.AbstractBinaryTree.php" );

function conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}

// Утилита вывода в CSV-формат дерева ДСЕ по корневому элементу

$user_query = "
                SELECT 
                zd.ID AS ID, 
                zd.PID AS PID,
                zd.LID AS LID,
                zd.NAME AS NAME,
                zd.OBOZ AS OBOZ,
                zd.PERCENT AS PERCENT,
                zd.RCOUNT AS RCOUNT,
                coopd.count AS coop_count,                
                zd2.name AS LNAME,
                zd2.OBOZ AS LOBOZ
                FROM okb_db_zakdet zd
                LEFT JOIN okb_db_zakdet zd2 ON zd2.ID = zd.LID
                LEFT JOIN okb_db_operitems oi ON oi.ID_zakdet = zd.ID
                LEFT JOIN okb_db_operations_with_coop_dep coopd ON coopd.oper_id = oi.ID                
                WHERE 1 
                ORDER BY zd.ORD
                ";


$user_query =  "SELECT 
                zd.ID AS ID, 
                zd.PID AS PID,
                zd.LID AS LID,
                zd.NAME AS NAME,
                zd.OBOZ AS OBOZ,
                zd.PERCENT AS PERCENT,
                zd.RCOUNT AS RCOUNT,
                MAX( coopd.count ) AS coop_count,                
                zd2.name AS LNAME,
                zd2.OBOZ AS LOBOZ
                FROM okb_db_zakdet zd
                LEFT JOIN okb_db_zakdet zd2 ON zd2.ID = zd.LID
                LEFT JOIN okb_db_operitems oi ON oi.ID_zakdet = zd.ID
                LEFT JOIN okb_db_operations_with_coop_dep coopd ON coopd.oper_id = oi.ID                
                WHERE 1 
                GROUP BY ID
                ORDER BY zd.ORD";

$krz_id = 558060;

$el = new AbstractBinaryTree( $pdo, "okb_db_zakdet", "ID", "PID", [ "LID", "NAME", "OBOZ", "PERCENT", "RCOUNT"], NULL, $user_query );
$arr = $el -> GetLocMapTree( $krz_id );
// _debug( $arr, 1 );
$arr = GetIdsFromRoot( $arr );
_debug( $arr );

$str = conv("ДСЕ").";".conv("Чертеж").";".conv("Кол-во на заказ").";".conv("% выполнения").conv("по кооп.").PHP_EOL;
foreach( $arr AS $val )
    $str .= "$val".PHP_EOL;

$file = '___.csv';
file_put_contents($file, $str );

function cyclic_to_analysis( $dataset, &$to_analysis, $level )
{
    foreach( $dataset AS $key => $value )
    {
        $id = $value[ "ID" ];
        $name = conv( $value[ "NAME" ] );
        $lname = conv( $value[ "LNAME" ] );        
        $oboz = conv( $value[ "OBOZ" ] );
        $loboz = conv( $value[ "LOBOZ" ] );        
        $rcount = $value[ "RCOUNT" ];
        $percent = $value[ "PERCENT" ];
        $coop_count = $value[ "coop_count" ] ? $value[ "coop_count" ] : "";
        $dots = str_repeat( ".", $level );

        if( strlen( $name ) == 0 )
        {
            $name = $lname ;
            $oboz = $loboz ;
        }

        $to_analysis[] = $dots."$name;$oboz;$rcount;$percent;$coop_count";

        if( isset( $value['childs']) )
                $dataset = cyclic_to_analysis( $value['childs'], $to_analysis, $level + 1 );
    }
    return $dataset;
}

function GetIdsFromRoot( $dataset )
{
        $level = 0 ;
        $to_analysis = [ conv( $dataset[ "NAME" ]).";".conv( $dataset[ "OBOZ" ] ).";".$dataset[ "PERCENT" ] ] ;
        if( isset( $dataset['childs']) )
              cyclic_to_analysis( $dataset['childs'], $to_analysis, $level + 1 ) ;
      
        return  array_unique ( $to_analysis );
}
