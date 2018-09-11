<?php
header('Content-Type: text/html; charset=windows-1251');

echo "<script>var can_add = 1 ; </script>";

function GetColor( $color )
{
    $colors = array( 
    '#D3D3D3',
    '#FFE4E1',        
    '#FFE4B5',
        
    '#FFDAB9',        
    '#EEE8AA',        
    '#F0E68C',        
    '#BDB76B',
    );
    return $colors[ $color ];
}

// �������� ��� ������������ �� ID �� ������� okb_db_resurs
function GetDateByStatus( $id, $status )
{
    if( $status == '���������' )
        $status = '���������';
    $query = "
        SELECT 
        DATE_FORMAT( DATA, '%d.%m.%Y' ) date
        FROM okb_db_itrzadan_statuses
        WHERE ID_edo=".$id." AND STATUS='".$status."'";
    
        $result = dbquery( $query );
        $row = mysql_fetch_assoc( $result );
        
    return $row['date'];
}

// �������� ��� ������������ �� ID �� ������� okb_db_resurs

function GetPerson( $person_id )
{
   if( $person_id == '')
       return '';
    $query = "
        SELECT 
        persons.NAME person_name 
        FROM okb_db_resurs persons  
        WHERE ID=".$person_id;
    
        $result = dbquery( $query );
        $row = mysql_fetch_assoc( $result );
        
    return $row['person_name'];
}



function GetZakName( $zak_id )
{
   global $db_prefix ;
    
   if( $zak_id == '' || $zak_id == '0' || $zak_id == 0 )
       return 0;

   $query = "
              SELECT
              okb_db_zak_type.zak_type zak_type,
              NAME, 
              DSE_NAME,
              PID 
              FROM ".$db_prefix."db_zak zak 
              INNER JOIN okb_db_zak_type ON zak.TID = okb_db_zak_type.ID 
              where zak.ID=".$zak_id ;
      
    $result = dbquery( $query );
    $zak = mysql_fetch_assoc( $result );
    $zak_name = $zak["NAME"];    
    $zak_prefix = $zak["zak_type"];
    $zak_dse_name = $zak["DSE_NAME"];
    $zak_parent = $zak["PID"];    

    return Array( 
                    'zak_id' => $zak_id, 
                    'zak_parent' => $zak_parent,
                    'zak_prefix' => $zak_prefix ,        
                    'zak_name' => $zak_name , 
                    'zak_dse_name' => $zak_dse_name
                );
}

function sortByDivName( $a, $b ) 
{
    $field = 'DivName';
    if ( $a[ $field ] == $b[ $field ] ) 
                return 0;
     return ( ( $a[ $field ] < $b[ $field ] ) ? -1 : 1 ) ;
}

function sortByDivTypeFunc( $a, $b ) 
{
    $field = 'DivType';
    
    if ( $a[ $field ] == $b[ $field ] )
        {
             $subfield1 = 'DivBoss';
                if ( $a[ $subfield1 ] == $b[ $subfield1 ] ) 
                {
                    $subfield2 = 'DivName';
                        if ( $a[ $subfield2 ] == $b[ $subfield2 ] ) 
                            return 0;
                    return ( ( $a[ $subfield2 ] < $b[ $subfield2 ] ) ? -1 : 1 ) ;
                }
             return ( ( $a[ $subfield1 ] < $b[ $subfield1 ] ) ? 1 : -1 ) ;                
    }
     return ( ( $a[ $field ] < $b[ $field ] ) ? 1 : -1 ) ;
}


