<?php
error_reporting( 0 );
require_once( "functions.php" );

$field = $_POST['field'];
$str = "<option value='0'>...</option>";

try
{
    $query = "
                      SELECT
                      okb_db_plan_fact_carry_causes.cause cause,
                      okb_db_plan_fact_carry_causes.id id
                      FROM
                      okb_db_plan_fact_direction_stages
                      INNER JOIN okb_db_plan_fact_carry_causes ON okb_db_plan_fact_direction_stages.id = okb_db_plan_fact_carry_causes.direction_stage_id
                      WHERE
                      okb_db_plan_fact_direction_stages.field='$field'
                      HAVING
                      LENGTH( cause ) > 0
                      ORDER BY id
                      " ;
    $stmt = $pdo->prepare( $query );
    $stmt->execute();
}
catch ( PDOException $e )
{
   die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
}
  while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
  {
      $id = $row -> id ;
      $cause = $row ->  cause;
      $str .= "<option value='$id'>$cause</option>";
  }

echo conv( $str );
