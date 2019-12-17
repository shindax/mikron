<?php
function console( $string )
{
    echo "<script>console.log('$string')</script>";
}

class PlanFactCollector
{
    const  PREPARE_GROUP = 1;
    const  EQUIPMENT_GROUP = 2;
    const  COOPERATION_GROUP = 3;
    const  PRODUCTION_GROUP = 4 ;
    const  COMMERTION_GROUP = 5;

    private $dblocation ;
    private $pdo ;
    private $orders;
    private $cols;
    private $ajax;

    private $query;
    private $where;
    private $order;

    private $user_id;
    private $statuses;

    public function __construct( $user_id, $dblocation, $dbname, $dbuser, $dbpasswd, $ajax = 0 )
    {

        $charset = 'utf8';
        $dsn = "mysql:host=$dblocation;dbname=$dbname;charset=$charset";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try{
            $this -> pdo = new PDO($dsn,$dbuser, $dbpasswd, $opt);
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't connect : " . $e->getMessage());
        }

        $this -> user_id = $user_id;
        $this -> dblocation = $dblocation;

        $this -> query = "
                        SELECT
                        okb_db_zak_type.description AS order_type_name,
                        okb_db_zak.`NAME` AS order_name,
                        okb_db_zak.DSE_NAME AS dse_name,
                        okb_db_zak.ID AS id,

                        okb_db_zak.ID_stage AS stage,
                        okb_db_zak.ID_status AS `status`,

                        LEFT( okb_db_zak.PD1, 1 ) kd_state,

                        okb_db_zak.PD1,
                        okb_db_zak.PD2,
                        okb_db_zak.PD3,
                        okb_db_zak.PD4,
                        okb_db_zak.PD5,
                        okb_db_zak.PD6,
                        okb_db_zak.PD7,
                        okb_db_zak.PD8,
                        okb_db_zak.PD9,
                        okb_db_zak.PD10,
                        okb_db_zak.PD11,
                        okb_db_zak.PD12,
                        okb_db_zak.PD13,
                        okb_db_zak.PD14,
                        okb_db_zak.PD_coop1,
                        okb_db_zak.PD_coop2,

                        okb_db_zak.pd1_conf,
                        okb_db_zak.pd2_conf,
                        okb_db_zak.pd3_conf,
                        okb_db_zak.pd4_conf,
                        okb_db_zak.pd5_conf,
                        okb_db_zak.pd6_conf,
                        okb_db_zak.pd7_conf,
                        okb_db_zak.pd8_conf,
                        okb_db_zak.pd9_conf,
                        okb_db_zak.pd10_conf,
                        okb_db_zak.pd11_conf,
                        okb_db_zak.pd12_conf,
                        okb_db_zak.pd13_conf,
                        okb_db_zak.pd14_conf,

                        okb_db_zak.pd_coop1_conf,
                        okb_db_zak.pd_coop2_conf,

                        okb_db_zak.DSE_OBOZ,
                        okb_db_zak.DATE,
                        okb_db_zak.DATE_PLAN,
                        okb_db_zak.DSE_COUNT AS dse_count,

                        okb_db_zak.SUMM_NO AS summ_no,
                        okb_db_zak.SUMM_V AS summ_v,
                        okb_db_zak.SUMM_NV AS summ_nv,
                        okb_db_zak.SUMM_N AS summ_n,

                        okb_db_clients.`NAME` AS client_name,
                        okb_users.IO AS user_name
                        FROM
                        okb_db_zak
                        LEFT JOIN okb_db_zak_type ON okb_db_zak.TID = okb_db_zak_type.id
                        LEFT JOIN okb_db_clients ON okb_db_zak.ID_clients = okb_db_clients.ID
                        LEFT JOIN okb_users ON okb_db_zak.ID_users2 = okb_users.ID ";

        $this -> where = "
                        WHERE
                        okb_db_zak.EDIT_STATE = 0
                        ";
//                             #AND okb_db_zak.ID BETWEEN 1 AND 400
        $this -> order = "
                        ORDER BY
                        order_name ASC ";

        $this -> ajax = $ajax ;
        $this -> getStatuses();

    }