function GetEmployees( & $in_arr )
{
   
    foreach( $in_arr AS $key => $value )
    {
      $div_id = $value['DivID'];
      
      if( ! $div_id || !isset( $div_id ) || $div_id == '' )
          continue ;
      
   // ��������� SELECT-������ 
            $query ="SELECT * FROM okb_db_shtat 
                     WHERE ID_otdel=".$div_id." AND NAME<> '' ORDER BY BOSS DESC, NAME"; 
            $result = dbquery( $query );
  
    while( $emp_row = mysql_fetch_assoc( $result ))        
        {
            $div_by_order = array( 
                            'DivName'               => '������� �� �������',
                            'DivType'               => 'div_type_order_group',
                            'DivBoss'               => '' ,
                            'DivHeadResID'          => '' ,
                            'DivHeadShtatID'        => '',
                            'DivTaskCount'          => 0,
                            'DivCompletedTaskCount' => 0,
                            'childs'                => array()
                          );

            $div_out_of_order = array( 
                            'DivName'               => '������� ��� �������',
                            'DivType'               => 'div_type_out_of_order',
                            'DivBoss'               => '' ,
                            'DivHeadResID'          => '' ,
                            'DivHeadShtatID'        => '',
                            'DivTaskCount'          => 0,
                            'DivCompletedTaskCount' => 0,                
                            'childs'                => array()
                          );

            $div_project = array( 
                            'DivName'               => '������� �� ��������',
                            'DivType'               => 'div_type_project',
                            'DivBoss'               => '' ,
                            'DivHeadResID'          => '' ,
                            'DivHeadShtatID'        => '',
                            'DivTaskCount'          => 0,
                            'DivCompletedTaskCount' => 0,                
                            'childs'                => array()
                          );

            
            
            $in_arr[$key]['childs'][] = 
                    array( 
                            'DivName'               => $emp_row['NAME'],
                            'DivType'               => 'div_type_person',
                            'DivBoss'               => $emp_row['BOSS'] ,                        
                            'DivHeadResID'          => $emp_row['ID_resurs'] ,
                            'DivHeadShtatID'        => $emp_row['ID'],
                            'DivTaskCount'          => 0,
                            'DivCompletedTaskCount' => 0,                        
                            'childs'                => array( $div_by_order, $div_out_of_order, $div_project )
                          );
            
        }
        if( count( $value['childs']))
            GetEmployees( $in_arr[$key]['childs']);
    }
}

function SortSubDivisions( & $in_arr )
{
    foreach( $in_arr AS $key => $value )
    {
        usort( $in_arr[$key]['childs'] , 'sortByDivName' );
        
        if( count( $in_arr[$key]['childs'] ))        
            SortSubDivisions( $in_arr[$key]['childs']);
    }
}


function SortByDivType( & $in_arr )
{
    foreach( $in_arr AS $key => $value )
    {
      if
      ( 
        $in_arr[$key]['childs'][0]['DivType'] == 'div_type_order_group' ||
        $in_arr[$key]['childs'][0]['DivType'] == 'div_type_out_of_order' ||
        $in_arr[$key]['childs'][0]['DivType'] == 'div_type_project'
      )
        continue ;  // �� ����������� ���� �������, 
                    //  �.�. ������� '�� �������', '��� �������', '�� ��������' �.�.�.
      
        usort( $in_arr[$key]['childs'] , 'sortByDivTypeFunc' );
        
        if( count( $in_arr[$key]['childs'] ))        
            SortByDivType( $in_arr[$key]['childs'] );
    }
}

// ������� ������������ ������ � ������� $in_value � ������
// $in_arr['childs'] ���� �������
function FindVal2( & $in_arr , $in_value , $search_key )
{
    $pid = $in_value['DivPID'];
    $retval = 0 ;
    
    foreach( $in_arr AS $key => $value )
    {
        if( $value[ $search_key ] == $pid )
        {
            array_push( $in_arr[ $key ]['childs'], $in_value );            
            return $key ;
        }

        if( count( $in_arr[ $key ]['childs'] ) )
        {
            $retval = FindVal( $in_arr[ $key ]['childs'], $in_value, $search_key );
            if( $retval !== NULL )
                return $retval ;
        }
    }
    
    return NULL ;
}


// ������� ������������ ������ � ������� $in_value � ������
// $in_arr['childs'] ���� �������
function FindVal( & $in_arr , $in_value , $parent_key, $child_key, $child_field = 'childs')
{
    $pid = $in_value[ $child_key ];
    $retval = 0 ;
    
    foreach( $in_arr AS $key => $value )
    {
        if( $value[ $parent_key ] == $pid )
        {
            array_push( $in_arr[ $key ][ $child_field ], $in_value );
            return $key ;
        }

        if( count( $in_arr[ $key ][ $child_field ] ) )
        {
            $retval = FindVal( $in_arr[ $key ][ $child_field ], $in_value, $parent_key, $child_key, $child_field );
            if( $retval !== NULL )
                return $retval ;
        }
    }
    
    return NULL ;
}

