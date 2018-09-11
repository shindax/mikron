<?php
//////////////////////////////////////////////////////
//
//	MAV ERP Solution
//
//	© 2012 Мирошников А.В.
//
//////////////////////////////////////////////////////

	if (!defined("MAV_ERP")) { die("Access Denied"); }


function gzip_start() {
   ob_start();
   ob_implicit_flush(0);
}

function gzip_output($compress = true, $use_etag = true, $send_body = true) {
$min_gz_size = 1024;
$page = ob_get_contents();
$length = strlen($page);
ob_end_clean();

if ($compress && extension_loaded('zlib') &&
    (strlen($page) > $min_gz_size) &&
    isset($globals['http_server_vars']['http_accept_encoding'])) {
   $ae = explode(',', str_replace(' ', '', $globals['http_server_vars']['http_accept_encoding']));
   $enc = false;
   if (in_array('gzip', $ae)) {
    $enc = 'gzip';
   } else if (in_array('x-gzip', $ae))
    $enc = 'x-gzip';

   if ($enc) {
    $length = strlen($page);
    header('content-encoding: ' . $enc);
    header('vary: accept-encoding');
   } else {
    $compress = false;
   }
} else
   $compress = false;

if ($use_etag) {
   $etag = '"' . md5($page) . '"';
   header('etag: ' . $etag);
   if (isset($globals['http_server_vars']['http_if_none_match'])) {
    $inm = explode(',', $globals['http_server_vars']['http_if_none_match']);
    foreach ($inm as $i) {
       if (trim($i) == $etag) {
        header('http/1.0 304 not modified');
        $send_body = false;
        break;
       }
    }
   }
}

if ($send_body) {
   header('content-length: ' . $length);
   echo $page;
   }
}

?>