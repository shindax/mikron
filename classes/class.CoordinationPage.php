<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/includes/send_mail.php" );

/*error_reporting(E_ALL);
ini_set('display_errors', true);*/
class CoordinationPage
{
    protected $pdo;
    protected $id;
    protected $user_id;
    protected $can_add_pages;
    protected $number;
    protected $data = [];    
    protected $tasks = [];    

    protected $krz2_id ;
    protected $krz2_det_id ;
    protected $krz2_name ;
    protected $krz2_unit_name ;    
    protected $krz2_count ;
    protected $krz2_draw ;    
    protected $krz2_client_name ;
    protected $krz2_comment ;
    protected $doc_path ;

    protected $has_cooperation ;
    protected $has_special_activity ;

    protected $frozen_by_id ;
    protected $frozen_by_name ;    
    protected $frozen_at ;

    public function __construct( $pdo, $user_id , $krz2_id )
    {
        $this -> pdo = $pdo;
        $this -> user_id = $user_id;
        $this -> krz2_id = $krz2_id;
        
        $this -> has_cooperation = false;
        $this -> has_special_activity = false ; 

        $this -> CollectKrz2CommomData();

       $this -> CheckCanAddPage();
        try
            {
                $query = "
                            SELECT * FROM coordination_pages
                            WHERE  krz2_id = $krz2_id";

                            $stmt = $this -> pdo->prepare( $query );
                            $stmt -> execute();
            }

            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
            }
       
        if( $stmt -> rowCount() )
        {
            if ( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            {
                $this -> id = $row -> id ;
                $this -> frozen_by_id = $row -> frozen_by;
            }
        }
        else
            if( $this -> IsCanAddPage() )
                {
                	    $page_id = $this -> InsertPage( $krz2_id );

        				$user_arr = [];
                        $email_arr = [];

                        try
                        {
                            $query = "
                                        SELECT user_arr, email_arr FROM coordination_pages_rows
                                        WHERE 1";

                                        $stmt = $this -> pdo -> prepare( $query );
                                        $stmt -> execute();
                        }

                        catch (PDOException $e)
                        {
                           die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
                        }
                        
                        while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
                        {
                            $user_arr = array_merge( $user_arr, json_decode( $row -> user_arr ) );
                            $email_arr = array_merge( $email_arr, json_decode( $row -> email_arr ) );
                        }

                        $user_arr = array_unique( $user_arr );
                        $email_arr = array_unique( $email_arr );

                    $male_message = "создал лист согласования № ".($this -> krz2_name)." по КРЗ2 <a href=\"index.php?do=show&formid=30&id=".$this -> krz2_id."\" target=\"_blank\">".$this -> krz2_name."( ".( $this -> krz2_unit_name )." )</a>";

                    $female_message = "создала лист согласования № ".($this -> krz2_name)." по КРЗ2 <a href=\"index.php?do=show&formid=30&id=".$this -> krz2_id."\" target=\"_blank\">".$this -> krz2_name." ( ".( $this -> krz2_unit_name )." )</a>";            

                      $this -> SendNotification( $user_arr, $email_arr, $this -> user_id, $this -> id, $male_message, $female_message, 11 );
                }
                else
                    return ;

       $this -> CollectData();
    } // public function __construct( $pdo, $user_id , $krz2_id )

    public function IsCanAddPage()
    {
        return $this -> can_add_pages;
    }

    protected function CheckCanAddPage()
    {
        $user_arr = [];

        try
            {
                $query = "SELECT user_arr 
                            FROM `coordination_pages_rows` 
                            WHERE id = 1";

                            $stmt = $this -> pdo->prepare( $query );
                            $stmt -> execute();
            }

            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
            }
       
        if( $stmt -> rowCount() )
        {
            if ( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
                $user_arr = json_decode( $row -> user_arr );
        }

        if( in_array( $this -> user_id, $user_arr ) )
            $this -> can_add_pages = 1 ;
                else
                    $this -> can_add_pages = 0 ;

        return $this -> can_add_pages;
    }

    public function GetData()
    {
        return $this -> data;
    }

    public function GetTasks()
    {
        return $this -> tasks;
    }

