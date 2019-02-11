<?php
$columns = 8;

class DecisionSupportSystemItem
{
    const LEVEL_SHIFT = 20;
    const EXPAND = "/uses/svg/arrow-right-down.svg";
    const COLLAPSE = '/uses/svg/arrow-left-up.svg';

	protected $pdo ;
    protected $res_id ;    
    protected $level ;
	protected $dss_item_id;
	protected $data = [];

    public function __construct( $pdo, $res_id, $dss_item_id, $level = 0 )
    {
        $this -> pdo = $pdo ;
        $this -> dss_item_id = $dss_item_id ;     
        $this -> level = $level ;
        $this -> res_id = $res_id;
        $this -> CollectData();   
    }

    public function GetData()
    {
    	return $this -> data ;
    }

    public function GetChildsCount()
    {
            try
            {
                $query ="
                            SELECT COUNT( * ) count
                            FROM `dss_projects`
                            WHERE parent_id = ". $this -> dss_item_id;
                $stmt = $this -> pdo->prepare( $query );
                $stmt->execute();
            }
            catch (PDOException $e)
            {
                  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query is : $query");
            }

       $row = $stmt->fetch( PDO::FETCH_OBJ );
       return $row -> count ;
    }

    public function GetDiscussionsStatistics()
    {
        $solved = 0 ;
        $total = 0 ;
        $new = 0 ;

            try
            {
                $query ="
                            SELECT dss_discussions.solved, dss_discussions.seen_by, dss_discussions.parent_id, dss_projects.team
                            FROM `dss_discussions`
                            LEFT JOIN `dss_projects` ON dss_projects.id = dss_discussions.project_id
                            WHERE 
                            project_id = ". $this -> dss_item_id;
                $stmt = $this -> pdo->prepare( $query );
                $stmt->execute();
            }
            catch (PDOException $e)
            {
                  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query is : $query");
            }

        while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
        {
            if( ! $row -> parent_id )
            {
                $total ++ ;
                continue ;
            }

            if( strlen( $row -> solved ) )
                $solved ++ ;
            
            $arr = json_decode( $row -> seen_by );
            
            if( empty($arr) )
                $arr = [];


            $team_arr = json_decode( $row -> team );
            
            if( empty($team_arr) )
                $team_arr = [];

            if( !in_array( $this -> res_id, $arr ) && in_array( $this -> res_id, $team_arr ) )
                $new ++;
        }

        return ['total' => $total, 'solved' => $solved ,'new' => $new ];
    }

        // $result = "" ;
        
        // switch( $total % 10)
        // {
        //     case 5 :
        //     case 6 :
        //     case 7 :
        //     case 8 :
        //     case 9 :
        //     case 0 : $result = conv(" обсуждений"); break ;

        //     case 1 : $result = conv(" обсуждение"); break ;

        //     case 2 : 
        //     case 3 : 
        //     case 4 : $result = conv(" обсуждения"); break ;
        // }

