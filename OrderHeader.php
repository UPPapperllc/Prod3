<?php
require_once '../uphead.php';
$year = date('y');
$year3 = (int)$year - 1;
$year4 = (int)$year - 2;
$ord = $_GET['O'];
$shp = $_GET['S'];

$s = "Select * from ordentp where MILORD = '$ord'";
$r = db2_exec($con, $s);
//var_dump($s, db2_stmt_errormsg());
$row = db2_fetch_assoc($r);
$billto = $row['CUST#'];
$shipto = $row['CUSTSH'];

$s2 = "Select * from HDCUST where CMCUST = $billto";
$r2 = db2_exec($con, $s2);
$row2 = db2_fetch_assoc($r2);
$billtodata = "<table border='1'>";
$billtodata .= '<tr><td>'.$row2['CMCNA1'] . ' ('. $billto . ')</td></tr>'; 
$billtodata .= '<tr><td>'.$row2['CMCNA2'] . '</td></tr>'; 
$billtodata .= '<tr><td>'.$row2['CMCCTY'] .', '. $row2['CMST'] . '   ' . $row2['CMZIP'] .'</td></tr>'; 
$billtodata .= "</table>";

$s3 = "Select * from HDCUST where CMCUST = $shipto";
$r3 = db2_exec($con, $s3);
$row3 = db2_fetch_assoc($r3);
$shiptodata = "<table border='1'>";
$shiptodata .= '<tr><td>'.$row3['CMCNA1'] . ' ('. $shipto . ')</td></tr>';
$shiptodata .= '<tr><td>'.$row3['CMCNA2'] . '</td></tr>';
$shiptodata .= '<tr><td>'.$row3['CMCCTY'] .', '. $row3['CMST'] . '   ' . $row3['CMZIP'] .'</td></tr>';
$shiptodata .= "</table>";
 
$codedata = "<table border='1'>";
$codedata .= '<tr><th>Status</th><td>'.$row['SCODE'].'</td></tr>'; 
$codedata .= '<tr><th>Credit</th><td>'.$row['CRDCDE'].'</td></tr>';
$codedata .= '<tr><th>Mfg Aprvl</th><td>'.$row['MFGCDE'].'</td></tr>'; 
$codedata .= '<tr><th>Trial Order</th><td>'.$row['TRIAL'].'</td></tr>'; 
$codedata .= '<tr><th>Warehouse</th><td>'.$row['WHSCDE'].'</td></tr>'; 
$codedata .= "</table>";

$mfgdata = "<table border='1'>";
$mfgdata .= '<tr><th>Ream</th><td>'.$row['REAM'].'</td></tr>'; 
$mfgdata .= '<tr><th>Basis Weight</th><td>'.$row['WGTBW7'].'</td></tr>';
$mfgdata .= '<tr><th>Caliper</th><td>'.$row['ROLCAL'].'</td></tr>';
$mfgdata .= '<tr><th>Diameter</th><td>'.$row['DIAM'].'</td></tr>';
$mfgdata .= '<tr><th>Core Code</th><td>'.$row['CORCDE'].'</td></tr>';
$mfgdata .= '<tr><th>Group Code</th><td>'.$row['SDCODE'].'</td></tr>'; 
$mfgdata .= '<tr><th>Specification</th><td>'.$row['SPEC#'].'</td></tr>'; 
$mfgdata .= '<tr><th>Generic </th><td>'.$row['GENLBL'].'</td></tr>';
$mfgdata .= '<tr><th>Stensile</th><td>'.$row['STEN'].'</td></tr>';
$mfgdata .= '<tr><th>&nbsp</th><td>'.$row['STEN#1'].'</td></tr>';
$mfgdata .= '<tr><th>&nbsp</th><td>'.$row['STEN#2'].'</td></tr>'; 
$mfgdata .= "</table>";

$data = array();
$x['ID'] = 1;
$x['LABEL'] = 'Bill To';
$x['VALUE'] = $billtodata;
$data[] = $x;
$x['ID'] = 2;
$x['LABEL'] = 'Ship To';
$x['VALUE'] = $shiptodata;
$data[] = $x;
$x['ID'] = 3;
$x['LABEL'] = 'Order Date';
$x['VALUE'] = $row['MONTH'] . '/'. $row['DAY'].'/20'.$row['YEAR'];
$data[] = $x;
$x['ID'] = 4;
$x['LABEL'] = 'Sales Rep';
$x['VALUE'] = $row['SALREP'];
$x['ID'] = 5;
$x['LABEL'] = 'Current Revision';
$x['VALUE'] = $row['REV#'];
$data[] = $x;
$x['ID'] = 6;
$x['LABEL'] = 'Contact';
$x['VALUE'] = $row['CUSCON'];
$data[] = $x;
$x['ID'] = 7;
$x['LABEL'] = 'Purchase Order';
$x['VALUE'] = $row['CUPO15'];
$data[] = $x;
$x['ID'] = 8;
$x['LABEL'] = 'Customers Grade';
$x['VALUE'] = $row['CUSGRD'];
$data[] = $x;
$x['ID'] = 8;
$x['LABEL'] = 'UPP Grade';
$x['VALUE'] = $row['GRD8'];
$data[] = $x;
$x['ID'] = 9;
$x['LABEL'] = 'Ship Via';
$x['VALUE'] = $row['CARVIA'];
$data[] = $x;
$x['ID'] = 9;
$x['LABEL'] = 'F.O.B.';
$x['VALUE'] = $row['FOBCDE'];
$data[] = $x;
$x['ID'] = 9;
$x['LABEL'] = 'U.O.M';
$x['VALUE'] = $row['UMEAS'];
$data[] = $x;
$x['ID'] = 10;
$x['LABEL'] = 'Pricing';
$x['VALUE'] = $row['PRICWT'];
$data[] = $x;
$x['ID'] = 11;
$x['LABEL'] = 'Pricing Code';
$x['VALUE'] =
$data[] = $x;
$row['PRCCDE'];
$x['ID'] = 12;
$x['LABEL'] = 'Discount';
$x['VALUE'] = $row['DSCDEC'];
$data[] = $x;
$x['ID'] = 13;
$x['LABEL'] = 'Total';
$x['VALUE'] = $row['ORDAMT'];
$data[] = $x;
$x['ID'] = 14;
$x['LABEL'] = 'Mfg Info';
$x['VALUE'] = $mfgdata;
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