    private function getStatuses()
    {
        $this -> statuses = [];

        try
        {
            $query = "SELECT * FROM `okb_db_zak_statuses` WHERE 1";
            $stmt = $this -> pdo -> prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        while ( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
            $this -> statuses [] = [ 'id' => $row -> id , 'status' => $this -> conv( $row -> description ) ];
    }

    public function conv( $str )
    {
      if( $this -> ajax )
      {
        if( $this -> dblocation == '127.0.0.1' )
         $result = iconv("UTF-8", "Windows-1251", $str );
          else
            $result = $str ;
      }
            else
              $result = iconv("UTF-8", "Windows-1251", $str );

      return $result ;
    }


    public function getDataCount()
    {
        return count( $this->orders );
    }

    public function getBreakApartPD( $str, $str_id )
    {
        // Получаем начало PD : состояние и первая дата
        $state_and_dates_str = explode('#', $str ) ;
        $state_and_first_date = explode('|', $state_and_dates_str[0] );
        $log_state = 1 * (int) $state_and_first_date[0] ;
        $state = $log_state ? 'checked' : '';

        if( isset( $state_and_first_date[1] ))
          $first_date = $state_and_first_date[1] ;
            else
              $first_date = $state_and_first_date[1] ='';

        $last_date = $state_and_dates_str[ count( $state_and_dates_str ) - 1 ] ;
        $arr = ['state' => $state, 'log_state' => 1 * $log_state, 'first_date' => extractDate( $first_date ), 'date_changes_count' => ( count( $state_and_dates_str ) - 1 ) / 2, 'last_date' => extractDate( $last_date ), 'str_id' => $str_id ];
        return $arr ;
    }

    public function getRawData()
    {
        return $this->orders ;
    }

    public function collectRawData( $inquery = 0, $completed = 0 )
    {
        $on_warehouse = " AND ID_status <> 5 ";
        try
        {
             if( $inquery )
                $query = $inquery;
                  else
                    $query = $this -> query . $this -> where . $on_warehouse. $this -> order ;

            $stmt = $this -> pdo -> prepare( $query );
            $stmt->execute();
        }

        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage()." Query : ". $query );
        }

        $row_count = $stmt->rowCount();
        $line = 1 ;
        $this->orders = [];

        while ( $row = $stmt->fetch( PDO::FETCH_OBJ ))
        {

                    $pd1 = $this -> getBreakApartPD( $row -> PD1, 'pd1' );
                    $pd2 = $this -> getBreakApartPD( $row -> PD2, 'pd2' );
                    $pd3 = $this -> getBreakApartPD( $row -> PD3, 'pd3' );
                    $pd4 = $this -> getBreakApartPD( $row -> PD4, 'pd4' );
                    $pd5 = $this -> getBreakApartPD( $row -> PD5, 'pd5' );
                    $pd6 = $this -> getBreakApartPD( $row -> PD6, 'pd6' );
                    $pd7 = $this -> getBreakApartPD( $row -> PD7, 'pd7' );
                    $pd8 = $this -> getBreakApartPD( $row -> PD8, 'pd8' );
                    $pd9 = $this -> getBreakApartPD( $row -> PD9, 'pd9' );
                    $pd10 = $this -> getBreakApartPD( $row -> PD10, 'pd10' );
                    $pd11 = $this -> getBreakApartPD( $row -> PD11, 'pd11' );
                    $pd12 = $this -> getBreakApartPD( $row -> PD12, 'pd12' );
                    $pd13 = $this -> getBreakApartPD( $row -> PD13, 'pd13' );
                    $pd14 = $this -> getBreakApartPD( $row -> PD14, 'pd14');

                    $pd1_conf = $row -> pd1_conf ;
                    $pd2_conf = $row -> pd2_conf ;
                    $pd3_conf = $row -> pd3_conf ;
                    $pd4_conf = $row -> pd4_conf ;
                    $pd5_conf = $row -> pd5_conf ;

                    $pd6_conf = $row -> pd6_conf ;
                    $pd7_conf = $row -> pd7_conf ;
                    $pd8_conf = $row -> pd8_conf ;
                    $pd9_conf = $row -> pd9_conf ;
                    $pd10_conf = $row -> pd10_conf ;

                    $pd11_conf = $row -> pd11_conf ;
                    $pd12_conf = $row -> pd12_conf ;
                    $pd13_conf = $row -> pd13_conf ;
                    $pd14_conf = $row -> pd14_conf ;

                    $pd_coop1_conf = $row -> pd_coop1_conf ;
                    $pd_coop2_conf = $row -> pd_coop2_conf ;


            $this->orders [] =
                [
                    'line' => ( $line ++ )." / $row_count",
                    'order_id' => $row -> id,
                    'create_date' => $this -> decodeDate( $row -> DATE ),
                    'supply_date' => $this -> decodeDate( $row -> DATE_PLAN ),

                    'order_name' => $row -> order_name,
                    'order_type_name' => $row -> order_type_name,
                    'dse_name' => $row -> dse_name,
                    'dse_count' => $row -> dse_count,
                    'user_name' => $row -> user_name,
                    'client_name' => $row -> client_name,

                    'summ_no' => $row -> summ_no,
                    'summ_v' => $row -> summ_v,
                    'summ_nv' => $row -> summ_nv,
                    'summ_n' => $row -> summ_n,

                    'pd1' => $pd1,
                    'pd2' => $pd2,
                    'pd3' => $pd3,
                    'pd4' => $pd4,
                    'pd5' => $pd5,

                    'pd6' => $pd6,
                    'pd7' => $pd7,
                    'pd8' => $pd8,
                    'pd9' => $pd9,
                    'pd10' => $pd10,

                    'pd11' => $pd11,
                    'pd12' => $pd12,
                    'pd13' => $pd13,
                    'pd14' => $pd14,

                    'pd_coop1' => $this -> getBreakApartPD( $row -> PD_coop1, 'pd_coop1'),
                    'pd_coop2' => $this -> getBreakApartPD( $row -> PD_coop2, 'pd_coop2'),

                    'pd1_conf' => $pd1_conf,
                    'pd2_conf' => $pd2_conf,
                    'pd3_conf' => $pd3_conf,
                    'pd4_conf' => $pd4_conf,
                    'pd5_conf' => $pd5_conf,

                    'pd6_conf' => $pd6_conf,
                    'pd7_conf' => $pd7_conf,
                    'pd8_conf' => $pd8_conf,
                    'pd9_conf' => $pd9_conf,
                    'pd10_conf' => $pd10_conf,

                    'pd11_conf' => $pd11_conf,
                    'pd12_conf' => $pd12_conf,
                    'pd13_conf' => $pd13_conf,
                    'pd14_conf' => $pd14_conf,

                    'pd_coop1_conf' => $pd_coop1_conf,
                    'pd_coop2_conf' => $pd_coop2_conf,

                    'stage' => $row -> stage,
                    'status' => $row -> status
                ];
        }
    }