	public function GetTableRow( $tr_class, $td_class, $expanded = 0 )
    {
    	global $columns ;

    	$data = $this -> data;
    	$member_list = '';
        $member_arr = [];

    	$team = $data['team'];
    	$team_count = count( $team );

    	if( $team_count )
    	{
    		$members = $this -> GetTeamMemebers();
    		foreach ( $members as $key => $value ) 
            {
				$member_list .= $value."\n";
                $member_arr[] = $key;
            }
    	}
    	
    	$discussions = $this -> GetDiscussionsStatistics();
        $discussions_total = "<span class='disc_total'>{$discussions['total']}</span>";
        $discussions_solved = "<span class='disc_solved'>{$discussions['solved']}</span>";
        
        $discussions_new = "<span class='disc_new'>{$discussions['new']}</span>";

        if( $discussions['new'] )
            $class = 'new_mess';
            else
                $class = '';

        $discussions_count = "<div class='$class'>$discussions_total/$discussions_solved/$discussions_new</div>";

        $childs_count = $this -> GetChildsCount();

    	$pictures = ( array ) $data['pictures'];
    	$pictures_count = count( $pictures );

        $parent_id = $data['parent_id'];
        $base_id = $data['base_id'];
        $ord = $data['ord'];

        $level = $this -> level;
        $data_id = $this -> dss_item_id;

    	$str = '';
    	$str .= "<tr id= '$data_id' class='$tr_class level_$level' data-id='$data_id' data-level='$level' data-changed='$expanded' data-state='$expanded' data-base-id='$base_id' data-parent-id='$parent_id' data-ord='$ord' data-creator-id='".$data['creator_id']."'>";

        if( $this -> res_id == $data['creator_id'] )
                {
                    $name = "<span class='ord'>$ord</span>. <span class='dse_name' data-role='name'>".$data['name']."</span><input class='input hidden' data-field='name' value='".$data['name']."'></input>";
                    $description = "&nbsp;<span class='dse_description' data-role='description'>".$data['description']."</span><input class='input hidden' data-field='description' value='".$data['description']."'></input>";
                    $div_class='edited';
                }
                else
                {
                    $name = "<span class='ord'>$ord</span>. <span class='dse_name'>".$data['name']."</span>";
                    $description = "<span class='dse_description'>".$data['description'];
                    
                    // "<!--span class='deb_id'>ID : $data_id</span> : 
                    // <span class='deb_par_id'>PAR_ID : $parent_id</span> : 
                    // <span class='deb_base_id'>BASE_ID : $base_id</span> :                     
                    // <span class='deb_cause'></span></span-->
                    // ";

                    $div_class='';
                }
        
        $icon = $expanded ? ( self :: COLLAPSE ) : ( self :: EXPAND ) ;
        $str .= "<td class='AC $td_class'></td>";
        $str .= "<td class='$td_class'><div class='head_wrap'>"
        .( !$data['parent_id'] ? "<img class='expand_all' src='/uses/svg/expand_sharp.svg' data-src='/uses/svg/arrow-down.svg' data-state='0' data-role='project_exp_coll'/>" : "" )
        .( $childs_count ? "<img class='icon expand' src='$icon' data-src='/uses/svg/arrow-down.svg' data-role='project_exp_coll'/>" : "<img class='icon' src='/uses/svg/spinner-2.svg' />")."<div class='head $div_class'>$name</div></div></td>";

		$str .= "<td class='$td_class'><div class='head $div_class'> $description</div></td>";

		$str .= "<td class='AC $td_class'>".$data['creator_name']."</td>";
		$str .= "<td class='AC $td_class'>".$data['creation_date']."</td>";

		$str .= "<td class='AC $td_class'>
                    <div class='ref_div' title='Добавить вложенную ДСЕ' data-role='dse_job'>
                        <img class='icon small' src='/uses/svg/add.svg' /> <img class='icon' src='/uses/svg/settings-3.svg' title='$childs_count вложенных ДСЕ'/>$childs_count</div></td>";
		
		$str .= "<td class='AC $td_class'>
                    <div class='ref_div' data-role='disc_job'>
                        <img class='icon' src='/uses/svg/speech-bubble-right-4.svg' data-src='/uses/svg/user-23.svg' />$discussions_count</div></td>";

		$str .= "<td class='AC $td_class'>
                    <div class='ref_div member_div' title='$member_list' data-role='users_job' data-member-list='".join(",", $member_arr)."'>
                        <img class='icon' src='/uses/svg/users.svg' data-src='/uses/svg/user-12.svg' /><span class='member_count'>$team_count</span></div></td>";
		
        $str .= "<td class='AC $td_class'>
                    <div class='ref_div' title='$pictures_count документов' data-role='pict_job'>
                        <img class='icon' src='/uses/svg/camera.svg' /><span class='pictures_count'>$pictures_count</span></div></td>";

		$str .= "<td class='AC $td_class'><div class='head_wrap'>".
			( $childs_count ? "" : 
				( 
					 $this -> res_id == $data['creator_id'] ?
					"<img class='icon del_row' src='/uses/del.png' data-role='del_row'/>":
					"<img class='icon' src='/uses/del_dis.png'/>"
				)
			).
			"</td>";

    	$str .= "</tr>";

    	return $str ;
    }

