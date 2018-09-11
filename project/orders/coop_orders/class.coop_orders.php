<?php

function GetSplitDate( $date )
{
   if( $date == 0 )
    return '';
    
  $year = substr( $date, 0, 4 );
  $month = substr( $date, 4, 2 );
  $day = substr( $date, 6, 2 );
  return $day.'.'.$month.'.'.$year ;
}

  class CoopOrder
{
    private $ord_number ;
    private $cr_name ;
    private $ord_draw ;
    private $ord_count ;
    private $labor_times_for_item;
    
    private $ord_date ;    
    private $ord_state ;
    
    private $material ;
    
    private $id_zak ;
    private $ordname ;
    private $ordtype ;
    
    private $dse_name ;
    private $customer ;
    private $job_kind ;    
    private $comment ;    
    private $aim;  
    
    private $plan_price;
    private $work_price;
    private $fact_price;
    private $eff;    
    
    public function __construct(
                   $ord_number, 
                   $cr_name, 
                   $ord_draw, 
                   $ord_count,
                   $labor_times_for_item,
                   $ord_date,
                   $exec_date,
                   $ord_state,
                   $id_zak,
                   $ord_name,
                   $ord_type,
                   $dse_name,
                   $customer,
                   $job_kind,
                   $material,
                   $comment,
                   $aim_type,
                   $plan_price,
                   $work_price,
                   $fact_price,
                   $eff
                   )
    {
      $this->ord_number   = $ord_number ;
      $this->cr_name      = $cr_name ;      
      $this->ord_draw     = $ord_draw ;
      $this->ord_count    = $ord_count ;
      
      $this->labor_times_for_item = $labor_times_for_item ;
      
      $this->ord_date     = GetSplitDate( $ord_date );
      $this->exec_date    = GetSplitDate( $exec_date );
      $this->ord_state    = $ord_state ;
      
      $this->id_zak       = $id_zak ;
      $this->ord_type     = $ord_type ;
      $this->ord_name     = $ord_name ;
      
      $this->dse_name     = $dse_name ;
      $this->customer     = $customer ;
      $this->job_kind     = $job_kind ;      
      
      $this->material     = $material ;
      $this->comment      = $comment ;      
      $this->aim_type     = $aim_type;
      
      $this->plan_price   = 1 * $plan_price ;
      $this->work_price   = 1 * $work_price ;
      $this->fact_price   = 1 * $fact_price ;
      $this->eff          = 1 * $eff ;
      
    }

