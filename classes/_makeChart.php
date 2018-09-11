<?php

//error_reporting( E_ALL );
//ini_set('display_errors', true);

error_reporting( 0 );
ini_set('display_errors', false );


 // Standard inclusions
 require_once("pChart/pData.class");
 require_once("pChart/pChart.class");

$background = 255 ;

function MakeChart( $img_name, $orders )
{
  global   $background ;

    $data = [ 100 ];
    $data_names = [ 'Нет заданий' ];
    $total_hour_count = 0 ;

     if( count( $orders ) )
     {
          $data = [];
          $data_names = [];

          foreach( $orders AS $order )
              {
                  $total_hour_count  += $order['hour_count'];
                  $data [] = $order['hour_count'];
//                  $data_names [] = wordwrap( $order['order_name'] , 100, "\n");
                  $data_names [] = wordwrap( iconv( "Windows-1251", "UTF-8", $order['order_name'] ) , 100, "\n");
              }

               if( $total_hour_count == 0 )
               {
                     $data = [];
                     $perc = 100 / count( $orders );
                     foreach( $orders AS $order )
                           $data [] = $perc;
               }
      }

 // Dataset definition
 $DataSet = new pData;
 $DataSet->AddPoint( $data,"Serie1");
 $DataSet->AddPoint( $data_names,"Serie2");
 $DataSet->AddAllSeries();
 $DataSet->SetAbsciseLabelSerie("Serie2");

 // Initialise the graph
 $chart = new pChart(700,220 + count( $orders ) * 10 );
 $chart->loadColorPalette( $_SERVER['DOCUMENT_ROOT']."/project/working_calendar/softtones.pal");
 $chart->setFontProperties( $_SERVER['DOCUMENT_ROOT']."/project/working_calendar/Fonts/tahoma.ttf",10);
 $chart->drawFilledRoundedRectangle(7,7,383,193,5,$background,$background,$background);
 $chart->drawRoundedRectangle(5,5,395,195,5,$background,$background,$background);

 // Draw the pie chart
 $chart->AntialiasQuality = 0;
 $chart->setShadowProperties(2,2,200,200,200);
 $chart->drawFlatPieGraphWithShadow($DataSet->GetData(),$DataSet->GetDataDescription(),130,125,80,PIE_PERCENTAGE,8);
 $chart->clearShadow();

 $chart->drawPieLegend(280,15,$DataSet->GetData(),$DataSet->GetDataDescription(),230,230,230);

 $chart->Render( "$img_name.png");
}
