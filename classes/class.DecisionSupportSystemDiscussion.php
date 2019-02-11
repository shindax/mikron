<?php
$columns = 8;

    // function debug( $arr , $conv = 0 )
    // {
    //     $str = print_r($arr, true);
    //     if( $conv )
    //         $str = conv( $str );
    //     echo '<pre>'.$str.'</pre>';
    // }

class DecisionSupportSystemDiscussion
{
	protected $pdo ;
    protected $user_id ;
    protected $res_id ;
    protected $res_name ;
    protected $res_gender ;
    
    protected $project_id ;
    protected $project_name ;
    protected $project_team ;    

    protected $base_discussion_id;
	protected $discussion_id;
	protected $data = [];
    protected $solved = [];
    protected $has_new_messages;
    protected $ids = [];

    public function GetIDs()
    {
        return $this -> ids;
    }

    public function GetProjectTeam()
    {
        return $this -> project_team;
    }


    public function __construct( $pdo, $res_id, $discussion_id )
    {
        $this -> pdo = $pdo ;
        $this -> discussion_id = $discussion_id ;     
        $this -> res_id = $res_id;
        $this -> has_new_messages = 0;
        $this -> base_discussion_id = 0;
        $this -> CollectData();   
    }

    public function GetData()
    {
    	return $this -> data ;
    }

