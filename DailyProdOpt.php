<?php
require_once '../uphead.php';
$user = strtoupper($_SERVER['PHP_AUTH_USER']);

require "UPClass2.php";
$menu = UPClass2::checkMenuOptions($con);

$s = "select * from dailyprodopt f1
join dailyprod f2 on f1.pgmid = f2.pgmid and UsrID = '$user' ";
$r = db2_exec($con, $s);
$data = array();
while($row = db2_fetch_assoc($r)){
    if ($row['ACTIVE'] == 'N') $row['CHECKED'] = ''; else  $row['CHECKED'] = 'checked'; 
 $data[] = $row;   
}

$jTableResult = array();
$jTableResult['Result'] = "OK";
$jTableResult['Records'] = $data;
//$jTableResult['TotalRecordCount'] = $row2['CNT'];
print json_encode($jTableResult);
