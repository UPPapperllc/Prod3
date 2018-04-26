<?php

$path = '/www/zendphp7/htdocs/';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
$path = '/www/zendphp7/htdocs/Prod3/';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);



require_once 'uphead.php';
require_once 'UPClass2.php';
//// var_dump($con);

$rtn = UPClass2::setSchedule($con);

