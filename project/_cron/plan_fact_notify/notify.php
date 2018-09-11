<?php
error_reporting( E_ALL );
require_once( "functions.php" );
// Подготовка
// PD1 - КД
// PD2 - Нормы расхода
// PD3 - МТК

// Комплектация
// PD4 - Проработка
// PD7 - Поставка

// Производство
// PD12 - Дата нач.
// PD8  - Дата оконч.
// PD13 - Инструмент и остнастка

// Коммерция
// PD9  - Предоплата
// PD10 - Оконч.расчет
// PD11 - Поставка

$now = new DateTime("00:00:00 "."now");

$group_heads =
array_merge(
                      getHeadResponsiblePersonsID( PREPARE_GROUP ) ,
                      getHeadResponsiblePersonsID( EQUIPMENT_GROUP ),
                      getHeadResponsiblePersonsID( PRODUCTION_GROUP ),
                      getHeadResponsiblePersonsID( COMMERTION_GROUP )
) ;


$prepare_group = getResponsiblePersonsID( PREPARE_GROUP ) ;
$equipment_group = getResponsiblePersonsID( EQUIPMENT_GROUP ) ;
$production_group = getResponsiblePersonsID( PRODUCTION_GROUP ) ;
$commertion_group = getResponsiblePersonsID( COMMERTION_GROUP ) ;
$all_groups = array_merge( $prepare_group, $equipment_group, $production_group, $commertion_group );

// for debug
// $group_heads = [1];
// $all_groups = [1];
// $prepare_group = [1];
// $equipment_group = [1];
// $production_group = [1];
// $commertion_group = [1];

      try
      {
        $query = "
                        SELECT
                        okb_db_zak.ID  id,
                        okb_db_zak.ID_stage stage,

                        okb_db_zak.PD1 pd1,
                        okb_db_zak.PD2 pd2,
                        okb_db_zak.PD3 pd3,

                        okb_db_zak.PD4 pd4,
                        okb_db_zak.PD7 pd7,

                        okb_db_zak.PD12 pd12,
                        okb_db_zak.PD8 pd8,
                        okb_db_zak.PD13 pd13,

                        okb_db_zak.PD9 pd9,
                        okb_db_zak.PD10 pd10,
                        okb_db_zak.PD11 pd11

                        FROM
                        okb_db_zak

                        WHERE
                        okb_db_zak.EDIT_STATE = 0
                        ";

          $stmt = $pdo->prepare( $query );
          $stmt -> execute();
      }
            catch (PDOException $e)
            {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
            }
           while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
           {
                $id = $row -> id ;
                $stage = $row -> stage ;

                checkItem( $now, $id, $stage, getState( $row -> pd1 ) ,getLastDate( $row -> pd1 ) , "pd1", PREPARE_GROUP );
                checkItem( $now, $id, $stage, getState( $row -> pd2 ) ,getLastDate( $row -> pd2 ) , "pd2", PREPARE_GROUP );
                checkItem( $now, $id, $stage, getState( $row -> pd3 ) ,getLastDate( $row -> pd3 ) , "pd3", PREPARE_GROUP );

                checkItem( $now, $id, $stage, getState( $row -> pd4 ) ,getLastDate( $row -> pd4 ) , "pd4", EQUIPMENT_GROUP );
                checkItem( $now, $id, $stage, getState( $row -> pd7 ) ,getLastDate( $row -> pd7 ) , "pd7", EQUIPMENT_GROUP );

                checkItem( $now, $id, $stage, getState( $row -> pd12 ) ,getLastDate( $row -> pd12 ) , "pd12", PRODUCTION_GROUP );
                checkItem( $now, $id, $stage, getState( $row -> pd8 ) ,getLastDate( $row -> pd8 ) , "pd8", PRODUCTION_GROUP );
                checkItem( $now, $id, $stage, getState( $row -> pd13 ) ,getLastDate( $row -> pd13 ) , "pd13", PRODUCTION_GROUP );

                checkItem( $now, $id, $stage, getState( $row -> pd9 ) ,getLastDate( $row -> pd9 ) , "pd9", COMMERTION_GROUP );
                checkItem( $now, $id, $stage, getState( $row -> pd10 ) ,getLastDate( $row -> pd10 ) , "pd10", COMMERTION_GROUP );
                checkItem( $now, $id, $stage, getState( $row -> pd11 ) ,getLastDate( $row -> pd11 ) , "pd11", COMMERTION_GROUP );
           }

