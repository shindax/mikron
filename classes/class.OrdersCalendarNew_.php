<?php
error_reporting( E_ALL );
error_reporting( 0 );

require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.BaseOrdersCalendar_.php" );

class OrdersCalendar_ extends BaseOrdersCalendar_
{
    function __construct( $pdo, $user_id, $year = 0 , $month = 0 )
    {
        parent::__construct( $pdo, [ $user_id ], $year, $month );
    }

    public function GetHeadTitle()
    {
        return $head_title = "<div class='head'>
                          <div>
                            <div><h2>".conv( "Редактирование рабочего календаря")."</h2><h3>".conv( "Сотрудник : ").conv($this -> GetUserName())."</h3></div>
                            <div><span class='label'>".conv( "Дата").":</span><input type='text' id='datepicker' readonly='readonly'></div>
                          </div>
                          <div id='pie_div'>
                          </div>

                          </div>";
    }

    public function GetWorkingCalendar()
    {
          $str =  $this -> GetTableBegin();
          $str .= conv( $this -> GetDataTable() );
          $str .= $this -> GetTableEnd();
          return $str ;
    }


            private function GetTableBegin()
            {
            return "<div class='row'>
                                  <table id='ord_table' class='tbl table-striped'>
                                  <col width='45%'><col width='2%'><col width='5%'><col width='5%'><col width='5%'><col width='15%'><col width='5%'><col width='5%'>
                                  <tr class='first'>
                                      <td rowspan='2' colspan='2'>".conv("Проект / задание")."</td>
                                      <td colspan='3'><span>".conv("Затрачено часов")."</span></td>
                                      <td rowspan='2'>".conv("% выполнения")."</td>
                                      <td rowspan='2'>".conv("Часов за месяц")."</td>
                                      <td rowspan='2'>".conv("Часов всего")."</td>
                                  </tr>
                                  <tr class='first'>
                                  <td><span id='prev_prev_date_span'></span></td>
                                  <td><span id='prev_date_span'></span></td>
                                  <td><span id='cur_date_span'></span></td>
                                  </tr>
                                  ";
            }
            private function GetTableEnd()
            {
                return "</table></div></div>";
            }

            public function GetDataTable()
            {
                    $str = '';
                    $str .= $this -> GetItemRow( $this -> projects );
                    $str .= $this -> GetItemRow( $this -> orders );
                    $str .= $this -> GetItemRow( $this -> unlinked );
                    return $str ;
            }

    private function GetItemRow( $input_section )
    {
        $color = '#dddf';
        $str = '';

        foreach(  $input_section AS $section )
        {
            $total_hour_count = $section['total_hour_count'];

            if( isset( $section['project_id'] ))
                {
                    $name = $section['project_name'];
                    $title = 'Проект';
                    $parent_id = "project_".$section['project_id'];
                }
                else
                {
                    if( isset( $section['zak_id'] ) )
                    {
                        $name = $section['zak_type'] . " " . $section['zak_name'] . " " . $section['zak_dse_name'];
                        $title = 'Заказ';
                        $parent_id = "zak_".$section['zak_id'];
                    }
                        else
                        {
                            $name = 'Задания вне проектов и заказов';
                            $title = '';
                            $parent_id = "unlinked";
                        }
                    }

            $str .= "<tr class='first' id='$parent_id'>
                        <td class='field AL name'>
                        <div>
                        <img data-show='0' class='coll_exp' src='/uses/collapse.png'/>
                        <span>$title</span> : <span>$name</span> ( <span>".count( $section['items'])."</span> )
                        </div></td>";
            $str .= "<td class='field legend'><div style='background:$color;'></div></td>";
            $str .= "<td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class='field AC'><span class='total_month_hours_span'>0</span></td>
                        <td class='field AC total_hrs'><span>$total_hour_count</span></td>
                        </tr>";

            foreach( $section['items'] AS $item )
            {
                $color = '#ffff';

                $order_id = $item['order_id'];
                $month_hours = $this -> GetOrderChartHours( $order_id, $this -> month, $this -> year );
                $month_hours = $month_hours ? $month_hours : 0 ;

                $debug_id = "<span>( $order_id )</span>";
                $debug_id = '';

                $str .= "<tr data-id='$order_id' class='item_row hidden' data-parent_id='$parent_id'><td class='field AL name'><span>" . $item['order_name'] ."</span>$debug_id</td>";
                $str .= "<td class='field legend'><div style='background:$color;'></div></td>";
              $str .= "
                            <td class='field  spinner'>
                                <input id='spinner-prev-prev-".$item['order_id']."'>
                            </td>

                            <td class='field  spinner'>
                                <input id='spinner-prev-".$item['order_id']."'>
                            </td>

                            <td class='field spinner'>
                                <input id='spinner-now-".$item['order_id']."'>
                            </td>

                            <td class='field slider'>
                                <div>
                                    <div data-perc='".$item['comp_perc']."' id='slider-".$item['order_id']."'></div>
                                    <div><input type='text' id='amount-".$item['order_id']."' readonly></div>
                                </div>
                            </td>
                            <td class='field AC'><span class='month_hours_span'>$month_hours</span></td>
                            <td class='field AC'><span class='hours_span'>".$item['hour_count']."</span></td>";

                $str .= "</tr>";
             }
        }

        return $str ;
    }

}
