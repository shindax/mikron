<script type="text/javascript" src="/project/presense_in_shift_orders/js/presense_in_shift_orders.js"></script>

<style>
div.inner
{
  display : flex;
  flex-direction : row ;
  justify-content: space-between;
  align-items : center;
}

div.inner:hover
{
  background-color: #6495ED;
  cursor: pointer;
}

.name_class_0
{
  background: #FFFAA4 ;
}

.name_class_1
{
  background: #A8E8FF ;
}

.name_class_2
{
  background: #A8FFBC ;
}

.name_class_3
{
  background: #E8E89C ;
}

.name_class_5
{
  background: #E8CD9C ;
}

.name_class_4
{
  background: #FDB3FF ;
}

</style>

<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/db.php" );

    function conv( $str )
    {
        return iconv( "UTF-8", "Windows-1251",  $str );
    }

global $pdo, $user;

switch( $user['ID'] )
{
  case 1:
  case 59:
  case 121:
  case 179:
  case 172:
  case 13:
  case 84:
                  $disabled = ''; break ;
  default :
                  $disabled = 'disabled'; break ;
}

        try
        {
            $query = "SELECT ID, NAME,  presense_in_shift_orders FROM `okb_db_shtat` WHERE 1 ORDER BY NAME";
            $stmt = $pdo -> prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. In function : ".__FUNCTION__." Can't get data : " . $e->getMessage());
        }


$out_str = "<h2>".conv( "Перечень работников получающих сменные задания")."</h2><br><table class='tbl'>";


        // Multiple record

      $i = 0 ;

      $first_char = 0 ;
      $cur_char = '' ;
      $even_odd = 0 ;

        while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
        {
          $name = conv( $row -> NAME );


          if( strlen( $name ))
            $first_char = $name[0];
          
          if( strlen( $name ) && ( $first_char != $cur_char ))
          {
            $cur_char = $first_char ;
            $even_odd ++;
          }

          $class = "name_class_".( $even_odd % 5 );

          $id = $row -> ID ;
          if( $row -> presense_in_shift_orders )
            $checked = "checked" ;
              else
                  $checked = "" ;

          if( strlen( $name ) && $name != conv( "Вакансия ..") )
          {

          if( $i == 0 )
            $out_str .= "<tr>";

                   $out_str .= "<td class='field'><div class='inner $class'><span>$name</span><input id='$id' type='checkbox' $checked $disabled/></div></td>";

              if( ++$i == 10 )
                  {
                    $out_str .= "</tr>";
                    $i = 0 ;
                  }

          }

       }

$out_str .= "</table>";

echo $out_str ;
