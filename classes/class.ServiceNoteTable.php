<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.ServiceNote.php" );

class ServiceNoteTable extends ServiceNote
{
  protected $can_edit ;

	public function __construct( $pdo, $id, $can_edit = 0 )
	{
		parent :: __construct( $pdo, $id );
    $this -> can_edit = $can_edit;
	}

	public static function GetTableHead()
    {

        $table_begin = "   <table class='table tbl'>
                           <col width='1%'>
                           <col width='7%'>
                           <col width='2%'>
                           <col width='50%'>
                           <col width='1%'>
                           <col width='10%'>
                           <col width='1%'>";

        $table_begin .= "<thead>
                            <tr class='first'>
                            <td class='Field AC'>".conv("Записка №")."</td>
                            <td class='Field AC'>".conv("Инициатор")."</td>
                            <td class='Field AC'>".conv("Дата")."</td>
                            <td class='Field AC'>".conv("Описание")."</td>                            
                            <td class='Field AC'><div><img src='uses/film.png' class='pict_img' /></div></td>
                            <td class='Field AC'>".conv("Получатели")."</td>
                            <td class='Field AC'>".conv("Выполнение")."</td>
                            </tr>
                            </thead>
                           ";
        return $table_begin ;
	}

	public function GetTableContent( $class = '' )
	{
    $option = $this -> data['note_data']['executed'] ? "checked" : "";
    $row_class = $this -> data['note_data']['executed'] ? "executed" : "";
    $hidden = "hidden";

    if( strlen( $this -> data['note_data']['note_scan_name'] ))
    {
        $img_class = "view_pict_img";
        $src = "uses/film.png";
        $title = conv( "Посмотреть документ");
        $hidden = "";
    }
    else
    {
        $title = conv( "Загрузить документ");

        if( $this -> can_edit )
        {
            $img_class = "add_pict_img";
            $src = "uses/addf_img.png";
        }
        else
        {
            $img_class = "";
            $src = "uses/addf_dis.png";
        }

    }
   
    if( !$this -> can_edit )
    {
        $option .= " disabled";
        $hidden = "hidden";        
    }

    $date = new DateTime( $this -> data['note_data']['creation_date'] );
    $creation_date = $date ->format( 'd.m.Y' );

    $str = "<tr data-id='".( $this -> id )."' class='data-class $class $row_class'>
            <td class='Field AC'>".conv( $this -> data['note_data']['note_number'])."</td>
            <td class='Field AC'>".conv( $this -> data['note_data']['creator_name'] )."</td>
            <td class='Field AC'>$creation_date</td>
            <td class='Field textarea'><textarea $option>".conv( $this -> data['note_data']['description'] )."</textarea></td>
            <td class='Field AC'><div><img data-img='".( $this -> data['note_data']['note_scan_name'] )."' title='$title' src='$src' class='$img_class' /><img class='del_img $hidden' title='".conv( "Удалить документ")."' src='uses/del.png' /></div></td>";

    $str .= "<td class='Field AC'>";

    if( $this -> can_edit )
        $str .= "<a class='receivers' href='#'>".conv("Получатели:<br>")."</a>";
            else
                $str .= "<span class='receivers'>".conv("Получатели:<br>")."</span>";

    $str .= "<div class='receivers_div'>";
    foreach ( $this -> data['receivers'] as $key => $value ) 
      $str .= "<span class='receiver_span' data-id='$key'>".conv( "$value" )."</span>";
    $str .= "</div></td>";  

    $str .= "<td class='Field AC'><input type='checkbox' $option/></td>";

    return $str ;    
	}

	public static function GetTableEnd()
	{
		$str = "</table>"; 
		return $str ;
	}

	public function GetTable()
	{
		$str = self :: GetTableHead();
		$str .= $this -> GetTableContent();
		$str .= self :: GetTableEnd();
		return $str ;
	}
}