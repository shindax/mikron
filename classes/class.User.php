
<?php
require_once( "db.php" );

class User
{
  public static $convert = 0 ;

  private $login_id = 0 ;
  private $resource_id = 0 ;
  private $name = 0 ;
  private $department = 0 ;

  public function __construct( $login_id = 0 )
  {
    global $pdo;
    $this -> login_id = $login_id ;

    if( $login_id )
    {
          try
          {
                  $query =
                  "SELECT ID, NAME FROM `okb_db_resurs`
                  WHERE
                  ID_users = $login_id
                  " ;
                  $stmt = $pdo->prepare( $query );
                  $stmt -> execute();
              }
              catch (PDOException $e)
              {
                die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
              }

              $row_count = $stmt -> rowCount() ;

                 if( $row_count )
                   if( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
                      {
                          $this -> resource_id = $row -> ID ;
                          $this -> name = $row -> NAME ;
                      }
       }
    else
    {
      $this -> resource_id = 1 ;
      $this -> name = 'administrator' ;
     }
  }
  public function GetResourceID()
  {
     return $this -> resource_id ;
  }
    public function __toString()
  {
    if( self::$convert )
      return $this -> name;
        else
          return $result = iconv( "Windows-1251", "UTF-8", $this -> name );
  }

  public static function GetUsersArrByDepartment( $dep_id )
  {
    global $pdo ;
    $arr = [];

    try
    {
        $query ="SELECT shtat.ID_resurs
                  FROM okb_db_shtat shtat
                  LEFT JOIN okb_db_otdel otdel ON otdel.ID = shtat.ID_otdel
                  WHERE 
                  shtat.ID_otdel IN ( $dep_id )
                  OR
                  otdel.PID IN ( $dep_id )
                  ORDER BY shtat.NAME";
        $stmt = $pdo->prepare( $query );
        $stmt -> execute();
    }
    catch (PDOException $e)
    {
       die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
    }

    $row_count = $stmt -> rowCount() ;

    if( $row_count )
      while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
             if( $row -> ID_resurs )
                 $arr[] = $row -> ID_resurs;
    return $arr ;
  }


}