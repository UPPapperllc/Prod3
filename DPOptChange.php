<?php
require_once '../uphead.php';
$user = strtoupper($_SERVER['PHP_AUTH_USER']);
$pgmid = $_GET['PGID'];
$yn = $_GET['YN'];
if ($yn == 'true') $active = 'Y'; else $active = 'N'; 
$s = "Update DailyProdOpt set Active = '$active' where PGMID = $pgmid and USRID = '$user'";
$r = db2_exec($con, $s);
$data = array();
$x['success'] = 'true';
$x['YN'] = $yn;
$x['ACTIVE'] = $active;
$data[] = $x;
$jTableResult = array();
$jTableResult['Result'] = "OK";
$jTableResult['Records'] = $data;
//$jTableResult['TotalRecordCount'] = $row2['CNT'];
print json_encode($jTableResult);