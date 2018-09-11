<?php
$columns = 8;

class DesigionSupportSystemItem
{
	protected $pdo ;
	protected $dss_item_id;
	protected $data = [];

    public function __construct( $pdo, $dss_item_id )
    {
        $this -> pdo = $pdo ;
        $this -> dss_item_id = $dss_item_id ;     
        $this -> CollectData();   
    }

    public function GetData()
    {
    	return $this -> data ;
    }

	public function GetTableRow( $tr_class='', $td_class='' )
    {
    	global $columns ;

    	$data = $this -> data;
    	$member_list = '';

    	$team = $data['team'];
    	$team_count = count( $team );

    	if( $team_count )
    	{
    		$members = $this -> GetTeamMemebers();
    		foreach ( $members as $key => $value) 
				$member_list .= $value."\n";
    	}
    	
    	$discussions = $data['discussions'];
    	$discussions_count = count( $discussions );

    	$pictures = $data['pictures'];
    	$pictures_count = count( $pictures );

    	$dse = $data['dse'];
    	$dse_count = count( $dse );

    	$str = '';
    	$str .= "<tr class='$tr_class' data-id='{$this -> dss_item_id}'>";
    	$str .= "<td class='$td_class'><img class='icon expand' data-state='0' src='/uses/svg/arrow-down.svg' data-role='project_exp_coll'/>".$data['name']."</td>";
		$str .= "<td class='$td_class'>".$data['description']."</td>";
		$str .= "<td class='AC $td_class'>".$data['creator_name']."</td>";
		$str .= "<td class='AC $td_class'>".$data['creation_date']."</td>";

		$str .= "<td class='AC $td_class'><div title=''><img class='icon' src='/uses/svg/settings-3.svg' />$dse_count</div></td>";
		
		$str .= "<td class='AC $td_class'><div title=''><img class='icon' src='/uses/svg/speech-bubble-right-4.svg' />$discussions_count</div></td>";

		$str .= "<td class='AC $td_class'><div class='ref_div' title='$member_list'  data-role='users_add_rem'><img class='icon' src='/uses/svg/users.svg' />$team_count</div></td>";
		$str .= "<td class='AC $td_class'><div class='ref_div' data-role='pict_add_rem'><img class='icon' src='/uses/svg/camera.svg' />$pictures_count</div></td>";
    	$str .= "</tr>";

    	$str .= "<tr class='discussion_section hidden' data-id='{$this -> dss_item_id}'>";
    	$str .= "<td class='Field' colspan='$columns'><img class='icon' src='/uses/svg/speech-bubble-right-4.svg' /><img class='icon expand' data-role='discussion_exp_coll' src='/uses/svg/arrow-down.svg' /><img class='icon add discussion_add' src='/uses/svg/add.svg' title='Добавить новое обсуждение' /></td>";
    	$str .= "</tr>";

    	$str .= "<tr class='dse_section hidden' data-id='{$this -> dss_item_id}'>";
    	$str .= "<td class='Field' colspan='$columns'><img class='icon' src='/uses/svg/settings-3.svg' /><img class='icon expand' data-role='dse_exp_coll' src='/uses/svg/arrow-down.svg' /><img class='icon add dse_add' src='/uses/svg/add.svg' title='Добавить новую ДСЕ'/></td>";
    	$str .= "</tr>";

    	return $str ;
    }

    private function CollectData()
    {
	    try
        {
            $query ="
                        SELECT prj.*, res.NAME creator_name, DATE_FORMAT( create_date, '%d.%m.%Y') fulldate
                        FROM `DSS_projects` prj
                        LEFT JOIN okb_db_resurs res ON res.ID = prj.creator_id
                        WHERE prj.id = ". $this -> dss_item_id;
            $stmt = $this -> pdo->prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        if ( $row = $stmt->fetch( PDO::FETCH_OBJ ))
            {
            	$this -> data = [
            				'parent_id' => $row -> parent_id,
            				'name' => $row -> name,
            				'description' => $row -> description,
            				'creator_id' => $row -> creator_id,
            				'creator_name' => $row -> creator_name,
            				'creation_date' => $row -> fulldate,
            				'team' => json_decode( $row -> team ),
            				'pictures' => json_decode( $row -> pictures ),
            				'discussions' => [],
            				'dse' => [],
            			];
            }

    }

    private function GetTeamMemebers()
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
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        while ( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
        	$team_members[ $row -> ID ] = $row -> NAME;

        return $team_members;
    }

}