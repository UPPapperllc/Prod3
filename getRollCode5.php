<?php
require_once '../uphead.php';
if (isset($_GET['OCC'])) $occ  = $_GET['OCC']; else $occ = 10;

$oc = 18;
$row=array();
$label = array();
$data1 = array();
$data2=array();
$os = 16;
$ttl = 0;
while($os <= $oc){ 
    $rtn = setRow($con, $os);
 //   var_dump($rtn);
$row[] = $rtn;

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



function setRow($con, $yr){
    
   
    
    
  
    $data['RECID'] = $yr;
    $data['MD'] = $yr;
    
    $s = "SELECT  sum(INVGRS) as INV, sum(COMAMT) as COMM FROM farlibr/psinvp WHERE IYEAR = $yr and 
IMONTH= 1 "; 
    $r = db2_exec($con, $s);
  //  var_dump($s, db2_stmt_errormsg());
    $row = db2_fetch_assoc($r);
    $data['JanInv'] = number_format($row['INV']);
    $data['JanCom'] = number_format($row['COMM']);
    $s = "SELECT  sum(INVGRS) as INV, sum(COMAMT) as COMM FROM farlibr/psinvp WHERE IYEAR = $yr and
    IMONTH= 2 ";
    $r = db2_exec($con, $s);
    $row = db2_fetch_assoc($r);
    $data['FebInv'] = number_format($row['INV']);
    $data['FebCom'] = number_format($row['COMM']);
    $s = "SELECT  sum(INVGRS) as INV, sum(COMAMT) as COMM FROM farlibr/psinvp WHERE IYEAR = $yr and
    IMONTH= 3 ";
    $r = db2_exec($con, $s);
    $row = db2_fetch_assoc($r);
    $data['MrcInv'] = number_format($row['INV']);
    $data['MrcCom'] = number_format($row['COMM']);
    $s = "SELECT  sum(INVGRS) as INV, sum(COMAMT) as COMM FROM farlibr/psinvp WHERE IYEAR = $yr and
    IMONTH= 4 ";
    $r = db2_exec($con, $s);
    $row = db2_fetch_assoc($r);
    $data['AprInv'] = number_format($row['INV']);
    $data['AprCom'] = number_format($row['COMM']);    
    $s = "SELECT  sum(INVGRS) as INV, sum(COMAMT) as COMM FROM farlibr/psinvp WHERE IYEAR = $yr and
    IMONTH= 5 ";
    $r = db2_exec($con, $s);
    $row = db2_fetch_assoc($r);
    $data['MayInv'] = number_format($row['INV']);
    $data['MayCom'] = number_format($row['COMM']);
    $s = "SELECT  sum(INVGRS) as INV, sum(COMAMT) as COMM FROM farlibr/psinvp WHERE IYEAR = $yr and
    IMONTH= 6 ";
    $r = db2_exec($con, $s);
    $row = db2_fetch_assoc($r);
    $data['JunInv'] = number_format($row['INV']);
    $data['JunCom'] = number_format($row['COMM']);
    $s = "SELECT  sum(INVGRS) as INV, sum(COMAMT) as COMM FROM farlibr/psinvp WHERE IYEAR = $yr and
    IMONTH= 7 ";
    $r = db2_exec($con, $s);
    $row = db2_fetch_assoc($r);
    $data['JulInv'] = number_format($row['INV']);
    $data['JulCom'] = number_format($row['COMM']);
    $s = "SELECT  sum(INVGRS) as INV, sum(COMAMT) as COMM FROM farlibr/psinvp WHERE IYEAR = $yr and
    IMONTH= 8 ";
    $r = db2_exec($con, $s);
    $row = db2_fetch_assoc($r);
    $data['AugInv'] = number_format($row['INV']);
    $data['AugCom'] = number_format($row['COMM']);
    $s = "SELECT  sum(INVGRS) as INV, sum(COMAMT) as COMM FROM farlibr/psinvp WHERE IYEAR = $yr and
    IMONTH= 9 ";
    $r = db2_exec($con, $s);
    $row = db2_fetch_assoc($r);
    $data['SepInv'] = number_format($row['INV']);
    $data['SepCom'] = number_format($row['COMM']);
    $s = "SELECT  sum(INVGRS) as INV, sum(COMAMT) as COMM FROM farlibr/psinvp WHERE IYEAR = $yr and
    IMONTH= 10 ";
    $r = db2_exec($con, $s);
    $row = db2_fetch_assoc($r);
    $data['OctInv'] = number_format($row['INV']);
    $data['OctCom'] = number_format($row['COMM']);
    $s = "SELECT  sum(INVGRS) as INV, sum(COMAMT) as COMM FROM farlibr/psinvp WHERE IYEAR = $yr and
    IMONTH= 11 ";
    $r = db2_exec($con, $s);
    $row = db2_fetch_assoc($r);
    $data['NovInv'] = number_format($row['INV']);
    $data['NovCom'] = number_format($row['COMM']);
    $s = "SELECT  sum(INVGRS) as INV, sum(COMAMT) as COMM FROM farlibr/psinvp WHERE IYEAR = $yr and
    IMONTH= 12 ";
    $r = db2_exec($con, $s);
    $row = db2_fetch_assoc($r);
    $data['DecInv'] = number_format($row['INV']);
    $data['DecCom'] = number_format($row['COMM']);
    
    

    
    
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



