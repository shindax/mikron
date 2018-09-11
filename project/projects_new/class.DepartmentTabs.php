<?php

class DepartmentTabs
{
  public function __toString()
  {
    $str = "<ul class='nav nav-tabs' role='tablist'>";

//  common tab
    $str .= "<li class='active'><a href='#home' role='tab' data-toggle='tab' data-id='0'>".conv("Все проекты")."</a></li>";

//  department tab
    $str .= "<li><a role='tab' data-toggle='tab' data-id='1'>".conv("Отдел 1")."</a></li>";

//  department tab
    $str .= "<li><a role='tab' data-toggle='tab' data-id='2'>".conv("Отдел 2")."</a></li>";

//  department tab
    $str .= "<li><a role='tab' data-toggle='tab' data-id='3'>".conv("Отдел 3")."</a></li>";

//  department tab
    $str .= "<li><a role='tab' data-toggle='tab' data-id='4'>".conv("Отдел 4")."</a></li>";


    $str .= "</ul>";

    return $str ;
  }
}