function getDivHead( $div_id ) 
{
  
      // ��������� SELECT-������ 
  $query ="SELECT * FROM okb_db_shtat 
          WHERE ID_otdel=".$div_id." AND BOSS='1' AND NAME<>''"; 
//  $boss = $mysqli -> query( $query );
    $boss = dbquery( $query );
  
//  if( ! $boss ) 
//      exit("������ ��������� � ��������"); 
 
  // ���� ������� ���� �� ���� ������, ������� ������ 
//  if( $boss -> num_rows )
//  {
//        $boss_name = $boss -> fetch_array();
        $boss_name = mysql_fetch_assoc( $boss );
        return Array(
                       'div_head_name' => $boss_name['NAME'],
                       'div_head_res_id' => $boss_name['ID_resurs'],
                       'div_head_shtat_id' => $boss_name['ID']
        );
        
  //}
}


function getEnterpriseState() 
{  
  $tempArr = array();  
  $entState = array();  
   
// ��������� SELECT-������ 
  $query2 ="
          SELECT * FROM okb_db_otdel 
          WHERE PID<>0 AND NAME<>''"; 

  $query ="
          SELECT * FROM okb_db_otdel 
          WHERE NAME<>''"; 
  
  $shtat = dbquery( $query );            
  
  while( $rec = mysql_fetch_assoc( $shtat ))  
  {
      $div_head = getDivHead( $rec['ID'] );
      $tempArr[] = array( 
                            'DivID'                 => $rec['ID'],
                            'DivPID'                => $rec['PID'],
                            'DivType'               => 'div_type_division',
                            'DivName'               => $rec['NAME'] ,
                            'DivOboz'               => $rec['OBOZ'] ,
                            'DivHeadName'           => $div_head['div_head_name'] ,
                            'DivHeadResID'          => $div_head['div_head_res_id'] ,
                            'DivHeadShtatID'        => $div_head['div_head_shtat_id'] ,
                            'DivTaskCount'          => 0, 
                            'DivCompletedTaskCount' => 0,
                            'childs'                => array() 
                          );
  }
 
  // ������� ������������� �������� ������
  foreach( $tempArr AS $key => $value )
  {
      if( $value['DivPID'] == 0 )
      {
          $entState[] = $value ;
          unset( $tempArr[$key] );
      }
  }

  $count = 0 ;

  // ������� ������ �������� ������������� �������� ������ ������ ������
  // susp
  while( count( $tempArr ) && $count ++ < 1000000 )
   {
    foreach( $tempArr AS $key => $value )
      if( ( $index = FindVal( $entState, $value, 'DivID', 'DivPID' )) !== NULL )
          unset( $tempArr[$key] );
   }
   if( $count >= 1000000 )
      echo "TaskByDivisionFunctions 'while' overflow";

//    usort( $entState , 'sortByDivName' );
    SortSubDivisions( $entState );
    GetEmployees( $entState );
    SortByDivType( $entState );    
    return $entState ;
} 


