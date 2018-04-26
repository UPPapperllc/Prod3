<?php
require_once '../uphead.php';
$year = date('y');
$year3 = (int)$year - 1;
$year4 = (int)$year - 2;
$cust = $_GET['CUST'];

$s2 = "Select * from  cust_sales_ytd where custsh = $cust and iyear = $year";
$r2 = db2_exec($con, $s2);
//  var_dump($s2, db2_stmt_errormsg());
$row2 = db2_fetch_assoc($r2);

$s3 = "Select * from  cust_sales_ytd where custsh = $cust and iyear = $year3";
$r3 = db2_exec($con, $s3);
//  var_dump($s2, db2_stmt_errormsg());
$row3 = db2_fetch_assoc($r3);

$s4 = "Select * from  cust_sales_ytd where custsh = $cust and iyear = $year4";
$r4 = db2_exec($con, $s4);
//  var_dump($s2, db2_stmt_errormsg());
$row4 = db2_fetch_assoc($r4);


$data = array();
$x['ID'] = 1;
$x['LABEL'] = 'Sales for 20' . $year;
$x['INVOICED'] = number_format($row2['INVOICED'],2);
$x['TONS'] = number_format($row2['TONSHP'],2);
$x['COMMIS'] = number_format($row2['COMMIS'],2);
$data[] = $x;
$x['ID'] = 2;
$x['LABEL'] = 'Sales for 20' . $year3;
$x['INVOICED'] = number_format($row3['INVOICED'],2);
$x['TONS'] = number_format($row3['TONSHP'],2);
$x['COMMIS'] = number_format($row3['COMMIS'],2);
$data[] = $x;
$x['ID'] = 3;
$x['LABEL'] = 'Sales for 20' . $year4;
$x['INVOICED'] = number_format($row4['INVOICED'],2);
$x['TONS'] = number_format($row4['TONSHP'],2);
$x['COMMIS'] = number_format($row4['COMMIS'],2);
$data[] = $x;



$jTableResult = array();
$jTableResult['Result'] = "OK";
$jTableResult['Records'] = $data;

//$jTableResult['TotalRecordCount'] = $row2['CNT'];
print json_encode($jTableResult);