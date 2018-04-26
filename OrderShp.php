<?php
require_once '../uphead.php';
$year = date('y');
$year3 = (int)$year - 1;
$year4 = (int)$year - 2;
$ord = $_GET['O'];
$shp = $_GET['S'];

$s = "SELECT * FROM shpordp f1 left join shpoutp f2 on f1.recnum =       
f2.recnum left join shpsubp f3 on f1.milord = f3.milord and        
f1.ship# = f3.ship# left join shpcmtp f4 on f4.milord = f1.milord  
and f1.ship# = f4.ship#                             
where f1.milord = '$ord' and f1.ship# = $shp  ";

$r = db2_exec($con, $s);
$row = db2_fetch_assoc($r);




$data = array();
$x['ID'] = 1;
$x['LABEL'] = 'Use Storage Rolls';
$x['VALUE'] = $row['STOROL'];
$data[] = $x;
$x['LABEL'] = 'Carrier Code';
$x['VALUE'] = $row['CARCD2'];
$data[] = $x;
$x['LABEL'] = 'Container ';
$x['VALUE'] = $row['TRKCAR'];
$data[] = $x;
$x['LABEL'] = 'Departure Date';
$x['VALUE'] = $row['DMONTH'] . '/'. $row['DDAY'].'/20'.$row['DYEAR'];
$data[] = $x;
$x['LABEL'] = 'Delivery Appointment ';
$x['VALUE'] = $row['DELDTAP'] . ' / '. $row['DELTMAP'];
$data[] = $x;
$x['LABEL'] = 'Comment ';
$x['VALUE'] = $row['COMMT'];
$data[] = $x;


$jTableResult = array();
$jTableResult['Result'] = "OK";
$jTableResult['Records'] = $data;

//$jTableResult['TotalRecordCount'] = $row2['CNT'];
print json_encode($jTableResult);
