<?php
       if (!$_GET["sort"]) $orderby = "order by DATE_PLAN, (SELECT NAME FROM okb_db_resurs WHERE okb_db_resurs.ID=okb_db_itrzadan.ID_users), (SELECT NAME FROM okb_db_resurs WHERE okb_db_resurs.ID=okb_db_itrzadan.ID_users3), ID desc, STATUS";
       if ($_GET["sort"]=="1") {
            $orderby = "order by DATE_PLAN, (SELECT NAME FROM okb_db_resurs WHERE okb_db_resurs.ID=okb_db_itrzadan.ID_users), (SELECT NAME FROM okb_db_resurs WHERE okb_db_resurs.ID=okb_db_itrzadan.ID_users3), ID desc, STATUS";
       }
        if ($_GET["sort"]=="2") {
            $orderby = "order by DATE_PLAN desc, (SELECT NAME FROM okb_db_resurs WHERE okb_db_resurs.ID=okb_db_itrzadan.ID_users), (SELECT NAME FROM okb_db_resurs WHERE okb_db_resurs.ID=okb_db_itrzadan.ID_users3), ID, STATUS";
       }
       if ($_GET["sort"]=="3") {
            $orderby = "order by ID desc, (SELECT NAME FROM okb_db_resurs WHERE okb_db_resurs.ID=okb_db_itrzadan.ID_users), (SELECT NAME FROM okb_db_resurs WHERE okb_db_resurs.ID=okb_db_itrzadan.ID_users3), DATE_PLAN, STATUS";
       }
       if ($_GET["sort"]=="4") {
            $orderby = "order by ID, (SELECT NAME FROM okb_db_resurs WHERE okb_db_resurs.ID=okb_db_itrzadan.ID_users), (SELECT NAME FROM okb_db_resurs WHERE okb_db_resurs.ID=okb_db_itrzadan.ID_users3), DATE_PLAN desc, STATUS";
       }
       if ($_GET["sort"]=="5") {
            $orderby = "order by (SELECT NAME FROM okb_db_resurs WHERE okb_db_resurs.ID=okb_db_itrzadan.ID_users), DATE_PLAN, (SELECT NAME FROM okb_db_resurs WHERE okb_db_resurs.ID=okb_db_itrzadan.ID_users3), ID desc, STATUS";
       }
       if ($_GET["sort"]=="6") {
            $orderby = "order by (SELECT NAME FROM okb_db_resurs WHERE okb_db_resurs.ID=okb_db_itrzadan.ID_users) desc, DATE_PLAN, (SELECT NAME FROM okb_db_resurs WHERE okb_db_resurs.ID=okb_db_itrzadan.ID_users3), ID desc, STATUS";
       }
       if ($_GET["sort"]=="7") {
            $orderby = "order by (SELECT NAME FROM okb_db_resurs WHERE okb_db_resurs.ID=okb_db_itrzadan.ID_users3), (SELECT NAME FROM okb_db_resurs WHERE okb_db_resurs.ID=okb_db_itrzadan.ID_users), DATE_PLAN, ID desc, STATUS";
       }
       if ($_GET["sort"]=="8") {
            $orderby = "order by (SELECT NAME FROM okb_db_resurs WHERE okb_db_resurs.ID=okb_db_itrzadan.ID_users3) desc, (SELECT NAME FROM okb_db_resurs WHERE okb_db_resurs.ID=okb_db_itrzadan.ID_users), DATE_PLAN, ID desc, STATUS";
       }
       if ($_GET["sort"]=="9") {
            $orderby = "order by STATUS, DATE_PLAN, (SELECT NAME FROM okb_db_resurs WHERE okb_db_resurs.ID=okb_db_itrzadan.ID_users), (SELECT NAME FROM okb_db_resurs WHERE okb_db_resurs.ID=okb_db_itrzadan.ID_users3), ID desc";
       }
       if ($_GET["sort"]=="10") {
            $orderby = "order by STATUS desc, DATE_PLAN, (SELECT NAME FROM okb_db_resurs WHERE okb_db_resurs.ID=okb_db_itrzadan.ID_users), (SELECT NAME FROM okb_db_resurs WHERE okb_db_resurs.ID=okb_db_itrzadan.ID_users3), ID desc";
       }
?>