    private function CollectData()
    {
	    try
        {
            $query ="
                        SELECT prj.*, res.NAME creator_name, DATE_FORMAT( create_date, '%d.%m.%Y') fulldate
                        FROM `dss_projects` prj
                        LEFT JOIN okb_db_resurs res ON res.ID = prj.creator_id
                        WHERE prj.id = ". $this -> dss_item_id ."
						ORDER BY ord DESC
                        ";
            $stmt = $this -> pdo->prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query is : $query");
        }

        if ( $row = $stmt->fetch( PDO::FETCH_OBJ ))
            {
            	$this -> data = [
            				'parent_id' => $row -> parent_id,
                            'base_id' => $row -> base_id,
                            'ord' => $row -> ord,
            				'name' => $row -> name,
            				'description' => $row -> description,
            				'creator_id' => $row -> creator_id,
            				'creator_name' => $row -> creator_name,
            				'creation_date' => $row -> fulldate,
            				'team' => json_decode( $row -> team ),
            				'pictures' => json_decode( $row -> pictures ),
            			];
            }

    }

    public function GetTeamMemebers()
    {
     	$data = $this -> data;
    	$team = $data['team'];
    	$member_list = join(",", $team);
    	$team_members = [];

	    try
        {
            $query ="
                        SELECT ID, NAME 
                        FROM `okb_db_resurs` 
                        WHERE id in ( $member_list ) 
                        ORDER BY NAME
                     ";
            $stmt = $this -> pdo->prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query is : $query");
        }

        while ( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
        	$team_members[ $row -> ID ] = $row -> NAME;

        return $team_members;
    }

    // private function MakeTree( $dataset ) 
    // {
    //     $tree = [];

    //     foreach ( $dataset as $id=>&$node) 
    //         if (!$node['parent_id'])
    //             $tree[$id] = &$node;
    //             else
    //                 $dataset[$node['parent_id']]['childs'][$id] = &$node;

    //     return $tree;
    // }

    public function GetUserListOption()
    {
        $list = "";

             try
            {
                $query ="
                            SELECT ID_resurs, NAME 
                            FROM `okb_db_shtat` 
                            WHERE presense_in_shift_orders=0
                            ORDER BY NAME
                            ";
                $stmt = $this -> pdo -> prepare( $query );
                $stmt -> execute();
            }
            catch (PDOException $e)
            {
                  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query is : $query");
            }

       while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
            {
                $name = $row -> NAME ;
                if( strlen( $name ) && $name != "Вакансия ..")
                    $list .= "<option value='". ( $row -> ID_resurs )."'>".conv( $name )."</option>";
            }

        return $list ;
    }

    public function GetProjectsBatch()
    {
        $arr_cat = [];

                try
                {
                    $query = "SELECT id, parent_id, name FROM `dss_projects` WHERE 1";
                    $stmt = $this -> pdo->prepare( $query );
                    $stmt->execute();
                }
                catch (PDOException $e)
                {
                  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
                }
                while( $row = $stmt->fetch(PDO::FETCH_ASSOC ) )
                    {
                        $arr_cat[$row['id']] = $row;
                    }

        return $arr_cat;
    }

    public function GetChain()
    {
        $id = $this -> dss_item_id;
        $arr = $this -> GetProjectsBatch();
        $parent_id = $arr[ $id ]['parent_id'];
        $chain = [ $id, $parent_id ];

        do
        {
            $id = $parent_id;
            $parent_id = $arr[ $id ]['parent_id'];
            if( $parent_id  )
                $chain[] = $parent_id ;
        }
        while( $parent_id  );

        return array_reverse( $chain );
    }
}