    protected function CollectData()
    {
		
        $data = [];
        $tasks = [];
        $id = $this -> id ;

        try
            {
                $query = "
                            SELECT
                            coordination_page_items.page_id,
                            coordination_page_items.id AS item_id,
                            coordination_pages.krz2_id,
                            DATE_FORMAT( coordination_pages.coordinated, '%d.%m.%Y') coordinated,
                            coordination_pages_rows.caption AS row_name,
                            DATE_FORMAT( coordination_page_items.date, '%d.%m.%Y') date,
                            DATE_FORMAT( coordination_page_items.date, '%Y-%m-%d') mysql_date,                            
                            DATE_FORMAT( coordination_page_items.ins_time, '%d.%m.%Y %H:%i') ins_time,                           
                            coordination_page_items.`comment`,
                            coordination_pages_rows.id AS row_id,
                            coordination_page_items.coordinator_id,
                            coordination_pages_task.id AS task_id,
                            coordination_pages_task.agreed_flag AS agreed_flag,
                            coordination_pages_task.caption AS task_name,
                            coordination_pages_rows.user_arr,
                            okb_users.FIO AS coordinator_name,
                            coordination_pages_task.can_hide can_hide
                            FROM
                            coordination_page_items
                            LEFT JOIN coordination_pages ON coordination_pages.id = coordination_page_items.page_id
                            LEFT OUTER JOIN coordination_pages_rows ON coordination_page_items.row_id = coordination_pages_rows.id
                            LEFT JOIN coordination_pages_task ON coordination_pages_task.id = coordination_page_items.task_id
                            LEFT JOIN okb_users ON coordination_page_items.coordinator_id = okb_users.ID
                            WHERE
                            coordination_page_items.page_id = $id 
                            AND 
                            coordination_page_items.ignored = 0 
                            ORDER BY
                            coordination_pages_rows.ord,
                            coordination_page_items.row_id ASC,
                            coordination_page_items.task_id ASC 
            ";

                            $stmt = $this -> pdo->prepare( $query );
                            $stmt -> execute();
            }

            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
            }

            while ( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
                {
                    $coordinated = $row -> coordinated ;

                    $row_id = $row -> row_id ;      
                    $can_hide = $row -> can_hide ;                          
                    $item_id = $row -> item_id ;
                    $task_id = $row -> task_id ;

                    $agreed_flag = $row -> agreed_flag;

                    $coordinator_id = $row -> coordinator_id ;
                    $coordinator_name = conv( $row -> coordinator_name );

                    $user_arr = json_decode( $row -> user_arr );
                    $user_list = join(",", $user_arr );

                    $row_name = conv( $row -> row_name );
                    $task_name = conv( $row -> task_name );

                    $date = $row -> date ;
                    $mysql_date = $row -> mysql_date ;                    
                    $ins_time = $row -> ins_time ;
                    $comment = conv( $row -> comment );
                    
                    if( !isset( $data[ $row_id ] ) )
                        $data[ $row_id ] = [ "row_name" => $row_name ];

                    $data[ $row_id ]['childs'][] = 
                        [
                            "row_id" => $row_id,
                            "item_id" => $item_id, 
                            "can_hide" => $can_hide,
                            "task_name" => $task_name,
                            "date" => $date,
                            "mysql_date" => $mysql_date,
                            "ins_time" => $ins_time,
//                            "last_task" => $last_task,
                            "comment" => $comment,
                            
                            "coordinator_id" => $coordinator_id,
                            "coordinator_name" => $coordinator_name,

                            "user_list" => $user_list,
                            "user_arr" => $user_arr,
                            "task_id" => $task_id,
                            "agreed_flag" => $agreed_flag,
                            "disabled" => 1,

                        ];

                        $tasks[ $item_id ] = $coordinator_id ;
                }

        // Разрешить только следующий элемент за установленным
        foreach( $data AS $dkey => $dval )
        {
            foreach ( $dval['childs'] as $key => $val ) 
            {                
                $item_id = $val['item_id'];
                $skip = $data[1]['childs'][0]['row_id'] == 1
                        &&
                        $data[1]['childs'][0]['date'] != "00.00.0000"
                        &&
                        ( $val['row_id'] == 2 || $val['row_id'] == 1 )
                        ;

                if
                (
                    $this -> isPrevNotNull( $tasks, $item_id )
                    &&  in_array( $this -> user_id, $val['user_arr'] )
                )
                    $data[ $dkey ][ 'childs' ][ $key ]['disabled'] = 0;

                if
                (
                    in_array( $this -> user_id, $val['user_arr'] ) && $skip
                )
                    $data[ $dkey ][ 'childs' ][ $key  ]['disabled'] = 0;

                if
                ( $dkey == 6 && in_array( $this -> user_id, $val['user_arr'] ) && !$this -> has_cooperation )
                    $data[ $dkey ][ 'childs' ][ $key  ]['disabled'] = 0;

            }
        }

        $this -> data = $data ;
        $this -> tasks = $tasks ;        
    }

