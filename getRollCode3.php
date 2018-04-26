<?php
require_once '../uphead.php';
if (isset($_GET['OCC'])) $occ  = $_GET['OCC']; else $occ = 10;

$oc = $occ - 1;
$row=array();
$label = array();
$data1 = array();
$data2=array();
$os = 0;
$ttl = 0;
while($os <= $oc){ 
    $rtn = setRow($con, $os);
 //   var_dump($rtn);
$row[] = $rtn;
$label[] = $rtn['MD'];
$data1[] = (float)$rtn['Tons'] ;
if ($os > 0) $ttl += (float)$rtn['Tons'] ;
$os++;
}
$avr = $ttl / $oc;
$os = 0;
while($os <= $oc){ 
$data2[] = round($avr,1);
$os ++;
}

$jTableResult = array();
$jTableResult['Result'] = "OK";
$jTableResult['Records'] = $row;
$jTableResult['Labels'] = $label;
$jTableResult['Data2'] = $data2;
$jTableResult['Data1'] = $data1;
//$jTableResult['TotalRecordCount'] = $row2['CNT'];
print json_encode($jTableResult);



function setRow($con, $offset){
    
    
    
    
  
    $data['RECID'] = $offset;
    
    $d0Y =  date("Y", time() - (86400 * $offset));
    $d0M =  date("m", time() - (86400 * $offset));
    $d0D =  date("d", time() - (86400 * $offset));
    
    $mid = getMid($d0Y, $d0M, $d0D);
    $m = $mid . $d0D;
    $dm = date("m/d", time() - (86400 * $offset));
    
    $data['MD'] = $dm;
    $data['Y'] = $d0Y;
    $data['M'] = $d0M;
    $data['D'] = $d0D;
    $data['MID'] = $m;
    
   $yr = $d0Y - 2000;
    
    $s = "SELECT SUM(ROLS#3) as Rolls, SUM(SHPGRS)as Wgt               
FROM shpMANJ2 WHERE YEAR = '$yr' AND MONTH = 
'$d0M' AND DAY = '$d0D'                              ";
    $r = db2_exec($con, $s);
    $row = db2_fetch_assoc($r);
  
    $data['Rolls'] = number_format($row['ROLLS'],0);
    $data['Tons'] = round($row['WGT']/2000,0);
    

    
    
    return $data;
}
function getMid($y, $m, $d)
{
    $peo = ($y % 2 == true ? 'odd' : 'even');
    // echo " - Year is $peo";
    if ($peo == 'even') {
        switch ($m) {
            
            case "01":
                $mid = "A";
                break;
            case "02":
                $mid = "B";
                break;
            case "03":
                $mid = "C";
                break;
            case "04":
                $mid = "D";
                break;
            case "05":
                $mid = "E";
                break;
            case "06":
                $mid = "F";
                break;
            case "07":
                $mid = "G";
                break;
            case "08":
                $mid = "H";
                break;
            case "09":
                $mid = "I";
                break;
            case "10":
                $mid = "J";
                break;
            case "11":
                $mid = "K";
                break;
            case "12":
                $mid = "L";
                break;
        }
    } else {
        switch ($m) {
            
            case "01":
                $mid = "M";
                break;
            case "02":
                $mid = "N";
                break;
            case "03":
                $mid = "P";
                break;
            case "04":
                $mid = "Q";
                break;
            case "05":
                $mid = "R";
                break;
            case "06":
                $mid = "S";
                break;
            case "07":
                $mid = "T";
                break;
            case "08":
                $mid = "V";
                break;
            case "09":
                $mid = "W";
                break;
            case "10":
                $mid = "X";
                break;
            case "11":
                $mid = "Y";
                break;
            case "12":
                $mid = "Z";
                break;
        }
    }
    
    return $mid;
}



