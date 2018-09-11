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

echo "<input id='input' />&nbsp<span id='span'></span>";
?>
<script>
$( function()
{
  $('#input').unbind('keyup').bind('keyup', keyupFunc );
});

function keyupFunc()
{
    var id = $( this ).val();

    $.post(
        "project/test/ajax.test.php",
        {
            id   : id ,
        },
        function( data )
        {
          $('#span').text( data );
        }
    );
}

</script>