    public function GetTable()
    {
    
        $str = $this -> GetTableBegin();
        $str .= $this -> GetTableContent();
        $str .= $this -> GetTableEnd();
        return $str ;
    }

    public function GetPrintTable()
    {
    
        $str = $this -> GetPrintTableBegin();
        $str .= $this -> GetPrintTableContent();
        $str .= $this -> GetTableEnd();
        return $str ;
    }


    protected function GetTableBegin()
    {
        $str = "<table id='coord_table' class='table tbl'>";
        $str .= "
                           <col width='15%'>
                           <col width='5%'>
                           <col width='5%'>
                           <col width='2%'>
                           <col width='2%'>
                           <col width='30%'>";

        $str .= "<tr class='first'>";
        $str .= "<td class='Field AC' data-coop = '".($this -> has_cooperation)."'>".conv( "Должность : " )."</td>";


        $str .= "<td class='Field AC'>".conv( "ФИО" )."</td>";
        $str .= "<td class='Field AC'>".conv( "Этап" )."</td>";
        $str .= "<td class='Field AC'>".conv( "Дата<br>выполнения" )."</td>";
        $str .= "<td class='Field AC'>".conv( "Время<br>заполнения" )."</td>";
        $str .= "<td class='Field AC'>".conv( "Примечание" )."</td>";
        $str .= "</tr>";
        return $str ;
    }

    protected function GetPrintTableBegin()
    {
        $str = "<table id='coord_table' class='table tbl'>";
        $str .= "
                           <col width='12%'>
                           <col width='12%'>
                           <col width='16%'>
                           <col width='2%'>
                           <col width='16%'>
                           <col width='20%'>";

        $str .= "<tr class='first'>";
        $str .= "<td class='Field AC'>".conv( "Должность" )."</td>";
        $str .= "<td class='Field AC'>".conv( "ФИО" )."</td>";
        $str .= "<td class='Field AC'>".conv( "Этап" )."</td>";
        $str .= "<td class='Field AC'>".conv( "Дата<br>выполнения" )."</td>";
        $str .= "<td class='Field AC'>".conv( "Время<br>заполнения" )."</td>";
        $str .= "<td class='Field AC'>".conv( "Примечание" )."</td>";
        $str .= "</tr>";
        return $str ;
    }

