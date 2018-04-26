<?php
require_once '../uphead.php';

if(isset($_GET['O'])){
 $o = $_GET['O'];
 $s = "Select * from ordentp where MILORD = '$o'";
 $r = db2_exec($con, $s);
 $ordrow = db2_fetch_assoc($r);
 $cust = $ordrow['CUSTSH'];
}  else {


$cust = $_GET['CUST'];
}
$action = $_GET['action'];

if (trim($action) == 'list'){

$s2 = "Select * from  transitTime where cust = $cust";
$r2 = db2_exec($con, $s2);
//  var_dump($s2, db2_stmt_errormsg());





$data = array();
$id = 1;
$row2 = db2_fetch_assoc($r2);

$x['ID'] = 'TRUCKMILES';
$x['LABEL'] = 'Transit Miles';
$x['VALUE'] = number_format($row2['TRUCKMILES']);
$x['TIP'] = ' ';
$x['READONLY'] = '0';
$data[] = $x;
$x['ID'] = 'TRUCKHOURSMIN';
$x['LABEL'] = 'Transit Hours/Truck';
$x['VALUE'] = number_format($row2['TRUCKHOURS']) .':'. number_format($row2['TRUCKMIN']) ;
$x['READONLY'] = '1';
$x['TIP'] = ' ';
$x['READONLY'] = '0';
$data[] = $x;
$x['ID'] = 'TRUCKDAYS';
$x['LABEL'] = 'Transit Days/Truck';
$x['VALUE'] = number_format($row2['TRUCKDAYS']);
$x['TIP'] = 'Default is hours/8 for safe driving time';
$x['READONLY'] = '0';
$data[] = $x;
$x['ID'] = 'RAILDAYS';
$x['LABEL'] = 'Transit Days/Rail';
$x['VALUE'] = number_format($row2['RAILDAYS']);
$x['TIP'] = 'I not calculated at this time must be manually input';
$x['READONLY'] = '0';
$data[] = $x;


$jTableResult = array();
$jTableResult['Result'] = "OK";
$jTableResult['Records'] = $data;

//$jTableResult['TotalRecordCount'] = $row2['CNT'];
print json_encode($jTableResult);

}
if (trim($action) == 'update'){
    
    $field = $_POST['ID'];
    $value = $_POST['VALUE'];
    $s = "Update TransitTime set $field = $value where CUST = $cust";
    $r = db2_exec($con, $s);
    if (!$r){
        $jTableResult['Result'] = "Error";
        $jTableResult['Message'] = db2_stmt_errormsg();
    } else {
        $jTableResult['Result'] = "OK";
    }
    
    print json_encode($jTableResult);
}