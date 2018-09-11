<?php

class EntranceControl
{
    private $pdo;
    private $id;
    private $date;
    private $number;

    private $proc_type_id;
    private $proc_type_name;

    private $client_id;
    private $client_name ;

    private $proc_list = [];
    private $image ;
    private $image_deleting ;
    private $filter ;

    public function __construct( $pdo, $id, $filter = '' )
    {
        $this -> pdo = $pdo;
        $this -> id = $id;
        $this -> image_deleting = 0 ;
        $this -> filter = $filter ;
        $this -> CollectData();
    }

    public function GetData()
    {
        return $this -> proc_list;
    }

    private function CollectOperations()
    {
        $filter = '';
        if( strlen( $this -> filter ) )
        {
            $filter = " ( okb_db_zak.NAME LIKE '%".( $this -> filter )."%' OR okb_db_zakdet.NAME LIKE '%".( $this -> filter )."%') AND ";
        }

        try
        {
            $query ="
                        SELECT
                        okb_db_oper.`NAME` AS operation_name,
                        okb_db_zakdet.`NAME` AS dse_name,
                        okb_db_zakdet.OBOZ AS dse_draw,
                        okb_db_zak.`NAME` AS zak_name,
                        okb_db_zak.DSE_NAME AS zak_dse_name,
                        okb_db_zak.DSE_OBOZ AS zak_dse_draw,
                        okb_db_entrance_control_items.count,

                        okb_db_entrance_control_items.id AS ent_cont_id,
                        okb_db_entrance_control_items.operation_id,

                        okb_db_entrance_control_items.dse_name AS ent_cont_dse_name,
                        okb_db_entrance_control_items.dse_draw AS ent_cont_dse_draw,

                        okb_db_entrance_control_items.inwork_state AS inwork_state,
                        okb_db_entrance_control_items.reject_state AS reject_state,
                        okb_db_entrance_control_items.rework_state AS rework_state,
                        okb_db_entrance_control_items.pass_state AS pass_state,

                        okb_db_zakdet.ID AS item_id,
                        okb_db_zak.ID AS zak_id
                        FROM
                        okb_db_entrance_control_items
                        LEFT JOIN okb_db_oper ON okb_db_oper.ID = okb_db_entrance_control_items.operation_id
                        LEFT JOIN okb_db_zakdet ON okb_db_entrance_control_items.order_item_id = okb_db_zakdet.ID
                        LEFT JOIN okb_db_zak ON okb_db_zakdet.ID_zak = okb_db_zak.ID
                        WHERE
                        $filter 
                        okb_db_entrance_control_items.control_page_id = ". $this -> id ;
            $stmt = $this -> pdo->prepare( $query );
            $stmt -> execute();
        }
        catch (PDOException $e)
        {
            die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }
        while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
        {
            $ent_cont_id =  $row -> ent_cont_id ;
            $item_id = $row -> item_id;
            $zak_id = $row -> zak_id;
            $operation_id = $row -> operation_id ;
            $operation_name = conv( $row -> operation_name );

            $ent_cont_dse_name = conv( $row -> ent_cont_dse_name );
            $ent_cont_dse_draw = conv( $row -> ent_cont_dse_draw );

            $inwork_state = $row -> inwork_state ;
            $reject_state = $row -> reject_state ;
            $rework_state = $row -> rework_state ;
            $pass_state = $row -> pass_state ;

            $dse_name = conv( $row -> dse_name );
            $dse_draw = conv( $row -> dse_draw ) ;

            $zak_name = conv( $row -> zak_name );
            $dse_draw = conv( $row -> dse_draw ) ;

            $zak_dse_name = conv( $row -> zak_dse_name );
            $zak_dse_draw = conv( $row -> zak_dse_draw ) ;

            $count = $row -> count ;

            if( isset( $this -> proc_list[ $operation_id ]['item_count']) )
                $this -> proc_list[ $operation_id ]['item_count'] ++ ;
                    else
                        $this -> proc_list[ $operation_id ]['item_count'] = 1 ;

            $this -> proc_list[ $operation_id ]['operation_name'] = $operation_name ;
            $this -> proc_list[ $operation_id ]['operation_id'] = $operation_id ;
            $this -> proc_list[ $operation_id ]['items'][] = [


                'ent_cont_id' => $ent_cont_id,

                'ent_cont_dse_name' => $ent_cont_dse_name,
                 'ent_cont_dse_draw' => $ent_cont_dse_draw,

                'item_id' => $item_id ,
                'zak_id' => $zak_id ,
                'dse_name' => $dse_name,
                'zak_name' => $zak_name,
                'dse_draw' => $dse_draw,
                'zak_dse_name' => $zak_dse_name,
                'zak_dse_draw' => $zak_dse_draw,
                'count' => $count,

                'inwork_state' => $inwork_state ,
                'reject_state' => $reject_state ,
                'rework_state' => $rework_state ,
                'pass_state' => $pass_state

            ];
        }
    }