    protected function GetTableContent()
    {
        $has_cooperation =  $this -> has_cooperation ;
        $has_special_activity =  $this -> has_special_activity ;

        $str = "";
        foreach( $this -> data AS $key => $val )
        {
            $childs = $val['childs'];
            $item_id = $val['childs'][0]['item_id'];
            $coordinator_id = $val['childs'][0]['coordinator_id'];
            
            $user_select = $this -> getUserSelect( $item_id, $val['childs'][0]['user_list'], $coordinator_id );

            if( $key == 5 && $has_cooperation == false )
            {
               unset( $childs[1] );                
               unset( $childs[2] );
               unset( $childs[3] );               
               $childs[0]['task_name'] = conv("Ознакомлен");
               $this -> data[6]['childs'][0]['disabled'] = 0 ;
            }

            if( $key == 9 && !$has_special_activity )
            {
               $childs[0]['task_name'] = conv("Ознакомлен");
               $this -> data[4]['childs'][0]['disabled'] = 0 ;
            }

            $str .= "<tr data-id='$item_id' data-row='$key'>";
            $str .= "<td class='field AC' rowspan='".count( $childs )."'>".$val['row_name']."</td>";

// Раздел "Кооперация"
            if( $key == 5 && $has_cooperation == false ) // no cooperation
            {
                $str .= "<td class='field AC' rowspan='".count( $childs )."'>$user_select</td>";

                    foreach( $childs AS $ckey => $cval )
                    {
                        $page_id = $this -> id;
                        $user_list = $cval['user_list'];
                        $can_hide = $cval['can_hide'];
                        $item_id = $cval['item_id'];
                        $task_id = $cval['task_id'];
                        $coordinator_id = $cval['coordinator_id'];
                        $coordinator_name = $cval['coordinator_name'];
                        $disabled = $cval['disabled'];
                        $agreed_flag = $cval['agreed_flag'];

                        $date = "";
                        $comment = "<input class='comment' ";

                         if( ( in_array( $this -> user_id, $cval['user_arr'] ) && $coordinator_id ))
                             $comm_dis = "";
                                else
                                    $comm_dis = "disabled";

                        if( $this -> frozen_by_id )
                            $comm_dis = "disabled";

                        $comment .= "value='".$cval['comment']."'";

                        $comment .= " $comm_dis />";

                        $mysql_date = $cval['mysql_date'];
                        $ins_time = $cval['ins_time'] ;

                        if( !strlen( $ins_time ) || $ins_time == '00.00.0000 00:00')
                            $ins_time = "--.-- --:--";

                        $date_val = $cval['date'];

                        $date = "<input data-page_id='".( $this -> id )."' class='acquainted_checkbox_input' type='checkbox' ";

                        if( $coordinator_id )
                            $date .= ' checked ';


                        if( $disabled || $mysql_date != '0000-00-00' || $this -> frozen_by_id )
                            $date .= "disabled aaa";

                        $date .= " />";
                       
                        $mysql_date = "";

                        $user_id = $this -> user_id ;

                        if( $ckey )
                          $str .= "<tr data-id='$item_id' data-row='$key'>";
                        
                        $str .= "<td class='field AC'><div class='hide_checkbox_div'><span>".$cval['task_name']."</span></div></td>";
                        $str .= "<td class='field AC date' data-mysql_date='$mysql_date'>$date</td>";
                        $str .= "<td class='field AC'><span class='ins_time'>$ins_time</span></td>";
                        $str .= "<td class='field AC'>$comment</td>";
                        $str .= "</tr>";

                        if( !$ckey )
                            $str .= "</tr>";
                    
                    } // foreach( $childs AS $ckey => $cval )
            }

// Раздел "Главный инженер"

            if( $key == 9 && ! $has_special_activity ) // Нет спецмероприятий
            {
                $str .= "<td class='field AC' rowspan='".count( $childs )."'>$user_select</td>";

                    foreach( $childs AS $ckey => $cval )
                    {
                        $page_id = $this -> id;
                        $user_list = $cval['user_list'];
                        $can_hide = $cval['can_hide'];
                        $item_id = $cval['item_id'];
                        $task_id = $cval['task_id'];
                        $coordinator_id = $cval['coordinator_id'];
                        $coordinator_name = $cval['coordinator_name'];
                        $disabled = $cval['disabled'];
                        $agreed_flag = $cval['agreed_flag'];

                        $date = "";
                        $comment = "<input class='comment' ";

                        if( ( in_array( $this -> user_id, $cval['user_arr'] ) && $coordinator_id ))
                             $comm_dis = "";
                                else
                                    $comm_dis = "disabled";

                        if( $this -> frozen_by_id )
                            $comm_dis = "disabled";

                        $comment .= "value='".$cval['comment']."'";

                        $comment .= " $comm_dis />";

                        $mysql_date = $cval['mysql_date'];
                        $ins_time = $cval['ins_time'] ;

                        if( !strlen( $ins_time ) || $ins_time == '00.00.0000 00:00')
                            $ins_time = "--.-- --:--";

                        $date_val = $cval['date'];

                        $date = "<input data-page_id='".( $this -> id )."' class='acquainted_checkbox_input' data-prop='$disabled $mysql_date' type='checkbox' ";

                        if( $coordinator_id )
                            $date .= ' checked ';

                        if( $disabled || $mysql_date != '0000-00-00' || $this -> frozen_by_id )
                            $date .= " disabled ";

                        $date .= " />";
                       
                        $mysql_date = "";

                        $user_id = $this -> user_id ;

                        if( $ckey )
                          $str .= "<tr data-id='$item_id' data-row='$key'>";
                        
                        $str .= "<td class='field AC'><div class='hide_checkbox_div'><span>".$cval['task_name']."</span></div></td>";
                        $str .= "<td class='field AC date' data-mysql_date='$mysql_date'>$date</td>";
                        $str .= "<td class='field AC'><span class='ins_time'>$ins_time</span></td>";
                        $str .= "<td class='field AC'>$comment</td>";
                        $str .= "</tr>";

                        if( !$ckey )
                            $str .= "</tr>";
                    
                    } // foreach( $childs AS $ckey => $cval )
            
            } // if( $key == 9 ) Раздел "Главный инженер"


// Все остальные разделы 
// 
            if( !( $key == 5 && !$has_cooperation ) && !( $key == 9 && ! $has_special_activity )  )
            {
                $str .= "<td class='field AC' rowspan='".count( $childs )."'>$user_select</td>";

                foreach( $childs AS $ckey => $cval )
                {
                    $page_id = $this -> id;
                    $user_list = $cval['user_list'];
                    $can_hide = $cval['can_hide'];
                    $item_id = $cval['item_id'];
                    $task_id = $cval['task_id'];
                    $coordinator_id = $cval['coordinator_id'];
                    $coordinator_name = $cval['coordinator_name'];
                    $disabled = $cval['disabled'];
                    $agreed_flag = $cval['agreed_flag'];

// Если нет спецмероприятий, ОМТС может вводить данные
 
                    if( 
                        $key == 4 
                        && 
                        ! $has_special_activity 
                        &&
                        in_array( $this -> user_id, $cval['user_arr'] ) 
                    )
                        $disabled = 0 ;

                    $date = "";
                    $comment = "<input class='comment' ";

                     if( in_array( $this -> user_id, $cval['user_arr'] ) && $coordinator_id )
                         $comm_dis = "";
                            else
                                $comm_dis = " disabled ";

                    $comment .= "value='".$cval['comment']."'";
                            $comment .= " $comm_dis />";

                    $mysql_date = $cval['mysql_date'];
                    $ins_time = $cval['ins_time'] ;

                    if( !strlen( $ins_time ) || $ins_time == '00.00.0000 00:00')
                        $ins_time = "--.-- --:--";

                    $date_val = $cval['date'];

                    if( $coordinator_id )
                    {
                        if( $agreed_flag )
                            $date = "<input class='checkbox_input' type='checkbox' checked disabled data-coordinator_id='$coordinator_id' />";
                             else
                                $date = "<input value='$date_val' class='datepicker' data-task='$task_id' disabled data-coordinator_id='$coordinator_id' />";
                    }
                         else
                         {
                            $date = "<input ";
                            
                            if( $agreed_flag )
                                $date .= " class='agreed_flag' type='checkbox'";
                                  else
                                    $date .= " data-task='$task_id' class='datepicker' ";

                           if( $disabled || $this -> frozen_by_id )
                               $date .= "disabled";

                            $date .= " />";
                           
                            $mysql_date = "";
                         }

                    $user_id = $this -> user_id ;

                    if( $ckey )
                      $str .= "<tr data-id='$item_id' data-row='$key'>";
                    
                   $hide_checkbox = '';

// 145 - Трифонов, 214 - Трифонова, 39 - Куимова
                   
                   if( $can_hide && $key == 1 && ( $this -> user_id == 145 || $this -> user_id == 214 || $this -> user_id == 39 ) && !$coordinator_id )

                        $hide_checkbox = "<img class='hide_checkbox' data-page_id='$page_id' data-task_id='$task_id' src='uses/del.png'/>";

                    $str .= "<td class='field AC'><div class='hide_checkbox_div'><span>".$cval['task_name']."</span>$hide_checkbox</div></td>";
                    $str .= "<td class='field AC date' data-mysql_date='$mysql_date'>$date</td>";
                    $str .= "<td class='field AC'>$ins_time</td>";
                    $str .= "<td class='field AC'>$comment</td>";
                    $str .= "</tr>";

                    if( !$ckey )
                        $str .= "</tr>";
                }
            } 

            $str .= "</tr>";
        }
        return $str ;
    } // protected function GetTableContent()

