<?php

function MakePie( $done )
{
  if( !$done )
    $done = 0 ;
    
  $img_name = "pie_".$done."_perc";

      $w = 16;
      $h = 16;
      $de = 0 ;
      $img = imagecreatetruecolor($w, $h);
      imagesavealpha($img,true);    // альфа-канал для прозрачности
      imagefill($img ,0,0,IMG_COLOR_TRANSPARENT);

      $colors = [
                        imagecolorallocate($img, 0, 255, 0),
                        imagecolorallocate($img, 150, 150, 150)
                      ];

          imagefilledarc($img, $w/2 , $h/2, $w, $h, $de, $de += round($done/100 * 360), $colors[0], IMG_ARC_PIE );
          imagefilledarc($img, $w/2, $h/2, $w, $h, $de, $de += round((100 - $done)/100 * 360), $colors[1], IMG_ARC_PIE );

      if( ! file_exists ( $_SERVER['DOCUMENT_ROOT']."/uses/pie/$img_name.png" ) )
        imagepng( $img, $_SERVER['DOCUMENT_ROOT']."/uses/pie/$img_name.png");
//      print '<h4>Circle diagramm</h4><img style="margin: 0px 0px" src="/pirog.png" />';
      return "<img style='margin: 0px 10px 0px 0px' src='/uses/pie/$img_name.png' />";
}

// for( $i = 0 ; $i < 100 ; $i += 10)
// {
//   $img = MakePie( $i, "pie$i" );
//   echo "<div style='padding-left: 50px;'>$img</div>";
// }