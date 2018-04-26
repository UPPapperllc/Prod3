<?php

error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('display_startup_errors', true);

include '../../uphead.php';
if (isset($_GET['MILRUN']))$milrun = $_GET['MILRUN']; else $milrun = 0;

$s = "Select * from Prod_Schedule where schedstat not in ('Complete', 'Closed') and milrun = $milrun";
$r = db2_exec($con, $s);
//var_dump($s, db2_stmt_errormsg());
$row = db2_fetch_assoc($r);
if (!$row or $milrun < 1000){

$s = "Select max(SCHETS) as Max from Prod_Schedule where MILRUN >= 1000";
$r=db2_exec($con, $s);
$row = db2_fetch_assoc($r);
//var_dump($row['MAX']);
$dt = $row['MAX'];
$stat = 'New';
} else {
    
  //  echo '<br>';
  //  var_dump($row);
    $dt = $row['SCHTS'];
    $stat = 'Change';
    
}
$newdt = substr($dt,5,2) . '/' . substr($dt,8,2).'/'. substr($dt,0,4) . ' ' . substr($dt,11,2) . ':'. substr($dt,14,2) . ':' . substr($dt,17,2);
$x['dt'] = $newdt;
$x['stat'] = $stat;
$x['row'] = $row;
$data[0] = $x;
$data['success'] = true;
echo json_encode($data);