    protected function GetTableEnd()
    {
        return "</table>";
    }

    protected function getUserSelect( $id, $user_list, $coordinator_id )
    {   
        $str = "";
        $user_arr = [];


        $query = "
                        SELECT ID id, FIO name
                        FROM `okb_users` AS clients
                        WHERE id IN ( $user_list )
                        ORDER BY name
                    ";

            try
            {
                $stmt = $this -> pdo->prepare( $query );
                $stmt->execute();
            }
            catch (PDOException $e)
            {
                die("Error in :".__FILE__." file, in ".__FUNCTION__." function, at ".__LINE__." line. Can't get data : " . $e->getMessage());
            }

            while ( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            {
                $str .= "<option value='".( $row -> id )."'";
                
                if( $coordinator_id )
                {
                    if( $coordinator_id == $row -> id )
                        $str .= " selected"; 
                }
                else
                     if( $this -> user_id == $row -> id )
                            $str .= " selected";    

                $str .= ">";
                $str .= conv( $row -> name )."</option>";
                $user_arr [] = conv( $row -> name );

            }

        $select = "<select class='coordinator_select' data-id='$id' disabled title='".join(", ", $user_arr)."'><option value='0'>...</option>";        
        $select .= $str ;
        $select .= "</select>";

        return $select ;
    }

    protected function isPrevNotNull( $arr, $key )
    {
        $first_key = key( $arr );

        if( $key ==  $first_key )
            return 1;
        
        $prev = $arr[ $first_key ];

        foreach( $arr AS $akey => $aval )
            if( $key == $akey )
                if( $prev )
                    return 1 ;
                    else
                        return 0 ;
             else
                $prev = $aval ;
    }

    protected function InsertPage( $krz2_id )
    {
            try
            {
                $query = "
                            INSERT INTO coordination_pages
                            SET krz2_id = $krz2_id, creator = ".$this -> user_id;
                            $stmt = $this -> pdo->prepare( $query );
                            $stmt -> execute();
            }

            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
            }

            $last_insert_id = $this -> pdo -> lastInsertId();

            try
            {
                $query = "
                            INSERT INTO  `coordination_page_items` 
                            ( id, page_id, row_id, task_id ) 

                            VALUES  
                            # Инициатор заявки
                                    ( NULL, $last_insert_id, 1, 1 ),
                                    ( NULL, $last_insert_id, 1, 2 ),
                                    ( NULL, $last_insert_id, 1, 3 ),
                                    ( NULL, $last_insert_id, 1, 5 ),
                                    ( NULL, $last_insert_id, 1, 6 ),
                                    ( NULL, $last_insert_id, 1, 7 ),

                            # Коммерческий директор
                                    ( NULL, $last_insert_id, 2, 8 ),

                            # Технический директор
                                    ( NULL, $last_insert_id, 3, 9 ),
                                    ( NULL, $last_insert_id, 3, 10 ),
                                    ( NULL, $last_insert_id, 3, 11 ),
                                    ( NULL, $last_insert_id, 3, 17 ),            
                                    ( NULL, $last_insert_id, 3, 18 ),

                            # Главный инженер
                                    ( NULL, $last_insert_id, 9, 16 ),

                            # Начальник ОМТС
                                    ( NULL, $last_insert_id, 4, 4 ),
                                    ( NULL, $last_insert_id, 4, 5 ),
                                    ( NULL, $last_insert_id, 4, 6 ),
                                    ( NULL, $last_insert_id, 4, 7 ),

                            # Начальник ОВК
                                    ( NULL, $last_insert_id, 5, 4 ),
                                    ( NULL, $last_insert_id, 5, 5 ),
                                    ( NULL, $last_insert_id, 5, 6 ),
                                    ( NULL, $last_insert_id, 5, 7 ),

                            # Начальник ПДО
                                    ( NULL, $last_insert_id, 6, 12 ),
                                    ( NULL, $last_insert_id, 6, 13 ),
                                    ( NULL, $last_insert_id, 6, 14 ),
                                    ( NULL, $last_insert_id, 6, 15 ),

                            # Начальник производства
                                    ( NULL, $last_insert_id, 7, 8 )";

                           $stmt = $this -> pdo->prepare( $query );
                           $stmt -> execute();

                $this -> id = $last_insert_id;
            }

            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
            }