    public function getTable()
    {
        $str = "<table id='order_table' class='tbl'>";
        $str .= $this -> getTableHeadHtml();
        $str .= $this -> getEmptyRowHtml();
        $str .= $this -> getTableBodyHtml();
        $str .= "</table>";
        return $str;
    }

    public function getTableHeadHtml()
    {
        $this -> cols = 19 ;

        $str = "<tr>
            <td class='bold' rowspan='2'>".$this -> conv("№")."</td>
            <td class='bold' colspan='4'>".$this -> conv("Подготовка производства")."</td>
            <td class='bold' colspan='2'>".$this -> conv("Комплектация")."</td>
            <td class='bold' colspan='2'>".$this -> conv("Кооперация")."</td>
            <td class='production bold' colspan='6'>".$this -> conv("Производство")."</td>
            <td class='bold' colspan='3'>".$this -> conv("Коммерция")."</td>
            <td>
                <span id='sort_status_arrow' data-sort='1' class='sort_arrow'>&#9660;&#9650;</span>
                <span id='sort_stage_arrow' data-sort='1' class='sort_arrow'>&#9660;&#9650;</span>".
                $this -> conv("Статус / этап").
           "</td></tr>";

        $str .= "<tr>
            <td>".$this -> conv("КД")."</td><td>".$this -> conv("Нормы<br>расхода")."</td>
            <td>".$this -> conv("МТК")."</td>
            <td class='bold'>".$this -> conv("Инструмент и<br>оснастка")."</td>

            <td>".$this -> conv("Проработка")."</td><td class='bold'>".$this -> conv("Поставка")."</td>
            <td>".$this -> conv("Проработка")."</td><td class='bold'>".$this -> conv("Поставка")."</td>
            <td>".$this -> conv("Дата<br>нач.")."</td><td>".$this -> conv("Дата<br>оконч.")."</td>

            <td class='pressable'><div>".$this -> conv("Вып<br>%")."</div><div class='arr_div'>&#9668;</div></td>

            <td class='hiddenly'>".$this -> conv("Объем<br>Н/Ч")."</td><td class='hiddenly'>".$this -> conv("Выполн.<br>Н/Ч")."</td><td class='hiddenly'>".$this -> conv("Ост.<br>Н/Ч")."</td>
            <td class='lbold'>".$this -> conv("Предоплата")."</td><td>".$this -> conv("Оконч.<br>расчет")."</td><td class='bold'>".$this -> conv("Поставка")."</td>

            <td>".$this -> conv("Ответственный")."</td>

            </tr>";

        return $str;
}

