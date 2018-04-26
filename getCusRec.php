<?php
require_once '../uphead.php';
$year = date('y');
$year3 = (int)$year - 1;
$year4 = (int)$year - 2;
$cust = $_GET['CUST'];

$s = "Select * from hdcust where CMCUST = $cust";
$r = db2_exec($con, $s);
$row = db2_fetch_assoc($r);

$data = array();
$x['ID'] = 1;
$x['LABEL'] = 'Name';
$x['VALUE'] = $row['CMCNA1'];
$data[] = $x;
$x['ID'] = 2;
$x['LABEL'] = 'Address Line 1';
$x['VALUE'] = $row['CMCNA2'];
$data[] = $x;
$x['ID'] = 3;
$x['LABEL'] = 'Address Line 2';
$x['VALUE'] = $row['CMCNA3'];
$data[] = $x;
$x['ID'] = 4;
$x['LABEL'] = 'Address Line 3';
$x['VALUE'] = $row['CMCNA4'];
$data[] = $x;
$x['ID'] = 5;
$x['LABEL'] = 'City';
$x['VALUE'] = $row['CMCCTY'];
$data[] = $x;
$x['ID'] = 6;
$x['LABEL'] = 'State';
$x['VALUE'] = $row['CMST'];
$data[] = $x;
$x['ID'] = 7;
$x['LABEL'] = 'Postal Code';
$x['VALUE'] = $row['CMZIP'];
$data[] = $x;
$x['ID'] = 8;
$x['LABEL'] = 'Country Code';
$x['VALUE'] = $row['CMCTRY'];
$data[] = $x;
$x['ID'] = 9;
$x['LABEL'] = 'PHONE';
$x['VALUE'] = formatNum($row['CMPHON']);
$data[] = $x;
$x['ID'] = 9;
$x['LABEL'] = 'FAX';
$x['VALUE'] = formatNum($row['CMFAX']);
$data[] = $x;
if (trim($row['CMUDF2']) == '') $coa = '*COA'; else $coa = $row['CMUDF2']; 
$x['ID'] = 9;
$x['LABEL'] = 'COA Format';
$x['VALUE'] = $coa;
$data[] = $x;

$s2 = "Select * from hdcusu left join syudfm on uffiln = 'HDCUSU' and uffldn = cufldn
where cucust = $cust";
$r2 = db2_exec($con, $s2);
while ($row2 = db2_fetch_assoc($r2)){
    $x['ID'] = $row2['CUFLDN'];
    $x['LABEL'] = $row2['UFDESC'];
    if ($row2['UFTYPE'] == 'N') $x['VALUE'] = $row2['CUFLDR']; else $x['VALUE'] = $row2['CUFLDV'];
    
    $data[] = $x;
}


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