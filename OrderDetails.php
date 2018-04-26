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
$row = db2_fetch_assoc($r);



 
$codedata = "<table border='1'>";
$codedata .= '<tr><th>Status</th><td>'.$row['SCODE'].'</td></tr>'; 
$codedata .= '<tr><th>Hold</th><td>'.$row['SHPHLD'].'</td></tr>'; 
$codedata .= '<tr><th>Ship Via</th><td>'.$row['CARVIA'].'</td></tr>'; 
$codedata .= "</table>";



$data = array();
$x['ID'] = 1;
$x['LABEL'] = 'Shipment Number';
$x['VALUE'] = $shp;
$data[] = $x;

$x['ID'] = 3;
$x['LABEL'] = 'Required by Date';
$x['VALUE'] = $row['RMONTH'] . '/'. $row['RDAY'].'/20'.$row['RYEAR'];
$data[] = $x;
$x['ID'] = 4;
$x['LABEL'] = 'Order Weight';
$x['VALUE'] = $row['WGTGRS'];
$data[] = $x;
$x['ID'] = 5;
$x['LABEL'] = '#of Rolls';
$x['VALUE'] = $row['ROLLS#'];
$data[] = $x;
$x['ID'] = 6;
$x['LABEL'] = 'Width';
$x['VALUE'] = $row['ROLWTH'] . ' '. $row['ROLFRC'];
$data[] = $x;

$x['ID'] = 15;
$x['LABEL'] = 'Codes';
$x['VALUE'] = $codedata;
$data[] = $x;






$jTableResult = array();
$jTableResult['Result'] = "OK";
$jTableResult['Records'] = $data;

//$jTableResult['TotalRecordCount'] = $row2['CNT'];
print json_encode($jTableResult);


function formatNum($num){
    $len  = strlen($num);
    //   var_dump($num, $len);
    if ($len == 11){
        $rtnnum = substr($num,0,1) . '(' . substr($num,1,3) . ') ' . substr($num,4,3 ) . '-' . substr($num,7,4);
    } elseif($len == 10){
        $rtnnum = '(' . substr($num,0,3) . ') ' . substr($num,3,3 ) . '-' . substr($num,6,4);
    } elseif($len == 7){
        $rtnnum =  substr($num,0,3 ) . '-' . substr($num,3,4);
    } else $rtnnum = $num;
    return $rtnnum;
}