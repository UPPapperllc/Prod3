<?php
include '../uphead.php';
sleep(5);
$th = $_GET['TH'];
$tm = $_GET['TM'];
$ts = $_GET['TS'];
$cust = $_GET['CUST'];
$zip = $_GET['ZIP'];
$miles = $_GET['MILES'];

$days = ceil($th/8);

$s = "Delete from TransitTime where CUST = $cust with NC";
$r = db2_exec($con, $s);

$s = "Insert into TransitTime (CUST, TruckMiles, TruckHours, TruckMin, TruckSec, TruckDays, ZIP) values(
$cust,
$miles,
$th,
$tm, 
$ts,
$days,
'$zip')";
$r = db2_exec($con, $s);
//var_dump($s, db2_stmt_errormsg());

?>
<script>
 window.close();
</script>