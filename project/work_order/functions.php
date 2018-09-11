<?php
require_once( "classes/db.php" );

function getResourceList( $pdo )
{
    $str = "<option value ='0'>Выберите ресурс</option>";

    $query = "
                SELECT 
                ID_resurs AS id, 
                NAME AS name 
                FROM `okb_db_shtat` 
                WHERE  
                presense_in_shift_orders = 1 
                AND
                name NOT LIKE '%Вакансия%'
                ORDER BY name";

        try
        {
            $stmt = $pdo->prepare( $query );
            $stmt->execute();
        }
        catch (PDOException $e)
        {
            die("Error in :".__FILE__." file, in ".__FUNCTION__." function, at ".__LINE__." line. Can't get data : " . $e->getMessage());
        }

        while ( $row = $stmt->fetch(PDO::FETCH_LAZY ) )
        {
            $name = $row['name'];
            $id = $row['id'];
            if( strlen( $name ))
                $str .= "<option value ='$id'>$name</option>";
        }

    return $str ;
}


function conv( $str )
{
  return iconv("UTF-8", "Windows-1251", $str );
}

function uconv( $str )
{
  return iconv("Windows-1251", "UTF-8", $str );
}

function debug($arr)
{
    echo '<pre>' . print_r($arr, true) . '</pre>';
}

function getHead()
{
    return conv( '<div class="wrapper"><div class="flex-top"><h1>Добавление новых заданий</h1></div><div class="center">');
}

function getFooter()
{
    global $pdo ;

    $res_list =  getResourceList( $pdo );
    $html = <<<HTML
</div>
    <div class="footer">
        <div id='fix1'>
            <div>
                <button id="show_work_orders">Показать сменные задания</button>
            </div>
        </div>
        <!--div id='fix2'>
            <div>
                <span class='title'>????</span><br>
            </div>
        </div-->
        <div id='fix3'>
            <div>
                <span class='title'>Навигация новая</span><br>
                    <span>Дата&nbsp;</span><input type='date' id='datepicker'></input>
                    <select id='shift_sel'>
                        <option value='0'>Смена</option>
                        <option value='1'>1</option>
                        <option value='2'>2</option>
                        <option value='3'>3</option>
                    </select>

                    <select id='res_sel'>$res_list</select>
            </div>
        </div>
        <div id='fix4'>
            <div>
                <span class='title'>Поиск заказа</span><br>
                <div id="order_search_div">
                    <input id="order_search" />
                    <!--button id="close_order_list">X</button>
                    <input id="orders_input" class="hidden"/-->

                    <div id="sel_div" class="hidden">
                        <ul id="order_ul">
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
HTML;
    return $html;
}