    private function CollectData()
    {
        if( $this -> id )
        {
            try
            {
                $query ="
                                SELECT
                                okb_db_entrance_control_pages.id,
                                DATE_FORMAT( okb_db_entrance_control_pages.date, '%d.%m.%Y' ) AS date,
                                okb_db_entrance_control_pages.page_num,
                                okb_db_entrance_control_pages.image,
                                okb_db_entrance_control_pages.proc_type_id,
                                okb_db_entrance_control_pages.client_id,
                                okb_db_entrance_control_pages_proc_type.description AS proc_type_name,
                                okb_db_clients.`NAME` AS client_name
                                FROM
                                okb_db_entrance_control_pages
                                LEFT JOIN okb_db_entrance_control_pages_proc_type ON okb_db_entrance_control_pages_proc_type.id = okb_db_entrance_control_pages.proc_type_id
                                LEFT JOIN okb_db_clients ON okb_db_clients.ID = okb_db_entrance_control_pages.client_id
                                WHERE
                                okb_db_entrance_control_pages.id = ".$this -> id;
                $stmt = $this -> pdo->prepare( $query );
                $stmt -> execute();
            }
            catch (PDOException $e)
            {
                die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage()." Query : $query");
            }

        if( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
        {
            $this -> date = $row -> date;
            $this -> number = conv( $row -> page_num );

            $this -> proc_type_id = $row -> proc_type_id;
            $this -> proc_type_name = conv( $row -> proc_type_name );

            $this -> client_id = $row -> client_id ;
            $this -> client_name = conv( $row -> client_name );

            $this -> image = $row -> image;
        }

        $this -> CollectOperations();
     }
    }

    public function __toString()
    {
        return
        "id : ".$this -> id.
        "<br> date : ".$this -> date.
        "<br> number : ".$this -> number.
        "<br> proc_type_id : ".$this -> proc_type_id.
        "<br> proc_type_name : ".$this -> proc_type_name.
        "<br> client id : ".$this -> client_id.
        "<br> client name : ".$this -> client_name.
        "<br> image : ".$this -> image ;
    }

    public function GetTableBegin()
    {
        $content_begin = "<div class='container' id='wrap'>

                          <div class='row'>
                              <div class='col-lg-12'>
                                  <hr>
                                  <button class='btn btn-small btn-primary pull-right add_row' type='button' data-id='".( $this -> id )."' disabled>".conv('Добавить операцию')."</button>
                              </div><!--div class='row'-->
                          </div><!--div class='container'-->

                           <div class='row table-row' data-id='".( $this -> id )."'>
                           <table class='table tbl' data-id='".( $this -> id )."'>
                           <col width='6%'>
                           <col width='5%'>
                           <col width='2%'>
                           <col width='7%'>
                           <col width='10%'>
                           <col width='10%'>
                           <col width='7%'>
                           <col width='10%'>
                           <col width='10%'>
                           <col width='10%'>
                           <col width='3%'>

                           <col width='3%'>
                           <col width='3%'>
                           <col width='3%'>
                           <col width='3%'>
                           <col width='3%'>

                           <thead>
                            <tr class='first'>
                            <td class='Field AC'>".conv("Дата")."</td>
                            <td class='Field AC'>".conv("Лист №")."</td>
                            <td class='Field AC'><div><img src='uses/film.png' class='add_pict_img' /></div></td>
                            <td class='Field AC'>".conv("Поставка/")."<br>".conv("Кооперация")."</td>
                            <td class='Field AC'>".conv("Поставщик/")."<br>".conv("Кооператор")."</td>
                            <td class='Field AC'>".conv("Операция")."</td>
                            <td class='Field AC'>".conv("Заказ №")."</td>
                            <td class='Field AC'>".conv("Наименование изделия")."</td>
                            <td class='Field AC'>".conv("Наименование ДСЕ")."</td>
                            <td class='Field AC'>".conv("№ чертежа ДСЕ")."</td>

                            <td class='Field AC'>".conv("Кол.")."</td>

                            <td class='Field AC'>".conv("ВР")."</td>
                            <td class='Field AC'>".conv("ИБ")."</td>
                            <td class='Field AC'>".conv("Д")."</td>
                            <td class='Field AC'>".conv("П")."</td>
                            <td class='Field AC'><div><img data-id='".( $this -> id )."'class='print_img' src='uses/word_16_dis.png' /></div></td>
                            </tr>
                            </thead>
                           ";


//                             <td class='Field AC'>".conv("Уд.")."</td>

        return $content_begin ;

    }

