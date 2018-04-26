<?php
require_once '../uphead.php';
require_once 'UPClass2.php';
$ord = $_GET['O'];
$shp = $_GET['S'];
$action = $_GET['action'];


if (trim($action) == 'list'){


$s = "Select * from ordentp 
left join hdcust on custsh = cmcust where milord = '$ord' ";
$r = db2_exec($con, $s);
//var_dump($s, db2_stmt_errormsg());
$row = db2_fetch_assoc($r);
$cust = $row['CUSTSH'];
if (strlen(trim($cust)) < 7){
    $cust = str_pad($cust, 7 , '0', STR_PAD_LEFT);
}
$na1 = trim($row['CMCNA1']);





$data = array();
$x['PATH'] = "attachments/PR/Customer/$cust/$ord - " . $shp ."_ $na1.pdf";
$x['FILENAME'] = $ord." - " . $shp ."_ ".$na1.".pdf";
$x['CUST'] = $cust;
$data[] = $x;

$x['PATH'] = "attachments/PR/Customer/$cust/$ord - " . $shp ."_ $na1.xlsx";
$x['FILENAME'] = $ord." - " . $shp ."_ ".$na1.".xlsx";
$x['CUST'] = $cust;
$data[] = $x;





$jTableResult = array();
$jTableResult['Result'] = "OK";
$jTableResult['Records'] = $data;

//$jTableResult['TotalRecordCount'] = $row2['CNT'];
print json_encode($jTableResult);

}
if (trim($action) == 'update'){
    
    $s = "Call COA2 ('$ord', '$shp') ";
    db2_exec($con, $s);
    $data = array();
    $x['MSG'] = 'COA has been submitted for processing';
    $data[]=$x;
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $data;
    
}

