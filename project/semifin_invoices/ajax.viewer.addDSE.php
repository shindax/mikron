<?php
require_once( "functions.php" );

$line = $_POST['line'];
$option = GetSemifinishedStoreType( 'option' );

        $str = "<tr class='order_row ".( $line %2 ? 'active' : '')."warning'>
                    <td class='AC'>$line</td>
                    <td><span class='dse_name hidden'></span><input class='dse_name_input'</td>
                    <td class='AC'><span class='order_name hidden'></span><input class='order_name_input' /></td>
                    <td class='AC'><span class='draw_name'></span></td>
                    <td><input  class='part_num' /></td>
                    <td class='AC'><input class='count' /></td>
                    <td class='AC'><input class='transfer_place' /></td>
                    <td class='AC'><select class='storage_time'>$option</select>
                    </td>
                    <td><input class='note' /></td>
                    </tr>";

//echo iconv("Windows-1251", "UTF-8",  $str );
echo $str ;

