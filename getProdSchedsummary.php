<?php
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
    exit;
}
require_once '../uphead.php';
$user = strtoupper(trim($_SERVER['PHP_AUTH_USER']));




    
    $s = "Select max(SCHDATE) as Max from Prod_Schedule";
    $r=db2_exec($con, $s);
    $row = db2_fetch_assoc($r);
    
    $sdate =  $row['MAX'];
    $s = "Select max(SCHTIME) as MaxT from Prod_Schedule where SCHDATE = '$sdate'";
    $r=db2_exec($con, $s);
    $row = db2_fetch_assoc($r);
    $stime = $row['MAXT'];
    
    
    //Return result to jTable
    $jTableResult = array();
    //$jTableResult['Result'] = "OK";
 
    $jTableResult['SDate'] = $sdate;
    $jTableResult['STime'] = $stime;

    print json_encode($jTableResult);
    