    public function GetHtmlBody()
    {
      $ord_type = array(" ","ОЗ","КР","СП","БЗ","ХЗ","ВЗ");
      $aim_type = array( "", "Заказ","Поставка","Склад","Рем. оборуд.","Хоз. нужды","Прочее");
      $ord_str = $ord_type[ $this -> ord_type ]." ".$this -> ord_name ;
      $aim_str = $aim_type[ $this -> aim_type ];
      $name = $this -> cr_name ;
      
      $id = $this -> ord_number ;
      $count = $this -> ord_count ;
      $labor_times_for_item = $this -> labor_times_for_item ;
      $labor_times_total = $labor_times_for_item * $count + 0.15;

      $plan_price = $this->plan_price ;
      $work_price = $this->work_price ;
      $fact_price = $this->fact_price ;
      $eff = $this -> eff ;

// Line 1
// <td class='Field' rowspan='8'>".( $this -> ord_name )."&nbsp".( $this -> id_zak )."</td>
$str = "<tr class='order_row-$id'>
         <td class='Field' rowspan='8'>$name</td>
         <td class='Field head_field' colspan='4' rowspan='2'><b>".( $this -> customer )."</b> ".( $this -> ord_date )."</td>
         <td class='Field head_field' colspan='3' rowspan='2'>Согл. / Откл.: <b></b> </td>
         <td class='Field head_field'  rowspan='2'>State1</td>
         <td class='Field head_field'>План Н/Ч</td>
         <td class='Field AC' id='labor_times_total-$id'>$labor_times_total</td>
         <td class='Field' rowspan='9'><img src='uses/nodel.png' alt='Нет доступа'></td></tr>";

// Line 2
$str .= "<tr class='order_row-$id'>
         <td class='Field head_field'>Цена Н/Ч план., руб.</td>
         <td class='rwField ntabg'><input type='text' id='plan_price-$id' value='$plan_price'></td>
         </tr>";

// Line 3
$str .= "<tr class='order_row-$id'>
         <td class='Field'>".( $this -> dse_name )."</td>
         <td class='Field'>".( $this -> ord_draw )."</td>
         <td class='Field AC'>$count</td>
         <td class='Field AC'>$labor_times_for_item</td>
         <td rowspan='3' class='Field AC'>$aim_str</td>
         <td rowspan='3' class='Field AC'>$ord_str</td>
         <td rowspan='3' class='Field AC'>".( $this -> exec_date )."</td>
         <td rowspan='3' class='Field AC'>State3</td>
         <td class='Field head_field'>Стоимость работ<br>без НДС, руб.</td>
         <td class='Field'><span id='work_price-$id'>$work_price</span></td>
         </tr>";

// Line 4
$str .= "<tr class='order_row-$id'>
        <td colspan='4' rowspan='2' class='Field'><b>Материал :</b> ".( $this -> material )."</td>
        <td class='Field head_field'>Цена итого руб.</td>
        <td class='rwField ntabg'><input type='text' id='fact_price-$id' value='$fact_price'></td>        
        </tr>";

// Line 4_2
$str .= "<tr class='order_row-$id'>
        <td class='Field head_field'>Стоим. работ факт. без НДС руб.</td>
        <td class='Field'><span id='fact_price_without_nds-$id'>".round( $fact_price/1.18 , 2 )."</span></td>
        </tr>";
        

// Line 5
$str .= "<tr class='order_row-$id'>
        <td class='Field head_field' colspan='8'><b>Вид работ, комментарии:</b></td>
        <td class='Field head_field'>Эффект без<br>НДС.руб.</td>
        <td class='Field' id='eff-$id'>$eff</td>
        </tr>";

// Line 6
$str .= "<tr class='order_row-$id'>
        <td colspan='8' class='Field'>".( $this -> job_kind )."</td>
        <td class='Field head_field'>Согл.</td>
        <td class='rwField'><!-- Val3 --></td>
        </tr>";

// Line 7
$str .= "<tr class='order_row-$id'>
         <td class='Field head_field'colspan='8'><b>Комментарии ОВК (Проработка/выполнение):</b></td>
         <td class='Field head_field' rowspan='2'><!-- Val4 --></td>
         <td class='Field head_field center' rowspan='2'><button id='close_but-$id' class='close_but'>Закрыть</button></td>
         </tr>";

// Line 8
  $str .= "<tr class='order_row-$id'>
         <td colspan='8' class='rwField tabg'><textarea >".( $this -> comment )."</textarea></td>
         </tr>";
    
  return $str ;
    }

    public function GetHtmlHead()
    {
      	return "<table width='1700px' class='rdtbl tbl' id='tbl'>
                <thead>
                <tr class='first'>
                <td width='90'>Рег №</td>
                <td width='200'>Наименование детали</td>
                <td width='160'>Чертеж</td>
                <td width='50'>Кол-во</td>
                <td width='50'>План<br>Н/Ч</td>                
                <td width='100'>Назначение</td>
                <td width='120'>Заказ</td>
                <td width='100'>Срок<br>поставки</td>
                <td width='110'>Статус</td>                
                <td width='230' colspan='2'></td>
                <td width='10'><img src='uses/nodel.png' title='Удаление элементов'></td>
                </tr>
                </thead><tbody>";
    
    }

    public function GetHtmlFoot()
    {
      return "</tbody></table>";
    }

}
  
?>





