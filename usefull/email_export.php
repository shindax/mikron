<script type='text/javascript' charset='utf-8' src='.././uses/jquery.js'></script>
<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/wh_functions.php" );

$user_id = $user['ID'];

export();

function find_mikron_email( $str )
{
	preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $str, $matches );
	$matches = $matches[0];
	foreach ( $matches AS $key => $value ) 
	{
		$pos = strpos( $value, "@okbmikron.ru");
		if( $pos === false )
			unset( $matches[ $key ] );
	}
	$str = join( ", ", $matches );
	
	return $str ;
}

function export()
{
	global $pdo;
    $line = 1 ;
    $file_name = 'peoples';
    echo __DIR__;
    $full_file_name = __DIR__."/$file_name.csv" ;
    $data = [];

            try
            {
                $query = "	SELECT 
                			shtat.NAME AS name, 
                			res.II AS first_name,
                			res.FF AS second_name,
                			res.OO AS surname,
                			res.EMAIL AS email, 
                			spec.NAME AS spec
							FROM okb_db_shtat AS shtat
							LEFT JOIN okb_db_resurs AS res ON res.ID = shtat.ID_resurs
							LEFT JOIN okb_db_special AS spec ON spec.ID = shtat.ID_special
							WHERE 
							res.TID = 0
							AND
							shtat.NAME <> ''
							AND
							shtat.NAME NOT LIKE '%Вакансия%'
							ORDER BY shtat.BOSS DESC, shtat.NAME";

                $stmt = $pdo->prepare( $query );
                $stmt->execute();
            }
            catch (PDOException $e)
            {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
            }
            while( $row = $stmt -> fetch( PDO::FETCH_OBJ ) )
            	{
            		$email = find_mikron_email( $row -> email );
            		if( strlen( $email ))
					$data[] = [ 
									'name' => _conv( "{$row->second_name} {$row->first_name} {$row->surname}" ),
									'email' => $email,
									'spec' => _conv( $row -> spec )
								];
            	}

    _debug( $data );

    $str = _conv(";Перечень сотрудников".PHP_EOL.PHP_EOL."ФИО;Должность;email".PHP_EOL.PHP_EOL);

    file_put_contents( $full_file_name, $str );

    foreach( $data AS $key => $val )
    {
		$substr = norm( $val['name'] );
		$substr .= norm( $val['spec'] );
		$substr .= norm( $val['email'] );		
		$str .= $substr.PHP_EOL;
    }

	file_put_contents( $full_file_name, $str );

	return  count( $data )." items exported";

} // function export( $dataset, $id )

function norm( $str )
{
	return "$str;" ;
}

function _conv( $str )
{
    return iconv( "UTF-8", "Windows-1251",  $str );
}
