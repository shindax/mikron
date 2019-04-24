<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/classes/class.EntranceControl.php" );

class EntranceControlCSV extends EntranceControl
{
    const EOL = PHP_EOL;
    // const EOL = "<br>";

    public function __construct( $pdo, $id, $filter = '' )
    {
        parent :: __construct( $pdo, $id, $filter );
    }

    public function GetData()
    {
        $data = parent :: GetData();
        $common_data = [];

        $common_data['date'] = $this -> date;
        $common_data['number'] = $this -> number;        
        $common_data['proc_type_name'] = $this -> proc_type_name;
        $common_data['client_name'] = $this -> client_name;

        return [ 'common_data' => $common_data, 'data' => $data ];
    }

    public function GetCSV()
    {
        $data = $this -> GetData();
        $common_data = $data['common_data'];
        $data = $data['data'];

        $page_number = $common_data['number'];
        $date = $common_data['date'];
        $proc_type_name = $common_data['proc_type_name'];
        $client_name = html_entity_decode( $common_data['client_name'] );
        $str = "";

        foreach( $data AS $value )
        {
            $item_count = $value['item_count'];
            $operation_name = html_entity_decode( $value['operation_name'] );
            for( $i = 0 ; $i < $item_count ; $i ++ )
            {
                $zak_dse_name = html_entity_decode( $value['items'][ $i ]['zak_dse_name'] );
                $dse_name = html_entity_decode( $value['items'][ $i ]['dse_name'] );
                $zak_name = html_entity_decode( $value['items'][ $i ]['zak_name'] );
                $ent_cont_dse_draw = html_entity_decode( $value['items'][ $i ]['ent_cont_dse_draw'] );
                $count = $value['items'][ $i ]['count'];
                $inwork = $value['items'][ $i ]['inwork_state'] ? conv("в работе") : conv("не в работе");
                
                $reject_state = $value['items'][ $i ]['reject_state'];
                $rework_state = $value['items'][ $i ]['rework_state'];
                $pass_state = $value['items'][ $i ]['pass_state'];

                $str .= "$date;$page_number;$proc_type_name;$client_name;$operation_name;$zak_name;$dse_name;$zak_dse_name;$ent_cont_dse_draw;$count;$inwork;$reject_state;$rework_state;$pass_state".self::EOL;
            }
        }

        return $str;
    }
}

