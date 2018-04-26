<?php

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
    exit;
}

require_once '../uphead.php';
require "UPClass2.php";
$userdata = UPClass2::getUserData($con);

$data = array();
$x['KEY'] = 'USUSER';
$x['LABEL'] = 'MyID';
$x['VALUE'] = $userdata['USER'];
$data[] = $x;
$x['KEY'] = 'USDESC';
$x['LABEL'] = 'My Name';
$x['VALUE'] = $userdata['NAME'];
$data[] = $x;
$x['KEY'] = 'USEMAL';
$x['LABEL'] = 'My Email';
$x['VALUE'] = $userdata['EMAIL'];
$data[] = $x;


$jTableResult = array();
$jTableResult['Result'] = "OK";
$jTableResult['Records'] = $data;
//$jTableResult['TotalRecordCount'] = $row2['CNT'];
print json_encode($jTableResult);