    private function CollectData()
    {
        $data = [];

// Get user name and gender
            try
            {
                $query ="
                            SELECT NAME, GENDER, ID_users
                            FROM `okb_db_resurs`
                            WHERE ID = ". $this -> res_id;
                $stmt = $this -> pdo->prepare( $query );
                $stmt->execute();

            }
            catch (PDOException $e)
            {
                  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query is : $query");
            }

            if( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
            {
                $this -> res_name = conv( $row -> NAME );
                $this -> res_gender = $row -> GENDER ;
                $this -> user_id = $row -> ID_users ;
            }

// Get project details
            try
            {
                $query ="
                            SELECT 
                            dss_discussions.base_id AS base_id,
                            dss_projects.id AS project_id, 
                            dss_projects.team AS project_team, 
                            dss_projects.name AS project_name
                            FROM `dss_discussions` 
                            LEFT JOIN dss_projects  ON dss_projects.id = dss_discussions.project_id
                            WHERE dss_discussions.id = ". $this -> discussion_id ;

                $stmt = $this -> pdo->prepare( $query );
                $stmt->execute();
            }
            catch (PDOException $e)
            {
                  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query is : $query");
            }

            if( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
            {
              $this -> base_discussion_id = $row -> base_id ;
              $this -> project_id = $row -> project_id ;
              $this -> project_name = conv( $row -> project_name );
              $this -> project_team = json_decode( $row -> project_team );
            }

            try
            {
                $query ="
                            SELECT solved
                            FROM `dss_discussions`
                            WHERE base_id = ". $this -> discussion_id;
                $stmt = $this -> pdo->prepare( $query );
                $stmt->execute();
            }
            catch (PDOException $e)
            {
                  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query is : $query");
            }

            if( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
                $this -> solved = conv( $row -> solved );

            try
            {
                $query ="
                            SELECT disc.*, res.NAME name
                            FROM `dss_discussions` disc
                            LEFT JOIN okb_db_resurs res ON res.ID = disc.res_id
                            WHERE base_id = ". $this -> discussion_id." ORDER BY disc.id";
                $stmt = $this -> pdo->prepare( $query );
                $stmt->execute();
            }
            catch (PDOException $e)
            {
                  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query is : $query");
            }

       while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
       {
            $data[ $row -> id ] = [
                                            'id' => $row -> id,
                                            'parent_id' => $row -> parent_id,
                                            'res_id' => $row -> res_id,
                                            'res_name' => conv( $row -> name ),
                                            'text' => conv( $row -> text ),
                                            'timestamp' => $this -> MakeDateTime( $row -> timestamp ),
                                            'childs' => []
                                           ];
       }

        $this -> data = $this -> MakeTree( $data ) ;
        $this -> CollectIDs();
    }

    private function MakeDateTime( $str ) 
    {
        $arr = explode(" ", $str );
        $date = explode("-", $arr[0] );
        $time = explode(":", $arr[1] );
        foreach( ['янв.','фев.','мар.','апр.','май.','июнь.','июль.','авг.','сен.','окт.','ноя.','дек.'] AS $key => $val )
        $month[ $key ] = conv( $val );

        $res = [ "date" => $date[2]." ".$month[ $date[1] - 1 ]." ".$date[0], "time" => $time[0].":".$time[1] ];
        return $res;
    }


    private function MakeTree( $dataset ) 
    {
        $tree = [];

        foreach ( $dataset as $id=>&$node) 
            if (!$node['parent_id'])
                $tree[$id] = &$node;
                else
                    $dataset[$node['parent_id']]['childs'][$id] = &$node;

        return $tree;
    }

    public function GetHtml()
    {
        $dataset = $this -> data ;
        $dataset = $dataset[ $this -> discussion_id ];
        $solved = strlen( $this -> solved ) ? 1 : 0 ;
        $str = '';
        $level = 0 ;
        $this -> CyclicBypass( $dataset['childs'], $str, $level ) ;
        $new_messages = $this -> HasNewMessages();
        $id_list = join( ",", $this -> GetIDs() );        

        if( $solved )
            $str .= "<div class='solved_theme_div'><span class='solved_theme_span'>".conv("Принято решение : ").( $this -> solved )."</span></div>";

        $new_messages --;
        $str .= "<span class='serv_span hidden'>$new_messages</span>";
     
        return $str ;
    }

    private function CyclicBypass( $dataset, &$str, $level )
    {
        $solved = strlen( $this -> solved ) ? 1 : 0 ;

        $level += 10 ;
        foreach( $dataset AS $key => $value )
        {
            $str .= "<div class='dlevel_$level' data-id='".$value['id']."' data-parent_id='".$value['parent_id']."'>
                        <span class='auth_span'>".$value['res_name']." : </span>
                        <span class='date_span'>".$value['timestamp']["date"]." </span>
                        <span class='time_span'>".$value['timestamp']["time"]." : </span>
                        <span class='body_span'>".$value['text']."</span>";
            
            // if( !$solved )
        if( in_array( $this -> res_id, $this -> project_team ) )
                $str .= "<span class='resp_span'>".conv("Ответить")."</span>";

            $str .= "</div>";

            if( count( $value['childs']) )
                    $this -> CyclicBypass( $value['childs'], $str, $level );
        }
    }

    private function cyclic_pass( $dataset, &$to_pass )
    {
        foreach( $dataset AS $key => $value )
        {
            $to_pass[] = $value['id'];
            if( isset( $value['childs']) )
                    $this -> cyclic_pass( $value['childs'], $to_pass );
        }
    }

    public function CollectIDs()
    {
        $key = key( $this -> data );
        $dataset = isset( $this -> data[ $key ] ) ? $this -> data[ $key ] : 0 ;
        $arr = [];
        $arr[] = $dataset['id'];

        if( isset( $dataset['childs']) )
              $this -> cyclic_pass( $dataset['childs'], $arr ) ;

        $this -> ids = $arr ;
    }

    public function HasNewMessages()
    {
        if( count( $this -> ids ) )
        {
            try
            {
                $query ="
                            SELECT seen_by, team
                            FROM `dss_discussions`
                            LEFT JOIN `dss_projects` ON dss_projects.id = dss_discussions.project_id
                            WHERE dss_discussions.id IN (".join( ",", $this -> ids ).")";
                $stmt = $this -> pdo->prepare( $query );
                $stmt->execute();
            }
            catch (PDOException $e)
            {
                  die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query is : $query");
            }

            while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
            {
                $arr = json_decode( $row -> seen_by );
                if( empty($arr) )
                    $arr = [];

                $team_arr = json_decode( $row -> team );
                if( empty($team_arr) )
                    $team_arr = [];


                if( !in_array( $this -> res_id, $arr ) && in_array( $this -> res_id, $team_arr ) )
                    $this -> has_new_messages ++;
            }
        }
        return $this -> has_new_messages;
    } // public function HasNewMessages()


    public function MakeNotification( $why, $male_msg, $female_msg )
    {
        $receivers = [] ;
        $description = $this -> res_name." ";

        if( $this -> res_gender == 1 )
            $description .= $male_msg ;
                else
                    $description .= $female_msg ;

        $description .= conv(" в системе принятия решений. Проект : ").$this -> project_name;

// Get user_id's from team's id
        try
        {
            $query ="
                        SELECT ID_users
                        FROM `okb_db_resurs`
                        WHERE ID IN (". join(',', $this -> project_team ) .")";
            $stmt = $this -> pdo->prepare( $query );
            $stmt->execute();

        }
        catch (PDOException $e)
        {
              die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query is : $query");
        }
        while( $row = $stmt->fetch( PDO::FETCH_OBJ ) )
            if( $row -> ID_users != $this -> user_id )
                $receivers[] = $row -> ID_users;

         foreach( $receivers AS $user_id )
            {

                    try
                    {
                        $query = "
                                          INSERT
                                          INTO `okb_db_plan_fact_notification` (`id`, `why`,`to_user`, `zak_id`, `field`, `stage`, `description`,`ack`,`timestamp`)
                                          VALUES (NULL, $why, '$user_id', ".( $this -> project_id ).", '', ".( $this -> base_discussion_id ).", '$description', '0', NOW() );
                                          " ;

                                         $stmt =  $this -> pdo->prepare( $query );
                                         $stmt->execute();

                    }
                    catch (PDOException $e)
                    {
                       die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage().". Query : $query");
                    }
               }

        // return $this -> user_id ." : ". $this -> res_id ." : ".  $this -> res_name ." : ".  $this -> res_gender ." : ". $this -> project_id ." : ". $this -> project_name ;    

    } // public function makeNotification( $why, $msg )
}