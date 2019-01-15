<?php
error_reporting( 0 );
error_reporting( E_ALL );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.DecisionSupportSystemDiscussion.php" );

global $pdo;

$id = $_POST['id'];
$res_id = $_POST['res_id'];

function conv( $str )
{
   global $dbpasswd;
    
    if( strlen( $dbpasswd ) )
        return iconv( "UTF-8", "Windows-1251",  $str );
        else
          return $str;
}

$str = '';

     $disc = new DecisionSupportSystemDiscussion( $pdo,  $res_id, $id );
     $str .= $disc -> GetHtml() ;
     $id_list = join( ",", $disc -> GetIDs() );

    if( strlen( $id_list ) )
    {
      $disc = [];
        try
        {
            $query ="   SELECT dss_discussions.id, dss_discussions.seen_by, dss_projects.team
                        FROM `dss_discussions` 
                        LEFT JOIN dss_projects ON dss_projects.id = dss_discussions.project_id
                        WHERE
                        dss_discussions.id IN ( $id_list )
                    ";

            $stmt = $pdo->prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
            die("Can't get data: " . $e->getMessage().". Query : $query");
        }

        while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
        {
           $arr = json_decode( $row -> seen_by );
           if( is_null( $arr ) )
              $arr = [];

           $team_arr = json_decode( $row -> team );
           if( is_null( $team_arr ) )
              $team_arr = [];

          if( in_array( $res_id, $team_arr ) )
            $disc[ $row -> id ] = $arr;
        }

        foreach( $disc AS $key => $val )
        {
           if( !in_array( $res_id, $val ) )
                  {
                      $arr[] = $res_id ;
                      $arr = array_unique( $arr );
                      try
                      {
                          $query ="   UPDATE `dss_discussions`
                                      SET seen_by = '".json_encode( $arr )."'
                                      WHERE
                                      id = $key
                                  ";

                          $stmt = $pdo->prepare( $query );
                          $stmt->execute();
                      }
                      catch (PDOException $e)
                      {
                          die("Can't get data: " . $e->getMessage().". Query : $query");
                      }                    
                    }
      } // foreach( $disc AS $key => $val )
    } //if( strlen( $id_list ) )

echo $str;