// ��������� � ������� ������������� ������ �������
function CreateChildTree( $item_chield , $line, $in_name = 0, $spacing = '5', $color = 1 )
{
    // ���� ��� �������, �� ������ �� �������
    if( ! $item_chield['DivTaskCount'] )
        return ;
    
    if( $line == 0 )
        $line = '';
    
    if( $in_name )
        {
            if( $item_chield['DivType'] == 'div_type_person' )
               $style = 'background-color:'.GetColor( $color + 3 ) ;
            if( $item_chield['DivType'] == 'div_type_division' )
               $style = 'background-color:'.GetColor( $color ) ;

            $color ++ ;
            $style .= ';display:none';
        }
    
    $child_space = 5 ;
    
    if( strlen( $item_chield['DivHeadName']) )
        $div_name = " ( ". $item_chield['DivHeadName']." )";
            else 
                $div_name = '';
           
    $name = $item_chield['DivName'].$div_name ;
    
    $div_task_count = $item_chield['DivTaskCount'] ;
    $div_completed_task_count = $item_chield['DivCompletedTaskCount'] ;

    $progress_bar = '<progress value="'.$div_completed_task_count.'" max="'.$div_task_count.'"></progress>';

    if( $item_chield['DivType'] != 'div_type_task' )
            $name .= ' ( '.$div_task_count.' / '.$div_completed_task_count.' )';

    if( $item_chield['DivType'] == 'div_type_person' && $item_chield['DivBoss'] )
        $name = '<i style="color:navy"><b>'.$name.'</b></i>';
        
    $childs = $item_chield['childs'];
    $chield_count = count ( $childs );
   
    $row_class = '';    

    $zak_id = $item_chield['zak_id'];    

    if( isset( $zak_id ) && $zak_id )
         $zak_link = '<div class="padded"><img onclick="ZakView('.$zak_id.')" src="/uses/view.png"></div>' ;

    if( $chield_count )
        {
            $name = '<span style="padding-left:'.$offset.'px" class="collspan">&#9658;</span>'.$name ;
            $row_class = 'collapsed';
        }
         else
            $name = '<span style="padding-left:'.$offset.'px">&#9899;&nbsp;&nbsp;</span>'.$name ;

    // ���������� id ��� ������        
    $id = uniqid( $in_name , 1 ); 

    $zadan_id = $item_chield['zadan_id'];
    $executor = GetPerson( $item_chield['zadan_executor']);
    $creator = GetPerson( $item_chield['zadan_creator']);
    $checker = GetPerson( $item_chield['zadan_checker']);    
    
    $date_of_beg_plan  = $item_chield['zadan_date_of_beg_plan'];
    $date_of_perf_plan = $item_chield['zadan_date_of_perf_plan'];
    $date_of_perf_fact = $item_chield['zadan_date_of_perf_fact'];    
    $div_type = $item_chield['DivType']; 
    $state = $item_chield['zadan_status'];
   
    $state_class = '';

    if( $state == '������������' )
        $state_class = 'state_annulated';
    
    if( $state == '���������' )
        $state_class = 'state_executed';
    
    if( $state == '���������')
        $state_class = 'state_completed';
    
    if( $state == '������� � ����������')
        $state_class = 'state_accepted_to_work';

    if( $state == '�� ���������')
        $state_class = 'state_rework';

    if( $state == '�������')
        $state_class = 'state_accepted';

    if( $state == '�����������' )
        $state_class = 'state_viewed';

    $link = '<a class="link" target="_blank" href="/index.php?do=show&formid=122&id='.$zadan_id.'">'.$zadan_id.'</a>';
    
    if( $item_chield['DivType'] == 'div_type_division' )    
        $link = '<div class="padded"><img src="/uses/group.png"></div>' ;

    if( $item_chield['DivType'] == 'div_type_person' )
        $link = '<div class="padded"><img src="/uses/man.png"></div>' ;
    
    
//    $name .= " $div_type";

    switch( $div_type )
    {
    
    case 'div_type_task' :
    $str = "
    <tr data-row-type='$div_type' class='".$row_class."' name='".$in_name."' id='".$id."' style='".$style."'>
    <td class='AR'>".$zak_link.$link."</td>
    <td name='$id' class='AL'><div class='add_img_div' id='$zadan_id' name='order'></div><div style='padding-left:".$spacing."px'>".$name."</div></td>
    <td class='AC'>$date_of_beg_plan</td>      
    <td class='AC'>$date_of_perf_plan</td>              
    <td class='AC'>$date_of_perf_fact</td>     
    <td class='AC'>".$creator."</td>
    <td class='AC'>".$executor."</td>
    <td class='AC'>".$checker."</td>
    <td class='AC ".$state_class."'>".$state."</td>        
    </tr>"; break ;
    
    case 'div_type_order' :
    $str = "
    <tr data-row-type='$div_type' class='".$row_class."' name='".$in_name."' id='".$id."' style='".$style."'>
    <td class='AR'>".$zak_link.$link."</td>
    <td colspan='2' name='$id' class='AL'><div class='add_img_div' id='$zak_id' name='order_group'></div><div style='padding-left:".$spacing."px'>".$name."</div></td>
    <td colspan='6' class='AL'>".$progress_bar."</td>        
    </tr>"; break ;

    default :
    $str = "
    <tr data-row-type='$div_type' class='".$row_class."' name='".$in_name."' id='".$id."' style='".$style."'>
    <td class='AR'>".$zak_link.$link."</td>
    <td colspan='2' name='$id' class='AL' style='padding-left:".$spacing."px'>".$name."</td>
    <td colspan='6' class='AL'>".$progress_bar."</td>        
    </tr>"; break ;
    
    
    }   
          
    // ���� ���� ��������� ������, �� ����������� ����� ��� ������ ����� ������.
    if( $chield_count )
        foreach( $childs AS $key => $ichild )
            $str .= CreateChildTree( $ichild , 0 , $id , $spacing + $child_space , $color );
   
    return $str ;
}
//              <table width='1200px' class='rdtbl tbl'>
function CreateStateTree( $arr )
{

        $str = "<H2>������� �� ��������������</H2>
                <table width='1200px' class='rdtbl tbl'>
                <thead>
                <tr class='first'>
                <td width='2%'></td>
                <td class='after_show_hide' width='15%'>������������ �������</td>
                <td width='3%'>����<br>������<br>����</td>                
                <td width='3%'>����<br>����������<br>����</td>
                <td width='3%'>����<br>����������<br>����</td>
                <td width='5%'>�����</td>
                <td width='5%'>�����������</td>
                <td width='5%'>���������</td>
                <td width='4%'>������</td>
                </tr></thead>";

foreach( $arr AS $key => $item )
    $str .= CreateChildTree( $item );

return $str .= "</table>";

}

