<?php
if (!$_GET["arch"]) {
       if (!$_GET["sort"]) $orderby = "order by DATE_PLAN, ID desc";
       if ($_GET["sort"]=="1") {
            $orderby = "order by DATE_PLAN, ID desc";
       }
        if ($_GET["sort"]=="2") {
            $orderby = "order by DATE_PLAN desc, ID desc";
       }
       if ($_GET["sort"]=="3") {
            $orderby = "order by ID desc, DATE_PLAN";
       }
       if ($_GET["sort"]=="4") {
            $orderby = "order by ID, DATE_PLAN";
       }
}
if ($_GET["arch"]) {
       if (!$_GET["sort"]) $orderby = "order by ID desc, DATE_PLAN";
       if ($_GET["sort"]=="1") {
            $orderby = "order by ID desc, DATE_PLAN";
       }
        if ($_GET["sort"]=="2") {
            $orderby = "order by ID, DATE_PLAN";
       }
       if ($_GET["sort"]=="3") {
            $orderby = "order by DATE_PLAN, ID desc";
       }
       if ($_GET["sort"]=="4") {
            $orderby = "order by DATE_PLAN desc, ID desc";
       }
}
?>