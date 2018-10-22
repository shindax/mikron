<?php
$columns = 8;

class DecisionSupportSystemDiscussion
{
	protected $pdo ;
    protected $user_id ;
    protected $res_id ;    
	protected $discussion_id;
	protected $data = [];
    protected $solved = [];

    public function __construct( $pdo, $user_id, $discussion_id )
    {
        $this -> pdo = $pdo ;
        $this -> user_id = $user_id ;        
        $this -> discussion_id = $discussion_id ;     
        $this -> res_id = self :: GetResInfo( $pdo, $user_id );
        $this -> CollectData();   
    }

    public function GetData()
    {
    	return $this -> data ;
    }

    private function CollectData()
    {
        $data = [];

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
                            SELECT disc.*, users.FIO name
                            FROM `dss_discussions` disc
                            LEFT JOIN okb_db_resurs res ON res.ID = disc.res_id
                            LEFT JOIN okb_users users ON users.ID = res.ID_users
                            WHERE base_id = ". $this -> discussion_id;
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
                                            'timestamp' => $row -> timestamp,
                                            'childs' => []
                                           ];
       }

       $this -> data = $this -> MakeTree( $data ) ;

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

    public static function GetResInfo( $pdo, $user_id )
    {
        try
        {
            $query ="SELECT ID, NAME FROM `okb_db_resurs` WHERE ID_users = $user_id";
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

        return $res_id ;
     }

    public function GetHtml()
    {
        $dataset = $this -> data ;
        $dataset = $dataset[ $this -> discussion_id ];
        $solved = strlen( $this -> solved ) ? 1 : 0 ;
        $str = '';
        $level = 0 ;
        $this -> CyclicBypass( $dataset['childs'], $str, $level ) ;

        if( $solved )
            $str .= "<div class='solved_theme_div'><span class='solved_theme_span'>".conv("Принято решение : ").( $this -> solved )."</span></div>";

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
                        <span class='body_span'>".$value['text']."</span>";
            if( !$solved )
                $str .= "<span class='resp_span'>".conv("Ответить")."</span>";

            $str .= "</div>";

            if( count( $value['childs']) )
                    $this -> CyclicBypass( $value['childs'], $str, $level );
        }
    }
}