    public function getEmptyRowHtml( $class = "empty_row" )
    {
        return "<tr class='$class'><td colspan='".$this -> cols."'></td></tr>";
    }

    public function getTableBodyHtml()
    {
        $str = '';

        $prepare_change_group = in_array( $this -> user_id, getResponsiblePersonsID( self::PREPARE_GROUP ) ) ? 1 : 0 ;
        $equipment_change_group = in_array( $this -> user_id, getResponsiblePersonsID( self::EQUIPMENT_GROUP )  ) ? 1 : 0 ;
        $cooperation_change_group = in_array( $this -> user_id, getResponsiblePersonsID( self::COOPERATION_GROUP )  ) ? 1 : 0 ;
        $production_change_group = in_array( $this -> user_id, getResponsiblePersonsID( self::PRODUCTION_GROUP )  ) ? 1 : 0 ;
        $commertion_change_group = in_array( $this -> user_id, getResponsiblePersonsID( self::COMMERTION_GROUP )  ) ? 1 : 0 ;

        $group_member =
        ( $prepare_change_group * 16 ) |
        ( $equipment_change_group * 8 ) |
        ( $cooperation_change_group * 4 ) |
        ( $production_change_group * 2 ) |
        ( $commertion_change_group * 1 ) ;

        $stage_arr = getStagesArray( $this -> ajax );

                foreach( $this -> orders AS $key => $value )
                {

                $status = $value['status'];
                $status_sel = "<select ".( $commertion_change_group ? '' : 'disabled' ).">";

                foreach( $this -> statuses AS $val )
                {
                    $id = $val['id'];
                    $stat_opt = $val['status'];
                    $status_sel .= "<option value='$id'";
                    if( $id == $status )
                        $status_sel .= " selected>";
                        else
                            $status_sel .= ">";
                    $status_sel .= "$stat_opt</option>";
                }

                $status_sel .= "</select>";

                $id = $value['order_id'];

                $stage = $stage_arr[ 0 ];

                if( isset( $value['stage'] ) )
                    if( isset(  $stage_arr[ $value['stage'] ] ) )
                        $stage = $stage_arr[ $value['stage'] ];

                $str .= "<tr data-id='$id'>
                         <td class='line_td r_bold' rowspan='2'><span class='num'>".$value['line']."</span></td>
                         <td class='ord_head' colspan='19'>
                         <img class='task_carries' src='uses/redo_16.png' title='".conv("Просмотр переносов сроков")."'/>
                         <a data-href='".$value['order_name']."' class='ord_link' data-id='$id'>".$this -> conv( 	$value['order_type_name']." ".$value['order_name']." " )."</a>
                         <span>".
                    $this -> conv(
                            $value['dse_name']." - ".
                            $value['dse_count']." шт. ".
                            $value['client_name']
                        )
                    ." [ ".$value['create_date']." ]".
                    ( strlen( $value['supply_date'] ) ?
                    " [ ".$this -> conv("Дата пл. отгрузки :")." ".$value['supply_date']." ]"
                    :'' ).
                    "</span>
                    <div class='status_wrap_div'>
                    <div class='status_div'>$status_sel</div>
                    <div class='stage_div'><span id='data-stage_$id' data-stage='".( $value['stage'] )."'>$stage</span></div>
                    </div>
                    </td>
                    </tr>";

                $str .= "<tr data-id='$id' data-group_member='$group_member'>
                                ".$this -> getFormattedField( $value['pd1'], $value['pd1_conf'], $prepare_change_group )."
                                ".$this -> getFormattedField( $value['pd2'], $value['pd2_conf'], $prepare_change_group )."
                                ".$this -> getFormattedField( $value['pd3'], $value['pd3_conf'], $prepare_change_group)."
                                ".$this -> getFormattedField( $value['pd13'],  $value['pd13_conf'], $prepare_change_group, 'r_bold')."

                                ".$this -> getFormattedField( $value['pd4'],  $value['pd4_conf'], $equipment_change_group )."
                                ".$this -> getFormattedField( $value['pd7'],  $value['pd7_conf'], $equipment_change_group, 'r_bold' )."

                                ".$this -> getFormattedField( $value['pd_coop1'],  $value['pd_coop1_conf'],  $cooperation_change_group )."
                                ".$this -> getFormattedField( $value['pd_coop2'],  $value['pd_coop2_conf'],  $cooperation_change_group, 'r_bold')."

                                ".$this -> getFormattedField( $value['pd12'],  $value['pd12_conf'],  $production_change_group )."
                                ".$this -> getFormattedField( $value['pd8'],   $value['pd8_conf'], $production_change_group,'r_bold')."

                                <td class='summ_field'>".$value['summ_v']."</td>
                                <td class='summ_field hiddenly'>".$value['summ_n']."</td>
                                <td class='summ_field hiddenly'>".$value['summ_nv']."</td>
                                <td class='summ_field hiddenly'>".$value['summ_no']."</td>

                                ".$this -> getFormattedField( $value['pd9'],  $value['pd9_conf'],  $commertion_change_group, 'l_bold')."
                                ".$this -> getFormattedField( $value['pd10'], $value['pd10_conf'], $commertion_change_group )."
                                ".$this -> getFormattedField( $value['pd11'], $value['pd11_conf'], $commertion_change_group )."

                                <td  class='l_bold'><input class='td_select' value='".$this -> conv( $value['user_name'] )."'/></td>
                                </tr>";
                }

        return $str ;
    }


