<?php
// после успешного добавления идёт редирект на $pageurl
// $delet_id - ID удаляемого элемента
	dbquery("DELETE from okb_db_mtk_perehod WHERE ID_operitems='".$delet_id."' ");
	dbquery("DELETE from okb_db_mtk_perehod_img WHERE ID_operitems='".$delet_id."' ");
?>