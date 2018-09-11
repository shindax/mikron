<?php
require_once($_SERVER['DOCUMENT_ROOT']."/db_config.php");

function scandirs($start)
{
    $files = array();
    $handle = opendir($start);
    while (false !== ($file = readdir($handle)))
    {
        if ($file != '.' && $file != '..')
        {
            if (is_dir($start.'/'.$file))
            {
                $dir = scandirs($start.'/'.$file);
                $files[$file] = $dir;
            }
// Здесь сканируем только директории             
//            else 
//            {
//                array_push($files, $file);
//            }
        }
    }
    closedir($handle);
    return $files; 
}

function process_childs( $arr , $proj_id, $par_id = 0 )
{
    global $mysqli;
    
        foreach( $arr AS $name => $project_items )
        {
        $id = 0 ;
        if( $par_id == 0 )
            $tip_fail = 9 ;
            else
              $tip_fail = 5 ;

        $query ="INSERT INTO okb_db_itrzadan (`TXT`,`ID_proj`,`TIP_FAIL`,`ID_edo`) VALUES('".$name."','".$proj_id."','".$tip_fail."','".$par_id."')"; 
  
        if( ! $mysqli->query( $query ) ) 
             exit("Ошибка обращения к БД ".$mysqli->error); 
                else 
                    $id = $mysqli->insert_id; 
        
        if( count( $project_items ))
             process_childs( $project_items , $proj_id, $id );
        }
}        

function ImportProjects()
{
    global $mysqli;
    
    $arr = scandirs("project/MyJobs/PROJECTS");
     
    foreach( $arr AS $name => $project_items )
    {
        $full_name = explode( '-', $name);
        $prefix = $full_name[0] ;
        $proj_name = $full_name[1] ;

        $query ="INSERT INTO okb_db_projects (`prefix`,`name`) VALUES('{$prefix}','{$proj_name}')"; 
  
        if( ! $mysqli->query( $query ) ) 
              exit("Ошибка обращения к БД".$mysqli->error); 
                else 
                    $id = $mysqli->insert_id; 
        
        if( count( $project_items ))
             process_childs( $project_items , $id );
    }
}

?>