function GetTasks( &$arr )
{
    $global_index = 0 ;
    
    foreach( $arr AS $key => $value )
    {
       foreach( $arr[$key]['childs'] AS $skey => $svalue )
        {
// ������� �� �������
         if( $svalue['DivType'] == 'div_type_person' )
          {
            $res_id = $svalue['DivHeadResID'];


// ���������� ������ ���������� ������� '�� �������'            
            $query ="SELECT COUNT(ID) cnt FROM okb_db_itrzadan 
                     WHERE ID_zak<>0 AND ID_users2=".$res_id ; 
            $result = dbquery( $query );
            $row = mysql_fetch_assoc( $result );
            $task_count = $row['cnt'];
            $arr[$key]['childs'][$skey]['DivTaskCount'] = $task_count ;

// �������� �������� '�� �������' c ����������� ������
            
            $query ="SELECT * FROM okb_db_itrzadan 
                     WHERE 
                     ID_users2=".$res_id." 
                     AND 
                     ID_zak<>0 
                     AND
                     STATUS<>'���������' 
                     AND
                     STATUS<>'������������'                      
                     GROUP BY ID_zak"; 
           
            $result = dbquery( $query );

            while( $row = mysql_fetch_assoc( $result ))
                {
                    $div_type_index = 0 ;
                    $zak_id = $row['ID_zak'] ;
                    $zak_item = GetZakName( $zak_id );
                    $zak_name = $zak_item['zak_prefix'].'-'.$zak_item['zak_name'].' '.$zak_item['zak_dse_name'];
                    $arr[$key]['childs'][$skey]['childs'][0]['childs'][$zak_id]['childs'] = array();
                    $arr[$key]['childs'][$skey]['childs'][0]['childs'][$zak_id]['DivType'] = 'div_type_order';
                    $arr[$key]['childs'][$skey]['childs'][0]['childs'][$zak_id]['DivName'] = $zak_name ;
                    $arr[$key]['childs'][$skey]['childs'][0]['childs'][$zak_id]['zak_id'] = $zak_id ;
                }

                
            $query ="
              SELECT 
              ID, TXT, ID_users, ID_users2, ID_users3, ID_zak , 
              DATE_FORMAT( DATE_PLAN, '%d.%m.%Y' ) date_of_perf_plan, 
              DATE_FORMAT( STARTDATE, '%d.%m.%Y' ) date_of_beg_plan,               
              STATUS 
              FROM okb_db_itrzadan 
              WHERE ID_users2=".$res_id."  
                     AND
                     STATUS<>'���������' 
                     AND
                     STATUS<>'������������'                      
                     AND 
                     ID_zak<>0 ORDER BY STARTDATE"; 
           
            $result = dbquery( $query );

            while( $row = mysql_fetch_assoc( $result ))
              {
                  $zak_id = $row['ID_zak'];                
                  $arr[$key]['childs'][$skey]['childs'][0]['childs'][$zak_id]['childs'][] = 
                          array( 
                                'DivName'                   => $row['TXT'], 
                                'DivType'                   => 'div_type_task',
                                'zadan_id'                  => $row['ID'],              
                                'zadan_creator'             => $row['ID_users'],
                                'zadan_executor'            => $row['ID_users2'],
                                'zadan_checker'             => $row['ID_users3'],
                                'zadan_date_of_beg_plan'    => $row['date_of_beg_plan'],
                                'zadan_date_of_perf_plan'   => $row['date_of_perf_plan'],
                                'zadan_date_of_perf_fact'   => GetDateByStatus( $row['ID'], $row['STATUS'] ),
                                'zadan_status'      => $row['STATUS']
                                );
              }
         }

// ������� ��� �������
         if( $svalue['DivType'] == 'div_type_person' ){
            $res_id = $svalue['DivHeadResID'];
            
            $query ="SELECT COUNT(ID) cnt FROM okb_db_itrzadan 
                     WHERE ID_zak=0 AND ID_users2=".$res_id ; 
            $result = dbquery( $query );
            $row = mysql_fetch_assoc( $result );
            $task_count = $row['cnt'];
            $arr[$key]['childs'][$skey]['DivTaskCount'] = $task_count ;

 /*            
            $query ="SELECT * FROM okb_db_itrzadan 
                     WHERE 
                     ID_users2=".$res_id." 
                     AND
                     STATUS<>'���������' 
                     AND
                     STATUS<>'������������'                      
                     AND 
                     ID_zak=0 
                     GROUP BY ID_zak"; 
           
            $result = dbquery( $query );

            while( $row = mysql_fetch_assoc( $result ))
                {
                    //[1] - DivType == div_type_out_of_order �.�. ������ ��� ��������
                    $div_type_index = 1 ;
                    $arr[$key]['childs'][$skey]['childs'][1]['childs'][$zak_id]['childs'] = array();
                    $arr[$key]['childs'][$skey]['childs'][1]['childs'][$zak_id]['DivType'] = 'div_type_out_of_order';
                }
*/
                
            $query ="
              SELECT 
              ID, TXT, ID_users, ID_users2, ID_users3, ID_zak , 
              DATE_FORMAT( DATE_PLAN, '%d.%m.%Y' ) date_of_perf_plan, 
              DATE_FORMAT( STARTDATE, '%d.%m.%Y' ) date_of_beg_plan,               
              STATUS 
              FROM okb_db_itrzadan 
              WHERE 
              ID_users2=".$res_id." 
              AND
              STATUS<>'���������' 
              AND
              STATUS<>'������������'                      
              AND 
              ID_zak=0 
              ORDER BY STARTDATE"; 
           
            $result = dbquery( $query );

            while( $row = mysql_fetch_assoc( $result ))
              {
                  $arr[$key]['childs'][$skey]['childs'][1]['childs'][] = 
                          array( 
                                'DivName'                   => $row['TXT'], 
                                'DivType'                   => 'div_type_task',
                                'zadan_id'                  => $row['ID'],              
                                'zadan_creator'             => $row['ID_users'],
                                'zadan_executor'            => $row['ID_users2'],
                                'zadan_checker'             => $row['ID_users3'],
                                'zadan_date_of_beg_plan'    => $row['date_of_beg_plan'],
                                'zadan_date_of_perf_plan'   => $row['date_of_perf_plan'],
                                'zadan_date_of_perf_fact'   => GetDateByStatus( $row['ID'], $row['STATUS'] ),
                                'zadan_status'      => $row['STATUS']
                                );
              }
         }
        }
       
        if( count ( $arr[$key]['childs']))
            GetTasks( $arr[$key]['childs']);
    }
}