    public static function GetTableEnd()
    {
        return $content_end = "</table></div><!--div class='row'--></div><!--div class='container'-->";
    }

    public function GetTableContent()
    {
        if( ! $this -> id )
            return '';

        $total_rowspan = 0 ;
        foreach( $this -> proc_list AS $proc )
            $total_rowspan += $proc['item_count'];

       $content = "
                  <tbody>
                  <tr id='". $this -> id ."'>";

        if( $this -> date == "00.00.0000")
            $content .=  "<td rowspan='$total_rowspan' class='Field AC'><input class='datepicker' type='text' /></td>";
                else
                    $content .= "<td rowspan='$total_rowspan' class='Field AC'><span>". $this -> date ."</span></td>";

        $page_num_caption = $this -> number == '' ? conv("Новый лист") : $this -> number ;

        $content .= "<td rowspan='$total_rowspan' class='Field AC'><input data-key='". $this -> id ."' id='page_num_input' value='$page_num_caption' disabled /></td>";

        $img_class = '';

        if( strlen( $this -> image ))
        {
            $img_class = "view_pict_img";
            $src = "uses/film.png";
            $del_class = '';
        }
        else
        {
            $img_class = "add_pict_img";
            $src = "uses/addf_img.png";
            $del_class = ' hidden';
        }

    $del_img = '';
    
    if( $this -> image_deleting )
            $del_img = "<img src='uses/del.png' class='del_img$del_class' />";

       $content .= "<td rowspan='$total_rowspan' class='Field AC'><div><img src='$src'
                  class='$img_class' data-img='".( $this -> image )."'/>
                    $del_img
                  </div></td>";

        $content .= "<td rowspan='$total_rowspan' class='Field AC'>
                            <select class='type_sel'>
                                <option value='1' ".( $this -> proc_type_id == 1 ? 'selected' : '').">".conv("Кооперация")."</option>
                                <option value='2'".( $this -> proc_type_id == 2 ? 'selected' : '').">".conv("Поставка")."</option>
                            </select></td>";

        if( $this -> client_id == 0 )
            $content .= "<td rowspan='$total_rowspan' class='Field'><input class='supplier' type='text' /></td>";
                else
                    $content .= "<td rowspan='$total_rowspan' class='Field AC'>". $this -> client_name ."</td>";

        $i = 0 ;
        foreach( $this -> proc_list AS $key => $proc )
        {
            $local_rowspan = count( $proc['items'] );
            $operation_id = $proc['operation_id'];

            if( $i )
                $content .= "<tr>";

            if( $operation_id )
            {
                     $content .= "<td rowspan='$local_rowspan' class='Field AC'><div><span class='operation'>".$proc['operation_name']."</span>
                      <img data-key='' data-id='$operation_id' src='uses/plus.png' class='add_img' /></div></td>";

                    foreach ( $proc['items'] AS $item )
                    {
                        $zak_id = $item['zak_id'];
                        $zak_name = $item['zak_name'];

                        $ent_cont_dse_name = $item['ent_cont_dse_name'];
                        $ent_cont_dse_draw = $item['ent_cont_dse_draw'];

                        $zak_dse_name = $item['zak_dse_name'];
                        $dse_name = $item['dse_name'];
                        $item_id = $item['item_id'];
                        $dse_draw = $item['dse_draw'];
                        $count = $item['count'] ;
                        $row_id = $item['ent_cont_id'];

                        if( $zak_id )
                        {
                                $content .= "<td class='Field AC order_name' data-id='$zak_id'>$zak_name</td>";
                                $content .= "<td class='Field AC'>$zak_dse_name</td>";

                                $content .= "<td class='Field AC'  data-id='$item_id'><input data-key='$row_id' data-field='dse_name' class='manual_edit' value='$ent_cont_dse_name' /></td>";

                                $content .= "<td class='Field AC'  data-id='$item_id'><input data-key='$row_id' data-field='dse_draw' class='manual_edit' value='$ent_cont_dse_draw' /></td>";
                        }
                        else
                        {

                        $cnt = count( $proc['items'] );

                        $del_order_img = $cnt == 1 ? "" : "<img data-key='".$item['ent_cont_id']."'  class='del_order_img' src='uses/del.png' />";

                                $content .= "<td class='Field AC' data-id='0'>
                                <div class='order_input_div'>
                                <input data-operation-id='$operation_id' data-key='".$item['ent_cont_id']."' class='order' type='text' />$del_order_img
                                </td>";
                                $content .= "<td class='Field AC'><input class='order_name' type='text' disabled/></td>";
                                $content .= "<td class='Field AC'  data-id='0'><input data-key='".$item['ent_cont_id']."' class='dse_name' type='text' disabled /></td>";
                                $content .= "<td class='Field AC'><input class='dse_draw' type='text' disabled /></td>";
                        }

                        $content .= "<td class='Field AC'><input data-id='$zak_id' data-key='".$item['ent_cont_id']."' class='count_input' value='$count'/></td>";

                        $data_key = $item['ent_cont_id'] ;

                        $content .= "<td class='Field AC'>
                        <input type='checkbox' class='inwork_state' data-key='$data_key' ".( $item['inwork_state'] ? ' checked' : '' )." /></td>";

                        $reject_state = $item['reject_state'] ;
                        $rework_state = $item['rework_state'] ;
                        $pass_state = $item['pass_state'] ;

                        $reject_state_class = $reject_state ? 'reject_state_field' : '';
                        $rework_state_class = $rework_state ? 'rework_state_field' : '';
                        $pass_state_class = $pass_state ? 'pass_state_field' : '';

                        if( $zak_id )
                            $hidden = '';
                                else
                                    $hidden = 'hidden';

                            $content .= "<td class='Field AC $reject_state_class'><button class='reject_state $hidden' data-key='$data_key'>$reject_state</button></td>";
                            $content .= "<td class='Field AC $rework_state_class'><button  class='rework_state $hidden' data-key='$data_key'>$rework_state</button></td>";
                            $content .= "<td class='Field AC $pass_state_class'><button class='pass_state $hidden' data-key='$data_key'>$pass_state</button></td>";
                            $content .= "<td class='Field AC'><input type='checkbox' class='print_check $hidden' data-key='$data_key'/></td>";

                        $content .= "</tr>";

                        $i ++ ;
                    }
          }
          else // new row
          {
                    foreach ( $proc['items'] AS $item )
                    {

                        $del_oper_img = count( $this -> proc_list ) == 1 ? "" : "<img data-key='".$item['ent_cont_id']."' class='del_oper_img' src='uses/del.png' />";

                                $content .= "<td class='Field AC'><div class='operation_input_div'><input data-key='".$item['ent_cont_id']."' class='operation' type='text' />$del_oper_img</div></td>";

                                $content .= "<td class='Field AC' data-id='0'><input data-key='".$item['ent_cont_id']."' class='order' type='text' /></td>";
                                $content .= "<td class='Field AC'><input class='order_name' type='text' disabled/></td>";
                                $content .= "<td class='Field AC'  data-id='0'><input data-key='".$item['ent_cont_id']."' class='dse_name' type='text' disabled/></td>";
                                $content .= "<td class='Field AC'><input class='dse_draw' type='text' disabled /></td>";

                                $content .= "<td class='Field AC'><input data-id='0' data-key='".$item['ent_cont_id']."' class='count_input' /></td>";

                                $hidden = 'hidden';
                                $data_key = $item['ent_cont_id'] ;

                                $content .= "<td class='Field AC'>
                                <input type='checkbox' class='inwork_state' data-key='$data_key' /></td>";

                                $content .= "<td class='Field AC'><button class='reject_state $hidden' data-key='$data_key'>0</button></td>";
                                $content .= "<td class='Field AC'><button  class='rework_state $hidden' data-key='$data_key'>0</button></td>";
                                $content .= "<td class='Field AC'><button class='pass_state $hidden' data-key='$data_key'>0</button></td>";
                                $content .= "<td class='Field AC'><input type='checkbox' class='print_check $hidden' data-key='$data_key'/></td>";


                                $content .= "</tr>";
                    }
          }

        }

        $content .="</tbody>";
        return $content ;
    }

    public function EnableImageDeleting()
    {
        $this -> image_deleting = 1 ;
    }

    public function Filtrate( $filter )
    {
        $arr = $this -> proc_list;
        $key = key( $arr );
        $item = $arr[ $key ]['items'] ;
        $filter = conv( $filter );

        foreach( $item AS $ikey => $ival )   
        {
           if( 
                strripos ( $ival['zak_name'], $filter ) === false
                &&
                strripos ( $ival['zak_dse_name'], $filter ) === false
             )
           {
             $arr[ $key ]['item_count'] -- ;
             unset( $arr[$key]['items'][ $ikey ] );
           }
        }
        $this -> proc_list = $arr ;
        return $arr ;
    }

    public function GetTable()
    {   
         $str = '';
         $arr = $this -> proc_list ;
         $key = key( $arr );
        
         if( $arr[ $key ]['item_count'] )
            {
             $str .= $this -> GetTableBegin();
             $str .= $this -> GetTableContent();
             $str .= $this -> GetTableEnd();
            }

         return $str;
    }
}

