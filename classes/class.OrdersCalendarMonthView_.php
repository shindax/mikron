<?php
require_once( "class.BaseOrdersCalendar.php" );

class OrdersCalendarHtml extends BaseOrdersCalendar
{
    function __construct( $pdo, $user_id_arr )
    {
        parent::__construct( $pdo, $user_id_arr );
    }

    private function GetMonthTableBegin( $days_count )
    {
        $str = "<div class='row'>
                      <table id='ord_table' class='tbl table-striped'>
                      <col width='40%'>
                      <col width='3%'>";

        for( $i = 1 ; $i <= $days_count ; $i++ )
            $str .= "<col width='2%'>";

        $str .= "<col width='3%'>";
        $str .= "<col width='3%'>";

        $str .= "<tr class='first'>
                    <td colspan='2'>Проект / задание</td>
                   ";
        for( $i = 1 ; $i <= $days_count ; $i++ )
            $str .= "<td class='field AC' >$i</td>";

        $str .= "<td class='field AC'>М</td>";
        $str .= "<td class='field AC'>В</td>";

        $str .= "</tr>";

        $str .= "<tr class='total'>
                    <td class='field AC' colspan='2'>Всего за месяц</td>
                      ";
        for( $i = 1 ; $i <= $days_count ; $i++ )
            $str .= "<td class='field AC' data-day='$i'>-</td>";

        $str .= "<td class='field AC' data-day='M'>-</td>";
        $str .= "<td class='field AC' data-day='T'>-</td>";

        $str .= "</tr>";


        return $str ;
    }
    private function GetTableEnd()
    {
        return "</table></div></div>";
    }

    public function GetTable( $month, $year )
    {
        $days_count = date('t', strtotime( "$year-$month-01" ) );
        $str = $this -> GetMonthTableBegin( $days_count );
        $str .= $this -> GetDataTable( $month, $year );
        $str .= $this -> GetTableEnd();
        return $str ;
    }

    private function GetDataTable( $month, $year )
    {
        $str = '';
        $str = $this -> GetItemRow( $this -> projects , $month, $year );
        $str .= $this -> GetItemRow( $this -> orders , $month, $year );
        $str .= $this -> GetItemRow( $this -> unlinked , $month, $year );

        return $str ;
    }

    private function GetItemRow( $input_section, $month, $year )
    {
        $str = '';
        $days_count = date('t', strtotime( "$year-$month-01" ) );
        foreach(  $input_section AS $section )
        {
            $color = "#98b8e2" ;
            $sect_data = $this -> GetMonthViewerData( $section, $month, $year );
            $head_data = [];
            $total_month_hrs = 0;
            $total_hour_count = $section['total_hour_count'];

            if( isset( $sect_data[0] ) )
            {
                $head_data = $sect_data[0];
                unset($sect_data[0]);
            }

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
            <span>$title</span> : <span>$name</span>&nbsp;(<span>".count( $section['items'])."</span>)&nbsp;</div></td>";
            $str .= "<td class='field legend'><div style='background:$color;'></div></td>";
            for ($i = 1; $i <= $days_count; $i++)
            {
                $str .= "<td class='field AC' data-key='$i'>";
                if( isset( $head_data[ $i ] ) )
                {
                    $str .= $this->MaskValue( $head_data[$i] );
                    $total_month_hrs += $head_data[$i];
                }
                else
                    $str .='-';
                $str .= "</td>";
            }

            $str .= "<td class='field AC total_month_hrs' data-key='M'><span>".( $this -> MaskValue( $total_month_hrs ))."</span></td>
                     <td class='field AC total_hrs'  data-key='T'><span>".( $this -> MaskValue( $total_hour_count ))."</span></td>";
            $str .= "</tr>";

            foreach( $section['items'] AS $item )
            {
                $color = '#fff';
                $total_month_hrs = 0;
                $total_hour_count = $item[ 'hour_count' ];
                $order_id = $item['order_id'];
                // <span> ( $order_id )</span></td>";
                $str .= "<tr data-id='$order_id' class='item_row hidden' data-parent_id='$parent_id'>
                            <td class='field AL name'>
                            <a target='_blank' href='index.php?do=show&formid=122&id=".( $order_id )."'><span>" . $item['order_name'] ."</span></a>
                            ";

                $str .= "<td class='field legend'><div style='background:$color;'></div></td>";
                for ($i = 1; $i <= $days_count; $i++)
                {
                    $class = '';
                    $hr = '';
                    if( isset( $sect_data[ $order_id ][ $i ] ) )
                    {
                        $hr = $sect_data[ $order_id ][$i];
                        if( $hr != 0 && $hr != '-' )
                        {
                            $total_month_hrs += $hr;
                            $class = 'hours';
                        }
                    }
                    else
                        $hr ='-';

                    $str .= "<td class='field AC $class'>$hr</td>";
                }

                $str .= "<td class='field AC hours'><span><strong>".( $this -> MaskValue( $total_month_hrs ))."</strong></span></td>
                     <td class='field AC hours'><span><strong>".( $this -> MaskValue( $total_hour_count ))."</strong></span></td>";
                $str .= "</tr>";
        }

        }

        return $str ;
    }

    private function MaskValue( $value )
    {
        return $value ? $value : '-';
    }

    private function GetMonthViewerData( $section, $month, $year )
    {
        foreach( $this -> user_id_arr AS $user_id )
            $users_arr [] = $user_id ;

        $users_arr = join(',', $users_arr );

        $total_month_hrs = 0 ;
        $dates = [] ;

        if( !isset( $section['items'] ) )
            foreach( $section AS $key => $sect )
                $arr['items'][ $key ] = $sect ;

        foreach( $section['items'] AS $order )
        {
        $order_id = $order['order_id'];

        try
        {
            $query ="
                                SELECT
                                DAY( date ) day,
                                hour_count
                                FROM
                                okb_db_working_calendar
                                WHERE
                                order_id = $order_id
                                AND
                                user_id IN( $users_arr )
                                AND
                                MONTH( date ) = $month
                                AND
                                YEAR( date ) = $year
                                ";

            $stmt = $this -> pdo->prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
            die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        while ( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
        {
            $hrs = $row -> hour_count;
            $dates[ $order_id ][ $row -> day ] = $this -> MaskValue( $hrs );
            if( isset( $dates[ 0 ][ $row -> day ] ) )
                $dates[ 0 ][ $row -> day ] += $hrs ;
                    else
                        $dates[ 0 ][ $row -> day ] = $hrs ;
        }
      }
        return $dates;
    }
}
