<?php
error_reporting( E_ALL );
error_reporting( 0 );

switch( gethostname() )
{  
case 'Iktorn' : // Мой сервер
               $EVENT_REGISTER_PAGE_ID  = 203;
               $EVENT_NAME_EDIT_PAGE_ID = 204;
               $CAGENT_EDIT_PAGE_ID     = 205;               
                break ;
  
case 'Programm-001' : 
               $EVENT_REGISTER_PAGE_ID  = 7;
               $EVENT_NAME_EDIT_PAGE_ID = 8;
               $CAGENT_EDIT_PAGE_ID     = 9;               
                break ;
}

?>