function CalcDivTasksCount( &$arr )
{
  $counter = array( 'TotalTaskCount' => 0 , 'CompletedTaskCount' => 0 );
 
  if( count( $arr['childs'] ))
    foreach( $arr['childs'] AS $key => $value )
  {
      $tmp_counter = CalcDivTasksCount( $arr['childs'][$key] );
      $counter['TotalTaskCount'] += $tmp_counter['TotalTaskCount'] ;
      $counter['CompletedTaskCount'] += $tmp_counter['CompletedTaskCount'] ;      
  }
  else
  {
      if( $arr['DivType'] == 'div_type_task' ) // ���� ������� - �������
      {
            $counter['TotalTaskCount'] ++ ;
            if( $arr['zadan_status'] == '���������' )
                $counter['CompletedTaskCount'] ++ ;
      }
  }
      
   $arr['DivTaskCount'] = $counter['TotalTaskCount'] ;
   $arr['DivCompletedTaskCount'] = $counter['CompletedTaskCount'] ;   
   return $counter ;
}

function CalcTaskCount( &$arr )
{
 $counter = array( 'TotalTaskCount' => 0 , 'CompletedTaskCount' => 0 );
    
foreach( $arr AS $key => $value )
    {
    
        foreach( $arr[$key]['childs'] AS $ekey => $evalue )
        {
            $cnt = CalcDivTasksCount( $arr[$key]['childs'][$ekey] );
            $counter['TotalTaskCount'] += $cnt['TotalTaskCount'] ;
            $counter['CompletedTaskCount'] += $cnt['CompletedTaskCount'] ;            
        }
        
        $arr[$key]['DivTaskCount'] = $counter['TotalTaskCount'] ;
        $arr[$key]['DivCompletedTaskCount'] = $counter['CompletedTaskCount'] ;           
    }
}

?> 

