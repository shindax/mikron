<?php
$columns = 8;

class DecisionSupportSystemItem
{
    const LEVEL_SHIFT = 20;
    const EXPAND = "/uses/svg/arrow-right-down.svg";
    const COLLAPSE = '/uses/svg/arrow-left-up.svg';

	protected $pdo ;
    protected $user_id ;
    protected $res_id ;    
    protected $level ;
	protected $dss_item_id;
	protected $data = [];

    public function __construct( $pdo, $user_id, $dss_item_id, $level = 0 )
    {
        $this -> pdo = $pdo ;
        $this -> user_id = $user_id ;        
        $this -> dss_item_id = $dss_item_id ;     
        $this -> level = $level ;
        $this -> res_id = self :: GetResId( $pdo, $user_id );
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

    public function GetDiscussionsCount()
    {
        $solved = 0 ;
        $total = 0 ;

            try
            {
                $query ="
                            SELECT solved
                            FROM `dss_discussions`
                            WHERE 
                            parent_id = 0 
                            AND
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
            $total ++ ;
            if( strlen( $row -> solved ) )
                $solved ++ ;
        }

        return ['total' => $total, 'solved' => $solved ];
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
    	

    	$discussions = $this -> GetDiscussionsCount();
        $discussions_count = '<span class="disc_total">'.$discussions['total'].'</span>&nbsp/&nbsp<span class="disc_solved">'.$discussions['solved'].'</span>';

        $childs_count = $this -> GetChildsCount();

    	$pictures = ( array ) $data['pictures'];
    	$pictures_count = count( $pictures );

        $parent_id = $data['parent_id'];
        $base_id = $data['base_id'];
        $ord = $data['ord'];

        $level = $this -> level;
        $data_id = $this -> dss_item_id;

    	$str = '';
    	$str .= "<tr class='$tr_class level_$level' data-id='$data_id' data-level='$level' data-changed='$expanded' data-state='$expanded' data-base-id='$base_id' data-parent-id='$parent_id' data-ord='$ord'>";

        if( $this -> res_id == $data['creator_id'] )
                {
                    $name = "<span class='ord'>$ord</span>. <span class='dse_name' data-role='name'>".$data['name']."</span><input class='input hidden' data-field='name' value='".$data['name']."'></input>";
                    $description = "&nbsp;<span class='dse_description' data-role='description'>".$data['description']."</span><input class='input hidden' data-field='description' value='".$data['description']."'></input>";
                    $div_class='edited';
                }
                else
                {
                    $name = "<span class='ord'>$ord</span>. <span class='dse_name'>".$data['name']."</span>";
                    $description = "<span class='dse_description'>".$data['description']."</span>";
                    $div_class='';
                }
        
        $icon = $expanded ? ( self :: COLLAPSE ) : ( self :: EXPAND ) ;

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

    public static function GetResId( $pdo, $user_id )
    {
        try
        {
            $query ="SELECT ID FROM `okb_db_resurs` WHERE ID_users = $user_id";
           $stmt = $pdo -> prepare( $query );
           $stmt->execute();
        }

        catch (PDOException $e)
        {
            die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
        }

        $res_id = 0 ;
        
        if( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
          $res_id = $row -> ID;

        return $res_id;
     }
}