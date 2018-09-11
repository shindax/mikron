<?php
       if (!$_GET["sort"]) $orderby = "order by ID desc, CONTRAGENT, VID_FAIL";
       if ($_GET["sort"]=="1") {
            $orderby = "order by ID desc, CONTRAGENT, VID_FAIL";
       }
        if ($_GET["sort"]=="2") {
            $orderby = "order by ID, CONTRAGENT, VID_FAIL";
       }
       if ($_GET["sort"]=="3") {
            $orderby = "order by CONTRAGENT, ID desc, VID_FAIL";
       }
       if ($_GET["sort"]=="4") {
            $orderby = "order by CONTRAGENT desc, ID desc, VID_FAIL";
       }
       if ($_GET["sort"]=="5") {
            $orderby = "order by VID_FAIL, ID desc, CONTRAGENT";
       }
       if ($_GET["sort"]=="6") {
            $orderby = "order by VID_FAIL desc, ID desc, CONTRAGENT";
       }
	   
?>