    return $last_insert_id;    

    } // protected function InsertPage( $krz2_id )

    protected function CollectKrz2CommomData()
    {
            try
            {
                $query = "
                            SELECT
                            okb_db_krz2.`NAME` AS krz2_name,
                            okb_db_clients.`NAME` AS client_name,
                            okb_db_krz2.ID AS id,
                            okb_db_krz2det.`NAME` AS unit_name,
                            okb_db_krz2det.COUNT AS count,
                            okb_db_krz2det.OBOZ AS draw,
                            okb_db_krz2det.ID AS krz2_det_id,

                            okb_db_krz2detitems.TID AS tid,

                            okb_db_krz2.MORE AS `comment`,
                            coordination_pages.doc_path AS doc_path,
                            coordination_pages.frozen_by AS frozen_by_id,
                            DATE_FORMAT( coordination_pages.frozen_at, '%d.%m.%Y %k:%i') frozen_at,
                            okb_users.FIO AS frozen_by_name
                            FROM
                            okb_db_krz2
                            LEFT JOIN okb_db_clients ON okb_db_krz2.ID_clients = okb_db_clients.ID
                            LEFT JOIN okb_db_krz2det ON okb_db_krz2det.ID_krz2 = okb_db_krz2.ID
                            LEFT JOIN okb_db_krz2detitems ON okb_db_krz2detitems.ID_krz2det = okb_db_krz2det.ID
                            LEFT JOIN  coordination_pages ON  coordination_pages.krz2_id = okb_db_krz2.ID
                            LEFT JOIN okb_users ON coordination_pages.frozen_by = okb_users.ID
                            WHERE
                            okb_db_krz2.ID = ".$this -> krz2_id;

                            $stmt = $this -> pdo->prepare( $query );
                            $stmt -> execute();
            }

            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
            }

            
            while( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
            {    
                if( $row -> tid == 2 )
                    $this -> has_cooperation =  true ;
                
                if( $row -> tid == 5 )
                    $this -> has_special_activity =  true ;

                $this -> krz2_name = conv( $row -> krz2_name );
                $this -> krz2_unit_name  = $row -> unit_name;
                $this -> krz2_count = $row -> count ;
                $this -> krz2_draw = conv( $row -> draw );  
                $this -> krz2_client_name = conv( $row -> client_name );
                $this -> krz2_comment = conv( $row -> comment ) ;
                $this -> doc_path = $row -> doc_path ;
                $this -> krz2_det_id = $row -> krz2_det_id;
                $this -> frozen_by_name = conv( $row -> frozen_by_name );
                $this -> frozen_by_id = $row -> frozen_by_id;
                $this -> frozen_at = $row -> frozen_at;
            }
    }

    public function GetKrz2CommomData()
    {
        return 
        [
            "id" => $this -> id,
            "krz2_id" => $this -> krz2_id,
            "krz2_name" => $this -> krz2_name,
            "krz2_unit_name" => $this -> krz2_unit_name,
            "krz2_count" => $this -> krz2_count,
            "krz2_draw" => $this -> krz2_draw,
            "krz2_client_name" => $this -> krz2_client_name,
            "krz2_comment" => $this -> krz2_comment,
            "doc_path" => $this -> doc_path,
            "krz2_det_id" => $this -> krz2_det_id,
            "frozen_by_name" => $this -> frozen_by_name,
            "frozen_by_id" => $this -> frozen_by_id,
            "frozen_at" => $this -> frozen_at      
        ];
    }

    public function IsKrz2Completed()
    {
        $data = $this -> data;
        foreach( $data AS $key => $val ); // Последний элемент в массиве исполнителей
        $data = $data[ $key ]['childs'];
        foreach( $data AS $key => $val ); // Последний элемент в массиве заданий исполнителя

        return $data[ $key ][ 'coordinator_id' ] ? true : false ;
    } // IsKrz2Completed()

    public function IsPageCoordinated()
    {
        if( ! $this -> IsKrz2Completed() )
            return null ;

        try
            {
                $query = "
                            SELECT 
                            DATE_FORMAT( coordination_pages.coordinated, '%d.%m.%Y %H:%m') coordinated 
                            FROM coordination_pages
                            WHERE  id = ". $this -> id;

                            $stmt = $this -> pdo->prepare( $query );
                            $stmt -> execute();
            }

            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
            }
       
            $row = $stmt->fetch(PDO::FETCH_OBJ );
            return $row -> coordinated == "00.00.0000 00:00" ? null : $row -> coordinated;
    } //IsPageCoordinated()

    public function GetPageId()
    {
        return $this -> id ;
    }

    public function GetKrz2Id()
    {
        return $this -> krz2_id ;
    } 

    protected function GetPrintTableContent()
    {
        $str = "";
        foreach( $this -> data AS $key => $val )
        {
            $childs = $val['childs'];
            $item_id = $val['childs'][0]['item_id'];
            $coordinator_name = $val['childs'][0]['coordinator_name'];
            
            $user = "";
            $str .= "<tr>";
            $str .= "<td class='field AC' rowspan='".count( $childs )."'>".$val['row_name']."</td>";

            $str .= "<td class='field AC' rowspan='".count( $childs )."'>$coordinator_name</td>";

            foreach( $childs AS $ckey => $cval )
            {
                $page_id = $this -> id;
                $user_list = $cval['user_list'];
                $can_hide = $cval['can_hide'];
                $item_id = $cval['item_id'];
                $task_id = $cval['task_id'];
                $coordinator_name = $cval['coordinator_name'];
                $disabled = $cval['disabled'];
                $agreed_flag = $cval['agreed_flag'];
               
                $date = $cval['date'] == '00.00.0000' ? '--.--.----' : $cval['date'] ;
                $ins_time = $cval['ins_time'] == '00.00.0000 00:00' ? '--.--.---- --:--' : $cval['ins_time'] ;
                
                $comment = $cval['comment'];

                if( $ckey )
                  $str .= "<tr>";

                $str .= "<td class='field AC'>".$cval['task_name']."</td>";
                $str .= "<td class='field AC date' data-mysql_date='$mysql_date'>$date</td>";
                $str .= "<td class='field AC'>$ins_time</td>";
                $str .= "<td class='field AL'>$comment</td>";
                $str .= "</tr>";

                if( !$ckey )
                    $str .= "</tr>";
            }

            $str .= "</tr>";
        }
        return $str ;
    } // protected function GetPrintTableContent()

	protected function SendNotification( $persons, $email_arr, $user_id, $page_id, $male_message, $female_message, $why )
	{
	  global $pdo ;
      
	  $query = '';

	        try
	      {
	          $query ="
	                      SELECT 
	                      users.FIO AS name,
	                      resurs.GENDER AS gender 
	                      FROM `okb_users` users
	                      INNER JOIN `okb_db_resurs` AS resurs ON resurs.ID_users = users.ID
	                      WHERE users.ID=$user_id";

	          $stmt = $pdo->prepare( $query );
	          $stmt -> execute();
	      }
	      catch (PDOException $e)
	      {
	        die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage()." Query : $query");
	      }

	      $row = $stmt->fetch( PDO::FETCH_OBJ ); // One record
	      $user_name = $row-> name ;
	      $gender = $row-> gender ;

	      if( $gender == 1 )
	        $message = $male_message;
	          else
	            $message = $female_message;

	                foreach( $persons AS $key => $to_user )
	                {
                        if( $user_id == $to_user)
                            continue ;

	                    try
	                    {
	                        $query ="
	                                  INSERT INTO okb_db_plan_fact_notification
	                                  ( id, why, to_user, zak_id, field, stage, ack, description, timestamp )
	                                  VALUES ( NULL, $why, $to_user ,0 ,$page_id ,0 ,0 ,'$user_name $message', NOW())
	                                  ";
	                        $stmt = $pdo->prepare( $query );
	                        $stmt -> execute();
	                    }
	                    catch (PDOException $e)
	                    {
	                      die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage()).". Query : $query";
	                    }
	                }

		} // SendNotification

        public function IsUserCanPathAdd()
        {
            try
            {
                $query = "
                            SELECT * FROM coordination_pages_rows
                            WHERE  id = 1";

                            $stmt = $this -> pdo->prepare( $query );
                            $stmt -> execute();
            }

            catch (PDOException $e)
            {
               die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
            }


            if( $row = $stmt->fetch(PDO::FETCH_OBJ ) )
                $user_arr = json_decode( $row -> user_arr );

            return in_array( $this -> user_id, $user_arr );
        }


        public function HasCooperation()
        {
            return $this -> has_cooperation ;
        }

        public function HasSpecialActivity()
        {
            $this -> has_special_activity ;
        }
}

    function debug( $arr , $conv = 0 )
    {
        $str = print_r($arr, true);
        if( $conv )
            $str = conv( $str );
        echo '<pre>'.$str.'</pre>';
    }