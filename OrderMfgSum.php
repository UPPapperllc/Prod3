<?php
require_once '../uphead.php';
$year = date('y');
$year3 = (int)$year - 1;
$year4 = (int)$year - 2;
$ord = $_GET['O'];
$shp = $_GET['S'];

$s = "Select * from ordatep where MILORD = '$ord' and SHIP#=$shp";
$r = db2_exec($con, $s);
//var_dump($s, db2_stmt_errormsg());
$rowdtl = db2_fetch_assoc($r);

$ordwgt = $rowdtl['WGTGRS'];

$s = "select lpstat,  count(*) as rolls, sum(rolwgta) as wgt, sum(rollena) as len, sum(ROLKSQFTA) as SQFT
from lpmast where milord = '$ord' and shipnum = $shp    group by lpstat";
$r = db2_exec($con, $s);
$data = array();
while($row = db2_fetch_assoc($r)){
$stat = $row['LPSTAT']; // default
    if (trim($row['LPSTAT']) == 'New') $stat = 'Produced(*)';
    if (trim($row['LPSTAT']) == 'Wrapped') $stat = 'Produced(**)';
    $wprod = ((float)$row['WGT']/(float)$ordwgt) * 100;
$x['STATUS'] = $stat;
$x['ROLLS'] = number_format($row['ROLLS']);
$x['WGT'] = number_format($row['WGT']);
$x['LEN'] = number_format($row['LEN']);
$x['SQFT'] = number_format($row['SQFT']);
$x['WP'] = round($wprod,2) . '%';
$data[] = $x;
}
// STATUS, ROLLS, WGT, LEN, SQFT, WP
$jTableResult = array();
$jTableResult['Result'] = "OK";
$jTableResult['Records'] = $data;

//$jTableResult['TotalRecordCount'] = $row2['CNT'];
print json_encode($jTableResult);