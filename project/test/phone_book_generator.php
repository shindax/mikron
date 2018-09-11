<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
global $pdo ;

function debug( $arr , $conv = 0 )
{
    $str = print_r($arr, true);
    if( $conv )
        $str = conv( $str );
    echo '<pre>'.$str.'</pre>';
}

function conv( $str )
{
//    return iconv( "UTF-8", "Windows-1251",  $str );
    return $str ;
}

function GetResourcesData()
{
	global $pdo;
	try
	{
	    $query = "	SELECT 
	    			res.ID AS ID,
					res.NAME AS NAME,
					res.FF AS FF,
					res.II AS II,
					res.OO AS OO,
					res.FOTO AS photo,
					res.TEL AS TEL,
					res.EMAIL AS EMAIL,
					special.NAME AS special,
					special.ID AS special_id,
					otdel.NAME AS department 
	    			FROM `okb_db_resurs` res
	    			INNER JOIN `okb_db_shtat` shtat ON shtat.ID_resurs = res.ID 
	    			INNER JOIN `okb_db_special` special ON special.ID = shtat.ID_special 
	    			INNER JOIN `okb_db_otdel` otdel ON otdel.ID = shtat.ID_otdel 
	    		  	WHERE 
	    		  	res.DATE_TO = 0 
	    		  	AND 
	    		  	res.TID <> 1
	    		  	AND
	    		  	res.ID <> 0
	    		  	";

	    $stmt = $pdo -> prepare( $query );
	    $stmt -> execute();
	}
	catch (PDOException $e)
	{
	  die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());

	}

	$emp_arr = [];

	while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
	{
		$id = $row -> ID ;
		$phones = $row -> TEL;

		$name = $row -> NAME ;
		$lastname = $row -> FF ;
		$firstname = $row -> II ;
		$surname = $row -> OO ;
		$special = $row -> special ;
		$special_id = $row -> special_id;
		$department = $row -> department ;
		$photo = $row -> photo ;

		if( $special_id == 64 || $special_id == 16 )
			$special .= " ($department)";

		if( $id && 
			$id != 428 // Шендаков
		  )
		{
			$phone_arr = ProcessPhones( $phones );
			if ( count( $phone_arr ) )
			{
				$phone = $phone_arr[0];
				$emp_arr[ $id ]	=
				[
					'id' => $id,
					'name' => $name,					
					'firstname' => $firstname,
					'surname' => $surname,
					'lastname' => $lastname,
					'special' => $special,
					'phone' => $phone,
					'photo' => $photo
				];
			}
		}
	}
	return $emp_arr ;
}

function ProcessPhones( $string )
{
	$string = preg_replace('/[\s()@.,;\-a-zA-Zа-яА-Я:\\\\\/]/u', '', $string);

	$substring = '';
	$phone_arr = [];

	$curpos = 0 ;

	for( $i = 0 ; $i < strlen( $string ) ; $i++ )
	{
		switch( $string[ $curpos ] )
		{
			case "8" : 
						$substring = "+7".substr ( $string, $curpos+1, 10 );
						$curpos += 11 ;
						$i += $curpos - 1 ;
						break ;
			case "2" : 
						$substring = substr ( $string, $curpos, 7 );
						$curpos += 7 ;
						$i += $curpos - 1 ;
						break ;
			case "+" : 
						$substring = substr ( $string, $curpos, 12 );
						$curpos += 12 ;
						$i += $curpos - 1 ;
						break ;
			case "7" : 
						$substring = "+".substr ( $string, $curpos, 12 );
						$curpos += 12 ;
						$i += $curpos - 1 ;
						break ;
		}

		$phone_arr[] = $substring ;
	}

 return $phone_arr ;
}

$arr = GetResourcesData(); 

$cards = "";
$i = 0 ;

foreach( $arr AS $item )
{
echo $item['id']." : ".$item['phone']."<br>";

$name = $item['name'];
$firstname = $item['firstname'];
$surname = $item['surname'];
$lastname = $item['lastname'];
$phone = $item['phone'];
$special = $item['special'];

// $file = $_SERVER['DOCUMENT_ROOT']."/".$item['photo'];
// $type = strtoupper( pathinfo($file, PATHINFO_EXTENSION) );
// $data = file_get_contents($file);
// $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
// echo $type."<br>";
// //PHOTO;ENCODING=B;TYPE=$type:$base64

$cards .= 
"BEGIN:VCARD
VERSION:3.0
N:;$firstname;$surname;$lastname;
FN:$name
TEL;CELL:$phone
ORG:ООО 'ОКБ Микрон'
TITLE:$special
END:VCARD
";
}

$file = 'D:/people.vcf';
file_put_contents( $file, $cards );

//debug( $arr );