    public function recalcStages()
    {
// ***********************************************************************************

        $query =        "
                        SELECT
                        okb_db_zak.ID id,

                        LEFT( okb_db_zak.PD1, 1 ) kd_state,

                        okb_db_zak.PD1,
                        okb_db_zak.PD2,
                        okb_db_zak.PD3,
                        okb_db_zak.PD4,
                        okb_db_zak.PD7,
                        okb_db_zak.PD8,
                        okb_db_zak.PD9,
                        okb_db_zak.PD10,
                        okb_db_zak.PD11,
                        okb_db_zak.PD12,
                        okb_db_zak.PD13

                        FROM
                        okb_db_zak
                        WHERE 1
                        ";

        try
        {
            $stmt = $this -> pdo -> prepare( $query );
            $stmt->execute();
        }

        catch (PDOException $e)
        {
          die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        $this->orders = [];

        while ( $row = $stmt->fetch( PDO::FETCH_OBJ ))
        {

                    $pd1 = getBreakApartPD( $row -> PD1 );
                    $pd2 = getBreakApartPD( $row -> PD2 );
                    $pd3 = getBreakApartPD( $row -> PD3 );
                    $pd4 = getBreakApartPD( $row -> PD4 );
                    $pd7 = getBreakApartPD( $row -> PD7 );
                    $pd8 = getBreakApartPD( $row -> PD8 );
                    $pd9 = getBreakApartPD( $row -> PD9 );
                    $pd10 = getBreakApartPD( $row -> PD10 );
                    $pd11 = getBreakApartPD( $row -> PD11 );
                    $pd12 = getBreakApartPD( $row -> PD12 );
                    $pd13 = getBreakApartPD( $row -> PD13 );

                    $stage_prepare  = MakeLogicData( $pd1['log_state'] , $pd2['log_state'] , $pd3['log_state'] , $pd13['log_state'] );
                    $stage_equipment   = MakeLogicData( 1, 1, $pd4['log_state'] , $pd7['log_state'] );
                    $stage_production   = MakeLogicData( 1, 1, $pd8['log_state'] , $pd12['log_state'] );
                    $stage_commertion  = MakeLogicData( 1, $pd9['log_state'] , $pd10['log_state'] , $pd11['log_state'] );

            $stage = stageLogic
                      (
                        $stage_prepare,
                        $stage_equipment,
                        $stage_production,
                        $stage_commertion
                      );

            $this->orders [] =
                [
                    'id' => $row -> id,
                    'stage' => $stage
                ];
        }


        foreach( $this->orders AS $order )
        {

            $query = "UPDATE okb_db_zak SET ID_stage = ".( $order['stage'])." where ID=".$order['id'];

            try
            {
                $stmt = $this -> pdo -> prepare( $query );
                $stmt->execute();
            }

            catch (PDOException $e)
            {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't update data : " . $e->getMessage());
            }

        }

    }
// **********************************************************************************
  public function getFormattedField( $value , $confirmation, $can_change, $td_class='' )
  {
    $state = $value['state'];
    $date = $value['last_date'];
    $timestamp  = strtotime( $date );
    $today = strtotime( date('d.m.Y') );
    $count = 1 * $value['date_changes_count'];
    $class = '';
    $a_class = "count_list_link";
    $disable = '';

    if( !$can_change )
      $disable = 'disabled';

    if( $state != 'checked' )
    {
        if ($timestamp < $today )
            $class = "cell_state_over";
        if( !$count )
            $class = '';
    }
    else
            $class = 'cell_state_good';


	if (
        $confirmation == 0 && 
        $count > 0 && 
        $value['str_id'] != 'pd10' &&
    	$value['str_id'] != 'pd9' &&
    	$value['str_id'] != 'pd13' &&
    	$value['str_id'] != 'pd4' &&
    	$value['str_id'] != 'pd12' &&
    	$value['str_id'] != 'pd11') 
    {
		if ($state == 'checked') 
			$class = 'cell_state_not_conf';
	}

    if( $count > 1)
        $class .= " dotted";

    return "<td class='$td_class'  data-str-id='".$value['str_id']."'>
            <div class='$class'>
            <a class='ch_date_link $disable'>$date</a>
            <br>
            <input class='ch_state' data-conf='$confirmation' data-can_change = '$can_change' type='checkbox' $state>
            <a class='$a_class'>[$count]
            </a>
            </div>
            </td>";
  }

    public function decodeDate( $value , $delimiter = '.' )
    {
        if( $value == 0 || strlen( $value ) == 0 )
            return '';

        $str = strval( $value );
        $outstr = '';
        $year = substr( $str , 0, 4 );
        $month = substr( $str , 4, 2 );
        $day = substr( $str , 6, 2 );

        if( $delimiter == '.')
            $outstr = "$day.$month.$year";

        if( $delimiter == '-')
            $outstr = "$year-$month-$day";
        return $outstr;
    }

    public function getQuery()
    {
        return $this -> query ;
    }

    public function getOrder()
    {
        return $this -> order ;
    }
    public function getWhere()
    {
        return $this -> where ;
    }


}

