<?php
require_once '../uphead.php';
$year = date('y');
$year3 = (int)$year - 1;
$year4 = (int)$year - 2;
$cust = $_GET['CUST'];

$s2 = "Select * from  cust_sales_mtd where custsh = $cust and iyear = $year";
$r2 = db2_exec($con, $s2);
//  var_dump($s2, db2_stmt_errormsg());





$data = array();
$id = 1;
While($row2 = db2_fetch_assoc($r2)){
$x['ID'] = $id;
$x['LABEL'] = $row2['IMONTH'];
$x['INVOICED'] = number_format($row2['INVOICED'],2);
$x['TONS'] = number_format($row2['TONSHP'],2);
$x['COMMIS'] = number_format($row2['COMMIS'],2);
$data[] = $x;


}




$jTableResult = array();
$jTableResult['Result'] = "OK";
$jTableResult['Records'] = $data;

//$jTableResult['TotalRecordCount'] = $row2['CNT'];
print json_encode($jTableResult);